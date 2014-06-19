<?php
class ModelStoreStore extends Model {
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "store SET 
          name = '" . $this->db->escape($data['config_name']) . "', 
          folder = '" . $this->db->escape($data['config_folder']) . "', 
          status = '1', 
          date_added = NOW()");
		
		$store_id = $this->db->getLastId();
		
		foreach ($data as $key => $value) {		  
    		if ($key == 'config_bounce_password' && !empty($value)) $value = base64_encode($value); 
    		if ($key == 'config_smtp_password' && !empty($value)) $value = base64_encode($value); 
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET 
            `store_id`  = '" . (int)$store_id . "',
            `group`     = 'config', 
            `key`       = '" . $this->db->escape($key) . "', 
            `value`     = '" . $this->db->escape($value) . "'");
		}
        
		foreach ($data['store_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET 
            store_id    = '" . (int)$store_id . "', 
            language_id = '" . (int)$language_id . "', 
            `title`     = '" . $this->db->escape($value['title']) . "',
            meta_description= '" . $this->db->escape($value['meta_description']) . "',
            meta_keywords   = '" . $this->db->escape($value['meta_keywords']) . "',
            description     = '" . $this->db->escape($value['description']) . "'");
		}
        
        $this->db->query("SELECT * FROM `". DB_PREFIX ."user`");
        
		foreach ($users->rows as $user) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "user_to_store SET 
            store_id  = '". intval($store_id) ."', 
            user_id = '". intval($user['user_id']) ."'");
        }
            
		$this->cache->delete('store');
		
