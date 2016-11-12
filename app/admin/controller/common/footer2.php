<?php  
class ControllerCommonFooter2 extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_name'), date('Y', time()));
		
		$this->id = 'footer';
        $this->template = 'common/footer2.tpl';
		
		$this->render();
	}
}