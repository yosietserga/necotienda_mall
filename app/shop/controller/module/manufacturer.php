<?php  
class ControllerModuleManufacturer extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/manufacturer');	
		
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['manufacturer_id'] = $this->request->get['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = 0;
		}
		
		$this->load->model('store/manufacturer');

		$this->data['manufacturers'] = array();
		
		$results = $this->modelManufacturer->getManufacturers();
		
		foreach ($results as $result) {
			$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'href'            => Url::createUrl("store/manufacturer",array("manufacturer_id"=>$result['manufacturer_id']))
			);
		}
		
		$this->id = 'manufacturer';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/manufacturer.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/manufacturer.tpl';
		} else {
			$this->template = 'default/module/manufacturer.tpl';
		}
		
		$this->render(); 
	}
}
