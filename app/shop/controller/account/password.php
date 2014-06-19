<?php
class ControllerAccountPassword extends Controller {
	private $error = array();
	     
  	public function index() {	
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect',Url::createUrl("account/password"));

      		$this->redirect(Url::createUrl("account/login"));
    	}

		$this->language->load('account/password');

    	$this->document->title = $this->language->get('heading_title');
			  
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
 
    			$this->load->model('account/customer');
    			
    			$this->modelCustomer->editPassword($this->customer->getEmail(), $this->request->post['password']);
     
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
        	'href'      => Url::createUrl("account/password"),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_password'] = $this->language->get('text_password');

    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');
    	
		if (isset($this->error['password'])) { 
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) { 
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
        
        if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}
	
    	$this->data['action'] = Url::createUrl("account/password");
		
		if (isset($this->request->post['password'])) {
    		$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
        
    	$this->data['back'] = Url::createUrl("account/account");

        // scripts
        $scripts[] = array('id'=>'scriptsEdit','method'=>'ready','script'=>
            "$('#form').ntForm();");
            
        $this->scripts = array_merge($this->scripts,$scripts);
            
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        $javascripts[] = $jspath."necojs/neco.form.js";
        $javascripts[] = $jspath."vendor/jquery-ui.min.js";
        $this->javascripts = array_merge($this->javascripts, $javascripts);

        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        $styles[] = array('media'=>'all','href'=>$csspath.'jquery-ui/jquery-ui.min.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'neco.form.css');
        $this->styles = array_merge($this->styles,$styles);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/password.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/password.tpl';
		} else {
			$this->template = 'default/account/password.tpl';
		}
		
		$this->children = array(
			'common/nav',
			'account/column_left',
			'account/footer',
			'account/header'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));				
  	}
  
  	private function validate() {
        // configuración de requerimientos de la contraseña
        if ($this->config->get('config_password_security')) {
            if (!$this->validar->esPassword($this->request->post['password'])) {
          		$this->error['password'] = $this->language->get('error_password');
        	}
        }    	
        
        if (!$this->validar->longitudMin($this->request->post['password'],6,$this->language->get('entry_password'))) {
      		$this->error['password'] = $this->language->get('error_password');
       	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  
        
        $this->data['mostrarError'] = $this->validar->mostrarError();
	
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
}
