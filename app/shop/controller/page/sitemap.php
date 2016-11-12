<?php  
class ControllerPageSitemap extends Controller {
	public function index() {
        $cached = $this->cache->get('sitemap.' . 
                $this->config->get('config_language_id') . "." . 
                $this->config->get('config_currency') . "." . 
                $this->config->get('config_store_id')
        );
        $this->load->library('user');
       	if ($cached && !$this->user->isLogged()) {
            $this->response->setOutput($cached, $this->config->get('config_compression'));
   	    } else {
        	$this->language->load('page/sitemap');
    		$this->document->title = $this->language->get('heading_title');
            
          	$this->document->breadcrumbs = array();
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("common/home"),
            	'text'      => $this->language->get('text_home'),
            	'separator' => false
          	);
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("page/sitemap"),
            	'text'      => $this->language->get('heading_title'),
            	'separator' => $this->language->get('text_separator')
          	);
    		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
        	$this->data['heading_title'] = $this->language->get('heading_title');
    
    		$this->load->model('store/category');
    		$this->load->model('content/page');
    		
    		$this->data['special'] = Url::createUrl("store/special");
    		$this->data['account'] = Url::createUrl("account/account");
        	$this->data['edit']    = Url::createUrl("account/edit");
        	$this->data['password']= Url::createUrl("account/password");
        	$this->data['address'] = Url::createUrl("account/address");
        	$this->data['history'] = Url::createUrl("account/history");
        	$this->data['download']= Url::createUrl("account/download");
        	$this->data['cart']    = Url::createUrl("checkout/cart");
        	$this->data['checkout']= Url::createUrl("checkout/shipping");
        	$this->data['search']  = Url::createUrl("store/search");
        	$this->data['contact'] = Url::createUrl("page/contact");
    		
    		$this->data['category'] = $this->getCategories(0);
    		$this->data['pages'] = $this->modelPage->getAll();
        	
            $this->loadWidgets();
        
            if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
            if (!$this->user->isLogged()) {
        		$this->cacheId = 'sitemap.' . 
                $this->config->get('config_language_id') . "." . 
                $this->config->get('config_currency') . "." . 
                $this->config->get('config_store_id');
            }
            
    		$this->children[] = 'common/column_left';
    		$this->children[] = 'common/column_right';
    		$this->children[] = 'common/nav';
    		$this->children[] = 'common/header';
    		$this->children[] = 'common/footer';
            
            $template = ($this->config->get('default_view_sitemap')) ? $this->config->get('default_view_sitemap') : 'page/sitemap.tpl';
        	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
                $this->template = $this->config->get('config_template') .'/'. $template;
       		} else {
                $this->template = 'default/'. $template;
        	}
                
            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }		
	}
	
	protected function getCategories($parent_id, $current_path = '') {
		$output = '';
		
		$results = $this->modelCategory->getCategories($parent_id);
		
		if ($results) {
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= '<li>';
			
			$output .= '<a href="' . Url::createUrl("store/category",array("path"=>$new_path))  . '">' . $result['name'] . '</a>';
			
        	$output .= $this->getCategories($result['category_id'], $new_path);
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
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