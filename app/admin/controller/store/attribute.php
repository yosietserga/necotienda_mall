<?php /**
 * ControllerStoreAttribute
 * 
 * @package NecoTienda powered by opencart
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerStoreAttribute extends Controller {
	private $error = array(); 
     
  	/**
  	 * ControllerStoreAttribute::index()
     * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see getList
  	 * @return void
  	 */
  	public function index() {
		$this->document->title = $this->language->get('heading_title'); 
		$this->getList();
  	}
  
  	/**
  	 * ControllerStoreAttribute::insert()
  	 * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see Redirect
  	 * @see getForm
  	 * @return void
  	 */
  	public function insert() {
    	$this->document->title = $this->language->get('heading_title'); 
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            
			$id = $this->modelAttribute->add($this->request->post);
            $this->session->set('success',$this->language->get('text_success'));
            $url = '';
    		if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . $this->request->get['filter_name'];
   			}
    		if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
   			}
    		if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
   			}
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
   			}
            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
   			}
    		$this->redirect(Url::createAdminUrl('store/attribute') . $url);
    	}
    	$this->getForm();
  	}

  	/**
  	 * ControllerStoreAttribute::update()
  	 * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see Redirect
  	 * @see getForm
  	 * @return void
  	 */
  	public function update() {
    	$this->document->title = $this->language->get('heading_title');
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->modelAttribute->update($this->request->get['attribute_group_id'], $this->request->post);
			$this->session->set('success',$this->language->get('text_success'));
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(Url::createAdminUrl('store/attribute') . $url);
		}

    	$this->getForm();
  	}

  	/**
  	 * ControllerStoreAttribute::getList()
  	 * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see Redirect
  	 * @see Session
  	 * @see Response
  	 * @return void
  	 */
  	private function getList() {
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('store/attribute') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = Url::createAdminUrl('store/attribute/insert') . $url;
		$this->data['copy'] = Url::createAdminUrl('store/attribute/copy') . $url;	
		$this->data['delete'] = Url::createAdminUrl('store/attribute/delete') . $url;

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
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
        $scripts[] = array('id'=>'productList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("store/attribute/activate")."&id=' + e,
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
                $.getJSON('".Url::createAdminUrl("store/attribute/copy")."&id=' + e, function(data) {
                    $('#gridWrapper').load('". Url::createAdminUrl("store/attribute/grid") ."',function(response){
                        $('#gridPreloader').hide();
                        $('#gridWrapper').show();
                    });
                });
            }
            function eliminar(e) {
                if (confirm('¿Desea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("store/attribute/delete") ."',{
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
                $.post('". Url::createAdminUrl("store/attribute/copy") ."',$('#form').serialize(),function(){
                    $('#gridWrapper').load('". Url::createAdminUrl("store/attribute/grid") ."',function(){
                        $('#gridWrapper').show();
                        $('#gridPreloader').hide();
                    });
                });
                return false;
            } 
            function deleteAll() {
                if (confirm('¿Desea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("store/attribute/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("store/attribute/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("store/attribute/grid") ."',function(){
                $('#gridPreloader').hide();
            });
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("store/attribute/grid") ."',
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
        
		$this->template = 'store/attribute_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}

  	/**
  	 * ControllerStoreAttribute::grid()
  	 * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see Redirect
  	 * @see Session
  	 * @see Response
  	 * @return void
  	 */
  	public function grid() {	
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$filter_category = isset($this->request->get['filter_category']) ? $this->request->get['filter_category'] : null;
		$filter_status = isset($this->request->get['filter_status']) ? $this->request->get['filter_status'] : null;
		$filter_date_start = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : null;
		$filter_date_end = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'a.name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');

		$url = '';
			
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; } 
		if (isset($this->request->get['filter_category'])) { $url .= '&filter_category=' . $this->request->get['filter_category']; } 
		if (isset($this->request->get['filter_status'])) { $url .= '&filter_status=' . $this->request->get['filter_status']; } 
		if (isset($this->request->get['filter_date_start'])) { $url .= '&filter_date_start=' . $this->request->get['filter_date_start']; }
		if (isset($this->request->get['filter_date_end'])) { $url .= '&filter_date_end=' . $this->request->get['filter_date_end']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; }
		

		$this->data['attributes'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_category'	  => $filter_category,
			'filter_status'   => $filter_status,
			'filter_date_start'=> $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);
        
		$attribute_total = $this->modelAttribute->getTotalGroups($data);
			
		$results = $this->modelAttribute->getAllGroups($data);
				    	
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
                        'href'  =>Url::createAdminUrl('store/attribute/update') . '&attribute_group_id=' . $result['product_attribute_group_id'] . $url,
                        'img'   => 'edit.png'
                ),
                'duplicate' => array(
                        'action'  => 'duplicate',
                        'text'  => $this->language->get('text_copy'),
                        'href'  =>'',
                        'img'   => 'copy.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
			
      		$this->data['attributes'][] = array(
				'attribute_group_id'=> $result['product_attribute_group_id'],
				'name'              => $result['name'],
				'date_added'      => $result['date_added'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_attribute_group_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}
        
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
    	$this->data['column_categories'] = $this->language->get('column_categories');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

$url = '';
								
		if (isset($this->request->get['page'])) {$url .= '&page=' . $this->request->get['page'];}
		if (isset($this->request->get['sort'])) {$url .= '&sort=' . $this->request->get['sort'];}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['filter_name'])) {$url .= '&filter_name=' . $this->request->get['filter_name'];}
		if (isset($this->request->get['filter_category'])) {$url .= '&filter_category=' . $this->request->get['filter_category'];}
		if (isset($this->request->get['filter_quantity'])) {$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];}
		if (isset($this->request->get['filter_status'])) {$url .= '&filter_status=' . $this->request->get['filter_status'];}
		
		$this->data['sort_name'] = Url::createAdminUrl('store/attribute/grid') . '&sort=a.name' . $url;
		$this->data['sort_category'] = Url::createAdminUrl('store/attribute/grid') . '&sort=cd.name' . $url;
		$this->data['sort_status'] = Url::createAdminUrl('store/attribute/grid') . '&sort=p.status' . $url;

		$pagination = new Pagination();
		$pagination->ajax = true;
		$pagination->ajaxTarget = "gridWrapper";
		$pagination->total = $attribute_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('store/attribute/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_category'] = $filter_category;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'store/attribute_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}

  	/**
  	 * ControllerStoreAttribute::getForm()
  	 * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see Redirect
  	 * @see Session
  	 * @see Response
  	 * @return void
  	 */
  	private function getForm() {
 		$this->data['error_warning'] = ($this->error['warning']) ? $this->error['warning'] : '';
 		$this->data['error_name'] = ($this->error['name']) ? $this->error['name'] : '';

		$url = '';
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; }
		if (isset($this->request->get['filter_category'])) { $url .= '&filter_category=' . $this->request->get['filter_category']; }
		if (isset($this->request->get['filter_status'])) { $url .= '&filter_status=' . $this->request->get['filter_status']; }					
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
       		'href'      => Url::createAdminUrl('store/attribute') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		if (!isset($this->request->get['attribute_group_id'])) {
			$this->data['action'] = Url::createAdminUrl('store/attribute/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('store/attribute/update') . '&attribute_group_id=' . $this->request->get['attribute_group_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('store/attribute') . $url;

		if (isset($this->request->get['attribute_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$attribute_info = $this->modelAttribute->getById($this->request->get['attribute_group_id']);
    	}

        $this->data['attribute'] = $attribute_info;
        $this->data['attributes_to_categories'] = array();
        foreach ($attribute_info['categories'] as $category) {
            $this->data['attributes_to_categories'][] = $category['category_id'];
        }
		
		$this->data['categories'] = $this->modelCategory->getAll();
        	
        $scripts[] = array('id'=>'form','method'=>'ready','script'=>
            "$('#accordion').accordion({
                collapsible: true
            });
            
            $('#q').on('change',function(e){
                var that = this;
                var valor = $(that).val().toLowerCase();
                if (valor.length <= 0) {
                    $('#categoriesWrapper li').show();
                } else {
                    $('#categoriesWrapper li b').each(function(){
                        if ($(this).text().toLowerCase().indexOf( valor ) != -1) {
                            $(this).closest('li').show();
                        } else {
                            $(this).closest('li').hide();
                        }
                    });
                }
            });");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->template = 'store/attribute_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	} 
	
  	/**
  	 * ControllerStoreAttribute::validateForm()
  	 * 
     * @see User
     * @see Language
     * @see Request
  	 * @return bool
  	 */
  	private function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'store/attribute')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
        //TODO: colocar validaciones propias
        if (empty($this->request->post['name'])) {
      		$this->error['name'] = $this->language->get('error_name');
    	}
		
    	if (!$this->error) {
			return true;
    	} else {
			return false;
    	}
  	}
	
  	/**
  	 * ControllerStoreAttribute::validateDelete()
  	 * 
     * @see User
     * @see Language
     * @see Request
  	 * @return bool
  	 */
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'store/attribute')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
        //TODO: colocar validaciones propias
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	/**
  	 * ControllerStoreAttribute::validateCopy()
  	 * 
     * @see User
     * @see Language
     * @see Request
  	 * @return bool
  	 */
  	private function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'store/attribute')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
        
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
	
    /**
     * ControllerStoreAttribute::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('store/attribute');
        $status = $this->modelAttribute->getById($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelAttribute->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelAttribute->desactivate($_GET['id']);
                echo -1;
            }
            
        } else {
            echo 0;
        }
     }
    
    /**
     * ControllerStoreAttribute::delete()
     * elimina un objeto
     * @return boolean
     * */
     public function delete() {
        $this->load->auto('store/attribute');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelAttribute->delete($id);
            }
		} else {
            $this->modelAttribute->delete($_GET['id']);
		}
     }
    
    /**
     * ControllerStoreCategory::sortable()
     * ordenar el listado actualizando la posición de cada objeto
     * @return boolean
     * */
     public function sortable() {
        if (!isset($_POST['tr'])) return false;
        $this->load->auto('store/attribute');
        $result = $this->modelAttribute->sortProduct($_POST['tr']);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
     
  	/**
  	 * ControllerMarketingNewsletter::copy()
     * duplicar un objeto
  	 * @return boolean
  	 */
  	public function copy() {
        $this->load->auto('store/attribute');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $id = $this->modelAttribute->copy($id);
            }
		} else {
            $id = $this->modelAttribute->copy($_GET['id']);
		}
        echo (int)$id;
  	}
}