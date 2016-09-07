<?php  
class ControllerModuleStoreLogo extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/store_logo.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/store_logo.tpl';
		} else {
            $this->template = 'default/module/store_logo.tpl';
		}
		$this->render();
	}
}
