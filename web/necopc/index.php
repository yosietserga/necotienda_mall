<?php
define('PACKAGE','standalone');
define('VERSION','2.0.3');

$config_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
if (!file_exists($config_path . 'cconfig.php')) {
	header('Location: install/index.php');
	exit;
} else {
    require_once($config_path . 'app/admin/config.php');
}

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

// JS
$javascripts = array();
$registry->set('javascripts', $javascripts);

// CSS
$styles = array();
$registry->set('styles', $styles);

// SCRIPTS
$scripts = array();
$registry->set('scripts', $scripts);

//TODO: Generar archivo de configuración txt y si no hay cambios recientes en la tabla, cargar este archivo para ahorrar tiempo y memoria
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}
$response->addHeader('Content-Type: text/html; charset=utf-8');

// Language
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
$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	

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
    $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	return true;
}
	
// Error Handler
//set_error_handler('error_handler');

// Application Map
require_once(DIR_APPLICATION.'map.php');

// Login
$controller->addPreAction(new Action('common/home/login'));

// Permission
$controller->addPreAction(new Action('common/home/permission'));

// Router
if (isset($request->get['r'])) {
	$action = new Action($request->get['r']);
} else {
	$action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

//var_dump($_SESSION);

// Output
$response->output();
