<?php /**
 * ModelSalePlanStore
 * 
 * @package NecoTienda powered by opencart
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelSalePlanStore extends Model {
	/**
	 * ModelSalePlanStore::addPlan()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "plan_store SET 
          name = '" . $this->db->escape($data['name']) . "', 
          image = '" . $this->db->escape($data['image']) . "', 
          price = '" . (float)$data['price'] . "', 
          has_email_marketing_module = '" . (int)$data['has_email_marketing_module'] . "', 
          has_style_module = '" . (int)$data['has_style_module'] . "', 
          has_layout_module = '" . (int)$data['has_layout_module'] . "', 
          has_banner_module = '" . (int)$data['has_banner_module'] . "', 
          has_report_module = '" . (int)$data['has_report_module'] . "', 
          has_order_module = '" . (int)$data['has_order_module'] . "', 
          has_custom_domain = '" . (int)$data['has_custom_domain'] . "', 
          has_premium_template = '" . (int)$data['has_premium_template'] . "', 
          sort_order = '" . (int)$data['sort_order'] . "', 
          status = '1', 
          date_added = NOW()");
		
		$plan_store_id = $this->db->getLastId();

		$this->cache->delete('plan_store');
        return $plan_store_id;
	}
	
	/**
	 * ModelSalePlanStore::editPlan()
	 * 
	 * @param int $plan_store_id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function edit($plan_store_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "plan_store SET 
          name = '" . $this->db->escape($data['name']) . "', 
          image = '" . $this->db->escape($data['image']) . "', 
          price = '" . (float)$data['price'] . "', 
          has_email_marketing_module = '" . (int)$data['has_email_marketing_module'] . "', 
          has_style_module = '" . (int)$data['has_style_module'] . "', 
          has_layout_module = '" . (int)$data['has_layout_module'] . "', 
          has_banner_module = '" . (int)$data['has_banner_module'] . "', 
          has_report_module = '" . (int)$data['has_report_module'] . "', 
          has_order_module = '" . (int)$data['has_order_module'] . "', 
          has_custom_domain = '" . (int)$data['has_custom_domain'] . "', 
          has_premium_template = '" . (int)$data['has_premium_template'] . "', 
          sort_order = '" . (int)$data['sort_order'] . "', 
          date_modified = NOW()
          WHERE plan_store_id = '" . (int)$plan_store_id . "'");
          
		$this->cache->delete('plan_store');
        return $plan_store_id;
	}
	
	/**
	 * ModelSalePlanStore::deletePlan()
	 * 
	 * @param int $plan_store_id
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function delete($plan_store_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "plan_store WHERE plan_store_id = '" . (int)$plan_store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_plan_store WHERE plan_store_id = '" . (int)$plan_store_id . "'");
		$this->cache->delete('plan_store');
	}	
	
	/**
	 * ModelSalePlanStore::getPlan()
	 * 
	 * @param int $plan_store_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function getPlan($plan_store_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'plan_store_id=" . (int)$plan_store_id . "') AS keyword FROM " . DB_PREFIX . "plan_store WHERE plan_store_id = '" . (int)$plan_store_id . "'");
		
		return $query->row;
	}
	
	/**
	 * ModelSalePlanStore::getPlans()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records 
	 */
	public function getPlans($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "plan_store m";
			
    		$implode = array();
    		
    		if ($data['filter_name']) {
    			$implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if ($data['filter_date_start'] && $data['filter_date_end']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif ($data['filter_date_start']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
    		if ($implode) {
    			$sql .= " WHERE " . implode(" AND ", $implode);
    		}
    		
			$sort_data = array(
				'name',
				'date_added',
				'sort_order'
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
		} else {
			$plan_store_data = unserialize($this->cache->get('plan_store'));
		
			if (!$plan_store_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "plan_store ORDER BY name");
	
				$plan_store_data = $query->rows;
			
				$this->cache->set('plan_store', serialize($plan_store_data));
			}
		 
			return $plan_store_data;
		}
	}
    
	/**
	 * ModelSalePlanStore::getTotalPlans()
	 * 
     * @see DB
	 * @return int Count sql records 
	 */
	public function getTotalPlans($data=array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "plan_store ";
		
    		$implode = array();
    		
    		if ($data['filter_name']) {
    			$implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if ($data['filter_date_start'] && $data['filter_date_end']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif ($data['filter_date_start']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
    		if ($implode) {
    			$sql .= " WHERE " . implode(" AND ", $implode);
    		}
    		
      	$query = $this->db->query($sql);
		return $query->row['total'];
	}	
	/**
	 * ModelStoreProduct::sortProduct()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortProduct($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "plan_store SET sort_order = '" . (int)$pos . "' WHERE plan_store_id = '" . (int)$id . "'");
            $pos++;
       }
	   return true;
	}
	
    /**
     * ModelStoreProduct::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "plan_store` SET `status` = '1' WHERE `plan_store_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "plan_store` SET `status` = '0' WHERE `plan_store_id` = '" . (int)$id . "'");
        return $query;
     }
}
