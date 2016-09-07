<?php  
class ControllerModuleCategory extends Controller {
	protected $category_id = 0;
	protected $path = array();
	
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/category');
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->load->model('store/category');
		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			$this->category_id = end($this->path);
		}
		
		if (isset($settings['parent_id'])) {
            $parent_id = (int)$settings['parent_id'];
		} else {
            $parent_id = 0;
		}
        
		$this->data['category'] = $this->getCategories($parent_id);
        
		$this->id = 'category';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/category.tpl';
		} else {
			$this->template = 'default/module/category.tpl';
		}
		$this->render();
  	}
	
	protected function getCategories($parent_id=0) {
		$output = '';
		$results = $this->modelCategory->getCategories($parent_id);
		if ($results) { 
			$output .= '<ul class="nt-dd2">';
    		foreach ($results as $result) {	    			
    			$childrens = $this->modelCategory->getCategories($result['category_id']);
                if ($childrens) {
                    $output .= '<li class="hasCategories">';
                } else {
                    $output .= '<li>';
                }
    			
    			if ($this->category_id == $result['category_id']) {
    				$output .= '<a href="'. Url::createUrl("store/category",array("path"=>$result['category_id'])) .'"><b>' . $result['name'] . '</b></a>';
    			} else {
    				$output .= '<a href="'. Url::createUrl("store/category",array("path"=>$result['category_id'])) .'">' . $result['name'] . '</a>';
    			}
    			
    			if ($childrens) {
                    $output .= '<ul>';
    			     foreach ($childrens as $child) {
    			         $output .= '<li><a href="'. Url::createUrl("store/category",array("path"=>$result['category_id'].'_'.$child['category_id'])) .'" title="'.$child['name'].'">'.$child['name'].'</a></li>';
    			     }
			         $output .= '</ul>';
    			}
    			$output .= '</li>'; 
    		}
 
			$output .= '</ul>';
		}
		return $output;
	}		
}