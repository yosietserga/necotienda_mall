<?php  
class ControllerModuleFBLike extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/fblike');

		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->data['pageid'] = html_entity_decode($settings['fblike_pageid']);
        $this->data['totalconnection'] = html_entity_decode($settings['fblike_totalconnection']);
        $this->data['width']  = html_entity_decode($settings['fblike_width']);
        $this->data['height'] = html_entity_decode($settings['fblike_height']);
        $this->data['stream'] = html_entity_decode($settings['fblike_stream']);
		$this->data['header'] = html_entity_decode($settings['fblike_header']);

		$this->id = 'fblike';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/fblike.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/fblike.tpl';
		} else {
			$this->template = 'default/module/fblike.tpl';
		}
		
		$this->render();
	}
}
