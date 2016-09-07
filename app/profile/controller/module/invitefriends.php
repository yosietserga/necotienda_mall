<?php  
class ControllerModuleInviteFriends extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/invitefriends');
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
        $this->data['live_client_id'] = $this->config->get('social_live_client_id');
        $this->data['google_client_id'] = $this->config->get('social_google_client_id');
        $this->data['facebook_app_id']  = $this->config->get('social_facebook_app_id');
        $this->data['twitter_oauth_token_secret'] = $this->config->get('social_twitter_oauth_token_secret');
        
		$this->id = 'invitefriends';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/invitefriends.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/invitefriends.tpl';
		} else {
			$this->template = 'default/module/invitefriends.tpl';
		}
		$this->render();
  	}
}