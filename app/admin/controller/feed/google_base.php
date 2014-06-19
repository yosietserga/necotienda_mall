<?php 
class ControllerFeedGoogleBase extends Controller {
	public function index() {
		$this->load->language('feed/google_base');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->auto('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->auto('setting/setting');
			
			$this->modelSetting->update('google_base', $this->request->post);				
			
			$this->session->set('success',$this->language->get('text_success'));

			$this->redirect(Url::createAdminUrl('extension/feed'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
		
		$this->data['button_save'] = $this->language->get('button_save');
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
       		'href'      => Url::createAdminUrl('extension/feed'),
       		'text'      => $this->language->get('text_feed'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('feed/google_base'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = Url::createAdminUrl('feed/google_base');
		
		$this->data['cancel'] = Url::createAdminUrl('extension/feed');
		
		if (isset($this->request->post['google_base_status'])) {
			$this->data['google_base_status'] = $this->request->post['google_base_status'];
		} else {
			$this->data['google_base_status'] = $this->config->get('google_base_status');
		}
		
		$this->data['data_feed'] = HTTP_CATALOG . 'index.php?r=feed/google_base';
		
		$this->template = 'feed/google_base.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'feed/google_base')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}	
}
