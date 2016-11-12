<?php  
class ControllerModuleLinks extends Controller {
	protected $links_id = 0;
	protected $path = array();
	
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/links');
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->data['links'] = $this->getLinks($settings['menu_id']);
        
		$this->id = 'links';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/links.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/links.tpl';
		} else {
			$this->template = 'default/module/links.tpl';
		}
		$this->render();
  	}
	
	protected function getLinks($menu_id=0,$parent_id=0) {
		$this->load->model('content/menu');
        
		$output = '';
		$results = $this->modelMenu->getLinks($menu_id,$parent_id);
		if ($results) { 
			$output .= '<ul>';
    		foreach ($results as $result) {	    			
    			$childrens = $this->modelMenu->getLinks($menu_id,$result['menu_link_id']);
                $output .= '<li><a href="'. $result['link'] .'" title="'.$result['tag'].'">' . $result['tag'] . '</a></li>';
    			
    			if ($childrens) {
    			     foreach ($childrens as $child) {
    			         $output .= '<li>&nbsp;&nbsp;&nbsp;&nbsp;<a href="'. $child['link'] .'" title="'.$child['tag'].'">'.$child['tag'].'</a></li>';
                         $childs = $this->modelMenu->getLinks($menu_id,$child['menu_link_id']);
                         if ($childs) {
            			     foreach ($childs as $ch) {
            			         $output .= '<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'. $ch['link'] .'" title="'.$ch['tag'].'">'.$ch['tag'].'</a></li>';
            			     }
            			 }
    			     }
    			}
    		}
			$output .= '</ul>';
		}
		return $output;
	}		
}