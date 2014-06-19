<?php 
class ControllerStoreCategory extends Controller {  
	public function index() { 
        
		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
      		'href'      => Url::createUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
       		'separator' => false
   		);	

		if (isset($this->request->get['path'])) {
			$path = '';
		
			$parts = explode('_', $this->request->get['path']);
		
			foreach ($parts as $path_id) {
				$category_info = $this->modelCategory->getCategory($path_id);
				
				if ($category_info) {
					if (!$path) {
						$path = $path_id;
					} else {
						$path .= '_' . $path_id;
					}

	       			$this->document->breadcrumbs[] = array(
   	    				'href'      => Url::createUrl('store/category',array('path'=>$path)),
    	   				'text'      => $category_info['name'],
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
		}
        
        $this->data['category_id'] = $category_id;
    	$category_info = $this->modelCategory->getCategory($category_id);
            
   		if ($category_info) {
    		$cached = $this->cache->get('category.' . 
                    $this->request->get['path'] .
                    (int)$category_id .
                    $this->config->get('config_language_id') . "." . 
                    $this->config->get('config_currency') . "." . 
                    (int)$this->config->get('config_store_id')
            );
            $this->load->library('user');
           	if ($cached && !$this->user->isLogged()) {
                $this->response->setOutput($cached, $this->config->get('config_compression'));
       	    } else {
                $this->document->title = $this->data['heading_title'] = $category_info['name'];
        		$this->document->description = $category_info['meta_description'];
        		$this->document->keywords = $category_info['meta_keywords'];
            
    			$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
    			$category_total = $this->modelCategory->getTotalCategoriesByCategoryId($category_id);
                $this->data['categories'] = array();
                
    			if ($category_total) {
    				$results = $this->modelCategory->getCategories($category_id);
    				
            		foreach ($results as $result) {
    					if ($result['image']) {
    						$image = $result['image'];
    					} else {
    						$image = 'no_image.jpg';
    					}
    					
    					$this->data['categories'][] = array(
                			'name'  => $result['name'],
                			'href'  => Url::createUrl('store/category',array("path"=>$this->request->get['path'] .'_'. $result['category_id'])) . $url,
                			'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
              			);
            		}
                    							
          		}
                  
    			$this->modelCategory->updateStats($this->request->getQuery('path'),(int)$this->customer->getId());
                
    			$product_total = $this->modelProduct->getTotalProductsByCategoryId($category_id);
    			
    			if (!$product_total) {
            		$this->data['text_error'] = $this->language->get('text_empty');
            		$this->data['button_continue'] = $this->language->get('button_continue');
            		$this->data['continue'] = Url::createUrl('common/home');						
          		}
                
                $this->loadWidgets();
        
                $this->scripts = array_merge($this->scripts,$scripts);
                
                $this->data['breadcrumbs'] = $this->document->breadcrumbs;
                
                if (!$this->user->isLogged()) {
            		$this->cacheId = 'category.' . 
                        $this->request->get['path'] .
                        (int)$category_id .
                        $this->config->get('config_language_id') . "." . 
                        $this->config->get('config_currency') . "." . 
                        (int)$this->config->get('config_store_id');
                }
                
                $template = $this->modelCategory->getProperty($this->data['category_id'], 'style', 'view');
                $default_template = ($this->config->get('default_view_product_category')) ? $this->config->get('default_view_product_category') : 'store/category.tpl';
                $template = empty($template) ? $default_template : $template;
        		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
                    $this->template = $this->config->get('config_template') .'/'. $template;
       			} else {
        			$this->template = 'default/'. $template;
        		}
                    
        		$this->children[] = 'common/column_left';
        		$this->children[] = 'common/column_right';
        		$this->children[] = 'common/nav';
        		$this->children[] = 'common/header';
        		$this->children[] = 'common/footer';
                
    			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
            }
    	} else {
			$this->error404();
		}
  	}
    
    protected function error404() {
        if (isset($this->request->get['path'])) {	
	       $this->document->breadcrumbs[] = array(
                'href'      => Url::createUrl('store/category',array("path"=>$this->request->get['path'])) . $url,
    	   		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
      		);
		}
		
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;	
		$this->document->title = $this->data['heading_title'] = $this->language->get('text_error');
        
        $this->loadWidgets();
        
        $template = ($this->config->get('default_view_product_category_error')) ? $this->config->get('default_view_product_category_error') : 'error/not_found.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
        
    	$this->children[] = 'common/column_left';
    	$this->children[] = 'common/column_right';
    	$this->children[] = 'common/nav';
    	$this->children[] = 'common/header';
    	$this->children[] = 'common/footer';
        
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }
    
	public function all() {
	   $this->language->load('store/category');
		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
      		'href'      => Url::createUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
       		'separator' => false
   		);	
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;	

        $this->document->title      = $this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->description= $this->language->get('meta_description');
		$this->document->keywords   = $this->language->get('meta_keywords');
        
		$this->data['categories'] = array();
        $results = $this->modelCategory->getCategories(0);
		foreach ($results as $result) {
            if ($result['image']) {
                $image = $result['image'];
            } else {
                $image = 'no_image.jpg';
            }
            $this->data['categories'][] = array(
                'name'  => $result['name'],
            	'href'  => Url::createUrl('store/category',array("path"=>$result['category_id'])),
            	'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
          		);
		}
        
        $this->loadWidgets();
        
        $template = ($this->config->get('default_view_product_category_all')) ? $this->config->get('default_view_product_category_all') : 'store/categories.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
        
		$this->children[] = 'common/nav';
		$this->children[] = 'common/column_left';
		$this->children[] = 'common/column_right';
		$this->children[] = 'common/footer';
		$this->children[] = 'common/header';
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
    public function home() {
        $this->setvar('sort');
        $this->setvar('order');
        $this->setvar('page');
        
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $sort = isset($this->request->get['sort']) ? $this->request->get['page'] : 'p.sort_order';
        $order= isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';

		$url  = '';
		$url .= !empty($this->data['sort']) ? '&sort=' . $this->data['sort'] : "";
		$url .= !empty($this->data['order']) ? '&order=' . $this->data['order'] : "";
		$url .= !empty($this->data['page']) ? '&page=' . $this->data['page'] : "";

        $this->data['sorts'] = array();
				
        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'p.sort_order-ASC',
					'href'  => Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=p.sort_order&order=ASC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id='. $this->request->get['category_id'] .'&sort=p.sort_order&order=ASC&page='.$page).'")');
				
        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => Url::createUrl('store/category/home','category_id='. $this->request->get['category_id'] .'&sort=pd.name&order=ASC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=pd.name&order=ASC&page='.$page).'")');
 
        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=pd.name&order=DESC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=pd.name&order=DESC&page='.$page).'")');  

        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=p.price&order=ASC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=p.price&order=ASC&page='.$page).'")'); 

        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=p.price&order=DESC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=p.price&order=DESC&page='.$page).'")'); 
				
        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=rating&order=DESC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=rating&order=ASC&page='.$page).'")'); 
				
        $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href' => Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=rating&order=ASC&page='.$page),
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . '&sort=rating&order=ASC&page='.$page).'")'); 	
        
        
		$this->load->auto('store/product'); 
        $product_total = $this->modelProduct->getTotalProductsByCategoryId($this->request->get["category_id"]);
			
        if ($product_total) {
            $this->prefetch($sort,$order,$page);									
 		} else {
            $this->document->title = $this->data['heading_title'] = $category_info['name'];
			$this->document->description = $category_info['meta_description'];
            $this->data['text_error'] = $this->language->get('text_empty');
            $this->data['products'] = array();
        }
        
        $this->load->auto('pagination');
        $pagination = new Pagination(true);
        $pagination->total = $product_total;
        $pagination->page  = $page;
        $pagination->ajax  = true;
        $pagination->limit = $this->config->get('config_catalog_limit');
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = Url::createUrl('store/category/home','category_id=' . $this->request->get['category_id'] . $url . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		$this->data['sort']  = $sort;
		$this->data['order'] = $order;
            
        $template = ($this->config->get('default_view_product_category_home')) ? $this->config->get('default_view_product_category_home') : 'store/products.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
    public function callback() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header('Cache-Control: no-cache, must-revalidate');
		header("Pragma: no-cache");
		header("Content-type: application/json");

		$this->load->model('store/category');
        $name = $this->request->get['term'];
        $parent_id = ($this->request->get['parent_id']) ? $this->request->get['parent_id'] : 0;
        $result = $this->modelCategory->getCategoriesByName($name,$parent_id);
        if (!$result) {
    		 $data['error'] = 1; 
		} else {
		  foreach ($result as $key => $value) {
		      $data[] = array(
                'id' => $value['category_id'],
                'label' => $value['name'],
                'value' => $value['name'],
              );
		  }
		      
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));
    }
    
    public function subcategories() {
		$this->load->model('store/category');
        $name = $this->request->get['term'];
        $parent_id = ($this->request->get['parent_id']) ? $this->request->get['parent_id'] : 0;
        $result = $this->modelCategory->getCategoriesByName($name,$parent_id);
        if ($result) {
            $data = '<option value="">Selecciona subcategor&iacute;a</option>';
            foreach ($result as $key => $value) {
                $data .= '<option value="'. $value['category_id'] .'">'. $value['name'] .'</option>';
            }  
		}
		$this->response->setOutput($data, $this->config->get('config_compression'));
    }
    
    public function attributes() {
		$this->load->model('store/category');
        $category_id = ($this->request->get['category_id']) ? $this->request->get['category_id'] : 0;
        $results = $this->modelCategory->getAttributes($category_id);
        $data = array();
        
        if ($results) {
            $data['success'] = 1;
            foreach ($results as $key => $result) {
                $data['items'][$key]['type']    = ($result['type']) ? $result['type'] : null;
                $data['items'][$key]['name']    = ($result['attribute']) ? $result['attribute'] : null;
                $data['items'][$key]['value']   = ($result['value']) ? $result['value'] : null;
                $data['items'][$key]['label']   = ($result['label']) ? $result['label'] : null;
                $data['items'][$key]['pattern'] = ($result['pattern']) ? $result['pattern'] : null;
                $data['items'][$key]['value'] = ($result['default']) ? $result['default'] : null;
                $data['items'][$key]['required']= ($result['required']) ? $result['required'] : null;
            }
		} else {
		  $data['error'] = 1;
		}
        
        $this->load->library('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));
    }
    
    protected function prefetch($sort,$order,$page) {
        
        $this->data['heading_title'] = "Productos";
        
		$results = $this->modelProduct->getProductsByCategoryId($this->request->get["category_id"], $sort, $order, ($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
        
        require_once(DIR_CONTROLLER . "store/product_array.php");
        
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
