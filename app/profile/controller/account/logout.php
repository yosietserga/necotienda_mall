<?php 
class ControllerAccountLogout extends Controller {
	public function index() {
    	if ($this->customer->isLogged()) {
      		$this->customer->logout();
	  		$this->cart->clear();
			
			$this->session->clear('shipping_address_id');
			$this->session->clear('shipping_method');
			$this->session->clear('shipping_methods');
			$this->session->clear('payment_address_id');
			$this->session->clear('payment_method');
			$this->session->clear('payment_methods');
			$this->session->clear('comment');
			$this->session->clear('order_id');
			$this->session->clear('coupon');
			
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
			
      		$this->redirect(Url::createUrl("account/logout"));
    	}
 
    	$this->language->load('account/logout');
		
		$this->document->title = $this->language->get('heading_title');
      	
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);
      	
		$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/account"),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("account/logout"),
        	'text'      => $this->language->get('text_logout'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = Url::createUrl("common/home");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/common/success.tpl';
		} else {
			$this->template = 'default/common/success.tpl';
		}
		
    		$this->children[] = 'common/column_left';
    		$this->children[] = 'common/column_right';
    		$this->children[] = 'common/nav';
    		$this->children[] = 'common/header';
    		$this->children[] = 'common/footer';
            
            $this->load->helper('widgets');
            $widgets = new NecoWidget($this->registry,$this->Route);
            foreach ($widgets->getWidgets('main') as $widget) {
                $settings = unserialize($widget['settings']);
                if ($settings->asyn) {
                    $url = Url::createUrl("{$settings->route}",$settings->params);
                    $scripts[$widget['name']] = array(
                        'id'=>$widget['name'],
                        'method'=>'ready',
                        'script'=>
                        "$(document.createElement('div'))
                        .attr({
                            id:'".$widget['name']."'
                        })
                        .html(makeWaiting())
                        .load('". $url . "')
                        .appendTo('".$settings->target."');"
                    );
                } else {
                    if (isset($settings->route)) {
                        $this->data['widgets'][] = $widget['name'];
                        $this->children[$widget['name']] = $settings->route;
                        $this->widget[$widget['name']] = $widget;
                    }
                }
            }
            
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));	
  	}
}
