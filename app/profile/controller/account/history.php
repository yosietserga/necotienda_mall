<?php 
class ControllerAccountHistory extends Controller {	
	public function index() {
        if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect',Url::createUrl("account/history"));

	  		$this->redirect(Url::createUrl("account/login"));
    	}
        
        $this->load->model('account/customer');
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {

                //if ($this->validateDB()) {
            	$result = $this->modelCustomer->addTransferencia($this->request->post);
                if ($result != 1) $this->validar->custom($result); 
                $this->data['mostrarError'] = $this->validar->mostrarError();
                $result = $this->modelCustomer->getCustomer($this->customer->getId());
             
    			$subject = "Reporte de Pago de Pedido";
    
    			$message = "Reporte de Pago de Pedido con Transferencia o Deposito Bancario\n";
                $message .= "--------------------------------------------------------------------\n\n";
                $message .= "Pedido:                #".$this->request->post['order_id']."\n";
                $message .= "Nombre:              ".$this->request->post['nombre']."\n";
                $message .= "Forma de Pago: ".$this->request->post['forma_de_pago']."\n";
                $message .= "Transacci&oacute;n:       #".$this->request->post['numero_transaccion']."\n";
                if (!empty($this->request->post['su_banco']))
                $message .= "Desde:           ".$this->request->post['su_banco']."\n";
                $message .= "Depositado en: ".$this->request->post['mi_banco']."\n";
                if (!empty($this->request->post['tipo_deposito']))
                $message .= "Tipo:            ".$this->request->post['tipo_deposito']."\n";
                $message .= "Fecha del Pago: ".$this->request->post['fecha_pago']."\n";
                $message .= "Monto:                 ".$this->request->post['monto_cancelado']."\n";
                $message .= "---------------------------------------------------------------------\n\n";
                $message .= "Observaciones:\n";
                $message .= $this->request->post['observacion'];
    			
    			$sendto = $result['email'];
    			$sendfrom = $this->config->get('config_email');
                
    			$mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo($sendto);
    	  		$mail->setFrom($sendfrom);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($subject);
    			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
          		$mail->send();
                //}
                unset($this->request->post);
            
		}
 
    	$this->language->load('account/history');

