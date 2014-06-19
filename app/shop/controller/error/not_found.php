<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {		
		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

		$this->language->load('error/not_found');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->document->breadcrumbs = array();
 
      	$this->document->breadcrumbs[] = array(
        	'href'      => Url::createUrl("common/home"),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);		
		
		if (isset($this->request->get['r'])) {
       		$this->document->breadcrumbs[] = array(
        		'href'      => Url::createUrl($this->request->get['r']),
        		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
      		);	   	
		}
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_error'] = $this->language->get('text_error');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = Url::createUrl("common/home");
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/error/not_found.tpl')) {
			$this->template = $this->config->get('config_template') . '/error/not_found.tpl';
		} else {
			$this->template = 'default/error/not_found.tpl';
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
