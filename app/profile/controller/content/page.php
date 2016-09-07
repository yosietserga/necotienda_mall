<?php 
class ControllerContentPage extends Controller {
	public function index() {  
    	$this->language->load('content/page');
		$this->load->model('content/page');
		
		$this->document->breadcrumbs = array();
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
		
		if (isset($this->request->get['page_id'])) {
			$path = '';
			$parts = explode('_', $this->request->get['page_id']);
			foreach ($parts as $id) {
				$page_info = $this->modelPage->getById($id);
				if ($page_info) {
					if (!$path) {
						$path = $id;
					} else {
						$path .= '_' . $id;
					}

	       			$this->document->breadcrumbs[] = array(
   	    				'href'      => Url::createUrl('content/page',array('page_id'=>$path)),
    	   				'text'      => $page_info['name'],
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}
			$page_id = array_pop($parts);
		} else {
			$page_id = 0;
		}
        
		$page_info = $this->modelPage->getById($page_id);
   		
		if ($page_info) {
            $this->session->set('redirect', Url::createUrl('content/page',array('page_id'=>$page_id)));
          
            $customerGroups = $this->modelPage->getProperty($page_id, 'customer_groups', 'customer_groups');
            if (($this->customer->isLogged() && in_array($this->customer->getCustomerGroupId(), $customerGroups)) || in_array(0, $customerGroups)) {
        		$cached = $this->cache->get('page.' . 
                        $this->request->get['page_id'] .
                        $this->config->get('config_language_id') . "." . 
                        $this->config->get('config_currency') . "." . 
                        (int)$this->config->get('config_store_id')
                );
                $this->load->library('user');
           	    if ($cached && !$this->user->isLogged()) {
                    $this->response->setOutput($cached, $this->config->get('config_compression'));
           	    } else {
        	  		$this->document->title = $page_info['title']; 
        
              		$this->document->breadcrumbs[] = array(
                		'href'      => Url::createUrl("content/page") . '&page_id=' . $this->request->get['page_id'],
                		'text'      => $page_info['title'],
                		'separator' => $this->language->get('text_separator')
              		);		
        			$this->data['breadcrumbs'] = $this->document->breadcrumbs;			
              		$this->data['heading_title']= $page_info['title'];
        			$this->data['description']  = html_entity_decode($page_info['description']);
        			$this->data['date_added']   = date('d-m-Y',strtotime($post_info['date_publish_start']));
        			$this->data['page_id']      = $page_info['post_id'];
                    $this->setvar('allow_reviews',$page_info,false);
                    $this->setvar('template',$page_info,false);
        			$image = isset($page_info['image']) ? $page_info['image'] : null;
            		$this->data['object_type'] = 'page';
              		
                    $this->loadWidgets();
                    
                    if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
                    $template = $this->modelPage->getProperty($page_id, 'style', 'view');
                    $default_template = ($this->config->get('default_view_page')) ? $this->config->get('default_view_page') : 'content/page.tpl';
                    $template = empty($template) ? $default_template : $template;
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
        			if (!$this->user->isLogged()) {
                		$this->cacheId = 'page.' . 
                            $this->request->get['page_id'] .
                            $this->config->get('config_language_id') . "." . 
                            $this->config->get('config_currency') . "." . 
                            (int)$this->config->get('config_store_id');
                    }
                            
        	  		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
                }
            } else {
          		$this->error404();
        	}
    	} else {
      		$this->error404();
    	}
  	}
    
    protected function error404() {
        $this->document->breadcrumbs[] = array(
            'href'      => Url::createUrl("content/page") . '&page_id=' . $this->request->get['page_id'],
        	'text'      => $this->language->get('text_error'),
        	'separator' => $this->language->get('text_separator')
  		);
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;	
	  	$this->document->title = $this->language->get('text_error');
			
      	$this->data['heading_title'] = $this->language->get('text_error');

      	$this->data['text_error'] = $this->language->get('text_error');

        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = ($this->config->get('default_view_page_error')) ? $this->config->get('default_view_page_error') : 'error/not_found.tpl';
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
    	$this->language->load('content/page');
		$this->load->model('content/page');
        
		$this->document->title = $this->language->get('heading_title') ." - ". $this->config->get('config_title');
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if ($this->request->hasQuery('page_id')) {
            $page_info = $this->modelPage->getById($this->request->getQuery('page_id'));
		}
        
		$this->document->breadcrumbs = array();
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("content/page/all"),
        	'text'      => $this->language->get('text_pages'),
        	'separator' => false
      	);
        if ($page_info) {
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("content/page",array('page_id'=>$this->request->getQuery('page_id'))),
            	'text'      => $page_info['title'],
            	'separator' => false
          	);
            $this->data['page_info'] = $page_info;
            $this->document->title = $page_info['seo_title'];
            $this->document->description = $page_info['meta_description'];
            $this->document->keywords = $page_info['meta_keywords'];
        }
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        $this->setvar('sort');
        $this->setvar('order');
        $this->setvar('page');
        $this->setvar('page_id');
        
        $data['page_id'] = ($this->request->hasQuery('page_id')) ? $this->request->getQuery('page_id') : null;
        $data['page'] = ($this->request->hasQuery('page')) ? $this->request->getQuery('page') : 1;
        $data['limit']= ($this->request->hasQuery('limit')) ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');
        $data['sort'] = ($this->request->hasQuery('sort')) ? $this->request->getQuery('sort') : 'p.date_publish_start';
        $data['order']= ($this->request->hasQuery('order')) ? $this->request->getQuery('order') : 'DESC';

		$paramPageId  = ($this->request->hasQuery('page_id')) ? '&page_id=' . $this->request->getQuery('page_id') : "";
		$paramLimit   = ($this->request->hasQuery('limit')) ? '&limit=' . $this->request->getQuery('limit') : "";
		$paramSort    = ($this->request->hasQuery('sort')) ? '&sort=' . $this->request->getQuery('sort') : "";
		$paramOrder   = ($this->request->hasQuery('order')) ? '&order=' . $this->request->getQuery('order') : "";
		$paramPage    = ($this->request->hasQuery('page')) ? '&page=' . $this->request->getQuery('page') : "";
		$url = $paramPageId . $paramSort . $paramOrder . $paramPage;

        $this->data['sorts'] = array();
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => Url::createUrl('content/page/all','&sort=p.sort_order&order=ASC'.$paramPageId.$paramPage.$paramLimit));
				
