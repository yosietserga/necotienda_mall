<?php  class ControllerAccountFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');
        $this->load->library('user');
        $this->data['config_js_security'] = $this->config->get('config_js_security');
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_name'));
		$this->id = 'footer';
        
            $this->load->helper('widgets');
            $widgets = new NecoWidget($this->registry,$this->Route);
            foreach ($widgets->getWidgets('footer') as $widget) {
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
            
        // SCRIPTS
        $scripts[] = array('id'=>'search','method'=>'function','script'=>
        "function moduleSearch(keyword,category_id,zone_id) {
            var url = '". HTTP_HOME ."buscar/';
            var form = $(keyword).closest('form');
            
            url += $(keyword).val()
                .replace(/_/g,'-')
                .replace('+','-')
                .replace(/\s+/g,'-');
            
            if ($('#'+ $(keyword).attr('id').replace('Keyword','Category')).val().length > 0) {
                url += '_Cat_'+ $('#'+ $(keyword).attr('id').replace('Keyword','Category'))
                    .val()
                    .replace(/_/g,'-')
                    .replace('+','-')
                    .replace(/\s+/g,'-');
            }
            
            if ($('#'+ $(keyword).attr('id').replace('Keyword','Zone')).val().length > 0) {
                url += '_Estado_'+ $('#'+ $(keyword).attr('id').replace('Keyword','Zone'))
                    .val()
                    .replace(/_/g,'-')
                    .replace('+','-')
                    .replace(/\s+/g,'-');
            }
            
            window.location = url;
        }
        function sort(e,a) {
            if (a.length > 0) {
                $('#products').html('<img src=\"". HTTP_IMAGE ."loader.gif\" alt=\"Cargando...\" />');
                $('#products').load(a);
            }
        }");
        
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
        
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_PROFILE_JS;
        foreach ($this->javascripts as $key => $js) {
            if (fopen($js,'r')) {
                $f_output .= file_get_contents($js);
            } else {
                echo "Error: No se pudo cargar el archivo $js.<br /> \n";
            }
            unset($this->javascripts[$key]);
        }
		if (file_exists(str_replace('%theme%',$this->config->get('config_template'),DIR_THEME_JS) . 'theme.js')) {
            $f_output .= file_get_contents(str_replace('%theme%',$this->config->get('config_template'),DIR_THEME_JS) . 'theme.js');
		}
        
        $this->data['scripts'] = $s_output;
        $this->data['scripts'] .= ($r_output) ? "<script> \n $(function(){".$r_output."}); </script>" : "";
        $this->data['scripts'] .= ($w_output) ? "<script> \n (function($){ $(window).load(function(){ ".$w_output." }); })(jQuery);</script>" : "";
        $this->data['scripts'] .= ($f_output) ? "<script> \n ".$f_output." </script>" : "";
        
        // javascript files
        if ($this->user->getId()) {
            $javascripts[] = HTTP_ADMIN ."js/front/admin.js";
            
            if ($this->request->hasQuery('theme_editor') && $this->request->hasQuery('theme_id') && (int)$this->request->getQuery('theme_id') > 0) {
                $javascripts[] = $jspath."vendor/jquery-ui.min.js";
                $javascripts[] = $jspath."necojs/neco.css.js";
                $javascripts[] = $jspath."necojs/neco.colorpicker.js";
                $javascripts[] = $jspath."necojs/neco.tips.js";
                $javascripts[] = HTTP_ADMIN ."js/front/theme_editor.js";
            }
        }
        
        if ($javascripts) $this->data['javascripts'] = $this->javascripts = array_merge($this->javascripts, $javascripts);
        
		$this->template = 'default/account/footer.tpl';
		
        $this->data['google_analytics_code'] = $this->config->get('google_analytics_code');
        $this->data['live_client_id'] = $this->config->get('social_live_client_id');
        $this->data['facebook_app_id'] = $this->config->get('social_facebook_app_id');
        $this->data['google_client_id'] = $this->config->get('social_google_client_id');
        $this->data['twitter_oauth_token_secret'] = $this->config->get('social_twitter_oauth_token_secret');
		
		$this->render();
	}
}
