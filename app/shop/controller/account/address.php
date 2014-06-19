<?php 
class ControllerAccountAddress extends Controller {
	private $error = array();
	  
  	public function index() {
        if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect',Url::createUrl("account/address"));
	  		$this->redirect(Url::createUrl("account/login"));
    	}
	
    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		
		$this->getList();
  	}

  	public function insert() {
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect',Url::createUrl("account/address"));
	  		$this->redirect(Url::createUrl("account/login")); 
    	}

    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->modelAddress->addAddress($this->request->post);    			
          	$this->session->set('success',$this->language->get('text_insert'));    
    	  	$this->redirect(Url::createUrl("account/address"));
    	}
	  	
		$this->getForm();
  	}

  	public function update() {
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect',Url::createUrl("account/address"));
	  		$this->redirect(Url::createUrl("account/login")); 
    	} 
		
    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		   
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

       		$this->modelAddress->editAddress($this->request->get['address_id'], $this->request->post);
	  		
			if ($this->session->has('shipping_address_id') && $this->request->get['address_id']==$this->session->get('shipping_address_id')) {
	  			$this->session->clear('shipping_methods');
                $this->session->clear('shipping_method');
				$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
			}

			if ($this->session->has('payment_address_id') && $this->request->get['address_id']==$this->session->get('payment_address_id')) {
	  			$this->session->clear('payment_methods');
                $this->session->clear('payment_method');		
			}
			
			$this->session->set('success',$this->language->get('text_update'));
	  		$this->redirect(Url::createUrl("account/address"));
    	   }
        
		$this->getForm();
  	}

  	public function delete() {
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect',Url::createUrl("account/address"));
	  		$this->redirect(Url::createUrl("account/login"));
    	} 
			
    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		
    	if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->modelAddress->deleteAddress($this->request->get['address_id']);	

			if ($this->session->has('shipping_address_id') && $this->request->get['address_id'] == $this->session->get('shipping_address_id')) {
                $this->session->clear('shipping_address_id');
                $this->session->clear('shipping_methods');
                $this->session->clear('shipping_method');
			}

			if ($this->session->has('payment_address_id') && $this->request->get['address_id'] == $this->session->get('payment_address_id')) {
                $this->session->clear('payment_address_id');
                $this->session->clear('payment_methods');
                $this->session->clear('payment_method');
			}
			$this->session->set('success',$this->language->get('text_delete'));
	  		$this->redirect(Url::createUrl("account/address"));
    	}
	
		$this->getList();	
  	}

  	private function getList() {
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
        	'href'      => Url::createUrl("account/address"),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_address_book'] = $this->language->get('text_address_book');
   
    	$this->data['button_new_address'] = $this->language->get('button_new_address');
    	$this->data['button_edit'] = $this->language->get('button_edit');
    	$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
    		$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
            $this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}
		
    	$this->data['addresses'] = array();
		
		$results = $this->modelAddress->getAddresses();

    	foreach ($results as $result) {
			if ($result['address_format']) {
      			$format = $result['address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
		
    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);
	
			$replace = array(
	  			'firstname' => $result['firstname'],
	  			'lastname'  => $result['lastname'],
	  			'company'   => $result['company'],
      			'address_1' => $result['address_1'],
      			'address_2' => $result['address_2'],
      			'city'      => $result['city'],
      			'postcode'  => $result['postcode'],
      			'zone'      => $result['zone'],
				'zone_code' => $result['zone_code'],
      			'country'   => $result['country']  
			);

        	if (isset($this->request->post['default'])) {
          		$this->data['default'] = $this->request->post['default'];
        	} elseif (isset($this->request->get['address_id'])) {
          		$this->data['default'] = $this->customer->getAddressId() == $this->request->get['address_id'];
        	} else {
    			$this->data['default'] = false;
    		}

      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'default' => ($this->customer->getAddressId()==$result['address_id']) ? $this->customer->getAddressId() : null,
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
        		'update'     => Url::createUrl("account/address/update",array("address_id"=>$result['address_id'])),
				'delete'     => Url::createUrl("account/address/delete",array("address_id"=>$result['address_id']))
      		);
    	}

    	$this->data['insert'] = Url::createUrl("account/address/insert");
		$this->data['back'] = Url::createUrl("account/account");
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/addresses.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/addresses.tpl';
		} else {
			$this->template = 'default/account/addresses.tpl';
		}
		
		$this->children = array(
			'common/nav',
			'account/column_left',
			'account/footer',
			'account/header'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}

  	private function getForm() {
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
        	'href'      => Url::createUrl("account/address"),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (!isset($this->request->get['address_id'])) {
      		$this->document->breadcrumbs[] = array(
        		'href'      => Url::createUrl("account/address/insert"),
        		'text'      => $this->language->get('text_edit_address'),
        		'separator' => $this->language->get('text_separator')
      		);
		} else {
      		$this->document->breadcrumbs[] = array(
        		'href'      => Url::createUrl("account/address/update",array("address_id"=>$this->request->get['address_id'])),
        		'text'      => $this->language->get('text_edit_address'),
        		'separator' => $this->language->get('text_separator')
      		);
		}
						
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_edit_address'] = $this->language->get('text_edit_address');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_company'] = $this->language->get('entry_company');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
    	$this->data['entry_default'] = $this->language->get('entry_default');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['firstname'])) {
    		$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
		if (isset($this->error['lastname'])) {
    		$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
		if (isset($this->error['address_1'])) {
    		$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
		if (isset($this->error['city'])) {
    		$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}		

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
        
        if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}
		
		if (!isset($this->request->get['address_id'])) {
    		$this->data['action'] = Url::createUrl("account/address/insert");
		} else {
    		$this->data['action'] = Url::createUrl("account/address/update",array("address_id"=>$this->request->get['address_id']));
		}
		
    	if (isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$address_info = $this->modelAddress->getAddress($this->request->get['address_id']);
		}
	
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = ucwords($this->request->post['firstname']);
    	} elseif (isset($address_info)) {
      		$this->data['firstname'] = ucwords($address_info['firstname']);
    	} else {
			$this->data['firstname'] = '';
		}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = ucwords($this->request->post['lastname']);
    	} elseif (isset($address_info)) {
      		$this->data['lastname'] = ucwords($address_info['lastname']);
    	} else {
			$this->data['lastname'] = '';
		}

    	if (isset($this->request->post['company'])) {
      		$this->data['company'] = ucwords($this->request->post['company']);
    	} elseif (isset($address_info)) {
			$this->data['company'] = ucwords($address_info['company']);
		} else {
      		$this->data['company'] = '';
    	}

    	if (isset($this->request->post['address_1'])) {
      		$this->data['address_1'] = $this->request->post['address_1'];
    	} elseif (isset($address_info)) {
			$this->data['address_1'] = $address_info['address_1'];
		} else {
      		$this->data['address_1'] = '';
    	}

    	if (isset($this->request->post['address_2'])) {
      		$this->data['address_2'] = $this->request->post['address_2'];
    	} elseif (isset($address_info)) {
			$this->data['address_2'] = $address_info['address_2'];
		} else {
      		$this->data['address_2'] = '';
    	}	

    	if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} elseif (isset($address_info)) {
			$this->data['postcode'] = $address_info['postcode'];			
		} else {
      		$this->data['postcode'] = '';
    	}

    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = ucwords($this->request->post['city']);
    	} elseif (isset($address_info)) {
			$this->data['city'] = ucwords($address_info['city']);
		} else {
      		$this->data['city'] = '';
    	}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	}  elseif (isset($address_info)) {
      		$this->data['country_id'] = $address_info['country_id'];			
    	} else {
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	}  elseif (isset($address_info)) {
      		$this->data['zone_id'] = $address_info['zone_id'];			
    	} else {
      		$this->data['zone_id'] = 'false';
    	}
        
        if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->modelCountry->getCountries();

    	if (isset($this->request->post['default'])) {
      		$this->data['default'] = $this->request->post['default'];
    	} elseif (isset($this->request->get['address_id'])) {
      		$this->data['default'] = $this->customer->getAddressId() == $this->request->get['address_id'];
    	} else {
			$this->data['default'] = false;
		}

    	$this->data['back'] = Url::createUrl("account/address");
		
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/address.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/address.tpl';
		} else {
			$this->template = 'default/account/address.tpl';
		}
		
		$this->children = array(
			'common/nav',
			'account/column_left',
			'account/footer',
			'account/header'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}
	
  	private function validateForm() {
    	if (empty($this->request->post['address_1'])) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if (empty($this->request->post['city'])) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
    	if ($this->request->post['country_id'] == 'false') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if ($this->request->post['zone_id'] == 'false') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
		
        $this->data['mostrarError'] = $this->validar->mostrarError();
        
    	if (!$this->error) {
      		return true;
		} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if ($this->modelAddress->getTotalAddresses() == 1) {
      		$this->error['warning'] = $this->language->get('error_delete');
    	}
        
    	if ($this->customer->getAddressId() == $this->request->get['address_id']) {
      		$this->error['warning'] = $this->language->get('error_default');
    	}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
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