<?php
class ControllerApiTwitter extends Controller {
	public function index() {
	    if ($this->customer->isLogged()) {  
      		$this->redirect(Url::createUrl("account/account"));
    	}
        
	    if (!$this->customer->isLogged() && (!$this->config->get('social_twitter_consumer_key') || !$this->config->get('social_twitter_consumer_secret'))) {  
      		$this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Twitter, por favor intente con otro servicio")));
    	}
        
        $this->load->library('twitter_sdk/twitteroauth');
        if ($this->session->has("oauth_token") && $this->session->has("oauth_token_secret") && $this->request->hasQuery("oauth_verifier")) {
            $twitter = new TwitterOAuth(
                $this->config->get('social_twitter_consumer_key'), 
                $this->config->get('social_twitter_consumer_secret'),
                $this->session->get("oauth_token"),
                $this->session->get("oauth_token_secret")
            );
            $access_token = $twitter->getAccessToken($this->request->getQuery("oauth_verifier"));
            $user_info = $twitter->get('account/verify_credentials');
            
            $data = array(
                "oauth_id"      => $user_info->id,
                "company"       => $user_info->name,
                "oauth_provider"=> 'twitter',
                "oauth_token"   => $this->session->get("oauth_token"),
                "oauth_token_secret"=> $this->session->get("oauth_token_secret")
            );
            
            $result = $this->modelCustomer->getCustomerByTwitter($data);
            if ($result) {
                if ($this->customer->loginWithTwitter($data)) {
                    if (!$result['email']) {
                        $this->redirect(Url::createUrl("account/complete_profile"));
                    } elseif ($this->session->has('redirect')) {
                        $this->redirect(str_replace('&amp;', '&', $this->session->get('redirect')));
         			} else {
                        $this->redirect(Url::createUrl("common/home"));
         			} 
                } else {
                    $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Twitter, por favor intente con otro servicio")));
                }
            } elseif ($this->modelCustomer->addCustomerFromTwitter($data)) {
                if ($this->customer->loginWithTwitter($data)) {
                    $this->redirect(Url::createUrl("account/complete_profile"));
                } else {
                    $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Twitter, por favor intente con otro servicio")));
                }
            }
            
        } else {
            $twitter = new TwitterOAuth(
                $this->config->get('social_twitter_consumer_key'), 
                $this->config->get('social_twitter_consumer_secret')
            );
            
            $request_token = $twitter->getRequestToken(Url::createUrl("api/twitter"));
            
            $this->session->set("oauth_token",$request_token['oauth_token']);
            $this->session->set("oauth_token_secret",$request_token['oauth_token_secret']);
            
            if($twitter->http_code == '200') {
                $this->redirect($twitter->getAuthorizeURL($request_token['oauth_token']));
        	} else {
        		$this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Twitter, por favor intente con otro servicio")));
        	}
        }
    }
}