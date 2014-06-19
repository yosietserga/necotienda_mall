<?php
/**
 * Backup
 * 
 * @package NecoTienda Standalone
 * @author Yosiet Serga
 * @copyright NecoTienda
 * @version 2012
 * @access public
 */
class Backup
{
    protected $db;
    protected $load;
    
    public $compressedData = array();
    public $centralDirectory = array(); // central directory
    public $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
    public $oldOffset = 0;
    
    public $backup_dir = "";
    
    function __construct($registry) {
        $this->db = $registry->get('db');
        $this->load = $registry->get('load');
        
        $this->backup_dir = DIR_BACKUP . date('dmYhis');
        if (!is_dir($this->backup_dir)) {
            mkdir($this->backup_dir,0777);
        }
    }

    public function getPaths($path=null,$ignore=null) {
        if (!isset($path)) {
            return array();
        }
        
        $folders = array();
        $directories = glob($path . '*', GLOB_ONLYDIR);
        if ($directories) {
            foreach ($directories as $directory) {
                $basepath = str_replace(DIR_ROOT, "", $directory);
                if (isset($ignore) && in_array(basename($directory),$ignore)) continue;
    			$folders[$basepath] = $path . basename($directory);
                
                $path2 = str_replace("//","/", $path . '/' . basename($directory) . '/');
                $directories2 = glob($path2 . '*', GLOB_ONLYDIR);
                if ($directories2) {
                    foreach ($directories2 as $directory2) {
                        $basepath2 = str_replace(DIR_ROOT, "", $directory2);
            			$folders[$basepath2] = $path2 . basename($directory2);
                        $folders = array_merge($folders,$this->getPaths($path2));
            		}
                }
    		}
        }
        return $folders;
    }
    
    public function getFiles ($folders,$extension = "{php}") {
        if ($folders) {
            foreach ($folders as $basepath => $folder) {
                if (is_array($folder)) {
                    $this->getFiles($folder,$extension);
                } else {
                    $this->addDirectory($basepath);
                    $files = glob($folder . '/*.' . $extension,GLOB_BRACE);
                    if ($files) {
                        foreach ($files as $file) {
                            $filepath = str_replace(DIR_ROOT, "", $directory);
                            if (file_exists($file)) {
                                $fileContents = file_get_contents($file);
                                $this->addFile($fileContents,$basepath ."/". basename($file));
                            }
                		}
                    }
                }
            }
        }
    }
    
    public function getAppPaths() {
        return $this->getPaths(DIR_ROOT . 'app/');
    }
    
    public function getSystemPaths() {
        $ignore = array('logs','cache');
        return $this->getPaths(DIR_ROOT . 'system/',$ignore);
    }
    
    public function getPublicPaths() {
        $ignore = array('images');
        return $this->getPaths(DIR_ROOT . 'web/',$ignore);
    }
    
