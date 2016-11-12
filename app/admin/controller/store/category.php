<?php 
/**
 * ControllerStoreCategory
 * 
 * @package  NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.1.0
 * @access public
 * @see Controller
 */
class ControllerStoreCategory extends Controller { 
	private $error = array();
 
	/**
	 * ControllerStoreCategory::index()
	 * 
	 * @return void
	 */
	public function index() {
		$this->document->title = $this->language->get('heading_title');
        $this->getList();
	}

	/**
	 * ControllerStoreCategory::insert()
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
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            foreach ($this->request->post['category_description'] as $language_id => $description) {
                $dom = new DOMDocument;
                $dom->preserveWhiteSpace = false;
                $dom->loadHTML(html_entity_decode($description['description']));
                $images = $dom->getElementsByTagName('img');
                foreach ($images as $image) {
                    $src = $image->getAttribute('src');
                    if (preg_match('/data:([^;]*);base64,(.*)/',$src)) {
                        list($type,$img) = explode(",",$src);
                        $type = trim(substr($type,strpos($type,"/")+1,3));
                        $type = str_replace('jpe','jpg',$type);
                        //TODO: validar archivos
                        $str = $this->config->get('config_name');
                        if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
                        		$str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
                        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
                        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
                        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
                        $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
                       	$str = strtolower( trim($str, '-') );
                            
                        $filename = uniqid($str."-") . "_" . time() . "." . $type;
                        $fp = fopen( DIR_IMAGE . "data/" . $filename, 'wb' );
                        fwrite( $fp, base64_decode($img));
                        fclose( $fp );
                        $image->setAttribute('src', HTTP_IMAGE . "data/" . $filename);
                    }
                }
                $description['description'] = htmlentities($dom->saveHTML());
                $this->request->post['category_description'][$language_id] = $description;
              }
			$category_id = $this->modelCategory->add($this->request->post);
            $this->modelCategory->setProperty($category_id,'style','view', $this->request->getPost('view'));

			$this->session->set('success',$this->language->get('text_success'));
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('store/category/update',array('category_id'=>$category_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('store/category/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('store/category')); 
            }
		}
		$this->getForm();
	}

	/**
	 * ControllerStoreCategory::update()
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
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            foreach ($this->request->post['category_description'] as $language_id => $description) {
                $dom = new DOMDocument;
                $dom->preserveWhiteSpace = false;
                $dom->loadHTML(html_entity_decode($description['description']));
                $images = $dom->getElementsByTagName('img');
                foreach ($images as $image) {
                    $src = $image->getAttribute('src');
                    
                    if (preg_match('/data:([^;]*);base64,(.*)/',$src)) {
                        list($type,$img) = explode(",",$src);
                        $type = trim(substr($type,strpos($type,"/")+1,3));
                        
                        //TODO: validar imagenes
                        
                        $str = $this->config->get('config_name');
                        if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
                            $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
                        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
                        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
                        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
                        $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
                        $str = strtolower( trim($str, '-') );
                                
                        $filename = uniqid($str."-") . "_" . time() . "." . $type;
                        $fp = fopen( DIR_IMAGE . "data/" . $filename, 'wb' );
                        fwrite( $fp, base64_decode($img));
                        fclose( $fp );
                        $image->setAttribute('src', HTTP_IMAGE . "data/" . $filename);
                    }
                }
                $description['description'] = htmlentities($dom->saveHTML());
                $this->request->post['category_description'][$language_id] = $description;
            }
              
            $this->modelCategory->update($this->request->getQuery('category_id'), $this->request->post);
            $this->modelCategory->setProperty($this->request->getQuery('category_id'),'style','view', $this->request->getPost('view'));
            
    		$this->session->set('success',$this->language->get('text_success'));
    		if ($this->request->post['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('store/category/update',array('category_id'=>$this->request->get['category_id']))); 
            } elseif ($this->request->post['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('store/category/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('store/category')); 
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
        $this->load->auto('store/category');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCategory->delete($id);
            }
		} else {
            $this->modelCategory->delete($_GET['id']);
		}
     }
    
	/**
	 * ControllerStoreCategory::getById()
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
       		'href'      => Url::createAdminUrl("store/category"),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
           //TODO: crear función para generar urls absolutas a partir de un controller						
		$this->data['insert'] = Url::createAdminUrl("store/category/insert");
		$this->data['delete'] = Url::createAdminUrl("store/category/delete");
        
		
		$this->data['heading_title']      = $this->language->get('heading_title');
		$this->data['button_insert']      = $this->language->get('button_insert');
		$this->data['button_delete']      = $this->language->get('button_delete');
        
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['success'] = $this->session->has('success') ? $this->session->get('success') : '';
        $this->session->clear('success');
        
        
        // SCRIPTS
        $scripts[] = array('id'=>'categoryList','method'=>'function','script'=>
            "function activate(e) {
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'". Url::createAdminUrl("store/category/activate") ."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $('#img_' + e).attr('src','image/good.png');
                        } else {
                            $('#img_' + e).attr('src','image/minus.png');
                        }
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
                    $.post('". Url::createAdminUrl("store/category/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("store/category/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('li#' + e).remove();
                	$.getJSON('". Url::createAdminUrl("store/category/delete") ."',{
                        id:e
                    });
                }
                return false;
             }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("store/category/grid") ."',function(e){
                $('#gridPreloader').hide();
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
                        
                        $.getJSON('". Url::createAdminUrl("store/category/updateparent") ."',{'parent_id':parent_id,'category_id':ui.item.attr('id')},function(data){
                            if (data.error) {
                                $('#msg').fadeIn().append('<div class=\"message success\"'+ data.msg +'</div>').delay(3600).fadeOut();
                            }
                        });
                        
                        var sorts = {}; 
                        var i = 0;
                        $('ol.items li').each(function(){
                            i++;
                            sorts[i] = $(this).attr('id');
                        }); 
                        
                        $.post('". Url::createAdminUrl("store/category/sortable") ."',sorts,
                        function(data){
                            if (data.error) {
                                $('#msg').fadeIn().append('<div class=\"message success\"'+ data.msg +'</div>').delay(3600).fadeOut();
                            }
                        });
                    }
        		});
            });
            
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("store/category/grid") ."',
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
                            
                            $.getJSON('". Url::createAdminUrl("store/category/updateparent") ."',{'parent_id':parent_id,'category_id':ui.item.attr('id')},function(data){
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
                            
                            $.post('". Url::createAdminUrl("store/category/sortable") ."',sorts,
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
        
        
		$this->template = 'store/category_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));  
	}
    
    public function updateparent() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        
        if (empty($_GET['category_id']) && !isset($_GET['parent_id'])) {
            $data['error'] = 1;
            $data['msg'] = "No se encontr&oacute; la categor&iacute;a que se va a actualizar";
        } 
        $result = $this->db->query("UPDATE ". DB_PREFIX ."category SET parent_id = ". (int)$_GET['parent_id'] ." WHERE category_id = ". (int)$_GET['category_id']);
        if ($result) {
            $data['success'] = 1;
        } else {
            $data['error'] = 1;
            $data['msg'] = "No se pudo actualizar la catego&iacute;a, por favor reporte esta falla a trav&eacute;s del formulario de sugerencias";
        }
        $this->load->auto('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));  
    }
    
	/**
	 * ControllerStoreCategory::grid()
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
	   //TODO: mantener las jerarquias cuando se filtren los items
       //TODO: Certificar todos los filtros
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
        
        $this->data['categories'] = $this->getCategories($this->modelCategory->getAllForList(0,$data),true);
        
		$this->data['text_no_results']    = $this->language->get('text_no_results');
		$this->data['column_name']        = $this->language->get('column_name');
		$this->data['column_sort_order']  = $this->language->get('column_sort_order');
		$this->data['column_action']      = $this->language->get('column_action');
        
        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $this->data['error_warning'] = $this->session->has('success') ? $this->session->get('success') : '';
        $this->session->clear('success');
        $this->data['Url'] = Url;
		$this->template = 'store/category_grid.tpl';
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));  
	}

     public function getCategories($categories,$parent = false) {
	    $output = '';
        if ($categories) {
            $output .= ($parent) ? '<ol class="items">' : '<ol>';
    		foreach ($categories as $result) {
                $output .= '<li id="'. $result['category_id'] .'">';
                $output .= '<div class="item">';
                $output .= '<input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="'. $result['category_id'] .'">';
                $output .= '<b class="name">'. $result['name'] .'</b>';
                
                $_img = ((int)$result['status'] == 1) ? 'good.png' : 'minus.png';
                
                $output .= '<div class="actions">';
                /*
                $output .= '<a title="'. $this->language->get('text_see') .'" href="'. Url::createAdminUrl("store/category/see",array('category_id'=>$result['category_id'])) .'">';
                $output .= '<img src="image/report.png" alt="'. $this->language->get('text_see') .'" />';
                $output .= '</a>';
                */
                $output .= '<a title="'. $this->language->get('text_edit') .'" href="'. Url::createAdminUrl("store/category/update",array('category_id'=>$result['category_id'])) .'">';
                $output .= '<img src="image/edit.png" alt="'. $this->language->get('text_edit') .'" />';
                $output .= '</a>';
                
