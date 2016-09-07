<?php
class ControllerContentPost extends Controller {
	public function index() {  
        $this->language->load('content/post');
    	$this->load->model('content/post');
    	
        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href'      => Url::createUrl("common/home"),
            'text'      => $this->language->get('text_home'),
            'separator' => false
       	);
    		
    	if (isset($this->request->get['post_id'])) {
            $post_id = $this->request->get['post_id'];
   		} else {
            $post_id = 0;
   		}
    		
    	$post_info = $this->modelPost->getById($post_id);
       		
        if ($post_info) {
            $customerGroups = $this->modelPost->getProperty($post_id, 'customer_groups', 'customer_groups');
            if (($this->customer->isLogged() && in_array($this->customer->getCustomerGroupId(), $customerGroups)) || in_array(0, $customerGroups)) {
        		$cached = $this->cache->get('post.' . 
                        $this->request->get['post_id'] .
                        $this->config->get('config_language_id') . "." . 
                        $this->config->get('config_currency') . "." . 
                        (int)$this->config->get('config_store_id')
                );
                $this->load->library('user');
           	    if ($cached && !$this->user->isLogged()) {
                    $this->response->setOutput($cached, $this->config->get('config_compression'));
           	    } else {
        	  		$this->document->title = $post_info['title']; 
              		$this->document->breadcrumbs[] = array(
                		'href'      => Url::createUrl("content/post",array('post_id'=>$this->request->get['post_id'])),
                		'text'      => $post_info['title'],
                		'separator' => $this->language->get('text_separator')
              		);	
                    $this->data['breadcrumbs'] = $this->document->breadcrumbs;
              		$this->data['heading_title'] = $post_info['title'];
        			$this->data['description']   = html_entity_decode($post_info['description']);
        			$this->data['date_added']   = date('d-m-Y',strtotime($post_info['date_publish_start']));
                    $this->setvar('allow_reviews',$post_info,false);
                    $this->setvar('template',$post_info,false);
                    $this->setvar('post_id',$post_info,0);
        			$image = isset($post_info['image']) ? $post_info['image'] : null;
              		
                    $this->loadWidgets();
                    
                    if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
                    $template = $this->modelPost->getProperty($post_id, 'style', 'view');
                    $default_template = ($this->config->get('default_view_post')) ? $this->config->get('default_view_post') : 'content/post.tpl';
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
                    
                    if (!$this->user->isLogged()) {
            		$this->cacheId = 'post.' . 
                        $this->request->get['post_id'] .
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
        		'href'      => Url::createUrl("content/post") . '&post_id=' . $this->request->get['post_id'],
        		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
  		);
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
	  	$this->document->title = $this->language->get('text_error');
      	$this->data['heading_title'] = $this->language->get('text_error');
      	$this->data['text_error'] = $this->language->get('text_error');
        
        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = ($this->config->get('default_view_post_error')) ? $this->config->get('default_view_post_error') : 'error/not_found.tpl';
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
    	$this->language->load('content/post');
		$this->load->model('content/post');
        
		$this->document->title = $this->language->get('heading_title') ." - ". $this->config->get('config_title');
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if ($this->request->hasQuery('post_id')) {
            $post_info = $this->modelPost->getById($this->request->getQuery('post_id'));
		}
        
		$this->document->breadcrumbs = array();
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("content/post/all"),
        	'text'      => $this->language->get('text_posts'),
        	'separator' => false
      	);
        if ($post_info) {
          	$this->document->breadcrumbs[] = array(
            	'href'      => Url::createUrl("content/post",array('post_id'=>$this->request->getQuery('post_id'))),
            	'text'      => $post_info['title'],
            	'separator' => false
          	);
            $this->data['post_info'] = $post_info;
            $this->document->title = $post_info['seo_title'];
            $this->document->description = $post_info['meta_description'];
            $this->document->keywords = $post_info['meta_keywords'];
        }
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        $this->setvar('sort');
        $this->setvar('order');
        $this->setvar('post');
        $this->setvar('post_id');
        
        $data['post_id'] = ($this->request->hasQuery('post_id')) ? $this->request->getQuery('post_id') : null;
        $data['post'] = ($this->request->hasQuery('post')) ? $this->request->getQuery('post') : 1;
        $data['limit']= ($this->request->hasQuery('limit')) ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');
        $data['sort'] = ($this->request->hasQuery('sort')) ? $this->request->getQuery('sort') : 'p.date_publish_start';
        $data['order']= ($this->request->hasQuery('order')) ? $this->request->getQuery('order') : 'DESC';

		$paramPostId  = ($this->request->hasQuery('post_id')) ? '&post_id=' . $this->request->getQuery('post_id') : "";
		$paramLimit   = ($this->request->hasQuery('limit')) ? '&limit=' . $this->request->getQuery('limit') : "";
		$paramSort    = ($this->request->hasQuery('sort')) ? '&sort=' . $this->request->getQuery('sort') : "";
		$paramOrder   = ($this->request->hasQuery('order')) ? '&order=' . $this->request->getQuery('order') : "";
		$paramPost    = ($this->request->hasQuery('post')) ? '&post=' . $this->request->getQuery('post') : "";
		$url = $paramPostId . $paramSort . $paramOrder . $paramPost;

        $this->data['sorts'] = array();
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => Url::createUrl('content/post/all','&sort=p.sort_order&order=ASC'.$paramPostId.$paramPost.$paramLimit));
				
        $this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => Url::createUrl('content/post/all','&sort=pd.title&order=ASC'.$paramPostId.$paramPost.$paramLimit));
 
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
			'href'  => Url::createUrl('content/post/all','&sort=pd.title&order=DESC'.$paramPostId.$paramPost.$paramLimit));  
            
        $data['start'] = ($data['post'] - 1) * $data['limit'];
		$post_total = $this->modelPost->getTotalLatest($data);
        if ($post_total) {
    		$posts = $this->modelPost->getLatest($data);
            foreach ($posts as $result) {
                if ($result['image']) {
                    $image = $result['image'];
    			} else {
                    $image = 'no_image.jpg';
                }
                $this->data['posts'][] = array(
                    'post_id'       => $result['post_id'],
                    'title'         => $result['title'],
                    'seo_title'     => $result['seo_title'],
                    'tags'          => explode(";",$result['meta_keywords']),
                    'description'   => html_entity_decode($result['description']),
                    'meta_description' => $result['meta_description'],
                    //'categories'       => $this->modelReview->getByPostId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPostId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPostId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPostId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPostId($result['pid']),
                    //'reviews'       => $this->modelReview->getByPostId($result['pid']),
                    'allowReviews'  => $result['allow_reviews'],
                    'thumb'         => NTImage::resizeAndSave($image, $this->config->get('config_image_post_width'), $this->config->get('config_image_post_height')),
                    'date_added'    => date('d-m-Y',strtotime($result['date_publish_start']))
                );
            }
 		}
        $this->load->library('pagination');
		$pagination = new Pagination(true);
		$pagination->total = $post_total;
		$pagination->post  = $data['post'];
		$pagination->limit = $data['limit'];
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = Url::createUrl("content/post/all").$paramPostId.$paramSort.$paramOrder.$paramLimit.'&post={post}';

		$this->data['pagination'] = $pagination->render();
        
        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = ($this->config->get('default_view_post_all')) ? $this->config->get('default_view_post_all') : 'content/posts.tpl';
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
        $this->language->load('content/post');
		$this->load->auto('store/review');
		$this->load->auto('pagination');
        
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : $page = 1;
		$this->data['reviews'] = array();
		$review_total = $this->modelReview->getTotalReviewsByPostId($this->request->get['post_id']);
        if ($review_total) {
    		$results = $this->modelReview->getReviewsByPostId($this->request->get['post_id'], ($page - 1) * 5, 5);
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
    		$this->data['object_type'] = 'post';
   			
    		$pagination = new Pagination();
    		$pagination->total = $review_total;
    		$pagination->ajax = true;
    		$pagination->ajaxTarget = 'review';
    		$pagination->page = $page;
    		$pagination->limit = 5; 
    		$pagination->text = $this->language->get('text_pagination');
    		$pagination->url = Url::createUrl('content/post/review',array('post_id'=>$this->request->get['post_id'],'page'=>'{page}'));
    			
    		$this->data['pagination'] = $pagination->render();
        }
        
        $template = ($this->config->get('default_view_post_review')) ? $this->config->get('default_view_post_review') : 'content/review.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
       	} else {
            $this->template = 'default/'. $template;
       	}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	public function comment() {
	   $this->language->load('content/post');
		$this->data['review_status'] = $this->config->get('config_review');
		$this->data['text_stars']    = sprintf($this->language->get('text_stars'), $average);
        $this->data['islogged']      = (int)$this->customer->islogged();
        $this->data['id']            = $this->request->getQuery('post_id');
        $this->data['object_type']     = 'post';
                    
        $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = ($this->config->get('default_view_post_comment')) ? $this->config->get('default_view_post_comment') : 'content/comment.tpl';
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
		$post_id = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id && $post_id) {
            $result = $this->modelReview->likeReview($review_id,$post_id,'post');
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
		$post_id = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->customer->islogged() && $review_id && $post_id) {
            $result = $this->modelReview->dislikeReview($review_id,$post_id,'post');
            $json['likes'] = $result['likes'];
            $json['dislikes'] = $result['dislikes'];
            $json['success'] = 1;
        }
        //TODO: registrar y enviar notificacion de que no le gusta 
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
    }
    
	public function write() {
        $this->language->load('content/post');
		$this->load->auto('store/review');
        
		$post_id = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
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
            
    		$review_id = $this->modelReview->addReview($post_id, $this->request->post,'post');
            
            $json['review_id']  =
            $json['author']     =
            $json['id'] =
            $json['text']       =
            $json['customer_id']=             
            $json['date_added'] = '';
            
            if ($this->config->get('config_review_approve')) {
                $json['review_id']  = $review_id;
                $json['author']     = $this->customer->getFirstName() ." ". $this->customer->getLastName();
                $json['id']         = $post_id;
                $json['text']       = $this->request->post['text'];
                $json['rating']     = $this->request->post['rating'];
                $json['customer_id']= $this->customer->getId();
                $json['object_type']= 'post';
                $json['date_added'] = date('d-m-Y h:i A');
                $json['show'] = 1;
            }
            
            $this->notifyReview($post_id);
    		$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	
	public function reply() {
        $this->language->load('content/post');
		$this->load->auto('store/review');
        
		$this->request->post['post_id'] = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getQuery('id');
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
            
    		$this->modelReview->addReply($this->request->post,'post');
            
            $json['review_id']  =
            $json['author']     =
            $json['post_id'] =
            $json['text']       =
            $json['customer_id']= 
            $json['date_added'] = '';
            if ($this->config->get('config_review_approve')) {
                $json['review_id']  = $this->request->post['review_id'];
                $json['author']     = $this->customer->getFirstName() ." ". $this->customer->getLastName();
                $json['post_id']    = $this->request->post['post_id'];
                $json['text']       = $this->request->post['text'];
                $json['customer_id']= $this->customer->getId();
                $json['date_added'] = date('d-m-Y');
                $json['show'] = 1;
            }
            
            $this->notifyReview($this->request->post['post_id']);
            
    		$json['success'] = $this->language->get('text_success');
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($json));
	}
	
    protected function notifyReview($post_id) {
        if (!$post_id) return false;
        $this->load->auto('email/mailer');
        $this->load->auto('content/post');
        $this->load->auto('store/review');
        $this->load->auto('marketing/newsletter');
        
        $post_info = $this->modelPost->getById($post_id);
        if ($post_info) {
            $page = $this->modelNewsletter->getById($this->config->get('marketing_email_new_comment'));
            $subject = $page['title'];
            $message = str_replace("{%post_url%}",Url::createUrl('content/post',array('post_id'=>$post_id)),$page['description']);
            $message = str_replace("{%post_name%}",$post_info['title'],$message);
            
            $mailer = new Mailer;
            $reps = $this->modelReview->getCustomersReviewsByPostId($post_id);
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
        $this->load->auto("content/post");
        $this->load->auto('image');
        $this->load->auto('json');
        $json['results'] = $this->modelPost->getRelated($this->request->get['post_id']);
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
		
		if (!$this->request->hasPost('post_id') && !$this->request->hasQuery('post_id')) {
			$this->error['message'] = $this->language->get('error_post');
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
		
		if (!$this->request->hasPost('post_id') && !$this->request->hasQuery('post_id')) {
			$this->error['message'] = $this->language->get('error_post');
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