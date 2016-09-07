<?php
class ControllerFeedGoogleSitemap extends Controller {
   public function index() {
	  if ($this->config->get('google_sitemap_status')) {
		 $output  = '<?xml version="1.0" encoding="UTF-8"';
		 $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		 
		 
		 
		 $this->load->model('store/product');
		 
		 $products = $this->modelProduct->getProducts();
		 
		 foreach ($products as $product) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', Url::createUrl("store/product",array("product_id"=>$product['product_id']))) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }
		 
		 $this->load->model('store/category');
		 
		 $categories = $this->modelCategory->getCategories();
		 
		 $output .= $this->getCategories(0);
		 
		 $this->load->model('store/manufacturer');
		 
		 $manufacturers = $this->modelManufacturer->getManufacturers();
		 
		 foreach ($manufacturers as $manufacturer) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', Url::createUrl("store/manufacturer",array("manufacturer_id"=>$manufacturer['manufacturer_id']))) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';   
			
			$products = $this->modelProduct->getProductsByManufacturerId($manufacturer['manufacturer_id']);
			
			foreach ($products as $product) {
			   $output .= '<url>';
			   $output .= '<loc>' . str_replace('&', '&amp;', Url::createUrl("store/product",array("manufacturer_id"=>$manufacturer['manufacturer_id'],"product_id"=>$product['product_id']))) . '</loc>';
			   $output .= '<changefreq>weekly</changefreq>';
			   $output .= '<priority>1.0</priority>';
			   $output .= '</url>';   
			}         
		 }
		 
		 $this->load->model('content/page');
		 
		 $pages = $this->modelPage->getByIds();
		 
		 foreach ($pages as $page) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', Url::createUrl("content/page",array("page_id"=>$page['page_id']))) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.5</priority>';
			$output .= '</url>';   
		 }
		 
		 $output .= '</urlset>';
		 
		 $this->response->addHeader('Content-Type: application/xml');
		 $this->response->setOutput($output);
	  }
   }
   
   protected function getCategories($parent_id, $current_path = '') {
	  $output = '';
	  
	  $results = $this->modelCategory->getCategories($parent_id);
	  
	  foreach ($results as $result) {
		 if (!$current_path) {
			$new_path = $result['category_id'];
		 } else {
			$new_path = $current_path . '_' . $result['category_id'];
		 }

		 $output .= '<url>';
		 $output .= '<loc>' . str_replace('&', '&amp;', Url::createUrl("store/category",array("path"=>$new_path))) . '</loc>';
		 $output .= '<changefreq>weekly</changefreq>';
		 $output .= '<priority>0.7</priority>';
		 $output .= '</url>';         

		 $products = $this->modelProduct->getProductsByCategoryId($result['category_id']);
		 
		 foreach ($products as $product) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', Url::createUrl("store/product",array("path"=>$new_path,"product_id"=>$product['product_id']))) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }   
		 
		   $output .= $this->getCategories($result['category_id'], $new_path);
	  }

	  return $output;
   }      
}
