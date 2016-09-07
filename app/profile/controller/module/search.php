<?php  
class ControllerModuleSearch extends Controller {
	protected function index($widget=null) {
		$this->language->load('module/search');
        
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
        
        // style files
        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        $csspath =  (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) ? str_replace("%theme%",$this->config->get('config_template'),$csspath) : str_replace("%theme%","default",$csspath);
                
        if (fopen($csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'),'r')) $styles[] = array('media'=>'all','href'=>$csspath.str_replace('controller','',strtolower(__CLASS__) . '.css')); 
        if ($styles) $this->styles = array_merge($styles,$this->styles);
        
        $this->getCategories3();
        $this->getZones();
        
		$this->id = 'search';
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/search.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/search.tpl';
		} else {
            $this->template = 'default/module/search.tpl';
		}
		$this->render();
	}
    
	protected function getCategories3() {
        $this->language->load('module/category');
		$output = $this->cache->get('category_select.tpl', $output);
        
        if (!$output) {
            $this->load->model('store/category');
		
    		$output = '';
    		$results = $this->modelCategory->getCategories(0);
    		if ($results) { 
                foreach ($results as $result) {
        			$output .= '<option value="'. $result['name'] .'">'. $result['name'] .'</option>';
        			
        		}
    		}
    		$this->cache->set('category_select.tpl', $output);
        }
        $this->data['categories'] = $output;
	}
    
	protected function getZones() {
		$output = $this->cache->get('zone_select.tpl');
        
        if (!$output) {
            $this->load->model('localisation/zone');
		
    		$output = '';
    		$results = $this->modelZone->getZonesByCountryId(229);
    		if ($results) { 
                foreach ($results as $result) {
        			$output .= '<option value="'. $result['name'] .'">'. $result['name'] .'</option>';
        			
        		}
    		}
    		$this->cache->set('zone_select.tpl', $output);
        }
        $this->data['zones'] = $output;
	}
}
