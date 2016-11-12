<?php    
/**
 * ControllerSaleEmailAccount
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerSaleEmailAccount extends Controller { 
	private $error = array();
    private $ip = "localhost";
    private $user = "andycorp";
    private $password = "PevrX01uRkxzxbPYXGEP";
    private $domain = "andycorporacion.com";
    private $port =2083;                 // cpanel secure authentication port unsecure port# 2082
    private $quota = 25; // default amount of space in megabytes
  	/**
  	 * ControllerSaleEmailAccount::index()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see getList
  	 * @return void 
  	 */
  	public function index() {
		$this->load->language('sale/emailaccount');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->library('cpxmlapi');
        
        $cp = new xmlapi($this->ip);
		
            $cp->set_port($this->port);  //set port number. cpanel client class allow you to access WHM as well using WHM port.
    
            $cp->password_auth($this->user, $this->password);   // authorization with password. not as secure as hash.
    
            // cpanel email addpop function Parameters
            $call = array('domain'=>$this->domain, 'email'=>$this->request->post['email'], 'password'=>$this->request->post['password'], 'quota'=>$this->quota);
    
            $cp->set_debug(0);      //output to error file  set to 1 to see error_log.
    
            $result = $cp->api2_query($this->user, "Email", "listpopswithdisk" ); // making call to cpanel api
            
            $this->data['accounts'] = $result;
    	$this->getList();
  	}
    
  	/**
  	 * ControllerSaleEmailAccount::insert()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Redirect
     * @see getForm
  	 * @return void 
  	 */
  	public function insert() {
		$this->load->language('sale/emailaccount');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->library('cpxmlapi');
			
        $cp = new xmlapi($this->ip);
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') /* && $this->validateForm() */) {
			
		  
            $cp->set_port($this->port);  //set port number. cpanel client class allow you to access WHM as well using WHM port.
    
            $cp->password_auth($this->user, $this->password);   // authorization with password. not as secure as hash.
    
            // cpanel email addpop function Parameters
            $call = array('domain'=>$this->domain, 'email'=>$this->request->post['email'], 'password'=>$this->request->post['password'], 'quota'=>$this->quota);
    
            $cp->set_debug(0);      //output to error file  set to 1 to see error_log.
    
            $result = $cp->api2_query($this->user, "Email", "addpop", $call ); // making call to cpanel api
    
            if ($result->data->result == 1){
                $this->session->data['success'] = " La cuenta " . $this->request->post['email'] .'@'. $this->domain.' ha sido creada con &eacute;xito';
            } else {
                $this->session->data['error'] = "No se pudo crear la cuenta de correo: " . $result->data->reason;
            }
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/emailaccount&token=' . $this->session->data['token'] . $url);
		}
        
    	

    	$this->getForm();
  	} 

  	/**
  	 * ControllerSaleEmailAccount::delete()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Redirect
     * @see getList
  	 * @return void 
  	 */
  	public function delete() {
		
		$this->load->library('cpxmlapi');
        
        $cp = new xmlapi($this->ip);
			
    	if (isset($this->request->post['selected']) /* && $this->validateDelete() */) {
    	   
                $cp->set_port($this->port);  //set port number. cpanel client class allow you to access WHM as well using WHM port.
        
                $cp->password_auth($this->user, $this->password);   // authorization with password. not as secure as hash.
        
                $cp->set_debug(0);      //output to error file  set to 1 to see error_log.
                
    			foreach ($this->request->post['selected'] as $user) {
    				
                    // cpanel email addpop function Parameters
                    $call = array('domain'=>$this->domain, 'email'=>$user);
                    
                    $result = $cp->api2_query($this->user, "Email", "delpop", $call ); // making call to cpanel api
            
    			}
			
                if ($result->data->result == 1){
                    $this->session->data['success'] = " La(s) cuenta(s) han sido eliminada(s) con &eacute;xito";
                } else {
                    $this->session->data['error'] = "No se pudo eliminar la cuenta de correo: " . $result->data->reason;
                }
                
    	}
    
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/emailaccount&token=' . $this->session->data['token'] . $url);
  	}  
    
  	/**
  	 * ControllerSaleEmailAccount::getById()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Response
     * @see Request     
  	 * @return void 
  	 */
  	private function getList() {
  	 
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/emailaccount&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/emailaccount/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/emailaccount/delete&token=' . $this->session->data['token'] . $url;
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->template = 'sale/emailaccount_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
    //TODO: colocar controles para exportar a pdf
  	/**
  	 * ControllerSaleEmailAccount::getForm()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Response
     * @see Request     
  	 * @return void 
  	 */
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	
    	$this->data['help_email'] = $this->language->get('help_email');
    	$this->data['help_password'] = $this->language->get('help_password');
    	$this->data['help_confirm'] = $this->language->get('help_confirm');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add'] = $this->language->get('button_add');
    	$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
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
		

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/emailaccount&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
        
        $this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/emailaccount/insert&token=' . $this->session->data['token'] . $url;
		
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/emailaccount&token=' . $this->session->data['token'] . $url;

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} else {
      		$this->data['email'] = '';
    	}
		
		
		$this->template = 'sale/emailaccount_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
  	/**
  	 * ControllerSaleEmailAccount::validateForm()
  	 * 
  	 * @return
  	 */
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/emailaccount')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		$pattern = '/^[A-Z0-9._%-]$/i';
    	
		if ((strlen(utf8_decode($this->request->post['email']))> 96) || (!preg_match($pattern, $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if (($this->request->post['password']) || (!isset($this->request->get['customer_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password']))> 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	/**
  	 * ControllerSaleEmailAccount::validateDelete()
  	 * 
  	 * @return
  	 */
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/emailaccount')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	} 	
}
?>
