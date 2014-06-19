<?php
class ControllerMarketingMessage extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->document->title = $this->language->get('heading_title');
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->modelSetting->update('marketing', $this->request->post);
			$this->session->set('success',$this->language->get('text_success'));
		}
        
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['newsletters']    = $this->modelNewsletter->getAll();
		$this->data['pages']          = $this->modelPage->getAll();
        
        $this->setvar('marketing_page_contact_id');
        $this->setvar('marketing_email_new_customer');
        $this->setvar('marketing_email_new_order');
        $this->setvar('marketing_email_order_pdf');
        $this->setvar('marketing_email_new_comment');
        $this->setvar('marketing_email_new_reply');
        $this->setvar('marketing_email_old_order');
        $this->setvar('marketing_email_new_payment');
        $this->setvar('marketing_email_update_order');
        $this->setvar('marketing_email_new_contact');
        $this->setvar('marketing_email_add_balance');
        $this->setvar('marketing_email_subtract_balance');
        $this->setvar('marketing_email_happy_birthday');
        $this->setvar('marketing_email_recommended_products');
        $this->setvar('marketing_email_promote_product');
        $this->setvar('marketing_email_invite_friends');
        $this->setvar('marketing_email_payment_instructions');
        
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('marketing/message'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

		$this->template = 'marketing/message.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('marketing_compression'));
  	}
    
  	/**
  	 * ControllerMarketingNewsletter::activate()
     * duplicar un objeto
  	 * @return boolean
  	 */
  	public function activate() {
  	     $result = 1;
        $this->load->auto('marketing/newsletter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelNewsletter->activate($id);
            }
		} else {
            $result = $this->modelNewsletter->toggleStatus($_GET['id']);
		}
        echo $result;
  	}
    
	private function validate() {
		if (!$this->user->hasPermission('modify', 'marketing/message')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
    
}
