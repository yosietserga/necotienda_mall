<?php 
class ControllerPageContact extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('page/contact');

    	$this->document->title = $this->language->get('heading_title');  
	 	  
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->library('email/mailer');
            $mailer = new Mailer;
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
            
            $mailer->IsHTML(false);
    		$mailer->AddAddress($this->config->get('config_email'),$this->config->get('config_name'));
    		$mailer->SetFrom($this->request->post['email'],$this->request->post['name']);
    		$mailer->Subject= $this->config->get('config_name') ." - Contacto";
    		$mailer->Body   = strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8'));
    		$mailer->Send();
            
            if ($this->request->hasPost('newsletter')) {
                $this->load->model('marketing/contact');
                $this->modelContact->add($this->request->post);
            }
            
            $this->data['success'] = $this->language->get('text_success');
            $this->request->post = array();
            unset($this->request->server['REQUEST_METHOD']);
        }

      	$this->document->breadcrumbs = array();
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("page/contact"),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;	
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';
		$this->data['error_email'] = isset($this->error['email']) ? $this->error['email'] : '';
		$this->data['error_enquiry'] = isset($this->error['enquiry']) ? $this->error['enquiry'] : '';
		$this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';
		$this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';
		
		$this->data['action'] = Url::createUrl("page/contact");
		$this->data['store']  = $this->config->get('config_name');
    	$this->data['address']= nl2br($this->config->get('config_address'));
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	
        $this->setvar('name');
        $this->setvar('email');
        $this->setvar('enquiry');
        
        if ($this->config->get('marketing_page_contact_id')) {
            $this->load->model('content/page');
            $contact_page = $this->modelPage->getById($this->config->get('marketing_page_contact_id'));
            $this->data['contact_page'] = html_entity_decode($contact_page['description']);
        }
        
        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        $styles[] = array('media'=>'all','href'=>$csspath.'neco.form.css');
        $this->styles = array_merge($styles,$this->styles);
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        $javascripts[] = $jspath."vendor/jquery-ui.min.js";
        $javascripts[] = $jspath."necojs/neco.form.js";
        $this->javascripts = array_merge($this->javascripts, $javascripts);
        
        // SCRIPTS
        $scripts[] = array('id'=>'contact','method'=>'ready','script'=>
        "$('#contact').ntForm();");
        
        $this->loadWidgets();
        
        $this->scripts = array_merge($this->scripts,$scripts);
        
   		$this->children[] = 'common/column_left';
    	$this->children[] = 'common/column_right';
    	$this->children[] = 'common/nav';
    	$this->children[] = 'common/header';
    	$this->children[] = 'common/footer';
        
        $template = ($this->config->get('default_view_contact')) ? $this->config->get('default_view_contact') : 'page/contact.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
                 
 		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}

  	private function validate() {
    	if (empty($this->request->post['name'])) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

		if (!$this->validar->validEmail($this->request->post['email'])) {
		      $this->error['email'] = $this->language->get('error_email');
		}

    	if (empty($this->request->post['enquiry'])) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}
        
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}
    
    protected function loadWidgets() {
        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $csspath = str_replace("%theme%",$this->config->get('config_template'),$csspath);
       	} else {
            $csspath = str_replace("%theme%","default",$csspath);
       	}
        if (fopen($csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'),'r')) {
            $styles[] = array('media'=>'all','href'=>$csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'));
        }
        if (count($styles)) {
            $this->data['styles'] = $this->styles = array_merge($this->styles,$styles);
        }
        
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
    }
}
