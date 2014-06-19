<?php
class ControllerLocalisationTaxClass extends Controller {
	private $error = array();
 
	public function index() {
		$this->document->title = $this->language->get('heading_title');
		$this->getList(); 
	}

	public function insert() {
		$this->document->title = $this->language->get('heading_title');		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$tax_class_id = $this->modelTaxclass->add($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/tax_class/update',array('tax_class_id'=>$tax_class_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/tax_class/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/tax_class')); 
            }
		}

		$this->getForm();
	}

	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelTaxclass->update($this->request->get['tax_class_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/tax_class/update',array('tax_class_id'=>$this->request->get['tax_class_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/tax_class/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/tax_class')); 
            }
		}

		$this->getForm();
	}

    /**
     * ControllerLocalisationTaxClass::delete()
     * elimina un objeto
     * @return boolean
     * */
     public function delete() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelTaxclass->delete($id);
            }
		} else {
            $this->modelTaxclass->delete($_GET['id']);
		}
     }

	private function getList() {
		$filter_title = isset($this->request->get['filter_title']) ? $this->request->get['filter_title'] : null;
		$post = isset($this->request->get['post']) ? $this->request->get['post'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_title'])) { $url .= '&filter_title=' . $this->request->get['filter_title']; }
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
       		'href'      => Url::createAdminUrl('localisation/tax_class'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['insert'] = Url::createAdminUrl('localisation/tax_class/insert') . $url;
		$this->data['heading_title'] = $this->language->get('heading_title');
 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        // SCRIPTS        
        $scripts[] = array('id'=>'tax_classList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/tax_class/activate")."&id=' + e,
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
                	$.getJSON('". Url::createAdminUrl("localisation/tax_class/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("localisation/tax_class/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/tax_class/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/tax_class/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/tax_class/grid") ."',
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
        
		$this->template = 'localisation/tax_class_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	public function grid() {
		$filter_title = isset($this->request->get['filter_title']) ? $this->request->get['filter_title'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_title'])) { $url .= '&filter_title=' . $this->request->get['filter_title']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 

		$this->data['tax_classes'] = array();
		
		$data = array(
			'filter_title'  => $filter_title,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$tax_class_total = $this->modelTaxclass->getAllTotal();
        if ($tax_class_total) { 
    		$results = $this->modelTaxclass->getAll($data);
    
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
                            'href'  =>Url::createAdminUrl('localisation/tax_class/update') . '&tax_class_id=' . $result['tax_class_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );
    					
    			$this->data['tax_classes'][] = array(
    				'tax_class_id' => $result['tax_class_id'],
    				'title'        => $result['title'],
    				'selected'     => isset($this->request->post['selected']) && in_array($result['tax_class_id'], $this->request->post['selected']),
    				'action'       => $action				
    			);
    		}
        }

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
		
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		
		$this->data['sort_title'] = Url::createAdminUrl('localisation/tax_class/grid') . '&sort=title' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];						
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
		$pagination = new Pagination();
		$pagination->total = $tax_class_total;
		$pagination->page = $page;
		$pagination->ajax = 'true';
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->limit= $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url  = Url::createAdminUrl('localisation/tax_class/grid') . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/tax_class_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
				
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_title'] = isset($this->error['title']) ? $this->error['title'] : '';
        $this->data['error_description'] = isset($this->error['description']) ? $this->error['description'] : '';

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
       		'href'      => Url::createAdminUrl('localisation/tax_class') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
						
		if (!isset($this->request->get['currency_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/tax_class/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/tax_class/update') . '&tax_class_id=' . $this->request->get['tax_class_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('localisation/tax_class') . $url;

		if (isset($this->request->get['tax_class_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tax_class_info = $this->modelTaxclass->getById($this->request->get['tax_class_id']);
		}

        $this->setvar('title',$tax_class_info,'');
        $this->setvar('description',$tax_class_info,'');
        
		$this->load->model('localisation/geozone');		
		$this->data['geo_zones'] = $this->modelGeozone->getAll();
		
		if (isset($this->request->post['tax_rate'])) {
			$this->data['tax_rates'] = $this->request->post['tax_rate'];
		} elseif (isset($this->request->get['tax_class_id'])) {
			$this->data['tax_rates'] = $this->modelTaxclass->getTaxRates($this->request->get['tax_class_id']);
		} else {
			$this->data['tax_rates'] = array();
		}
		
		$this->template = 'localisation/tax_class_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['title'])) < 3) || (strlen(utf8_decode($this->request->post['title']))> 32)) {
			$this->error['title'] = $this->language->get('error_title');
		}
		
		if (isset($this->request->post['tax_rate'])) {
			foreach ($this->request->post['tax_rate'] as $value) {
				if (!$value['priority']) {
					$this->error['warning'] = $this->language->get('error_priority');
				}

				if (!$value['rate']) { 
					$this->error['warning'] = $this->language->get('error_rate');
				}

				if ((strlen(utf8_decode($value['description'])) < 1) || (strlen(utf8_decode($value['description']))> 255)) {
					$this->error['warning'] = $this->language->get('error_description');
				}
			}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $tax_class_id) {
			$product_total = $this->modelProduct->getTotalproductsByTaxClassId($tax_class_id);

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
?>