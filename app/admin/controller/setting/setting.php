<?php
class ControllerSettingSetting extends Controller {
	private $error = array();
 
	public function index() {
	   $this->load->library('url');
		$this->document->title = $this->language->get('heading_title');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		  
			if (isset($this->request->post['config_token_ignore'])) {
				$this->request->post['config_token_ignore'] = serialize($this->request->post['config_token_ignore']);
			}
		  
			$this->modelSetting->update('config', $this->request->post);

		  
			if ($this->config->get('config_currency_auto')) {
				$this->modelCurrency->updateAll();
			}	
		  
			
			$this->session->set('success',$this->language->get('text_success'));
		  
			$this->redirect(Url::createAdminUrl('setting/setting'));
		  
		}
        
        if (isset($this->request->post['config_maintenance'])) { 
            $this->modelSetting->editMaintenance($this->request->post['config_maintenance']);
        }

        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : null;
        $this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : null;
        $this->data['error_rif'] = isset($this->error['rif']) ? $this->error['rif'] : null;
        $this->data['error_url'] = isset($this->error['url']) ? $this->error['url'] : null;
        $this->data['error_owner'] = isset($this->error['owner']) ? $this->error['owner'] : null;
        $this->data['error_address'] = isset($this->error['address']) ? $this->error['address'] : null;
        $this->data['error_email'] = isset($this->error['email']) ? $this->error['email'] : null;
        $this->data['error_telephone'] = isset($this->error['telephone']) ? $this->error['telephone'] : null;
        $this->data['error_title'] = isset($this->error['title']) ? $this->error['title'] : null;
        $this->data['error_image_thumb'] = isset($this->error['image_thumb']) ? $this->error['image_thumb'] : null;
        $this->data['error_image_popup'] = isset($this->error['image_popup']) ? $this->error['image_popup'] : null;
        $this->data['error_image_category'] = isset($this->error['image_category']) ? $this->error['image_category'] : null;
        $this->data['error_image_product'] = isset($this->error['image_product']) ? $this->error['image_product'] : null;
        $this->data['error_image_additional'] = isset($this->error['image_additional']) ? $this->error['image_additional'] : null;
        $this->data['error_image_related'] = isset($this->error['image_related']) ? $this->error['image_related'] : null;
        $this->data['error_image_cart'] = isset($this->error['image_cart']) ? $this->error['image_cart'] : null;
        $this->data['error_error_filename'] = isset($this->error['error_filename']) ? $this->error['error_filename'] : null;
        $this->data['error_catalog_limit'] = isset($this->error['catalog_limit']) ? $this->error['catalog_limit'] : null;
        $this->data['error_admin_limit'] = isset($this->error['admin_limit']) ? $this->error['admin_limit'] : null;
        $this->data['error_thousands_separator'] = isset($this->error['thousands_separator']) ? $this->error['thousands_separator'] : null;
        $this->data['error_decimal_separator'] = isset($this->error['decimal_separator']) ? $this->error['decimal_separator'] : null;
        $this->data['error_smtp_host'] = isset($this->error['smtp_host']) ? $this->error['smtp_host'] : null;
        $this->data['error_pop3_host'] = isset($this->error['pop3_host']) ? $this->error['pop3_host'] : null;
        $this->data['error_pop3_port'] = isset($this->error['pop3_port']) ? $this->error['pop3_port'] : null;
        $this->data['error_smtp_port'] = isset($this->error['smtp_port']) ? $this->error['smtp_port'] : null;
        $this->data['error_smtp_from_email'] = isset($this->error['smtp_from_email']) ? $this->error['smtp_from_email'] : null;
        $this->data['error_smtp_timeout'] = isset($this->error['smtp_timeout']) ? $this->error['smtp_timeout'] : null;
        $this->data['error_bounce_email'] = isset($this->error['bounce_email']) ? $this->error['bounce_email'] : null;
        $this->data['error_replyto_email'] = isset($this->error['replyto_email']) ? $this->error['replyto_email'] : null;
		
        
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('setting/setting'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['cancel'] = Url::createAdminUrl('setting/setting');

		$this->data['action'] = Url::createAdminUrl('setting/setting');
		
        $this->setvar('config_name');
        $this->setvar('config_rif');
        $this->setvar('config_url');
        $this->setvar('config_owner');
        $this->setvar('config_address');
        $this->setvar('config_email');
        $this->setvar('config_telephone');
        $this->setvar('config_fax');
        $this->setvar('config_title');
        $this->setvar('config_meta_description');
        $this->setvar('config_template');
        $this->setvar('config_decimal_separator');
        $this->setvar('config_thousands_separator');
        $this->setvar('config_country_id');
        $this->setvar('config_zone_id');
        $this->setvar('config_language');
        $this->setvar('config_admin_language');
        $this->setvar('config_currency');
        $this->setvar('config_currency_auto');
        $this->setvar('config_tax');
        $this->setvar('config_customer_group_id');
        $this->setvar('config_customer_price');
        $this->setvar('config_customer_approval');
        $this->setvar('config_account_id');
        $this->setvar('config_checkout_id');
        $this->setvar('config_stock_display');
        $this->setvar('config_stock_checkout');
        $this->setvar('config_order_status_id');
        $this->setvar('config_stock_status_id');
        $this->setvar('config_shipping_session');
        $this->setvar('config_admin_limit');
        $this->setvar('config_catalog_limit');
        $this->setvar('config_new_days');
        $this->setvar('config_cart_weight');
        $this->setvar('config_review');
        $this->setvar('config_review_approve');
        $this->setvar('config_logo');
        $this->setvar('config_icon');
        $this->setvar('config_image_thumb_width');
        $this->setvar('config_image_thumb_height');
        $this->setvar('config_image_popup_width');
        $this->setvar('config_image_popup_height');
        $this->setvar('config_image_category_width');
        $this->setvar('config_image_category_height');
        $this->setvar('config_image_post_width');
        $this->setvar('config_image_post_height');
        $this->setvar('config_image_product_width');
        $this->setvar('config_image_product_height');
        $this->setvar('config_image_additional_width');
        $this->setvar('config_image_additional_height');
        $this->setvar('config_image_related_width');
        $this->setvar('config_image_related_height');
        $this->setvar('config_image_cart_width');
        $this->setvar('config_image_cart_height');
        $this->setvar('config_mail_protocol');
        $this->setvar('config_smtp_host');
        $this->setvar('config_pop3_host');
        $this->setvar('config_smtp_from_email');
        $this->setvar('config_smtp_from_name');
        $this->setvar('config_smtp_username');
        $this->setvar('config_smtp_password');
        $this->setvar('config_smtp_method',null,'mail');
        $this->setvar('config_smtp_port',null,25);
        $this->setvar('config_pop3_protocol',null,'pop3');
        $this->setvar('config_pop3_port',null,110);
        $this->setvar('config_smtp_timeout',null,5);
        $this->setvar('config_smtp_maxsize',null,0);
        $this->setvar('config_smtp_charset',null,'iso-8859-1');
        $this->setvar('config_alert_mail');
        $this->setvar('config_smtp_auth');
        $this->setvar('config_alert_emails');
        $this->setvar('config_mail_parameter');
        $this->setvar('config_ssl');
        $this->setvar('config_pop3_ssl');
        $this->setvar('config_smtp_ssl');
        $this->setvar('config_bounce_email',null,$this->config->get('config_pop3_email'));
        $this->setvar('config_replyto_email',null,'');
        $this->setvar('config_bounce_server',null,'');
        $this->setvar('config_bounce_username',null,'');
        $this->setvar('config_bounce_password',null,'');
        $this->setvar('config_bounce_protocol',null,'');
        $this->setvar('config_bounce_extra_settings',null,'');
        $this->setvar('config_bounce_protocol',null,'pop3');
        $this->setvar('config_bounce_process',null,0);
        $this->setvar('config_bounce_agree_delete',null,0);
        $this->setvar('config_bounce_extra_settings',null,'');
        $this->setvar('config_bounce_extra_settings',null,'');
        $this->setvar('config_maintenance');
        $this->setvar('config_encryption');
        $this->setvar('config_js_security');
        $this->setvar('config_js_security');
        $this->setvar('config_server_security');
        $this->setvar('config_password_security');
        $this->setvar('config_seo_url');
        $this->setvar('config_compression');
        $this->setvar('config_error_display');
        $this->setvar('config_error_log');
        $this->setvar('config_error_filename');
        $this->setvar('config_dir_export');
        
        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		$this->data['templates'] = array();
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}	
        
