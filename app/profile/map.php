<?php
$loader->auto('url');
$loader->auto('user');
$loader->auto('customer');
$loader->auto('currency');
$loader->auto('tax');
$loader->auto('weight');
$loader->auto('length');
$loader->auto('cart');
$loader->auto('validar');
$loader->auto('recaptcha');
$loader->auto('encoder');

$registry->set('load', $loader);
$registry->set('config', $config);
$registry->set('db', $db);
$registry->set('log', $log);
$registry->set('request', $request);
$registry->set('response', $response); 
$registry->set('session', $session);
$registry->set('cache', new Cache());
$registry->set('document', new Document());
$registry->set('language', $language);
$registry->set('user', new User($registry));

// App Libs
$registry->set('customer', new Customer($registry));
$registry->set('currency', new Currency($registry));
$registry->set('tax', new Tax($registry));
$registry->set('weight', new Weight($registry));
$registry->set('length', new Length($registry));
$registry->set('cart', new Cart($registry));
$registry->set('javascripts', array());
$registry->set('styles', array());
$registry->set('scripts', array());

// Default
$language->load('common/header');

$loader->auto('account/customer');
$loader->auto('store/product');
$loader->auto('store/category');
 //TODO: redise�ar clase de URLs
$loader->auto('localisation/language');
$loader->auto('localisation/currency');

// Email Libs
//$mailer  = new Mailer();
//$email_f = new email($mailer,$config);
//$registry->set('smtp', new SMTP());
//$registry->set('mailer', $mailer);
//$registry->set('email_f', $email_f);
//$registry->set('utf8', new utf8());
$registry->set('encoder', new Encoder());
$registry->set('validar', new Validar());

$route = isset($request->get['_r_']) ? strtolower($request->get['_r_']) : strtolower($request->get['r']);
switch ($route) {
    case 'common/home':
    default:
        //Languages
        $language->load('common/home');
        
        //Models
		$loader->auto('checkout/extension');
        break;
}