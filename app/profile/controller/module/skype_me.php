<?php  
class ControllerModuleSkypeMe extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/twitter');

		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->data['skype_me'] = html_entity_decode($settings['skype_me_code']);

		$this->id = 'skype_me';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/skype_me.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/skype_me.tpl';
		} else {
			$this->template = 'default/module/skype_me.tpl';
		}
		$this->render();
	}
}
