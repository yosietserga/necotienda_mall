<?php 
class ControllerLocalisationLanguage extends Controller {
	private $error = array();
  
	public function index() {
		$this->document->title = $this->language->get('heading_title');
		$this->getList();
	}

	public function insert() {
		$this->document->title = $this->language->get('heading_title');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$language_id = $this->modelLanguage->add($this->request->post);
            
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/language/update',array('language_id'=>$language_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/language/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/language')); 
            }
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('localisation/language');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('localisation/language');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelLanguage->update($this->request->get['language_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
            
			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/language/update',array('language_id'=>$this->request->get['language_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/language/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/language')); 
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
        $this->load->auto('localisation/language');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelLanguage->delete($id);
            }
		} else {
            $this->modelLanguage->delete($_GET['id']);
		}
     }
    
  	/**
  	 * ControllerMarketingNewsletter::copy()
     * duplicar un objeto
  	 * @return boolean
  	 */
  	public function copy() {
        $this->load->auto('localisation/language');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelLanguage->copy($id);
            }
		} else {
            $this->modelLanguage->copy($_GET['id']);
		}
        echo 1;
  	}

	private function getList() {
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

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('localisation/language'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['insert'] = Url::createAdminUrl('localisation/language/insert') . $url;
		$this->data['heading_title'] = $this->language->get('heading_title');
 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        // SCRIPTS        
        $scripts[] = array('id'=>'languageList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/language/activate")."&id=' + e,
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
                $.getJSON('".Url::createAdminUrl("localisation/language/copy")."&id=' + e, function(data) {
                    $('#gridWrapper').load('". Url::createAdminUrl("localisation/language/grid") ."',function(response){
                        $('#gridPreloader').hide();
                        $('#gridWrapper').show();
                    });
                });
            }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("localisation/language/delete") ."',{
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
                $.post('". Url::createAdminUrl("localisation/language/copy") ."',$('#form').serialize(),function(){
                    $('#gridWrapper').load('". Url::createAdminUrl("localisation/language/grid") ."',function(){
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
                    $.post('". Url::createAdminUrl("localisation/language/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/language/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/language/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/language/grid") ."',
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
        
		$this->template = 'localisation/language_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
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
		
		$language_total = $this->modelLanguage->getAllTotal();
		
        if ($language_total) {
    		$results = $this->modelLanguage->getAll($data);
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
                            'href'  =>Url::createAdminUrl('localisation/language/update') . '&language_id=' . $result['language_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );
						
    			$this->data['languages'][] = array(
    				'language_id' => $result['language_id'],
    				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : ''),
    				'code'        => $result['code'],
    				'selected'    => isset($this->request->post['selected']) && in_array($result['language_id'], $this->request->post['selected']),
    				'action'      => $action	
    			);		
    		}
        }
        
		$url = '';
		
		$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		
		$this->data['sort_name'] = Url::createAdminUrl('localisation/language/grid') . '&sort=name' . $url;
		$this->data['sort_code'] = Url::createAdminUrl('localisation/language/grid') . '&sort=code' . $url;

		$url = '';

		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
				
		$pagination = new Pagination();
		$pagination->total= $language_total;
		$pagination->page = $page;
		$pagination->ajax = 'true';
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->limit= $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url  = Url::createAdminUrl('localisation/language/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/language_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
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
       		'href'      => Url::createAdminUrl('localisation/language') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
						
		if (!isset($this->request->get['currency_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/language/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/language/update') . '&language_id=' . $this->request->get['language_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('localisation/language') . $url;

		if (isset($this->request->get['language_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$language_info = $this->modelLanguage->getById($this->request->get['language_id']);
		}
        
        $this->setvar('name',$language_info,'');
        $this->setvar('code',$language_info,'');
        $this->setvar('locale',$language_info,'');
        $this->setvar('image',$language_info,'');
        $this->setvar('directory',$language_info,'');
        $this->setvar('filename',$language_info,'');
        $this->setvar('sort_order',$language_info,'');
        $this->setvar('status',$language_info,1);

		$this->template = 'localisation/language_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name']))> 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (strlen(utf8_decode($this->request->post['code'])) < 2) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->request->post['locale']) {
			$this->error['locale'] = $this->language->get('error_locale');
		}
		
		if (!$this->request->post['directory']) { 
			$this->error['directory'] = $this->language->get('error_directory'); 
		}

		if (!$this->request->post['filename']) {
			$this->error['filename'] = $this->language->get('error_filename');
		}
		
		if ((strlen(utf8_decode($this->request->post['image'])) < 3) || (strlen(utf8_decode($this->request->post['image']))> 32)) {
			$this->error['image'] = $this->language->get('error_image');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} 
		
		$this->load->model('setting/store');
		$this->load->model('sale/order');
		
		foreach ($this->request->post['selected'] as $language_id) {
			$language_info = $this->modelLanguage->getById($language_id);

			if ($language_info) {
				if ($this->config->get('config_language') == $language_info['code']) {
					$this->error['warning'] = $this->language->get('error_default');
				}
				
				if ($this->config->get('config_admin_language') == $language_info['code']) {
					$this->error['warning'] = $this->language->get('error_admin');
				}	
			
				$store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);
	
				if ($store_total) {
					$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
				}
			}
				
			$order_total = $this->model_sale_order->getAllTotalByLanguageId($language_id);

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