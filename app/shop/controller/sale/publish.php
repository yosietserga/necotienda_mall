<?php 
class ControllerSalePublish extends Controller { 
    var $error = array();
    
	public function index() {
	   if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect', Url::createUrl('sale/create'));
	  		$this->redirect(Url::createUrl('account/login'));
    	}
        
	    if ($this->customer->isBanned()) {  
      		$this->session->set('redirect', Url::createUrl('sale/create'));
	  		$this->redirect(Url::createUrl('account/banned'));
    	}
        
	    if (!$this->customer->canPublish()) {  
      		$this->session->set('redirect', Url::createUrl('sale/create'));
	  		$this->redirect(Url::createUrl('account/permissions',array('can_publish'=>0)));
    	}
        
        $this->document->title = $this->data['heading_title'] = "Publicar Art&iacute;culo Nuevo";
		$this->load->model('store/category');
		$this->load->model('store/product');
		$this->load->model('sale/plan');
		$this->load->library('image');
        
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
	   		$dom = new DOMDocument;
                $dom->preserveWhiteSpace = false;
                $dom->loadHTML(html_entity_decode($this->request->post['description']));
                $images = $dom->getElementsByTagName('img');
                foreach ($images as $image) {
                    $src = $image->getAttribute('src');
                    
                    if (preg_match('/data:([^;]*);base64,(.*)/',$src)) {
                        $image->removeChild();
                    }
                }

                $this->request->post['description'] = htmlentities($dom->saveHTML());
				$html = html_entity_decode($this->request->post['description']);
				$html = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is','',$html);
				$html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is','',$html);
				$html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is','',$html);
				$html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is','',$html);
				$html = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is','',$html);
				$html = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is','',$html);
				$html = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is','',$html);
				$html = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is','',$html);
				$html = preg_replace('/<noembed\b[^>]*>(.*?)<\/noembed>/is','',$html);
				$this->request->post['description'] = htmlentities($html);
				
                $token = mt_rand(1,999) . $this->customer->getId();
                $keyword = str_replace('.'.$ext,'',$keyword);
                $keyword = $token . "-" . $this->config->get('config_name') . "-" . $this->request->post['name'];
                if($keyword !== mb_convert_encoding( mb_convert_encoding($keyword, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
                    $keyword = mb_convert_encoding($keyword, 'UTF-8', mb_detect_encoding($keyword));
                $keyword = htmlentities($keyword, ENT_NOQUOTES, 'UTF-8');
                $keyword = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $keyword);
                $keyword = html_entity_decode($keyword, ENT_NOQUOTES, 'UTF-8');
                $keyword = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $keyword);
                $keyword = strtolower( trim($keyword, '-') );
                    
                $this->request->post['keyword'] = $keyword . ".html";
                $this->request->post['Images'] = $this->upload();
                
                $product_id = $this->modelProduct->add($this->request->post);
                
                $this->redirect(Url::createUrl("store/product",array(
                    'product_id'=>$product_id,
                    'np'=>'1'
                )));
		}
		
		$this->data['categories'] = $this->modelCategory->getCategories(0);
		
		$this->load->library('currency');
        
		$this->data['products'] = $this->modelProduct->getProductsByCustomerId($this->customer->getId());
		$model = $this->modelProduct->getProduct($this->request->get['product_id']);
        
		foreach ($this->modelPlan->getPlans() as $plan) {
    		if (!empty($plan['image']) && file_exists(DIR_IMAGE . $plan['image'])) {
    			$image = NTImage::resizeAndSave($plan['image'], 50, 50);
    		} else {
    			$image = NTImage::resizeAndSave('no_image.jpg', 50, 50);
    		}
        
            $this->data['plans'][] = array(
                'plan_id'=>$plan['plan_id'],
                'name'=>$plan['name'],
                'price'=>$this->currency->format($this->tax->calculate($plan['price'], $this->config->get('config_taxt_id'), $this->config->get('config_tax'))),
                'image'=>$image,
                'qty_days'=>$plan['qty_days'],
                'qty_images'=>$plan['qty_images'],
                'qty_videos'=>$plan['qty_videos'],
                'featured'=>$plan['featured'],
                'show_in_home'=>$plan['show_in_home'],
                'sort_order'=>$plan['sort_order']
            );
		}
        
