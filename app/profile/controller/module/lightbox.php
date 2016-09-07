<?php  
class ControllerModuleLightBox extends Controller {
	protected function index($widget=null) {
	    if (!$this->session->has($widget['name'])) {
            if (isset($widget)) {
                $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
                $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
            }
            
    		if (isset($settings['title'])) {
                $this->data['heading_title'] = $settings['title'];
    		} else {
                $this->data['heading_title'] = $this->language->get('heading_title');
    		}
            
            if ((int)$settings['page_id']) {
                $this->load->model('content/page');
                $this->data['page'] = $this->modelPage->getById($settings['page_id']);
            }
            
    		$this->id = 'lightbox';
    
    		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/lightbox.tpl')) {
    		  $this->template = $this->config->get('config_template') . '/module/lightbox.tpl';
    		} else {
    		  $this->template = 'default/module/lightbox.tpl';
    		}
    		$this->session->set($widget['name'],true);
    		$this->render();
        }
	}
}