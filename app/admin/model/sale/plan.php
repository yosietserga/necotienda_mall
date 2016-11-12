<?php /**
 * ModelSalePlan
 * 
 * @package NecoTienda powered by opencart
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelSalePlan extends Model {
	/**
	 * ModelSalePlan::addPlan()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "plan SET 
          name = '" . $this->db->escape($data['name']) . "', 
          image = '" . $this->db->escape($data['image']) . "', 
          price = '" . (float)$data['price'] . "', 
          qty_images = '" . (int)$data['qty_images'] . "', 
          qty_days = '" . (int)$data['qty_days'] . "', 
          qty_videos = '" . (int)$data['qty_videos'] . "', 
          featured = '" . (int)$data['featured'] . "', 
          show_in_home = '" . (int)$data['show_in_home'] . "', 
          sort_order = '" . (int)$data['sort_order'] . "', 
          status = '1', 
          date_added = NOW()");
		
		$plan_id = $this->db->getLastId();

		
        foreach ($data['Products'] as $product_id => $value) {
            if ($value == 0) continue;
    		$this->db->query("UPDATE " . DB_PREFIX . "product SET plan_id = '" . (int)$plan_id."' WHERE product_id = '" . (int)$product_id."'");
        }
        
		$this->cache->delete('plan');
        return $plan_id;
	}
	
	/**
	 * ModelSalePlan::editPlan()
	 * 
	 * @param int $plan_id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function edit($plan_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "plan SET 
          name = '" . $this->db->escape($data['name']) . "', 
          image = '" . $this->db->escape($data['image']) . "', 
          price = '" . (float)$data['price'] . "', 
          qty_images = '" . (int)$data['qty_images'] . "', 
          qty_days = '" . (int)$data['qty_days'] . "', 
          qty_videos = '" . (int)$data['qty_videos'] . "', 
          featured = '" . (int)$data['featured'] . "', 
          show_in_home = '" . (int)$data['show_in_home'] . "', 
          sort_order = '" . (int)$data['sort_order'] . "' 
          WHERE plan_id = '" . (int)$plan_id . "'");
          
        foreach ($data['Products'] as $product_id => $value) {
            if ($value == 0) continue;
    		$this->db->query("UPDATE " . DB_PREFIX . "product SET plan_id = '" . (int)$plan_id."' WHERE product_id = '" . (int)$product_id."'");
        }
        
		$this->cache->delete('plan');
        return $plan_id;
	}
	
	/**
	 * ModelSalePlan::deletePlan()
	 * 
	 * @param int $plan_id
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function delete($plan_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "plan WHERE plan_id = '" . (int)$plan_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_plan WHERE plan_id = '" . (int)$plan_id . "'");
		$this->cache->delete('plan');
	}	
	
	/**
	 * ModelSalePlan::getPlan()
	 * 
	 * @param int $plan_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function getPlan($plan_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'plan_id=" . (int)$plan_id . "') AS keyword FROM " . DB_PREFIX . "plan WHERE plan_id = '" . (int)$plan_id . "'");
		
		return $query->row;
	}
	
	/**
	 * ModelSalePlan::getPlans()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records 
	 */
	public function getPlans($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "plan m";
			
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
			$plan_data = unserialize($this->cache->get('plan'));
		
			if (!$plan_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "plan ORDER BY name");
	
				$plan_data = $query->rows;
			
				$this->cache->set('plan', serialize($plan_data));
			}
		 
			return $plan_data;
		}
	}
    
	/**
	 * ModelSalePlan::getTotalPlans()
	 * 
     * @see DB
	 * @return int Count sql records 
	 */
	public function getTotalPlans($data=array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "plan ";
		
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
            $this->db->query("UPDATE " . DB_PREFIX . "plan SET sort_order = '" . (int)$pos . "' WHERE plan_id = '" . (int)$id . "'");
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
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "plan` SET `status` = '1' WHERE `plan_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "plan` SET `status` = '0' WHERE `plan_id` = '" . (int)$id . "'");
        return $query;
     }
}
