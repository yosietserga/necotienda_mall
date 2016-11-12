<?php    
/**
 * ControllerSaleCustomer
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerSaleCustomer extends Controller { 
	private $error = array();
  
  	/**
  	 * ControllerSaleCustomer::index()
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
  	 * ControllerSaleCustomer::insert()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Redirect
     * @see getForm
  	 * @return void 
  	 */
  	public function insert() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->modelCustomer->add($this->request->post);
			
			$this->session->set('success',$this->language->get('text_success'));
		  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			//TODO: agregar los redirect condicionales
			$this->redirect(Url::createAdminUrl('sale/customer') . $url);
		}
    	
    	$this->getForm();
  	} 
   
  	/**
  	 * ControllerSaleCustomer::update()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Redirect
     * @see getForm
  	 * @return void 
  	 */
  	public function update() {
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->modelCustomer->editCustomer($this->request->get['customer_id'], $this->request->post);
	  		
			$this->session->set('success',$this->language->get('text_success'));
	  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect(Url::createAdminUrl('sale/customer') . $url);
		}
    
    	$this->getForm();
  	}   

    /**
     * ControllerSaleCustomer::delete()
     * elimina un objeto
     * @return boolean
     * */
     public function delete() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->delete($id);
            }
		} else {
            $this->modelCustomer->delete($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::setPublish()
     * @return boolean
     * */
     public function setPublish() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->setPublish($id);
            }
		} else {
            $this->modelCustomer->setPublish($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::unsetPublish()
     * @return boolean
     * */
     public function unsetPublish() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->unsetPublish($id);
            }
		} else {
            $this->modelCustomer->unsetPublish($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::setActive()
     * @return boolean
     * */
     public function setActive() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->activate($id);
            }
		} else {
            $this->modelCustomer->activate($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::unsetActive()
     * @return boolean
     * */
     public function unsetActive() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->desactivate($id);
            }
		} else {
            $this->modelCustomer->desactivate($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::setApproved()
     * @return boolean
     * */
     public function setApproved() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->approve($id);
            }
		} else {
            $this->modelCustomer->approve($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::unsetApproved()
     * @return boolean
     * */
     public function unsetApproved() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->desapprove($id);
            }
		} else {
            $this->modelCustomer->desapprove($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::setBuy()
     * @return boolean
     * */
     public function setBuy() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->setBuy($id);
            }
		} else {
            $this->modelCustomer->setBuy($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::unsetBuy()
     * @return boolean
     * */
     public function unsetBuy() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->unsetBuy($id);
            }
		} else {
            $this->modelCustomer->unsetBuy($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::setAsk()
     * @return boolean
     * */
     public function setAsk() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->setAsk($id);
            }
		} else {
            $this->modelCustomer->setAsk($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::unsetAsk()
     * @return boolean
     * */
     public function unsetAsk() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->unsetAsk($id);
            }
		} else {
            $this->modelCustomer->unsetAsk($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::setBanned()
     * @return boolean
     * */
     public function setBanned() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->setBanned($id);
            }
		} else {
            $this->modelCustomer->setBanned($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::unsetBanned()
     * @return boolean
     * */
     public function unsetBanned() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->unsetBanned($id);
            }
		} else {
            $this->modelCustomer->unsetBanned($_GET['id']);
		}
     }
    
    /**
     * ControllerSaleCustomer::publish()
     * elimina un objeto
     * @return boolean
     * */
     public function publish() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->publish($id);
            }
		} else {
            $this->modelCustomer->publish($_GET['id']);
            $model = $this->modelCustomer->getCustomer($_GET['id']);
            echo $model['can_publish'];
		}
     }
    
    /**
     * ControllerSaleCustomer::buy()
     * elimina un objeto
     * @return boolean
     * */
     public function buy() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->buy($id);
            }
		} else {
            $this->modelCustomer->buy($_GET['id']);
            $model = $this->modelCustomer->getCustomer($_GET['id']);
            echo $model['can_buy'];
		}
     }
    
    /**
     * ControllerSaleCustomer::ask()
     * elimina un objeto
     * @return boolean
     * */
     public function ask() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->ask($id);
            }
		} else {
            $this->modelCustomer->ask($_GET['id']);
            $model = $this->modelCustomer->getCustomer($_GET['id']);
            echo $model['can_ask'];
		}
     }
    
    /**
     * ControllerSaleCustomer::banned()
     * elimina un objeto
     * @return boolean
     * */
     public function banned() {
        $this->load->auto('sale/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelCustomer->banned($id);
            }
		} else {
            $this->modelCustomer->banned($_GET['id']);
            $model = $this->modelCustomer->getCustomer($_GET['id']);
            echo $model['banned'];
		}
     }
    
    
  	/**
  	 * ControllerSaleCustomer::getById()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Response
     * @see Request     
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
       		'href'      => Url::createAdminUrl('sale/customer') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = Url::createAdminUrl('sale/customer/insert') . $url;
	
		if ($this->session->has('error')) {
			$this->data['error_warning'] = $this->session->get('error');
			
			$this->session->clear('error');
		} elseif (isset($this->error['warning'])) {
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
        $scripts[] = array('id'=>'customerList','method'=>'function','script'=>
            "function aprobar(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("sale/customer/approve")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_approve_\" + e).attr('src','image/customer_unlocked.png');
                        } else {
                            $(\"#img_approve_\" + e).attr('src','image/customer_locked.png');
                        }
                   }
            	});
             }
             function setPublish(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("sale/customer/publish")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_pub_\" + e).attr('src','image/publish_on.png');
                        } else {
                            $(\"#img_pub_\" + e).attr('src','image/publish_off.png');
                        }
                   }
            	});
             }
             function setBuy(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("sale/customer/buy")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_buy_\" + e).attr('src','image/buy_on.png');
                        } else {
                            $(\"#img_buy_\" + e).attr('src','image/buy_off.png');
                        }
                   }
            	});
             }
             function setAsk(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("sale/customer/ask")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_ask_\" + e).attr('src','image/ask_on.png');
                        } else {
                            $(\"#img_ask_\" + e).attr('src','image/ask_off.png');
                        }
                   }
            	});
             }
             function setBanned(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("sale/customer/banned")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_ban_\" + e).attr('src','image/banned_on.png');
                        } else {
                            $(\"#img_ban_\" + e).attr('src','image/banned_off.png');
                        }
                   }
            	});
             }
             function activate(e) {    
            	$.ajax({
            	   'type':'get',
                   'dataType':'json',
                   'url':'".Url::createAdminUrl("sale/customer/activate")."&id=' + e,
                   'success': function(data) {
                        if (data > 0) {
                            $(\"#img_activate_\" + e).attr('src','image/good.png');
                        } else {
                            $(\"#img_activate_\" + e).attr('src','image/minus.png');
                        }
                   }
            	});
             }
            function eliminar(e) {
                if (confirm('\\xbfDesea eliminar este objeto?')) {
                    $('#tr_' + e).remove();
                	$.getJSON('". Url::createAdminUrl("sale/customer/delete") ."',{
                        id:e
                    });
                }
                return false;
             }
             
            function setPublishAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/setpublish") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function unsetPublishAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/unsetpublish") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function setBuyAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/setbuy") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function unsetBuyAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/unsetbuy") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function setAskAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/setask") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function unsetAskAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/unsetask") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function setBannedAll() {
                if (confirm('\\xbfDesea vetar todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/setbanned") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }
            function unsetBannedAll() {
                if (confirm('\\xbfDesea quitar veto a todos los objetos seleccionados?')) {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/unsetbanned") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }
            function approveAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/setapproved") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function desapproveAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/unsetapproved") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function activeAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/setActive") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                
                return false;
            }
            function desactiveAll() {
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                    $.post('". Url::createAdminUrl("sale/customer/unsetActive") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
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
                    $.post('". Url::createAdminUrl("sale/customer/delete") ."',$('#form').serialize(),function(){
                        $('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(){
                            $('#gridWrapper').show();
                            $('#gridPreloader').hide();
                        });
                    });
                }
                return false;
            }");
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("sale/customer/grid") ."',function(e){
                $('#gridPreloader').hide();
                $('#list tbody').sortable({
                    opacity: 0.6, 
                    cursor: 'move',
                    handle: '.move',
                    update: function() {
                        $.ajax({
                            'type':'post',
                            'dateType':'json',
                            'url':'". Url::createAdminUrl("sale/customer/sortable") ."',
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
                url:'". Url::createAdminUrl("sale/customer/grid") ."',
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
        
		$this->template = 'sale/customer_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
  	/**
  	 * ControllerSaleCustomer::grid()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Response
     * @see Request     
  	 * @return void 
  	 */
  	public function grid() {
		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
		$filter_email = isset($this->request->get['filter_email']) ? $this->request->get['filter_email'] : null;
		$filter_customer_group_id = isset($this->request->get['filter_customer_group_id']) ? $this->request->get['filter_customer_group_id'] : null;
		$filter_status = isset($this->request->get['filter_status']) ? $this->request->get['filter_status'] : null;
		$filter_approved = isset($this->request->get['filter_approved']) ? $this->request->get['filter_approved'] : null;
		$filter_date_start = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : null;
		$filter_date_end = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : null;
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$limit = !empty($this->request->get['limit']) ? $this->request->get['limit'] : $this->config->get('config_admin_limit');
		
		$url = '';
			
		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; } 
		if (isset($this->request->get['filter_email'])) { $url .= '&filter_email=' . $this->request->get['filter_email']; } 
		if (isset($this->request->get['filter_customer_group_id'])) {$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];} 
		if (isset($this->request->get['filter_status'])) { $url .= '&filter_status=' . $this->request->get['filter_status']; }
		if (isset($this->request->get['filter_approved'])) { $url .= '&filter_approved=' . $this->request->get['filter_approved']; }
		if (isset($this->request->get['filter_date_start'])) { $url .= '&filter_date_start=' . $this->request->get['filter_date_start']; }
		if (isset($this->request->get['filter_date_end'])) { $url .= '&filter_date_end=' . $this->request->get['filter_date_end']; }
		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }
		if (!empty($this->request->get['limit'])) { $url .= '&limit=' . $this->request->get['limit']; }

		$this->data['customers'] = array();

		$data = array(
			'filter_name'              => $filter_name, 
			'filter_email'             => $filter_email, 
			'filter_customer_group_id' => $filter_customer_group_id, 
			'filter_status'            => $filter_status, 
			'filter_approved'          => $filter_approved, 
			'filter_date_start'        => $filter_date_start,
			'filter_date_end'          => $filter_date_end,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
		
		$customer_total = $this->modelCustomer->getAllTotal($data);
		$results = $this->modelCustomer->getAll($data);
 
    	foreach ($results as $result) {
			
			$action = array(
                'publish'  => array(
                        'action'  => 'publish',
                        'text'  => $this->language->get('text_publish'),
                        'href'  =>'',
                        'img'   => ($result['can_publish']==1) ? 'publish_on.png' :  'publish_off.png'
                ),
                'buy'  => array(
                        'action'  => 'buy',
                        'text'  => $this->language->get('text_buy'),
                        'href'  =>'',
                        'img'   => ($result['can_buy']==1) ? 'buy_on.png' :  'buy_off.png'
                ),
                'ask'  => array(
                        'action'  => 'ask',
                        'text'  => $this->language->get('text_ask'),
                        'href'  =>'',
                        'img'   => ($result['can_ask']==1) ? 'ask_on.png' :  'ask_off.png'
                ),
                'banned'  => array(
                        'action'  => 'banned',
                        'text'  => $this->language->get('text_banned'),
                        'href'  =>'',
                        'img'   => ($result['banned']==1) ? 'banned_on.png' :  'banned_off.png'
                ),
                'activate'  => array(
                        'action'  => 'activate',
                        'text'  => $this->language->get('text_activate'),
                        'href'  =>'',
                        'img'   => ($result['status']==1) ? 'good.png' :  'minus.png'
                ),
                'approve' => array(
                        'action'  => 'approve',
                        'text'  => $this->language->get('button_approve'),
                        'href'  =>'',
                        'img'   => ($result['approved']==1) ? 'customer_unlocked.png' :  'customer_locked.png'
                ),
                'edit'      => array(
                        'action'  => 'edit',
                        'text'  => $this->language->get('text_edit'),
                        'href'  =>Url::createAdminUrl('sale/customer/update') . '&customer_id=' . $result['cid'] . $url,
                        'img'   => 'edit.png'
                ),
                'delete'    => array(
                        'action'  => 'delete',
                        'text'  => $this->language->get('text_delete'),
                        'href'  =>'',
                        'img'   => 'delete.png'
                )
            );
			$this->data['customers'][] = array(
				'customer_id'    => $result['cid'],
				'name'           => $result['name'],
				'email'          => $result['email'],   
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'telephone'      => $result['telephone'],
				'fax'            => $result['fax'],
				'newsletter'     => ($result['newsletter'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'address'        => $result['address_1'].", ".$result['city'].". ".$result['zone']." - ".$result['country'],
				'fdate_added'    => $result['date_added'],
				'rif'            => $result['rif'],
				'company'        => $result['company'],
				'activation_code'         => $result['activation_code'],
				'birthday'     => $result['birthday'],
				'blog'           => $result['blog'],
				'website'        => $result['website'],
				'profesion'      => $result['profesion'],
				'titulo'         => $result['titulo'],
				'msn'            => $result['msn'],                
				'gmail'          => $result['gmail'],
				'yahoo'          => $result['yahoo'],
				'skype'          => $result['skype'],
				'facebook'       => $result['facebook'],
				'twitter'        => $result['twitter'],
				'complete'       => ($result['complete'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'sex'           => $result['sex'],                
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['customer_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}
        
		$url = '';

		if (isset($this->request->get['filter_name'])) { $url .= '&filter_name=' . $this->request->get['filter_name']; }
		if (isset($this->request->get['filter_email'])) { $url .= '&filter_email=' . $this->request->get['filter_email']; }
		if (isset($this->request->get['filter_customer_group_id'])) { $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id']; }
		if (isset($this->request->get['filter_status'])) { $url .= '&filter_status=' . $this->request->get['filter_status']; }
		if (isset($this->request->get['filter_approved'])) { $url .= '&filter_approved=' . $this->request->get['filter_approved']; }
		if (isset($this->request->get['filter_date_added'])) { $url .= '&filter_date_added=' . $this->request->get['filter_date_added']; }
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) { $url .= '&page=' . $this->request->get['page']; }
		
		$this->data['sort_name'] = Url::createAdminUrl('sale/customer/grid') . '&sort=name' . $url;
		$this->data['sort_email'] = Url::createAdminUrl('sale/customer/grid') . '&sort=c.email' . $url;
		$this->data['sort_customer_group'] = Url::createAdminUrl('sale/customer/grid') . '&sort=customer_group' . $url;
		$this->data['sort_status'] = Url::createAdminUrl('sale/customer/grid') . '&sort=c.status' . $url;
		$this->data['sort_approved'] = Url::createAdminUrl('sale/customer/grid') . '&sort=c.approved' . $url;
		$this->data['sort_date_added'] = Url::createAdminUrl('sale/customer/grid') . '&sort=c.date_added' . $url;
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {$url .= '&filter_name=' . $this->request->get['filter_name'];}
		if (isset($this->request->get['filter_email'])) {$url .= '&filter_email=' . $this->request->get['filter_email'];}
		if (isset($this->request->get['filter_customer_group_id'])) {$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];}
		if (isset($this->request->get['filter_status'])) { $url .= '&filter_status=' . $this->request->get['filter_status']; }
		if (isset($this->request->get['filter_approved'])) { $url .= '&filter_approved=' . $this->request->get['filter_approved']; }
		if (isset($this->request->get['filter_date_added'])) { $url .= '&filter_date_added=' . $this->request->get['filter_date_added']; }
		if (isset($this->request->get['sort'])) { $url .= '&sort=' . $this->request->get['sort']; }
		if (isset($this->request->get['order'])) { $url .= '&order=' . $this->request->get['order']; }

		$pagination = new Pagination();
		$pagination->ajax = true;
		$pagination->ajaxTarget = "gridWrapper";
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = Url::createAdminUrl('sale/customer/grid') . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		
    	$this->data['customer_groups'] = $this->modelCustomergroup->getAll();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/customer_grid.tpl';
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
  	/**
  	 * ControllerSaleCustomer::getForm()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see Session
     * @see Response
     * @see Request     
  	 * @return void 
  	 */
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled']    = $this->language->get('text_enabled');
    	$this->data['text_disabled']   = $this->language->get('text_disabled');
		$this->data['text_select']     = $this->language->get('text_select');
    	$this->data['text_man']        = $this->language->get('text_man');
		$this->data['text_woman']      = $this->language->get('text_woman');
		$this->data['text_sexo']       = $this->language->get('text_sexo');
    	
    	$this->data['entry_rif']       = $this->language->get('entry_rif');
    	$this->data['entry_facebook']  = $this->language->get('entry_facebook');
    	$this->data['entry_twitter']   = $this->language->get('entry_twitter');
    	$this->data['entry_msn']       = $this->language->get('entry_msn');
    	$this->data['entry_yahoo']     = $this->language->get('entry_yahoo');
    	$this->data['entry_gmail']     = $this->language->get('entry_gmail');
    	$this->data['entry_skype']     = $this->language->get('entry_skype');
    	$this->data['entry_profesion'] = $this->language->get('entry_profesion');
		$this->data['entry_titulo']    = $this->language->get('entry_titulo');
    	$this->data['entry_blog']      = $this->language->get('entry_blog');
		$this->data['entry_website']   = $this->language->get('entry_website');
		$this->data['entry_foto']      = $this->language->get('entry_foto');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname']  = $this->language->get('entry_lastname');
    	$this->data['entry_email']     = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax']       = $this->language->get('entry_fax');
    	$this->data['entry_password']  = $this->language->get('entry_password');
    	$this->data['entry_confirm']   = $this->language->get('entry_confirm');
		$this->data['entry_newsletter']= $this->language->get('entry_newsletter');
    	$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status']    = $this->language->get('entry_status');
		$this->data['entry_company']   = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city']      = $this->language->get('entry_city');
		$this->data['entry_postcode']  = $this->language->get('entry_postcode');
		$this->data['entry_zone']      = $this->language->get('entry_zone');
		$this->data['entry_country']   = $this->language->get('entry_country');
		$this->data['entry_default']   = $this->language->get('entry_default');
		$this->data['entry_name']      = $this->language->get('entry_name');
		$this->data['entry_address']   = $this->language->get('entry_address');
		$this->data['entry_sexo']      = $this->language->get('entry_sexo');
		$this->data['entry_city_postcode'] = $this->language->get('entry_city_postcode');
		$this->data['entry_country_zone']  = $this->language->get('entry_country_zone');
    	
    	$this->data['help_rif']        = $this->language->get('help_rif');
    	$this->data['help_facebook']   = $this->language->get('help_facebook');
    	$this->data['help_twitter']    = $this->language->get('help_twitter');
    	$this->data['help_msn']        = $this->language->get('help_msn');
    	$this->data['help_yahoo']      = $this->language->get('help_yahoo');
    	$this->data['help_gmail']      = $this->language->get('help_gmail');
    	$this->data['help_skype']      = $this->language->get('help_skype');
    	$this->data['help_profesion']  = $this->language->get('help_profesion');
		$this->data['help_titulo']     = $this->language->get('help_titulo');
    	$this->data['help_blog']       = $this->language->get('help_blog');
		$this->data['help_website']    = $this->language->get('help_website');
		$this->data['help_foto']       = $this->language->get('help_foto');
    	$this->data['help_firstname']  = $this->language->get('help_firstname');
    	$this->data['help_lastname']   = $this->language->get('help_lastname');
    	$this->data['help_email']      = $this->language->get('help_email');
    	$this->data['help_telephone']  = $this->language->get('help_telephone');
    	$this->data['help_fax']        = $this->language->get('help_fax');
    	$this->data['help_password']   = $this->language->get('help_password');
    	$this->data['help_confirm']    = $this->language->get('help_confirm');
		$this->data['help_newsletter'] = $this->language->get('help_newsletter');
    	$this->data['help_customer_group'] = $this->language->get('help_customer_group');
		$this->data['help_status']     = $this->language->get('help_status');
		$this->data['help_company']    = $this->language->get('help_company');
		$this->data['help_address_1']  = $this->language->get('help_address_1');
		$this->data['help_address_2']  = $this->language->get('help_address_2');
		$this->data['help_city']       = $this->language->get('help_city');
		$this->data['help_postcode']   = $this->language->get('help_postcode');
		$this->data['help_zone']       = $this->language->get('help_zone');
		$this->data['help_country']    = $this->language->get('help_country');
		$this->data['help_default']    = $this->language->get('help_default');
		$this->data['help_name']       = $this->language->get('help_name');
		$this->data['help_address']    = $this->language->get('help_address');
		$this->data['help_sexo']       = $this->language->get('help_sexo');
		$this->data['help_city_postcode'] = $this->language->get('help_city_postcode');
		$this->data['help_country_zone']  = $this->language->get('help_country_zone');
 
		$this->data['button_save']     = $this->language->get('button_save');
    	$this->data['button_cancel']   = $this->language->get('button_cancel');
    	$this->data['button_add']      = $this->language->get('button_add');
    	$this->data['button_remove']   = $this->language->get('button_remove');
		$this->data['button_save_and_new'] = $this->language->get('button_save_and_new');
		$this->data['button_save_and_exit']= $this->language->get('button_save_and_exit');
		$this->data['button_save_and_keep']= $this->language->get('button_save_and_keep');
	
		$this->data['tab_general']     = $this->language->get('tab_general');
		$this->data['tab_address']     = $this->language->get('tab_address');

 		$this->data['error_warning']     = ($this->error['warning']) ? $this->error['warning'] : '';
 		$this->data['error_firstname']   = ($this->error['firstname']) ? $this->error['firstname'] : '';
 		$this->data['error_lastname']    = ($this->error['lastname']) ? $this->error['lastname'] : '';
 		$this->data['error_email']       = ($this->error['email']) ? $this->error['email'] : '';
 		$this->data['error_sexo']        = ($this->error['sexo']) ? $this->error['sexo'] : '';
 		$this->data['error_telephone']   = ($this->error['telephone']) ? $this->error['telephone'] : '';
 		$this->data['error_password']    = ($this->error['password']) ? $this->error['password'] : '';
 		$this->data['error_confirm']     = ($this->error['confirm']) ? $this->error['confirm'] : '';
 		$this->data['error_address_1']   = ($this->error['address_1']) ? $this->error['address_1'] : '';
 		$this->data['error_city']        = ($this->error['city']) ? $this->error['city'] : '';
 		$this->data['error_postcode']    = ($this->error['postcode']) ? $this->error['postcode'] : '';
 		$this->data['error_address_zone']= ($this->error['address_zone']) ? $this->error['address_zone'] : '';
 		$this->data['error_rif']         = ($this->error['rif']) ? $this->error['rif'] : '';
 		$this->data['error_company']     = ($this->error['company']) ? $this->error['company'] : '';
 		$this->data['error_address_country'] = ($this->error['address_country']) ? $this->error['address_country'] : '';

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('sale/customer') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['customer_id'])) {
			$this->data['action'] = Url::createAdminUrl('sale/customer/insert') . $url;
		} else {
			$this->data['action'] = Url::createAdminUrl('sale/customer/update') . '&customer_id=' . $this->request->get['customer_id'] . $url;
		}
		  
    	$this->data['cancel'] = Url::createAdminUrl('sale/customer') . $url;

    	if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$customer_info = $this->modelCustomer->getCustomer($this->request->get['customer_id']);
    	}
        
        $this->setvar('profesion',$customer_info,'');
        $this->setvar('titulo',$customer_info,'');
        $this->setvar('blog',$customer_info,'');
        $this->setvar('website',$customer_info,'');
        $this->setvar('foto',$customer_info,'');
        $this->setvar('facebook',$customer_info,'');
        $this->setvar('twitter',$customer_info,'');
        $this->setvar('yahoo',$customer_info,'');
        $this->setvar('gmail',$customer_info,'');
        $this->setvar('msn',$customer_info,'');
        $this->setvar('skype',$customer_info,'');
        $this->setvar('firstname',$customer_info,'');
        $this->setvar('lastname',$customer_info,'');
        $this->setvar('email',$customer_info,'');
        $this->setvar('telephone',$customer_info,'');
        $this->setvar('fax',$customer_info,'');
        $this->setvar('rif',$customer_info,'');
        $this->setvar('company',$customer_info,'');
        $this->setvar('newsletter',$customer_info,'');
        $this->setvar('sexo',$customer_info,'');
        $this->setvar('status',$customer_info,1);
        $this->setvar('customer_group_id',$customer_info,$this->config->get('config_customer_group_id'));
        
        $this->data['password'] = ($this->request->post['password']) ? $this->request->post['password'] : '';
        $this->data['confirm'] = ($this->request->post['confirm']) ? $this->request->post['confirm'] : '';
		
		$this->data['customer_groups'] = $this->modelCustomergroup->getAll();		
		$this->data['countries'] = $this->modelCountry->getAll();
		foreach ($this->data['countries'] as $country) { 
            $countries .= "<option value=\"". $country["country_id"] ."\">". addslashes($country["name"]) ."</option>";
        }
        
		if (isset($this->request->post['addresses'])) { 
      		$this->data['addresses'] = $this->request->post['addresses'];
		} elseif (isset($this->request->get['customer_id'])) {
			$this->data['addresses'] = $this->modelCustomer->getAddresses($this->request->get['customer_id']);
		} else {
			$this->data['addresses'] = array();
    	}
        
        $this->data['Url'] = new Url;
		
        $scripts[] = array('id'=>'customerScripts','method'=>'ready','script'=>
            " $('#form').ntForm({
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
            
            $('.vtabs_page').hide();
            $('#tab_general').show();");
            
        $scripts[] = array('id'=>'customerFunctions','method'=>'function','script'=>
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
            
            function showTab(a) {
                $('.vtabs_page').hide();
                $($(a).attr('data-target')).show();
                console.log(a);
            }
            
            function addAddress() {
                var address_row = $('.vtabs_page:last-child').index() + 1 * 1;
                var input = {};
                var label = {};
                
                var div = $(document.createElement('div')).addClass('vtabs_page').attr({'id':'tab_address_'+ address_row});
                var h2 = $(document.createElement('h2')).text('Direcci\u00F3n '+ address_row).appendTo(div);
                var table = $(document.createElement('table')).addClass('form').appendTo(div);
                
                tr = $(document.createElement('tr')).appendTo(table);
                td1 = $(document.createElement('td')).appendTo(tr);
                td2 = $(document.createElement('td')).appendTo(tr);
                
                label.country_id = $(document.createElement('label')).text('". str_replace('&iacute;','\u00ED',$this->data['entry_country']) ."').appendTo(td1);
                input.country_id = $(document.createElement('select')).attr({
                    'type':'text',
                    'name':'addresses[' + address_row + '][country_id]'
                }).on('change',function(e){
                    $('select[name=\"addresses['+ address_row +'][zone_id]\"]').load('". Url::createAdminUrl('sale/customer/zone') ."&country_id=' + this.value + '&zone_id=0');
                }).appendTo(td2);
                countries = '<option value=\"false\">". $this->data['text_select'] ."</option>';
                countries += '". $countries ."';
                input.country_id.append(countries);
                
                tr = $(document.createElement('tr')).appendTo(table);
                td1 = $(document.createElement('td')).appendTo(tr);
                td2 = $(document.createElement('td')).appendTo(tr);
                
                label.zone_id = $(document.createElement('label')).text('". $this->data['entry_zone'] ."').appendTo(td1);
                input.zone_id = $(document.createElement('select')).attr({
                    'type':'text',
                    'name':'addresses['+ address_row +'][zone_id]'
                }).appendTo(td2);
                
                tr = $(document.createElement('tr')).appendTo(table);
                td1 = $(document.createElement('td')).appendTo(tr);
                td2 = $(document.createElement('td')).appendTo(tr);
                
                label.city = $(document.createElement('label')).text('". $this->data['entry_city'] ."').appendTo(td1);
                input.city = $(document.createElement('input')).attr({
                    'type':'text',
                    'name':'addresses['+ address_row +'][city]'
                }).appendTo(td2);
                
                tr = $(document.createElement('tr')).appendTo(table);
                td1 = $(document.createElement('td')).appendTo(tr);
                td2 = $(document.createElement('td')).appendTo(tr);
                
                label.postcode = $(document.createElement('label')).text('". str_replace('&oacute;','\u00F3',$this->data['entry_postcode']) ."').appendTo(td1);
                input.postcode = $(document.createElement('input')).attr({
                    'type':'text',
                    'name':'addresses['+ address_row +'][postcode]'
                }).appendTo(td2);
                
                tr = $(document.createElement('tr')).appendTo(table);
                td1 = $(document.createElement('td')).appendTo(tr);
                td2 = $(document.createElement('td')).appendTo(tr);
                
                label.address = $(document.createElement('label')).text('". str_replace('&oacute;','\u00F3',$this->data['entry_address_1']) ."').appendTo(td1);
                input.address = $(document.createElement('input')).attr({
                    'type':'text',
                    'name':'addresses['+ address_row +'][address_1]'
                }).appendTo(td2);
                
            	$('#form').append(div);
                
                var li = $(document.createElement('li')).attr('id','address_'+ address_row);
                
                var a = $(document.createElement('a')).attr({
                    'data-target':'#tab_address_'+ address_row,
                    'onclick':'showTab(this)'
                })
                .text('". str_replace('&oacute;','\u00F3',$this->data['tab_address']) ." '+ address_row)
                .appendTo(li);
                
                var span = $(document.createElement('span')).attr({
                    'title':'Eliminar Direcci&oacute;n'
                })
                .text('\u00A0')
                .addClass('remove')
                .on('click',function(e){
                    $('#vtabs a:first').trigger('click'); 
                    $('#address_'+ address_row).remove(); 
                    $('#tab_address_'+ address_row).remove();
                })
                .appendTo(a);
                
            	$('#address_add').before(li);
            	$('#address_'+ address_row).trigger('click');
            }");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->template = 'sale/customer_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	/**
	 * ControllerSaleCustomer::zone()
	 * 
	 * @return
	 */
	public function zone() {
		$output = '';
		
		$this->load->auto('localisation/zone');
		
		$results = $this->modelZone->getAllByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}

  	/**
  	 * ControllerSaleCustomer::validateForm()
  	 * 
  	 * @return
  	 */
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['rif'])) < 1) || (strlen(utf8_decode($this->request->post['rif']))> 32)) {
      		$this->error['rif'] = $this->language->get('error_rif');
    	}

    	if ($this->request->post['rif'] == 'false') {
      		$this->error['sexo'] = $this->language->get('error_sexo');
    	}
        
        if ((strlen(utf8_decode($this->request->post['company'])) < 1) || (strlen(utf8_decode($this->request->post['company']))> 32)) {
      		$this->error['company'] = $this->language->get('error_company');
    	}
        
        if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname']))> 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname']))> 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
    	
		if ((strlen(utf8_decode($this->request->post['email']))> 96) || (!preg_match($pattern, $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone']))> 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if (($this->request->post['password']) || (!isset($this->request->get['customer_id']))) {
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

  	/**
  	 * ControllerSaleCustomer::validateDelete()
  	 * 
  	 * @return
  	 */
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	} 	
    
    public function callback() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header('Cache-Control: no-cache, must-revalidate');
		header("Pragma: no-cache");
		header("Content-type: application/json");

		$this->load->model('sale/customer');
        $name = $this->request->get['term'];
        
        $results = $this->modelCustomer->getAll();
        if (!$results) {
    		 $data['error'] = 1; 
		} else {
		  foreach ($results as $key => $value) {
		      $data[] = array(
                'id' => $value['cid'],
                'label' => $value['email'],
                'value' => $value['name'],
              );
		  }
		      
		}
		$this->load->library('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));
    }
    
    /**
     * ControllerSaleCategory::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function activate() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('sale/customer');
        $model = $this->modelCustomer->getCustomer($_GET['id']);
        if ($model['status'] == 0) {
                $this->modelCustomer->activate($_GET['id']);
                echo 1;
        } else {
                $this->modelCustomer->desactivate($_GET['id']);
                echo -1;
        }
     }
     
    /**
     * ControllerSaleCategory::activate()
     * activar o desactivar un objeto accedido por ajax
     * @return boolean
     * */
     public function approve() {
        if (!isset($_GET['id'])) return false;
        $this->load->auto('sale/customer');
        $model = $this->modelCustomer->getCustomer($_GET['id']);
        if ($model['approved'] == 0) {
            $this->modelCustomer->approve($_GET['id']);
            echo 1;
        } else {
            $this->modelCustomer->desapprove($_GET['id']);
            echo -1;
        }
     }
}
