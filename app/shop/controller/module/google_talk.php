<?php  
class ControllerModuleGoogleTalk extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/google_talk');

		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->data['code'] = html_entity_decode($settings['google_talk_code']);
		
		$this->id = 'google_talk';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/google_talk.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/google_talk.tpl';
		} else {
			$this->template = 'default/module/google_talk.tpl';
		}
		
		$this->render();
	}
}
