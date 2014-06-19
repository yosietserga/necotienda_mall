<?php  
class ControllerModuleTwitter extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/twitter');

		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
        
		$this->data['twitter_search'] = ($settings['twitter_search']) ? $settings['twitter_search'] : '';
		$this->data['twitter_search_limit'] = ($settings['twitter_search_limit']) ? $settings['twitter_search_limit'] : 5;
		$this->data['twitter_search_rate'] = ($settings['twitter_search_rate']) ? $settings['twitter_search_rate'] : 15000;
        
		$this->data['twitter_time'] = ($settings['twitter_time']) ? $settings['twitter_time'] : '';
		$this->data['twitter_time_limit'] = ($settings['twitter_time_limit']) ? $settings['twitter_time_limit'] : 5;
		$this->data['twitter_time_refresh'] = ($settings['twitter_time_refresh']) ? $settings['twitter_time_refresh'] : 'false';
		$this->data['twitter_time_mode'] = ($settings['twitter_time_mode']) ? $settings['twitter_time_mode'] : 'user_timeline';
        
        // style files
        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $csspath = str_replace("%theme%",$this->config->get('config_template'),$csspath);
       	} else {
            $csspath = str_replace("%theme%","default",$csspath);
       	}
        if (fopen($csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'),'r')) {
            $styles[] = array('media'=>'all','href'=>$csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'));
        }
        if (count($styles)) {
            $this->data['styles'] = $this->styles = array_merge($this->styles,$styles);
        }
                
		$this->id = 'twitter';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/twitter_home.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/twitter_home.tpl';
		} else {
			$this->template = 'default/module/twitter_home.tpl';
		}
		$this->render();
	}
}
