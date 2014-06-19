<?php
class ControllerLocalisationGeoZone extends Controller { 
	private $error = array();
 
	public function index() {
		$this->document->title = $this->language->get('heading_title');
		$this->getList();
	}

	public function insert() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$geo_zone_id = $this->modelGeozone->add($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/geo_zone/update',array('geo_zone_id'=>$geo_zone_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/geo_zone/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/geo_zone')); 
            }
		}

		$this->getForm();
	}

	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelGeozone->update($this->request->get['geo_zone_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('localisation/geo_zone/update',array('geo_zone_id'=>$this->request->get['geo_zone_id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('localisation/geo_zone/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('localisation/geo_zone')); 
            }
		}

		$this->getForm();
	}

     public function delete() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelGeozone->delete($id);
            }
		} else {
            $this->modelGeozone->delete($_GET['id']);
		}
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
       		'href'      => Url::createAdminUrl('localisation/geo_zone'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
	
		$this->data['insert'] = Url::createAdminUrl('localisation/geo_zone/insert') . $url;
		$this->data['heading_title'] = $this->language->get('heading_title');
 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        // SCRIPTS        
        $scripts[] = array('id'=>'geo_zoneList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("localisation/geo_zone/activate")."&id=' + e,
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
                	$.getJSON('". Url::createAdminUrl("localisation/geo_zone/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("localisation/geo_zone/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("localisation/geo_zone/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("localisation/geo_zone/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("localisation/geo_zone/grid") ."',
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
        
		$this->template = 'localisation/geo_zone_list.tpl';
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

		$data = array(
			'filter_name'  => $filter_name,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$this->data['geo_zones'] = array();
        
		$geo_zone_total = $this->modelGeozone->getAllTotal();
		if ($geo_zone_total) {
    		$results = $this->modelGeozone->getAll($data);
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
                            'href'  =>Url::createAdminUrl('localisation/geo_zone/update') . '&geo_zone_id=' . $result['geo_zone_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );
                
    			$this->data['geo_zones'][] = array(
    				'geo_zone_id' => $result['geo_zone_id'],
    				'name'        => $result['name'],
    				'description' => $result['description'],
    				'selected'    => isset($this->request->post['selected']) && in_array($result['geo_zone_id'], $this->request->post['selected']),
    				'action'      => $action
    			);
    		}
        }
		
		$url = '';
		
		$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		
		$this->data['sort_name'] = Url::createAdminUrl('localisation/geo_zone/grid') . '&sort=name' . $url;

		$url = '';

		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
				
		$pagination = new Pagination();
		$pagination->total= $geo_zone_total;
		$pagination->page = $page;
		$pagination->ajax = 'true';
		$pagination->ajaxTarget = 'gridWrapper';
		$pagination->limit= $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url  = Url::createAdminUrl('localisation/geo_zone/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/geo_zone_grid.tpl';
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
        $this->data['error_description'] = isset($this->error['description']) ? $this->error['description'] : '';
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
       		'href'      => Url::createAdminUrl('localisation/geo_zone') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		if (!isset($this->request->get['currency_id'])) {
			$this->data['action'] = Url::createAdminUrl('localisation/geo_zone/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('localisation/geo_zone/update') . '&geo_zone_id=' . $this->request->get['geo_zone_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('localisation/geo_zone') . $url;

		if (isset($this->request->get['geo_zone_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$geo_zone_info = $this->modelGeozone->getGeoZone($this->request->get['geo_zone_id']);
		}

        $this->setvar('name',$geo_zone_info,'');
        $this->setvar('description',$geo_zone_info,'');
        
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->modelCountry->getAll();
		
		if (isset($this->request->post['zone_to_geo_zone'])) {
			$this->data['zone_to_geo_zones'] = $this->request->post['zone_to_geo_zone'];
		} elseif (isset($this->request->get['geo_zone_id'])) {
			$this->data['zone_to_geo_zones'] = $this->modelGeozone->getZoneToGeoZones($this->request->get['geo_zone_id']);
		} else {
			$this->data['zone_to_geo_zones'] = array();
		}
		
		$this->template = 'localisation/geo_zone_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name']))> 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((strlen(utf8_decode($this->request->post['description'])) < 3) || (strlen(utf8_decode($this->request->post['description']))> 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/tax_class');

		foreach ($this->request->post['selected'] as $geo_zone_id) {
			$tax_rate_total = $this->model_localisation_tax_class->getAllTotalByGeoZoneId($geo_zone_id);

			if ($tax_rate_total) {
				$this->error['warning'] = sprintf($this->language->get('error_tax_rate'), $tax_rate_total);
			}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function zone() {
		$output = '<option value="0">' . $this->language->get('text_all_zones') . '</option>';
		
		$this->load->model('localisation/zone');
		
		$results = $this->modelZone->getAllByCountryId($this->request->get['country_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($this->request->get['zone_id'] == $result['zone_id']) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	} 		
}
?>