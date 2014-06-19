<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
        if ($this->customer->isLogged()) {
			$this->redirect(Url::createUrl("account/account"));
		}

		$this->language->load('account/forgotten');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->library('email/mailer');
            $this->load->library('BarcodeQR');
            $this->load->library('Barcode39');
                        
            $mailer     = new Mailer;
            $qr         = new BarcodeQR;
            $barcode    = new Barcode39(C_CODE);
			$password   = substr(md5(rand()), 0, 11);
            $qrStore    = "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png';
            $eanStore   = "cache/" . str_replace(" ","_",$this->config->get('config_owner') ."_barcode_39_order_id_" . $order_id) . '.gif';
                    
            if (!file_exists(DIR_IMAGE . $qrStore)) {
                $qr->url(HTTP_HOME);
                $qr->draw(150,DIR_IMAGE . $qrStore);
            }
            if (!file_exists(DIR_IMAGE . $eanStore)) {
                $barcode->draw(DIR_IMAGE . $eanStore);
            }
                    
            if ($this->config->get('marketing_email_new_password')) {
                $this->load->model("marketing/newsletter");
          		$result = $this->modelNewsletter->getById($this->config->get('marketing_email_new_password'));
                $message = $result['htmlbody'];
           		
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
                $message = str_replace("{%email%}",$this->request->post['email'],$message);
                $message = str_replace("{%password%}",$password,$message);
                $message = str_replace("{%date_added%}",date('d-m-Y h:i A'),$message);
                $message = str_replace("{%ip%}",$_SERVER['REMOTE_ADDR'],$message);
                $message = str_replace("{%qr_code_store%}",'<img src="'. HTTP_IMAGE . $qrStore .'" alt="QR Code" />',$message);
                $message = str_replace("{%barcode_39_order_id%}",'<img src="'. HTTP_IMAGE . $eanStore .'" alt="NT Code" />',$message);
                        
                $message .= "<p style=\"text-align:center\">Powered By Necotienda&reg; ". date('Y') ."</p>";
            } else {
                $message = "<h1>". $this->config->get('config_name') ."</h1>";
                $message .= "<p>". $this->language->get('text_password_renew') ."</p>";
                $message .= "<p><b>". $password ."</b></p><br />";
                $message .= '<img src="'. HTTP_IMAGE . $qrStore .'" alt="QR Code" />';
                $message .= '<img src="'. HTTP_IMAGE . $eanStore .'" alt="NT Code" />';
                $message .= "<br /><p style=\"text-align:center\">Powered By Necotienda&reg; ". date('Y') ."</p>";
            }
            
            $subject = $this->config->get('config_name') ." ". $this->language->get('text_new_password');
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
            $mailer->AddAddress($this->request->post['email'],$this->config->get('config_name'));
            $mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
            $mailer->Subject = $subject;
        	$mailer->Body = html_entity_decode(htmlspecialchars_decode($message));
            $mailer->Send();
                    
			$this->modelCustomer->editPassword($this->request->post['email'], $password);
			$this->session->set('success',$this->language->get('text_success'));
			$this->redirect(Url::createUrl("account/login"));
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
        	'href'      => Url::createUrl("account/forgotten"),
        	'text'      => $this->language->get('text_forgotten'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_your_email'] = $this->language->get('text_your_email');
		$this->data['text_email'] = $this->language->get('text_email');

		$this->data['entry_email'] = $this->language->get('entry_email');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['message'])) {
			$this->data['error'] = $this->error['message'];
		} else {
			$this->data['error'] = '';
		}
		
		$this->data['action'] = Url::createUrl("account/forgotten");
		$this->data['back'] = Url::createUrl("account/account");
		
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
            
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/forgotten.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/forgotten.tpl';
		} else {
			$this->template = 'default/account/forgotten.tpl';
		}
		
		$this->children = array(
			'common/nav',
			'common/column_left',
			'account/footer',
			'account/header'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
	}

	private function validate() {
		$this->load->model('account/customer');
		if (!$this->validar->validEmail($this->request->post['email'])) {
		      $this->error['message'] = $this->language->get('error_email');
		} elseif (!$this->modelCustomer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['message'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