		return $store_id;
	}
	
	public function update($store_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "store SET 
          name = '" . $this->db->escape($data['config_name']) . "', 
          status = '1', 
          date_modified = NOW()
          WHERE store_id = '" . (int)$store_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "setting 
        WHERE `group` = 'config'
        AND store_id = '" . (int)$store_id . "'");
        
		foreach ($data as $key => $value) {		  
    		if ($key == 'config_bounce_password' && !empty($value)) $value = base64_encode($value); 
    		if ($key == 'config_smtp_password' && !empty($value)) $value = base64_encode($value); 
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET 
            `store_id`  = '" . (int)$store_id . "',
            `group`     = 'config', 
            `key`       = '" . $this->db->escape($key) . "', 
            `value`     = '" . $this->db->escape($value) . "'");
		}
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "store_description WHERE store_id = '" . (int)$store_id . "'");
		foreach ($data['store_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET 
            store_id    = '" . (int)$store_id . "', 
            language_id = '" . (int)$language_id . "', 
            `title`     = '" . $this->db->escape($value['title']) . "',
            meta_description= '" . $this->db->escape($value['meta_description']) . "',
            meta_keywords   = '" . $this->db->escape($value['meta_keywords']) . "',
            description     = '" . $this->db->escape($value['description']) . "'");
		}
		
        if (!empty($data['Products'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Products'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                product_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Categories'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Categories'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                category_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Manufacturers'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Manufacturers'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                manufacturer_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Downloads'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "download_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Downloads'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "download_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                download_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Pages'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Pages'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                post_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Posts'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Posts'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                post_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['PostCategories'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['PostCategories'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                post_category_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Banners'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Banners'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "banner_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                banner_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Menus'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Menus'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                menu_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Coupons'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Coupons'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                coupon_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['BankAccounts'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "bank_account_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['BankAccounts'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "bank_account_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                bank_account_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Customers'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Customers'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                customer_id = '" . (int)$result . "'");
    		}
        }
        
		$this->cache->delete('store');
	}
	
	public function saveContent($store_id, $data) {
        if (!empty($data['Products'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Products'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                product_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Categories'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Categories'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                category_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Manufacturers'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Manufacturers'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                manufacturer_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Downloads'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "download_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Downloads'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "download_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                download_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Pages'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Pages'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                post_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Posts'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Posts'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                post_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['PostCategories'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['PostCategories'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                post_category_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Banners'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Banners'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "banner_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                banner_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Menus'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Menus'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                menu_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Coupons'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Coupons'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                coupon_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['BankAccounts'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "bank_account_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['BankAccounts'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "bank_account_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                bank_account_id = '" . (int)$result . "'");
    		}
        }
        
        if (!empty($data['Customers'])) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_store WHERE store_id = '" . (int)$store_id . "'");
    		foreach ($data['Customers'] as $result) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_store SET 
                store_id    = '" . (int)$store_id . "', 
                customer_id = '" . (int)$result . "'");
    		}
        }
        
		$this->cache->delete('store');
	}
	
	public function delete($store_id) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX ."setting WHERE store_id = '". (int)$store_id ."' AND `key` = 'config_folder'");
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "store_description WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `store_id` = '" .  (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "stat WHERE `store_id` = '" .  (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "search` WHERE `store_id` = '" .  (int)$store_id . "'");
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_to_store WHERE store_id = '" . (int)$store_id . "'");
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_store WHERE store_id = '" . (int)$store_id . "'");
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "bank_account_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_to_store WHERE store_id = '" . (int)$store_id . "'");
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "theme_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "widget_to_store WHERE store_id = '" . (int)$store_id . "'");
        
		//$this->db->query("DELETE FROM " . DB_PREFIX . "user_to_store WHERE store_id = '" . (int)$store_id . "'");
        
        if (!empty($query->row['config_folder'])) {
            $this->deleteFiles(DIR_ROOT ."app/{$query->row['config_folder']}/*");
            $this->deleteFiles(DIR_ROOT ."web/{$query->row['config_folder']}/*");
        }
        
		$this->cache->delete('store');
	}
    
    public function deleteFiles($folder) {
        if (empty($folder)) return false;
		$files = glob($folder);
		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
                    if (is_dir($file)) {
      			       $this->deleteFiles($file."/*");   
                    } else {
      			       unlink($file);
                    }
                }
            }
        }
    }
    
	public function getSettings($group,$id=0) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting 
        WHERE `group` = '" . $this->db->escape($group) . "'
        AND store_id = '". (int)$id ."'");
		
		foreach ($query->rows as $result) {
			$data[$result['key']] = $result['value'];
		}
		return $data;
	}
	
    public function editMaintenance($data,$group = 'config') {		
    	$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($data) . "' WHERE `key` = 'config_maintenance'");
	}
	
	public function getById($store_id) {
        $store_data = array();
		$query = $this->db->query("SELECT DISTINCT * FROM ". DB_PREFIX ."store WHERE store_id = '" . (int)$store_id . "'");
        $store_data['store_id'] = $query->row['store_id'];
        $store_data['name']   = $query->row['name'];
        $store_data['folder'] = $query->row['folder'];
        
    	$qry = $this->db->query("SELECT * FROM ". DB_PREFIX ."setting WHERE store_id = '". (int)$store_id ."'");
        foreach ($qry->rows as $setting) {
            $store_data[$setting['key']] = $setting['value'];
        }
		return $store_data;
	}
	
	public function getDescriptions($store_id) {
		$return = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_description WHERE store_id = '" . (int)$store_id . "'");
		foreach ($query->rows as $result) {
			$return[$result['language_id']] = array(
                'title'         => $result['title'],
                'description'   => $result['description'],
                'meta_keywords' => $result['meta_keywords'],
                'meta_description' => $result['meta_description']
            );
		}
		return $return;
	}
	
	public function getAll() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
        foreach ($query->rows as $key => $store) {
            $store_data[$key]['store_id'] = $store['store_id'];
            $store_data[$key]['name'] = $store['name'];
            $store_data[$key]['folder'] = $store['folder'];
                
    		$qry = $this->db->query("SELECT * FROM ". DB_PREFIX ."setting WHERE store_id = '". (int)$store['store_id'] ."'");
            foreach ($qry->rows as $setting) {
                $store_data[$key][$setting['key']] = $setting['value'];
            }
        }
		return $store_data;
	}

	public function getAllTotal() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");
		return $query->row['total'];
	}	

	public function getAllTotalByLanguage($language) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store s
          LEFT JOIN `" . DB_PREFIX . "setting` ss ON (ss.store_id=s.store_id) 
          WHERE ss.language = '" . $this->db->escape($language) . "'");
		return $query->row['total'];		
	}
	
	public function getAllTotalByCurrency($currency) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store s
          LEFT JOIN `" . DB_PREFIX . "setting` ss ON (ss.store_id=s.store_id) 
          WHERE ss.currency = '" . $this->db->escape($currency) . "'");
		return $query->row['total'];		
	}
	
	public function getAllTotalByCountryId($country_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store s
          LEFT JOIN `" . DB_PREFIX . "setting` ss ON (ss.store_id=s.store_id) 
          WHERE ss.country_id = '" . $this->db->escape($country_id) . "'");
		return $query->row['total'];		
	}
	
	public function getAllTotalByZoneId($zone_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store s
          LEFT JOIN `" . DB_PREFIX . "setting` ss ON (ss.store_id=s.store_id) 
          WHERE ss.zone_id = '" . $this->db->escape($zone_id) . "'");
		return $query->row['total'];		
	}
	
	public function getAllTotalByCustomerGroupId($customer_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store s
          LEFT JOIN `" . DB_PREFIX . "setting` ss ON (ss.store_id=s.store_id) 
          WHERE ss.customer_group_id = '" . $this->db->escape($customer_group_id) . "'");
		return $query->row['total'];		
	}	
	
	public function getAllTotalByOrderStatusId($order_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store s
          LEFT JOIN `" . DB_PREFIX . "setting` ss ON (ss.store_id=s.store_id) 
          WHERE ss.order_status_id = '" . $this->db->escape($order_status_id) . "'");
		return $query->row['total'];		
	}
    
	public function getAllProducts($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
        ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllCategories($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "category p 
            LEFT JOIN " . DB_PREFIX . "category_description pd ON (p.category_id = pd.category_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store p2s ON (p.category_id = p2s.category_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
        ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllPostCategories($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "post_category p 
            LEFT JOIN " . DB_PREFIX . "post_category_description pd ON (p.post_category_id = pd.post_category_id) 
            LEFT JOIN " . DB_PREFIX . "post_category_to_store p2s ON (p.post_category_id = p2s.post_category_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
        ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllManufacturers($id) {
		$query = $this->db->query("SELECT DISTINCT * 
        FROM " . DB_PREFIX . "manufacturer p 
            LEFT JOIN " . DB_PREFIX . "manufacturer_to_store p2s ON (p.manufacturer_id = p2s.manufacturer_id) 
        WHERE p2s.store_id = '" . (int)$id . "' 
        ORDER BY p.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllPages($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "post p 
            LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
            LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id = p2s.post_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
            AND p.post_type = 'page' 
        ORDER BY pd.title ASC");
								  
		return $query->rows;
	} 
	
	public function getAllPosts($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "post p 
            LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
            LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id = p2s.post_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
            AND p.post_type = 'post' 
        ORDER BY pd.title ASC");
								  
		return $query->rows;
	} 
	
	public function getAllBanners($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "banner p 
            LEFT JOIN " . DB_PREFIX . "banner_to_store p2s ON (p.banner_id = p2s.banner_id) 
        WHERE p2s.store_id = '" . (int)$id . "' 
        ORDER BY p.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllMenus($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "menu p 
            LEFT JOIN " . DB_PREFIX . "menu_to_store p2s ON (p.menu_id = p2s.menu_id) 
        WHERE p2s.store_id = '" . (int)$id . "' 
        ORDER BY p.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllDownloads($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "download p 
            LEFT JOIN " . DB_PREFIX . "download_description pd ON (p.download_id = pd.download_id) 
            LEFT JOIN " . DB_PREFIX . "download_to_store p2s ON (p.download_id = p2s.download_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
        ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllCoupons($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "coupon p 
            LEFT JOIN " . DB_PREFIX . "coupon_description pd ON (p.coupon_id = pd.coupon_id) 
            LEFT JOIN " . DB_PREFIX . "coupon_to_store p2s ON (p.coupon_id = p2s.coupon_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '" . (int)$id . "' 
        ORDER BY pd.name ASC");
								  
		return $query->rows;
	} 
	
	public function getAllBankAccounts($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "bank_account p 
            LEFT JOIN " . DB_PREFIX . "bank_account_to_store p2s ON (p.bank_account_id = p2s.bank_account_id) 
        WHERE p2s.store_id = '" . (int)$id . "' 
        ORDER BY p.accountholder ASC");
								  
		return $query->rows;
	} 
	
	public function getAllCustomers($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "customer p 
            LEFT JOIN " . DB_PREFIX . "customer_to_store p2s ON (p.customer_id = p2s.customer_id) 
        WHERE p2s.store_id = '" . (int)$id . "' 
        ORDER BY p.firstname ASC");
								  
		return $query->rows;
	} 
	
}