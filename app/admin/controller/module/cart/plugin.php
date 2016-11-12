<?php
/**
 * ControllerModuleCartPlugin
 * 
 * @package   
 * @author NecoTienda
 * @copyright Inversiones Necoyoad, C.A.
 * @version 2010
 * @access public
 */
class ControllerModuleCartPlugin extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCartPlugin::index()
	 * 
	 * @return
	 */
	public function index() {   
		$this->load->language('module/cart');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->auto('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->modelSetting->update('cart', $this->request->post);		
					
			$this->session->set('success',$this->language->get('text_success'));
						
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('module/cart')); 
            } else {
                $this->redirect(Url::createAdminUrl('extension/module')); 
            }
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_ajax'] = $this->language->get('entry_ajax');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_save_and_new']= $this->language->get('button_save_and_new');
		$this->data['button_save_and_exit']= $this->language->get('button_save_and_exit');
		$this->data['button_save_and_keep']= $this->language->get('button_save_and_keep');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('extension/module'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('module/cart'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = Url::createAdminUrl('module/cart');
		
		$this->data['cancel'] = Url::createAdminUrl('extension/module');

		if (isset($this->request->post['cart_ajax'])) {
			$this->data['cart_ajax'] = $this->request->post['cart_ajax'];
		} else {
			$this->data['cart_ajax'] = $this->config->get('cart_ajax');
		}
		
		if (isset($this->request->post['cart_position'])) {
			$this->data['cart_position'] = $this->request->post['cart_position'];
		} else {
			$this->data['cart_position'] = $this->config->get('cart_position');
		}
		
		if (isset($this->request->post['cart_status'])) {
			$this->data['cart_status'] = $this->request->post['cart_status'];
		} else {
			$this->data['cart_status'] = $this->config->get('cart_status');
		}
		
		if (isset($this->request->post['cart_sort_order'])) {
			$this->data['cart_sort_order'] = $this->request->post['cart_sort_order'];
		} else {
			$this->data['cart_sort_order'] = $this->config->get('cart_sort_order');
		}				
		
		$this->data['positions'] = array();
		
		$this->data['positions'][] = array(
			'position' => 'left',
			'title'    => $this->language->get('text_left'),
		);
		
		$this->data['positions'][] = array(
			'position' => 'right',
			'title'    => $this->language->get('text_right'),
		);
		
        $this->data['Url'] = new Url;
        
        $scripts[] = array('id'=>'jsForm','method'=>'ready','script'=>
            "$('#form').ntForm({
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
            
        $scripts[] = array('id'=>'jsFunctions','method'=>'function','script'=>
            "function saveAndExit() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndExit'>\").submit(); 
            }
            
            function saveAndKeep() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndKeep'>\").submit(); 
            }");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->template = 'module/cart.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	/**
	 * ControllerModuleCartPlugin::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/cart')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
