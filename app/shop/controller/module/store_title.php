<?php  
class ControllerModuleStoreTitle extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
        $this->data['heading_title'] = $this->config->get('config_title_'.$this->config->get('config_language_id'));
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/store_title.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/store_title.tpl';
		} else {
            $this->template = 'default/module/store_title.tpl';
		}
		$this->render();
	}
}
