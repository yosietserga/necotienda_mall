<?php  class ControllerCommonFooter2 extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_name'), date('Y', time()));
		$this->id = 'footer';

        $this->scripts = array_merge($this->scripts,$scripts);
        $r_output = $w_output = $s_output = $f_output = "";
        $script_keys = array();
        foreach ($this->scripts as $k => $script) { 
            if (in_array($script['id'],$script_keys)) continue;
            $script_keys[$k] = $script['id'];
            switch($script['method']) {
                case 'ready':
                default:
                    $r_output .= $script['script'];
                    break;
                case 'window':
                    $w_output .= $script['script'];
                    break;
                case 'function':
                    $f_output .= $script['script'];
                    break;
            }
        }
        $this->data['scripts'] = $s_output;
        $this->data['scripts'] .= ($r_output) ? "<script> \n $(function(){".$r_output."}); </script>" : "";
        $this->data['scripts'] .= ($w_output) ? "<script> \n (function($){ $(window).load(function(){ ".$w_output." }); })(jQuery);</script>" : "";
        $this->data['scripts'] .= ($f_output) ? "<script> \n ".$f_output." </script>" : "";
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        $javascripts[] = $jspath."plugins.js";
        $javascripts[] = $jspath."main.js";

        $this->data['javascripts'] = $this->javascripts = array_merge($this->javascripts, $javascripts);
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/footer2.tpl')) {
			$this->template = $this->config->get('config_template') . '/common/footer2.tpl';
		} else {
			$this->template = 'default/common/footer2.tpl';
		}
		
		$this->render();
	}
}