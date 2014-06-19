<?php    
/**
 * ControllerSaleCustomer
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerStyleEditor extends Controller { 
	private $error = array();
  
  	/**
  	 * ControllerStyleBackgrounds::index()
  	 * 
     * @see Load
     * @see Document
     * @see Language
     * @see getList
  	 * @return void 
  	 */
  	public function index() {
        $this->load->library('url');
        $this->data['Url'] = new Url;
        
        $f = str_replace("-","/",$this->request->get['f']);
        
        if (isset($this->request->get['tpl'])) {
            $this->data['template'] = $template = $this->request->get['tpl'];
        } else {
            $this->data['template'] = $template = $this->config->get('config_template');
        }
        
        if ($this->request->get['t'] == 'css') {
            if (is_dir(DIR_THEME_ASSETS . $template .'/css/')) {
                $folder = DIR_THEME_ASSETS . $template .'/css/';
            } else {
                $folder = DIR_THEME_ASSETS .'default/css/';
           	}
        } elseif ($this->request->get['t'] == 'js') {
            if (is_dir(DIR_THEME_ASSETS . $template .'/js/')) {
                $folder = DIR_THEME_ASSETS . $template .'/js/';
           	} else {
                $folder = DIR_THEME_ASSETS . 'default/js/';
           	}
        } elseif ($this->request->get['t'] == 'tpl') {
            if (file_exists(DIR_CATALOG . 'view/theme/' . $template .'/common/home.tpl')) {
                $folder = DIR_CATALOG . 'view/theme/' . $template .'/';
           	} else {
                $folder = DIR_CATALOG . 'view/theme/default/';
           	}
        }
            
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $fopen = fopen($folder . $f,'w+');
            fputs($fopen,html_entity_decode($this->request->post['code']));
            fclose($fopen);
			$this->session->data['success'] = $this->language->get('text_success');
		}
        
  		if (file_exists(DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/common/home.tpl')) {
            $folderTPL = DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/';
    	} else {
    		$folderTPL = DIR_CATALOG . 'view/theme/default/';
    	}
        $directories = glob($folderTPL . "*", GLOB_ONLYDIR);
		$this->data['templates'] = array();
		foreach ($directories as $key => $directory) {
			$this->data['views'][$key]['folder'] = basename($directory);
            $files = glob($directory . "/*.tpl", GLOB_NOSORT);
            foreach ($files as $k => $file) {
    			$this->data['views'][$key]['files'][$k] = str_replace("\\","/",$file) ;
    		}
		}
        
        unset($directories,$files);
  		if (is_dir(DIR_THEME_ASSETS . $this->config->get('config_template') . '/css/')) {
            $folderCSS = DIR_THEME_ASSETS . $this->config->get('config_template') . '/css/';
    	} else {
    		$folderCSS = DIR_THEME_ASSETS . 'default/css/';
    	}
  		if (file_exists($folderCSS . 'theme.css')) {
            $files = glob($folderCSS . "*.css");
    		$this->data['styles'] = array();
    		foreach ($files as $key => $file) {
    			$this->data['styles'][] = basename($file);                
    		}
            
            unset($directories,$files);
            $directories = glob($folderCSS . "*", GLOB_ONLYDIR);
            if ($directories) {
                foreach ($directories as $ky => $directory) {
                    $files = glob($directory . "/*.css", GLOB_NOSORT);
                    if ($files) {
                        foreach ($files as $k => $file) {
                            $this->data['styles'][] = basename($directory) ."/". basename($file);
                        }
            		}
                }
            }
		}
        
        unset($directories,$files);
  		if (is_dir(DIR_THEME_ASSETS . $this->config->get('config_template') . '/js/')) {
            $folderJS = DIR_THEME_ASSETS . $this->config->get('config_template') . '/js/';
    	} else {
    		$folderJS = DIR_THEME_ASSETS . 'default/js/';
    	}
  		if (file_exists($folderJS . 'theme.js')) {
            $files = glob($folderJS . "*.js");
    		$this->data['javascripts'] = array();
    		foreach ($files as $key => $file) {
    			$this->data['javascripts'][] = basename($file);                
    		}
            
            unset($directories,$files);
            $directories = glob($folderJS . "*", GLOB_ONLYDIR);
            if ($directories) {
                foreach ($directories as $ky => $directory) {
                    $files = glob($directory . "/*.js", GLOB_NOSORT);
                    if ($files) {
                        foreach ($files as $k => $file) {
                            $this->data['javascripts'][] = basename($directory) ."/". basename($file);
                        }
            		}
                }
            }
		}
        
        $this->data['action'] = Url::createAdminUrl("style/editor",array(
            't'=>$this->request->getQuery('t'),
            'tpl'=>$this->request->getQuery('tpl'),
            'f'=>$this->request->getQuery('f'),
            'menu'=>'apariencia'
        ));
        $this->data['cancel'] = Url::createAdminUrl("style/editor") ."&menu=apariencia";
        $this->data['msg'] = "<b>Haz click sobre un archivo de la izquierda para comenzar a editar</b>";
        
        if ($this->request->get['f']) {
            if (file_exists($folder . $f)) {
                $this->data['code']     = file_get_contents($folder . $f);
                $this->data['filename'] = $f;
            } else {
                $this->data['msg'] = "<b>El archivo no existe.</b>";
                $this->data['error'] = true;
            }
        } else {
            $this->data['error'] = true;
        }
        
        // javascript files
        $javascripts[] = "js/vendor/ace/src-min/ace.js";
        $javascripts[] = "js/vendor/ace/src-min/mode-html.js";
        $javascripts[] = "js/vendor/ace/src-min/mode-css.js";
        $javascripts[] = "js/vendor/ace/src-min/mode-javascript.js";
        $javascripts[] = "js/vendor/ace/src-min/mode-php.js";
        
        $this->javascripts = array_merge($javascripts,$this->javascripts);
        
        $scripts[] = array('id'=>'editorScripts','method'=>'ready','script'=>
            "");
        
        $scripts[] = array('id'=>'editorFunctions','method'=>'function','script'=>
            "function loadFile(filepath) {
                $.getJSON('". Url::createAdminUrl('style/editor/file') ."&f=' + encodeURIComponent(filepath),function(response){
                    $('#code').text(response.code);
                    $('#editor').text(response.syntax);
                    $('h2').text(response.filename);
                    var editor = ace.edit('editor');
                    editor.getSession().setValue(response.code);
                });
            }");
        
        //TODO: mostrar los productos al scrolldown para no colapsar el navegador cuando se listan todos los productos
        $scripts[] = array('id'=>'form','method'=>'ready','script'=>
            "var form_clean = $('#form').serialize();  
            
            window.onbeforeunload = function (e) {
                var form_dirty = $('#form').serialize();
                if(form_clean != form_dirty) {
                    return 'There is unsaved form data.';
                }
            };
            
            $('.sidebar .tab').on('click',function(){
                $(this).closest('.sidebar').addClass('show').removeClass('hide').animate({'right':'0px'});
            });
            $('.sidebar').mouseenter(function(){
                clearTimeout($(this).data('timeoutId'));
            }).mouseleave(function(){
                var e = this;
                var timeoutId = setTimeout(function(){
                    if ($(e).hasClass('show')) {
                        $(e).removeClass('show').addClass('hide').animate({'right':'-400px'});
                    }
                }, 600);
                $(this).data('timeoutId', timeoutId); 
            });");
            
        $this->data['scripts'] = $this->scripts = array_merge($scripts,$this->scripts);
        
		$this->template = 'style/editor.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
    public function file() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache");
        header("Content-type: application/json");
        $data = array();
        if ($this->request->get['f'] && file_exists($this->request->get['f'])) {
            $data['code'] = file_get_contents($this->request->get['f']);
            $data['syntax'] = htmlentities($data['code']);
            $data['syntax'] = str_replace("&gt;",">",$data['code']);
            $data['syntax'] = str_replace("&quote;","\"",$data['code']);
            $data['filename'] = basename($this->request->get['f']);
        }
        $this->load->auto('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));  
    }
}