		$languages = $this->data['languages'] = $this->modelLanguage->getAll();
		
		foreach ($languages as $language) {
			if (isset($this->request->post['config_title_' . $language['language_id']])) {
				$this->data['config_title_' . $language['language_id']] = $this->request->post['config_title_' . $language['language_id']];
			} else {
				$this->data['config_title_' . $language['language_id']] = $this->config->get('config_title_' . $language['language_id']);
			}
            
			if (isset($this->request->post['config_meta_description_' . $language['language_id']])) {
				$this->data['config_meta_description_' . $language['language_id']] = $this->request->post['config_meta_description_' . $language['language_id']];
			} else {
				$this->data['config_meta_description_' . $language['language_id']] = $this->config->get('config_meta_description_' . $language['language_id']);
			}
            
			if (isset($this->request->post['config_description_' . $language['language_id']])) {
				$this->data['config_description_' . $language['language_id']] = $this->request->post['config_description_' . $language['language_id']];
			} else {
				$this->data['config_description_' . $language['language_id']] = $this->config->get('config_description_' . $language['language_id']);
			}
		}

		$this->data['countries']      = $this->modelCountry->getAll();
		$this->data['currencies']     = $this->modelCurrency->getAll();
		$this->data['customer_groups']= $this->modelCustomergroup->getAll();
		$this->data['order_statuses'] = $this->modelOrderstatus->getAll();
		$this->data['stock_statuses'] = $this->modelStockstatus->getAll();
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['preview_logo'] = HTTP_IMAGE . $this->config->get('config_logo');		
		} else {
			$this->data['preview_logo'] = NTImage::resizeAndSave('no_image.jpg', 100, 100);
		}
        
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['preview_icon'] = HTTP_IMAGE . $this->config->get('config_icon');		
		} else {
			$this->data['preview_icon'] = NTImage::resizeAndSave('no_image.jpg', 100, 100);
		}
		
		$ignore = array(
			'common/login',
			'common/logout',
			'error/not_found',
			'error/permission'
		);
		
		$this->data['tokens'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/*/*.php');
		
		foreach ($files as $file) {
			$data = explode('/', dirname($file));
			
			$token = end($data) . '/' . basename($file, '.php');
			
			if (!in_array($token, $ignore)) {
				$this->data['tokens'][] = $token;
			}
		}
		

		if (isset($this->request->post['config_token_ignore'])) {
			$this->data['config_token_ignore'] = $this->request->post['config_token_ignore']; 
		} elseif ($this->config->get('config_token_ignore')) {
			$this->data['config_token_ignore'] = unserialize($this->config->get('config_token_ignore'));
		} else {
			$this->data['config_token_ignore'] = array();
		}
				
        foreach ($this->data['languages'] as $language) {
            $scripts[] = array('id'=>'Language'. $language["language_id"],'method'=>'ready','script'=>
                "CKEDITOR.replace('description". $language["language_id"] ."', {
                	filebrowserBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserImageBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserFlashBrowseUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserImageUploadUrl: '". Url::createAdminUrl("common/filemanager") ."',
                	filebrowserFlashUploadUrl: '". Url::createAdminUrl("common/filemanager") ."'
                });");
        }
        
        $scripts[] = array('id'=>'Functions','method'=>'function','script'=>
            "function image_delete(field, preview) {
                $('#' + field).val('');
                $('#' + preview).attr('src','". HTTP_IMAGE ."cache/no_image-100x100.jpg');
            }
            
            function image_upload(field, preview) {
                var height = $(window).height() * 0.8;
                var width = $(window).width() * 0.8;
                
            	$('#dialog').remove();
            	$('.box').prepend('<div id=\"dialog\" style=\"padding: 3px 0px 0px 0px;z-index:10000;\"><iframe src=\"". Url::createAdminUrl("common/filemanager") ."&field=' + encodeURIComponent(field) + '\" style=\"padding:0; margin: 0; display: block; width: 100%; height: 100%;z-index:10000;\" frameborder=\"no\" scrolling=\"auto\"></iframe></div>');
                
                $('#dialog').dialog({
            		title: '".$this->data['text_image_manager']."',
            		close: function (event, ui) {
            			if ($('#' + field).attr('value')) {
            				$.ajax({
            					url: '". Url::createAdminUrl("common/filemanager/image") ."',
            					type: 'POST',
            					data: 'image=' + encodeURIComponent($('#' + field).val()),
            					dataType: 'text',
            					success: function(data) {
            						$('#' + preview).replaceWith('<img src=\"' + data + '\" id=\"' + preview + '\" class=\"image\" onclick=\"image_upload(\'' + field + '\', \'' + preview + '\');\">');
            					}
            				});
            			}
            		},	
            		bgiframe: false,
            		width: width,
            		height: height,
            		resizable: false,
            		modal: false
            	});}");
            
        $this->scripts = array_merge($this->scripts,$scripts);
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        
        $javascripts[] = "js/vendor/ckeditor/ckeditor.js";
        
        $this->javascripts = array_merge($javascripts,$this->javascripts);
        
		$this->template = 'setting/setting.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
    
    public function maintenance() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->auto('setting/setting');
            if ($this->request->post['config_maintenance'] == 'si') {
            $this->modelSetting->editMaintenance(1);
            } else {
            $this->modelSetting->editMaintenance(0);                
            }
        }
    }

	private function validate() {
		  
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		  
		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}

		  
		if (!$this->validate_form->esRif($this->request->post['config_rif'])) {
			$this->error['rif'] = $this->language->get('error_rif');
		}	
		
		  
		if (!$this->request->post['config_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}	
		
		  
		if (!$this->request->post['config_owner']) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		  
		if (!$this->request->post['config_address']) {
			$this->error['address'] = $this->language->get('error_address');
		}

		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

		  
    	if ((strlen(utf8_decode($this->request->post['config_email']))> 96) || (!preg_match($pattern, $this->request->post['config_email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

		  
		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}	
		
		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}	
		
		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}
		
		if (!$this->request->post['config_image_post_width'] || !$this->request->post['config_image_post_height']) {
			$this->error['image_post'] = $this->language->get('error_image_post');
		}
		
		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}
		
		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}
		
		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}
		
		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}
		
		if (!$this->request->post['config_error_filename']) {
			$this->error['error_filename'] = $this->language->get('error_error_filename');
		}
		
		if (!$this->request->post['config_admin_limit']) {
			$this->error['admin_limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}
		
		if (isset($this->request->post['config_smtp_port']) && !$this->validate_form->esSoloNumeros($this->request->post['config_smtp_port'],$this->language->get('entry_smtp_port'))) {
			$this->error['smtp_port'] = $this->language->get('error_smtp_port');
		}
		
		if (isset($this->request->post['config_pop3_port']) && !$this->validate_form->esSoloNumeros($this->request->post['config_pop3_port'],$this->language->get('entry_pop3_port'))) {
			$this->error['pop3_port'] = $this->language->get('error_pop3_port');
		}
		
		if (isset($this->request->post['config_smtp_timeout']) && !$this->validate_form->esSoloNumeros($this->request->post['config_smtp_timeout'],$this->language->get('entry_smtp_timeout'))) {
			$this->error['smtp_timeout'] = $this->language->get('error_smtp_timeout');
		}
		
		if (isset($this->request->post['config_smtp_from_email']) && !$this->validate_form->validEmail($this->request->post['config_smtp_from_email'],$this->language->get('entry_smtp_from_email'))) {
			$this->error['smtp_from_email'] = $this->language->get('error_smtp_from_email');
		}
        
		if (isset($this->request->post['config_replyto_email']) && !$this->validate_form->validEmail($this->request->post['config_replyto_email'],$this->language->get('entry_replyto_email'))) {
		      $this->error['replyto_email'] = $this->language->get('error_replyto_email');
		}
        
		if (isset($this->request->post['config_bounce_email']) && !$this->validate_form->validEmail($this->request->post['config_bounce_email'],$this->language->get('entry_bounce_email'))) {
		      $this->error['bounce_email'] = $this->language->get('error_bounce_email');
		}
        
        $this->data['mostrarError'] = $this->validate_form->mostrarError();
        
		if (!$this->error) {
			return true;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return false;
		}
	}
    
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

    public function template() {
		$template = basename($this->request->get['template']);
		if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png')) {
			$image = HTTP_IMAGE . 'templates/' . $template . '.png';
		} else {
			$image = HTTP_IMAGE . 'no_image.jpg';
		}
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}
    
}
