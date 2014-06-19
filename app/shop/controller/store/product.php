<?php
class ControllerStoreProduct extends Controller {
	private $error = array(); 
	public $product_id;
	public function index() {
		$this->product_id = $product_id = ($this->request->hasQuery('product_id')) ? (int)$this->request->getQuery('product_id') : $product_id = 0;
		$product_info = $this->modelProduct->getProduct($product_id);
        
        $this->load->model('account/customer');
        $customer_info = $this->modelCustomer->getCustomer($product_info['owner_id']);
        
        $this->session->set('redirect',Url::createUrl('store/product',array('product_id'=>$product_id)));
        
		if (($product_info['status']==1 && $customer_info['banned']==0 && $product_info['paid']==1) || ($this->customer->getId()==$product_info['owner_id'])) {
            $cached = $this->cache->get('product.' . 
                    $product_id .
                    $this->config->get('config_language_id') . "." . 
                    $this->config->get('config_currency') . "." . 
                    (int)$this->config->get('config_store_id')
            );
            $this->load->library('user');
           	if ($cached && !$this->user->isLogged() && !$this->request->hasQuery('np')) {
                $this->response->setOutput($cached, $this->config->get('config_compression'));
       	    } else {
                //Languages
                $this->language->load('store/product');
                
                //Models
        		$this->load->auto('store/product');
        		$this->load->auto('store/category');
        		$this->load->auto('store/manufacturer');
        		$this->load->auto('store/review');
        		$this->load->auto('account/customer');
                
                //Libs
        		$this->load->auto('currency');
        		$this->load->auto('tax');
                
        		$this->document->breadcrumbs = array();
        		$this->document->breadcrumbs[] = array(
        			'href'      => Url::createUrl('store/home'),
        			'text'      => $this->language->get('text_home'),
        			'separator' => false
        		);
        		
        		if (isset($this->request->get['path'])) {
        			$path = '';
        			foreach (explode('_', $this->request->get['path']) as $path_id) {
        				$category_info = $this->modelCategory->getCategory($path_id);
        				$path .= (!$path) ? $path_id : '_' . $path_id;
        				if ($category_info) {
        					$this->document->breadcrumbs[] = array(
        						'href'      => Url::createUrl('store/category',array('path'=>$path)),
        						'text'      => $category_info['name'],
        						'separator' => $this->language->get('text_separator')
        					);
        				}
        			}
        		}
        		
        		if (isset($this->request->get['manufacturer_id'])) {
        			$manufacturer_info = $this->modelManufacturer->getManufacturer($this->request->get['manufacturer_id']);
        			if ($manufacturer_info) {	
        				$this->document->breadcrumbs[] = array(
        					'href'	    => Url::createUrl('store/manufacturer',array('manufacturer_id'=>$this->request->get['manufacturer_id'])),
        					'text'	    => $manufacturer_info['name'],
        					'separator' => $this->language->get('text_separator')
        				);
        			}
        		}
        		
        		if (isset($this->request->get['keyword'])) {
        			$url = '';
        			if (isset($this->request->get['category_id'])) {
        				$url .= '&category_id=' . $this->request->get['category_id'];
        			}
        			if (isset($this->request->get['description'])) {
        				$url .= '&description=' . $this->request->get['description'];
        			}
        			$this->document->breadcrumbs[] = array(
        				'href'      => Url::createUrl('store/search','&keyword=' . $this->request->get['keyword'] . $url),
        				'text'      => $this->language->get('text_search'),
        				'separator' => $this->language->get('text_separator')
        			);	
        		}
    		
    			$url = '';
    			if (isset($this->request->get['path'])) {
    				$url .= '&path=' . $this->request->get['path'];
    			}
    			if (isset($this->request->get['manufacturer_id'])) {
    				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
    			}
    			if (isset($this->request->get['keyword'])) {
    				$url .= '&keyword=' . $this->request->get['keyword'];
    			}
    			if (isset($this->request->get['category_id'])) {
    				$url .= '&category_id=' . $this->request->get['category_id'];
    			}
    			if (isset($this->request->get['description'])) {
    				$url .= '&description=' . $this->request->get['description'];
    			}						
    			$this->document->breadcrumbs[] = array(
    				'href'      => Url::createUrl('store/product',$url . '&product_id=' . $product_id),
    				'text'      => $product_info['name'],
    				'separator' => $this->language->get('text_separator')
    			);			
    			
                $this->data['breadcrumbs'] = $this->document->breadcrumbs;
                
    			$this->document->title       = $product_info['name'];
    			$this->document->keywords    = $product_info['meta_keywords'];
    			$this->document->description = $product_info['meta_description'];
    			$this->document->links       = array();
    			$this->document->links[]     = array(
    				'href' => Url::createUrl('store/product',array('product_id'=>$product_id)),
    				'rel'  => 'canonical'
    			);
    
    			$this->data['heading_title'] = $product_info['name'];
    			$this->data['text_minimum']  = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
    			
    			$average = ($this->config->get('config_review')) ? $this->modelReview->getAverageRating($product_id) : false;
    
    			$this->data['review_status'] = $this->config->get('config_review');
    			$this->data['text_stars']    = sprintf($this->language->get('text_stars'), $average);
    			$this->data['action']        = Url::createUrl('checkout/cart');
    			$this->data['redirect']      = Url::createUrl('store/product',$url . '&product_id=' . $product_id);
    
    			$image = isset($product_info['image']) ? $product_info['image'] : $image = 'no_image.jpg';
    			$this->data['popup']=NTImage::resizeAndSave($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
    			$this->data['thumb']=NTImage::resizeAndSave($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
    
                $imgProduct = array(
                    'popup'  => NTImage::resizeAndSave($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                    'preview'=> NTImage::resizeAndSave($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                    'thumb'  => NTImage::resizeAndSave($image, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                );
    
    			$this->data['product_info'] = $product_info;
    			
   				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                
    			if ($product_info['quantity'] <= 0) {
    				$this->data['stock'] = $product_info['stock'];
    			} else {
    				if ($this->config->get('config_stock_display')) {
    					$this->data['stock'] = $product_info['quantity'];
    				} else {
    					$this->data['stock'] = $this->language->get('text_instock');
    				}
    			}
    			
                $this->data['paid']          = $product_info['paid'];
                $this->data['expire']        = strtotime($product_info['date_end']) < strtotime(date('d-m-Y'));
                $this->data['isOwner']       = ($this->customer->getId()==$product_info['owner_id']);
    			$this->data['model']         = $product_info['model'];
    			$this->data['total_visits']  = $product_info['total_visits'];
    			$this->data['manufacturer']  = $product_info['manufacturer'];
                $this->data['total_comments']= $this->modelReview->getTotalReviews($product_id);
    			$this->data['description']   = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			    $this->data['attributes']    = $this->modelProduct->getProperties($product_id, 'attribute');
			    $this->data['payment_methods']    = $this->modelProduct->getProperties($product_id, 'payment_methods');
			    $this->data['shipping_methods']   = $this->modelProduct->getProperties($product_id, 'shipping_methods');
			    $this->data['zones']         = $this->modelProduct->getZones($product_id);
    			$this->data['product_id']    = $product_id;
    			$this->data['average']       = $average;
    			$this->data['options']       = array();
    			$this->data['categories']    = $this->modelProduct->getCategoriesByProduct(array('product_id'=>$product_id));
                
                $this->data['owner']         = $this->modelCustomer->getCustomer($product_info['owner_id']);
                $this->data['owner_rating']  = $this->modelReview->getAverageRating($product_info['owner_id'],'seller');
                $this->data['owner_reviews'] = $this->modelReview->getTotalReviews($product_info['owner_id'],'seller');
                $this->data['owner_products']= $this->modelProduct->getProductsByCustomerId($product_info['owner_id']);
                $this->data['owner_total_products'] = $this->modelProduct->getTotalProductsByCustomerId($product_info['owner_id'],array());
                $this->data['owner_total_active_products'] = $this->modelProduct->getTotalProductsByCustomerId($product_info['owner_id'],array('status'=>1));
                
        	    $this->load->library('browser');
                $this->data['browser'] = new Browser;
                $this->data['Image'] = new NTImage;
                //TODO: cuantas ventas ha concretado, cuantas recomendaciones tiene y cual es el promedio
                /**
                 * total contactos
                 * total ventas
                 * */
                
    			$this->data['images'] = array();
    			$results = $this->modelProduct->getProductImages($product_id);
    			
    			foreach ($results as $k => $result) {
    				$this->data['images'][$k] = array(
    					'popup' => NTImage::resizeAndSave($result['image'] , $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
    					'preview' => NTImage::resizeAndSave($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
    					'thumb' => NTImage::resizeAndSave($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
    				);
    			}
                $k = count($this->data['images']) + 1;
                $this->data['images'][$k] = $imgProduct;
                
                $this->data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
                $this->data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
                $this->data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
                $this->data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
                $this->data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
                $this->data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
                
    			$this->modelProduct->updateStats($this->request->getQuery('product_id'),(int)$this->customer->getId());
    			
    			$this->data['tags'] = array();
    					
    			$results = $this->modelProduct->getProductTags($product_id);
    			
    			foreach ($results as $result) {
    				if ($result['tag']) {
    					$this->data['tags'][] = array(
    						'tag'	=> $result['tag'],
    						'href'	=> Url::createUrl('store/search',array('q'=>$result['tag']))
    					);
    				}
    			}
    			
                    // style files
                    $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
                    
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            		  $csspath = str_replace("%theme%",$this->config->get('config_template'),$csspath);
            		} else {
            		  $csspath = str_replace("%theme%","default",$csspath);
            		}
                    
                    $styles[] = array('media'=>'all','href'=>$csspath .'joyride.css');
                    $this->styles = array_merge($this->styles,$styles);
                
                    $this->loadWidgets();
        
                    if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
                    $this->data['live_client_id'] = $this->config->get('social_live_client_id');
                    $this->data['facebook_app_id'] = $this->config->get('social_facebook_app_id');
                    $this->data['google_client_id'] = $this->config->get('social_google_client_id');
                    $this->data['twitter_oauth_token_secret'] = $this->config->get('social_twitter_oauth_token_secret');
                    
           			if (!$this->user->isLogged() && !$this->request->hasQuery('np') && !$expire && $paid && $this->customer->getId()==$product_info['owner_id']) {
                		$this->cacheId = 'product.' . 
                            $product_id .
                            $this->config->get('config_language_id') . "." . 
                            $this->config->get('config_currency') . "." . 
                            (int)$this->config->get('config_store_id');
                    }
                    
                    $template = $this->modelProduct->getProperty($product_id, 'style', 'view');
                    $default_template = ($this->config->get('default_view_product')) ? $this->config->get('default_view_product') : 'store/product.tpl';
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
        $url = '';
		if (isset($this->request->get['path'])) { $url .= '&path=' . $this->request->get['path']; }
		if (isset($this->request->get['manufacturer_id'])) { $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id']; }
		if (isset($this->request->get['keyword'])) { $url .= '&keyword=' . $this->request->get['keyword']; }
        if (isset($this->request->get['category_id'])) { $url .= '&category_id=' . $this->request->get['category_id']; }
		if (isset($this->request->get['description'])) { $url .= '&description=' . $this->request->get['description']; }	
		
        $this->document->breadcrumbs[] = array(
            'href'      => Url::createUrl('store/product',$url . '&product_id=' . $product_id),
        	'text'      => $this->language->get('text_error'),
        	'separator' => $this->language->get('text_separator')
		);			
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
      	$this->document->title     = $this->data['heading_title'] = $this->language->get('text_error');
      	$this->data['continue']    = Url::createUrl('store/home');
	  
        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = ($this->config->get('default_view_product_error')) ? $this->config->get('default_view_product_error') : 'error/not_found.tpl';
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
    	$this->language->load('store/product');
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array( 
       		'href'      => Url::createUrl("common/home"),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
   		$this->document->breadcrumbs[] = array( 
       		'href'      => Url::createUrl("store/product/all"),
       		'text'      => $this->language->get('text_products'),
      		'separator' => false
   		);
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
		$data['filter_keyword']       = $this->request->hasQuery('q') ? $this->request->getQuery('q') : '';
		$data['filter_price_start']   = $this->request->hasQuery('ps') ? $this->request->getQuery('ps') : '';
		$data['filter_price_end']     = $this->request->hasQuery('pe') ? $this->request->getQuery('pe') : '';
		$data['filter_color']         = $this->request->hasQuery('co') ? $this->request->getQuery('co') : '';
		$data['filter_category']      = $this->request->hasQuery('c') ? $this->request->getQuery('c') : '';
		$data['filter_manufacturer']  = $this->request->hasQuery('m') ? $this->request->getQuery('m') : '';
        
		$data['page']   = $this->request->hasQuery('page') ? $this->request->getQuery('page') : 1;
		$data['sort']   = $this->request->hasQuery('sort') ? $this->request->getQuery('sort') : 'pd.name';
		$data['order']  = $this->request->hasQuery('order') ? $this->request->getQuery('order') : 'ASC';
		$data['limit']  = $this->request->hasQuery('limit') ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');
        
        $this->data['sorts'] = array();
        
		$url = '';
		if ($this->request->hasQuery('q'))    { $url .= '&q=' . $this->request->getQuery('q'); }
		if ($this->request->hasQuery('ps'))   { $url .= '&ps=' . $this->request->getQuery('ps'); }
		if ($this->request->hasQuery('pe'))   { $url .= '&pe=' . $this->request->getQuery('pe'); }
		if ($this->request->hasQuery('co'))   { $url .= '&co=' . $this->request->getQuery('co'); }
		if ($this->request->hasQuery('c'))    { $url .= '&c=' . $this->request->getQuery('c'); }
		if ($this->request->hasQuery('m'))    { $url .= '&m=' . $this->request->getQuery('m'); }
		if ($this->request->hasQuery('page')) { $url .= '&page=' . $this->request->getQuery('page'); }
		if ($this->request->hasQuery('limit')){ $url .= '&limit=' . $this->request->getQuery('limit'); }
		if ($this->request->hasQuery('v'))    { $url .= '&v=' . $this->request->getQuery('v'); }
        
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
            'value' => 'p.sort_order-ASC',
			'href'  => Url::createUrl("store/product/all",'&sort=p.sort_order&order=ASC'. $url)
        );
        
		$this->data['sorts'][] = array(
            'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => Url::createUrl("store/product/all",'&sort=pd.name&order=ASC'. $url)
			);
 
		$this->data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => Url::createUrl("store/product/all",'&sort=pd.name&order=DESC'. $url)
		);  

		$this->data['sorts'][] = array(
            'text'  => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
			'href'  => Url::createUrl("store/product/all",'&sort=p.price&order=ASC'. $url)
		); 

		$this->data['sorts'][] = array(
            'text'  => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
			'href'  => Url::createUrl("store/product/all",'&sort=p.price&order=DESC'. $url)
		); 
        
		$this->data['sorts'][] = array(
            'text'  => $this->language->get('text_rating_asc'),
            'value' => 'p.rating-ASC',
			'href'  => Url::createUrl("store/product/all",'&sort=p.rating&order=ASC'. $url)
		); 

		$this->data['sorts'][] = array(
            'text'  => $this->language->get('text_rating_desc'),
            'value' => 'p.rating-DESC',
			'href'  => Url::createUrl("store/product/all",'&sort=p.rating&order=DESC'. $url)
		);
        
		$this->load->model('store/product');
		$this->load->model('store/review');
        $data['start'] = ($data['page'] - 1) * $data['limit'];
		$product_total = $this->modelProduct->getTotalByKeyword($data);
		if ($product_total) {
    		$url = '';
    		if ($this->request->hasQuery('q'))    { $url .= '&q=' . $this->request->getQuery('q'); }
    		if ($this->request->hasQuery('ps'))   { $url .= '&ps=' . $this->request->getQuery('ps'); }
    		if ($this->request->hasQuery('pe'))   { $url .= '&pe=' . $this->request->getQuery('pe'); }
    		if ($this->request->hasQuery('co'))   { $url .= '&co=' . $this->request->getQuery('co'); }
    		if ($this->request->hasQuery('c'))    { $url .= '&c=' . $this->request->getQuery('c'); }
    		if ($this->request->hasQuery('m'))    { $url .= '&m=' . $this->request->getQuery('m'); }
    		if ($this->request->hasQuery('order')){ $url .= '&order=' . $this->request->getQuery('order'); }
    		if ($this->request->hasQuery('sort')) { $url .= '&sort=' . $this->request->getQuery('sort'); }
    		if ($this->request->hasQuery('limit')){ $url .= '&limit=' . $this->request->getQuery('limit'); }
            
            $this->data['products'] = array();
            $results = $this->modelProduct->getByKeyword($data);
    		foreach ($results as $result) {
    		    $image = !empty($result['image']) ? $result['image'] : 'no_image.jpg';
    
    			if ($this->config->get('config_review')) {
    				$rating = $this->modelReview->getAverageRating($result['product_id']);	
    			} else {
    				$rating = false;
    			}
                
    			$special = false;
    			$discount = $this->modelProduct->getProductDiscount($result['product_id']);
    			
    			if ($discount) {
    				$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
    			} else {
    				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
    				$special = $this->modelProduct->getProductSpecial($result['product_id']);
    				if ($special) {
    					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
    				}						
    			}
                
				$add = Url::createUrl('store/product',array('product_id'=>$result['product_id']));
                
    			$this->data['products'][] = array(
    				'product_id'    => $result['product_id'],
    				'name'    		=> $result['name'],
    				'model'   		=> $result['model'],
    				'overview'   	=> $result['meta_description'],
    				'rating'  		=> $rating,
    				'stars'   		=> sprintf($this->language->get('text_stars'), $rating),
    				'price'   		=> $price,
    				'options'   	=> $options,
    				'special' 		=> $special,
    				'image'   		=> NTImage::resizeAndSave($image, 38, 38),
    				'lazyImage'   		=> NTImage::resizeAndSave('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
    				'thumb'   		=> NTImage::resizeAndSave($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
    				'href'    		=> Url::createUrl('store/product',array('product_id'=>$result['product_id'])),
    				'add'    		=> $add
    			);
    		}
    
    		if (!$this->config->get('config_customer_price')) {
    			$this->data['display_price'] = true;
    		} elseif ($this->customer->isLogged()) {
    			$this->data['display_price'] = true;
    		} else {
    			$this->data['display_price'] = false;
    		}
            
            $this->load->library('pagination');
        	$pagination = new Pagination(true);
        	$pagination->total = $product_total;
        	$pagination->page  = $data['page'];
        	$pagination->limit = $data['limit'];
        	$pagination->text  = $this->language->get('text_pagination');
        	$pagination->url   = Url::createUrl("store/product/all",$url . '&page={page}');
        			
       		$this->data['pagination'] = $pagination->render();					
      	
            $this->data['gridView'] = Url::createUrl("store/product/all", $url . '&v=grid');
            $this->data['listView'] = Url::createUrl("store/product/all", $url . '&v=list');
            
        	if ($this->request->hasQuery('v')){ $url .= '&v=' . $this->request->getQuery('v'); }
            
            $this->data['url'] = $url;
        }
        
        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
		$this->children[] = 'common/footer';
		$this->children[] = 'common/column_left';
		$this->children[] = 'common/column_right';
		$this->children[] = 'common/nav';
		$this->children[] = 'common/header';
		
        $template = ($this->config->get('default_view_product_all')) ? $this->config->get('default_view_product_all') : 'store/products_all.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
 		}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
	
	public function review() {
        //Languages
        $this->language->load('store/product');
        
        //Models
		$this->load->auto('store/review');
        
        //Libs
		$this->load->auto('pagination');
        
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : $page = 1;
		$this->data['reviews'] = array();
		$review_total = $this->modelReview->getTotalReviewsByProductId($this->request->get['product_id']);
        if ($review_total) {
    		$results = $this->modelReview->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
    		foreach ($results as $result) {
                $text = strip_tags($result['text']);
                $text = urldecode($text);
                $text = html_entity_decode($text);
                $text = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is','',$text);
    			$text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is','',$text);
    			$text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is','',$text);
    			$text = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is','',$text);
    			$text = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is','',$text);
    			$text = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is','',$text);
    			$text = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is','',$text);
    			$text = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is','',$text);
    			$text = preg_replace('/<noembed\b[^>]*>(.*?)<\/noembed>/is','',$text);
    			$text = htmlentities($text);
                
            	$this->data['reviews'][] = array(
            		'review_id'  => $result['review_id'],
            		'product_id' => $result['product_id'],
            		'author'     => $result['author'],
    				'rating'     => $result['rating'],
    				'likes'      => $result['likes'],
    				'dislikes'   => $result['dislikes'],
    				'text'       => $text,
    				'replies'    => $this->modelReview->getReplies($result['review_id']),
    				'isOwner'    => ($this->customer->getId() == $result['customer_id']) ? true : null,
            		'stars'      => sprintf($this->language->get('text_stars'), $result['rating']),
            		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            	);
          	}			
    		$this->data['isLogged'] = $this->customer->isLogged();
    			
    		$pagination = new Pagination();
    		$pagination->total = $review_total;
    		$pagination->ajax = true;
    		$pagination->ajaxTarget = 'review';
    		$pagination->page = $page;
    		$pagination->limit = 5; 
    		$pagination->text = $this->language->get('text_pagination');
    		$pagination->url = Url::createUrl('store/product/review',array('product_id'=>$this->request->get['product_id'],'page'=>'{page}'));
    			
    		$this->data['pagination'] = $pagination->render();
        }
        $template = ($this->config->get('default_view_product_review')) ? $this->config->get('default_view_product_review') : 'store/review.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
 		}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	public function comment() {
	   $this->language->load('store/product');
		$this->data['review_status']  = $this->config->get('config_review');
		$this->data['text_stars']     = sprintf($this->language->get('text_stars'), $average);
        $this->data['islogged']       = (int)$this->customer->islogged();
        $this->data['product_id']     = $this->request->getQuery('product_id');
                    
        $template = ($this->config->get('default_view_product_comment')) ? $this->config->get('default_view_product_comment') : 'store/comment.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
 		}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
    public function deleteReview() {
        //Models
		$this->load->auto('store/review');
        
		$review_id = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id) {
            $this->modelReview->deleteReview($review_id);
        }
    }
    
    public function likeReview() {
        //Models
		$this->load->auto('store/review');
        
		$review_id = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
		$product_id = $this->request->getPost('product_id') ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id && $product_id) {
            $result = $this->modelReview->likeReview($review_id,$product_id);
            $json['likes'] = $result['likes'];
            $json['dislikes'] = $result['dislikes'];
            $json['success'] = 1;
        }
        //TODO: registrar y enviar notificacion de que le gusta 
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
    }
    
    public function dislikeReview() {
        //Models
		$this->load->auto('store/review');
        
		$review_id = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
		$product_id = $this->request->getPost('product_id') ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id && $product_id) {
            $result = $this->modelReview->dislikeReview($review_id,$product_id);
            $json['likes'] = $result['likes'];
            $json['dislikes'] = $result['dislikes'];
            $json['success'] = 1;
        }
        //TODO: registrar y enviar notificacion de que no le gusta 
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
    }
    
	public function write() {
        //Languages
        $this->language->load('store/product');
        
        //Models
		$this->load->auto('store/review');
        
		$product_id = $this->request->getPost('product_id') ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		  
            $text = strip_tags($this->request->post['text']);
            $text = urldecode($text);
            $text = html_entity_decode($text);
            $text = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<noembed\b[^>]*>(.*?)<\/noembed>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$this->request->post['text'] = htmlentities($text);
			$this->request->post['status'] = intval($this->config->get('config_review_approve'));
            
    		$review_id = $this->modelReview->addReview($product_id, $this->request->post);
            
            
            $json['review_id']  =
            $json['author']     =
            $json['product_id'] =
            $json['text']       =
            $json['customer_id']=             
            $json['date_added'] = '';
            
            if ($this->config->get('config_review_approve')) {
                $json['review_id']  = $review_id;
                $json['author']     = $this->customer->getFirstName() ." ". $this->customer->getLastName();
                $json['product_id'] = $product_id;
                $json['text']       = $this->request->post['text'];
                $json['rating']     = $this->request->post['rating'];
                $json['customer_id']= $this->customer->getId();
                $json['date_added'] = date('d-m-Y h:i A');
                $json['show'] = 1;
            }
            
            $this->notifyReview($product_id);
    		$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	
	public function reply() {
        //Languages
        $this->language->load('store/product');
        
        //Models
		$this->load->auto('store/review');
        
		$this->request->post['product_id'] = $this->request->getPost('product_id') ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
		$this->request->post['review_id'] = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
		$json = array();
		$json['success'] = 0;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateReply()) {
		  
            $text = strip_tags($this->request->post['text']);
            $text = urldecode($text);
            $text = html_entity_decode($text);
            $text = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$text = preg_replace('/<noembed\b[^>]*>(.*?)<\/noembed>/is',' [CONTENIDO ELIMINADO POR SEGURIDAD] ',$text);
			$this->request->post['text'] = htmlentities($text);
            
			$this->request->post['status'] = intval($this->config->get('config_review_approve'));
            
    		$this->modelReview->addReply($this->request->post);
            
            $json['review_id']  =
            $json['author']     =
            $json['product_id'] =
            $json['text']       =
            $json['customer_id']= 
            $json['date_added'] = '';
            if ($this->config->get('config_review_approve')) {
                $json['review_id']  = $this->request->post['review_id'];
                $json['author']     = $this->customer->getFirstName() ." ". $this->customer->getLastName();
                $json['product_id'] = $this->request->post['product_id'];
                $json['text']       = $this->request->post['text'];
                $json['customer_id']= $this->customer->getId();
                $json['date_added'] = date('d-m-Y');
                $json['show'] = 1;
            }
            
            $this->notifyReview($this->request->post['product_id']);
            $this->notifyReply($this->request->post['review_id'],$this->request->post['product_id']);
            
    		$json['success'] = $this->language->get('text_success');
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	
    protected function notifyReview($product_id) {
        if (!$product_id) return false;
        $this->load->auto('email/mailer');
        $this->load->auto('store/product');
        $this->load->auto('store/review');
        $this->load->auto('marketing/newsletter');
        $product_info = $this->modelProduct->getProduct($product_id);
        
        if ($this->config->get('marketing_email_new_comment') && $product_info) {
            $this->load->model("marketing/newsletter");
            $this->load->library('email/mailer');
            $this->load->library('BarcodeQR');
            $this->load->library('Barcode39');
            $mailer     = new Mailer;
            $qr         = new BarcodeQR;
            $barcode    = new Barcode39(C_CODE);
            
            $qrStore = "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png';
            $eanStore = "cache/" . str_replace(" ","_",$this->config->get('config_owner') ."_barcode_39_order_id_" . $order_id) . '.gif';
            
            if (!file_exists(DIR_IMAGE . $qrStore)) {
                $qr->url(HTTP_HOME);
                $qr->draw(150,DIR_IMAGE . $qrStore);
            }
            if (!file_exists(DIR_IMAGE . $eanStore)) {
                $barcode->draw(DIR_IMAGE . $eanStore);
            }
            
            $result = $this->modelNewsletter->getById($this->config->get('marketing_email_new_comment'));
            $message = $result['htmlbody'];

            $message = str_replace("{%store_logo%}",'<img src="'. HTTP_IMAGE . $this->config->get('config_logo') .'" alt="'. $this->config->get('config_name') .'" />',$message);
            $message = str_replace("{%store_url%}",HTTP_HOME,$message);
            $message = str_replace("{%store_owner%}",$this->config->get('config_owner'),$message);
            $message = str_replace("{%store_name%}",$this->config->get('config_name'),$message);
            $message = str_replace("{%store_rif%}",$this->config->get('config_rif'),$message);
            $message = str_replace("{%store_email%}",$this->config->get('config_email'),$message);
            $message = str_replace("{%store_telephone%}",$this->config->get('config_telephone'),$message);
            $message = str_replace("{%store_address%}",$this->config->get('config_address'),$message);
            $message = str_replace("{%product_url%}",Url::createUrl('store/product',array('product_id'=>$product_id)),$message);
            $message = str_replace("{%url_account%}",Url::createUrl('account/review'),$message);
            $message = str_replace("{%product_name%}",$product_info['name'],$message);
            $message = str_replace("{%fullname%}",$this->customer->getFirstName() ." ". $this->customer->getFirstName(),$message);
            $message = str_replace("{%company%}",$this->customer->getCompany(),$message);
            $message = str_replace("{%email%}",$this->customer->getEmail(),$message);
            $message = str_replace("{%qr_code_store%}",'<img src="'. HTTP_IMAGE . $qrStore .'" alt="QR Code" />',$message);
            $message = str_replace("{%barcode_39_order_id%}",'<img src="'. HTTP_IMAGE . $eanStore .'" alt="QR Code" />',$message);
                
            $message .= "<p style=\"text-align:center\">Powered By Necotienda&reg; ". date('Y') ."</p>";
            
            $subject = $this->config->get('config_owner') ." ". $this->language->get('text_new_comment');
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
            
            $reps = $this->modelReview->getCustomersReviewsByProductId($product_id);
            $this->load->library('validar');
            $validate = new Validar;
            foreach ($reps as $k => $v) {
                if (!$validate->validEmail($v['email'])) continue;
                $mailer->AddBCC($v['email'],$v['author']);
            }
            
            $mailer->AddBCC($this->config->get('config_email'),$this->config->get('config_name'));
        	$mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
        	$mailer->Subject = $subject;
        	$mailer->Body = html_entity_decode(htmlspecialchars_decode($message));
            $mailer->Send();
        }
    }
    
    protected function notifyReply($review_id,$product_id) {
        if (!$review_id) return false;
        $this->load->auto('email/mailer');
        $this->load->auto('store/product');
        $this->load->auto('account/customer');
        $this->load->auto('store/review');
        $this->load->auto('marketing/newsletter');
        $review_info = $this->modelReview->getById($review_id);
        $product_info = $this->modelProduct->getProduct($product_id);
        
        if ($this->config->get('marketing_email_new_reply') && $review_info) {
            $this->load->model("marketing/newsletter");
            $this->load->library('email/mailer');
            $this->load->library('BarcodeQR');
            $this->load->library('Barcode39');
            $mailer     = new Mailer;
            $qr         = new BarcodeQR;
            $barcode    = new Barcode39(C_CODE);
            
            $qrStore = "cache/" . str_replace(".","_",$this->config->get('config_owner')).'.png';
            $eanStore = "cache/" . str_replace(" ","_",$this->config->get('config_owner') ."_barcode_39_order_id_" . $order_id) . '.gif';
            
            if (!file_exists(DIR_IMAGE . $qrStore)) {
                $qr->url(HTTP_HOME);
                $qr->draw(150,DIR_IMAGE . $qrStore);
            }
            if (!file_exists(DIR_IMAGE . $eanStore)) {
                $barcode->draw(DIR_IMAGE . $eanStore);
            }
            
            $customer_info = $this->modelCustomer->getCustomer($review_info['customer_id']);
            
            $result = $this->modelNewsletter->getById($this->config->get('marketing_email_new_reply'));
            $message = $result['htmlbody'];

            $message = str_replace("{%store_logo%}",'<img src="'. HTTP_IMAGE . $this->config->get('config_logo') .'" alt="'. $this->config->get('config_name') .'" />',$message);
            $message = str_replace("{%store_url%}",HTTP_HOME,$message);
            $message = str_replace("{%store_owner%}",$this->config->get('config_owner'),$message);
            $message = str_replace("{%store_name%}",$this->config->get('config_name'),$message);
            $message = str_replace("{%store_rif%}",$this->config->get('config_rif'),$message);
            $message = str_replace("{%store_email%}",$this->config->get('config_email'),$message);
            $message = str_replace("{%store_telephone%}",$this->config->get('config_telephone'),$message);
            $message = str_replace("{%store_address%}",$this->config->get('config_address'),$message);
            $message = str_replace("{%product_url%}",Url::createUrl('store/product',array('product_id'=>$product_id)),$message);
            $message = str_replace("{%url_account%}",Url::createUrl('account/review'),$message);
            $message = str_replace("{%product_name%}",$product_info['name'],$message);
            $message = str_replace("{%fullname%}",$customer_info['firstname'] ." ". $customer_info['lastname'],$message);
            $message = str_replace("{%company%}",$customer_info['company'],$message);
            $message = str_replace("{%email%}",$customer_info['email'],$message);
            $message = str_replace("{%qr_code_store%}",'<img src="'. HTTP_IMAGE . $qrStore .'" alt="QR Code" />',$message);
            $message = str_replace("{%barcode_39_order_id%}",'<img src="'. HTTP_IMAGE . $eanStore .'" alt="QR Code" />',$message);
                
            $message .= "<p style=\"text-align:center\">Powered By Necotienda&reg; ". date('Y') ."</p>";
            
            $subject = $this->config->get('config_owner') ." ". $this->language->get('text_new_reply');
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
            $mailer->AddAddress($customer_info['email'],$customer_info['author']);
            $mailer->AddBCC($this->config->get('config_email'),$this->config->get('config_name'));
        	$mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
        	$mailer->Subject = $subject;
        	$mailer->Body = html_entity_decode($message);
            $mailer->Send();
        }
    }
    
    public function relatedJson() {
        $json = array();
        $this->load->auto("store/product");
        $this->load->auto('image');
        $this->load->auto('json');
        
        $json['results'] = $this->modelProduct->getProductRelatedByCustomer($this->request->get['product_id']);
        $width  = isset($_GET['width']) ? $_GET['width'] : 80;
        $height = isset($_GET['height']) ? $_GET['height'] : 80;
        foreach ($json['results'] as $k => $v) {
            if (!file_exists(DIR_IMAGE . $v['image'])) $json['results'][$k]['image'] = HTTP_IMAGE ."no_image.jpg";
            $json['results'][$k]['thumb'] = NTImage::resizeAndSave($v['image'], $width, $height);
            $json['results'][$k]['price'] = $this->currency->format($this->tax->calculate($v['price'], $v['tax_class_id'], $this->config->get('config_tax')));
        }
        
        if (!count($json['results'])) $json['error'] = 1;
        
        $this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }
    
    public function related() {
        //Languages
        $this->language->load('store/related');
        
        //Models
		$this->load->auto('store/product');
        
        //Libs
		$this->load->auto('image');
		$this->load->auto('currency');
		$this->load->auto('tax');
        
        $results = $this->modelProduct->getProductRelated($this->request->get['product_id']);
        require_once(DIR_CONTROLLER . "store/product_array.php");
        $template = ($this->config->get('default_view_product_related')) ? $this->config->get('default_view_product_related') : 'store/products_grid.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
 		}
        
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

	private function validate() {
		if (!$this->customer->islogged()) {
			$this->error['message'] = $this->language->get('error_login');
		}
		
		if (!$this->request->hasPost('product_id') && !$this->request->hasQuery('product_id')) {
			$this->error['message'] = $this->language->get('error_product');
		}
		
		if (empty($this->request->post['text'])) {
			$this->error['message'] = $this->language->get('error_text');
		}
        
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	private function validateReply() {
		if (!$this->customer->islogged()) {
			$this->error['message'] = $this->language->get('error_login');
		}
		
		if (!$this->request->hasPost('product_id') && !$this->request->hasQuery('product_id')) {
			$this->error['message'] = $this->language->get('error_product');
		}
		
		if (!$this->request->hasPost('review_id') && !$this->request->hasQuery('review_id')) {
			$this->error['message'] = $this->language->get('error_review');
		}
		
		if (empty($this->request->post['text'])) {
			$this->error['message'] = $this->language->get('error_text');
		}
        
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
    
    public function emailsent() {
        $product_id = ($this->request->hasPost('product_id')) ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
        if ($product_id) {
            $this->load->model('store/product');
            $this->modelProduct->productMailed($product_id,$this->customer->getId());
        }
    }
    
    public function productcalled() {
        $product_id = ($this->request->hasPost('product_id')) ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
        if ($product_id) {
            $this->load->model('store/product');
            $this->modelProduct->productCalled($product_id,$this->customer->getId());
        }
    }
    
    public function web() {
        $product_id = ($this->request->hasPost('product_id')) ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
        if ($product_id) {
            $this->load->model('store/product');
            $this->modelProduct->webVisited($product_id,$this->customer->getId());
        }
        if ($this->request->hasQuery('redirect')) {
            $redirect = (!strpos($this->request->getQuery('redirect'),'http')) ? 'http://'. $this->request->getQuery('redirect') : $this->request->getQuery('redirect');
            $this->redirect($redirect);
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
        
        $jspath = defined("CDN") ? CDN_JS : HTTP_THEME_JS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $jspath = str_replace("%theme%",$this->config->get('config_template'),$jspath);
       	} else {
            $jspath = str_replace("%theme%","default",$jspath);
       	}
        if (fopen($jspath.str_replace('controller','',strtolower(__CLASS__) . '.js'),'r')) {
            $javascripts[] = $jspath.str_replace('controller','',strtolower(__CLASS__) . '.js');
        }
        if (count($javascripts)) {
            $this->javascripts = array_merge($this->javascripts,$javascripts);
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
