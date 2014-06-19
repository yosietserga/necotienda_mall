<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
	   if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect',Url::createUrl("account/account"));
	  		$this->redirect(Url::createUrl("account/login"));
    	}
	
		$this->language->load('account/account');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/account"),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

		if ($this->session->has('success')) {
    		$this->data['success'] = $this->session->get('success');
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/account.tpl';
		} else {
			$this->template = 'default/account/account.tpl';
		}
        
		$this->children[] = 'common/nav';
		$this->children[] = 'account/column_left';
		$this->children[] = 'account/footer';
		$this->children[] = 'account/header';

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}
}
