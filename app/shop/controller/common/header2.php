<?php   class ControllerCommonHeader2 extends Controller {
	protected function index() {
        $this->data['Url'] = new Url($this->registry);
        
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->set('language',$this->request->post['language_code']);
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect(Url::createUrl('common/home'));
			}
    	}		
		
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
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
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
        
        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        //TODO: detectar browser y cargar el estilo adecuado
        $styles[] = array('media'=>'all','href'=>$csspath.'screen.css'); 
        //$styles[] = array('media'=>'print','href'=>$csspath.'print.css');
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
			$styles[] = array('media'=>'all','href'=> str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_CSS) . 'theme.css');
		} else {
			$styles[] = array('media'=>'all','href'=> str_replace('%theme%','default',HTTP_THEME_CSS) . 'theme.css');
		}
        
        if (file_exists(DIR_CSS."custom-". (int)$this->config->get('theme_default_id') ."-". $this->config->get('config_template') .".css")) {
            $styles[] = array('media'=>'all','href'=>$csspath."custom-". (int)$this->config->get('theme_default_id') ."-". $this->config->get('config_template') .".css");
        }
        $this->data['styles'] = $this->styles = array_merge($styles,$this->styles);
        
		$this->data['store'] = $this->config->get('config_name');
		
		$this->data['isLogged']  = $this->customer->isLogged();
		
        if ($this->customer->isLogged()) {
			$this->data['greetings'] = 'Bienvenido(a), '. ucwords($this->customer->getFirstName() .' '. $this->customer->getLastName());
		}
        
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
		
		$this->data['language_code'] = $this->session->get('language');
		$this->data['languages'] = array();
		$results = $this->modelLanguage->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => HTTP_IMAGE . "flags/" . $result['image']
				);	
			}
		}
		
		$this->data['currency_code'] = $this->currency->getCode();
	    $this->data['currencies'] = array();
		$results = $this->modelCurrency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title' => $result['title'],
					'code'  => $result['code']
				);
			}
		}
        
		$this->id = 'header';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header2.tpl')) {
			$this->template = $this->config->get('config_template') . '/common/header2.tpl';
		} else {
			$this->template = 'default/common/header2.tpl';
		}
    	$this->render();
	}
}