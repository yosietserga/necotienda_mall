<?php
/**
 * ControllerModuleSkypeMeWidget
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSkypeMeWidget extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSkypeMeWidget::index()
	 * 
	 * @return
	 */
	public function index() {
        if (!$this->request->hasQuery('name')) return false;
        
		$this->load->language('module/skype_me');
        
		$this->load->helper('widgets');
        $widget = new NecoWidget($this->registry);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $widget_name = $this->request->getQuery('name');
            $data = $this->request->post['Widgets'][$widget_name];
            
            $settings = new stdClass;
            foreach ($data['settings'] as $key => $value) {
                $settings->$key = $value;
            }
            $data['settings'] = $settings;
            
            if ($widget->save($data)) {
                $json['success'] = 1;
            } else {
                $json['error'] = 1;
                $json['msg'] = $this->language->get('error_saving_widget');
            }
        } else {
            $data['name']           = ($this->request->hasQuery('name')) ? $this->request->getQuery('name') : null;
            $data['landing_page']   = 'all';
            $data['position']       = ($this->request->hasQuery('position')) ? $this->request->getQuery('position') : null;
            $data['extension']       = ($this->request->hasQuery('extension')) ? $this->request->getQuery('extension') : null;
            $data['app']            = ($this->request->hasQuery('app')) ? $this->request->getQuery('app') : 'shop';
            $data['order']          = ($this->request->hasQuery('order')) ? $this->request->getQuery('order') : 0;
            $data['store_id']       = ($this->request->hasQuery('store_id')) ? $this->request->getQuery('store_id') : 0;
            
            if ($this->request->hasQuery('w')) {
                $this->load->model('style/widget');
                $widget_info = $this->modelWidget->getByName($data['name']);
                $this->setvar('widget_id',$widget_info);
                $this->setvar('code',$widget_info);
                $this->setvar('name',$widget_info);
                $this->setvar('position',$widget_info);
                $this->setvar('extension',$widget_info);
                $this->setvar('status',$widget_info);
                $this->setvar('app',$widget_info);
                $this->setvar('order',$widget_info);
                $this->setvar('store_id',$widget_info);
                
                $landing_pages = array();
                foreach ($widget_info['landing_pages'] as $lp) {
                    $landing_pages[] = $lp['landing_page'];
                }
                $this->data['landing_pages'] = $landing_pages;
                
                $this->data['settings'] = (array)unserialize($widget_info['settings']);
            } else {
                $settings           = new stdClass;
                $settings->route    = 'module/skype_me';
                $settings->autoload = 1;
                $data['settings']   = $settings;
                $widget->save($data);
                $this->setvar('name');
            }
            
            $this->data['routes'] = $widget->getRoutes();
            foreach ($this->data['routes'] as $text_var => $landing_page) {
                $json['routes'][] = array(
        			'landing_page' => $landing_page,
        			'title'    => $this->language->get($text_var),
        		);
            }
            
    		$this->template = 'module/skype_me/widget.tpl';
            $json['html'] = $this->render(true);
        }
        
        $this->load->library('json');
		$this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
	}
}
