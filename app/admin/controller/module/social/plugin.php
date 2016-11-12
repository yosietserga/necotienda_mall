<?php

/**
 * ControllerModuleSocialPlugin
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSocialPlugin extends Controller {

    private $error = array();

    /**
     * ControllerModuleSocialPlugin::index()
     * 
     * @return
     */
    public function index() {
        $this->load->language('module/social');

        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

        $this->load->auto('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->modelSetting->update('social', $this->request->post);

            $this->session->set('success', $this->language->get('text_success'));

            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('module/social/plugin'));
            } else {
                $this->redirect(Url::createAdminUrl('extension/module'));
            }
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => Url::createAdminUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->document->breadcrumbs[] = array(
            'href' => Url::createAdminUrl('extension/module'),
            'text' => $this->language->get('text_module'),
            'separator' => ' :: '
        );

        $this->document->breadcrumbs[] = array(
            'href' => Url::createAdminUrl('module/social'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['action'] = Url::createAdminUrl('module/social');
        $this->data['cancel'] = Url::createAdminUrl('extension/module');

        $this->data['social_facebook_app_id'] = isset($this->request->post['social_facebook_app_id']) ?
                $this->request->post['social_facebook_app_id'] :
                $this->config->get('social_facebook_app_id');

        $this->data['social_facebook_app_secret'] = isset($this->request->post['social_facebook_app_secret']) ?
                $this->request->post['social_facebook_app_secret'] :
                $this->config->get('social_facebook_app_secret');

        $this->data['social_meli_app_id'] = isset($this->request->post['social_meli_app_id']) ?
                $this->request->post['social_meli_app_id'] :
                $this->config->get('social_meli_app_id');

        $this->data['social_meli_app_secret'] = isset($this->request->post['social_meli_app_secret']) ?
                $this->request->post['social_meli_app_secret'] :
                $this->config->get('social_meli_app_secret');

        $this->data['social_paypal_app_id'] = isset($this->request->post['social_paypal_app_id']) ?
                $this->request->post['social_paypal_app_id'] :
                $this->config->get('social_paypal_app_id');

        $this->data['social_paypal_app_secret'] = isset($this->request->post['social_paypal_app_secret']) ?
                $this->request->post['social_paypal_app_secret'] :
                $this->config->get('social_paypal_app_secret');

        $this->data['social_twitter_consumer_key'] = isset($this->request->post['social_twitter_consumer_key']) ?
                $this->request->post['social_twitter_consumer_key'] :
                $this->config->get('social_twitter_consumer_key');

        $this->data['social_twitter_consumer_secret'] = isset($this->request->post['social_twitter_consumer_secret']) ?
                $this->request->post['social_twitter_consumer_secret'] :
                $this->config->get('social_twitter_consumer_secret');

        $this->data['social_twitter_oauth_token'] = isset($this->request->post['social_twitter_oauth_token']) ?
                $this->request->post['social_twitter_oauth_token'] :
                $this->config->get('social_twitter_oauth_token');

        $this->data['social_twitter_oauth_token_secret'] = isset($this->request->post['social_twitter_oauth_token_secret']) ?
                $this->request->post['social_twitter_oauth_token_secret'] :
                $this->config->get('social_twitter_oauth_token_secret');

        $this->data['social_twitter_consumer_secret'] = isset($this->request->post['social_twitter_consumer_secret']) ?
                $this->request->post['social_twitter_consumer_secret'] :
                $this->config->get('social_twitter_consumer_secret');

        $this->data['social_google_client_id'] = isset($this->request->post['social_google_client_id']) ?
                $this->request->post['social_google_client_id'] :
                $this->config->get('social_google_client_id');

        $this->data['social_google_client_secret'] = isset($this->request->post['social_google_client_secret']) ?
                $this->request->post['social_google_client_secret'] :
                $this->config->get('social_google_client_secret');

        $this->data['social_google_api_key'] = isset($this->request->post['social_google_api_key']) ?
                $this->request->post['social_google_api_key'] :
                $this->config->get('social_google_api_key');

        $this->data['social_google_consumer_key'] = isset($this->request->post['social_google_consumer_key']) ?
                $this->request->post['social_google_consumer_key'] :
                $this->config->get('social_google_consumer_key');

        $this->data['social_google_consumer_secret'] = isset($this->request->post['social_google_consumer_secret']) ?
                $this->request->post['social_google_consumer_secret'] :
                $this->config->get('social_google_consumer_secret');

        $this->data['social_live_client_id'] = isset($this->request->post['social_live_client_id']) ?
                $this->request->post['social_live_client_id'] :
                $this->config->get('social_live_client_id');

        $this->data['social_live_client_secret'] = isset($this->request->post['social_live_client_secret']) ?
                $this->request->post['social_live_client_secret'] :
                $this->config->get('social_live_client_secret');

        $scripts[] = array('id' => 'socialScripts', 'method' => 'ready', 'script' =>
            "$('.vtabs_page').hide();
            $('#tab_facebook').show();");

        $scripts[] = array('id' => 'socialFunctions', 'method' => 'function', 'script' =>
            "function showTab(a) {
                $('.vtabs_page').hide();
                $($(a).data('target')).show();
            }");

        $this->scripts = array_merge($this->scripts, $scripts);

        $this->template = 'module/social/plugin.tpl';
        
        $this->children[] = 'common/header';
        $this->children[] = 'common/footer';
        
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    /**
     * ControllerModuleSocialPlugin::validate()
     * 
     * @return
     */
    private function validate() {
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}
