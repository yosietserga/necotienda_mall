<?php
/**
 * ControllerModuleGoogleTalkPlugin
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleGoogleTalkPlugin extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleGoogleTalkPlugin::index()
	 * 
	 * @return
	 */
	public function index() {   
		$this->load->language('module/google_talk');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->auto('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->modelSetting->update('google_talk', $this->request->post);		
					
			$this->session->set('success',$this->language->get('text_success'));
						
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('module/google_talk')); 
            } else {
                $this->redirect(Url::createAdminUrl('extension/module')); 
            }
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_code'] = $this->language->get('help_code');
		$this->data['help_position'] = $this->language->get('help_position');
		$this->data['help_status'] = $this->language->get('help_status');
		$this->data['help_sort_order'] = $this->language->get('help_sort_order');
		
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
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
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
       		'href'      => Url::createAdminUrl('module/google_talk'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = Url::createAdminUrl('module/google_talk');
		
		$this->data['cancel'] = Url::createAdminUrl('extension/module');

		if (isset($this->request->post['google_talk_code'])) {
			$this->data['google_talk_code'] = $this->request->post['google_talk_code'];
		} else {
			$this->data['google_talk_code'] = $this->config->get('google_talk_code');
		}	
		
		if (isset($this->request->post['google_talk_position'])) {
			$this->data['google_talk_position'] = $this->request->post['google_talk_position'];
		} else {
			$this->data['google_talk_position'] = $this->config->get('google_talk_position');
		}
		
		if (isset($this->request->post['google_talk_status'])) {
			$this->data['google_talk_status'] = $this->request->post['google_talk_status'];
		} else {
			$this->data['google_talk_status'] = $this->config->get('google_talk_status');
		}
		
		if (isset($this->request->post['google_talk_sort_order'])) {
			$this->data['google_talk_sort_order'] = $this->request->post['google_talk_sort_order'];
		} else {
			$this->data['google_talk_sort_order'] = $this->config->get('google_talk_sort_order');
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
		
		$this->data['positions'][] = array(
			'position' => 'home',
			'title'    => $this->language->get('text_home'),
		);
		
        $this->data['Url'] = new Url;
        
        $scripts[] = array('id'=>'featuredForm','method'=>'ready','script'=>
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
            
        $scripts[] = array('id'=>'featuredFunctions','method'=>'function','script'=>
            "function saveAndExit() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndExit'>\").submit(); 
            }
            
            function saveAndKeep() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndKeep'>\").submit(); 
            }");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->template = 'module/google_talk.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	/**
	 * ControllerModuleGoogleTalkPlugin::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_talk')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['google_talk_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