        $this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => Url::createUrl('content/page/all','&sort=pd.title&order=ASC'.$paramPageId.$paramPage.$paramLimit));
 
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
			'href'  => Url::createUrl('content/page/all','&sort=pd.title&order=DESC'.$paramPageId.$paramPage.$paramLimit));  
            
        $data['start'] = ($data['page'] - 1) * $data['limit'];
		$page_total = $this->modelPage->getTotalLatest($data);
        if ($page_total) {
    		$pages = $this->modelPage->getLatest($data);
            foreach ($pages as $result) {
                if ($result['image']) {
                    $image = $result['image'];
    			} else {
                    $image = 'no_image.jpg';
                }
                $id = ($result['parent_id']) ? $result['parent_id'] ."_". $result['post_id']: $result['post_id'];
                $this->data['pages'][] = array(
                    'page_id'       => $id,
                    'title'         => $result['title'],
                    'seo_title'     => $result['seo_title'],
                    'tags'          => explode(";",$result['meta_keywords']),
                    'description'   => html_entity_decode($result['description']),
                    'meta_description' => $result['meta_description'],
                    //'categories'       => $this->modelReview->getByPageId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPageId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPageId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPageId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPageId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPageId($result['pid']),
                    'allow_reviews' => $result['allow_reviews'],
                    'thumb'         => NTImage::resizeAndSave($image, $this->config->get('config_image_post_width'), $this->config->get('config_image_post_height')),
                    'date_added'    => date('d-m-Y',strtotime($result['date_publish_start']))
                );
            }
 		}
        $this->load->library('pagination');
		$pagination = new Pagination(true);
		$pagination->total = $page_total;
		$pagination->page  = $data['page'];
		$pagination->limit = $data['limit'];
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = Url::createUrl("content/page/all").$paramPageId.$paramSort.$paramOrder.$paramLimit.'&page={page}';

		$this->data['pagination'] = $pagination->render();

        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = $this->modelPage->getProperty($page_id, 'style', 'view');
        $default_template = ($this->config->get('default_view_page_all')) ? $this->config->get('default_view_page_all') : 'content/pages.tpl';
        $template = empty($template) ? $default_template : $template;
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
    
	public function review() {
        $this->language->load('content/page');
		$this->load->auto('store/review');
		$this->load->auto('pagination');
        
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : $page = 1;
		$this->data['reviews'] = array();
		$review_total = $this->modelReview->getTotalReviewsByPageId($this->request->get['page_id']);
        if ($review_total) {
    		$results = $this->modelReview->getReviewsByPageId($this->request->get['page_id'], ($page - 1) * 5, 5);
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
            		'object_id'  => $result['post_id'],
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
    		$this->data['object_type'] = 'page';
   			
    		$pagination = new Pagination();
    		$pagination->total = $review_total;
    		$pagination->ajax = true;
    		$pagination->ajaxTarget = 'review';
    		$pagination->page = $page;
    		$pagination->limit = 5; 
    		$pagination->text = $this->language->get('text_pagination');
    		$pagination->url = Url::createUrl('content/page/review',array('page_id'=>$this->request->get['page_id'],'page'=>'{page}'));
    			
    		$this->data['pagination'] = $pagination->render();
        }
        
        $template = ($this->config->get('default_view_page_review')) ? $this->config->get('default_view_page_review') : 'content/review.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	public function comment() {
	   $this->language->load('content/page');
		$this->data['review_status']  = $this->config->get('config_review');
		$this->data['text_stars']     = sprintf($this->language->get('text_stars'), $average);
        $this->data['islogged']       = (int)$this->customer->islogged();
        $this->data['id']             = $this->request->getQuery('page_id');
        $this->data['object_type']    = 'page';
                    
        $this->scripts = array_merge($this->scripts,$scripts);
        
        $template = ($this->config->get('default_view_page_comment')) ? $this->config->get('default_view_page_comment') : 'content/comment.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
    public function deleteReview() {
		$this->load->auto('store/review');
		$review_id = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id) {
            $this->modelReview->deleteReview($review_id);
        }
    }
    
    public function likeReview() {
		$this->load->auto('store/review');
		$review_id = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
		$page_id = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id && $page_id) {
            $result = $this->modelReview->likeReview($review_id,$page_id,'page');
            $json['likes'] = $result['likes'];
            $json['dislikes'] = $result['dislikes'];
            $json['success'] = 1;
        }
        //TODO: registrar y enviar notificacion de que le gusta 
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
    }
    
    public function dislikeReview() {
		$this->load->auto('store/review');
		$review_id = $this->request->getPost('review_id') ? $this->request->getPost('review_id') : $this->request->getQuery('review_id');
		$page_id = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id && $page_id) {
            $result = $this->modelReview->dislikeReview($review_id,$page_id,'page');
            $json['likes'] = $result['likes'];
            $json['dislikes'] = $result['dislikes'];
            $json['success'] = 1;
        }
        //TODO: registrar y enviar notificacion de que no le gusta 
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
    }
    
	public function write() {
        $this->language->load('content/page');
		$this->load->auto('store/review');
        
		$page_id = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
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
            
    		$review_id = $this->modelReview->addReview($page_id, $this->request->post,'page');
            
            $json['review_id']  =
            $json['author']     =
            $json['id']    =
            $json['text']       =
            $json['customer_id']=             
            $json['date_added'] = '';
            
            if ($this->config->get('config_review_approve')) {
                $json['review_id']  = $review_id;
                $json['author']     = $this->customer->getFirstName() ." ". $this->customer->getLastName();
                $json['id']         = $page_id;
                $json['text']       = $this->request->post['text'];
                $json['rating']     = $this->request->post['rating'];
                $json['customer_id']= $this->customer->getId();
                $json['object_type']= 'page';
                $json['date_added'] = date('d-m-Y h:i A');
                $json['show'] = 1;
            }
            
            $this->notifyReview($page_id);
    		$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	
	public function reply() {
        $this->language->load('content/page');
		$this->load->auto('store/review');
        
		$this->request->post['page_id'] = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
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
            
    		$this->modelReview->addReply($this->request->post,'page');
            
            $json['review_id']  =
            $json['author']     =
            $json['page_id'] =
            $json['text']       =
            $json['customer_id']= 
            $json['date_added'] = '';
            if ($this->config->get('config_review_approve')) {
                $json['review_id']  = $this->request->post['review_id'];
                $json['author']     = $this->customer->getFirstName() ." ". $this->customer->getLastName();
                $json['page_id']    = $this->request->post['page_id'];
                $json['text']       = $this->request->post['text'];
                $json['customer_id']= $this->customer->getId();
                $json['date_added'] = date('d-m-Y');
                $json['show'] = 1;
            }
            
            $this->notifyReview($this->request->post['page_id']);
            
    		$json['success'] = $this->language->get('text_success');
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	
    protected function notifyReview($page_id) {
        if (!$page_id) return false;
        $this->load->auto('email/mailer');
        $this->load->auto('content/page');
        $this->load->auto('store/review');
        $this->load->auto('marketing/newsletter');
        
        $page_info = $this->modelPage->getById($page_id);
        if ($page_info) {
            $page = $this->modelNewsletter->getById($this->config->get('marketing_email_new_comment'));
            $subject = $page['title'];
            $message = str_replace("{%page_url%}",Url::createUrl('content/page',array('page_id'=>$page_id)),$page['description']);
            $message = str_replace("{%page_name%}",$page_info['title'],$message);
            
            $mailer = new Mailer;
            $reps = $this->modelReview->getCustomersReviewsByPageId($page_id);
            foreach ($reps as $k => $v) {
                $mailer->AddBCC($v['email'],$v['author']);
            }
            $mailer->AddBCC($this->config->get('config_email'),$this->config->get('config_name'));
            
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
            $mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
            $mailer->Subject = $subject;
            $mailer->Body = $message;
            $mailer->Send();
        }
    }
    
    public function relatedJson() {
        $json = array();
        $this->load->auto("content/page");
        $this->load->auto('image');
        $this->load->auto('json');
        $json['results'] = $this->modelPage->getRelated($this->request->get['page_id']);
        $width  = isset($_GET['width']) ? $_GET['width'] : 80;
        $height = isset($_GET['height']) ? $_GET['height'] : 80;
        foreach ($json['results'] as $k => $v) {
            if (!file_exists(DIR_IMAGE . $v['image'])) $json['results'][$k]['image'] = HTTP_IMAGE ."no_image.jpg";
            $json['results'][$k]['thumb'] = NTImage::resizeAndSave($v['image'], $width, $height);
        }
        
        if (!count($json['results'])) $json['error'] = 1;
        $this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }
    
	private function validate() {
		if (!$this->customer->islogged()) {
			$this->error['message'] = $this->language->get('error_login');
		}
		
		if (!$this->request->hasPost('page_id') && !$this->request->hasQuery('page_id')) {
			$this->error['message'] = $this->language->get('error_page');
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
		
		if (!$this->request->hasPost('page_id') && !$this->request->hasQuery('page_id')) {
			$this->error['message'] = $this->language->get('error_page');
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
