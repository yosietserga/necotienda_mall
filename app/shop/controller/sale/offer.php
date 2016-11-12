<?php 
class ControllerSaleOffer extends Controller {
    
	public function index() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header('Cache-Control: no-cache, must-revalidate');
		header("Pragma: no-cache");
		header("Content-type: application/json");

    	$this->language->load('sale/offer');
		$json = array();
        $price = explode(',',$this->request->post['price']);
        $this->request->post['price'] = str_replace('.','',$price[0]) .'.'. $price[1];
        
        if (!$this->request->hasPost("quantity") || !is_numeric($this->request->getPost("quantity"))) {
            $json['error'] = 1;
            $json['msg'] .= '<li>'. $this->language->get('error_quantity') .'</li>';
        }
        
        if (!$this->request->hasPost("price")) {
            $json['error'] = 1;
            $json['msg'] .= '<li>'. $this->language->get('error_price') .'</li>';
        }
        
        if (!$this->request->hasPost("delivery")) {
            $json['error'] = 1;
            $json['msg'] .= '<li>'. $this->language->get('error_delivery') .'</li>';
        }
        
        if (!$this->request->hasPost("payment_method")) {
            $json['error'] = 1;
            $json['msg'] .= '<li>'. $this->language->get('error_payment_method') .'</li>';
        }
        
        if (!$this->request->hasPost("shipping_method")) {
            $json['error'] = 1;
            $json['msg'] .= '<li>'. $this->language->get('error_shipping_method') .'</li>';
        }
        
        if (!$this->customer->isLogged()) {
            $json['error'] = 1;
            $json['msg'] .= '<li>'. $this->language->get('error_login') .'</li>';
        }
        
        if (!$json['error']) {
            $this->load->model('catalog/product');
            $this->load->model('sale/offer');
            
            $this->request->post['product_id']      = $this->request->getQuery('product_id');
            $this->request->post['customer_id']     = $this->customer->getId();
            $this->request->post['offer_status_id'] = $this->config->get('config_offer_status_id');
            $this->request->post['payment_methods'] = implode(';',$this->request->post['payment_method']);
            $this->request->post['shipping_methods']= implode(';',$this->request->post['shipping_method']);
            $this->request->post['uncensored']      = $this->request->post['comment'];
            //$this->request->post['comment']         = $this->filter($this->request->post['comment']);
            
    		$offer_id = $this->modelOffer->add($this->request->post);
            /*
    		$model = $this->modelProduct->getProduct($this->request->get['product_id']);
            if ($this->config->get('config_smtp_method')=='smtp') {
                $this->mailer->IsSMTP();
                $this->mailer->Hostname = $this->config->get('config_smtp_host');
            	$this->mailer->Username = $this->config->get('config_smtp_username');
            	$this->mailer->Password = base64_decode($this->config->get('config_smtp_password'));
            	$this->mailer->Port     = $this->config->get('config_smtp_port');
                $this->mailer->Timeout  = $this->config->get('config_smtp_timeout');
                $this->mailer->SMTPSecure = $this->config->get('config_smtp_ssl');
                $this->mailer->SMTPAuth = ($this->config->get('config_smtp_auth')) ? true : false;
             } elseif ($this->config->get('config_smtp_method')=='sendmail') {
                $this->mailer->IsSendmail();
             } else {
                $this->mailer->IsMail();
             }
             
        	$this->mailer->IsHTML();
        	$this->mailer->AddAddress($model['email'],$model['company']);
        	$this->mailer->AddAddress($this->config->get('config_email'),$this->config->get('config_email'));
        	$this->mailer->SetFrom($this->customer->getEmail(),$this->customer->getFirstName() ." ". $this->customer->getLastName());
        	$this->mailer->Subject = $subject;
        	$this->mailer->Body = $message;
            $this->mailer->Send();
            */
            $json['msg'] = $this->language->get('text_success');
            $json['success'] = 1;
        }
        
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));	
  	}
}