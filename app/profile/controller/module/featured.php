<?php  
class ControllerModulefeatured extends Controller {
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
    	$limit = ((int)$settings['limit']) ? (int)$settings['limit'] : 4;
		$this->prefetch($limit);
        
		$this->id = 'featured';

		if ($this->config->get('featured_position') == 'home') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/store/products.tpl')) {
				$this->template = $this->config->get('config_template') . '/store/products.tpl';
			} else {
				$this->template = 'default/store/products.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/featured.tpl')) {
				$this->template = $this->config->get('config_template') . '/module/featured.tpl';
			} else {
				$this->template = 'default/module/featured.tpl';
			}
		}
		
		$this->render();
	}
    
    public function home() {
        $this->prefetch();
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/store/products.tpl')) {
			$this->template = $this->config->get('config_template') . '/store/products.tpl';
		} else {
			$this->template = 'default/store/products.tpl';
		}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }
    
    protected function prefetch($limit) {
		$this->language->load('module/featured');
		$this->load->model('store/product');
		$results = $this->modelProduct->getFeaturedProducts($this->config->get('featured_limit'));
        $this->load->auto('store/review');
      	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_see_product'] = $this->language->get('button_see_product');
		$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
		$this->data['products'] = array();
			
        list($dia,$mes,$ano) = explode('-',date('d-m-Y'));
        $l = ((int)$this->config->get('config_new_days') > 30) ? 30 : $this->config->get('config_new_days');
        if ( ($dia = $dia - $l) <= 0) {
            $dia = $dia + 30;
            if ($dia <= 0) $dia = 1;
            $mes = $mes - 1;
            if ($mes <= 0) {
                $mes = $mes + 12;
                $ano = $ano - 1;
            }
        }
		foreach ($results as $key => $result) {
		    $image = !empty($result['image']) ? $result['image'] : 'no_image.jpg';

			if ($this->config->get('config_review')) {
				$rating = $this->modelReview->getAverageRating($result['product_id']);	
			} else {
				$rating = false;
			}

			$special = false;
			$discount = $this->modelProduct->getProductDiscount($result['product_id']);
			
			if ($discount) {
				$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				$special = $this->modelProduct->getProductSpecial($result['product_id']);
				if ($special) {
					$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
				}						
			}
						
			$options = $this->modelProduct->getProductOptions($result['product_id']);
			
			if ($options) {
				$add = Url::createUrl('store/product',array('product_id'=>$result['product_id']));
			} else {
				$add = Url::createUrl('checkout/cart',array('product_id'=>$result['product_id']));
			}
            
            list($pdia,$pmes,$pano) = explode('-',date('d-m-Y',strtotime($result['created'])));
            
            if ($special) {
                $sticker = '<b class="oferta"></b>';
            } elseif ($discount) {
                $sticker = '<b class="descuento"></b>';
            } elseif (strtotime($dia."-".$mes."-".$ano) <= strtotime($pdia."-".$pmes."-".$pano)) {
                $sticker = '<b class="nuevo"></b>';
            } else {
                $sticker = "";
            }
            
			$this->load->auto('image');
			$this->data['products'][] = array(
				'product_id'    => $result['product_id'],
				'name'    		=> $result['name'],
				'model'   		=> $result['model'],
				'overview'   	=> $result['meta_description'],
				'rating'  		=> $rating,
				'stars'   		=> sprintf($this->language->get('text_stars'), $rating),
				'price'   		=> $price,
				'sticker'   	=> $sticker,
				'options'   	=> $options,
				'special' 		=> $special,
				'image'   		=> NTImage::resizeAndSave($image, 38, 38),
				'lazyImage'   		=> NTImage::resizeAndSave('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
				'thumb'   		=> NTImage::resizeAndSave($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
				'href'    		=> Url::createUrl('store/product',array('product_id'=>$result['product_id'])),
				'add'    		=> $add,
				'created'    	=> $result['created']
			);
		}
        
		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = true;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = true;
		} else {
			$this->data['display_price'] = false;
		}
    }
    
    public function carousel() {
        $json = array();
        $this->load->auto("store/product");
        $this->load->auto('image');
        $this->load->auto('json');
        
        $json['results'] = $this->modelProduct->getFeaturedProducts(isset($_GET['limit']) ? $_GET['limit'] : 40);
        $width  = isset($_GET['width']) ? $_GET['width'] : 80;
        $height = isset($_GET['height']) ? $_GET['height'] : 80;
        foreach ($json['results'] as $k => $v) {
            if (!file_exists(DIR_IMAGE . $v['image'])) $json['results'][$k]['image'] = HTTP_IMAGE ."no_image.jpg";
            $json['results'][$k]['thumb'] = NTImage::resizeAndSave($v['image'], $width, $height);
            $json['results'][$k]['href'] = Url::createUrl('store/product',array('product_id'=>$json['results'][$k]['product_id']));
            $json['results'][$k]['price'] = $this->currency->format($this->tax->calculate($v['price'], $v['tax_class_id'], $this->config->get('config_tax')));
        }
        
        if (!count($json['results'])) $json['error'] = 1;
        
        $this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }
}
