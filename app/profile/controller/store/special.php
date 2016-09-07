<?php 
class ControllerStoreSpecial extends Controller { 	
	public function index() { 
    	$this->language->load('store/special');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createUrl("store/home"),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
		$this->load->model('store/product');
			
		$product_total = $this->modelProduct->getTotalProductSpecials();
						
		if ($product_total) {
		  
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['v'])) {
				$url .= '&v=' . $this->request->get['v'];
			}
			
            
			if (isset($this->request->get['page'])) {
			 $url .= '&page=' . $this->request->get['page'];
			}			
            
            $this->data['url'] = $url;
            
            $this->loadWidgets();
        
            if ($scripts) $this->scripts = array_merge($this->scripts,$scripts);
            
            $template = ($this->config->get('default_view_special')) ? $this->config->get('default_view_special') : 'store/special.tpl';
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
		} else {
      		$this->error404();
		}
  	}
    
    protected function error404() {
        $this->data['text_error'] = $this->language->get('text_empty');
	  				
        $this->loadWidgets();
        
        $template = ($this->config->get('default_view_special_error')) ? $this->config->get('default_view_special_error') : 'error/not_found.tpl';
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
    
    public function home() { 
            $this->load->language("store/special");
			
        $page = ($this->request->hasQuery('page')) ? $this->request->getQuery('page') : 1;
        $sort = ($this->request->hasQuery('sort')) ? $this->request->getQuery('sort') : 'p.sort_order';
        $view = ($this->request->hasQuery('v')) ? $this->request->getQuery('v') : 'grid';
        $order = ($this->request->hasQuery('order')) ? $this->request->getQuery('order') : 'ASC';
        
		$url = '';
			
		if ($this->request->hasQuery('sort')) { $url .= '&sort=' . $this->request->getQuery('sort'); }
		if ($this->request->hasQuery('order')) { $url .= '&order=' . $this->request->getQuery('order'); }
		if ($this->request->hasQuery('v')) { $url .= '&v=' . $this->request->getQuery('v'); }
		if ($this->request->hasQuery('page')) { $url .= '&page=' . $this->request->getQuery('page'); }

            $this->data['sorts'] = array();
				
            $this->data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'p.sort_order-ASC',
					'href'  => Url::createUrl("store/special/home") .$url. '&sort=p.sort_order&order=ASC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") .$url. '&sort=p.sort_order&order=ASC&page='.$page .'")'
            );
				
			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => Url::createUrl("store/special/home") .$url. '&sort=pd.name&order=ASC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") .$url. '&sort=pd.name&order=ASC&page='.$page.'")'
			);
 
			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => Url::createUrl("store/special/home") . $url . '&sort=pd.name&order=DESC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") . $url . '&sort=pd.name&order=DESC&page='.$page.'")'
			);  

			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => Url::createUrl("store/special/home") . $url . '&sort=p.price&order=ASC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") . $url . '&sort=p.price&order=ASC&page='.$page.'")'
			); 

			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => Url::createUrl("store/special/home") . $url . '&sort=p.price&order=DESC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") . $url . '&sort=p.price&order=DESC&page='.$page.'")'
			); 
				
			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => Url::createUrl("store/special/home") . $url . '&sort=rating&order=DESC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") . $url . '&sort=rating&order=DESC&page='.$page.'")'
			); 
				
			$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href' => Url::createUrl("store/special/home") . $url . '&sort=rating&order=ASC&page='.$page,
					'ajax' => true,
					'ajaxFunction'  => 'sort(this,"'.Url::createUrl("store/special/home") . $url . '&sort=rating&order=ASC&page='.$page.'")'
			); 	
            
    		$this->load->model('store/product');
    			
    		$product_total = $this->modelProduct->getTotalProductSpecials();
    						
    		if ($product_total) {
        		$this->prefetch($sort,$order,$page);									
      		} 
    	
			$pagination = new Pagination(true);
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->ajax = true;
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = Url::createUrl("store/special/home") . $url . '&page={page}';
			
			$this->data['pagination'] = $pagination->render();

            $this->data['sort'] = $sort;
            $this->data['order'] = $order;
                
            $this->data['url'] = $url;
            
        $template = ($this->config->get('default_view_search_home')) ? $this->config->get('default_view_search_home') : 'store/products.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $template)) {
            $this->template = $this->config->get('config_template') .'/'. $template;
        } else {
            $this->template = 'default/'. $template;
        }
            
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
    protected function prefetch($sort,$order,$page) {
        $this->language->load('store/product');
        
		$this->load->model('store/product');
        
		$results = $this->modelProduct->getProductSpecials($sort, $order, ($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));
        	
        require_once(DIR_CONTROLLER . "store/product_array.php");
        
    }
}
