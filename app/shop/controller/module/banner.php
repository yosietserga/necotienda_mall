<?php  
class ControllerModuleBanner extends Controller {
	protected function index($widget=null) {
		$this->language->load('module/banner');
        
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
        if ((int)$settings['banner_id']) {
            $this->data['Image'] = new NTImage;
            $this->load->model('content/banner');
            $this->data['banner'] = $this->modelBanner->getById($settings['banner_id']);
            
            if (!empty($this->data['banner']['jquery_plugin'])) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/banner/'. $this->data['banner']['jquery_plugin'] .'.tpl')) {
        			$this->template = $this->config->get('config_template') . '/banner/'. $this->data['banner']['jquery_plugin'] .'.tpl';
        		} else {
                    $this->template = 'default/banner/nivo-slider.tpl';
        		}
            } else {
                $this->template = 'default/banner/nivo-slider.tpl';
            }
            
    		$this->id = 'banner';
    		$this->render();
        }
	}
}
