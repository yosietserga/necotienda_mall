<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "cconfig.php");
define('ADMIN_PATH','admin');

$defaultPath    = dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
$adminPath      = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$systemPath     = $defaultPath . "system" . DIRECTORY_SEPARATOR;
$shopPath       = $adminPath . ".." . DIRECTORY_SEPARATOR . "shop" . DIRECTORY_SEPARATOR;

$protocol  = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http://' : 'https://';
$httpAdminPath = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] : substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")+1);
$httppath = $httpAdminPath = str_replace('/index.php',"",$httpAdminPath);
$httppath = substr($httppath,0,strrpos($httppath,"/",1));
$httppath = str_replace("/web","",$httppath);


define('HTTP_HOME',   $protocol.$httpAdminPath . "/");
define('HTTP_CATALOG',  $protocol.$httppath . "/");
define('HTTP_IMAGE',    $protocol.$httppath."/assets/images/");
define('HTTP_UPLOAD',  $protocol.$httppath."/upload/");
define('HTTP_EMAIL_TPL_IMAGE', HTTP_HOME . "email_templates/");
define('HTTP_CSS', $protocol.$httppath."/assets/css/");
define('HTTP_TPL_IMAGE', HTTP_HOME . "view/image/");
define('HTTP_JS', $protocol.$httppath."/assets/js/");
define('HTTP_TPL', HTTP_HOME . "view/template/");

define('HTTP_ADMIN_CSS', $protocol.$httppath."/". ADMIN_PATH ."/css/");
define('HTTP_ADMIN_JS', $protocol.$httppath."/". ADMIN_PATH ."/js/");
define('HTTP_ADMIN_IMAGE', $protocol.$httppath."/". ADMIN_PATH ."/image/");

// DIR
define('CATALOG', 'shop');
define('JS', '/view/theme/%theme%/javascript/');
define('CSS', '/view/theme/%theme%/css/');

// Admin system
define('DIR_ROOT',          $defaultPath);
define('DIR_APPLICATION',   $adminPath);
define('DIR_LANGUAGE',      DIR_APPLICATION . "language" . DIRECTORY_SEPARATOR);
define('DIR_TEMPLATE',      DIR_APPLICATION . "view/template" . DIRECTORY_SEPARATOR);
define('DIR_EMAIL_TEMPLATE',$defaultPath . "web/". ADMIN_PATH ."/email_templates" . DIRECTORY_SEPARATOR);

// Core System
define('DIR_BACKUP',    $defaultPath . "backups/");
define('DIR_SYSTEM',    $systemPath);
define('DIR_DATABASE',  DIR_SYSTEM.'database' . DIRECTORY_SEPARATOR);
define('DIR_CONFIG',    DIR_SYSTEM.'config' . DIRECTORY_SEPARATOR);
define('DIR_CACHE',     DIR_SYSTEM.'cache' . DIRECTORY_SEPARATOR);
define('DIR_LOGS',      DIR_SYSTEM.'logs' . DIRECTORY_SEPARATOR);

// Catalog System
define('DIR_IMAGE',     $defaultPath . "web/assets/images/");
define('DIR_CSS',       $defaultPath . "web/assets/css/");
define('DIR_JS',        $defaultPath . "web/assets/js/");
define('DIR_UPLOAD',    $defaultPath . "web/upload/");
define('DIR_DOWNLOAD',  $defaultPath . "web/download/");
define('DIR_THEME_ASSETS',       $defaultPath . "web/assets/theme/");
define('DIR_CATALOG',   $shopPath);