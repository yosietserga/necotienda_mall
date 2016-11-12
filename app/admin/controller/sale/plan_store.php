<?php /**
 * ControllerSalePlanStore
 * 
 * @package NecoTienda powered by opencart
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerSalePlanStore extends Controller { 
	private $error = array();
  
  	/**
  	 * ControllerSalePlanStore::index()
     * 
  	 * @see Load
  	 * @see Document
  	 * @see Language
  	 * @see getList
  	 * @return void
  	 */
  	public function index() {
		$this->document->title = $this->language->get('heading_title');
    	$this->getList();
  	}
  
  	/**
  	 * ControllerSalePlanStore::insert()
  	 * 
  	 * @see Load
  	 * @see Document
  	 * @see Request
  	 * @see Session
  	 * @see Redirect
  	 * @see Language
  	 * @see getForm
  	 * @return void
  	 */
  	public function insert() {
    	$this->document->title = $this->language->get('heading_title');	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$plan_store_id = $this->modelPlan_store->add($this->request->post);

			$this->session->set('success',$this->language->get('text_success'));
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('sale/plan_store/update',array('plan_store_id'=>$plan_store_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('sale/plan_store/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('sale/plan_store')); 
            }
		}
    
    	$this->getForm();
  	} 
   
  	/**
  	 * ControllerSalePlanStore::update()
  	 * 
  	 * @see Load
  	 * @see Document
  	 * @see Request
  	 * @see Session
  	 * @see Redirect
  	 * @see Language
  	 * @see getForm
  	 * @return void
  	 */
  	public function update() {
    	$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$plan_store_id = $this->modelPlan_store->edit($this->request->get['plan_store_id'], $this->request->post);

			$this->session->set('success',$this->language->get('text_success'));
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('sale/plan_store/update',array('plan_store_id'=>$plan_store_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('sale/plan_store/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('sale/plan_store')); 
            }
		}
    
    	$this->getForm();
  	}   

    /**
     * ControllerStoreCategory::delete()
     * elimina un objeto
     * @return boolean
     * */
     public function delete() {
        $this->load->auto('sale/plan_store');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelPlan_store->delete($id);
            }
		} else {
            $this->modelPlan_store->delete($_GET['id']);
		}
     }
    
  	/**
  	 * ControllerSalePlanStore::getList()
  	 * 
  	 * @see Load
  	 * @see Document
  	 * @see Request
  	 * @see Session
  	 * @see Response
  	 * @see Pagination
  	 * @see Language
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
       		'href'      => Url::createAdminUrl('sale/plan_store') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = Url::createAdminUrl('sale/plan_store/insert') . $url;
		$this->data['delete'] = Url::createAdminUrl('sale/plan_store/delete') . $url;	

		$this->data['heading_title']  = $this->language->get('heading_title');
		$this->data['button_insert']  = $this->language->get('button_insert');
		$this->data['button_delete']  = $this->language->get('button_delete');
 
 		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}

        // SCRIPTS
        $scripts[] = array('id'=>'plan_storeList','method'=>'function','script'=>
            "function activate(e) {
                $.getJSON('". Url::createAdminUrl("sale/plan_store/activate") ."',{
                    id:e
                },function(data){
                    if (data > 0) {
                        $('#img_' + e).attr('src','image/good.png');
                    } else {
                        $('#img_' + e).attr('src','image/minus.png');
                    }
                });
            }
            function editAll() {
                return false;
            } 
            function addToList() {
                return false;
            } 
            function deleteAll() {
                if (confirm('¿Desea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/plan_store/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/plan_store/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }
            function eliminar(e) {
                if (confirm('¿Desea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("sale/plan_store/delete") ."',{
                        id:e
                    });
                }
                return false;
             }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("sale/plan_store/grid") ."',function(e){
                $('#gridPreloader').hide();
                $('#list tbody').sortable({
                    opacity: 0.6, 
                    cursor: 'move',
                    handle: '.move',
                    update: function() {
                        $.ajax({
                            'type':'post',
                            'dateType':'json',
                            'url':'". Url::createAdminUrl("sale/plan_store/sortable") ."',
                            'data': $(this).sortable('serialize'),
                            'success': function(data) {
                                if (data > 0) {
                                    var msj = '<div class=\"messagesuccess\">Se han ordenado los objetos correctamente</div>';
                                } else {
                                    var msj = '<div class=\"messagewarning\">Hubo un error al intentar ordenar los objetos, por favor intente m&aacute;s tarde</div>';
                                }
                                $('#msg').fadeIn().append(msj).delay(3600).fadeOut();
                            }
                        });
                    }
                }).disableSelection();
                $('.move').css('cursor','move');
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("sale/plan_store/grid") ."',
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
        
		$this->template = 'sale/plan_store_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
    
    public function grid() {
        
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$filter_date_start = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : null;
		$filter_date_end = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
        
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; } 
		if (isset($this->request->get['filter_product'])) { $url .= '&filter_product=' . $this->request->get['filter_product']; } 
		if (isset($this->request->get['filter_date_start'])) { $url .= '&filter_date_start=' . $this->request->get['filter_date_start']; }
		if (isset($this->request->get['filter_date_end'])) { $url .= '&filter_date_end=' . $this->request->get['filter_date_end']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; }
		
        
		$this->data['plan_stores'] = array();

        
		$data = array(
			'filter_name' => $filter_name, 
			'filter_date_start' => $filter_date_start, 
			'filter_date_end' => $filter_date_end, 
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $limit
		);
		
        
		$plan_store_total = $this->modelPlan_store->getTotalPlans($data);
	
        
		$results = $this->modelPlan_store->getPlans($data);
 
        
    	foreach ($results as $result) {
				$action = array(
                'edit'      => array(
                        'action'  => 'edit',
                        'text'  => $this->language->get('text_edit'),
                        'href'  =>Url::createAdminUrl('sale/plan_store/update') . '&plan_store_id=' . $result['plan_store_id'] . $url,
                        'img'   => 'edit.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
					
        
			$this->data['plan_stores'][] = array(
				'plan_store_id' => $result['plan_store_id'],
				'name'            => $result['name'],
				'price'            => $result['price'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['plan_store_id'], $this->request->post['selected']),
				'action'          => $action
			);
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
		
        
		$this->data['sort_name'] = Url::createAdminUrl('sale/plan_store/grid') . '&sort=name' . $url;
		$this->data['sort_sort_order'] = Url::createAdminUrl('sale/plan_store/grid') . '&sort=sort_order' . $url;
		
		$url = '';

        
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

        
		$pagination = new Pagination();
		$pagination->total = $plan_store_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('sale/plan_store/grid') . $url . '&page={page}';
			
        
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->data['text_no_results']= $this->language->get('text_no_results');
		$this->data['column_image']    = $this->language->get('column_image');
		$this->data['column_name']    = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action']  = $this->language->get('column_action');	
        
		$this->template = 'sale/plan_store_grid.tpl';
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }
    
  	/**
  	 * ControllerSalePlanStore::getForm()
  	 * 
  	 * @see Load
  	 * @see Document
  	 * @see Request
  	 * @see Session
  	 * @see Response
  	 * @see Pagination
  	 * @see Language
  	 * @return void
  	 */
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
    	$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_name'] = $this->language->get('help_name');
		$this->data['help_keyword'] = $this->language->get('help_keyword');
    	$this->data['help_image'] = $this->language->get('help_image');
		$this->data['help_sort_order'] = $this->language->get('help_sort_order');
  
		$this->data['button_save_and_new']= $this->language->get('button_save_and_new');
		$this->data['button_save_and_exit']= $this->language->get('button_save_and_exit');
		$this->data['button_save_and_keep']= $this->language->get('button_save_and_keep');
		$this->data['button_cancel']      = $this->language->get('button_cancel');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
	  
 		$this->data['error_warning'] = ($this->error['warning']) ? $this->error['warning'] : '';
 		$this->data['error_name'] = ($this->error['name']) ? $this->error['name'] : '';
		    

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
       		'href'      => Url::createAdminUrl('sale/plan_store') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
			
		if (!isset($this->request->get['plan_store_id'])) {
			$this->data['action'] = Url::createAdminUrl('sale/plan_store/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('sale/plan_store/update') . '&plan_store_id=' . $this->request->get['plan_store_id'] . $url;
		}
		

		$this->data['cancel'] = Url::createAdminUrl('sale/plan_store') . $url;

    	if (isset($this->request->get['plan_store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

      		$plan_store_info = $this->modelPlan_store->getPlan($this->request->get['plan_store_id']);

    	}
        
        $this->setvar('name',$plan_store_info,'');
        $this->setvar('price',$plan_store_info,'');
        $this->setvar('image',$plan_store_info,'');
        $this->setvar('has_email_marketing_module',$plan_store_info,'');
        $this->setvar('has_style_module',$plan_store_info,'');
        $this->setvar('has_layout_module',$plan_store_info,'');
        $this->setvar('has_report_module',$plan_store_info,'');
        $this->setvar('has_order_module',$plan_store_info,'');
        $this->setvar('has_custom_domain',$plan_store_info,'');
        $this->setvar('has_premium_template',$plan_store_info,'');
        $this->setvar('sort_order',$plan_store_info,'');
        
		if (!empty($plan_store_info['image']) && file_exists(DIR_IMAGE . $plan_store_info['image'])) {
			$this->data['preview'] = NTImage::resizeAndSave($plan_store_info['image'], 100, 100);
		} else {
			$this->data['preview'] = NTImage::resizeAndSave('no_image.jpg', 100, 100);
		}
        
        $this->data['Url'] = new Url;

        //TODO: mostrar los productos al scrolldown para no colapsar el navegador cuando se listan todos los productos
        $scripts[] = array('id'=>'form','method'=>'ready','script'=>
            "$('#name').blur(function(e){
                $.getJSON('". Url::createAdminUrl('common/home/slug') ."',{ slug : $(this).val() },function(data){
                        $('#slug').val(data.slug);
                });
            });
            
            $('#form').ntForm({
                submitButton:false,
                cancelButton:false,
                lockButton:false
            });
            $('textarea').ntTextArea();
            
            var form_clean = $('#form').serialize();  
            
            window.onbeforeunload = function (e) {
                var form_dirty = $('#form').serialize();
                if(form_clean != form_dirty) {
                    return 'There is unsaved form data.';
                }
            };
            
            $('.tabs li').on('click',function() {
                $('.tabs li').each(function(){
                   $('#' + this.id + '_content').hide();
                   $(this).removeClass('active'); 
                });
                $(this).addClass('active');
                $('#' + this.id + '_content').show(); 
           }); 
            $('.sidebar .tab').on('click',function(){
                $(this).closest('.sidebar').addClass('show').removeClass('hide').animate({'right':'0px'});
            });
            $('.sidebar').mouseenter(function(){
                clearTimeout($(this).data('timeoutId'));
            }).mouseleave(function(){
                var e = this;
                var timeoutId = setTimeout(function(){
                    if ($(e).hasClass('show')) {
                        $(e).removeClass('show').addClass('hide').animate({'right':'-400px'});
                    }
                }, 600);
                $(this).data('timeoutId', timeoutId); 
            });");

            
        $scripts[] = array('id'=>'categoryFunctions','method'=>'function','script'=>
            "function saveAndExit() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndExit'>\").submit(); 
            }
            
            function saveAndKeep() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndKeep'>\").submit(); 
            }
            
            function saveAndNew() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndNew'>\").submit(); 
            }
            
            function image_delete(field, preview) {
                $('#' + field).val('');
                $('#' + preview).attr('src','". HTTP_IMAGE ."cache/no_image-100x100.jpg');
            }
            
            function image_upload(field, preview) {
                var height = $(window).height() * 0.8;
                var width = $(window).width() * 0.8;
                
            	$('#dialog').remove();
            	$('.box').prepend('<div id=\"dialog\" style=\"padding: 3px 0px 0px 0px;\"><iframe src=\"". Url::createAdminUrl("common/filemanager") ."&field=' + encodeURIComponent(field) + '\" style=\"padding:0; margin: 0; display: block; width: 100%; height: 100%;\" frameborder=\"no\" scrolling=\"auto\"></iframe></div>');
                
                $('#dialog').dialog({
            		title: '".$this->data['text_image_manager']."',
            		close: function (event, ui) {
            			if ($('#' + field).attr('value')) {
            				$.ajax({
            					url: '". Url::createAdminUrl("common/filemanager/image") ."',
            					type: 'POST',
            					data: 'image=' + encodeURIComponent($('#' + field).val()),
            					dataType: 'text',
            					success: function(data) {
            						$('#' + preview).replaceWith('<img src=\"' + data + '\" id=\"' + preview + '\" class=\"image\" onclick=\"image_upload(\'' + field + '\', \'' + preview + '\');\">');
            					}
            				});
            			}
            		},	
            		bgiframe: false,
            		width: width,
            		height: height,
            		resizable: false,
            		modal: false
            	});}");
            

        $this->scripts = array_merge($this->scripts,$scripts);
        

		$this->template = 'sale/plan_store_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}  
	 
  	/**
  	 * ControllerSalePlanStore::validateForm()
  	 * 
  	 * @see Request
  	 * @see Language
  	 * @return bool
  	 */
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/plan_store')) {
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
  	 * ControllerSalePlanStore::validateDelete()
  	 * 
  	 * @see Request
  	 * @see Language
  	 * @return bool
  	 */
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/plan_store')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}	
        //TODO: colocar validaciones propias
		
		$this->load->auto('sale/product');

		foreach ($this->request->post['selected'] as $plan_store_id) {
  			$product_total = $this->modelProduct->getTotalProductsByPlanId($plan_store_id);
    
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
    
    /**
     * ControllerStoreCategory::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('sale/plan_store');
        $status = $this->modelPlan_store->getPlan($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelPlan_store->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelPlan_store->desactivate($_GET['id']);
                echo -1;
            }
            
        } else {
            echo 0;
        }
     }
    
    /**
     * ControllerStoreCategory::sortable()
     * ordenar el listado actualizando la posición de cada objeto
     * @return boolean
     * */
     public function sortable() {
        if (!isset($_POST['tr'])) return false;
        $this->load->auto('sale/plan_store');
        $result = $this->modelPlan_store->sortProduct($_POST['tr']);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
     
}