<?php 
class ControllerToolErrorLog extends Controller { 
	private $error = array();
	
	public function index() {		
		$this->load->language('tool/error_log');

		$this->document->title = $this->language->get('heading_title');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_clear'] = $this->language->get('button_clear');
		
		$this->data['tab_general'] = $this->language->get('tab_general');

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('tool/error_log'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['clear'] = Url::createAdminUrl('tool/error_log/clear');
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, NULL);
		} else {
			$this->data['log'] = '';
		}
		 
		$this->template = 'tool/error_log.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	public function clear() {
		$this->load->language('tool/error_log');
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		$handle = fopen($file, 'w+'); 
				
		fclose($handle); 			
		
		$this->session->set('success',$this->language->get('text_success'));
		
		$this->redirect(Url::createAdminUrl('tool/error_log'));		
	}
}
