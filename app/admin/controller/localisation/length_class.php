<?php
class ControllerLocalisationLengthClass extends Controller {
	private $error = array();  
 
	public function index() {
		$this->document->title = $this->language->get('heading_title');		
		$this->getList();
	}

	public function insert() {
		$this->document->title = $this->language->get('heading_title');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$length_class_id = $this->modelLengthclass->add($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/length_class/update',array('length_class_id'=>$length_class_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/length_class/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/length_class')); 
            }
		}
		$this->getForm();
	}

	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelLengthclass->update($this->request->get['length_class_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/length_class/update',array('length_class_id'=>$this->request->get['length_class_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/length_class/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/length_class')); 
            }
		}
		$this->getForm();
	}

     public function delete() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelLengthclass->delete($id);
            }
		} else {
            $this->modelLengthclass->delete($_GET['id']);
		}
     }
     
	private function getList() {
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

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('localisation/length_class'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['insert'] = Url::createAdminUrl('localisation/length_class/insert') . $url;
		$this->data['heading_title'] = $this->language->get('heading_title');
 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        // SCRIPTS        
        $scripts[] = array('id'=>'length_classList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/length_class/activate")."&id=' + e,
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
                	$.getJSON('". Url::createAdminUrl("localisation/length_class/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("localisation/length_class/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/length_class/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/length_class/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/length_class/grid") ."',
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
        
		$this->template = 'localisation/length_class_list.tpl';
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

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('localisation/length_class'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$data = array(
			'filter_title'  => $filter_title,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$this->data['length_classes'] = array();
		
		$length_class_total = $this->modelLengthclass->getAllTotal();
		if ($length_class_total) {
    		$results = $this->modelLengthclass->getAll($data);
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
                            'href'  =>Url::createAdminUrl('localisation/length_class/update') . '&length_class_id=' . $result['length_class_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );
                
    			$this->data['length_classes'][] = array(
    				'length_class_id' => $result['length_class_id'],
    				'title'           => $result['title'] . (($result['unit'] == $this->config->get('config_length_class')) ? $this->language->get('text_default') : NULL),
    				'unit'            => $result['unit'],
    				'value'           => $result['value'],
    				'selected'        => isset($this->request->post['selected']) && in_array($result['length_class_id'], $this->request->post['selected']),
    				'action'          => $action
    			);
    		}
        }

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$url = '';
		
		$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		
		$this->data['sort_title'] = Url::createAdminUrl('localisation/length_class/grid') . '&sort=title' . $url;
		$this->data['sort_unit']  = Url::createAdminUrl('localisation/length_class/grid') . '&sort=unit' . $url;
		$this->data['sort_value'] = Url::createAdminUrl('localisation/length_class/grid') . '&sort=value' . $url;

		$url = '';

		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
		$pagination = new Pagination();
		$pagination->total = $length_class_total;
		$pagination->page = $page;
		$pagination->ajax = 'true';
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->limit= $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url  = Url::createAdminUrl('localisation/length_class/grid') . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/length_class_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_title'] = isset($this->error['title']) ? $this->error['title'] : '';
        $this->data['error_unit'] = isset($this->error['unit']) ? $this->error['unit'] : '';
        
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
       		'href'      => Url::createAdminUrl('localisation/length_class') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
        			
		if (!isset($this->request->get['currency_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/length_class/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/length_class/update') . '&length_class_id=' . $this->request->get['length_class_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('localisation/language') . $url;

		if (isset($this->request->get['length_class_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$length_class_info = $this->modelLengthclass->getById($this->request->get['length_class_id']);
    	}
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getAll();
		
		if (isset($this->request->post['length_class_description'])) {
			$this->data['length_class_description'] = $this->request->post['length_class_description'];
		} elseif (isset($this->request->get['length_class_id'])) {
			$this->data['length_class_description'] = $this->modelLengthclass->getDescriptions($this->request->get['length_class_id']);
		} else {
			$this->data['length_class_description'] = array();
		}	
		
        $this->setvar('value',$length_class_info,'');
        
		$this->template = 'localisation/length_class_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/length_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['length_class_description'] as $language_id => $value) {
			if (empty($value['title'])) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (empty($value['unit'])) {
				$this->error['unit'][$language_id] = $this->language->get('error_unit');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/length_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('catalog/product');
		
		foreach ($this->request->post['selected'] as $length_class_id) {
			$length_class_info = $this->modelLengthclass->getById($length_class_id);
			
			if ($length_class_info && ($this->config->get('config_length_class') == $length_class_info['unit'])) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$product_total = $this->modelProduct->getAllTotalByLengthClassId($length_class_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
}
?>