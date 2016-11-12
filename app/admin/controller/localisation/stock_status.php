<?php 
class ControllerLocalisationStockStatus extends Controller {
	private $error = array(); 
   
  	public function index() {
		$this->document->title = $this->language->get('heading_title');
		$this->getList();
  	}
              
  	public function insert() {
		$this->document->title = $this->language->get('heading_title');	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$stock_status_id = $this->modeStockstatus->add($this->request->post);
		  	
			$this->session->set('success',$this->language->get('text_success'));

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/stock_status/update',array('stock_status_id'=>$stock_status_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/stock_status/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/stock_status')); 
            }
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->modeStockstatus->update($this->request->get['stock_status_id'], $this->request->post);
			
			$this->session->set('success',$this->language->get('text_success'));

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/stock_status/update',array('stock_status_id'=>$this->request->get['stock_status_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/stock_status/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/stock_status')); 
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
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modeStockstatus->delete($id);
            }
		} else {
            $this->modeStockstatus->delete($_GET['id']);
		}
     }
    
  	private function getList() {
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$post = isset($this->request->get['post']) ? $this->request->get['post'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; }
		if (isset($this->request->get['post'])) { $url .= '&post=' . $this->request->get['post']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('localisation/stock_status'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = Url::createAdminUrl('localisation/stock_status/insert') . $url;

		$this->data['heading_title'] = $this->language->get('heading_title');
 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        // SCRIPTS        
        $scripts[] = array('id'=>'stockstatusList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/stock_status/activate")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_\" + e).attr('src','image/good.png');
                        } else {
                            $(\"#img_\" + e).attr('src','image/minus.png');
                        }
                   }
            	});
             }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("localisation/stock_status/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("localisation/stock_status/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/stock_status/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/stock_status/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/stock_status/grid") ."',
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
        
		$this->template = 'localisation/stock_status_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
  
  	public function grid() {
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 

		$this->data['stock_statuses'] = array();

		$data = array(
			'filter_name'  => $filter_name,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$stock_status_total = $this->modelStockstatus->getAllTotal();
        if ($stock_status_total) {
    		$results = $this->modelStockstatus->getAll($data);
     
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
                            'href'  =>Url::createAdminUrl('localisation/stock_status/update') . '&stock_status_id=' . $result['stock_status_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );			
    			$this->data['stock_statuses'][] = array(
    				'stock_status_id' => $result['stock_status_id'],
    				'name'            => $result['name'] . (($result['stock_status_id'] == $this->config->get('config_stock_status_id')) ? $this->language->get('text_default') : NULL),
    				'selected'        => isset($this->request->post['selected']) && in_array($result['stock_status_id'], $this->request->post['selected']),
    				'action'          => $action
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
		
		$this->data['sort_name'] = Url::createAdminUrl('localisation/stock_status/grid') . '&sort=name' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $stock_status_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->ajax = 'true';
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('localisation/stock_status/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/stock_status_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
  
  	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';

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
       		'href'      => Url::createAdminUrl('localisation/stock_status') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		if (!isset($this->request->get['stock_status_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/stock_status/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/stock_status/update') . '&stock_status_id=' . $this->request->get['stock_status_id'] . $url;
		}
			
		$this->data['cancel'] = Url::createAdminUrl('localisation/stock_status') . $url;
		
		$this->data['languages'] = $this->modelLanguage->getAll();
		
		if (isset($this->request->post['stock_status'])) {
			$this->data['stock_status'] = $this->request->post['stock_status'];
		} elseif (isset($this->request->get['stock_status_id'])) {
			$this->data['stock_status'] = $this->modelStockstatus->getDescriptions($this->request->get['stock_status_id']);
		} else {
			$this->data['stock_status'] = array();
		}
		
		$this->template = 'localisation/stock_status_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));	
  	}
  	
	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/stock_status')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['stock_status'] as $language_id => $value) {
      		if ((strlen(utf8_decode($value['name'])) < 3) || (strlen(utf8_decode($value['name']))> 32)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/stock_status')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->auto('store/product');
		
		foreach ($this->request->post['selected'] as $stock_status_id) {
			if ($this->config->get('config_stock_status_id') == $stock_status_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
						
			$product_total = $this->modelProduct->getAllTotalByStockStatusId($stock_status_id);
		
			if ($product_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
			}  
	  	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}	  
}
