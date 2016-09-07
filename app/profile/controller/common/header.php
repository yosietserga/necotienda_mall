<?php
class ControllerCommonHeader extends Controller {
	protected function index() {
        $this->load->library('browser');
        $browser = new Browser;
        if ($browser->getBrowser() == 'Internet Explorer' && $browser->getVersion() <= 8) {
            $this->redirect(Url::createUrl("page/deprecated"));
        }
        
        if (!$this->session->has('token')) {
            $this->session->set('token',md5(rand()));
        }
        
        $this->data['token'] = $this->session->get('token');
        
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_HOME;
		} else {
			$this->data['base'] = HTTP_HOME;
		}
		
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = HTTP_IMAGE . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		if ($this->config->get('config_small_logo') && file_exists(DIR_IMAGE . $this->config->get('config_small_logo'))) {
			$this->data['logo'] = HTTP_IMAGE . $this->config->get('config_small_logo');
		} else {
			$this->data['logo'] = '';
		}
        
		$this->data['title']      = $this->document->title;
		$this->data['keywords']   = $this->document->keywords;
		$this->data['description']= $this->document->description;
		$this->data['template']   = $this->config->get('config_template');
		$this->data['charset']    = $this->language->get('charset');
		$this->data['lang']       = $this->language->get('code');
		$this->data['direction']  = $this->language->get('direction');
		$this->data['links']      = $this->document->links;
		$this->data['breadcrumbs']= $this->document->breadcrumbs;
        
        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        
        $this->data['css'] = "";
		$this->data['css'] .= file_get_contents($csspath . 'theme.css');
        foreach ($this->styles as $css) {
            $this->data['css'] .= file_get_contents($css['href']);
        }
        if ($this->data['css']) {
            $this->data['css'] = str_replace("../../../images/",HTTP_IMAGE,$this->data['css']);
            $this->data['css'] = str_replace("../images/",str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_IMAGE),$this->data['css']);
            $this->data['css'] = str_replace("../fonts/",str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_FONT),$this->data['css']);
        }
        
        if ($styles) $this->data['styles'] = $this->styles = array_merge($styles,$this->styles);
        
		$this->data['isLogged']  = $this->customer->isLogged();
		
		if (!isset($this->request->get['r'])) {
			$this->data['redirect'] = Url::createUrl('common/home');
		} else {			
			$data = $this->request->get;
			unset($data['_route_']);
			$route = $data['r'];
			unset($data['r']);
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data));
			}
			$this->data['redirect'] = Url::createUrl($route,$url);
		}
		$this->session->set('redirect',$this->data['redirect']);
        $this->session->set('state',md5(rand()));
        $this->data['live_client_id'] = $this->config->get('social_live_client_id');
        $this->data['google_client_id'] = $this->config->get('social_google_client_id');
        $this->data['facebook_app_id']  = $this->config->get('social_facebook_app_id');
        $this->data['twitter_oauth_token_secret'] = $this->config->get('social_twitter_oauth_token_secret');
        
		$this->id = 'header';
        $this->template = 'default/common/header.tpl';
    	$this->render();
	}
}