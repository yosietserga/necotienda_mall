<?php
/**
 * ControllerStoreManufacturer
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerStoreManufacturer extends Controller { 
	private $error = array();
  
  	/**
  	 * ControllerStoreManufacturer::index()
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
  	 * ControllerStoreManufacturer::insert()
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
			$manufacturer_id = $this->modelManufacturer->add($this->request->post);
            $this->modelManufacturer->setProperty($manufacturer_id,'style','view', $this->request->getPost('view'));

			$this->session->set('success',$this->language->get('text_success'));
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('store/manufacturer/update',array('manufacturer_id'=>$manufacturer_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('store/manufacturer/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('store/manufacturer')); 
            }
		}
    
    	$this->getForm();
  	} 
   
  	/**
  	 * ControllerStoreManufacturer::update()
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
			$this->modelManufacturer->update($this->request->getQuery('manufacturer_id'), $this->request->post);
            $this->modelManufacturer->setProperty($this->request->getQuery('manufacturer_id'),'style','view', $this->request->getPost('view'));

			$this->session->set('success',$this->language->get('text_success'));
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('store/manufacturer/update',array('manufacturer_id'=>$this->request->getQuery('manufacturer_id')))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('store/manufacturer/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('store/manufacturer')); 
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
        $this->load->auto('store/manufacturer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelManufacturer->delete($id);
            }
		} else {
            $this->modelManufacturer->delete($_GET['id']);
		}
     }
    
  	/**
  	 * ControllerStoreManufacturer::getById()
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
       		'href'      => Url::createAdminUrl('store/manufacturer') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = Url::createAdminUrl('store/manufacturer/insert') . $url;
		$this->data['delete'] = Url::createAdminUrl('store/manufacturer/delete') . $url;	

 		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}

        // SCRIPTS
        $scripts[] = array('id'=>'manufacturerList','method'=>'function','script'=>
            "function activate(e) {
                $.getJSON('". Url::createAdminUrl("store/manufacturer/activate") ."',{
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
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("store/manufacturer/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("store/manufacturer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("store/manufacturer/delete") ."',{
                        id:e
                    });
                }
                return false;
             }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("store/manufacturer/grid") ."',function(e){
                $('#gridPreloader').hide();
                $('#list tbody').sortable({
                    opacity: 0.6, 
                    cursor: 'move',
                    handle: '.move',
                    update: function() {
                        $.ajax({
                            'type':'post',
                            'dateType':'json',
                            'url':'". Url::createAdminUrl("store/manufacturer/sortable") ."',
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
                url:'". Url::createAdminUrl("store/manufacturer/grid") ."',
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
        
		$this->template = 'store/manufacturer_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
    
    public function grid() {
        $this->load->auto('image');
        
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$filter_product = isset($this->request->get['filter_product']) ? $this->request->get['filter_product'] : null;
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
		
		$this->data['manufacturers'] = array();

		$data = array(
			'filter_name' => $filter_name, 
			'filter_product' => $filter_product, 
			'filter_date_start' => $filter_date_start, 
			'filter_date_end' => $filter_date_end, 
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$manufacturer_total = $this->modelManufacturer->getAllTotal();
	
		$results = $this->modelManufacturer->getAll($data);
 
    	foreach ($results as $result) {
				$action = array(
                'edit'      => array(
                        'action'  => 'edit',
                        'text'  => $this->language->get('text_edit'),
                        'href'  =>Url::createAdminUrl('store/manufacturer/update') . '&manufacturer_id=' . $result['manufacturer_id'] . $url,
                        'img'   => 'edit.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
						
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = NTImage::resizeAndSave($result['image'], 40, 40);
			} else {
				$image = NTImage::resizeAndSave('no_image.jpg', 40, 40);
			}
			
			$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'image'      => $image,
				'selected'        => isset($this->request->post['selected']) && in_array($result['manufacturer_id'], $this->request->post['selected']),
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
		
		$this->data['sort_name'] = Url::createAdminUrl('store/manufacturer/grid') . '&sort=name' . $url;
		$this->data['sort_sort_order'] = Url::createAdminUrl('store/manufacturer/grid') . '&sort=sort_order' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->ajax = true;
		$pagination->ajaxTarget = "gridWrapper";
		$pagination->total = $manufacturer_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('store/manufacturer/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->data['text_no_results']= $this->language->get('text_no_results');
		$this->data['column_image']    = $this->language->get('column_image');
		$this->data['column_name']    = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action']  = $this->language->get('column_action');	
        
		$this->template = 'store/manufacturer_grid.tpl';
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }
    
  	/**
  	 * ControllerStoreManufacturer::getForm()
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
       		'href'      => Url::createAdminUrl('store/manufacturer') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['manufacturer_id'])) {
			$this->data['action'] = Url::createAdminUrl('store/manufacturer/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('store/manufacturer/update') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('store/manufacturer') . $url;

    	if ($this->request->hasQuery('manufacturer_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$manufacturer_info = $this->modelManufacturer->getById($this->request->get['manufacturer_id']);
    	}

        $this->setvar('manufacturer_id',$manufacturer_info,'');
        $this->setvar('name',$manufacturer_info,'');
        $this->setvar('keyword',$manufacturer_info,'');
        $this->setvar('image',$manufacturer_info,'');
        $this->setvar('sort_order',$manufacturer_info,'');
        
		$this->data['stores'] = $this->modelStore->getAll();
		$this->data['_stores'] = $this->modelManufacturer->getStores($this->request->getQuery('manufacturer_id'));
        $this->data['layout'] = $this->modelManufacturer->getProperty($this->request->getQuery('manufacturer_id'),'style','view');
        
  		if (file_exists(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/common/home.tpl')) {
            $folderTPL = DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/';
    	} else {
    		$folderTPL = DIR_CATALOG . 'view/theme/default/';
    	}
        
        $directories = glob($folderTPL . "*", GLOB_ONLYDIR);
		$this->data['templates'] = array();
		foreach ($directories as $key => $directory) {
			$this->data['views'][$key]['folder'] = basename($directory);
            $files = glob($directory . "/*.tpl", GLOB_NOSORT);
            foreach ($files as $k => $file) {
    			$this->data['views'][$key]['files'][$k] = str_replace("\\","/",$file) ;
    		}
		}
                
		if (isset($manufacturer_info) && $manufacturer_info['image'] && file_exists(DIR_IMAGE . $manufacturer_info['image'])) {
			$this->data['preview'] = NTImage::resizeAndSave($manufacturer_info['image'], 100, 100);
		} else {
			$this->data['preview'] = NTImage::resizeAndSave('no_image.jpg', 100, 100);
		}
		
        //TODO: mostrar los productos al scrolldown para no colapsar el navegador cuando se listan todos los productos
        $scripts[] = array('id'=>'form','method'=>'ready','script'=>
            "$('#name').blur(function(e){
                $.getJSON('". Url::createAdminUrl('common/home/slug') ."',
                { 
                    slug : $(this).val(),
                    query : 'manufacturer_id=". $this->request->getQuery('manufacturer_id') ."',
                },
                function(data){
                        $('#slug').val(data.slug);
                });
            });
            
            $('#addsWrapper').hide();
            
            $('#addsPanel').on('click',function(e){
                var products = $('#addsWrapper').find('.row');
                
                if (products.length == 0) {
                    $.getJSON('".Url::createAdminUrl("store/manufacturer/products")."',
                        {
                            'manufacturer_id':'".$this->request->getQuery('manufacturer_id')."'
                        }, function(data) {
                            
                            $('#addsWrapper').html('<div class=\"row\"><label for=\"q\" style=\"float:left\">Filtrar listado de productos:</label><input type=\"text\" value=\"\" name=\"q\" id=\"q\" placeholder=\"Filtrar Productos\" /></div><div class=\"clear\"></div><br /><ul id=\"adds\"></ul>');
                            
                            $.each(data, function(i,item){
                                $('#adds').append('<li><img src=\"' + item.pimage + '\" alt=\"' + item.pname + '\" /><b class=\"' + item.class + '\">' + item.pname + '</b><input type=\"hidden\" name=\"Products[' + item.product_id + ']\" value=\"' + item.value + '\" /></li>');
                                
                            });
                            
                            $('#q').on('change',function(e){
                                var that = this;
                                var valor = $(that).val().toLowerCase();
                                if (valor.length <= 0) {
                                    $('#adds li').show();
                                } else {
                                    $('#adds li b').each(function(){
                                        if ($(this).text().toLowerCase().indexOf( valor ) > 0) {
                                            $(this).closest('li').show();
                                        } else {
                                            $(this).closest('li').hide();
                                        }
                                    });
                                }
                            }); 
                            
                            $('li').on('click',function() {
                                var b = $(this).find('b');
                                if (b.hasClass('added')) {
                                    b.removeClass('added').addClass('add');
                                    $(this).find('input').val(0);
                                } else {
                                    b.removeClass('add').addClass('added');
                                    $(this).find('input').val(1);
                                }
                            });
                    });
                }
            });
                
            $('#addsPanel').on('click',function(){ $('#addsWrapper').slideToggle() });");
            
        $scripts[] = array('id'=>'categoryFunctions','method'=>'function','script'=>
            "function image_delete(field, preview) {
                $('#' + field).val('');
                $('#' + preview).attr('src','". HTTP_IMAGE ."cache/no_image-100x100.jpg');
            }
            
            function image_upload(field, preview) {
                var height = $(window).height() * 0.8;
                var width = $(window).width() * 0.8;
                
            	$('#dialog').remove();
            	$('.box').prepend('<div id=\"dialog\" style=\"padding: 3px 0px 0px 0px;z-index:10000;\"><iframe src=\"". Url::createAdminUrl("common/filemanager") ."&field=' + encodeURIComponent(field) + '\" style=\"padding:0; margin: 0; display: block; width: 100%; height: 100%;z-index:10000;\" frameborder=\"no\" scrolling=\"auto\"></iframe></div>');
                
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
        
		$this->template = 'store/manufacturer_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}  
	 
  	/**
  	 * ControllerStoreManufacturer::validateForm()
  	 * 
  	 * @see Request
  	 * @see Language
  	 * @return bool
  	 */
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'store/manufacturer')) {
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
  	 * ControllerStoreManufacturer::validateDelete()
  	 * 
  	 * @see Request
  	 * @see Language
  	 * @return bool
  	 */
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'store/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}	
        //TODO: colocar validaciones propias
		
		$this->load->auto('store/product');

		foreach ($this->request->post['selected'] as $manufacturer_id) {
  			$product_total = $this->modelProduct->getAllTotalByManufacturerId($manufacturer_id);
    
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
        $this->load->auto('store/manufacturer');
        $status = $this->modelManufacturer->getById($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelManufacturer->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelManufacturer->desactivate($_GET['id']);
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
        $this->load->auto('store/manufacturer');
        $result = $this->modelManufacturer->sortProduct($_POST['tr']);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
     
     
     public function products() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        $this->load->auto("store/product");
        $this->load->auto("image");
        $this->load->auto("url");
        if ($this->request->hasQuery('manufacturer_id')) {
            $rows = $this->modelProduct->getAllByManufacturerId($this->request->getQuery('manufacturer_id'));
            $products_by_manufacturer = array();
            foreach ($rows as $row) {
                $products_by_manufacturer[] = $row['product_id'];
            }
        }
        $cache = $this->cache->get("products.for.manufacturer.form");
        if ($cache) {
            $products = unserialize($cache);
        } else {
            $model = $this->modelProduct->getAll();
            $products = $model->obj;
            $this->cache->set("products.for.manufacturer.form",serialize($products));
        }
        
        $this->data['Image'] = new NTImage();
        $this->data['Url'] = new Url;
        
        $output = array();
        
        foreach ($products as $product) {
            if (!empty($products_by_manufacturer) && in_array($product->product_id,$products_by_manufacturer)) {
                $output[] = array(
                    'product_id'=>$product->product_id,
                    'pimage'    =>NTImage::resizeAndSave($product->pimage,50,50),
                    'pname'     =>$product->pname,
                    'class'     =>'added',
                    'value'     =>1
                );
            } else {
                $output[] = array(
                    'product_id'=>$product->product_id,
                    'pimage'    =>NTImage::resizeAndSave($product->pimage,50,50),
                    'pname'     =>$product->pname,
                    'class'     =>'add',
                    'value'     =>0
                );
            }
        }
        $this->load->auto('json');
        $this->response->setOutput(Json::encode($output), $this->config->get('config_compression'));
     }
}