                $output .= '<a title="'. $this->language->get('text_activate') .'" onclick="activate('. $result['category_id'] .')">';
                $output .= '<img id="img_'. $result['category_id'] .'" src="image/'. $_img .'" alt="'. $this->language->get('text_activate') .'" />';
                $output .= '</a>';
               
                $output .= '<a title="'. $this->language->get('text_delete') .'" onclick="eliminar('. $result['category_id'] .')">';
                $output .= '<img src="image/delete.png" alt="'. $this->language->get('text_delete') .'" />';
                $output .= '</a>';
               /*
                $output .= '<a title="'. $this->language->get('text_copy') .'" onclick="copy('. $result['category_id'] .')">';
                $output .= '<img src="image/copy.png" alt="'. $this->language->get('text_copy') .'" />';
                $output .= '</a>';
               */
                $output .= '</div>';
                
                $output .= '</div>';
                
                // subcategories
    			if ($result['childrens']) {
                    $output .= $this->getCategories($result['childrens']);
    			}
                
                $output .= '</li>';
            }
            $output .= '</ol>';
        }	
        return $output;
	}
	
	/**
	 * ControllerStoreCategory::getForm()
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
       		'href'      => Url::createAdminUrl('store/category'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = Url::createAdminUrl('store/category/insert');
		} else {
			$this->data['action'] = Url::createAdminUrl('store/category/update',array('category_id'=>$this->request->get['category_id']));
		}
		
		$this->data['cancel'] = Url::createAdminUrl('store/category');

		if ($this->request->hasQuery('category_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->modelCategory->getById($this->request->getQuery('category_id'));
    	}
		
		$this->data['languages'] = $this->modelLanguage->getAll();
		$this->data['stores'] = $this->modelStore->getAll();
		$this->data['_stores'] = $this->modelCategory->getStores($this->request->getQuery('category_id'));
        $this->data['layout'] = $this->modelCategory->getProperty($this->request->getQuery('category_id'),'style','view');
        
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
        
		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($category_info)) {
			$this->data['category_description'] = $this->modelCategory->getDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}

        $this->setvar('category_id',$category_info);
        $this->setvar('status',$category_info,1);
        $this->setvar('parent_id',$category_info,0);
        $this->setvar('keyword',$category_info,'');
        $this->setvar('image',$category_info);
        $this->setvar('sort_order',$category_info,0);
        
		$this->data['categories'] = $this->modelCategory->getAll();

		if (!empty($category_info['image']) && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['preview'] = NTImage::resizeAndSave($category_info['image'], 100, 100);
		} else {
			$this->data['preview'] = NTImage::resizeAndSave('no_image.jpg', 100, 100);
		}
        
        $scripts[] = array('id'=>'categoryForm','method'=>'ready','script'=>
            "$('#addsWrapper').hide();
            
            $('#addsPanel').on('click',function(e){
                var products = $('#addsWrapper').find('.row');
                
                if (products.length == 0) {
                    $.getJSON('".Url::createAdminUrl("store/category/products")."',
                        {
                            'category_id':'".$this->request->getQuery('category_id')."'
                        }, function(data) {
                            
                            $('#addsWrapper').html('<div class=\"row\"><label for=\"q\" style=\"float:left\">Filtrar listado de productos:</label><input type=\"text\" value=\"\" name=\"q\" id=\"q\" placeholder=\"Filtrar Productos\" /></div><div class=\"clear\"></div><br /><ul id=\"adds\"></ul>');
                            
                            $.each(data, function(i,item){
                                $('#adds').append('<li><img src=\"' + item.pimage + '\" alt=\"' + item.pname + '\" /><b class=\"' + item.class + '\">' + item.pname + '</b><input type=\"checkbox\" name=\"Products[' + item.product_id + ']\" value=\"' + item.product_id + '\" style=\"display:none\" /></li>');
                                
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
                                    $(this).find('input[type=checkbox]').removeAttr('checked');
                                } else {
                                    b.removeClass('add').addClass('added');
                                    $(this).find('input[type=checkbox]').attr('checked','checked');
                                }
                            });
                    });
                }
            });
                
            $('#addsPanel').on('click',function(){ $('#addsWrapper').slideToggle() });");
            
        foreach ($this->data['languages'] as $language) {
            $scripts[] = array('id'=>'categoryLanguage'.$language["language_id"],'method'=>'ready','script'=>
                "CKEDITOR.replace('description". $language["language_id"] ."', {
                	filebrowserBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserImageBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserFlashBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserImageUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserFlashUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                    height:600
                });
                $('#description_". $language["language_id"] ."_name').change(function(e){
                    $.getJSON('". Url::createAdminUrl('common/home/slug') ."',
                    { 
                        slug : $(this).val(),
                        query : 'path=". $this->request->getQuery('category_id') ."',
                    },
                    function(data){
                        $('#description_". $language["language_id"] ."_keyword').val(data.slug);
                    });
                });");
        }
        
        $scripts[] = array('id'=>'categoryFunctions','method'=>'function','script'=>
            "function image_delete(field, preview) {
                $('#' + field).val('');
                $('#' + preview).attr('src','". HTTP_IMAGE ."cache/no_image-100x100.jpg');
            }
            
            function image_upload(field, preview) {
                var height = $(window).height() * 0.8;
                var width = $(window).width() * 0.8;
                
            	$('#dialog').remove();
            	$('.box').prepend('<div id=\"dialog\" style=\"padding: 3px 0px 0px 0px;z-index:10000;\"><iframe src=\"". Url::createAdminUrl("common/filemanager") ."&field=' + encodeURIComponent(field) + '\" style=\"padding:0; margin: 0; display: block; width: 100%; height: 100%;z-index:10000\" frameborder=\"no\" scrolling=\"auto\"></iframe></div>');
                
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
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        
        $javascripts[] = "js/vendor/ckeditor/ckeditor.js";
        
        $this->javascripts = array_merge($javascripts,$this->javascripts);
        
        /* feedback form values */
        $this->data['domain'] = HTTP_HOME;
        $this->data['account_id'] = C_CODE;
        $this->data['local_ip'] = $_SERVER['SERVER_ADDR'];
        $this->data['remote_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->data['server'] = serialize($_SERVER); //TODO: encriptar todos estos datos con una llave que solo yo poseo
        
        
		$this->template = 'store/category_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	/**
	 * ControllerStoreCategory::validateForm()
	 * 
     * @see User
     * @see Request
     * @see Language
	 * @return bool
	 */
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'store/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        //TODO: agregar funciones de validación propias

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if (empty($value['name'])) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
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
	 * ControllerStoreCategory::validateDelete()
	 * 
     * @see User
     * @see Language
	 * @return bool
	 */
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'store/category')) {
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
     * ControllerStoreCategory::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('store/category');
        $status = $this->modelCategory->getById($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelCategory->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelCategory->desactivate($_GET['id']);
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
        $this->load->auto('store/category');
        $result = $this->modelCategory->sortCategory($_POST);
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
        
        if ($this->request->hasQuery('category_id')) {
            $rows = $this->modelProduct->getAllByCategoryId($this->request->getQuery('category_id'));
            $products_by_category = array();
            foreach ($rows as $row) {
                $products_by_category[] = $row['product_id'];
            }
        }
        $cache = $this->cache->get("products.for.category.form");
        if ($cache) {
            $products = $cache;
        } else {
            $products = $this->modelProduct->getAll();
            $this->cache->set("products.for.category.form",$products);
        }
        
        $output = array();
        
        foreach ($products as $product) {
            if (!empty($products_by_category) && in_array($product['product_id'],$products_by_category)) {
                $output[] = array(
                    'product_id'=>$product['product_id'],
                    'pimage'    =>NTImage::resizeAndSave($product['image'],50,50),
                    'pname'     =>$product['name'],
                    'class'     =>'added',
                    'value'     =>1
                );
            } else {
                $output[] = array(
                    'product_id'=>$product['product_id'],
                    'pimage'    =>NTImage::resizeAndSave($product['image'],50,50),
                    'pname'     =>$product['name'],
                    'class'     =>'add',
                    'value'     =>0
                );
            }
        }
        $this->load->auto('json');
        $this->response->setOutput(Json::encode($output), $this->config->get('config_compression'));
     }
}