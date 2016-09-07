<?php
class ModelStoreProduct extends Model {
	/**
	 * ModelCatalogProduct::addProduct()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET 
        owner_id = '" . (int)$this->customer->getId() . "', 
        model = '" . $this->db->escape($data['model']) . "', 
        quantity = '" . (int)$data['quantity'] . "', 
        minimum = '1', 
        subtract = '1', 
        stock_status_id = '" . (int)$data['stock_status_id'] . "', 
        date_available = NOW(), 
        manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
        price = '" . (float)$data['price'] . "',  
        status = '1', 
        tax_class_id = '" . (int)$data['tax_class_id'] . "', 
        sort_order = '" . (int)$data['sort_order'] . "', 
        date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		if (isset($data['Images'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET 
            image = '" . $this->db->escape($data['Images'][0]['name']) . "' 
            WHERE product_id = '" . (int)$product_id . "'");
            unset($data['Images'][0]);
            foreach ($data['Images'] as $key => $image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET 
                product_id = '" . (int)$product_id . "', 
                `image` = '". $this->db->escape($image['name']) ."'");
            }
		}
		
		if (isset($data['Attributes'])) {
            foreach ($data['Attributes'] as $key => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_property SET 
                product_id = '" . (int)$product_id . "', 
                `group` = 'attribute', 
                `key` = '". $this->db->escape($key) ."', 
                `value` = '". $this->db->escape($value) ."'");
            }
		}
		
		if (isset($data['Zones'])) {
            foreach ($data['Zones'] as $key => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_zone SET 
                product_id = '" . (int)$product_id . "', 
                zone_id = '" . (int)$value . "'");
            }
		}
		
        if ($data['plan_id']) {
            $date = new DateTime;
            $date->add(new DateInterval('P'. (int)$data['qty_days'] .'D'));
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_plan SET 
                `product_id` = '" . (int)$product_id . "', 
                `plan_id` = '". (int)$data['plan_id'] ."', 
                `days` = '". (int)$data['qty_days'] ."', 
                `date_start` = NOW(),
                `date_end` = '". $date->format('Y-m-d h:i:s') ."'");
        }
        
        if ($data['featured']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_featured SET `product_id` = '" . (int)$product_id . "'");
        }
        
        if ($data['homepage']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_homepage SET `product_id` = '" . (int)$product_id . "'");
        }
        
        //TODO: obtener todos los idiomas e insertar el registro en cada uno de ellos
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
        product_id = '" . (int)$product_id . "', 
        language_id = '" . (int)$this->config->get("config_language_id") . "', 
        name = '" . $this->db->escape($data['name']) . "',
        meta_description = '" . $this->db->escape($data['meta_description']) . "',
        description = '" . $this->db->escape($data['description']) . "'");
		
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$value['customer_group_id'] . "', priority = '" . (int)$value['priority'] . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
		}

		if (isset($data['Categories'])) {
			foreach ($data['Categories'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET
            object_id   = '". (int)$product_id ."',
            object_type = 'product',
            language_id = '". (int)$this->config->get('language_id') ."',
            query = 'product_id=" . (int)$product_id . "', 
            keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
        
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET
            store_id   = '0',
            product_id = '". (int)$product_id . "'");
		
		if (isset($data['Stores'])) {
			foreach ($data['Stores'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
                product_id = '" . (int)$product_id . "', 
                store_id = '" . (int)$value . "'");
			}
		}
		
        if (isset($data['PaymentMethods'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_property SET 
                product_id = '" . (int)$product_id . "', 
                `group` = 'payment_methods', 
                `key` = 'payment_methods', 
                `value` = '". $this->db->escape(serialize($data['PaymentMethods'])) ."'");
		}
		
        if (isset($data['ShippingMethods'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_property SET 
                product_id = '" . (int)$product_id . "', 
                `group` = 'shipping_methods', 
                `key` = 'shipping_methods', 
                `value` = '". $this->db->escape(serialize($data['ShippingMethods'])) ."'");
		}
		
		$this->cache->delete('product');
        return $product_id;
	}
	
	public function getProperties($id,$group) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "product_property 
        WHERE product_id = '" . (int)$id . "' 
        AND `group` = '" . $this->db->escape($group) . "'");
		return $query->rows;
	}
	
	public function getZones($id) {
		$query = $this->db->query("SELECT * 
        FROM ". DB_PREFIX ."product_to_zone p2z
        LEFT JOIN ". DB_PREFIX ."zone z ON (z.zone_id=p2z.zone_id)
        WHERE product_id = '" . (int)$id . "'");
		return $query->rows;
	}
	
	public function getProduct($product_id) {
        if ($this->customer->isLogged()) {
            $model = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "'"); 
        }
        
		$sql = "SELECT DISTINCT *, p.viewed AS total_visits, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock 
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_plan p2p ON (p.product_id = p2p.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
        WHERE p.product_id = '" . (int)$product_id . "' 
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id IN ('". (int)STORE_ID ."', 0)";
        
        $criteria = array();
           
        if ($model->row['owner_id']!=$this->customer->getId()) {
            $criteria[] = " p2p.date_start <= NOW() ";
            $criteria[] = " p2p.date_end >= NOW() ";
            $criteria[] = " p.status = '1' ";
        }
        
        if ($criteria) {
            $sql .= " AND " . implode(" AND ",$criteria);
        }
        
		$query = $this->db->query($sql);
	
		return $query->row;
	}

	public function getProducts() {
        if ($this->customer->isLogged()) {
            $model = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "'"); 
        }
        
		$query = $this->db->query("SELECT DISTINCT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, wcd.unit AS weight_class 
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_plan p2p ON (p.product_id = p2p.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (p.weight_class_id = wcd.weight_class_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "'  
            AND p2s.store_id IN ('". (int)STORE_ID ."', 0) 
            AND p2p.date_start <= NOW() 
            AND p2p.date_end >= NOW() 
            AND p.status = '1'");
	
		return $query->rows;
	}
	
    public function getTotalProductsByCustomerId($id, $data=array()) {
		$sql = "SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "product p 
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        LEFT JOIN " . DB_PREFIX . "product_to_plan p2p ON (p.product_id = p2p.product_id) 
        WHERE p.owner_id = '". (int)$id ."' ";
        
        $implode = array();
           
        if ($data['letter']) {
            $implode[] = " LCASE(pd.name) LIKE '" . $this->db->escape(strtolower($data['letter'])) . "%' ";
        }
        
        if ($data['keyword']) {
            $implode[] = " LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['keyword'])) . "%' ";
        }
        
        if ($data['status'] === -1) {
            $implode[] = " p.status = '-1' ";
            $implode[] = " p2p.date_end < NOW() ";
        } elseif ($data['status'] === 0) {
            $implode[] = " p.status = '0' ";
            $implode[] = " p2p.date_end >= NOW() ";
        } elseif ($data['status'] === 1) {
            $implode[] = " p.status = '1' ";
            $implode[] = " p2p.date_end >= NOW() ";
        }
        
        if ($implode) {
            $sql .= " AND " . implode(" AND ",$implode);
        }
        
        $query = $this->db->query($sql);
		return $query->row['total'];
    }
    
	public function getTotalProducts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        WHERE p.date_available <= NOW() 
            AND p2s.store_id = '". (int)STORE_ID ."' 
            AND p.status = '1'");
	
		return $query->row['total'];
	}
	
	public function getProductsByCustomerId($id,$data=array()) {
		$sql = "SELECT *, p.status AS product_status, p.product_id AS pid, p.viewed AS views, pd.name AS name, pl.name AS plan, p.image, m.name AS manufacturer
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_property pp ON (p.product_id = pp.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_plan p2p ON (p.product_id = p2p.product_id) 
            LEFT JOIN " . DB_PREFIX . "plan pl ON (pl.plan_id = p2p.plan_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  
        AND p.owner_id = '". (int)$id ."'";
        
        $implode = array();
           
        if ($data['letter']) {
            $implode[] = " LCASE(pd.name) LIKE '" . $this->db->escape(strtolower($data['letter'])) . "%' ";
        }
        
        if ($data['keyword']) {
            $implode[] = " LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['keyword'])) . "%' ";
        }
        
        if ($data['status'] === -1) {
            $implode[] = " p2p.date_end < NOW() ";
        } elseif ($data['status'] === 0) {
            $implode[] = " p.status = '0' ";
            $implode[] = " p2p.date_end >= NOW() ";
        } elseif ($data['status'] === 1) {
            $implode[] = " p.status = '1' ";
            $implode[] = " p2p.date_end >= NOW() ";
        }
        
        if ($implode) {
            $sql .= " AND " . implode(" AND ",$implode);
        }
        
        $sql .= "GROUP BY p.product_id";
		$sql .= " ORDER BY pd.name ASC";	
			
		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        
        $query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getProductsByCategoryId($category_id, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, 
        (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id GROUP BY r.object_id) AS rating 
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (p2c.category_id = c2s.category_id) 
        WHERE p.status = '1' AND p.date_available <= NOW() 
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND c2s.store_id = '". (int)STORE_ID ."' 
            AND p2s.store_id = '". (int)STORE_ID ."' 
            AND p2c.category_id = '" . (int)$category_id . "'";
		
		$sort_data = array(
			'pd.name',
			'p.sort_order',
			'special',
			'rating'
		);
			
		if (in_array($sort, $sort_data)) {
			if ($sort == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $sort . ")";
			} else {
				$sql .= " ORDER BY " . $sort;
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
								  
		return $query->rows;
	} 
	
	public function getTotalProductsByCategoryId($category_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_category p2c 
            LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (p2c.category_id = c2s.category_id) 
        WHERE p.status = '1' 
            AND p.date_available <= NOW() 
            AND p2s.store_id = '". (int)STORE_ID ."' 
            AND c2s.store_id = '". (int)STORE_ID ."' 
            AND p2c.category_id = '" . (int)$category_id . "'");

		return $query->row['total'];
	}

	public function getProductsByManufacturerId($manufacturer_id, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, 
        (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
        FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
        WHERE p.status = '1' 
            AND p.date_available <= NOW() 
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND m2s.store_id = '". (int)STORE_ID ."' 
            AND p2s.store_id = '". (int)STORE_ID ."'
            AND m.manufacturer_id = '" . (int)$manufacturer_id. "'";

		$sort_data = array(
			'pd.name',
			'p.sort_order',
			'special',
			'rating'
		);
			
		if (in_array($sort, $sort_data)) {
			if ($sort == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $sort . ")";
			} else {
				$sql .= " ORDER BY " . $sort;
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	} 

	public function getTotalProductsByManufacturerId($manufacturer_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        WHERE status = '1' 
            AND date_available <= NOW() 
            AND p2s.store_id = '". (int)STORE_ID ."'
            AND manufacturer_id = '" . (int)$manufacturer_id . "'");
		
		return $query->row['total'];
	}
	
	public function getProductsByTag($tag, $category_id = 0, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
		if ($tag) {
		
			$sql = "SELECT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_tags pt ON (p.product_id = pt.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "'  
                AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND p2s.store_id = '". (int)STORE_ID ."'
                AND (LCASE(pt.tag) = '" . $this->db->escape(strtolower($tag)) . "'";

			$keywords = explode(" ", $tag);
						
			foreach ($keywords as $keyword) {
				$sql .= " OR LCASE(pt.tag) = '" . $this->db->escape(strtolower($keyword)) . "'";
			}
			
			$sql .= ")";
			
			if ($category_id) {
				$data = array();
				
				$this->load->model('store/category');
				
				$string = rtrim($this->getPath($category_id), ',');
				
				foreach (explode(',', $string) as $category_id) {
					$data[] = "category_id = '" . (int)$category_id . "'";
				}
				
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . implode(" OR ", $data) . ")";
			}
		
			$sql .= " AND p.status = '1' AND p.date_available <= NOW() GROUP BY p.product_id";
		
			$sort_data = array(
				'pd.name',
				'p.sort_order',
				'special',
				'rating'
			);
				
			if (in_array($sort, $sort_data)) {
				if ($sort == 'pd.name') {
					$sql .= " ORDER BY LCASE(" . $sort . ")";
				} else {
					$sql .= " ORDER BY " . $sort;
				}
			} else {
				$sql .= " ORDER BY p.sort_order";	
			}
			
			if ($order == 'DESC') {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if ($start < 0) {
				$start = 0;
			}
		
			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
			
			$query = $this->db->query($sql);
			
			$products = array();
			
			foreach ($query->rows as $key => $value) {
				$products[$value['product_id']] = $this->getProduct($value['product_id']);
			}
			
			return $products;
		}
	}
	
    public function getCategoriesByProduct($data) {
	   $cachedId = "search_categories_" . $data['filter_keyword'] ."_". (int)STORE_ID ."_". $data['filter_category'];
       if($cachedId !== mb_convert_encoding( mb_convert_encoding($cachedId, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        		$cachedId = mb_convert_encoding($cachedId, 'UTF-8', mb_detect_encoding($cachedId));
      	$cachedId = htmlentities($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $cachedId);
      	$cachedId = html_entity_decode($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $cachedId);
      	$cachedId = strtolower( trim($cachedId, '-') );
            
	   $cached = $this->cache->get($cachedId);
       if (!$cached) {
            $sql = "SELECT *, COUNT(*) AS total, cd.name AS category 
            FROM " . DB_PREFIX . "category_description cd 
                LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (cd.category_id = c2s.category_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.category_id = cd.category_id) ";
                
    	   $criteria = array();
           
           if ($data['filter_category']) {
             $criteria[] = " LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_category'])) . "%' ";
           }
           
           $criteria[] = " p2c.product_id = '" . (int)$data['product_id'] . "' ";
           $criteria[] = " cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
           $criteria[] = " c2s.store_id = '". (int)STORE_ID ."'";
           
            if ($criteria) {
                $sql .= " WHERE " . implode(" AND ",$criteria);
            }
            
            $sql .= " GROUP BY cd.category_id";
                  
    		$query = $this->db->query($sql);
    					
    		foreach ($query->rows as $key => $value) {
                $return[] = array(
                    'category_id'=>$value['category_id'],
                    'name'=>$value['category'],
                    'total'=>$value['total']
                );
    		}
   			$this->cache->set($cachedId,$return);
    		return $return;
        } else {
            return $cached;
        }
    }
    
    public function getManufacturersByProduct($data) {
	   $cachedId = "search_manufacturers_" . $data['keyword'] ."_". $data['manufacturer'] ."_". (int)STORE_ID ."_". $data['city'];
       if($cachedId !== mb_convert_encoding( mb_convert_encoding($cachedId, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        		$cachedId = mb_convert_encoding($cachedId, 'UTF-8', mb_detect_encoding($cachedId));
      	$cachedId = htmlentities($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $cachedId);
      	$cachedId = html_entity_decode($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $cachedId);
      	$cachedId = strtolower( trim($cachedId, '-') );
            
	   $cached = $this->cache->get($cachedId);
       if (!$cached) {
            $sql = "SELECT *, COUNT(*) AS total, md.name AS manufacturer 
            FROM " . DB_PREFIX . "manufacturer_description md 
                LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (md.manufacturer_id = m2s.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.category_id = cd.category_id) 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2c.product_id = pd.product_id)
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = pd.product_id) ";
                
    	   $criteria = array();
           
           if ($data['keyword']) {
             $criteria[] = " LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['keyword'])) . "%' ";
           }
           
           if ($data['category']) {
             $criteria[] = " LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower($data['category'])) . "%' ";
           }
           
           $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
           $criteria[] = " p.status = '1' ";
           $criteria[] = " p.date_available <= NOW() ";
           $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
           $criteria[] = " m2s.store_id = '". (int)STORE_ID ."' ";
           
            if ($criteria) {
                $sql .= " AND " . implode(" AND ",$criteria);
            }
            
            $sql .= " GROUP BY cd.category_id";
                  
    		$query = $this->db->query($sql);
    					
    		foreach ($query->rows as $key => $value) {
                $return[] = array(
                    'category_id'=>$value['category_id'],
                    'name'=>$value['category'],
                    'total'=>$value['total']
                );
    		}
            
   			$this->cache->set($cachedId,$return);
    		return $return;
        } else {
            return $cached;
        }
    }
    
	public function getByKeyword($data) {
	   $cachedId = "search_items_". 
       $data['filter_keyword'] ."_". 
       $data['filter_price_start'] ."_". 
       $data['filter_price_end'] ."_". 
       $data['filter_manufacturer'] ."_". 
       $data['filter_category'] ."_". 
       $data['filter_color'] ."_". 
       $data['order'] ."_". 
       (int)STORE_ID ."_". 
       $data['sort'] ."_". 
       $data['page'] ."_". 
       $data['start'] ."_". 
       $data['limit'];
       
       if($cachedId !== mb_convert_encoding( mb_convert_encoding($cachedId, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        		$cachedId = mb_convert_encoding($cachedId, 'UTF-8', mb_detect_encoding($cachedId));
      	$cachedId = htmlentities($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $cachedId);
      	$cachedId = html_entity_decode($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $cachedId);
      	$cachedId = strtolower( trim($cachedId, '-') );
            
	   $cached = $this->cache->get($cachedId);
       if (!$cached) {
			$sql = "SELECT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, 
            (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) ";
			
    	   $criteria = array();
           
           $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
           $criteria[] = " ss.language_id = '" . (int)$this->config->get('config_language_id') . "'";
           $criteria[] = " p.status = '1'";
           $criteria[] = " p.date_available <= NOW()";
           $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
           
           if (!empty($data['filter_keyword'])) {
             $criteria[] = " LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_keyword'])) . "%' ";
           }
           
           if (!empty($data['filter_price_start']) && !empty($data['filter_price_end'])) {
                $criteria[] = " p.price BETWEEN '" . (float)strtolower($data['filter_price_start']) . "' AND '" . (float)strtolower($data['filter_price_end']) . "'";
           } elseif (!empty($data['filter_price_start']) && empty($data['filter_price_end'])) {
                $criteria[] = " p.price >= '" . (float)strtolower($data['filter_price_start']) . "'";
           } elseif (!empty($data['filter_price_start']) && empty($data['filter_price_end'])) {
                $criteria[] = " p.price <= '" . (float)strtolower($data['filter_price_end']) . "'";
           }
           
           if (!empty($data['filter_manufacturer'])) {
             $criteria[] = " LCASE(m.name) LIKE '%" . $this->db->escape(strtolower($data['filter_manufacturer'])) . "%' ";
           }
           
           if (!empty($data['filter_category'])) {
             $criteria[] = " pd.product_id IN 
             (SELECT product_id FROM ". DB_PREFIX ."product_to_category p2c 
             LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = p2c.category_id) 
             WHERE LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower(str_replace("-"," ",$data['filter_category']))) . "%') ";
           }
           
            if ($criteria) {
                $sql .= " WHERE " . implode(" AND ",$criteria);
            }
            
            $sql .= " GROUP BY p.product_id";
                   
    		$sort_data = array(
    				'pd.name',
    				'p.sort_order',
    				'p.price',
    				'special',
    				'rating'
    		);
    				
    		if (in_array($data['sort'], $sort_data)) {
    		  if ($data['sort'] == 'pd.name') {
    				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
        		} else {
    				$sql .= " ORDER BY " . $data['sort'];
        		}
    		} else {
    		  $sql .= " ORDER BY p.sort_order";	
    		}
    			
    		if ($data['order'] == 'DESC') {
                $sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    
    		if ($data['start'] < 0) {
    			$data['start'] = 0;
    		}
    		
    		if (!(int)$data['limit']) {
    			$data['limit'] = 25;
    		}
    		
    		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            
    		$query = $this->db->query($sql);
    			
    		$products = $this->getProductsByTag($data['filter_keyword'], $data['filter_category'], $data['sort'], $data['order'], $data['start'], $data['limit']);
    						
    		foreach ($query->rows as $key => $value) {
                $products[$value['product_id']] = $query->rows[$key];
    		}
            
   			$this->cache->set($cachedId,$products);
    		return $products;
        } else {
            return $cached;
        }
	}
	
	public function getTotalByKeyword($data) {
	   $cachedId = "search_total_" . 
       $data['filter_keyword'] ."_". 
       $data['filter_price_start'] ."_". 
       $data['filter_price_end'] ."_". 
       $data['filter_manufacturer'] ."_". 
       $data['filter_category'] ."_". 
       $data['filter_color'] ."_". 
       $data['order'] ."_". 
       (int)STORE_ID ."_". 
       $data['sort'] ."_". 
       $data['page'] ."_". 
       $data['start'] ."_". 
       $data['limit'];
       
       if($cachedId !== mb_convert_encoding( mb_convert_encoding($cachedId, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        		$cachedId = mb_convert_encoding($cachedId, 'UTF-8', mb_detect_encoding($cachedId));
      	$cachedId = htmlentities($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $cachedId);
      	$cachedId = html_entity_decode($cachedId, ENT_NOQUOTES, 'UTF-8');
      	$cachedId = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $cachedId);
      	$cachedId = strtolower( trim($cachedId, '-') );
            
	   $cached = $this->cache->get($cachedId);
       if (!$cached) {
    	   $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
           
    	   $criteria = array();
           
           $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
           $criteria[] = " p.status = '1'";
           $criteria[] = " p.date_available <= NOW()";
           $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
           
           if (!empty($data['filter_keyword'])) {
             $criteria[] = " LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_keyword'])) . "%' ";
           }
           
           if (!empty($data['filter_price_start']) && !empty($data['filter_price_end'])) {
                $criteria[] = " p.price BETWEEN '" . (float)strtolower($data['filter_price_start']) . "' AND '" . (float)strtolower($data['filter_price_end']) . "'";
           } elseif (!empty($data['filter_price_start']) && empty($data['filter_price_end'])) {
                $criteria[] = " p.price >= '" . (float)strtolower($data['filter_price_start']) . "'";
           } elseif (!empty($data['filter_price_start']) && empty($data['filter_price_end'])) {
                $criteria[] = " p.price <= '" . (float)strtolower($data['filter_price_end']) . "'";
           }
           
           if (!empty($data['filter_manufacturer'])) {
             $criteria[] = " LCASE(m.name) LIKE '%" . $this->db->escape(strtolower($data['filter_manufacturer'])) . "%' ";
           }
           
           if (!empty($data['filter_category'])) {
             $criteria[] = " pd.product_id IN 
             (SELECT product_id FROM ". DB_PREFIX ."product_to_category p2c 
             LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = p2c.category_id) 
             WHERE LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower(str_replace("-"," ",$data['filter_category']))) . "%') ";
           }
           
            if ($criteria) {
                $sql .= " WHERE " . implode(" AND ",$criteria);
            }
            
            $query = $this->db->query($sql);
                    
            $tag_count = $this->getTotalProductsByTag($keyword, $category_id);
            if ($query->num_rows) {
                $this->cache->set($cachedId,$query->row['total'] + $tag_count);
                return ($query->row['total'] + $tag_count);
            } else {
                $this->cache->set($cachedId,$tag_count);
                return (0 + $tag_count);
            }
        } else {
            return $cached;
        }
	}
	
	public function getProductsByKeyword($keyword, $category_id = 0, $description = false, $model = false, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
		if ($keyword) {
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_tags pt ON (p.product_id = pt.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND p2s.store_id = '". (int)STORE_ID ."'
                AND (LCASE(pt.tag) = '" . $this->db->escape(strtolower($tag)) . "'";
			
			if (!$description) {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%'";
			} else {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%'";
			}
			
			if (!$model) {
				$sql .= ")";
			} else {
				$sql .= " OR LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%')";
			}
			
			if ($category_id) {
				$data = array();
				
				$this->load->model('store/category');
				
				$string = rtrim($this->getPath($category_id), ',');
				
				foreach (explode(',', $string) as $category_id) {
					$data[] = "category_id = '" . (int)$category_id . "'";
				}
				
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . implode(" OR ", $data) . ")";
			}
		
			$sql .= " AND p.status = '1' AND p.date_available <= NOW() GROUP BY p.product_id";
		
			$sort_data = array(
				'pd.name',
				'p.sort_order',
				'special',
				'rating'
			);
				
			if (in_array($sort, $sort_data)) {
				if ($sort == 'pd.name') {
					$sql .= " ORDER BY LCASE(" . $sort . ")";
				} else {
					$sql .= " ORDER BY " . $sort;
				}
			} else {
				$sql .= " ORDER BY p.sort_order";	
			}
			
			if ($order == 'DESC') {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if ($start < 0) {
				$start = 0;
			}
		
			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

			$query = $this->db->query($sql);
			
			$products = $this->getProductsByTag($keyword, $category_id, $sort, $order, $start, $limit);
						
			foreach ($query->rows as $key => $value) {
				$products[$value['product_id']] = $query->rows[$key];
			}
			
			return $products;

		} else {
			return 0;	
		}
	}
	
	public function getTotalProductsByKeyword($keyword, $category_id = 0, $description = false, $model = false) {
		if ($keyword) {
			$sql = "SELECT COUNT(*) AS total 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND p2s.store_id = '". (int)STORE_ID ."'";
			
			if (!$description) {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%'";
			} else {
				$sql .= " AND (LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%'";
			}

			if (!$model) {
				$sql .= ")";
			} else {
				$sql .= " OR LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%')";
			}

			if ($category_id) {
				$data = array();
				
				$this->load->model('store/category');
				
				$string = rtrim($this->getPath($category_id), ',');
				
				foreach (explode(',', $string) as $category_id) {
					$data[] = "category_id = '" . (int)$category_id . "'";
				}
				
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . implode(" OR ", $data) . ")";
			}
			
			$sql .= " AND p.status = '1' AND p.date_available <= NOW()";
			
			$query = $this->db->query($sql);
		
			$tag_count = $this->getTotalProductsByTag($keyword, $category_id);
								
			if ($query->num_rows) {
				return ($query->row['total'] + $tag_count);
			} else {
				return (0 + $tag_count);
			}
		} else {
			return 0;	
		}		
	}
	
	public function getTotalProductsByTag($tag, $category_id = 0) {
		if ($tag) {
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_tags pt ON (p.product_id = pt.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND p2s.store_id = '". (int)STORE_ID ."'
                AND (LCASE(pt.tag) = '" . $this->db->escape(strtolower($tag)) . "'";

			$keywords = explode(" ", $tag);
						
			foreach ($keywords as $keyword) {
				$sql .= " OR LCASE(pt.tag) = '" . $this->db->escape(strtolower($keyword)) . "'";
			}
			
			$sql .= ")";
			
			if ($category_id) {
				$data = array();
				
				$this->load->model('store/category');
				
				$string = rtrim($this->getPath($category_id), ',');
				
				foreach (explode(',', $string) as $category_id) {
					$data[] = "category_id = '" . (int)$category_id . "'";
				}
				
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE " . implode(" OR ", $data) . ")";
			}
		
			$sql .= " AND p.status = '1' AND p.date_available <= NOW()";
					
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return $query->row['total'];
			} else {
				return 0;
			}
		}
	}
	
	public function getPath($category_id) {
		$string = $category_id . ',';
		
		$results = $this->modelCategory->getCategories($category_id);

		foreach ($results as $result) {
			$string .= $this->getPath($result['category_id']);
		}
		
		return $string;
	}	
    
    public function getRandomProducts($limit) {
		$query = $this->db->query("SELECT DISTINCT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, wcd.unit AS weight_class FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (p.weight_class_id = wcd.weight_class_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p.date_available <= NOW() 
            AND p.status = '1' 
            AND p2s.store_id = '". (int)STORE_ID ."'
        ORDER BY RAND() LIMIT " . (int)$limit);
	
		return $query->rows;
	}
	
    public function getRecommendedProducts($limit) {
		$query = $this->db->query("SELECT DISTINCT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, wcd.unit AS weight_class 
        FROM " . DB_PREFIX . "stat s 
            LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = s.object_id) 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (p.weight_class_id = wcd.weight_class_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND s.object_type = 'product'
            AND s.customer_id = '". (int)$this->reseller->getId() ."'
            AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p.date_available <= NOW() 
            AND p.status = '1' 
            AND p2s.store_id = '". (int)STORE_ID ."'
        ORDER BY s.date_added DESC 
        LIMIT " . (int)$limit);
	
		return $query->rows;
	}
	
	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . $this->config->get('config_language_id') . '.' . (int)C_CODE . '.' . $limit);
        
		if (!$product_data) { 
            $query = $this->db->query("SELECT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            WHERE p.status = '1' AND p.date_available <= NOW() 
                AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND p2s.store_id = '". (int)STORE_ID ."'
            ORDER BY p.date_added DESC 
            LIMIT " . (int)$limit);
			$product_data = $query->rows;

			$this->cache->set('product.latest.' . $this->config->get('config_language_id') . '.' . (int)C_CODE . '.' . $limit, $product_data);
		}
		
		return $product_data;
	}
    
	public function getPopularProducts($limit) {
		$query = $this->db->query("SELECT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, 
        (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
        WHERE p.status = '1' 
            AND p.date_available <= NOW() 
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '". (int)STORE_ID ."'
        ORDER BY p.viewed, p.date_added DESC 
        LIMIT " . (int)$limit);
		 	 		
		return $query->rows;
	}
	
	public function getFeaturedProducts($limit) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_featured f 
            LEFT JOIN " . DB_PREFIX . "product p ON (f.product_id=p.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (f.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (f.product_id = p2s.product_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND p2s.store_id = '". (int)STORE_ID ."'
        LIMIT " . (int)$limit);
	
		return $query->rows;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . $this->config->get('config_language_id') . '.' . (int)C_CODE . '.' . $limit);

		if (!$product_data) { 
			$product_data = array();
			
			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.order_status_id> '0' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
			
			foreach ($query->rows as $result) {
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p 
                    LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                    LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                WHERE p.product_id = '" . (int)$result['product_id'] . "' 
                    AND p.status = '1' 
                    AND p.date_available <= NOW() 
                    AND p2s.store_id = '". (int)STORE_ID ."'
                    AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
				if ($product_query->num_rows) {
					$product_data[] = $product_query->row;
				}
			}

			$this->cache->set('product.bestseller.' . $this->config->get('config_language_id') . '.' . (int)C_CODE . '.' . $limit, $product_data);
		}
		
		return $product_data;
	}
		
	public function updateStats($product_id,$customer_id) {
	   $this->load->library('browser');
       $browser = new Browser;
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	    $this->registerStat($product_id,$customer_id,'product');
	}
	
	public function productMailed($product_id,$customer_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET mailed = (mailed + 1) WHERE product_id = '" . (int)$product_id . "'");
	    $this->registerStat($product_id,$customer_id,'product_mailed');
	}
    
	public function productCalled($product_id,$customer_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET called = (called + 1) WHERE product_id = '" . (int)$product_id . "'");
	    $this->registerStat($product_id,$customer_id,'product_called');
	}
    
	public function webVisited($product_id,$customer_id) {
	    $this->registerStat($product_id,$customer_id,'web_visited');
	}
    
    public function registerStat($product_id,$customer_id,$object_type) {
	    $this->load->library('browser');
        $browser = new Browser;
		$this->db->query("INSERT " . DB_PREFIX . "stat SET 
        `object_id`     = '". (int)$product_id ."',
        `store_id`      = '". (int)STORE_ID ."',
        `customer_id`   = '". (int)$customer_id ."',
        `object_type`   = '". $this->db->escape($object_type) ."',
        `server`        = '". $this->db->escape(serialize($_SERVER)) ."',
        `session`       = '". $this->db->escape(serialize($_SESSION)) ."',
        `request`       = '". $this->db->escape(serialize($_REQUEST)) ."',
        `store_url`     = '". $this->db->escape($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']) ."',
        `ref`           = '". $this->db->escape($_SERVER['HTTP_REFERER']) ."',
        `browser`       = '". $this->db->escape($browser->getBrowser()) ."',
        `browser_version`= '". $this->db->escape($browser->getVersion()) ."',
        `os`            = '". $this->db->escape($browser->getPlatform()) ."',
        `ip`            = '". $this->db->escape($_SERVER['REMOTE_ADDR']) ."',
        `date_added`    = NOW()");
    }
	
	public function getProductOptions($product_id) {
		$product_option_data = array();
		
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
		
		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();
			
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY sort_order");
			
			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'name'                    => $product_option_value_description_query->row['name'],
         			'price'                   => $product_option_value['price'],
         			'prefix'                  => $product_option_value['prefix']
				);
			}
						
			$product_option_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
						
        	$product_option_data[] = array(
        		'product_option_id' => $product_option['product_option_id'],
				'name'              => $product_option_description_query->row['name'],
				'option_value'      => $product_option_value_data,
				'sort_order'        => $product_option['sort_order']
        	);
      	}	
		
		return $product_option_data;
	}
	
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}
	
	public function getProductTags($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tags WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}
	
	public function getProductDiscount($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$query = $this->db->query("SELECT price 
        FROM " . DB_PREFIX . "product_discount 
        WHERE product_id = '" . (int)$product_id . "' 
        AND customer_group_id = '" . (int)$customer_group_id . "' 
        AND quantity = '1' 
        AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) 
        ORDER BY priority ASC, price ASC 
        LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row['price'];
		} else {
			return false;
		}
	}

	public function getProductDiscounts($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "product_discount 
        WHERE product_id = '" . (int)$product_id . "' 
        AND customer_group_id = '" . (int)$customer_group_id . "' 
        AND quantity > 1 
        AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end> NOW())) 
        ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;		
	}
	
	public function getProductSpecial($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end> NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row['price'];
		} else {
			return false;
		}
	}
	
	public function getProductSpecials($sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		$sql = "SELECT *, p.date_added AS created, pd.name AS name, p.price, 
                (SELECT ps2.price 
                    FROM " . DB_PREFIX . "product_special ps2 
                    WHERE p.product_id = ps2.product_id 
                        AND ps2.customer_group_id = '" . (int)$customer_group_id . "' 
                        AND ((ps2.date_start = '0000-00-00' OR ps2.date_start < NOW()) 
                        AND (ps2.date_end = '0000-00-00' OR ps2.date_end> NOW())) 
                    ORDER BY ps2.priority ASC, ps2.price ASC LIMIT 1) AS special, 
                p.image, 
                m.name AS manufacturer, 
                ss.name AS stock, 
                (SELECT AVG(r.rating) 
                    FROM " . DB_PREFIX . "review r 
                    WHERE p.product_id = r.object_id AND `object_type` = 'product'
                    GROUP BY r.object_id) AS rating 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
                WHERE p.status = '1' 
                    AND p.date_available <= NOW() 
                    AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                    AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                    AND p2s.store_id = '". (int)STORE_ID ."'
                    AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
                    AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
                    AND (ps.date_end = '0000-00-00' OR ps.date_end> NOW())) 
                    AND ps.product_id NOT IN 
                        (SELECT pd2.product_id 
                            FROM " . DB_PREFIX . "product_discount pd2 
                            WHERE p.product_id = pd2.product_id 
                                AND pd2.customer_group_id = '" . (int)$customer_group_id . "' 
                                AND pd2.quantity = '1' 
                                AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) 
                                AND (pd2.date_end = '0000-00-00' OR pd2.date_end> NOW()))) 
                GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.sort_order',
			'special',
			'rating'
		);
			
		if (in_array($sort, $sort_data)) {
			if ($sort == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $sort . ")";
			} else {
				$sql .= " ORDER BY " . $sort;
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

		$query = $this->db->query($sql);
		
		return $query->rows;
	}	

	public function getTotalProductSpecials() {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		
		
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total 
        FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        WHERE p.status = '1' 
            AND p.date_available <= NOW() 
            AND p2s.store_id = '". (int)STORE_ID ."'
            AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
            AND (ps.date_end = '0000-00-00' OR ps.date_end> NOW())) 
            AND ps.product_id NOT IN 
                (SELECT pd2.product_id FROM " . DB_PREFIX . "product_discount pd2 
                    WHERE p.product_id = pd2.product_id 
                        AND pd2.customer_group_id = '" . (int)$customer_group_id . "' 
                        AND pd2.quantity = '1' 
                        AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) 
                        AND (pd2.date_end = '0000-00-00' OR pd2.date_end> NOW())))");
		
		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}	
	
	public function getProductRelated($product_id) {
		$product_data = array();

		$product_related_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($product_related_query->rows as $result) { 
			$product_query = $this->db->query("SELECT DISTINCT *, p.date_added AS created, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, 
            (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            WHERE p.product_id = '" . (int)$result['related_id'] . "' 
                AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND p2s.store_id = '". (int)STORE_ID ."'
                AND p.date_available <= NOW() 
                AND p.status = '1'");
			
			if ($product_query->num_rows) {
				$product_data[$result['related_id']] = $product_query->row;
			}
		}
		
		return $product_data;
	}
	
	public function getProductRelatedByCustomer($id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, m.name AS manufacturer, ss.name AS stock, 
            (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND `object_type` = 'product' GROUP BY r.object_id) AS rating 
            FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_plan p2p ON (p.product_id = p2p.product_id) 
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            WHERE p.owner_id = (SELECT owner_id FROM ". DB_PREFIX ."product WHERE product_id ='" . (int)$id . "')
                AND p.product_id NOT IN (" . (int)$id . ")
                AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND p2s.store_id = '". (int)STORE_ID ."'
                AND p2p.date_start <= NOW() 
                AND p2p.date_end >= NOW() 
                AND p.status = '1'
            ORDER BY RAND()
            LIMIT 40");
	    
		return $query->rows;
	}
	
    
	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
    
    /**
     * ModelStoreProduct::getProperty()
     * 
     * Obtener una propiedad del producto
     * 
     * @param int $id product_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_property 
        WHERE `product_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
  
		return unserialize(str_replace("\'","'",$query->row['value']));
	}
	
    /**
     * ModelStoreProduct::getAllProperties()
     * 
     * Obtiene todas las propiedades del producto
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($product_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($product_id, 'NombreDelGrupo');
     * 
     * @param int $id product_id
     * @param varchar $group
     * @return array all properties
     * */
	public function getAllProperties($id, $group='*') {
        if ($group=='*') {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_property 
            WHERE `product_id` = '" . (int)$id . "'");
        } else {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_property 
            WHERE `product_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
        
		return $query->rows;
	}
    
    /**
     * ModelStoreProduct::setFinished()
     * 
     * Finaliza la publicacion de un producto
     * 
     * @param int $id product_id
     * @return void
     * */
    public function setFinished($id) {
        $this->db->query("UPDATE ". DB_PREFIX ."product SET `status` = '-1' WHERE product_id = '". (int)$id ."'");
        $this->db->query("UPDATE ". DB_PREFIX ."product_to_plan SET 
        `date_start` = '". (date('Y')-10) ."-01-01 00:00:00',
        `date_end` = '". (date('Y')-10) ."-01-01 00:00:00' 
        WHERE product_id = '". (int)$id ."'");
    }
}
