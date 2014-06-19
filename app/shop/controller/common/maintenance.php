<?php
class ControllerCommonMaintenance extends Controller {
	public function index() {
        $this->load->language('common/maintenance');
		$this->language->load('common/footer');
        $this->document->title = $this->language->get('heading_title') ." - ". $this->config->get('config_title');
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_name'), date('Y', time()));
        
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
		$this->data['links']      = $this->document->links;			
		$this->data['breadcrumbs']= $this->document->breadcrumbs;
        
        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href'      => (Url::createUrl('common/maintenance')),
            'text'      => $this->language->get('text_maintenance'),
            'separator' => false
        ); 
        $this->data['google_analytics_code'] = $this->config->get('google_analytics_code');
        
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        $this->data['message'] = $this->language->get('text_message');
        
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
        
        if (is_file(DIR_CSS."custom-". $this->config->get('config_theme_id') ."-". $this->config->get('config_template') .".css")) {
            $styles[] = array('media'=>'all','href'=>$csspath."custom-". $this->config->get('config_theme_id') ."-". $this->config->get('config_template') .".css");
        }
        $this->data['styles'] = array_merge($styles,$this->styles);
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/maintenance.tpl')) {
            $this->template = $this->config->get('config_template') . '/common/maintenance.tpl';
        } else {
            $this->template = 'default/common/maintenance.tpl';
        }
        
            $this->load->helper('widgets');
            $widgets = new NecoWidget($this->registry,$this->Route);
            foreach ($widgets->getWidgets('main') as $widget) {
                $settings = unserialize($widget['settings']);
                if ($settings->asyn) {
                    $url = Url::createUrl("{$settings->route}",$settings->params);
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
                        .appendTo('".$settings->target."');"
                    );
                } else {
                    if (isset($settings->route)) {
                        $this->data['widgets'][] = $widget['name'];
                        $this->children[$widget['name']] = $settings->route;
                        $this->widget[$widget['name']] = $widget;
                    }
                }
            }
            
        $this->response->setOutput($this->render(true));
    }
    
    public function check() {
        if ($this->config->get('config_maintenance')) {
            
            // Show site if logged in as admin
			require_once(DIR_SYSTEM . 'library/user.php');
			$this->registry->set('user', new User($this->registry));
            
            if (!$this->user->isLogged()) {
                return $this->forward('common/maintenance');
            }
        }
    }
}
