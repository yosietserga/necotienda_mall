<?php
error_reporting(0);
define('PACKAGE','standalone');
define('VERSION','2.0.2');

$matches = array();
$config_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
if (!file_exists($config_path . 'cconfig.php')) {
    $protocol       = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http://' : 'https://';
    $httpDefaultPath= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] : substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")+1);
    $httpPath = str_replace('/index.php',"",$httpDefaultPath);
    $httpPath = str_replace('/web/',"",$httpPath);
	header('Location: '. $protocol . $httpPath .'/install/index.php');
	exit;
} else {
    require_once($config_path . 'cconfig.php');
    require_once($config_path . 'app/profile/config.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

$registry   = new Registry();
$loader     = new Loader($registry);
$config     = new Config();
$db         = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$log        = new Log($config->get('config_error_filename'));
$request    = new Request();
$response   = new Response();
$controller = new Front($registry);
$session    = new Session();
   
// llave para utilizar en los formularios y evitar ataques csrf
if (!$session->has('fkey')) {
    $i = 0;
    $super_rand = "";
    while ($i <= 10) {
        $super_rand .= md5(mt_rand(1000000000,9999999999));
        $i++;
    }
    $session->set('fkey',md5($_SERVER['REMOTE_ADDR']) . "." . $super_rand."_".strtotime(date('d-m-Y'))); 
    $registry->set('fkey', $session->get('fkey'));
} else {
    $registry->set('fkey', $session->get('fkey'));
}
    
// Settings
if (!$session->has('ntConfig_'.(int)STORE_ID)) {
    $query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '". (int)STORE_ID ."'");
    foreach ($query->rows as $setting) {
        $config->set($setting['key'], $setting['value']);
    }
} else {
    $config = unserialize($session->get('ntConfig_'.(int)STORE_ID)); 
}
$config->set('config_store_id',STORE_ID);


$response->addHeader('Content-Type: text/html; charset=utf-8');

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = array(
		'language_id' => $result['language_id'],
		'name'        => $result['name'],
		'code'        => $result['code'],
		'locale'      => $result['locale'],
		'directory'   => $result['directory'],
		'filename'    => $result['filename']
	);
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE'])) { 
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
	
	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			$locale = explode(',', $value['locale']);

			if (in_array($browser_language, $locale)) {
				$detect = $key;
			}
		}
	}
}

if (isset($_GET['language']) && array_key_exists($_GET['language'], $languages)) {
	$code = $_GET['language'];
} elseif ($session->has('language') && array_key_exists($session->get('language'), $languages)) {
	$code = $session->get('language');
} elseif (isset($request->cookie[C_CODE."_".'language']) && array_key_exists($request->cookie[C_CODE."_".'language'], $languages)) {
	$code = $request->cookie[C_CODE."_".'language'];
} elseif ($detect) {
	$code = $detect;
} else {
	$code = $config->get('config_language');
}

if (!$session->has('language') || $session->get('language') != $code) {
	$session->set('language',$code);
}

if (!isset($request->cookie[C_CODE."_".'language']) || $request->cookie[C_CODE."_".'language'] != $code) {	  
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}			

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language		
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}
		
    echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b></br >';
    //$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	return true;
}
	
// Error Handler
//set_error_handler('error_handler');

// App Libs and Configs Preload
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app/profile/map.php');
$session->set("config", serialize($config));

// Template Preview
if (!empty($_GET['template']) && file_exists(DIR_TEMPLATE . $_GET['template'] . '/common/header.tpl')) {
    $config->set('config_template',$_GET['template']);
}

$session->set('ntconfig', serialize($config));

// Front Controller 
$controller = new Front($registry);

// Maintenance Mode
$controller->addPreAction(new Action('common/maintenance/check'));

// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));
// Router
if (isset($request->get['r'])) {
    if (!isset($controller->ClassName)) $controller->ClassName = $request->get['r'];
	$action = new Action($request->get['r']);
} else {
	$action = new Action('account/profile');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();