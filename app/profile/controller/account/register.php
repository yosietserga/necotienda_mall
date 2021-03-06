<?php 
class ControllerAccountRegister extends Controller {
	private $error = array();
	      
  	public function index() {
        if ($this->customer->isLogged()) {  
      		$this->redirect(Url::createUrl("account/account"));
    	}
        
    	$this->language->load('account/register');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/customer');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validate()) {
            $this->request->post['rif'] = $this->request->post['riftype'] . $this->request->post['rif'];
            $this->request->post['birthday'] = $this->request->post['byear'] ."-". $this->request->post['bmonth'] ."-". $this->request->post['bday'];
    	    $result = $this->modelCustomer->addCustomer($this->request->post);
            if ($result) {
                $this->session->clear('guest');
                
                $this->customer->login($this->request->getPost('email'),$this->request->getPost('password'));
                
                if ($this->config->get('marketing_email_new_customer')) {
                    $this->load->model("marketing/newsletter");
                    $this->load->library('email/mailer');
                    $this->load->library('BarcodeQR');
                    $this->load->library('Barcode39');
                    $mailer     = new Mailer;
                    $qr         = new BarcodeQR;
                    $barcode    = new Barcode39(C_CODE);
                        
                    $qrStore  = "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png';
                    $eanStore = "cache/" . str_replace(" ","_",$this->config->get('config_owner') ."_barcode_39_order_id_" . $order_id) . '.gif';
                    
                    if (!file_exists(DIR_IMAGE . $qrStore)) {
                        $qr->url(HTTP_HOME);
                        $qr->draw(150,DIR_IMAGE . $qrStore);
                    }
                    if (!file_exists(DIR_IMAGE . $eanStore)) {
                        $barcode->draw(DIR_IMAGE . $eanStore);
                    }
                    
            		if ($this->config->get('config_customer_approval')) {
                        $result = $this->modelNewsletter->getById($this->config->get('marketing_email_new_customer'));
                        $message = $result['htmlbody'];
           			} else {
                        $result = $this->modelNewsletter->getById($this->config->get('marketing_email_activate_customer'));
                        $message = $result['htmlbody'];
           			}
        			
                    $message = str_replace("{%store_logo%}",'<img src="'. HTTP_IMAGE . $this->config->get('config_logo') .'" alt="'. $this->config->get('config_name') .'" />',$message);
                    $message = str_replace("{%store_url%}",HTTP_HOME,$message);
                    $message = str_replace("{%url_login%}",Url::createUrl("account/login"),$message);
                    $message = str_replace("{%url_activate%}",Url::createUrl("account/activate",array('ac'=>$this->request->post['activation_code'])),$message);
                    $message = str_replace("{%store_owner%}",$this->config->get('config_owner'),$message);
                    $message = str_replace("{%store_name%}",$this->config->get('config_name'),$message);
                    $message = str_replace("{%store_rif%}",$this->config->get('config_rif'),$message);
                    $message = str_replace("{%store_email%}",$this->config->get('config_email'),$message);
                    $message = str_replace("{%store_telephone%}",$this->config->get('config_telephone'),$message);
                    $message = str_replace("{%store_address%}",$this->config->get('config_address'),$message);
                    $message = str_replace("{%products%}",$product_html,$message);
                    $message = str_replace("{%fullname%}",$this->request->post['firstname'] ." ". $this->request->post['lastname'],$message);
                    $message = str_replace("{%rif%}",$this->request->post['rif'],$message);
                    $message = str_replace("{%company%}",$this->request->post['company'],$message);
                    $message = str_replace("{%email%}",$this->request->post['email'],$message);
                    $message = str_replace("{%password%}",$this->request->post['password'],$message);
                    $message = str_replace("{%date_added%}",date('d-m-Y h:i A'),$message);
                    $message = str_replace("{%ip%}",$_SERVER['REMOTE_ADDR'],$message);
                    $message = str_replace("{%qr_code_store%}",'<img src="'. HTTP_IMAGE . $qrStore .'" alt="QR Code" />',$message);
                    $message = str_replace("{%barcode_39_order_id%}",'<img src="'. HTTP_IMAGE . $eanStore .'" alt="NT Code" />',$message);
                        
                    $message .= "<p style=\"text-align:center\">Powered By Necotienda&reg; ". date('Y') ."</p>";
                    
                    $subject = $this->config->get('config_name') ." ". $this->language->get('text_welcome');
                    if ($this->config->get('config_smtp_method')=='smtp') {
                        $mailer->IsSMTP();
                		$mailer->Hostname = $this->config->get('config_smtp_host');
                		$mailer->Username = $this->config->get('config_smtp_username');
                		$mailer->Password = base64_decode($this->config->get('config_smtp_password'));
                		$mailer->Port     = $this->config->get('config_smtp_port');
                        $mailer->Timeout  = $this->config->get('config_smtp_timeout');
                        $mailer->SMTPSecure = $this->config->get('config_smtp_ssl');
                        $mailer->SMTPAuth = ($this->config->get('config_smtp_auth')) ? true : false;          
                    } elseif ($this->config->get('config_smtp_method')=='sendmail') {
                        $mailer->IsSendmail();
                    } else {
                        $mailer->IsMail();
                    }
                    $mailer->IsHTML();
            		$mailer->AddAddress($this->request->post['email'],$this->request->post['company']);
            		$mailer->AddBCC($this->config->get('config_email'),$this->config->get('config_name'));
            		$mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
            		$mailer->Subject = $subject;
        		    $mailer->Body = html_entity_decode(htmlspecialchars_decode($message));
            		$mailer->Send();
                }
        	  	$this->redirect(Url::createUrl("account/success"));
            }
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
        	'href'      => Url::createUrl("account/register"),
        	'text'      => $this->language->get('text_create'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), Url::createUrl("account/login"));
				
