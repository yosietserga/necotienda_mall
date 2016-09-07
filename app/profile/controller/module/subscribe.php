<?php  
class ControllerModuleSubscribe extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/subscribe');
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->id = 'subscribe';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/subscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/subscribe.tpl';
		} else {
			$this->template = 'default/module/subscribe.tpl';
		}
		$this->render();
  	}
    
    public function subscribe() {
        $json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('marketing/contact');
            if (!$this->modelContact->getByEmail($this->request->post)) {
                $data['email'] = $this->request->post['subscribe_email'];
                $this->modelContact->add($data);
            }
            $json['success'] = 1;
            $json['msg'] = $this->language->get('text_success');
		} else {
            $json['error'] = 1;
            $json['msg'] = $this->language->get('error_email');
		}
        $this->load->library('json');
        $this->response->setOutput(Json::encode($json),$this->config->get('config_compression'));
    }
    
    protected function validate() {
        $this->load->library('validar');
        $validate = new Validar;
        if (empty($this->request->post['subscribe_email']) || !$validate->validEmail($this->request->post['subscribe_email'])) {
            return false;
        }
        return true;
    }
}