<?php
class ControllerShippingPickup extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/pickup');
		$this->load->auto('url');
		$this->load->auto('setting/setting');
		$this->load->auto('localisation/geo_zone');
		$this->load->auto('localisation/tax_class');

		$this->document->title = $this->language->get('heading_title');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->modelSetting->update('pickup', $this->request->post);		
					
			$this->session->set('success',$this->language->get('text_success'));
							
            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('shipping/pickup')); 
            } else {
                $this->redirect(Url::createAdminUrl('extension/shipping')); 
            }
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_save_and_exit']= $this->language->get('button_save_and_exit');
		$this->data['button_save_and_keep']= $this->language->get('button_save_and_keep');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

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
       		'href'      => Url::createAdminUrl('extension/shipping'),
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('shipping/pickup'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = Url::createAdminUrl('shipping/pickup');
		
		$this->data['cancel'] = Url::createAdminUrl('extension/shipping');

		if (isset($this->request->post['pickup_geo_zone_id'])) {
			$this->data['pickup_geo_zone_id'] = $this->request->post['pickup_geo_zone_id'];
		} else {
			$this->data['pickup_geo_zone_id'] = $this->config->get('pickup_geo_zone_id');
		}
		
		if (isset($this->request->post['pickup_status'])) {
			$this->data['pickup_status'] = $this->request->post['pickup_status'];
		} else {
			$this->data['pickup_status'] = $this->config->get('pickup_status');
		}
		
		if (isset($this->request->post['pickup_sort_order'])) {
			$this->data['pickup_sort_order'] = $this->request->post['pickup_sort_order'];
		} else {
			$this->data['pickup_sort_order'] = $this->config->get('pickup_sort_order');
		}				
		
		$this->data['geo_zones'] = $this->modelGeozone->getAll();
						
        $scripts[] = array('id'=>'scriptForm','method'=>'ready','script'=>
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
            
        $scripts[] = array('id'=>'scriptFunctions','method'=>'function','script'=>
            "function saveAndExit() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndExit'>\").submit(); 
            }
            
            function saveAndKeep() { 
                window.onbeforeunload = null;
                $('#form').append(\"<input type='hidden' name='to' value='saveAndKeep'>\").submit(); 
            }");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        		
		$this->template = 'shipping/pickup.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/pickup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
