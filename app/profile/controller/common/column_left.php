<?php  class ControllerCommonColumnLeft extends Controller {
	protected function index() {
            $this->load->helper('widgets');
            $widgets = new NecoWidget($this->registry,$this->Route);
            foreach ($widgets->getWidgets('column_left') as $widget) {
                $settings = (array)unserialize($widget['settings']);
                if ($settings['asyn']) {
                    $url = Url::createUrl("{$settings['route']}",$settings['params']);
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
                        .appendTo('".$settings['target']."');"
                    );
                } else {
                    if (isset($settings['route'])) {
                        if ($settings['autoload']) $this->data['widgets'][] = $widget['name'];
                        $this->children[$widget['name']] = $settings['route'];
                        $this->widget[$widget['name']] = $widget;
                    }
                }
            }
            
		$this->id = 'column_left';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/column_left.tpl')) {
			$this->template = $this->config->get('config_template') . '/common/column_left.tpl';
		} else {
			$this->template = 'default/common/column_left.tpl';
		}
		
		$this->render();
	}
}