    	$this->document->title = $this->language->get('heading_title');

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
        	'href'      => Url::createUrl("account/history"),
        	'text'      => $this->language->get('text_history'),
        	'separator' => $this->language->get('text_separator')
      	);
				
		$this->load->model('account/order');

		$order_total = $this->modelOrder->getTotalOrders();
		
		if ($order_total) {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_order'] = $this->language->get('text_order');
      		$this->data['text_status'] = $this->language->get('text_status');
     		$this->data['text_date_added'] = $this->language->get('text_date_added');
      		$this->data['text_customer'] = $this->language->get('text_customer');
      		$this->data['text_products'] = $this->language->get('text_products');
      		$this->data['text_total'] = $this->language->get('text_total');
            $this->data['text_transferencia'] = $this->language->get('text_transferencia');
            $this->data['text_deposito'] = $this->language->get('text_deposito');
            $this->data['transferencia_heading'] = $this->language->get('transferencia_heading');
            $this->data['transferencia_elija_pago'] = $this->language->get('transferencia_elija_pago');
            $this->data['transferencia_nombre'] = $this->language->get('transferencia_nombre');
            $this->data['transferencia_subanco'] = $this->language->get('transferencia_subanco');
            $this->data['transferencia_mibanco'] = $this->language->get('transferencia_mibanco');
            $this->data['transferencia_numero'] = $this->language->get('transferencia_numero');
            $this->data['transferencia_monto'] = $this->language->get('transferencia_monto');
            $this->data['transferencia_order'] = $this->language->get('transferencia_order');
            $this->data['transferencia_fecha'] = $this->language->get('transferencia_fecha');
            $this->data['transferecnia_observacion'] = $this->language->get('transferecnia_observacion');
            $this->data['entry_captcha'] = $this->language->get('entry_captcha');
            $this->data['transferencia_tipo_deposito'] = $this->language->get('transferencia_tipo_deposito');    

      		$this->data['button_view'] = $this->language->get('button_view');
      		$this->data['button_continue'] = $this->language->get('button_continue');
            $this->data['button_back'] = $this->language->get('button_back');
            
    		$this->data['action'] = Url::createUrl("account/history");
            
    		$this->setvar('forma_de_pago');
    		$this->setvar('order_id');
    		$this->setvar('nombre');
    		$this->setvar('numero_transaccion');
    		$this->setvar('su_banco');
    		$this->setvar('mi_banco');
    		$this->setvar('tipo_deposito');
    		$this->setvar('fecha_pago');
    		$this->setvar('monto_cancelado');
    		$this->setvar('observacion');
    		$this->setvar('captcha');
    		$this->setvar('agree');            
                
            $this->data['back'] = Url::createUrl("account/account");
                
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
      		$this->data['orders'] = array();
			
			$results = $this->modelOrder->getOrders(($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
      		
			foreach ($results as $result) {
        		$product_total = $this->modelOrder->getTotalOrderProductsByOrderId($result['order_id']);

        		$this->data['orders'][] = array(
          			'order_id'   => $result['order_id'],
          			'name'       => $result['firstname'] . ' ' . $result['lastname'],
          			'status'     => $result['status'],
          			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'products'   => $product_total,
          			'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
					'href'       => Url::createUrl("account/invoice",array("order_id"=>$result['order_id']))
        		);
      		}

			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = Url::createUrl("account/history") . '&page=%s';
			
			$this->data['pagination'] = $pagination->render();

      		$this->data['continue'] = Url::createUrl("account/account");
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/history.tpl')) {
				$this->template = $this->config->get('config_template') . '/account/history.tpl';
			} else {
				$this->template = 'default/account/history.tpl';
			}
			
			$this->children = array(
			'common/nav',
			'common/column_left',
			'common/footer',
			'common/header'
		);
			
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));				
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = Url::createUrl("account/account");
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/error/not_found.tpl';
			} else {
				$this->template = 'default/error/not_found.tpl';
			}
			
			$this->children = array(
			'common/nav',
			'common/column_left',
			'common/footer',
			'common/header'
		);
					
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));				
		}
	}
    
  	private function validate() {
  	     
        if (empty($this->request->post['forma_de_pago'])) {
        	$this->data['error_forma_de_pago'] = "Error";
            $this->validar->custom("<li>Debe seleccionar una forma de pago</li>");
    	}
        
        if (!$this->validar->longitudMinMax($this->request->post['order_id'],1,11,"ID del Pedido") || (!$this->validar->esSoloNumeros($this->request->post['order_id'],"ID del Pedido") && !$this->validar->esSinCharEspeciales($this->request->post['nombre'],"Nombre"))) {
            $this->data['error_order_id'] = $this->language->get('error_order_id');
        }
    	
        if (!$this->validar->longitudMinMax($this->request->post['nombre'],3,32,"Nombre") || (!$this->validar->esSoloTexto($this->request->post['nombre'],"Nombre") && !$this->validar->esSinCharEspeciales($this->request->post['nombre'],"Nombre"))) {
      		$this->data['error_nombre'] = $this->language->get('error_nombre');
    	}
        
        if (!$this->validar->longitudMinMax($this->request->post['numero_transaccion'],3,32,"N&uacute;mero de Transferencia") && !$this->validar->esSoloNumeros($this->request->post['numero_transaccion'],"N&uacute;mero de Transferencia")) {
      		$this->data['error_numero_transaccion'] = $this->language->get('error_numero');
    	}
        
        if ($this->request->post['forma_de_pago'] == 'Transferencia'){
            if (empty($this->request->post['su_banco'])) {
               $this->validar->custom("<li>Debe seleccionar el banco desde donde realiz&oacute; la transferencia</li>");
        	   $this->data['error_su_banco'] = $this->language->get('error_subanco');
            }  
        }
        
        if ($this->request->post['forma_de_pago'] == 'Deposito'){
            if (empty($this->request->post['tipo_deposito'])) {
               $this->validar->custom("<li>Debe seleccionar el tipo del dep&oacute;sito</li>");
        	   $this->data['error_tipo_deposito'] = $this->language->get('error_tipo_deposito');
            } 
        }
        
        if (empty($this->request->post['mi_banco']) || !$this->validar->esSinCharEspeciales($this->request->post['mi_banco'],"Banco Donde Deposit&oacute;")) {
        	$this->data['error_mi_banco'] = $this->language->get('error_mibanco');
            $this->validar->custom("<li>Debe seleccionar el banco donde realiz&oacute; el dep&oacute;sito o la transferencia</li>");
    	}
        
        if (!$this->validar->esFechaCorta($this->request->post['fecha_pago'],"Fecha de Pago")) {
      		$this->data['error_fecha_pago'] = $this->language->get('error_fecha');
    	}
        
        if (!$this->validar->longitudMinMax($this->request->post['monto_cancelado'],1,12,"Monto Cancelado") && !$this->validar->esSoloNumerosConDecimales($this->request->post['monto_cancelado'],"Monto Cancelado")) {
      		$this->data['error_monto'] = $this->language->get('error_monto');
    	}
        
        if (!empty($this->request->post['forma_de_pago']) && !$this->validar->esSinCharEspeciales($this->request->post['forma_de_pago'],"Forma de Pago")) {
        	$this->data['error_forma_de_pago'] = $this->language->get('forma_de_pago');
    	}
        
        if (!empty($this->request->post['observacion']) && !$this->validar->esSinCharEspeciales($this->request->post['observacion'],"Observacion")) {
        	$this->data['error_observacion'] = "Error";
    	}
        
        if ($this->session->get('skey') != $this->customer->skey) {
      		$this->error['skey'] = true;
            $this->validar->custom("<li>Se ha detectado un ataque a la seguridad del sistema. Se han deshabilitado algunas funciones y se est&aacute; rastreando su direcci&oacute;n IP</li>");
    	}
        
        if (!$this->session->has('captcha') || $this->session->get('captcha') != $this->request->post['captcha']) {
      		$this->data['error_captcha'] = $this->language->get('error_captcha');
            if (!$this->session->has('captcha')) {
                $this->validar->custom("<li>Debe ingresar el resultado de la ecuaci&oacute;n</li>");
            } elseif ($this->session->get('captcha') != $this->request->post['captcha']) {
                $this->validar->custom("<li>El resultado de la ecuaci&oacute;n es incorrecto</li>");
            }
    	}
		
        $this->data['mostrarError'] = $this->validar->mostrarError();
        
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
