<?php   
class ControllerCommonHeader2 extends Controller {
	protected function index() {
		$this->language->load('common/header');
		$this->data['title'] = $this->document->title;
		$this->data['keywords'] = $this->document->keywords;
		$this->data['description'] = $this->document->description;
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTP_HOME;
		} else {
			$this->data['base'] = HTTP_HOME;
		}
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
        
		$this->id = 'header';
		$this->template = 'common/header2.tpl';
		
    	$this->render();
	}
}