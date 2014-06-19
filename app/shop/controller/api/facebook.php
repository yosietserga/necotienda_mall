<?php
class ControllerApiFacebook extends Controller {
	public function index() {
	    if ($this->customer->isLogged()) {  
      		$this->redirect(Url::createUrl("account/account"));
    	}
        
	    if (!$this->customer->isLogged() && (!$this->config->get('social_facebook_app_id') || !$this->config->get('social_facebook_app_secret'))) {  
      		$this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Facebook, por favor intente con otro servicio")));
    	}
        
        $this->load->library('facebook_sdk/facebook');
        $fb = new Facebook(array(
            'appId'  =>$this->config->get('social_facebook_app_id'),
            'secret' =>$this->config->get('social_facebook_app_secret'),
            'cookie' =>true
        ));
        
        if ($fb->getUser()) {
            try {
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
            }

            if (!empty($user_profile )) {
                $data = array(
                    'email'         => $user_profile['email'],
                    'company'       => $user_profile['name'],
                    'firstname'     => $user_profile['first_name'],
                    'lastname'      => $user_profile['last_name'],
                    'oauth_provider'=> 'facebook',
                    'oauth_id'      => $user_profile['id']
                );
                $result = $this->modelCustomer->getCustomerByEmail($user_profile['email']);
                if ($result) {
                    if ($this->customer->loginWithFacebook($data)) {
                        if ($this->session->has('redirect')) {
                            $this->redirect(str_replace('&amp;', '&', $this->session->get('redirect')));
              			} else {
                            $this->redirect(Url::createUrl("common/home"));
              			} 
                    } else {
                        $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Facebook, por favor intente con otro servicio")));
                    }
                } elseif ($this->modelCustomer->addCustomer($data)) {
                    if ($this->customer->loginWithFacebook($data)) {
                        if ($this->session->has('redirect')) {
                            $this->redirect(str_replace('&amp;', '&', $this->session->get('redirect')));
              			} else {
                            $this->redirect(Url::createUrl("common/home"));
              			} 
                    } else {
                        $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Facebook, por favor intente con otro servicio")));
                    }
                }
            }
        } else {
           $this->redirect($fb->getLoginUrl(
                array(
                    'scope' => 'email,user_birthday,read_stream,publish_actions,friends_likes,user_interests,offline_access'
                )
           ));
        }
    }
}