    public function run($send = false) {
        $appFolders     = $this->getAppPaths();
        $systemFolders  = $this->getSystemPaths();
        
        $this->getFiles($appFolders);
        $this->getFiles($systemFolders);
        //$this->getFiles($this->getPublicPaths());
        //$this->getFiles($this->getPaths(DIR_ROOT . 'web/admin/',array('email_templates')),'{css,js,php,htaccess}');
        //$this->getFiles($this->getPaths(DIR_ROOT . 'web/assets/'),'{css,js}');
        //$this->getFiles($this->getPaths(DIR_ROOT . 'web/assets/css/'),'{jpg,png,gif}');
        
        
        $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");
		
		foreach ($query->rows as $result) {
			$tables[] = $result['Tables_in_' . DB_DATABASE];
		}
        
		$output = '';
        
		foreach ($tables as $table) {
			if (DB_PREFIX) {
				if (strpos($table, DB_PREFIX) === false) {
					$status = false;
				} else {
					$status = true;
				}
			} else {
				$status = true;
			}
			
			if ($status) {
                $this->db->query('LOCK TABLES `' . $table . '` WRITE');
                $q = $this->db->query('SHOW CREATE TABLE `' . $table . '`');
                $output .= str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$q->row['Create Table']) . "\n\n";
				$output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
				$query = $this->db->query("SELECT * FROM `" . $table . "`");
				foreach ($query->rows as $result) {
					$fields = '';
					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}
					$values = '';
					foreach (array_values($result) as $value) {
						$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
						$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
						$value = str_replace('\\', '\\\\',	$value);
						$value = str_replace('\'', '\\\'',	$value);
						$value = str_replace('\\\n', '\n',	$value);
						$value = str_replace('\\\r', '\r',	$value);
						$value = str_replace('\\\t', '\t',	$value);			
						
						$values .= '\'' . $value . '\', ';
					}
					$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
				}
				$output .= "\n\n";
                $this->db->query('UNLOCK TABLES');
			}
		}
        
        $this->addFile($output,basename($this->backup_dir . "/backup.sql"));
        
        $fd = fopen ($this->backup_dir . "/backup.zip", "wb");
        fwrite ($fd, $this->getZippedfile());
        fclose ($fd);
        
        $checksum = sha1_file($this->backup_dir . "/backup.zip");
        $fd = fopen ($this->backup_dir . "/CKECKSUM", "w");
        fwrite ($fd, $checksum);
        fclose ($fd);
        
        if ($send) {
            $this->load->library('email/mailer');
            $mailer = new Mailer();
            
        }
    }
    
    /**
     * Backup::addDirectory()
     * 
     * Create the directory where the file(s) will be unzipped
     * 
     * @param string $directoryName
     * @return
     */
    public function addDirectory($directoryName)
    {
        $directoryName = str_replace("\\", "/", $directoryName);
        $directoryName = str_replace("//", "/", $directoryName . "/");

        $feedArrayRow = "\x50\x4b\x03\x04";
        $feedArrayRow .= "\x0a\x00";
        $feedArrayRow .= "\x00\x00";
        $feedArrayRow .= "\x00\x00";
        $feedArrayRow .= "\x00\x00\x00\x00";

        $feedArrayRow .= pack("V", 0);
        $feedArrayRow .= pack("V", 0);
        $feedArrayRow .= pack("V", 0);
        $feedArrayRow .= pack("v", strlen($directoryName));
        $feedArrayRow .= pack("v", 0);
        $feedArrayRow .= $directoryName;

        $feedArrayRow .= pack("V", 0);
        $feedArrayRow .= pack("V", 0);
        $feedArrayRow .= pack("V", 0);

        $this->compressedData[] = $feedArrayRow;

        $newOffset = strlen(implode("", $this->compressedData));

        $addCentralRecord = "\x50\x4b\x01\x02";
        $addCentralRecord .= "\x00\x00";
        $addCentralRecord .= "\x0a\x00";
        $addCentralRecord .= "\x00\x00";
        $addCentralRecord .= "\x00\x00";
        $addCentralRecord .= "\x00\x00\x00\x00";
        $addCentralRecord .= pack("V", 0);
        $addCentralRecord .= pack("V", 0);
        $addCentralRecord .= pack("V", 0);
        $addCentralRecord .= pack("v", strlen($directoryName));
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("v", 0);
        $ext = "\x00\x00\x10\x00";
        $ext = "\xff\xff\xff\xff";
        $addCentralRecord .= pack("V", 16);

        $addCentralRecord .= pack("V", $this->oldOffset);
        $this->oldOffset = $newOffset;

        $addCentralRecord .= $directoryName;

        $this->centralDirectory[] = $addCentralRecord;
    }

    /**
     * Backup::addFile()
     * 
     * Function to add file(s) to the specified directory in the archive
     * 
     * @param mixed $data
     * @param string $directoryName
     * @return
     */
    public function addFile($data, $directoryName)
    {
        $directoryName = str_replace("\\", "/", $directoryName);

        $feedArrayRow = "\x50\x4b\x03\x04";
        $feedArrayRow .= "\x14\x00";
        $feedArrayRow .= "\x00\x00";
        $feedArrayRow .= "\x08\x00";
        $feedArrayRow .= "\x00\x00\x00\x00";

        $uncompressedLength = strlen($data);
        $compression = crc32($data);
        $gzCompressedData = gzcompress($data);
        $gzCompressedData = substr(substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2);
        $compressedLength = strlen($gzCompressedData);
        $feedArrayRow .= pack("V", $compression);
        $feedArrayRow .= pack("V", $compressedLength);
        $feedArrayRow .= pack("V", $uncompressedLength);
        $feedArrayRow .= pack("v", strlen($directoryName));
        $feedArrayRow .= pack("v", 0);
        $feedArrayRow .= $directoryName;

        $feedArrayRow .= $gzCompressedData;

        $feedArrayRow .= pack("V", $compression);
        $feedArrayRow .= pack("V", $compressedLength);
        $feedArrayRow .= pack("V", $uncompressedLength);

        $this->compressedData[] = $feedArrayRow;

        $newOffset = strlen(implode("", $this->compressedData));

        $addCentralRecord = "\x50\x4b\x01\x02";
        $addCentralRecord .= "\x00\x00";
        $addCentralRecord .= "\x14\x00";
        $addCentralRecord .= "\x00\x00";
        $addCentralRecord .= "\x08\x00";
        $addCentralRecord .= "\x00\x00\x00\x00";
        $addCentralRecord .= pack("V", $compression);
        $addCentralRecord .= pack("V", $compressedLength);
        $addCentralRecord .= pack("V", $uncompressedLength);
        $addCentralRecord .= pack("v", strlen($directoryName));
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("v", 0);
        $addCentralRecord .= pack("V", 32);

        $addCentralRecord .= pack("V", $this->oldOffset);
        $this->oldOffset = $newOffset;

        $addCentralRecord .= $directoryName;

        $this->centralDirectory[] = $addCentralRecord;
    }

    /**
     * Backup::getZippedfile()
     * 
     * Fucntion to return the zip file
     * 
     * @return zipfile (archive)
     */
    public function getZippedfile()
    {

        $data = implode("", $this->compressedData);
        $controlDirectory = implode("", $this->centralDirectory);

        return $data . $controlDirectory . $this->endOfCentralDirectory . pack("v",
            sizeof($this->centralDirectory)) . pack("v", sizeof($this->centralDirectory)) .
            pack("V", strlen($controlDirectory)) . pack("V", strlen($data)) . "\x00\x00";
    }
}