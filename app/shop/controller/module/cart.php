<?php 
class ControllerModuleCart extends Controller { 
	protected function index($widget=null) {
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
		$this->language->load('module/cart');
		
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
    	
		$this->data['view'] = Url::createUrl("checkout/cart");
		
		$this->data['products'] = array();
		
    	foreach ($this->cart->getProducts() as $result) {
        	$option_data = array();

        	foreach ($result['option'] as $option) {
          		$option_data[] = array(
            		'name'  => $option['name'],
            		'value' => $option['value']
          		);
        	}
			
      		$this->data['products'][] = array(
        		'key' 		 => $result['key'],
        		'name'       => $result['name'],
				'option'     => $option_data,
        		'quantity'   => $result['quantity'],
				'stock'      => $result['stock'],
				'price'      => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'href'       => Url::createUrl("store/product",array("product_id"=>$result['product_id'])),
      		);
    	}
	
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('checkout/extension');
		
		$sort_order = array(); 
		
		$results = $this->modelExtension->getExtensions('total');
		
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}
		
		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $total_data);
		
    	$this->data['totals'] = $total_data;
		
        $scripts[] = array(
                'id'=>$module['code'],
                'method'=>'function',
                'script'=>"function getUrlParam(name) {
                  var name = name.replace(/[\[]/,\"\\\[\").replace(/[\]]/,\"\\\]\");
                  var regexS = \"[\\?&]\"+name+\"=([^&#]*)\";
                  var regex = new RegExp(regexS);
                  var results = regex.exec(window.location.href);
                  if (results == null) {
                    return \"\";
                  } else {
                    return results[1];
                  }
                }");
        $scripts[] = array('id'=>$module['code'],
                'method'=>'function',
                'script'=>"$('.cartRemove').live('click', function () {
            		if (!confirm('<?php echo $text_confirm; ?>')) {
            			return false;
            		}
            		$(this).removeClass('cartRemove').addClass('cartRemoveLoading');
            		$.ajax({
            			type: 'post',
            			url: 'index.php?route=module/cart/callback',
            			dataType: 'html',
            			data: 'remove=' + this.id,
            			success: function (html) {
            				$('#module_cart .content').html(html);
            				if (getUrlParam('r').indexOf('checkout') != -1) {
            					window.location.reload();
            				}
            			}
            		});
            	});");
                
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->data['ajax'] = $settings['cart_ajax'];
		
		$this->id = 'cart';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/module/cart.tpl';
		} else {
			$this->template = 'default/module/cart.tpl';
		}
		
		$this->render();
	}
		
	public function callback() {
		$this->language->load('module/cart');

		
		
		$this->session->clear('shipping_methods');
		$this->session->clear('shipping_method');
		$this->session->clear('payment_methods');
		$this->session->clear('payment_method');	
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			
			if (isset($this->request->post['remove'])) {
	    		$result = explode('_', $this->request->post['remove']);
          		$this->cart->remove(trim($result[1]));
      		} else {
				if (isset($this->request->post['option'])) {
					$option = $this->request->post['option'];
				} else {
					$option = array();	
				}
				
      			$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
			}
		}
				
		$output = '<table cellpadding="2" cellspacing="0" style="width: 100%;">';
		
		if ($this->cart->getProducts()) {
		
    		foreach ($this->cart->getProducts() as $product) {
      			$output .= '<tr>';
        		$output .= '<td width="1" valign="top" align="left"><span class="cart_remove" id="remove_ ' . $product['key'] . '" />&nbsp;</span></td><td width="1" valign="top" align="right">' . $product['quantity'] . '&nbsp;x&nbsp;</td>';
        		$output .= '<td align="left" valign="top"><a href="' . Url::createUrl("store/product",array("product_id"=>$product['product_id'])) . '">' . $product['name'] . '</a>';
          		$output .= '<div>';
	            
				foreach ($product['option'] as $option) {
            		$output .= ' - <small style="color: #999;">' . $option['name'] . ' ' . $option['value'] . '</small><br />';
	            }
				
				$output .= '</div></td>';
				$output .= '</tr>';
      		}
			
			$output .= '</table>';
    		$output .= '<br />';
    		
    		$total = 0;
			$taxes = $this->cart->getTaxes();
			 
			$this->load->model('checkout/extension');
			
			$sort_order = array(); 
			
			$view = Url::createUrl("checkout/cart");
			$checkout = Url::createUrl("checkout/shipping");
			
			$results = $this->modelExtension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				$this->load->model('total/' . $result['key']);

				$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
			}
			
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
      			$sort_order[$key] = $value['sort_order'];
    		}

    		array_multisort($sort_order, SORT_ASC, $total_data);
    	    		
    		$output .= '<table cellpadding="0" cellspacing="0" align="right" style="display:inline-block;">';
      		foreach ($total_data as $total) {
      			$output .= '<tr>';
		        $output .= '<td align="right"><span class="cart_module_total"><b>' . $total['title'] . '</b></span></td>';
		        $output .= '<td align="right"><span class="cart_module_total">' . $total['text'] . '</span></td>';
      			$output .= '</tr>';
      		}
      		$output .= '</table>';
      		$output .= '<div style="padding-top:5px;text-align:center;clear:both;"><a href="' . $view . '">' . $this->language->get('text_view') . '</a> | <a href="' . $checkout . '">' . $this->language->get('text_checkout') . '</a></div>';
		} else {
			$output .= '<div style="text-align: center;">' . $this->language->get('text_empty') . '</div>';
		}
		
		$this->response->setOutput($output, $this->config->get('config_compression'));
	} 	
}
