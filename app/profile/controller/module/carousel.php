<?php  
class ControllerModuleCarousel extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		}
        
		$this->id = 'carousel';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/carousel.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/carousel.tpl';
        } else {
			$this->template = 'default/module/carousel.tpl';
		}
		$this->render();
	}
}
