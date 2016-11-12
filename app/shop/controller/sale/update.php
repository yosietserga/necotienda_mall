<?php 
class ControllerSaleUpdate extends Controller { 
    var $error = array();
    
	public function index() {
	   if (!$this->customer->isLogged()) {  
      		$this->session->data['redirect'] = HTTP_HOME . 'index.php?r=sale/update';
	  		$this->redirect(HTTP_HOME . 'index.php?r=account/login');
    	}
        $this->document->title = $this->data['heading_title'] = "Editar Art&iacute;culo";
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('sale/plan');
		$this->load->library('url');
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
                
                $product_id = $this->model_catalog_product->add($this->request->post);
                
                $this->redirect(Url::createUrl("store/product",array(
                    'product_id'=>$product_id,
                    'np'=>'1'
                )));
		}
		
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		$this->load->library('currency');
        
		$this->load->library('url');
		$this->data['Url'] = new Url;
		$this->data['products'] = $this->model_catalog_product->getProductsByCustomerId($this->customer->getId());
		$model = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        
		foreach ($this->modelPlan->getPlans() as $plan) {
    		if (!empty($plan['image']) && file_exists(DIR_IMAGE . $plan['image'])) {
    			$image = NTImage::resizeAndSave($plan['image'], 100, 100);
    		} else {
    			$image = NTImage::resizeAndSave('no_image.jpg', 100, 100);
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
		
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
    	
		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (isset($product_info)) {
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}
		
        $csspath = defined("CDN") ? CDN.CSS : CSS;
            
        $styles[] = array('media'=>'screen','href'=>'http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'neco.form.css');
        $this->styles = array_merge($styles,$this->styles);
        
        // javascript files
        $jspath = defined("CDN") ? CDN.JS : JS;
            
        $javascripts[] = array('place'=>'footer','src'=>"http://code.jquery.com/ui/1.9.2/jquery-ui.js");
        $javascripts[] = array('place'=>'footer','src'=>$jspath."necojs/neco.form.js");
        $javascripts[] = array('place'=>'footer','src'=>$jspath."vendor/ckeditor/ckeditor_basic.js");
        if (count($javascripts)) $this->javascripts = array_merge($this->javascripts, $javascripts);
            
		$scripts = array();
        $scripts[] = array('id'=>'register','method'=>'ready','script'=>
            "CKEDITOR.replace('description');
		    $('#formSale').ntForm();
            var cache = {};
            $('#category_0').autocomplete({
                minLength: 2,
                source: function( request, response ) {
                    var term = request.term;
                    if ( term in cache ) {
                        response( cache[ term ] );
                        return;
                    }
     
                    $.getJSON( '" .Url::createUrl("store/category/callback"). "', request, function( data, status, xhr ) {
                        cache[ term ] = data;
                        response( data );
                        
                    });
                },
                select: function(event, ui) {
                    var category_id = ui.item.id;
                    $('#category0').val(category_id);
                    $('#categoriesWrapper select').remove();
                    drawCategories(category_id);
                }
            });");
        $scripts[] = array('id'=>'functios','method'=>'function','script'=>
            "function drawCategories(category_id) {
                if (typeof category_id != 'undefined') {
                    var idx = $('#categoriesWrapper select:last-child').index() + 1 * 1;
                    $('#categoriesWrapper').append('<select id=\"subcategory_'+ idx +'\" name=\"Categories[]\"></select>');
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
            }
            function setPlan(plan_id,qty_days,qty_images,qty_videos,featured,in_home,price) {
                $('#plan_id').val(plan_id);
                $('#qty_days').val(qty_days);
                
                i=0;
                for(i;i < qty_images;i++) {
                    $('#product_images').append('<input type=\"file\" name=\"files[]\" id=\"image'+ i +'_\" value=\"\" showquick=\"off\" /><div class=\"clear\">&nbsp;</div>');
                }
                
                $('#image0_').attr('required','required');
                
                if (featured) {
                    $('#formSale').append('<input type=\"hidden\" name=\"featured\" value=\"1\" />');
                }
                
                if (in_home) {
                    $('#formSale').append('<input type=\"hidden\" name=\"show_in_home\" value=\"1\" />');
                }
                
                $('#step1').hide();
                $('#step2').fadeIn();
                
            }");
        
        $this->scripts = array_merge($scripts,$this->scripts);
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/sale/update.tpl')) {
			$this->template = $this->config->get('config_template') . '/sale/update.tpl';
		} else {
			$this->template = 'default/sale/update.tpl';
		}
		$this->children = array('common/column_left', 'common/footer', 'common/header');
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
	
    protected function validate() {
        if (!$this->request->post['name']) {
            $this->error['name'] = "Debe ingresar el nombre del art&iacute;lo";
        }
        
        if (empty($this->request->files['files']['name'])) {
            $this->error['image'] = "Debe seleccionar al menos una imagen";
        }
        
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