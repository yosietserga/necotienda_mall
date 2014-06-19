<?php 
/**
 * ControllerContentBanner
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerContentBanner extends Controller {
	private $error = array(); 
     
  	/**
  	 * ControllerContentBanner::index()
     * 
  	 * @see Load
  	 * @see Language
  	 * @see Document
  	 * @see Model
  	 * @see getList
  	 * @return void
  	 */
  	public function index() {
		$this->load->language('content/banner');
		$this->document->title = $this->language->get('heading_title'); 
		$this->getList();
  	}
  
  	/**
  	 * ControllerContentBanner::insert()
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
            if (empty($this->request->post['publish_date_end'])) {
                $this->request->post['publish_date_end'] = '0000-00-00 00:00:00';
            } else {
                $dpe = explode("/",$this->request->post['publish_date_end']);
                $this->request->post['publish_date_end'] = $dpe[2] ."-". $dpe[1] ."-". $dpe[0] .' 00:00:00';
            }
            
            $dps = explode("/",$this->request->post['publish_date_start']);
            $this->request->post['publish_date_start'] = $dps[2] ."-". $dps[1] ."-". $dps[0] .' 00:00:00';
            
			$banner_id = $this->modelBanner->add($this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
    	  
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('content/banner/update',array('id'=>$banner_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('content/banner/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('content/banner')); 
            }
        }
    	$this->getForm();
  	}

  	/**
  	 * ControllerContentBanner::update()
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
    	   
            if (empty($this->request->post['publish_date_end'])) {
                $this->request->post['publish_date_end'] = '0000-00-00 00:00:00';
            } else {
                $dpe = explode("/",$this->request->post['publish_date_end']);
                $this->request->post['publish_date_end'] = $dpe[2] ."-". $dpe[1] ."-". $dpe[0] .' 00:00:00';
            }
            
            $dps = explode("/",$this->request->post['publish_date_start']);
            $this->request->post['publish_date_start'] = $dps[2] ."-". $dps[1] ."-". $dps[0] .' 00:00:00';
            
			$this->modelBanner->update($this->request->get['id'], $this->request->post);
            
			$this->session->data['success'] = $this->language->get('text_success');
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('content/banner/update',array('id'=>$this->request->get['id']))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('content/banner/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('content/banner')); 
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
        $this->load->auto('content/banner');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelBanner->delete($id);
            }
		} else {
            $this->modelBanner->delete($_GET['id']);
		}
     }
    
  	/**
  	 * ControllerMarketingNewsletter::copy()
     * duplicar un objeto
  	 * @return boolean
  	 */
  	public function copy() {
        $this->load->auto('content/banner');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelBanner->copy($id);
            }
		} else {
            $this->modelBanner->copy($_GET['id']);
		}
        echo 1;
  	}
      
  	/**
  	 * ControllerContentBanner::getById()
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
       		'href'      => Url::createAdminUrl("common/home"),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl("content/banner") . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = Url::createAdminUrl("content/banner/insert") . $url;
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_insert'] = $this->language->get('button_insert');
 
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
        $scripts[] = array('id'=>'bannerList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("content/banner/activate")."&id=' + e,
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
                $.getJSON('".Url::createAdminUrl("content/banner/copy")."&id=' + e, function(data) {
                    $('#gridWrapper').load('". Url::createAdminUrl("content/banner/grid") ."',function(response){
                        $('#gridPreloader').hide();
                        $('#gridWrapper').show();
                    });
                });
            }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("content/banner/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function copyAll() {
                $('#gridWrapper').hide();
                $('#gridPreloader').show();
                $.post('". Url::createAdminUrl("content/banner/copy") ."',$('#form').serialize(),function(){
                    $('#gridWrapper').load('". Url::createAdminUrl("content/banner/grid") ."',function(){
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
                    $.post('". Url::createAdminUrl("content/banner/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("content/banner/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("content/banner/grid") ."',function(e){
                $('#gridPreloader').hide();
                $('#list tbody').sortable({
                    opacity: 0.6, 
                    cursor: 'move',
                    handle: '.move',
                    update: function() {
                        $.ajax({
                            'type':'post',
                            'dateType':'json',
                            'url':'". Url::createAdminUrl("content/banner/sortable") ."',
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
                url:'". Url::createAdminUrl("content/banner/grid") ."',
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
        
		$this->template = 'content/banner_list.tpl';
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
		if (isset($this->request->get['filter_date_start'])) { $url .= '&filter_date_start=' . $this->request->get['filter_date_start']; }
		if (isset($this->request->get['filter_date_end'])) { $url .= '&filter_date_end=' . $this->request->get['filter_date_end']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 
			
		$this->data['banners'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $limit
		);
		
		$banner_total = $this->modelBanner->getTotal($data);
		$results = $this->modelBanner->getAll($data);
				    	
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
                        'href'  => Url::createAdminUrl("content/banner/update") . '&id=' . $result['banner_id'] . $url,
                        'img'   => 'edit.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
			
      		$this->data['banners'][] = array(
				'banner_id' => $result['banner_id'],
				'name'       => $result['name'],
				'publish_date_start'    => date('d-m-Y',strtotime($result['publish_date_start'])),
				'publish_date_end'      => ($result['publish_date_end'] != '0000-00-00') ? date('d-m-Y',strtotime($result['publish_date_end'])) : 'Indeterminado',
				'selected'   => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}
        
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
				
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_name'] = Url::createAdminUrl("content/banner/grid") . '&sort=name' . $url;
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $banner_total;
		$pagination->ajax = true;
		$pagination->ajaxTarget = "gridWrapper";
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl("content/banner/grid") . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		$this->data['filter_name'] = $filter_name;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'content/banner_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}

  	/**
  	 * ControllerContentBanner::getForm()
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
    	$this->data['heading_title'] = $this->language->get('heading_title');
 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';
 		$this->data['error_name']    =  (isset($this->error['name'])) ? $this->error['name'] : '';
		
		$url = '';
        
		if (isset($this->request->get['filter_name'])) $url .= '&filter_name=' . $this->request->get['filter_name'];
		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl("common/home"),
       		'text'      => $this->language->get('text_home'),
			'separator' => false
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl("content/banner") . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		if (!isset($this->request->get['id'])) {
			$this->data['action'] = Url::createAdminUrl("content/banner/insert") . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl("content/banner/update") . '&id=' . $this->request->get['id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl("content/banner") . $url;

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$banner_info = $this->modelBanner->getById($this->request->get['id']);
    	}

        $this->setvar('name',$banner_info,'');
        $this->setvar('publish_date_start',$banner_info,'');
        $this->setvar('publish_date_end',$banner_info,'');
        $this->setvar('jquery_plugin',$banner_info,'');
        $this->setvar('banner_items',$banner_info,'');
        $this->setvar('banner_stores',$banner_info,'');

		$this->data['stores'] = $this->modelStore->getAll();
		$this->data['NTImage'] = new NTImage;
		
        $folderJS = DIR_JS . 'sliders/';
        $directories = glob($folderJS . "*", GLOB_ONLYDIR);
		$this->data['sliders'] = array();
		foreach ($directories as $key => $directory) {
			$this->data['sliders'][$key] = basename($directory);
		}
        
        /*
        $products = $this->cache->get("banner.products");
        if (!$products) {
            foreach ($this->modelProduct->getAll() as $product) {
                
    			if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
    				$image = NTImage::resizeAndSave($product['image'], 40, 40);
    			} else {
    				$image = NTImage::resizeAndSave('no_image.jpg', 40, 40);
    			}
    			
                
                $this->data['products'][] = array(
                    'product_id'=>$product['product_id'],
                    'href' =>$this->model_tool_seo_url->rewrite(HTTP_CATALOG.'index.php?r=store/product&product_id='.$product['product_id']),
                    'image'=>$image,
                    'name' =>$product['name']
                );
                $this->cache->set("banner.products",serialize($this->data['products']));
            }
        } else {
            $this->data['products'] = unserialize($products);
        }
        
        $categories = $this->cache->get("banner.categories");
        if (!$categories) {
            foreach ($this->modelCategory->getAll() as $category) {
                $this->data['categories'][] = array(
                    'category_id'=>$category['category_id'],
                    'href' =>$this->model_tool_seo_url->rewrite(HTTP_CATALOG.'index.php?r=store/category&path='.$category['category_id']),
                    'name' =>$category['name']
                );
                $this->cache->set("banner.categories",serialize($this->data['categories']));
            }
        } else {
            $this->data['categories'] = unserialize($categories);
        }
        */
        
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->modelLanguage->getAll();

        $scripts[] = array('id'=>'bannerFunctions','method'=>'function','script'=>
            "function setLink(link) {
                $('input[name=link]').val(link);
                $('#products').dialog('close');
                $('#categories').dialog('close');
            }
            function showProducts() {
                $('#products').dialog({
            		width: 700,
            		height: 350
                });
            }
            function showCategories() {
                $('#categories').dialog({
            		width: 700,
            		height: 350
                });
            }
            
            function image_delete(field, preview) {
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
        
		$this->template = 'content/banner_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	} 
	
  	/**
  	 * ControllerContentBanner::validateForm()
  	 * 
     * @see User
     * @see Language
     * @see Request
  	 * @return bool
  	 */
  	private function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'content/banner')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
        
    	if (!$this->error) {
			return true;
    	} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
      		return false;
    	}
  	}
	
  	/**
  	 * ControllerContentBanner::validateDelete()
  	 * 
     * @see User
     * @see Language
     * @see Request
  	 * @return bool
  	 */
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'content/banner')) {
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
     * ControllerContentCategory::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->model('content/banner');
        $status = $this->modelBanner->getById($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelBanner->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelBanner->desactivate($_GET['id']);
                echo -1;
            }
            
        } else {
            echo 0;
        }
     }
    
    /**
     * ControllerContentCategory::sortable()
     * ordenar el listado actualizando la posición de cada objeto
     * @return boolean
     * */
     public function sortable() {
        if (!isset($_POST['tr'])) return false;
        $this->load->model('content/banner');
        $result = $this->modelBanner->sortBanner($_POST['tr']);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
}
?>