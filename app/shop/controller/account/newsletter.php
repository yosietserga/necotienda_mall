<?php 
class ControllerAccountNewsletter extends Controller {  
	public function index() {
        if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect',Url::createUrl("account/newsletter"));
	  
	  		$this->redirect(Url::createUrl("account/login"));
    	}
		
		$this->language->load('account/newsletter');
    	
		$this->document->title = $this->language->get('heading_title');
		    
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {       
        			$this->load->model('account/customer');        			
        			$this->modelCustomer->editNewsletter($this->request->post['newsletter']);        			
        			$this->session->set('success',$this->language->get('text_success'));        			
        			$this->redirect(Url::createUrl("account/account"));
        }

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
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/newsletter"),
        	'text'      => $this->language->get('text_newsletter'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

    	$this->data['action'] = Url::createUrl("account/newsletter");
		
		$this->data['newsletter'] = $this->customer->getNewsletter();
		
		$this->data['back'] = Url::createUrl("account/account");
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/newsletter.tpl';
		} else {
			$this->template = 'default/account/newsletter.tpl';
		}
		
		$this->children = array(
			'common/nav',
			'common/column_left',
			'account/footer',
			'account/header'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));			
  	}
}
