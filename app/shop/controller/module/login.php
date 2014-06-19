<?php  
class ControllerModuleLogin extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/login');
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
        
        if (!$this->session->has('tokenLogin')) {
            $this->data['tokenLogin'] = md5(rand() . time());
            $this->session->set('tokenLogin', $this->data['tokenLogin']);
        } else {
            $this->data['tokenLogin'] = $this->session->get('tokenLogin');
        }
    	
		$this->id = 'login';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/login.tpl';
		} else {
			$this->template = 'default/module/login.tpl';
		}
		$this->render();
  	}
    
    public function login() {
        $json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $json['success'] = 1;
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
        if (empty($this->request->post['email']) || !$validate->validEmail($this->request->post['email'])) {
            return false;
        }
        if (empty($this->request->post['password'])) {
            return false;
        }
        if ($this->request->post['token'] != $this->session->get('tokenLogin')) {
            return false;
        }
        if (!$this->customer->login($this->request->post['email'],$this->request->post['password'],false)) {
            return false;
        }
        return true;
    }
}