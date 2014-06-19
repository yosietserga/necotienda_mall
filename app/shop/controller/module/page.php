<?php  
class ControllerModulePage extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/page');
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->load->model('content/page');
		$this->data['pages'] = array();

		foreach ($this->modelPage->getAll() as $result) {
      		$this->data['pages'][] = array(
        		'title' => $result['title'],
	    		'href'  => Url::createUrl("content/page",array("page_id"=>$result['post_id']))
      		);
    	}

		$this->data['contact'] = Url::createUrl("page/contact");
    	$this->data['sitemap'] = Url::createUrl("page/sitemap");
		
		$this->id = 'page';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/page.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/page.tpl';
		} else {
			$this->template = 'default/module/page.tpl';
		}
		
		$this->render();
	}
}
