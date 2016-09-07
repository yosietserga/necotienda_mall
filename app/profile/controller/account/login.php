<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();
	
	public function index() {
        if ($this->customer->isLogged()) {  
      		$this->redirect(Url::createUrl("account/account"));
    	}

        $this->activarUser();
        
    	$this->language->load('account/login');

    	$this->document->title = $this->language->get('heading_title');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if (isset($this->request->post['email']) && isset($this->request->post['password']) && $this->validate()) {
                $this->session->clear('guest');
                    
                if (isset($this->request->post['redirect'])) {
                    $this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
      			} else {
                    $this->redirect(Url::createUrl("common/home"));
      			} 
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
        	'href'      => Url::createUrl("account/login"),
        	'text'      => $this->language->get('text_login'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['error'] = isset($this->error['message']) ? $this->error['message'] : '';
		$this->data['action'] = Url::createUrl("account/login");
		$this->data['register'] = Url::createUrl("account/register");

    	if (isset($this->request->post['redirect'])) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif ($this->session->has('redirect')) {
      		$this->data['redirect'] = $this->session->get('redirect');
	  		$this->session->clear('redirect');		  	
    	} else {
			$this->data['redirect'] = '';
		}
        
        if ($this->request->hasQuery('error')) {
            $this->data['error'] = $this->language->get('error_login');
        }
        
		if ($this->session->has('success')) {
    		$this->data['success'] = $this->session->get('success');
	  		$this->session->clear('success');	
		} else {
			$this->data['success'] = '';
		}
		
		if ($this->session->has('message')) {
    		$this->data['message'] = $this->session->get('message');
	  		$this->session->clear('message');	
		} else {
			$this->data['message'] = '';
		}
		
		if ($this->session->has('account')) {
			$this->data['account'] = $this->session->get('account');
		} else {
			$this->data['account'] = 'register';
		}
		
        $this->session->set('state',md5(rand()));
        $this->data['live_client_id'] = $this->config->get('social_live_client_id');
        $this->data['google_client_id'] = $this->config->get('social_google_client_id');
        $this->data['facebook_app_id']  = $this->config->get('social_facebook_app_id');
        $this->data['twitter_oauth_token_secret'] = $this->config->get('social_twitter_oauth_token_secret');
        
    	$this->data['forgotten'] = Url::createUrl("account/forgotten");
		$this->data['guest_checkout'] = ($this->config->get('config_guest_checkout') && $this->cart->hasProducts() && !$this->cart->hasDownload());

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
            
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/login.tpl';
		} else {
			$this->template = 'default/account/login.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/nav',
			'common/column_left',
			'common/column_right',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
    private function activarUser() {
        $arrValor = array();
        $codigo = $_SERVER['QUERY_STRING'];
        $arrCodigo = explode('&amp;',$codigo);
        foreach($arrCodigo as $key => $value) {
            $arrValor[] = explode('=',$value);
        }
        foreach ($arrValor as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == '1') $arrFinal[] = $value2;
            }
        }
        if (!empty($arrFinal[3])) {
        $email = $arrFinal[1];
        $password = $arrFinal[2];
        $codigo = $arrFinal[3];
        if ($this->customer->activateUser($codigo)) {
        echo "<center><div style='background:#fff88d top center;display:block;width:1000px;height:15px;font:bold 11px verdana;color:#e47202;'>Su cuenta ha sido activada, Ya puede acceder y disfrutar de nuestros servicios.</div></center>";
        }}
    }
  
  	private function validate() {
    	$this->language->load('account/login');
    	if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
      		$this->error['message'] = $this->language->get('error_login');
    	}     	
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}  	
  	}
    
    public function header() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        
    	$this->language->load('account/login');
        if (!$this->request->hasPost("email") && !$this->request->hasPost("password")) {
            $json['error'] = 1;
            $json['message'] = $this->language->get('error_login');
        }
        
        if (!$this->request->hasPost("token") && $this->request->getPost("token") != $this->session->get('token')) {
            $json['error'] = 1;
            $json['message'] = $this->language->get('error_login');
        }
        
        if (!$this->customer->login($this->request->getPost("email"), $this->request->getPost("password"), false)) {
      		$json['error'] = 1;
            $json['message'] = $this->language->get('error_login');
    	} 
        
        if (!$json['error']) {
            $json['success'] = 1;
        }
        
        $this->load->auto('json');
		$this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));  
    }
}
