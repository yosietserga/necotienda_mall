<?php
/**
 * ModelStoreTheme
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelStyleTheme extends Model {
	/**
	 * ModelStoreTheme::add()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "theme SET 
          template_id   = '" . intval($data['template_id']) . "', 
          user_id       = '" . intval($data['user_id']) . "', 
          store_id      = '" . intval($data['store_id']) . "',
          name          = '" . $this->db->escape($data['name']) . "', 
          template      = '" . $this->db->escape($data['template']) . "', 
          date_publish_start = '" . $this->db->escape($data['date_publish_start']) . "', 
          date_publish_end = '" . $this->db->escape($data['date_publish_end']) . "', 
          `default`      = '" . intval($data['default']) . "',
          status        = '1', 
          sort_order    = '0', 
          date_added    = NOW()");
          
		$theme_id = $this->db->getLastId();

		if (!empty($data['stores'])) {
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "theme_to_store SET 
                store_id  = '". intval($store['store_id']) ."', 
                theme_id = '". intval($theme_id) ."'");
            }
        }
        
        foreach ($data['style'] as $k => $value) {
            if ($value == 0) continue;
    		$this->db->query("INSERT INTO " . DB_PREFIX . "theme_style SET 
              `theme_id`    = '" . intval($theme_id) . "',
              `selector`    = '" . $this->db->escape($data['selector']) . "', 
              `property`    = '" . $this->db->escape($data['property']) . "', 
              `value`       = '" . $this->db->escape($data['value']) . "'");
        }
        
		$this->cache->delete('theme');
        
        return $theme_id;
	}
	
	/**
	 * ModelStoreTheme::editTheme()
	 * 
	 * @param int $theme_id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function update($theme_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "theme SET 
          template_id   = '" . intval($data['template_id']) . "', 
          user_id       = '" . intval($data['user_id']) . "', 
          store_id      = '" . intval($data['store_id']) . "',
          name          = '" . $this->db->escape($data['name']) . "',
          template      = '" . $this->db->escape($data['template']) . "', 
          date_publish_start = '" . $this->db->escape($data['date_publish_start']) . "', 
          date_publish_end = '" . $this->db->escape($data['date_publish_end']) . "', 
          `default`      = '" . intval($data['default']) . "',
          status        = '1', 
          sort_order    = '0', 
          date_added    = NOW()
          WHERE theme_id = '" . intval($theme_id) . "' ");
          
		if (!empty($data['stores'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "theme_to_store WHERE theme_id = '". (int)$theme_id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "theme_to_store SET 
                store_id  = '". intval($store['store_id']) ."', 
                theme_id = '". intval($theme_id) ."'");
            }
        }
        
        foreach ($data['style'] as $k => $value) {
            if ($value == 0) continue;
            $this->db->query("DELETE " . DB_PREFIX . "theme_style WHERE theme_id = '" . intval($theme_id) . "'");
    		$this->db->query("INSERT INTO " . DB_PREFIX . "theme_style SET 
              `theme_id`    = '" . intval($theme_id) . "',
              `selector`    = '" . $this->db->escape($data['selector']) . "', 
              `property`    = '" . $this->db->escape($data['property']) . "', 
              `value`       = '" . $this->db->escape($data['value']) . "'");
        }
        
        return $theme_id;
	}
	
	/**
	 * ModelStoreTheme::editTheme()
	 * 
	 * @param int $theme_id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function saveStyle($theme_id, $data) {
	   if (!$theme_id && empty($data)) return false;
	   $this->db->query("DELETE FROM " . DB_PREFIX . "theme_style WHERE theme_id = '" . intval($theme_id) . "'");
       $sql = "INSERT INTO " . DB_PREFIX . "theme_style (theme_id, selector, `property`, `value`) VALUES ";
        foreach ($data as $selector => $properties) {
            foreach ($properties as $property => $value) {
                if (empty($value)) continue;
                $sql .= "(". (int)$theme_id .",'". $this->db->escape($selector) ."','". $this->db->escape($property) ."','". $this->db->escape($value) ."'),";
            }
        }
        $sql = substr($sql,0,strlen($sql) - 1);
        $this->db->query($sql);
	}
	
	public function copy($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "theme WHERE theme_id = '" . (int)$id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['name'] = $data['name'] ." - copia";
			$data = array_merge($data, array('style' => $this->getStyles($id)));
			$this->add($data);
		}
	}
	
	/**
	 * ModelStoreTheme::deleteTheme()
	 * 
	 * @param int $theme_id
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function delete($theme_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "theme WHERE theme_id = '" . (int)$theme_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "theme_to_store WHERE theme_id = '" . (int)$theme_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "theme_style WHERE theme_id = '" . (int)$theme_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'theme_default_id'");
		$this->cache->delete('theme');
	}	
	
	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme_to_store WHERE theme_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelContentPost::getDescriptions()
	 * 
	 * @param int $post_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getStyles($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme_style WHERE theme_id = '" . (int)$id . "'");
		return $query->rows;
	}
	
	/**
	 * ModelStoreTheme::getTheme()
	 * 
	 * @param int $theme_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function getTheme($theme_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."theme t 
        WHERE t.theme_id = '" . (int)$theme_id . "'");
		return $query->row;
	}
	
	/**
	 * ModelStoreTheme::getThemes()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records 
	 */
	public function getAll($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "theme m";
			
    		$implode = array();
    		
    		if (!empty($data['filter_name'])) {
    			$implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if (!empty($data['filter_template'])) {
    			$implode[] = "template_id IN (SELECT template_id FROM " . DB_PREFIX . "template WHERE LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_template'])) . "%')";
    		}
    		
    		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif (!empty($data['filter_date_start'])) {
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
			$theme_data = $this->cache->get('theme');
		
			if (!$theme_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme ORDER BY name");
	
				$theme_data = $query->rows;
			
				$this->cache->set('theme', $theme_data);
			}
		 
			return $theme_data;
		}
	}

	/**
	 * ModelStoreTheme::getTotalThemes()
	 * 
     * @see DB
	 * @return int Count sql records 
	 */
	public function getTotalThemes($data = null) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "theme m";
			
        $implode = array();
    		
    	if (!empty($data['filter_name'])) {
    	   $implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    	}
    		
    	if (!empty($data['filter_template'])) {
    	   $implode[] = "template_id IN (SELECT template_id FROM " . DB_PREFIX . "template WHERE LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_template'])) . "%')";
    	}
    		
    	if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
   		} elseif (!empty($data['filter_date_start'])) {
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
	public function ntSort($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "theme SET sort_order = '" . (int)$pos . "' WHERE theme_id = '" . (int)$id . "'");
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
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "theme` SET `status` = '1' WHERE `theme_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "theme` SET `status` = '0' WHERE `theme_id` = '" . (int)$id . "'");
        return $query;
     }
}