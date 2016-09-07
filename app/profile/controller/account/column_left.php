<?php
class ControllerAccountColumnLeft extends Controller {
	protected function index() {
	   
		$this->language->load('account/account');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
		$this->data['text_my_orders'] = $this->language->get('text_my_orders');
		$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
    	$this->data['text_password'] = $this->language->get('text_password');
    	$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_history'] = $this->language->get('text_history');
    	$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_cancel_account'] = $this->language->get('text_cancel_account');
		$this->data['text_balance'] = $this->language->get('text_balance');
		$this->data['text_social_networks'] = $this->language->get('text_social_networks');
		$this->data['text_hobbies'] = $this->language->get('text_hobbies');
		$this->data['text_my_activities'] = $this->language->get('text_my_activities');
		$this->data['text_my_comment'] = $this->language->get('text_my_comment');
		$this->data['text_profile'] = $this->language->get('text_profile');
		$this->data['text_payments'] = $this->language->get('text_payments');
        
		$this->data['text_messages'] = $this->language->get('text_messages');
		$this->data['text_create_message'] = $this->language->get('text_create_message');
		$this->data['text_inbounce'] = $this->language->get('text_inbounce');
		$this->data['text_outbounce'] = $this->language->get('text_outbounce');

       $this->data['Url'] = new Url($this->registry);
       
        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        $styles[] = array('media'=>'all','href'=>$csspath.'jquery-ui/jquery-ui.min.css');
        $this->styles = array_merge($styles,$this->styles);
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        $javascripts[] = $jspath."vendor/jquery-ui.min.js";
        $this->javascripts = array_merge($this->javascripts, $javascripts);
        
        // SCRIPTS
        $scripts[] = array('id'=>'messageScripts','method'=>'ready','script'=>
        "var icons = {
            header: 'ui-icon-circle-arrow-e',
            activeHeader: 'ui-icon-circle-arrow-s'
            };
            $( '#accordion' ).accordion({
            icons: icons
            });
            $( '#toggle' ).button().click(function() {
            if ( $( '#accordion' ).accordion( 'option', 'icons' ) ) {
            $( '#accordion' ).accordion( 'option', 'icons', null );
            } else {
            $( '#accordion' ).accordion( 'option', 'icons', icons );
            }
       });");
        
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->id = 'column_left';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/account/column_left.tpl')) {
			$this->template = $this->config->get('config_template') . '/account/column_left.tpl';
		} else {
			$this->template = 'default/account/column_left.tpl';
		}
		
		$this->render();
	}
}