		$this->setvar('name',$model,'');
		$this->setvar('model',$model,'');
		$this->setvar('price',$model,'');
		$this->setvar('description',$model,'');
		$this->setvar('stock_status_id',$model,'');
		$this->setvar('quantity',$model,'');
		$this->setvar('weight',$model,'');
		$this->setvar('images',$model,'');
		$this->setvar('properties',$model,'');
		$this->setvar('prices',$model,'');
        
		$this->load->model('localisation/stock_status');
		
		$this->data['stock_statuses'] = $this->modelStock_status->getStockStatuses();
    	
		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (isset($product_info)) {
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}
		
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        $styles[] = array('media'=>'screen','href'=>$csspath.'jquery-ui/jquery-ui.min.css');
        $styles[] = array('media'=>'screen','href'=>$csspath.'neco.form.css');
        
        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $csspath = str_replace("%theme%",$this->config->get('config_template'),$csspath);
       	} else {
            $csspath = str_replace("%theme%","default",$csspath);
       	}
                
        if (fopen($csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'),'r')) {
            $styles[] = array('media'=>'all','href'=>$csspath.str_replace('controller','',strtolower(__CLASS__) . '.css'));
        }
        
        if ($styles) $this->styles = array_merge($styles,$this->styles);
        
        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        $javascripts[] = $jspath."vendor/jquery-ui.min.js";
        $javascripts[] = $jspath."necojs/neco.form.js";
        $javascripts[] = $jspath."vendor/ckeditor/ckeditor.js";
        $javascripts[] = $jspath."plugins.js";
        if (count($javascripts)) $this->javascripts = array_merge($this->javascripts, $javascripts);
            
		$scripts = array();
        $scripts[] = array('id'=>'register','method'=>'ready','script'=>
            "var cache = {};
            $.getJSON( '" .Url::createUrl("store/category/callback"). "', function( data ) {
                $.each(data,function(i,item){
                    var category_id = item.id;
                    var name = item.label;
                    var value = item.id;
                    var label = item.label;
                    $(document.createElement('option')).attr({
                        value:value
                    })
                    .text(label)
                    .appendTo('#category_0');
                });
                $('#category_0').on('change',function(e){
                    $('.Categories').remove();
                    drawCategories($(this).val());
                    $('#category0').val($(this).val());
                });
            });");
        $scripts[] = array('id'=>'functios','method'=>'function','script'=>
            "function drawCategories(category_id) {
                if (typeof category_id != 'undefined' && category_id > 0) {
                    var idx = $('#categoriesWrapper select:last-child').index() + 1 * 1;
                    $('#categoriesWrapper').append('<select id=\"subcategory_'+ idx +'\" name=\"Categories[]\" class=\"Categories\"></select>');
                    $('#subcategory_'+ idx)
                        .load('". Url::createUrl("store/category/subcategories") ."&parent_id=' + category_id,function(data){
                            if (data.length == 0) {
                                $(this).remove();
                            }
                        })
                        .change(function(e){
                            var idx_ = $(this).index();
                            $('#categoriesWrapper select').each(function(){
                                if ($(this).index() > idx_) {
                                    $(this).remove();
                                }
                            });
                            var id_ = $(this).val();
                            drawCategories(id_);
                        });
                }
            }");
        
        $this->scripts = array_merge($scripts,$this->scripts);
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/sale/publish.tpl')) {
			$this->template = $this->config->get('config_template') . '/sale/publish.tpl';
		} else {
			$this->template = 'default/sale/publish.tpl';
		}
		$this->children = array('common/column_left', 'common/footer', 'common/header');
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
    
    public function savelist() {
        $this->load->model('account/list');
        $data = array();
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateList()) {
            if ($this->request->hasPost('list_id')) {
                $this->modelList->update($this->request->getPost('list_id'), $this->request->post);
            } else {
                $data['success'] = $data['list_id'] = $this->modelList->add($this->request->post);
            }
		}
        $this->load->library('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));
    }
	
    public function saveproduct() {
        $this->load->model('store/category');
        $data = array();
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateProduct()) {
		  
	   		$dom = new DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->loadHTML(html_entity_decode($this->request->post['description']));
            $images = $dom->getElementsByTagName('img');
            foreach ($images as $image) {
                $src = $image->getAttribute('src');
                if (preg_match('/data:([^;]*);base64,(.*)/',$src)) {
                    $image->removeChild();
                }
            }

            $this->request->post['description'] = htmlentities($dom->saveHTML());
			$html = html_entity_decode($this->request->post['description']);
			$html = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is','',$html);
			$html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is','',$html);
			$html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is','',$html);
			$html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is','',$html);
			$html = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is','',$html);
			$html = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is','',$html);
			$html = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is','',$html);
			$html = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is','',$html);
			$html = preg_replace('/<noembed\b[^>]*>(.*?)<\/noembed>/is','',$html);
			$this->request->post['description'] = htmlentities($html);
				
            if ($this->request->hasPost('product_id')) {
                $token   = mt_rand(1,999) . $this->customer->getId();
                $keyword = str_replace('.'.$ext,'',$keyword);
                $keyword = $this->config->get('config_name') ."-". $this->request->post['name']  ."-". $token;
                if($keyword !== mb_convert_encoding( mb_convert_encoding($keyword, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
                    $keyword = mb_convert_encoding($keyword, 'UTF-8', mb_detect_encoding($keyword));
                $keyword = htmlentities($keyword, ENT_NOQUOTES, 'UTF-8');
                $keyword = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $keyword);
                $keyword = html_entity_decode($keyword, ENT_NOQUOTES, 'UTF-8');
                $keyword = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $keyword);
                $keyword = strtolower( trim($keyword, '-') );
                $this->request->post['keyword'] = $keyword . ".html";
            
                $this->modelProduct->update($this->request->getPost('product_id'), $this->request->post);
            } else {
                $data['success'] = $data['product_id'] = $this->modelProduct->add($this->request->post);
            }
		  /*
            - obtener y validar las imágenes            
          */
            $data['attributes'] = $this->modelCategory->getAttributes($this->request->post['Categories'][count($this->request->post['Categories']) - 1]);
		} else {
            $data['error'] = 1;
            $data['msg'] = (isset($this->error['name'])) ? $this->error['name'] : '';
		}
        $this->load->library('json');
		$this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));
    }
	
    protected function validateList() {
        if (!$this->request->post['name']) {
            $this->error['name'] = "Debe ingresar el nombre del art&iacute;lo";
        }
        return true;
        return ($this->error);
    }
	
    protected function validateProduct() {
        if (!$this->request->post['name']) {
            $this->error['name'] = "Debe ingresar el nombre del art&iacute;lo";
        }
        
        if (empty($this->request->files['files']['name'])) {
            $this->error['image'] = "Debe seleccionar al menos una imagen";
        }
        return true;
        return ($this->error);
    } 
	
	protected function upload() {
		$dir = "data/" . date('m-Y');
		$directory = DIR_IMAGE . $dir;
		if (!is_dir($directory)) {
			mkdir($directory,0777);
		}
		$files = $this->request->files['files'];
		$i = 0;
        foreach ($files as $key => $file) {
            $arr[$i] = array(
                'name'  =>$files['name'][$i],
                'tmp_name'=>$files['tmp_name'][$i],
                'size'  =>$files['size'][$i],
                'type'  =>$files['type'][$i],
                'error' =>$files['error'][$i]
            );
            $i++;
        }
        
        $files = $arr;
		foreach ($files as $key => $file) {
            if (empty($file['name'])) continue;
        	$name = $file['name'];
            $ext  = strtolower(substr($file['name'],(strrpos($file['name'],'.') + 1)));
            $tmp_name = $file['tmp_name'];
            $size = $file['size'];
            $type = $file['type'];
            $error = $file['error'];
				
			$token = uniqid() . strtotime('d-m-Y H:i:s') . $this->customer->getId() . $key;
				
            $name = str_replace('.'.$ext,'',$name);
            $name = $token ."-". $this->config->get('config_name') . "-" . $this->request->post['name'];
            if($name !== mb_convert_encoding( mb_convert_encoding($name, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
                $name = mb_convert_encoding($name, 'UTF-8', mb_detect_encoding($name));
            $name = htmlentities($name, ENT_NOQUOTES, 'UTF-8');
            $name = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $name);
            $name = html_entity_decode($name, ENT_NOQUOTES, 'UTF-8');
            $name = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $name);
            $name = strtolower( trim($name, '-') );
                    
            if ($size > 2000000) {
                $error = $this->language->get('error_file_size') . __LINE__;
  		    }
        			
        	$mime_types_allowed = array(
                'image/jpg',
                'image/jpeg',
        		'image/pjpeg',
        		'image/png',
        		'image/x-png',
        		'image/gif'
       		);
                    
            if(!in_array(strtolower($type), $mime_types_allowed)) {
                $return['error'] = 1;
                $return['msg'] = "Archivo no permitido, debe seleccionar un archivo .CSV o .TXT";
            }
                    
 			$extension_allowed = array(
                'jpg',
                'jpeg',
        		'pjpeg',
        		'png',
        		'gif'
  		    );
                    
            if(!in_array(strtolower($ext), $extension_allowed)) {
                $return['error'] = 1;
                $return['msg'] = "Archivo no permitido, solo se permiten im&aacute;genes";
            }
                    
            if($size == 0 && !$return['error']) {
                $return['error'] = 1;
                $return['msg'] = "El archivo est&aacute; vac&iacute;o";
            }
                    
            if(($size / 1024 / 1024) > 2 && !$return['error']) {
                $return['error'] = 1;
                $return['msg'] = "El tama&ntilde;o del archivo es muy grande, solo se permiten archivos hasta 2MB";
            }
                    
            if ($error > 0 && !$return['error']) {
                $return['error'] = 1;
                $return['msg'] = $error;
            }
                  	
            if ($error == UPLOAD_ERR_INI_SIZE) $error = $this->language->get('UPLOAD_ERR_INI_SIZE') . __LINE__;
            if ($error == UPLOAD_ERR_FORM_SIZE) $error = $this->language->get('UPLOAD_ERR_FORM_SIZE') . __LINE__;
            if ($error == UPLOAD_ERR_PARTIAL) $error = $this->language->get('UPLOAD_ERR_PARTIAL') . __LINE__;
            if ($error == UPLOAD_ERR_NO_FILE) $error = $this->language->get('UPLOAD_ERR_NO_FILE') . __LINE__;
            if ($error == UPLOAD_ERR_NO_TMP_DIR) $error = $this->language->get('UPLOAD_ERR_NO_TMP_DIR') . __LINE__;
            if ($error == UPLOAD_ERR_CANT_WRITE) $error = $this->language->get('UPLOAD_ERR_CANT_WRITE') . __LINE__;
            if ($error == UPLOAD_ERR_EXTENSION) $error = $this->language->get('UPLOAD_ERR_EXTENSION') . __LINE__;
                    
            if (!$error) {
                $filename = basename($name.'.'.$ext);
            	if (@move_uploaded_file($tmp_name, $directory . '/' . $filename)) {
                    $_files[] = array(
					   'name'=>$dir . "/" . $name.'.'.$ext,
					   'ext' =>$ext,
					   'size'=>$size,
					   'type'=>$type,
					   'response'=>$return
                    );
      		    } else {
            	   $error = $this->language->get('error_uploaded');
            	}
           	} else {
           	    return $error;
           	}
        }
		return $_files;
	}
}