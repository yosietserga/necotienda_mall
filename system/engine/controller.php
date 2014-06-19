<?php abstract class Controller {
	protected $registry;	
	protected $id;
	protected $template;
	protected $children = array();
	protected $data     = array();
	protected $widget   = array();
	protected $output;
    protected $cacheId  = null;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
    /**
     * Controller::setvar()
     * Asigna un valor a una variable 
     * @param string $varname
     * @param array $model
     * @return mixed $varname
     * */
	public function setvar($varname, $model = null,$default=null) {
	   $config = $this->registry->get('config');
		if (isset($this->request->post[$varname])) {
			$this->data[$varname] = $this->request->post[$varname];
		} elseif (isset($model)) {
			$this->data[$varname] = $model[$varname];
		} elseif (isset($this->request->get[$varname])) {
			$this->data[$varname] = $this->request->get[$varname];
        } elseif (isset($config) && $config->get($varname)) {
            $this->data[$varname] = $config->get($varname);
		} elseif (isset($default)) {
			$this->data[$varname] = $default;
		} else {
			$this->data[$varname] = '';
		}
        return $this->data[$varname];
	}
    
    /**
     * Controller::setImageVar
     * Asigna el valor del campo de imagen del formulario
     * @param string $varname el nombre de la variable a definir
     * @param array $group el nombre del grupo de estilos
     * @return void
     * */
    public function setImageVar($varname,$group) {
        $this->load->model("image");
        $this->load->model("style/style");
        $model = $this->model_style_style->getStyles($group);
        
		if (!empty($model['background-image']) && file_exists(DIR_IMAGE . $model['background-image'])) {
			$this->data[$varname] = NTImage::resizeAndSave($model['background-image'], 100, 100);
            $this->data["_".$varname] = $model['background-image'];
		} else {
			$this->data[$varname] = NTImage::resizeAndSave('sin_fondo.jpg', 100, 100);
            $this->data["_".$varname] = "";
		}
    }
    
    
    /**
     * Controller::setStyleVar
     * Asigna el valor del campo de una propiedad css
     * @param string $varname el nombre de la variable a definir
     * @param array $group el nombre del grupo de estilos
     * @return void
     * */
    public function setStyleVar($varname,$group,$prefix = null) {
        $this->load->model("style/style");
        $model = $this->model_style_style->getStyles($group);
        
		if (!empty($model[$varname])) {
            if ($prefix)
                $this->data[$prefix."_".str_replace("-","_",$varname)] = $model[$varname];
            else
                $this->data[$group."_".str_replace("-","_",$varname)] = $model[$varname];
		} else {
            if ($prefix)
                $this->data[$prefix."_".str_replace("-","_",$varname)] = $model[$varname];
            else
                $this->data[$group."_".str_replace("-","_",$varname)] = $model[$varname];
		}
    }
    	
	public function getvar($varname) {
	   return isset($this->data[$varname]) ? $this->data[$varname] : false;
	}
	
	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url) {
		header('Location: ' . str_replace('&amp;', '&', $url));
        //echo "<script> window.location = '".str_replace('&amp;', '&', $url)."'; </script>";
		exit();
	}
	
	protected function render($return = false) {
        $cache = $this->registry->get('cache');
        $user = $this->registry->get('user');
       
        if (isset($this->cacheId) && !empty($this->cacheId) && isset($user) && !$user->islogged()) {
            $cached = $cache->get($this->cacheId);
        }
       
       if (!isset($cached)) {
    		foreach ($this->children as $key => $child) {
    			$action = new Action($child);
    			$file   = $action->getFile();
    			$class  = $action->getClass();
    			$method = $action->getMethod();
    			$args   = $action->getArgs();
    		
    			if (file_exists($file)) {
    				require_once($file);
    				$controller = new $class($this->registry);
    				$controller->index($this->widget[$key]);
    				if (!is_numeric($key)) {
    				    $this->data[$key."_hook"] = $key;
    				    $this->data[$key."_code"] = $controller->output;
    				} else {
    				    $this->data[$controller->id] = $controller->output;
    				}
    			} else {
    				exit('Error: Could not load controller ' . $child . '!');
    			}
    		}
    		
            if ($return) {
                $r = $this->fetch($this->template);
                if (isset($this->cacheId) && !empty($this->cacheId)) {
                    $cache->set($this->cacheId,$r);
                }
    			return $r;
    		} else {
    			$this->output = $this->fetch($this->template);
    		}
       } else {
            if ($return) {
    			return $cached;
    		} else {
    			$this->output = $cached;
    		}
       }
	}
	
	protected function fetch($filename) {
		$file = DIR_TEMPLATE . $filename;
		if (file_exists($file)) {
            $this->data['Config'] = $this->registry->get('config');
            $this->data['Language'] = $this->registry->get('language');
            $this->data['Url']      = new Url($this->registry);
            
			extract($this->data);
      		ob_start();
	  		require($file);
	  		$content = ob_get_contents();
      		ob_end_clean();
            
            foreach ($this->children as $key => $child) {
                if (!is_numeric($key)) $content = str_replace('{%'. $this->data[$key."_hook"] .'%}',$this->data[$key."_code"],$content);
            }
            
            $content = str_replace("\n","",$content);
            $content = str_replace("\r","",$content);
            $content = preg_replace('/\s{2,}/', "",$content);
            $content = preg_replace('/\n\s*\n/', "\n", $content);
            
      		return $content;
    	} else {
      		exit('Error: Could not load template ' . $file . '!');
    	}
	}
    
}