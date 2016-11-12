<?php  
class ControllerModulePlainText extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
        
		$this->id = 'plaintext';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/plaintext.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/plaintext.tpl';
		} else {
			$this->template = 'default/module/plaintext.tpl';
			
		}
		
		$this->render();
	}
}
