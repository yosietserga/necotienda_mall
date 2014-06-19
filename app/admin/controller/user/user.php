<?php  
class ControllerUserUser extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->document->title = $this->language->get('heading_title');
        $this->getList();
  	}
   
  	public function insert() {
    	$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$user_id = $this->modelUser->add($this->request->post);
			
			$this->session->set('success',$this->language->get('text_success'));
			
			$url = '';

			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('user/user/update',array('user_id'=>$user_id)) . $url); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('user/user/insert') . $url); 
            } else {
                $this->redirect(Url::createAdminUrl('user/user') . $url); 
            }
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelUser->update($this->request->get['user_id'], $this->request->post);
			
			$this->session->set('success',$this->language->get('text_success'));
			
			$url = '';

			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
			
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('user/user/update',array('user_id'=>$this->request->get['user_id'])) . $url); 
            } elseif ($_POST['to'] == "saveAndNew") {
                $this->redirect(Url::createAdminUrl('user/user/insert') . $url); 
            } else {
                $this->redirect(Url::createAdminUrl('user/user') . $url); 
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
        $this->load->auto('user/user');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelUser->delete($id);
            }
		} else {
            $this->modelUser->delete($_GET['id']);
		}
     }
    
  	private function getList() {
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('user/user') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = Url::createAdminUrl('user/user/insert') . $url;		
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_insert'] = $this->language->get('button_insert');
        
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
        $scripts[] = array('id'=>'userList','method'=>'function','script'=>
            "function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("user/user/activate")."&id=' + e,
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
                	$.getJSON('". Url::createAdminUrl("user/user/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
            function deleteAll() {
                if (confirm('\\xbfDesea eliminar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("user/user/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("user/user/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("user/user/grid") ."',function(e){
                $('#gridPreloader').hide();
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("user/user/grid") ."',
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
        
		$this->template = 'user/user_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
	
  	public function grid() {
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$filter_email = isset($this->request->get['filter_email']) ? $this->request->get['filter_email'] : null;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'username';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; } 
		if (isset($this->request->get['filter_email'])) { $url .= '&filter_email=' . $this->request->get['filter_email']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; } 

    	$this->data['users'] = array();

		$data = array(
			'filter_name'  => $filter_name,
			'filter_email'  => $filter_email,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($post - 1) * $limit,
			'limit' => $limit
		);
		
		$user_total = $this->modelUser->getAllTotal($data);
        if ($user_total) {
    		$results = $this->modelUser->getAll($data);
    		foreach ($results as $result) {
    			$action = array();
    			
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
                            'href'  =>Url::createAdminUrl('user/user/update') . '&user_id=' . $result['user_id'] . $url,
                            'img'   => 'edit.png'
                    ),
                    'delete'    => array(
                            'action'  => 'delete',
                            'text'  => $this->language->get('text_delete'),
                            'href'  =>'',
                            'img'   => 'delete.png'
                    )
                );
    				
          		$this->data['users'][] = array(
    				'user_id'    => $result['user_id'],
    				'username'   => $result['username'],
    				'customer_group' => $result['name'],
    				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
    				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
    				'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
    				'action'     => $action
    			);
    		}
        }
        
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		$this->data['sort_username'] = Url::createAdminUrl('user/user') . '&sort=username' . $url;
		$this->data['sort_status'] = Url::createAdminUrl('user/user') . '&sort=status' . $url;
		$this->data['sort_date_added'] = Url::createAdminUrl('user/user') . '&sort=date_added' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->ajax = true;
		$pagination->ajaxTarget = "gridWrapper";
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('user/user/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'user/user_grid.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
	
	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

 		$this->data['error_warning'] =  (isset($this->error['warning'])) ? $this->error['warning'] : '';
 		$this->data['error_username'] =  (isset($this->error['username'])) ? $this->error['username'] : '';
 		$this->data['error_password'] =  (isset($this->error['password'])) ? $this->error['password'] : '';
 		$this->data['error_confirm'] =  (isset($this->error['confirm'])) ? $this->error['confirm'] : '';
 		$this->data['error_firstname'] =  (isset($this->error['firstname'])) ? $this->error['firstname'] : '';
 		$this->data['error_email'] =  (isset($this->error['email'])) ? $this->error['email'] : '';
 		$this->data['error_lastname'] =  (isset($this->error['lastname'])) ? $this->error['lastname'] : '';
		
		$url = '';
			
		if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
		if (isset($this->request->get['sort'])) $url .= '&sort=' . $this->request->get['sort'];
		if (isset($this->request->get['order'])) $url .= '&order=' . $this->request->get['order'];
		
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('user/user') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = Url::createAdminUrl('user/user/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('user/user/update') . '&user_id=' . $this->request->get['user_id'] . $url;
		}
		  
    	$this->data['cancel'] = Url::createAdminUrl('user/user') . $url;

    	if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->modelUser->getById($this->request->get['user_id']);
    	}
        
        $this->setvar('username',$user_info,'');
        $this->setvar('firstname',$user_info,'');
        $this->setvar('lastname',$user_info,'');
        $this->setvar('email',$user_info,'');
        $this->setvar('user_group_id',$user_info,'');
        $this->setvar('status',$user_info,0);
        
    	$this->data['user_groups'] = $this->modelUsergroup->getAll();
 
		$this->template = 'user/user_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));	
  	}
  	
    private function validEmail($email)
        {
           $isValid = true;
           $atIndex = strrpos($email, "@");
           if (is_bool($atIndex) && !$atIndex)
           {
              $isValid = false;
           }
           else
           {
              $domain = substr($email, $atIndex+1);
              $local = substr($email, 0, $atIndex);
              $localLen = strlen($local);
              $domainLen = strlen($domain);
              if ($localLen < 1 || $localLen> 64)
              {
                 // local part length exceeded
                 $isValid = false;
              }
              else if ($domainLen < 1 || $domainLen> 255)
              {
                 // domain part length exceeded
                 $isValid = false;
              }
              else if ($local[0] == '.' || $local[$localLen-1] == '.')
              {
                 // local part starts or ends with '.'
                 $isValid = false;
              }
              else if (preg_match('/\\.\\./', $local))
              {
                 // local part has two consecutive dots
                 $isValid = false;
              }
              else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
              {
                 // character not valid in domain part
                 $isValid = false;
              }
              else if (preg_match('/\\.\\./', $domain))
              {
                 // domain part has two consecutive dots
                 $isValid = false;
              }
              else if
        (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                         str_replace("\\\\","",$local)))
              {
                 // character not valid in local part unless 
                 // local part is quoted
                 if (!preg_match('/^"(\\\\"|[^"])+"$/',
                     str_replace("\\\\","",$local)))
                 {
                    $isValid = false;
                 }
              }
              if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
              {
                 // domain not found in DNS
                 $isValid = false;
              }
           }
           return $isValid;
        }
    
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((strlen(utf8_decode($this->request->post['username'])) < 3) || (strlen(utf8_decode($this->request->post['username']))> 20)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}

    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname']))> 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}
        
        if (!$this->validEmail($this->request->post['email'])) {
		      $this->error['email'] = $this->language->get('error_email');
		}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname']))> 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if (($this->request->post['password']) || (!isset($this->request->get['user_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password']))> 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() { 
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	} 
	  	  
		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}
		 
		if (!$this->error) {
	  		return true;
		} else { 
	  		return false;
		}
  	}
    
    /**
     * ControllerCatalogCategory::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('user/user');
        $status = $this->modelUser->getById($_GET['id']);
        if ($status) {
            if ($status['status'] == 0) {
                $this->modelUser->activate($_GET['id']);
                echo 1;
            } else {
                $this->modelUser->desactivate($_GET['id']);
                echo -1;
            }
            
        } else {
            echo 0;
        }
     }
}