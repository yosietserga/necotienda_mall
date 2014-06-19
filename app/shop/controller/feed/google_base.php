<?php 
class ControllerFeedGoogleBase extends Controller {
	public function index() {
		if ($this->config->get('google_base_status')) { 
			$output  = '<?xml version="1.0" encoding="UTF-8" ';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            $output .= '<channel>';
			$output .= '<title>' . $this->config->get('config_name') . '</title>'; 
			$output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '<link>' . HTTP_HOME . '</link>';
			
			$this->load->model('store/category');
			
			$this->load->model('store/product');
			
			$products = $this->modelProduct->getProducts();
			
			foreach ($products as $product) {
				if ($product['description']) {
					$output .= '<item>';
					$output .= '<title>' . html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8') . '</title>';
					$output .= '<link>' . Url::createUrl("store/product") . '&amp;product_id=' . $product['product_id'] . '</link>';
					$output .= '<description>' . $product['description'] . '</description>';
					$output .= '<g:brand>' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . '</g:brand>';
					$output .= '<g:condition>new</g:condition>';
					$output .= '<g:id>' . $product['product_id'] . '</g:id>';
					
					if ($product['image']) {
						$output .= '<g:image_link>' . NTImage::resizeAndSave($product['image'], 500, 500) . '</g:image_link>';
					} else {
						$output .= '<g:image_link>' . NTImage::resizeAndSave('no_image.jpg', 500, 500) . '</g:image_link>';
					}
					
					$output .= '<g:mpn>' . $product['model'] . '</g:mpn>';

					$special = $this->modelProduct->getProductSpecial($product['product_id']);
					
					if ($special) {
						$output .= '<g:price>' . $this->tax->calculate($special, $product['tax_class_id']) . '</g:price>';
					} else {
						$output .= '<g:price>' . $this->tax->calculate($product['price'], $product['tax_class_id']) . '</g:price>';
					}
			   
					$categories = $this->modelProduct->getCategories($product['product_id']);
					
					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);
						
						if ($path) {
							$string = '';
							
							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->modelCategory->getCategory($path_id);
								
								if ($category_info) {
									if (!$string) {
										$string = $category_info['name'];
									} else {
										$string .= ' &gt; ' . $category_info['name'];
									}
								}
							}
						 
							$output .= '<g:product_type>' . $string . '</g:product_type>';
						}
					}
					
					$output .= '<g:quantity>' . $product['quantity'] . '</g:quantity>';
					$output .= '<g:upc>' . $product['model'] . '</g:upc>'; 
					$output .= '<g:weight>' . $this->weight->format($product['weight'], $product['weight_class']) . '</g:weight>'; 
					$output .= '</item>';
				}
			}
			
			$output .= '</channel>'; 
			$output .= '</rss>';	
			
			$this->response->addHeader('Content-Type: application/rss+xml');
			$this->response->setOutput($output, 0);
		}
	}
	
	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->modelCategory->getCategory($parent_id);
	
		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	
		
			$path = $this->getPath($category_info['parent_id'], $new_path);
					
			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}		
}