<?php
class ModelLocalisationLanguage extends Model {
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "language SET 
        name = '" . $this->db->escape($data['name']) . "', 
        code = '" . $this->db->escape($data['code']) . "', 
        locale = '" . $this->db->escape($data['locale']) . "', 
        directory = '" . $this->db->escape($data['directory']) . "', 
        filename = '" . $this->db->escape($data['filename']) . "', 
        image = '" . $this->db->escape($data['image']) . "', 
        sort_order = '" . $this->db->escape($data['sort_order']) . "', 
        status = '" . (int)$data['status'] . "'");
		
		$this->cache->delete('language');
		
		$language_id = $this->db->getLastId();

		// Store
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $store) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET 
            store_id = '" . (int)$store['store_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            title = '" . $this->db->escape($store['title']) . "', 
            meta_keywords = '" . $this->db->escape($store['meta_keywords']) . "', 
            meta_description= '" . $this->db->escape($store['meta_description']) . "', 
            description = '" . $this->db->escape($store['description']) . "'");
		}
		$this->cache->delete('store');

		// Category
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET 
            category_id = '" . (int)$category['category_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($category['name']) . "', 
            meta_description= '" . $this->db->escape($category['meta_description']) . "', 
            description = '" . $this->db->escape($category['description']) . "'");
		}
		$this->cache->delete('category');

		// Coupon
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $coupon) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET 
            coupon_id = '" . (int)$coupon['coupon_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($coupon['name']) . "', 
            description = '" . $this->db->escape($coupon['description']) . "'");
		}
		
		// Download
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET 
            download_id = '" . (int)$download['download_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($download['name']) . "'");
		}
				
		// Posts and Pages
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $post) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description SET 
            post_id     = '" . (int)$post['post_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            title       = '" . $this->db->escape($post['title']) . "', 
            description = '" . $this->db->escape($post['description']) . "'");
		}
		$this->cache->delete('post');
		$this->cache->delete('page');

		// Length
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET 
            length_class_id = '" . (int)$length['length_class_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            title = '" . $this->db->escape($length['title']) . "', 
            unit = '" . $this->db->escape($length['unit']) . "'");
		}
		$this->cache->delete('length_class');
		
		// Order Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET 
            order_status_id = '" . (int)$order_status['order_status_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($order_status['name']) . "'");
		}
		$this->cache->delete('order_status');
		
		// Order Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment_status SET 
            order_payment_status_id = '" . (int)$order_status['order_payment_status_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($order_status['name']) . "'");
		}
		$this->cache->delete('order_status');
		
		// Product
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
            product_id = '" . (int)$product['product_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($product['name']) . "', 
            meta_description= '" . $this->db->escape($product['meta_description']) . "', 
            description = '" . $this->db->escape($product['description']) . "'");
		}
		$this->cache->delete('product');

		// Product Option
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $product_option) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_description SET 
            product_option_id = '" . (int)$product_option['product_option_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            product_id = '" . (int)$product_option['product_id'] . "', 
            name = '" . $this->db->escape($product_option['name']) . "'");
		}
		
		// Product Option Value
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $product_option_value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_description SET 
            product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            product_id = '" . (int)$product_option_value['product_id'] . "', 
            name = '" . $this->db->escape($product_option_value['name']) . "'");
		}

		// Stock Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET 
            stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($stock_status['name']) . "'");
		}
		$this->cache->delete('stock_status');
		
		// Banner
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_item_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $banner) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "banner_item_description SET 
            banner_item_id  = '" . (int)$banner['banner_item_id'] . "', 
            language_id     = '" . (int)$language_id . "', 
            title            = '" . $this->db->escape($banner['title']) . "',
            description     = '" . $this->db->escape($banner['description']) . "'");
		}
		$this->cache->delete('banner');
		
		// Store
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $store) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET 
            store_id = '" . (int)$store['store_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            description = '" . $this->db->escape($store['description']) . "'");
		}
		$this->cache->delete('store');		
		
		// Weight Class
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET 
            weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', 
            language_id = '" . (int)$language_id . "', 
            title = '" . $this->db->escape($weight_class['title']) . "', 
            unit = '" . $this->db->escape($weight_class['unit']) . "'");
		}	
		$this->cache->delete('weight_class');
	}
	
	public function update($language_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "language SET 
        name = '" . $this->db->escape($data['name']) . "', 
        code = '" . $this->db->escape($data['code']) . "', 
        locale = '" . $this->db->escape($data['locale']) . "', 
        directory = '" . $this->db->escape($data['directory']) . "', 
        filename = '" . $this->db->escape($data['filename']) . "', 
        image = '" . $this->db->escape($data['image']) . "', 
        sort_order = '" . $this->db->escape($data['sort_order']) . "', 
        status = '" . (int)$data['status'] . "' 
        WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('language');
	}
	
	public function copy($language_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['name'] = $data['name'] ." - copia";
			$data['code'] = "copia";
			$this->add($data);
		}
	}
	
	public function delete($language_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('language');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('category');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('post');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_item_description WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('banner');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('length_class');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('order_status');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment_status WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('order_payment_status');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('product');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('stock_status');
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$language_id . "'");
		$this->cache->delete('weight_class');
	}
	
	public function getById($language_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
		return $query->row;
	}

	public function getAll($data = array()) {
	   $sql = "SELECT * FROM " . DB_PREFIX . "language";
	   
       $criteria = array();
       
        if (!empty($data['filter_name'])) {
            $criteria[] = " LCASE(name) LIKE '%". strtolower($this->db->escape($data['filter_name'])) ."%'";
        }
        
        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $criteria[] = " date_added BETWEEN '". $this->db->escape($data['filter_date_start']) ."' AND '". $this->db->escape($data['filter_date_end']) ."'";
        } elseif (!empty($data['filter_date_start']) && empty($data['filter_date_end'])) {
            $criteria[] = " date_added BETWEEN '". $this->db->escape($data['filter_date_start']) ."' AND '". $this->db->escape(date('Y-m-d h:i:s')) ."'";
        }
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ", $criteria);
        }
        
        $sort_data = array(
            'name',
			'code'
        );
			
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];	
		} else {
            $sql .= " ORDER BY name";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
		} else {
            $sql .= " ASC";
		}
			
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
			}					

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
			
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getAllTotal() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language";
	   
       $criteria = array();
       
        if (!empty($data['filter_name'])) {
            $criteria[] = " LCASE(name) LIKE '%". strtolower($this->db->escape($data['filter_name'])) ."%'";
        }
        
        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $criteria[] = " date_added BETWEEN '". $this->db->escape($data['filter_date_start']) ."' AND '". $this->db->escape($data['filter_date_end']) ."'";
        } elseif (!empty($data['filter_date_start']) && empty($data['filter_date_end'])) {
            $criteria[] = " date_added BETWEEN '". $this->db->escape($data['filter_date_start']) ."' AND '". $this->db->escape(date('Y-m-d h:i:s')) ."'";
        }
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ", $criteria);
        }
        $query = $this->db->query($sql);
		return $query->row['total'];
	}
}
