<?php 
class ControllerLocalisationCurrency extends Controller {
	private $error = array();
 
	public function index() {
		$this->document->title = $this->language->get('heading_title');
		$this->getList();
	}

	public function insert() {
		$this->document->title = $this->language->get('heading_title');		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$currency_id = $this->modelCurrency->add($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/currency/update',array('currency_id'=>$currency_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/currency/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/currency')); 
            }
		}
		$this->getForm();
	}

	public function update() {
		$this->document->title = $this->language->get('heading_title');		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelCurrency->update($this->request->get['currency_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/currency/update',array('currency_id'=>$this->request->get['currency_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/currency/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/currency')); 
            }
		}
		$this->getForm();
	}

    /**
     * ControllerMarketingNewsletter::delete()
     * elimina un objeto
     * @return boolean
     * */
     public function delete() {
        $this->load->auto('localisation/currency');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCurrency->delete($id);
            }
		} else {
            $this->modelCurrency->delete($_GET['id']);
		}
     }
    
  	/**
  	 * ControllerMarketingNewsletter::copy()
     * duplicar un objeto
  	 * @return boolean
  	 */
  	public function copy() {
        $this->load->auto('localisation/currency');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCurrency->copy($id);
            }
		} else {
            $this->modelCurrency->copy($_GET['id']);
		}
        echo 1;
  	}

	private function getList() {
		$page = (isset($this->request->get['page'])) ? $this->request->get['page'] : 1;
		$sort = (isset($this->request->get['sort'])) ? $this->request->get['sort'] : 'title';
		$order = (isset($this->request->get['order'])) ? $this->request->get['order'] : 'ASC';
		
		$url = '';
	
		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];		
		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('localisation/currency') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = Url::createAdminUrl('localisation/currency/insert') . $url;
        
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

        // SCRIPTS        
        $scripts[] = array('id'=>'currencyList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/currency/activate")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_\" + e).attr('src','image/good.png');
                        } else {
                            $(\"#img_\" + e).attr('src','image/minus.png');
                        }
                   }
            	});
             }
            function copy(e) {
                $('#gridWrapper').hide();
                $('#gridPreloader').show();
                $.getJSON('".Url::createAdminUrl("localisation/currency/copy")."&id=' + e, function(data) {
                    $('#gridWrapper').load('". Url::createAdminUrl("localisation/currency/grid") ."',function(response){
                        $('#gridPreloader').hide();
                        $('#gridWrapper').show();
                    });
                });
            }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("localisation/currency/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function editAll() {
                return false;
            } 
            function addToList() {
                return false;
            } 
            function copyAll() {
                $('#gridWrapper').hide();
                $('#gridPreloader').show();
                $.post('". Url::createAdminUrl("localisation/currency/copy") ."',$('#form').serialize(),function(){
                    $('#gridWrapper').load('". Url::createAdminUrl("localisation/currency/grid") ."',function(){
                        $('#gridWrapper').show();
                        $('#gridPreloader').hide();
                    });
                });
                return false;
            } 
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("localisation/currency/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/currency/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/currency/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/currency/grid") ."',
                beforeSend:function(){
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                },
                success:function(data){
                    $('#gridPreloader').hide();
                    $('#gridWrapper').html(data).show();
                }
            });");
             
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->template = 'localisation/currency_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function grid() {
		$filter_title = isset($this->request->get['filter_title']) ? $this->request->get['filter_title'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'title';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_title'])) { $url .= '&filter_title=' . $this->request->get['filter_title']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 
		
		$this->data['currencies'] = array();

		$data = array(
			'filter_title'  => $filter_title,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$currency_total = $this->modelCurrency->getAllTotal();
        if ($currency_total) {
    		$results = $this->modelCurrency->getAll($data);
    
    		foreach ($results as $result) {
    			$action = array(
                    'activate'  => array(
                            'action'  => 'activate',
                            'text'  => $this->language->get('text_activate'),
                            'href'  =>'',
                            'img'   => 'good.png'
                    ),
                    'edit'      => array(
                            'action'  => 'edit',
                            'text'  => $this->language->get('text_edit'),
                            'href'  =>Url::createAdminUrl('localisation/currency/update') . '&currency_id=' . $result['currency_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );
						
    			$this->data['currencies'][] = array(
    				'currency_id'   => $result['currency_id'],
    				'title'         => $result['title'] . (($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_default') : NULL),
    				'code'          => $result['code'],
    				'value'         => $result['value'],
    				'date_modified' => date('d-m-Y', strtotime($result['date_modified'])),
    				'selected'      => isset($this->request->post['selected']) && in_array($result['currency_id'], $this->request->post['selected']),
    				'action'        => $action
    			);
    		}	
        }
        
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = Url::createAdminUrl('localisation/currency/grid') .'&sort=title' . $url;
		$this->data['sort_code'] = Url::createAdminUrl('localisation/currency/grid') .'&sort=code' . $url;
		$this->data['sort_value'] = Url::createAdminUrl('localisation/currency/grid') .'&sort=value' . $url;
		$this->data['sort_date_modified'] = Url::createAdminUrl('localisation/currency/grid') .'&sort=date_modified' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];		
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
		$pagination = new Pagination();
		$pagination->total = $currency_total;
		$pagination->page = $page;
		$pagination->ajax = true;
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->limit= $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url  = Url::createAdminUrl('localisation/currency/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/currency_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
    	
        $this->data['error_warning']= isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_title']  = isset($this->error['title']) ? $this->error['title'] : '';
        $this->data['error_code']   = isset($this->error['code']) ? $this->error['code'] : '';
        $this->data['error_value']  = isset($this->error['value']) ? $this->error['value'] : '';

		$url = '';
			
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
        
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('localisation/currency') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['currency_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/currency/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/currency/update') . '&currency_id=' . $this->request->get['currency_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('localisation/currency') . $url;

		if (isset($this->request->get['currency_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$currency_info = $this->modelCurrency->getById($this->request->get['currency_id']);
		}

        $this->setvar('title',$currency_info,'');
        $this->setvar('code',$currency_info,'');
        $this->setvar('symbol_left',$currency_info,'');
        $this->setvar('symbol_right',$currency_info,'');
        $this->setvar('decimal_place',$currency_info,'');
        $this->setvar('value',$currency_info,'');
        $this->setvar('status',$currency_info,1);

		$this->template = 'localisation/currency_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() { 
		if (!$this->user->hasPermission('modify', 'localisation/currency')) { 
			$this->error['warning'] = $this->language->get('error_permission');
		} 

		if ((strlen(utf8_decode($this->request->post['title'])) < 3) || (strlen(utf8_decode($this->request->post['title']))> 32)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (strlen(utf8_decode($this->request->post['code'])) != 3) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->error) { 
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('sale/order');
		
		foreach ($this->request->post['selected'] as $currency_id) {
			$currency_info = $this->modelCurrency->getById($currency_id);

			if ($currency_info) {
				if ($this->config->get('config_currency') == $currency_info['code']) {
					$this->error['warning'] = $this->language->get('error_default');
				}
				
				$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);
	
				if ($store_total) {
					$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
				}					
			}
			
			$order_total = $this->model_sale_order->getAllTotalByCurrencyId($currency_id);

			if ($order_total) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
			}					
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
}