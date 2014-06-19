<?php
/**
 * ModelContentBanner
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelContentBanner extends Model {
	/**
	 * ModelContentBanner::add()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "banner SET 
        `name`              = '" . $this->db->escape($data['name']) . "', 
        `jquery_plugin`     = '" . $this->db->escape($data['jquery_plugin']) . "', 
        `params`            = '" . $this->db->escape($data['params']) . "', 
        `publish_date_start`= '" . $this->db->escape($data['publish_date_start']) . "', 
        `publish_date_end`  = '" . $this->db->escape($data['publish_date_end']) . "', 
        `status`            = '1', 
        `date_added`        = NOW()");
        
		$banner_id = $this->db->getLastId();
		
        foreach ($data['stores'] as $store) {
    		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_to_store SET 
            store_id       = '" . intval($store) . "', 
            banner_id        = '" . intval($banner_id) . "'");
        }
        
        foreach ($data['items'] as $key => $item) {
    		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_item SET 
            `banner_id`  = '" . intval($banner_id) . "', 
            `sort_order` = '" . intval($item['sort_order']) . "', 
            `status`     = '1', 
            `image`      = '" . $this->db->escape($item['image']) . "',
            `link`       = '" . $this->db->escape($item['link']) . "'");
            
            $banner_item_id = $this->db->getLastId();
            
            foreach ($item['descriptions'] as $language_id => $description) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_item_description SET 
                `banner_item_id`= '" . intval($banner_item_id) . "', 
                `language_id`   = '" . intval($language_id) . "',
                `title`         = '" . $this->db->escape($description['title']) . "',
                `description`   = '" . $this->db->escape($description['description']) . "'");
            }
            
        }
        
		$this->cache->delete('banner');
        return $banner_id;
	}
	
	/**
	 * ModelContentBanner::update()
	 * 
	 * @param int $id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function update($banner_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "banner SET 
        `name`              = '" . $this->db->escape($data['name']) . "', 
        `jquery_plugin`     = '" . $this->db->escape($data['jquery_plugin']) . "', 
        `params`            = '" . $this->db->escape($data['params']) . "', 
        `publish_date_start`= '" . $this->db->escape($data['publish_date_start']) . "', 
        `publish_date_end`  = '" . $this->db->escape($data['publish_date_end']) . "', 
        `date_modified`        = NOW()
        WHERE banner_id = '". (int)$banner_id ."'");
        
            $this->db->query("DELETE FROM " . DB_PREFIX . "banner_to_store WHERE banner_id = '". (int)$banner_id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_to_store SET 
                store_id  = '". intval($store) ."', 
                banner_id = '". intval($banner_id) ."'");
            }
        
        $this->db->query("DELETE FROM ". DB_PREFIX ."banner_item_description WHERE banner_item_id IN (SELECT banner_item_id FROM ". DB_PREFIX ."banner_item WHERE banner_id = '". (int)$banner_id ."')");
        $this->db->query("DELETE FROM ". DB_PREFIX ."banner_item WHERE banner_id = '". (int)$banner_id ."'");
        foreach ($data['items'] as $key => $item) {
    		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_item SET 
            `banner_id`  = '" . intval($banner_id) . "', 
            `sort_order` = '" . intval($item['sort_order']) . "', 
            `image`      = '" . $this->db->escape($item['image']) . "',
            `link`       = '" . $this->db->escape($item['link']) . "'");
            
            $banner_item_id = $this->db->getLastId();
            
            foreach ($item['descriptions'] as $language_id => $description) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_item_description SET 
                `banner_item_id`= '" . intval($banner_item_id) . "', 
                `language_id`   = '" . intval($language_id) . "',
                `title`         = '" . $this->db->escape($description['title']) . "',
                `description`   = '" . $this->db->escape($description['description']) . "'");
            }
            
        }
        
		$this->cache->delete('banner');
	}
	
	/**
	 * ModelContentBanner::delete()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function delete($id) {
		$this->db->query("DELETE FROM ". DB_PREFIX ."banner WHERE banner_id = '" . (int)$id . "'");
        $this->db->query("DELETE FROM ". DB_PREFIX ."banner_to_store WHERE banner_id = '". (int)$banner_id ."'");
        $this->db->query("DELETE FROM ". DB_PREFIX ."banner_item_description WHERE banner_item_id IN (SELECT banner_item_id FROM ". DB_PREFIX ."banner_item WHERE banner_id = '". (int)$banner_id ."')");
        $this->db->query("DELETE FROM ". DB_PREFIX ."banner_item WHERE banner_id = '". (int)$banner_id ."'");
	}
	
	/**
	 * ModelContentBanner::getById()
	 * 
	 * @param int $banner_id
     * @see DB
     * @see Cache
	 * @return array sql record
	 */
	public function getById($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
        $return = $query->row;
        $return['banner_items']  = $this->getItems($banner_id);
        $return['banner_stores'] = $this->getStores($banner_id);
		return $return;
	}
	
	/**
	 * ModelContentCategory::getItems()
	 * 
	 * @param int $banner_id
     * @see DB
	 * @return array sql records
	 */
	public function getItems($banner_id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_item WHERE banner_id = '" . (int)$banner_id . "'");
		foreach ($query->rows as $key => $result) {
            $data[$key] = $result;
            $data[$key]['descriptions'] = $this->getDescriptions($result['banner_item_id']);
		}
		return $data;
	}	
	
	/**
	 * ModelContentCategory::getDescriptions()
	 * 
	 * @param int $slider_id
     * @see DB
	 * @return array sql records
	 */
	public function getDescriptions($banner_item_id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_item_description WHERE banner_item_id = '" . (int)$banner_item_id . "'");
		foreach ($query->rows as $result) {
			$data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description']
			);
		}
		return $data;
	}	
	
	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_to_store WHERE banner_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelContentBanner::getAll()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getAll($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "banner ";
        
        $criteria = array();
        
        if (!empty($data['filter_name'])) {
            $criteria[] = "LCASE(name) LIKE '%". $this->db->escape(strtolower($data['filter_name'])) ."%'";
        }
        
        if (!empty($data['filter_plugin'])) {
            $criteria[] = "LCASE(jquery_plugin) LIKE '%". $this->db->escape(strtolower($data['filter_plugin'])) ."%'";
        }
        
		if (!empty($data['filter_date_start'])) {
            $criteria[] = "publish_date_start >= '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "'";
		}
        
		if (!empty($data['filter_date_end'])) {
            $criteria[] = "publish_date_end <= '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "'";
		}
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ",$criteria);
        }

        $sort_data = array(
            'name',
			'publish_date_start',
			'publish_date_end'
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
    
	/**
	 * ModelContentBanner::getTotal()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return array sql record
	 */
	public function getTotal($product_id) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banner ";
        
        $criteria = array();
        
        if (!empty($data['filter_name'])) {
            $criteria[] = "LCASE(name) LIKE '%". $this->db->escape(strtolower($data['filter_name'])) ."%'";
        }
        
        if (!empty($data['filter_plugin'])) {
            $criteria[] = "LCASE(jquery_plugin) LIKE '%". $this->db->escape(strtolower($data['filter_plugin'])) ."%'";
        }
        
		if (!empty($data['filter_date_start'])) {
            $criteria[] = "publish_date_start >= '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "'";
		}
        
		if (!empty($data['filter_date_end'])) {
            $criteria[] = "publish_date_end <= '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "'";
		}
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ",$criteria);
        }

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	/**
	 * ModelContentBanner::sortBanner()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortBanner($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "banner SET sort_order = '" . (int)$pos . "' WHERE banner_id = '" . (int)$id . "'");
            $pos++;
       }
	   return true;
	}

    /**
     * ModelContentBanner::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "banner` SET `status` = '1' WHERE `banner_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelContentBanner::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "banner` SET `status` = '0' WHERE `banner_id` = '" . (int)$id . "'");
        return $query;
     }
}