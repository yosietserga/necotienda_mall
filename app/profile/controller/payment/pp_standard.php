<?php
class ControllerPaymentPPStandard extends Controller {
	protected function index() {
		$this->language->load('payment/pp_standard');
	   if (!$this->config->get('pp_standard_test')) {
    		$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
        
		$this->load->library('image');
        $this->data['Image'] = new NTImage;
        
		$this->load->model('checkout/order');
        if ($this->request->hasQuery('order_id')) {
            $order_id = $this->request->getQuery('order_id');
        } elseif ($this->session->has('order_id')) {
            $order_id = $this->session->get('order_id');
        } else {
            $order_id = 0;
        }
		$order_info = $this->modelOrder->getOrder($order_id);
		
		$this->data['business']   = $this->config->get('pp_standard_email');
		$this->data['item_name']  = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');				
		$this->data['currency_code'] = $order_info['currency'];
		$this->data['amount']     = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');	
		$this->data['last_name']  = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');	
		$this->data['address1']   = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');	
		$this->data['address2']   = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');	
		$this->data['city']       = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');	
		$this->data['zip']        = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');	
		$this->data['country']    = $order_info['payment_iso_code_2'];
		$this->data['notify_url'] = Url::createUrl("payment/pp_standard/callback");
		$this->data['email']      = $order_info['email'];
		$this->data['invoice']    = $order_id . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['lc']         = $this->session->get('language');
		
		$this->data['return'] = Url::createUrl("account/order");
		$this->data['cancel_return'] = Url::createUrl("account/payment/register",array('order_id'=>$order_id));
		
		if (!$this->config->get('pp_standard_transaction')) {
			$this->data['paymentaction'] = 'authorization';
		} else {
			$this->data['paymentaction'] = 'sale';
		}
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$this->data['custom'] = $encryption->encrypt($order_id);
		$this->data['button_pay'] = 'PayPal Standard';
		
        $this->load->model("marketing/newsletter");
        $result = $this->modelNewsletter->getById($this->config->get('pp_standard_newsletter_id'));
        $this->data['instructions'] = html_entity_decode($result['htmlbody']);
                
        // style files
        $csspath = defined("CDN") ? CDN.CSS : HTTP_CSS;
            
        $styles[] = array('media'=>'all','href'=>$csspath.'jquery-ui/jquery-ui.min.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'neco.form.css');
            
        if (count($styles)) {
            $this->data['styles'] = $this->styles = array_merge($this->styles,$styles);
        }
                
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/payment/pp_standard.tpl')) {
			$this->template = $this->config->get('config_template') . '/payment/pp_standard.tpl';
		} else {
			$this->template = 'default/payment/pp_standard.tpl';
		}	
		
		$this->render();	
	}
	
	public function callback() {
		$this->load->library('encryption');
	
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		if (isset($this->request->post['custom'])) {
			$order_id = $encryption->decrypt($this->request->post['custom']);
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(stripslashes(html_entity_decode($value, ENT_QUOTES, 'UTF-8')));
			}
				
			if (extension_loaded('curl')) {
				if (!$this->config->get('pp_standard_test')) {
					$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
				} else {
					$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
				}

				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
				$response = curl_exec($ch);
			
				if (strcmp($response, 'VERIFIED') == 0 || $this->request->post['payment_status'] == 'Completed') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id'));
				} else {
					$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
				}
					
				curl_close($ch);
			} else {
				$header  = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n";
				$header .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
				$header .= 'Content-Length: ' . strlen(utf8_decode($request)) . "\r\n";
				$header .= 'Connection: close'  ."\r\n\r\n";
				
				if (!$this->config->get('pp_standard_test')) {
					$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
				} else {
					$fp = fsockopen('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
				}
			
				if ($fp) {
					fputs($fp, $header . $request);
				
					while (!feof($fp)) {
						$response = fgets($fp, 1024);
					
						if (strcmp($response, 'VERIFIED') == 0) {
							$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id'));
						} else {
							$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
						}
					}
				
					fclose($fp);
				}
			}
		}
	}
}