    	$this->data['action'] = Url::createUrl("account/register");
        
        $this->data['error_warning']    = isset($this->error['warning']) ? $this->error['warning'] : "";
        $this->data['error_email']      = isset($this->error['email']) ? $this->error['email'] : "";
        $this->data['error_company']    = isset($this->error['company']) ? $this->error['company'] : "";
        $this->data['error_rif']        = isset($this->error['rif']) ? $this->error['rif'] : "";
        $this->data['error_password']   = isset($this->error['password']) ? $this->error['password'] : "";
        $this->data['error_confirm']    = isset($this->error['confirm']) ? $this->error['confirm'] : "";
        $this->data['error_captcha']    = isset($this->error['captcha']) ? $this->error['captcha'] : "";
        $this->data['error_recaptcha']  = isset($this->error['recaptcha']) ? $this->error['recaptcha'] : null;

        $this->setvar('company');
        $this->setvar('riftype');
        $this->setvar('rif');
        $this->setvar('lastname');
        $this->setvar('firstname');
        $this->setvar('email');
        $this->setvar('byear');
        $this->setvar('bmonth');
        $this->setvar('bday');
        $this->setvar('telephone');
        
        $this->setvar('country_id');
        $this->setvar('zone_id');
        $this->setvar('address_1');
        $this->setvar('postcode');
        $this->setvar('city');
        
        $this->load->model('localisation/country');
       	$this->data['countries'] = $this->modelCountry->getCountries();
        $this->data['page_legal_terms_id'] = ($this->config->get('config_account_id')) ? $this->config->get('config_account_id') : 0;
        $this->data['page_privacy_terms_id'] = ($this->config->get('config_account_id')) ? $this->config->get('config_account_id') : 0;
        
