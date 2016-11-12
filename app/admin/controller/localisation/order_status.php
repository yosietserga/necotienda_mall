<?php 
class ControllerLocalisationOrderStatus extends Controller { 
	private $error = array();
   
  	public function index() {
    	$this->document->title = $this->language->get('heading_title');
		$this->getList();
  	}
              
  	public function insert() {
    	$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$id = $this->modelOrderstatus->add($this->request->post);
		  	
			$this->session->set('success',$this->language->get('text_success'));

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/order_status/update',array('order_status_id'=>$id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/order_status/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/order_status')); 
            }
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->modelOrderstatus->update($this->request->get['order_status_id'], $this->request->post);
			
			$this->session->set('success',$this->language->get('text_success'));

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/order_status/update',array('order_status_id'=>$this->request->get['order_status_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/order_status/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/order_status')); 
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
        $this->load->auto('localisation/orderstatus');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelOrderstatus->delete($id);
            }
		} else {
            $this->modelOrderstatus->delete($_GET['id']);
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
       		'href'      => Url::createAdminUrl('localisation/order_status'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['insert'] = Url::createAdminUrl('localisation/order_status/insert') . $url;

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}

        // SCRIPTS        
        $scripts[] = array('id'=>'order_statusList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/order_status/activate")."&id=' + e,
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
                	$.getJSON('". Url::createAdminUrl("localisation/order_status/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("localisation/order_status/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/order_status/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/order_status/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/order_status/grid") ."',
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
        
		
		$this->template = 'localisation/order_status_list.tpl';
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

		$this->data['languages'] = array();

		$data = array(
			'filter_name'  => $filter_name,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$order_status_total = $this->modelOrderstatus->getAllTotal();
        if ($order_status_total) {
    		$results = $this->modelOrderstatus->getAll($data);
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
                                'href'  =>Url::createAdminUrl('localisation/order_status/update') . '&order_status_id=' . $result['order_status_id'] . $url,
                                'img'   => 'edit.png'
                        ),
                        'delete'    => array(
                                'action'  => 'delete',
                                'text'  => $this->language->get('text_delete'),
                                'href'  =>'',
                                'img'   => 'delete.png'
                        )
                );
                
    			$this->data['order_statuses'][] = array(
    				'order_status_id' => $result['order_status_id'],
    				'name'            => $result['name'] . (($result['order_status_id'] == $this->config->get('config_order_status_id')) ? $this->language->get('text_default') : ''),
    				'selected'        => isset($this->request->post['selected']) && in_array($result['order_status_id'], $this->request->post['selected']),
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
		
		$this->data['sort_name'] = Url::createAdminUrl('localisation/order_status/grid') . '&sort=name' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_status_total;
		$pagination->page = $page;
		$pagination->ajax = 'true';
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('localisation/order_status/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/order_status_grid.tpl';
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
        $this->data['error_code'] = isset($this->error['code']) ? $this->error['code'] : '';
        $this->data['error_locale'] = isset($this->error['locale']) ? $this->error['locale'] : '';
        $this->data['error_image'] = isset($this->error['image']) ? $this->error['image'] : '';
        $this->data['error_directory'] = isset($this->error['directory']) ? $this->error['directory'] : '';
        $this->data['error_filename'] = isset($this->error['filename']) ? $this->error['filename'] : '';

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
       		'href'      => Url::createAdminUrl('localisation/order_status') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
						
		if (!isset($this->request->get['order_status_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/order_status/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/order_status/update') . '&order_status_id=' . $this->request->get['order_status_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('localisation/order_status') . $url;

		if (isset($this->request->post['order_status'])) {
			$this->data['order_status'] = $this->request->post['order_status'];
		} elseif (isset($this->request->get['order_status_id'])) {
			$this->data['order_status'] = $this->modelOrderstatus->getDescriptions($this->request->get['order_status_id']);
		} else {
			$this->data['order_status'] = array();
		}
		
		$this->data['languages'] = $this->modelLanguage->getAll();
		
		$this->template = 'localisation/order_status_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['order_status'] as $language_id => $value) {
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
		if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->auto('sale/order');
		
		foreach ($this->request->post['selected'] as $order_status_id) {
    		if ($this->config->get('config_order_status_id') == $order_status_id) {
	  			$this->error['warning'] = $this->language->get('error_default');	
			}  
			
    		if ($this->config->get('config_download_status') == $order_status_id) {
	  			$this->error['warning'] = $this->language->get('error_download');	
			}  
			
			$order_total = $this->modelOrder->getHistoryTotalByOrderStatusId($order_status_id);
		
			if ($order_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);	
			}  
	  	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}	  
}