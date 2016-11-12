<?php
class ControllerAccountSale extends Controller { 
	public function index() {
	   $this->language->load('account/sale');
       $this->load->library('image');
       $this->load->model('store/product');
       
	   if (!$this->customer->isLogged()) {  
      		$this->session->data['redirect'] = Url::createUrl('account/sale');
	  		$this->redirect(Url::createUrl('account/login'));
    	}
        
        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');
	
	   $page =  ($this->request->get['page']) ? $this->request->get['page'] : 1;
	   $data['sort'] = $sort =  ($this->request->get['sort']) ? $this->request->get['sort'] : 'p2p.date_end';
	   $data['order'] = $order =  ($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
	   $data['limit'] = $limit =  ($this->request->get['limit']) ? $this->request->get['limit'] : 5;
	   $data['keyword'] =  ($this->request->get['keyword']) ? $this->request->get['keyword'] : null;
	   $data['letter'] =  ($this->request->get['letter']) ? $this->request->get['letter'] : null;
	   $data['status'] =  ($this->request->get['status']) ? $this->request->get['status'] : null;
       $data['start'] = ($page - 1) * $limit;
       
	   $url = '';
			
        if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }	
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (isset($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; }			
		
		$this->data['letters'] = range('A', 'Z');
        
        $product_total = $this->modelProduct->getTotalProductsByCustomerId($this->customer->getId(),$data);
        
        if ($product_total) {
            $products = $this->modelProduct->getProductsByCustomerId($this->customer->getId(),$data);
            foreach ($products as $key => $value) {
                if (!empty($value['image']) && file_exists(DIR_IMAGE . $value['image'])) {
                    $image = $value['image'];
                } else {
                    $image = 'no_image.jpg';
                }
                
                $date_start = new DateTime($value['date_start']);
                $date_end = new DateTime($value['date_end']);
                $date_diff = $date_start->diff($date_end);
                
                if ($value['product_status']) {
                    $status = 'Activado';
                    $status_class = 'green';
                } else {
                    $status = 'Desactivado';
                    $status_class = 'red';
                }
                
                if ($date_diff->days == 0 || strtotime($date_end->format('Y-m-d')) < strtotime(date('Y-m-d'))) {
                    $status = 'Finalizado';
                    $status_class = 'orange';
                    $remaining = 0;
                } elseif ($date_diff->days > 0) {
                    $remaining = $date_diff->days;
                }
                
                $this->data['products'][] = array(
                    'product_id'=>$value['pid'],
                    'name'  =>$value['name'],
                    'model' =>$value['model'],
                    'price' =>$this->currency->format($this->tax->calculate($value['price'], $value['tax_class_id'], $this->config->get('config_tax'))),
                    'plan' =>$value['plan'],
                    'image' =>NTImage::resizeAndSave($image,50,50),
                    'viewed'=>(int)$value['views'],
                    'contacts'=>(int)$value['contacts'],
                    'remaining'=>$remaining,
                    'status'=>$status,
                    'status_class'=>$status_class,
                    'href'  =>Url::createUrl("store/product",array('product_id'=>$value['product_id']))
                ); 
            }
            
            $this->load->library('pagination');
            $pagination = new Pagination(true);
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
    		$pagination->text = $this->language->get('text_pagination');
            $pagination->url = Url::createUrl('account/sale') . $url . '&page={page}';
            $this->data['pagination'] = $pagination->render();
                  
        } 
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/sale.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/sale.tpl';
		} else {
			$this->template = 'default/account/sale.tpl';
		}
        
		$this->children[] = 'common/nav';
		$this->children[] = 'account/column_left';
		$this->children[] = 'common/footer';
		$this->children[] = 'common/header';

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}
    
    public function finish() {
        if ($this->customer->isLogged()) {
            $product_id = ($this->request->hasPost('product_id')) ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
            $this->load->model('store/product');
            $product = $this->modelProduct->getProduct($product_id);
            if ($product && ($product['owner_id']==$this->customer->getId())) {
                $result = $this->modelProduct->setFinished($product_id);
            } else {
                $result = 0;
            }
        }
        $this->load->library('json');
        $this->response->setOutput(Json::encode($result), $this->config->get('config_compression'));	
    }
    
    public function delete() {
        if ($this->customer->isLogged()) {
            $product_id = ($this->request->hasPost('product_id')) ? $this->request->getPost('product_id') : $this->request->getQuery('product_id');
            $this->load->model('store/product');
            $product = $this->modelProduct->getProduct($product_id);
            if ($product && ($product['owner_id']==$this->customer->getId())) {
                $result = $this->modelProduct->delete($product_id);
            } else {
                $result = 0;
            }
        }
        $this->load->library('json');
        $this->response->setOutput(Json::encode($result), $this->config->get('config_compression'));
    }
}