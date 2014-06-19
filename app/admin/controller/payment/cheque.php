<?php 
class ControllerPaymentCheque extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/cheque');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->auto('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->auto('setting/setting');
			
			$this->modelSetting->update('cheque', $this->request->post);				
			
			$this->session->set('success',$this->language->get('text_success'));

            if ($_POST['to'] == "saveAndKeep") {
                $this->redirect(Url::createAdminUrl('payment/cheque')); 
            } else {
                $this->redirect(Url::createAdminUrl('extension/payment')); 
            }
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_payable'] = $this->language->get('entry_payable');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_save_and_keep'] = $this->language->get('button_save_and_keep');
        $this->data['button_save_and_exit'] = $this->language->get('button_save_and_exit');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['payable'])) {
			$this->data['error_payable'] = $this->error['payable'];
		} else {
			$this->data['error_payable'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('extension/payment'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('payment/cheque'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = Url::createAdminUrl('payment/cheque');
		$this->data['cancel'] = Url::createAdminUrl('extension/payment');
		
        $this->setvar('cheque_payable');
        $this->setvar('cheque_order_status_id');
        $this->setvar('cheque_email_template');
        $this->setvar('cheque_geo_zone_id');
        $this->setvar('cheque_status');
        $this->setvar('cheque_sort_order');
        
		$this->load->auto('localisation/orderstatus');
		
		$this->data['order_statuses'] = $this->modelOrderstatus->getAll();
		
		$this->load->auto('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->modelGeozone->getAll();
		
		$this->load->model('marketing/newsletter');
		$this->data['newsletters']    = $this->modelNewsletter->getAll();
        
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
        
		$this->template = 'payment/cheque.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cheque')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['cheque_payable']) {
			$this->error['payable'] = $this->language->get('error_payable');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
