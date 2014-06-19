<?php
/**
 * ControllerContentPost
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerContentPost extends Controller { 
	private $error = array();

	/**
	 * ControllerContentPost::index()
     * 
	 * @see Load
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
	 * ControllerContentPost::insert()
	 * 
	 * @see Load
	 * @see Document
	 * @see Model
	 * @see Request
	 * @see Session
	 * @see Redirect
	 * @see getForm
	 * @return void
	 */
	public function insert() {
		$this->document->title = $this->language->get('heading_title');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            
            foreach ($this->request->post['post_description'] as $language_id => $description) {
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
                $this->request->post['post_description'][$language_id]['description'] = htmlentities($dom->saveHTML());
            }
              
            if (empty($this->request->post['date_publish_end'])) {
                $this->request->post['date_publish_end'] = '0000-00-00 00:00:00';
            } else {
                $dpe = explode("/",$this->request->post['date_publish_end']);
                $this->request->post['date_publish_end'] = $dpe[2] ."-". $dpe[1] ."-". $dpe[0] .' 00:00:00';
            }
            
            $dps = explode("/",$this->request->post['date_publish_start']);
            $this->request->post['date_publish_start'] = $dps[2] ."-". $dps[1] ."-". $dps[0] .' 00:00:00';
            
			$post_id = $this->modelPost->add($this->request->post);
            $this->modelPost->setProperty($post_id,'customer_groups','customer_groups', $this->request->getPost('customer_groups'));
            $this->modelPost->setProperty($post_id,'style','view', $this->request->getPost('view'));
			
			$this->session->set('success',$this->language->get('text_success'));

            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('content/post/update',array('post_id'=>$post_id))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('content/post/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('content/post')); 
            }
		}

		$this->getForm();
	}

	/**
	 * ControllerContentPost::update()
	 * 
	 * @see Load
	 * @see Document
	 * @see Model
	 * @see Request
	 * @see Session
	 * @see Redirect
	 * @see getForm
	 * @return void
	 */
	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
            
            foreach ($this->request->post['post_description'] as $language_id => $description) {
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
                $this->request->post['post_description'][$language_id]['description'] = htmlentities($dom->saveHTML());
            }
              
            if (empty($this->request->post['date_publish_end'])) {
                $this->request->post['date_publish_end'] = '0000-00-00 00:00:00';
            } else {
                $dpe = explode("/",$this->request->post['date_publish_end']);
                $this->request->post['date_publish_end'] = $dpe[2] ."-". $dpe[1] ."-". $dpe[0] .' 00:00:00';
            }
            
            $dps = explode("/",$this->request->post['date_publish_start']);
            $this->request->post['date_publish_start'] = $dps[2] ."-". $dps[1] ."-". $dps[0] .' 00:00:00';
            
			$post_id = $this->modelPost->update($this->request->get['post_id'], $this->request->post);
            $this->modelPost->setProperty($this->request->getQuery('post_id'),'customer_groups','customer_groups', $this->request->getPost('customer_groups'));
            $this->modelPost->setProperty($this->request->getQuery('post_id'),'style','view', $this->request->getPost('view'));
			
			$this->session->set('success',$this->language->get('text_success'));

            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('content/post/update',array('post_id'=>$this->request->getQuery('post_id')))); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('content/post/insert')); 
            } else {
                $this->redirect(Url::createAdminUrl('content/post')); 
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
        $this->load->auto('content/post');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelPost->delete($id);
            }
		} else {
            $this->modelPost->delete($_GET['id']);
		}
     }
    
  	/**
  	 * ControllerMarketingNewsletter::copy()
     * duplicar un objeto
  	 * @return boolean
  	 */
  	public function copy() {
        $this->load->auto('content/post');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelPost->copy($id);
            }
		} else {
            $this->modelPost->copy($_GET['id']);
		}
        echo 1;
  	}
      
	/**
	 * ControllerContentPost::getById()
	 * 
	 * @see Load
	 * @see Document
	 * @see Model
	 * @see Request
	 * @see Session
	 * @see Redirect
	 * @see Language
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
       		'href'      => Url::createAdminUrl('content/post') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = Url::createAdminUrl('content/post/insert') . $url;

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
        $scripts[] = array('id'=>'postList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("content/post/activate")."&id=' + e,
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
                $.getJSON('".Url::createAdminUrl("content/post/copy")."&id=' + e, function(data) {
                    $('#gridWrapper').load('". Url::createAdminUrl("content/post/grid") ."',function(response){
                        $('#gridPreloader').hide();
                        $('#gridWrapper').show();
                    });
                });
            }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("content/post/delete") ."',{
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
                $.post('". Url::createAdminUrl("content/post/copy") ."',$('#form').serialize(),function(){
                    $('#gridWrapper').load('". Url::createAdminUrl("content/post/grid") ."',function(){
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
                    $.post('". Url::createAdminUrl("content/post/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("content/post/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("content/post/grid") ."',function(e){
                $('#gridPreloader').hide();
                $('#list tbody').sortable({
                    opacity: 0.6, 
                    cursor: 'move',
                    handle: '.move',
                    update: function() {
                        $.ajax({
                            'type':'post',
                            'dateType':'json',
                            'url':'". Url::createAdminUrl("content/post/sortable") ."',
                            'data': $(this).sortable('serialize'),
                            'success': function(data) {
                                if (data > 0) {
                                    var msj = '<div class=\"message success\">Se han ordenado los objetos correctamente</div>';
                                } else {
                                    var msj = '<div class=\"message warning\">Hubo un error al intentar ordenar los objetos, por favor intente m&aacute;s tarde</div>';
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
                url:'". Url::createAdminUrl("content/post/grid") ."',
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
        
		$this->template = 'content/post_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	/**
	 * ControllerContentPost::grid()
	 * 
	 * @see Load
	 * @see Document
	 * @see Model
	 * @see Request
	 * @see Session
	 * @see Redirect
	 * @see Language
	 * @see Response
	 * @return void
	 */
	public function grid() {
		$filter_title = isset($this->request->get['filter_title']) ? $this->request->get['filter_title'] : null;
		$filter_product = isset($this->request->get['filter_parent']) ? $this->request->get['filter_parent'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'title';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_title'])) { $url .= '&filter_title=' . $this->request->get['filter_title']; } 
		if (isset($this->request->get['filter_parent'])) { $url .= '&filter_parent=' . $this->request->get['filter_parent']; } 
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 

		$this->data['posts'] = array();

		$data = array(
			'filter_title'  => $filter_title,
			'filter_product'  => $filter_product,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$post_total = $this->modelPost->getTotalPosts();
	
		$results = $this->modelPost->getPosts($data);
 
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
                        'href'  =>Url::createAdminUrl('content/post/update') . '&post_id=' . $result['post_id'] . $url,
                        'img'   => 'edit.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
				
			$this->data['posts'][] = array(
				'post_id'    => $result['post_id'],
				'title'      => $result['title'],
				'publish'    => ($result['publish']) ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'date_publish_start'=> date('d-m-Y h:i A',strtotime($result['date_publish_start'])),
				'date_publish_end'=> date('d-m-Y h:i A',strtotime($result['date_publish_end'])),
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['post_id'], $this->request->post['selected']),
				'action'     => $action
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
		
		$this->data['sort_title'] = Url::createAdminUrl('content/post/grid') . '&sort=title' . $url;
		$this->data['sort_publish'] = Url::createAdminUrl('content/post/grid') . '&sort=publish' . $url;
		$this->data['sort_date_publish_start'] = Url::createAdminUrl('content/post/grid') . '&sort=date_publish_start' . $url;
		$this->data['sort_date_publish_end'] = Url::createAdminUrl('content/post/grid') . '&sort=date_publish_end' . $url;
		$this->data['sort_sort_order'] = Url::createAdminUrl('content/post/grid') . '&sort=pa.sort_order' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $post_total;
		$pagination->page = $page;
		$pagination->ajax = 'true';
		$pagination->ajax = 'gridWrapper';
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('content/post/grid') . $url . '&post={post}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'content/post_grid.tpl';
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	/**
	 * ControllerContentPost::getForm()
	 * 
	 * @see Load
	 * @see Document
	 * @see Model
	 * @see Request
	 * @see Session
	 * @see Redirect
	 * @see Language
	 * @see Response
	 * @see Pagination
	 * @return void
	 */
	private function getForm() {
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
       		'href'      => Url::createAdminUrl('content/post') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['post_id'])) {
			$this->data['action'] = Url::createAdminUrl('content/post/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('content/post/update') . '&post_id=' . $this->request->get['post_id'] . $url;
		}
		
		$this->data['cancel'] = Url::createAdminUrl('content/post') . $url;

		if ($this->request->hasQuery('post_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$post_info = $this->modelPost->getPost($this->request->get['post_id']);
		}
        
        $this->setvar('post_id',$post_info,'');
        $this->setvar('parent_id',$post_info,'');
        
		$this->data['languages'] = $this->modelLanguage->getAll();
		$this->data['stores'] = $this->modelStore->getAll();
		$this->data['_stores'] = $this->modelPost->getStores($this->request->getQuery('post_id'));
		$this->data['customerGroups'] = $this->modelCustomergroup->getAll();
		$this->data['customer_groups'] = $this->modelPost->getProperty($this->request->getQuery('post_id'),'customer_groups','customer_groups');
        $this->data['layout'] = $this->modelPost->getProperty($this->request->getQuery('post_id'),'style','view');
        
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
        
		if (isset($this->request->post['post_description'])) {
			$this->data['post_description'] = $this->request->post['post_description'];
		} elseif (isset($this->request->get['post_id'])) {
			$this->data['post_description'] = $this->modelPost->getDescriptions($this->request->get['post_id']);
		} else {
			$this->data['post_description'] = array();
		}
        
		if (isset($this->request->post['date_publish_start'])) {
			$this->data['date_publish_start'] = date('d-m-Y',strtotime($this->request->post['date_publish_start']));
		} elseif (isset($post_info['date_publish_start']) && $post_info['date_publish_start'] != '0000-00-00 00:00:00') {
			$this->data['date_publish_start'] = date('d-m-Y',strtotime($post_info['date_publish_start']));
		} else {
			$this->data['date_publish_start'] = date('d-m-Y');
		}

		if (isset($this->request->post['date_publish_end'])) {
			$this->data['date_publish_end'] = date('d-m-Y',strtotime($this->request->post['date_publish_end']));
		} elseif (isset($post_info['date_publish_end']) && $post_info['date_publish_end'] != '0000-00-00 00:00:00') {
			$this->data['date_publish_end'] = date('d-m-Y',strtotime($post_info['date_publish_end']));
		} else {
			$this->data['date_publish_end'] = '';
		}

        $scripts[] = array('id'=>'form','method'=>'ready','script'=>
            "$('#q').on('change',function(e){
                var that = this;
                var valor = $(that).val().toLowerCase();
                if (valor.length <= 0) {
                    $('#storesWrapper li').show();
                } else {
                    $('#storesWrapper li b').each(function(){
                        if ($(this).text().toLowerCase().indexOf( valor ) != -1) {
                            $(this).closest('li').show();
                        } else {
                            $(this).closest('li').hide();
                        }
                    });
                }
            });
            $('#qCustomerGroups').on('change',function(e){
                var that = this;
                var valor = $(that).val().toLowerCase();
                if (valor.length <= 0) {
                    $('#customerGroupsWrapper li').show();
                } else {
                    $('#customerGroupsWrapper li b').each(function(){
                        if ($(this).text().toLowerCase().indexOf( valor ) != -1) {
                            $(this).closest('li').show();
                        } else {
                            $(this).closest('li').hide();
                        }
                    });
                }
            });");
            
        foreach ($this->data['languages'] as $language) {
            $scripts[] = array('id'=>'postLanguage'.$language["language_id"],'method'=>'ready','script'=>
                "var editor". $language["language_id"] ." = CKEDITOR.replace('description". $language["language_id"] ."', {
                	filebrowserBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserImageBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserFlashBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserImageUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserFlashUploadUrl: '". Url::createAdminUrl("common/filemanager") ."'
                });
                $('#description_". $language["language_id"] ."_title').change(function(e){
                    $.getJSON('". Url::createAdminUrl('common/home/slug') ."',
                    { 
                        slug : $(this).val(),
                        query : 'post_id=". $this->request->getQuery('post_id') ."',
                    },
                    function(data){
                        $('#description_". $language["language_id"] ."_keyword').val(data.slug);
                    });
                });");
        }
        
        $this->scripts = array_merge($this->scripts,$scripts);
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        
        $javascripts[] = "js/vendor/ckeditor/ckeditor.js";
        
        $this->javascripts = array_merge($javascripts,$this->javascripts);
        
		$this->template = 'content/post_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	/**
	 * ControllerContentPost::validateForm()
	 * 
	 * @see User
	 * @see Request
	 * @see Language
	 * @return bool
	 */
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'content/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['post_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title']))> 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
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
	 * ControllerContentPost::validateDelete()
	 * 
	 * @see User
	 * @see Request
	 * @see Language
	 * @return bool
	 */
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'content/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		
		foreach ($this->request->post['selected'] as $post_id) {
			if ($this->config->get('config_account_id') == $post_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $post_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
    
    /**
     * ControllerContentPost::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('content/post');
        $status = $this->modelPost->getPost($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelPost->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelPost->desactivate($_GET['id']);
                echo -1;
            }
            
        } else {
            echo 0;
        }
     }
    
    /**
     * ControllerContentPost::sortable()
     * ordenar el listado actualizando la posición de cada objeto
     * @return boolean
     * */
     public function sortable() {
        if (!isset($_POST['tr'])) return false;
        $this->load->auto('content/post');
        $result = $this->modelPost->sortPost($_POST['tr']);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
}