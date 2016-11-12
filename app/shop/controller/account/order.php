<?php 
class ControllerAccountOrder extends Controller { 
	public function index() {
	   if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect', Url::createUrl("account/order"));
	  		$this->redirect(Url::createUrl("account/login"));
    	}
	
    	$this->language->load('account/history');
        
      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/account"),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/order"),
        	'text'      => $this->language->get('text_history'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_order'] = $this->language->get('text_order');
      		$this->data['text_status'] = $this->language->get('text_status');
     		$this->data['text_date_added'] = $this->language->get('text_date_added');
      		$this->data['text_customer'] = $this->language->get('text_customer');
      		$this->data['text_products'] = $this->language->get('text_products');
      		$this->data['text_total'] = $this->language->get('text_total');
            $this->data['text_transferencia'] = $this->language->get('text_transferencia');
            $this->data['text_deposito'] = $this->language->get('text_deposito');
            $this->data['transferencia_heading'] = $this->language->get('transferencia_heading');
            $this->data['transferencia_elija_pago'] = $this->language->get('transferencia_elija_pago');
            $this->data['transferencia_nombre'] = $this->language->get('transferencia_nombre');
            $this->data['transferencia_subanco'] = $this->language->get('transferencia_subanco');
            $this->data['transferencia_mibanco'] = $this->language->get('transferencia_mibanco');
            $this->data['transferencia_numero'] = $this->language->get('transferencia_numero');
            $this->data['transferencia_monto'] = $this->language->get('transferencia_monto');
            $this->data['transferencia_order'] = $this->language->get('transferencia_order');
            $this->data['transferencia_fecha'] = $this->language->get('transferencia_fecha');
            $this->data['transferecnia_observacion'] = $this->language->get('transferecnia_observacion');
            $this->data['entry_captcha'] = $this->language->get('entry_captcha');
            $this->data['transferencia_tipo_deposito'] = $this->language->get('transferencia_tipo_deposito');    

      		$this->data['button_view'] = $this->language->get('button_view');
      		$this->data['button_continue'] = $this->language->get('button_continue');
            $this->data['button_back'] = $this->language->get('button_back');
            
	   $data['page']   = $page =  ($this->request->get['page']) ? $this->request->get['page'] : 1;
	   $data['order_id']= $order_id =  ($this->request->get['order_id']) ? $this->request->get['order_id'] : null;
	   $data['sort']   = $sort =  ($this->request->get['sort']) ? $this->request->get['sort'] : 'o.date_end';
	   $data['order']  = $order =  ($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
	   $data['limit']  = $limit =  ($this->request->get['limit']) ? $this->request->get['limit'] : 25;
	   $data['order_status_id'] =  ($this->request->get['status']) ? $this->request->get['status'] : null;
       $data['start']  = ($page - 1) * $limit;
       
	   $url = '';
			
        if (isset($this->request->get['order_id'])) { $url .= '&order_id=' . $this->request->get['order_id']; }	
        if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }	
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }		
		if (isset($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; }			
		
		$this->load->model('account/order');
        $this->data['statuses'] = $this->modelOrder->getOrderStatuses();
        $order_total = $this->modelOrder->getTotalOrders($data);
        echo $order_total;
        if ($order_total) {
            foreach ($this->modelOrder->getOrders($data) as $key => $result) {
        		$this->data['orders'][] = array(
          			'order_id'   => $result['order_id'],
          			'name'       => $result['firstname'] .' '. $result['lastname'],
          			'status'     => $result['status'],
          			'status_id'  => $result['order_status_id'],
          			'date_added' => date('d-m-Y h:i A', strtotime($result['dateAdded'])),
          			'products'   => $this->modelOrder->getTotalOrderProductsByOrderId($result['order_id']),
          			'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
					'href'       => Url::createUrl("account/invoice",array('order_id'=>$result['order_id']))
        		);
            }
            
            $this->load->library('pagination');
            $pagination = new Pagination(true);
            $pagination->total = $order_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
    		$pagination->text = $this->language->get('text_pagination');
            $pagination->url = Url::createUrl('account/order') . $url . '&page={page}';
            $this->data['pagination'] = $pagination->render();
                  
        }  
        
		$this->children[] = 'common/nav';
		$this->children[] = 'account/column_left';
		$this->children[] = 'common/footer';
		$this->children[] = 'common/header';
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/order.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/order.tpl';
		} else {
			$this->template = 'default/account/order.tpl';
		}
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
}