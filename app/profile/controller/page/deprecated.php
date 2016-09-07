<?php 
class ControllerPageDeprecated extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('page/deprecated');
    	$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');  
      	$this->document->breadcrumbs = array();
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("page/deprecated"),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/page/deprecated.tpl')) {
			$this->template = $this->config->get('config_template') . '/page/deprecated.tpl';
		} else {
			$this->template = 'default/page/deprecated.tpl';
		}
		
		$this->children[] = 'common/header2';
		$this->children[] = 'common/footer2';
            
 		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}
}