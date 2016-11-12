<?php  
class ControllerCommonNav extends Controller {
	protected function index() {
	   $this->load->auto('store/category');
	   $this->load->auto('content/menu');
       
       $menu = $this->modelMenu->getMainMenu();
       if ($menu['menu_id']) {
           $this->data['links'] = $this->getLinks($menu['menu_id'],0);
       }
       
        $this->id = 'navigation';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/nav.tpl')) {
			$this->template = $this->config->get('config_template') . '/common/nav.tpl';
		} else {
			$this->template = 'default/common/nav.tpl';
		}
		
		$this->render();
	}
    
	protected function getLinks($menu_id,$parent_id=0) {
	    $this->load->auto('content/menu');
		$output = '';
		$results = $this->modelMenu->getLinks($menu_id,$parent_id);
		if ($results) { 
			$output .= '<ul>';
    		foreach ($results as $result) {
                $output .= '<li>';
    			$output .= '<a href="'. Url::rewrite($result['link']) .'" title="'.$result['tag'].'">' . $result['tag'] . '</a>';
    			$output .= $this->getLinks($menu_id,$result['menu_link_id']);
    			$output .= '</li>'; 
    		}
			$output .= '</ul>';
		}
		return $output;
	}	
}