        // scripts
        $scripts[] = array('id'=>'scriptsRegister','method'=>'ready','script'=>
            "$('#create').ntForm();
            $('#email').on('change',function(e){
           	    $.post('". Url::createUrl("account/register/checkemail") ."', {email: $(this).val()},
                    function(response){
                        $('#tempLink').remove();
              	        var data = $.parseJSON(response);
                        if (typeof data.error != 'undefined') {
                            $('#email').removeClass('neco-input-success').addClass('neco-input-error');
                            $('#email').parent().find('.neco-form-error').attr({'title':\"Este email ya existe!\"});
                            $('#email').closest('.row').after('<p id=\"tempLink\" class=\"error\">'+ data.msg +'</p>');
             			} else {
                            $('#email').addClass('neco-input-success').removeClass('neco-input-error');
                            $('#email').parent().find('.neco-form-error').attr({'title':\"No hay errores en este campo\"});
                            $('#tempLink').remove();
             			}
                  	});
                });
            $('#firstname,#lastname').on('change',function(e){
                if (($('#firstname').val().length != 0) && ($('#lastname').val().length != 0) && ($('#company').val().length == 0)) {
                    $('#company').val($('#firstname').val() +' '+ $('#lastname').val());
                }
            });
            ");
            
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

            $this->load->helper('widgets');
            $widgets = new NecoWidget($this->registry,$this->Route);
            foreach ($widgets->getWidgets('main') as $widget) {
                $settings = (array)unserialize($widget['settings']);
                if ($settings['asyn']) {
                    $url = Url::createUrl("{$settings['route']}",$settings['params']);
                    $scripts[$widget['name']] = array(
                        'id'=>$widget['name'],
                        'method'=>'ready',
                        'script'=>
                        "$(document.createElement('div'))
                        .attr({
                            id:'".$widget['name']."'
                        })
                        .html(makeWaiting())
                        .load('". $url . "')
                        .appendTo('".$settings['target']."');"
                    );
                } else {
                    if (isset($settings['route'])) {
                        if ($settings['autoload']) $this->data['widgets'][] = $widget['name'];
                        $this->children[$widget['name']] = $settings['route'];
                        $this->widget[$widget['name']] = $widget;
                    }
                }
            }
            
            foreach ($widgets->getWidgets('featuredContent') as $widget) {
                $settings = (array)unserialize($widget['settings']);
                if ($settings['asyn']) {
                    $url = Url::createUrl("{$settings['route']}",$settings['params']);
                    $scripts[$widget['name']] = array(
                        'id'=>$widget['name'],
                        'method'=>'ready',
                        'script'=>
                        "$(document.createElement('div'))
                        .attr({
                            id:'".$widget['name']."'
                        })
                        .html(makeWaiting())
                        .load('". $url . "')
                        .appendTo('".$settings['target']."');"
                    );
                } else {
                    if (isset($settings['route'])) {
                        if ($settings['autoload']) $this->data['featuredWidgets'][] = $widget['name'];
                        $this->children[$widget['name']] = $settings['route'];
                        $this->widget[$widget['name']] = $widget;
                    }
                }
            }
            
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/register.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/register.tpl';
		} else {
			$this->template = 'default/account/register.tpl';
		}
		
    		$this->children[] = 'common/column_left';
    		$this->children[] = 'common/column_right';
    		$this->children[] = 'common/nav';
    		$this->children[] = 'common/header';
    		$this->children[] = 'common/footer';
            
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));	
  	}

  	private function validate() {
  	 // parámetros de seguridad configurables     
  	     if ($this->config->get('config_server_security')) {
        	
            if (!$this->validar->esSinCharEspeciales($this->request->post['company'],$this->language->get('entry_company')) && !$this->validar->longitudMinMax($this->request->post['company'],3,32,$this->language->get('entry_company'))) {
          		$this->error['company'] = $this->language->get('error_company');
        	}
            
            if (!$this->validar->esSoloNumeros($this->request->post['rif'])) {
          		$this->error['rif'] = $this->language->get('error_rif');
        	}    
        }
        
  		if (!$this->validar->validEmail($this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
  		}            


    	if ($this->modelCustomer->getTotalCustomersByEmail($this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}
		
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
            $this->validar->custom("<li>La confirmaci&oacute;n de la contrase&ntilde;a no coincide</li>");
    	}
		
        $this->data['mostrarError'] = $this->validar->mostrarError();
        
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
    
    public function checkemail() {
        $this->load->model("account/customer");
        $this->load->library("json");
        $json = array();
        if (!isset($this->request->post['email'])) {
            $json['error'] = 1;
            $json['msg'] = $this->language->get('error_email'); 
        }
        $result = $this->modelCustomer->getTotalCustomersByEmail($this->request->post['email']);
        if ($result) {
            $json['error'] = 1;
            $json['msg'] = $this->language->get('error_exists'); 
        }
		$this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }
    
    public function register() {
        if (!$this->customer->islogged()) {
            $this->load->model("account/customer");
            $this->load->model("marketing/newsletter");
            $this->load->library('email/mailer');
            $this->load->library('BarcodeQR');
            $mailer     = new Mailer;
            
            if (!file_exists(DIR_IMAGE . "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png')) {
                $qr = new BarcodeQR;
                $qr->url(HTTP_HOME);
                $qr->draw(100,DIR_IMAGE . "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png');
            }
            
            $this->request->post['rif'] = $this->request->post['riftype'] . $this->request->post['rif'];
            $this->request->post['password'] = substr(md5(rand(11111111,99999999)),0,8);
            
            if ($this->modelCustomer->addCustomer($this->request->post)) {
                $this->customer->login($this->request->post['email'], $this->request->post['password'], true);
                $this->session->clear('guest');
                if ($this->config->get('marketing_email_register_customer')) {
                    $newsletter = $this->modelNewsletter->getById($this->config->get('marketing_email_register_customer'));
                    $message = $newsletter['htmlbody'];
                    
                    
                    $message = str_replace("{%store_name%}",$this->config->get('config_owner'),$message);
                    $message = str_replace("{%store_rif%}",$this->config->get('config_rif'),$message);
                    $message = str_replace("{%store_address%}",$this->config->get('config_address'),$message);
                    $message = str_replace("{%company%}",$this->customer->getCompany(),$message);
                    $message = str_replace("{%email%}",$this->customer->getEmail(),$message);
                    $message = str_replace("{%password%}",$this->request->post['password'],$message);
                    $message = str_replace("{%date_added%}",date('d-m-Y h:i A',strtotime($order['date_added'])),$message);
                    $message = str_replace("{%ip%}",$order['ip'],$message);
                    $message = str_replace("{%qr_code_store%}",HTTP_IMAGE . "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png',$message);

                    $subject = $this->config->get('config_owner') ." ". $this->language->get('text_welcome');
                    if ($this->config->get('config_smtp_method')=='smtp') {
                        $mailer->IsSMTP();
                		$mailer->Hostname = $this->config->get('config_smtp_host');
                		$mailer->Username = $this->config->get('config_smtp_username');
                		$mailer->Password = base64_decode($this->config->get('config_smtp_password'));
                		$mailer->Port     = $this->config->get('config_smtp_port');
                        $mailer->Timeout  = $this->config->get('config_smtp_timeout');
                        $mailer->SMTPSecure = $this->config->get('config_smtp_ssl');
                        $mailer->SMTPAuth = ($this->config->get('config_smtp_auth')) ? true : false;          
                    } elseif ($this->config->get('config_smtp_method')=='sendmail') {
                        $mailer->IsSendmail();
                    } else {
                        $mailer->IsMail();
                    }
                    $mailer->IsHTML();
            		$mailer->AddAddress($this->customer->getEmail(),$this->customer->getCompany());
            		$mailer->AddBCC($this->config->get('config_email'),$this->config->get('config_name'));
            		$mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
            		$mailer->Subject = $subject;
      		        $mailer->Body = html_entity_decode(htmlspecialchars_decode($message));
            		$mailer->Send();
                }
            }
        }
    }
    
    public function addAddress() {
        if ($this->customer->islogged()) {
            $this->load->model("account/customer");
            $this->request->post['firstname'] = $this->customer->getFirstName();
            $this->request->post['lastname']  = $this->customer->getLastName();
            $this->request->post['company']   = $this->customer->getCompany();
            $address_id = $this->modelCustomer->addAddress($this->customer->getId(),$this->request->post);
            $this->response->setOutput($address_id, $this->config->get('config_compression'));
        }
    }
    
  	public function zone() {
		$output = '<option value="false">' . $this->language->get('text_select') . '</option>';
		$this->load->model('localisation/zone');
    	$results = $this->modelZone->getZonesByCountryId($this->request->get['country_id']);
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
			if (!$this->request->get['zone_id']) {
		  		$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}
	
		$this->response->setOutput($output, $this->config->get('config_compression'));
  	}
}
