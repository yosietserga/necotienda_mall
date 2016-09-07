<?php
define('CATALOG', 'profile');
define('STORE_ID', 0);

$publictPath    = dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR;
$privatePath    = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$mainPath       = dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;

$protocol       = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http://' : 'https://';
$httpDefaultPath= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] : substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")+1);
$httpPath = str_replace('/index.php',"",$httpDefaultPath);
$httpPath = str_replace('/web',"",$httpPath);

 // HTTP addresses
define('HTTP_HOME',     "http://" . $httpPath . "/");
define('HTTP_IMAGE',    HTTP_HOME . "assets/images/");
define('HTTP_CSS',      HTTP_HOME . "assets/css/");
define('HTTP_JS',       HTTP_HOME . "assets/js/");
define('HTTP_UPLOAD',   HTTP_HOME . "assets/upload/");
define('HTTP_DOWNLOAD', HTTP_HOME . "assets/upload/");
define('HTTP_THEME_CSS', HTTP_HOME . "assets/theme/%theme%/css/");
define('HTTP_THEME_JS', HTTP_HOME . "assets/theme/%theme%/js/");
define('HTTP_THEME_IMAGE', HTTP_HOME . "assets/theme/%theme%/images/");
define('HTTP_THEME_FONT', HTTP_HOME . "assets/theme/%theme%/fonts/");

 // HTTPS addresses
define('HTTPS_HOME',     "https://" . $httpPath . "/");
define('HTTPS_IMAGE',    HTTP_HOME . "assets/images/");
define('HTTPS_CSS',      HTTP_HOME . "assets/css/");
define('HTTPS_JS',       HTTP_HOME . "assets/js/");
define('HTTPS_UPLOAD',   HTTP_HOME . "assets/upload/");
define('HTTPS_DOWNLOAD', HTTP_HOME . "assets/upload/");

define('DIR_APPLICATION',$privatePath);
define('DIR_MODEL',     $privatePath . "model" . DIRECTORY_SEPARATOR);
define('DIR_CONTROLLER',$privatePath . "controller" . DIRECTORY_SEPARATOR);
define('DIR_LANGUAGE',  $privatePath . "language" . DIRECTORY_SEPARATOR);
define('DIR_TEMPLATE',  $privatePath . "view/theme/");

// Shared Files
define('DIR_IMAGE',     $publictPath . "assets/images/");
define('DIR_CSS',       $publictPath . "assets/css/");
define('DIR_JS',        $publictPath . "assets/js/");
define('DIR_UPLOAD',    $publictPath . "assets/upload/");
define('DIR_DOWNLOAD',  $publictPath . "assets/upload/");
define('DIR_THEME_CSS', $publictPath . "assets/theme/%theme%/css/");
define('DIR_THEME_JS',  $publictPath . "assets/theme/%theme%/js/");
define('DIR_THEME_IMAGE', $publictPath . "assets/theme/%theme%/image/");

// System files
define('DIR_SYSTEM',    $mainPath . "system" . DIRECTORY_SEPARATOR);
define('DIR_DATABASE',  DIR_SYSTEM . 'database/');
define('DIR_CONFIG',    DIR_SYSTEM . 'config/');
define('DIR_CACHE',     DIR_SYSTEM . 'cache/');
define('DIR_LOGS',      DIR_SYSTEM . 'logs' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR);