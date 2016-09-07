<?php 
class ControllerContentCategory extends Controller {
	public function index() {
    	$this->language->load('content/category');
		$this->load->model('content/category');
		$this->load->model('content/post');
		
		$this->document->breadcrumbs = array();
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("content/category"),
        	'text'      => $this->language->get('text_posts'),
        	'separator' => false
      	);
		
		$this->document->title = $this->language->get('heading_title') ." - ". $this->config->get('config_title');
		$this->data['heading_title'] = $this->language->get('heading_title');
		
        $this->setvar('post_category_id',null,0);
        if ($this->data['post_category_id']) {
            $category_info = $this->modelCategory->getById($this->data['post_category_id']);
        }
   		
		if ($category_info) {
	  		$this->document->title         = $category_info['seo_title'];
	  		$this->document->description   = $category_info['meta_description'];
	  		$this->document->keywords      = $category_info['meta_keywords'];
            
      		$this->document->breadcrumbs[] = array(
        		'href'      => Url::createUrl("content/category") . '&post_category_id=' . $this->request->get['post_category_id'],
        		'text'      => $category_info['title'],
        		'separator' => $this->language->get('text_separator')
      		);
            
		    $this->data['breadcrumbs'] = $this->document->breadcrumbs;
	  		$this->data['heading_title'] = $category_info['title'];
			$this->data['description']   = html_entity_decode($category_info['description']);
			$this->data['keywords']      = explode(";",$category_info['meta_keywords']);
            
            $this->setvar('title',$category_info);
      		
            $categories_total = $this->modelCategory->getTotalById($category_info['post_category_id']);
            $this->data['categories'] = array();
            
			if ($categories_total) {
				$results = $this->modelCategory->getAllById($category_info['post_category_id']);
        		foreach ($results as $result) {
					$image =  ($result['image']) ? $image = $result['image'] : 'no_image.jpg';
					$this->data['categories'][] = array(
            			'name'  => $result['name'],
            			'total' => $result['total'],
            			'href'  => Url::createUrl('content/category',array("post_category_id"=>$result['post_category_id'])),
            			'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
          			);
        		}
      		}
        }
        
        $this->setvar('sort');
        $this->setvar('order');
        $this->setvar('page');
        $this->setvar('post_category_id');
        
        $data['post_category_id']= ($this->request->hasQuery('post_category_id')) ? $this->request->getQuery('post_category_id') : null;
        $data['page']       = ($this->request->hasQuery('page')) ? $this->request->getQuery('page') : 1;
        $data['limit']      = ($this->request->hasQuery('limit')) ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');
        $data['sort']       = ($this->request->hasQuery('sort')) ? $this->request->getQuery('sort') : 'p.date_publish_start';
        $data['order']      = ($this->request->hasQuery('order')) ? $this->request->getQuery('order') : 'DESC';

		$paramId  = ($this->request->hasQuery('post_category_id')) ? '&post_category_id=' . $this->request->getQuery('post_category_id') : "";
		$paramLimit   = ($this->request->hasQuery('limit')) ? '&limit=' . $this->request->getQuery('limit') : "";
		$paramSort    = ($this->request->hasQuery('sort')) ? '&sort=' . $this->request->getQuery('sort') : "";
		$paramOrder   = ($this->request->hasQuery('order')) ? '&order=' . $this->request->getQuery('order') : "";
		$paramPage    = ($this->request->hasQuery('page')) ? '&page=' . $this->request->getQuery('page') : "";
		$url = $paramId . $paramSort . $paramOrder . $paramPage;

        $this->data['sorts'] = array();
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => Url::createUrl('content/category','&sort=p.sort_order&order=ASC'.$paramId.$paramPage.$paramLimit));
				
        $this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => Url::createUrl('content/category','&sort=pd.title&order=ASC'.$paramId.$paramPage.$paramLimit));
 
        $this->data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
			'href'  => Url::createUrl('content/category','&sort=pd.title&order=DESC'.$paramId.$paramPage.$paramLimit));  
            
		
        $data['start'] = ($data['page'] - 1) * $data['limit'];
		$post_total = $this->modelPost->getTotalByCategoryId($data);
		
        if ($post_total) {
    		$posts = $this->modelPost->getAllByCategoryId($data);
            foreach ($posts as $result) {
                $image =  ($result['image']) ? $image = $result['image'] : '';
                $this->data['posts'][] = array(
                    'post_id'       => $result['post_id'],
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
                    'thumb'         => NTImage::resizeAndSave($image, $this->config->get('config_image_post_width'), $this->config->get('config_image_post_height')),
                    'date_added'    => date('d-m-Y',strtotime($result['date_publish_start']))
                );
            }
 		}
		
        $this->load->library('pagination');
		$pagination = new Pagination(true);
		$pagination->total = $post_total;
		$pagination->page  = $data['page'];
		$pagination->limit = $data['limit'];
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = Url::createUrl("content/category").$paramId.$paramSort.$paramOrder.$paramLimit.'&page={page}';

		$this->data['pagination'] = $pagination->render();
        
        $this->loadWidgets();
        
        if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
        $template = $this->modelCategory->getProperty($this->data['post_category_id'], 'style', 'view');
        $default_template = ($this->config->get('default_view_post_category')) ? $this->config->get('default_view_post_category') : 'content/category.tpl';
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
