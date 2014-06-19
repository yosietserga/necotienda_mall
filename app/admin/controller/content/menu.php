<?php 
/**
 * ControllerContentMenu
 * 
 * @package  NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.1.0
 * @access public
 * @see Controller
 */
class ControllerContentMenu extends Controller { 
	private $error = array();
 
	/**
	 * ControllerContentMenu::index()
	 * 
	 * @return void
	 */
	public function index() {
		$this->document->title = $this->language->get('heading_title');
        $this->getList();
	}

	/**
	 * ControllerContentMenu::insert()
	 * 
     * @see Load
     * @see Model
     * @see Request
     * @see Document
     * @see Session
     * @see Redirect
     * @see getForm
	 * @return void
	 */
	public function insert() {
		$this->document->title = $this->language->get('heading_title');
		$this->getForm();
	}

	/**
	 * ControllerContentMenu::update()
	 * 
     * @see Load
     * @see Model
     * @see Request
     * @see Document
     * @see Session
     * @see Redirect
     * @see getForm
	 * @return void
	 */
	public function update() {
		$this->document->title = $this->language->get('heading_title');
        $this->getForm();
    }

    /**
     * ControllerContentMenu::delete()
     * elimina un objeto
     * @return boolean
     * */
     public function delete() {
        $this->load->auto('content/menu');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelMenu->delete($id);
            }
		} else {
            $this->modelMenu->delete($_GET['id']);
		}
     }
    
	/**
	 * ControllerContentMenu::getById()
	 * 
     * @see Load
     * @see Model
     * @see Document
     * @see Session
     * @see Language
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
       		'href'      => Url::createAdminUrl("content/menu"),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
           //TODO: crear función para generar urls absolutas a partir de un controller						
		$this->data['insert'] = Url::createAdminUrl("content/menu/insert");
		$this->data['delete'] = Url::createAdminUrl("content/menu/delete");
        
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['success'] = $this->session->has('success') ? $this->session->get('success') : '';
        $this->session->clear('success');
        
        // SCRIPTS
        $scripts[] = array('id'=>'menuList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("content/menu/activate")."&id=' + e,
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
                	$.getJSON('". Url::createAdminUrl("content/menu/delete") ."',{
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
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("content/menu/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("content/menu/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("content/menu/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
            
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("content/menu/grid") ."',
                beforeSend:function(){
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                },
                success:function(data){
                    $('#gridPreloader').hide();
                    $('#gridWrapper').html(data).show();
                    
                    $('ol.items').nestedSortable({
            			forcePlaceholderSize: true,
            			handle: 'div.item',
            			helper:	'clone',
            			items: 'li',
            			maxLevels: 3,
            			opacity: .6,
            			placeholder: 'placeholder',
            			revert: 250,
            			tabSize: 25,
            			tolerance: 'pointer',
            			toleranceElement: '> div.item',
                        update:  function (event, ui) {
                            var parent = ui.item.parents('li');
                            
                            if (parent.length > 0) {
                                parent_id = parent.attr('id');
                            } else {
                                parent_id = 0;
                            }
                            
                            $.getJSON('". Url::createAdminUrl("content/menu/updateparent") ."',{'parent_id':parent_id,'menu_id':ui.item.attr('id')},function(data){
                                if (data.error) {
                                    $('#msg').fadeIn().append('<div class=\"message message success\"'+ data.msg +'</div>').delay(3600).fadeOut();
                                }
                            });
                            
                            var sorts = {}; 
                            var i = 0;
                            $('ol.items li').each(function(){
                                i++;
                                sorts[i] = $(this).attr('id');
                            }); 
                            
                            $.post('". Url::createAdminUrl("content/menu/sortable") ."',sorts,
                            function(data){
                                if (data.error) {
                                    $('#msg').fadeIn().append('<div class=\"message message success\"'+ data.msg +'</div>').delay(3600).fadeOut();
                                }
                            });
                        }
            		});
                }
            });");
             
        $this->scripts = array_merge($this->scripts,$scripts);
        
        
		$this->template = 'content/menu_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));  
	}
    
	/**
	 * ControllerContentMenu::grid()
	 * 
     * @see Load
     * @see Model
     * @see Document
     * @see Session
     * @see Language
     * @see Response
	 * @return void
	 */
	public function grid() {
		$filter_name = !empty($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$filter_product = !empty($this->request->get['filter_product']) ? $this->request->get['filter_product'] : null;
		$filter_date_start = !empty($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : null;
		$filter_date_end = !empty($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : null;
		$page = !empty($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = !empty($this->request->get['sort']) ? $this->request->get['sort'] : 'cd.name';
		$order = !empty($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
        
        $start = explode("/",$filter_date_start);
        $end = explode("/",$filter_date_end);
        
		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_product'  => $filter_product,
			'filter_date_start'=> ($filter_date_start) ? date('Y-m-d h:i:s',strtotime($start[2]."-".$start[1]."-".$start[0])) : null,
			'filter_date_end' => ($filter_date_end) ? date('Y-m-d h:i:s',strtotime($end[2]."-".$end[1]."-".$end[0])) : null,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		);
        
        $results = $this->modelMenu->getAll();
        
        foreach ($results as $key => $result) {
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
                        'href'  =>Url::createAdminUrl('content/menu/update') . '&menu_id=' . $result['menu_id'] . $url,
                        'img'   => 'edit.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
            
            $this->data['menus'][] = array(
                'menu_id'=>$result['menu_id'],
                'name'=>$result['name'],
                'route'=>$result['route'],
                'position'=>$result['position'],
                'status'=>($result['status']) ? 'Activado' : 'Desactivado',
                'action'=>$action
            );
        }
        
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_warning'] = $this->session->has('success') ? $this->session->get('success') : '';
        $this->session->clear('success');
		$this->template = 'content/menu_grid.tpl';
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));  
	}

     public function getMenus($menus,$parent = false) {
	    $output = '';
        if ($menus) {
            $output .= ($parent) ? '<ol class="items">' : '<ol>';
    		foreach ($menus as $result) {
                $output .= '<li id="'. $result['menu_id'] .'">';
                $output .= '<div class="item">';
                $output .= '<input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="'. $result['menu_id'] .'">';
                $output .= '<b class="name">'. $result['name'] .'</b>';
                
                $_img = ((int)$result['status'] == 1) ? 'good.png' : 'minus.png';
                
                $output .= '<div class="actions">';
                /*
                $output .= '<a title="'. $this->language->get('text_see') .'" href="'. Url::createAdminUrl("content/menu/see",array('menu_id'=>$result['menu_id'])) .'">';
                $output .= '<img src="image/report.png" alt="'. $this->language->get('text_see') .'" />';
                $output .= '</a>';
                */
                $output .= '<a title="'. $this->language->get('text_edit') .'" href="'. Url::createAdminUrl("content/menu/update",array('menu_id'=>$result['menu_id'])) .'">';
                $output .= '<img src="image/edit.png" alt="'. $this->language->get('text_edit') .'" />';
                $output .= '</a>';
                
                $output .= '<a title="'. $this->language->get('text_activate') .'" onclick="activate('. $result['menu_id'] .')">';
                $output .= '<img id="img_'. $result['menu_id'] .'" src="image/'. $_img .'" alt="'. $this->language->get('text_activate') .'" />';
                $output .= '</a>';
               
                $output .= '<a title="'. $this->language->get('text_delete') .'" onclick="eliminar('. $result['menu_id'] .')">';
                $output .= '<img src="image/delete.png" alt="'. $this->language->get('text_delete') .'" />';
                $output .= '</a>';
               /*
                $output .= '<a title="'. $this->language->get('text_copy') .'" onclick="copy('. $result['menu_id'] .')">';
                $output .= '<img src="image/copy.png" alt="'. $this->language->get('text_copy') .'" />';
                $output .= '</a>';
               */
                $output .= '</div>';
                
                $output .= '</div>';
                
                // submenus
    			if ($result['childrens']) {
                    $output .= $this->getMenus($result['childrens']);
    			}
                
                $output .= '</li>';
            }
            $output .= '</ol>';
        }	
        return $output;
	}
	
	/**
	 * ControllerContentMenu::getForm()
	 * 
     * @see Load
     * @see Model
     * @see Document
     * @see Session
     * @see Language
     * @see Response
	 * @return void
	 */
	private function getForm() {
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('content/menu'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['menu_id'])) {
			$this->data['action'] = Url::createAdminUrl('content/menu/save');
		} else {
			$this->data['action'] = Url::createAdminUrl('content/menu/save',array('menu_id'=>$this->request->get['menu_id']));
		}
		
		$this->data['cancel'] = Url::createAdminUrl('content/menu');

		if (isset($this->request->get['menu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$menu_info = $this->modelMenu->getMenu($this->request->get['menu_id']);
      		$this->data['links'] = $this->getLinks();
    	}
        
        $this->data['stores'] = $this->modelStore->getAll();
        $this->data['_stores'] = $this->modelMenu->getStores($this->request->get['menu_id']);
        $this->data['pages'] = $this->getAll();
        $this->data['post_categories'] = $this->getPostCategories();
        $this->data['categories'] = $this->getCategories();
        //$this->data['manufacturers'] = $this->modelManufacturer->getAll();
		
        $this->setvar('name',$menu_info,'');
        $this->setvar('default',$menu_info,0);
        $this->setvar('status',$menu_info,1);
        $this->setvar('parent_id',$menu_info,0);
        $this->setvar('sort_order',$menu_info,0);
        
		//$this->data['menus'] = $this->modelMenu->getMenus(0);

        $scripts[] = array('id'=>'menuForm','method'=>'ready','script'=>
            "$('#_name').on('change', function(){
                $('#name').val($(this).val());
            });
            
            $('ol.items').nestedSortable({
      			forcePlaceholderSize: true,
        		handle: 'div.item',
        		helper:	'clone',
        		items: 'li',
        		maxLevels: 3,
        		opacity: .6,
        		placeholder: 'placeholder',
        		revert: 250,
        		tabSize: 25,
        		tolerance: 'pointer',
        		toleranceElement: '> div.item',
                update:  function (event, ui) {
                    $('ol.items li').each(function(){
                        parentIndex = '';
                        idIndex = '';
                        parent = $(this).parents('li');
                        if (parent.index() >= 0) {
                            principal = $(parent).parents('li');
                            if (principal.index() >= 0) {
                                parent = $(this).parents('li:eq(0)');
                                parentIndex = '['+ $(principal).index() +'.'+ $(parent).index() +'.'+ $(this).index() +']';
                                idIndex = $(principal).index() +'.'+ $(parent).index() +'.'+ $(this).index();
                            } else {
                                parentIndex = '['+ $(parent).index() +'.'+ $(this).index() +']';
                                idIndex = $(parent).index() +'.'+ $(this).index();
                            } 
                        } else {
                            parentIndex = '['+ $(this).index() +']';
                            idIndex = $(this).index();
                        }
                        
                        $(this).find('.menu_link').attr({
                            id:'link.'+ idIndex +'.link',
                            name:'link'+ parentIndex +'[link]'
                        });
                        $(this).find('.menu_tag').attr({
                            id:'link.'+ idIndex +'.tag',
                            name:'link'+ parentIndex +'[tag]'
                        });
                    });
                }
       		});");
            
        $scripts[] = array('id'=>'menuFunctions','method'=>'function','script'=>
            "function saveAndKeep() {
                $('#temp').remove();
                $('#menuMsg').append('<div class=\"message success\" id=\"temp\">". $this->language->get('text_success') ."</div>');
                window.onbeforeunload = null;
                
                data = $.extend(true, $('#formMenu').serializeFormJSON(), $('#menuItems').serializeFormJSON(), {items:$('#menuItems').serialize()}); 
                
                $.post('". Url::createAdminUrl("content/menu/save") ."', data,
                function(response){
                    
                });
            }
            (function($) {
                $.fn.serializeFormJSON = function() {
                
                   var o = {};
                   var a = this.serializeArray();
                   $.each(a, function() {
                       if (o[this.name]) {
                           if (!o[this.name].push) {
                               o[this.name] = [o[this.name]];
                           }
                           o[this.name].push(this.value || '');
                       } else {
                           o[this.name] = this.value || '';
                       }
                   });
                   return o;
                };
                })(jQuery);");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        
        /* feedback form values */
        $this->data['domain'] = HTTP_HOME;
        $this->data['account_id'] = C_CODE;
        $this->data['local_ip'] = $_SERVER['SERVER_ADDR'];
        $this->data['remote_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->data['server'] = serialize($_SERVER); //TODO: encriptar todos estos datos con una llave que solo yo poseo
        
        
		$this->template = 'content/menu_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

    public function save() {
        $this->load->model('content/menu');
        $this->load->library('json');
        $json = $data = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $json = $data = $_POST;
            if ($this->request->hasQuery('menu_id')) {
                $this->modelMenu->update($this->request->get['menu_id'], $data);
        		$this->session->set('success',$this->language->get('text_success'));
                $this->redirect(Url::createAdminUrl("content/menu/update",array('menu_id'=>$this->request->get['menu_id'])));
            } else {
    			$menu_id = $this->modelMenu->add($data);
    			$this->session->set('success',$this->language->get('text_success'));
                $this->redirect(Url::createAdminUrl("content/menu/update",array('menu_id'=>$menu_id)));
			}
		}
		$this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }

	/**
	 * ControllerContentMenu::validateForm()
	 * 
     * @see User
     * @see Request
     * @see Language
	 * @return bool
	 */
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'content/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        //TODO: agregar funciones de validación propias

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ControllerContentMenu::validateDelete()
	 * 
     * @see User
     * @see Language
	 * @return bool
	 */
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'content/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        //TODO: agregar funciones de validación propias
 
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
    
    /**
     * ControllerContentMenu::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('content/menu');
        $status = $this->modelMenu->getMenu($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelMenu->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelMenu->desactivate($_GET['id']);
                echo -1;
            }
            
        } else {
            echo 0;
        }
     }
    
    /**
     * ControllerContentMenu::sortable()
     * ordenar el listado actualizando la posición de cada objeto
     * @return boolean
     * */
     public function sortable() {
        $this->load->auto('content/menu');
        $result = $this->modelMenu->sortMenu($_POST);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
     
     public function getAll($parent_id=0, $marginLeft=5) {
        $pages = $this->modelPage->getAll($parent_id);
        $return = '';
        if ($pages) {
            foreach ($pages as $key => $value) {
                $return .= '<li style="padding-left:'.$marginLeft.'px">';
                $return .= '<input type="checkbox" name="pages[]" value="'. $value['post_id'] .'" />';
                $return .= '<b>'. $value['title'] .'</b>';
                $return .= '</li>';
                
                $childrens = $this->modelPage->getAll($value['post_id']);
                if ($childrens) {
                    $return .= $this->getAll($value['post_id'],$marginLeft + 20);
                }
            }
        }
        return $return;
     }
     
     public function getPostCategories($parent_id=0, $marginLeft=5) {
        $categories = $this->modelPost_category->getAllForMenu($parent_id);
        $return = '';
        if ($categories) {
            foreach ($categories as $key => $value) {
                $return .= '<li style="padding-left:'.$marginLeft.'px">';
                $return .= '<input type="checkbox" name="post_categories[]" value="'. $value['post_category_id'] .'" />';
                $return .= '<b>'. $value['name'] .'</b>';
                $return .= '</li>';
                
                $childrens = $this->modelPost_category->getAllForMenu($value['post_category_id']);
                if ($childrens) {
                    $return .= $this->getPostCategories($value['post_category_id'],$marginLeft + 20);
                }
            }
        }
        return $return;
     }
     
     public function getCategories($parent_id=0, $marginLeft=5) {
        $categories = $this->modelCategory->getAllForMenu($parent_id);
        $return = '';
        if ($categories) {
            foreach ($categories as $key => $value) {
                $return .= '<li style="padding-left:'.$marginLeft.'px">';
                $return .= '<input type="checkbox" name="categories[]" value="'. $value['category_id'] .'" />';
                $return .= '<b>'. $value['name'] .'</b>';
                $return .= '</li>';
                
                $childrens = $this->modelCategory->getAllForMenu($value['category_id']);
                if ($childrens) {
                    $return .= $this->getCategories($value['category_id'],$marginLeft + 20);
                }
            }
        }
        return $return;
     }
     

     public function getLinks($parent_id=0) {
	    $output = '';
        $links = $this->modelMenu->getLinks($this->request->get['menu_id'],$parent_id);
        if ($links) {
    		foreach ($links as $key => $result) {
    		    $index =  ($parent_id) ? $parent_id .".". $result['menu_link_id'] : $result['menu_link_id'];
                $output .= '<li id="li_'. $index .'">';
                $output .= '<div class="item">';
                $output .= '<b>'. $result['tag'] .'</b>';
                $output .= '<a class="showOptions" onclick="$(\'#linkOptions'. $index .'\').slideToggle(\'fast\')">&darr;</a>';
                $output .= '</div>';
                $output .= '<div id="linkOptions'. $index .'" class="itemOptions">';
                
                $output .= '<div class="row">';
                $output .= '<label class="neco-label" for="link.'. $index .'.link">Url:</label>';
                $output .= '<input type="url" id="link.'. $index .'.link" name="link['. $index .'][link]" value="'. $result['link'] .'" style="width: 60%;" class="menu_link" />';
                $output .= '</div>';
                
                $output .= '<div class="clear"></div>';
                
                $output .= '<div class="row">';
                $output .= '<label class="neco-label" for="link.'. $index .'.tag">Etiqueta:</label>';
                $output .= '<input type="text" id="link.'. $index .'.tag" name="link['. $index .'][tag]" value="'. $result['tag'] .'" style="width: 60%;" class="menu_tag" />';
                $output .= '</div>';
                
                $output .= '<div class="clear"></div>';
                
                $output .= '<a style="float:right;font-size:10px;" onclick="$(\'#li_'. $index .'\').remove()">[ Eliminar ]</a>';
                $output .= '</div>';
                
                // subcategories
                $childrens = $this->modelMenu->getLinks($this->request->get['menu_id'],$result['menu_link_id']);
    			if ($childrens) {
                    $output .= '<ol>';
                    $output .= $this->getLinks($result['menu_link_id']);
                    $output .= '</ol>';
    			}
                
                $output .= '</li>';
            }
            
        }	
        return $output;
	}
	
     public function page() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        
        $data = array();
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['pages'])) {
    		$this->load->model('content/page');
    		$this->load->library('url');
    		$this->load->library('json');
        
            
            foreach ($this->request->post['pages'] as $key => $value) {
                $result = $this->modelPage->getPage($value);
                if (!$result) continue;
                $data[$key]['title'] = $result['title'];
                $data[$key]['href'] = Url::createUrl('content/page',array('page_id' => $result['post_id']),'NONSSL',HTTP_CATALOG);
            }
        } 
        
		$this->response->setOutput(Json::encode($data));       
     }
     
     public function category() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        
        $data = array();
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['categories'])) {
    		$this->load->model('store/category');
    		$this->load->library('url');
    		$this->load->library('json');
            foreach ($this->request->post['categories'] as $key => $value) {
                $result = $this->modelCategory->getById($value);
                if (!$result) continue;
                $path = ($result['parent_id']) ? $result['parent_id'] ."_". $result['category_id'] : $result['category_id'];
                
                $data[$value]['title']  = $result['name'];
                $data[$value]['href']   = Url::createUrl('store/category',array('path' => $path),'NONSSL',HTTP_CATALOG);
            }
        } 
        
		$this->response->setOutput(Json::encode($data));       
     }
     
     public function postcategory() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        
        $data = array();
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['post_categories'])) {
    		$this->load->model('content/post_category');
    		$this->load->library('url');
    		$this->load->library('json');
            foreach ($this->request->post['post_categories'] as $key => $value) {
                $result = $this->modelPost_category->getById($value);
                if (!$result) continue;
                $path = ($result['parent_id']) ? $result['parent_id'] ."_". $result['post_category_id'] : $result['post_category_id'];
                
                $data[$value]['title']  = $result['name'];
                $data[$value]['href']   = Url::createUrl('content/category',array('path' => $path),'NONSSL',HTTP_CATALOG);
            }
        } 
        
		$this->response->setOutput(Json::encode($data));       
     }
}