<?php
/*
 * jQuery File Upload Plugin PHP Class 5.9.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

require_once('xhttp/xhttp.php');
require_once('pclzip.php');

/**
 * Update
 * 
 * @package NecoTienda Standalone
 * @author Yosiet Serga
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Update
{
    protected $db;
    protected $load;
    protected $handler;
    public $update_info = "";
    /**
     * Update::__construct()
     * 
     * @param mixed $registry
     * @return
     */
    function __construct($registry) {
        $this->db = $registry->get('db');
        $this->load = $registry->get('load');
        
        $this->update_info = "http://www.necotienda.com/index.php?r=update/info&p=" . PACKAGE;
        
        if (in_array('curl', get_loaded_extensions())) {
            $this->handler = new xhttp;
        } else {
            $this->handler = new UpdateClass;
        }
    }
    
    /**
     * Update::run()
     * 
     * @return
     */
    public function run() {
        $info = $this->getInfo();
        
        if (version_compare(VERSION,$info['version'],'<')) {
            $response = $this->handler->fetch($info['url_update']);
            if (isset($response['body']) && $response['successful']) {
                $file_update = $response['body'];
            } else {
                $file_update = $response;
            }
            
            $file_saved = DIR_ROOT . "update.zip";
            
            $f = fopen($file_saved,'wb');
            fwrite($f,$file_update);
            fclose($f);
            
            if (file_exists($file_saved) && sha1_file($file_saved) == $info['checksum']) {
                $zip = new PclZip();
                $zip->setZipName($file_saved);
                if ($zip->extract(PCLZIP_OPT_PATH,DIR_ROOT,PCLZIP_OPT_REPLACE_NEWER) > 0) {
                    unlink($file_saved);
                } else {
                    return $zip->errorInfo(true);
                }
            } else {
                return false;
            }
        } else {
            //TODO: Ya esta actualizada
        }
    }
    
    /**
     * Update::info()
     * 
     * array(
     *  'description'   =>$description,     // html text
     *  'changelog'     =>$changelog,       // text
     *  'version'       =>$version,         // string version
     *  'files_to_install'=>array(),        // array filenames to install
     *  'files_to_update'=>array(),         // array filenames to update
     *  'files_to_remove'=>array(),         // array filenames to delete
     *  'checksum'=>$checksum,              // string file checksum
     *  'url_update'=>$url_update,          // string file url for update
     * );
     * 
     * @return array update info
     */
    public function getInfo() {
        $file_info = $this->handler->fetch($this->update_info);
        
        if (isset($file_info['body'])) {
            return unserialize($file_info['body']);
        } else {
            return unserialize($file_info);
        }
    }
}

class UpdateClass 
{
    /**
     * UpdateClass::file_exists()
     * 
     * @param mixed $url
     * @return
     */
    public function file_exists($url) {
        if (fopen($url, 'r')) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * UpdateClass::fetch()
     * 
     * @param mixed $url
     * @param mixed $requestData
     * @return
     */
    public function fetch($url,$requestData=array()) {
        return file_get_contents($url);
    }
}