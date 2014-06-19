<?php /**
 * ModelStoreAttribute
 * 
 * @package NecoTienda powered opencart
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelStoreAttribute extends Model {
	/**
	 * ModelStoreAttribute::add()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute_group SET 
        `name` = '" . $this->db->escape($data['name']) . "',
        `status` = '1',
        date_added = NOW()");
		
		$id = $this->db->getLastId();
        
        foreach ($data['Properties'] as $row => $property) {
            if (empty($property['name']) || empty($property['label'])) continue;
    		$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET 
            `product_attribute_group_id` = '" . (int)$id . "',
            `store_id`  = '" . (int)STORE_ID . "',
            `group`     = '" . $this->db->escape($data['name']) . "',
            `name`      = '" . $this->db->escape($property['name']) . "',
            `label`     = '" . $this->db->escape($property['label']) . "',
            `type`      = '" . $this->db->escape($property['type']) . "',
            `pattern`   = '" . $this->db->escape($property['pattern']) . "',
            `required`  = '" . $this->db->escape($property['required']) . "',
            `default`   = '" . $this->db->escape($property['default']) . "',
            date_added  = NOW()");
        }
        
        foreach ($data['Categories'] as $row => $category_id) {
    		$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute_to_category SET 
            `product_attribute_group_id` = '" . (int)$id . "',
            `category_id`  = '" . (int)$category_id . "'");
        }
        return $id;
	}
	
	/**
	 * ModelStoreAttribute::update()
	 * 
	 * @param int $id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function update($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product_attribute_group SET 
        `name` = '". $this->db->escape($data['name']) ."'
        WHERE product_attribute_group_id = '". (int)$id ."'");
		
        if (!empty($data['Properties'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_attribute_group_id = '". (int)$id ."'");
            foreach ($data['Properties'] as $row => $property) {
                if (empty($property['name']) || empty($property['label'])) continue;
        		$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET 
                `product_attribute_group_id` = '" . (int)$id . "',
                `store_id`  = '". (int)STORE_ID . "',
                `group`     = '". $this->db->escape($data['name']) ."',
                `name`      = '" . $this->db->escape($property['name']) . "',
                `label`     = '" . $this->db->escape($property['label']) . "',
                `type`      = '" . $this->db->escape($property['type']) . "',
                `pattern`   = '" . $this->db->escape($property['pattern']) . "',
                `required`  = '" . $this->db->escape($property['required']) . "',
                `default`   = '" . $this->db->escape($property['default']) . "',
                date_added  = NOW()");
            }
        }
        
        if (!empty($data['Categories'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute_to_category WHERE product_attribute_group_id = '". (int)$id ."'");
            foreach ($data['Categories'] as $row => $category_id) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute_to_category SET 
                `product_attribute_group_id` = '" . (int)$id . "',
                `category_id`  = '" . (int)$category_id . "'");
            }
        }
        return $id;
	}
	
	/**
	 * ModelStoreAttribute::delete()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function delete($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute_group WHERE product_attribute_group_id = '" . (int)$id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute_to_category WHERE product_attribute_group_id = '" . (int)$id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_attribute_group_id = '" . (int)$id . "'");
	}
	
	/**
	 * ModelStoreAttribute::getById()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return array sql record
	 */
	public function getById($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_attribute_group ag WHERE product_attribute_group_id = '" . (int)$id . "'");
		
        $return = array(
            'product_attribute_group_id' => $query->row['product_attribute_group_id'],
            'name' => $query->row['name'],
            'status' => $query->row['status'],
            'date_added' => $query->row['date_added'],
            'categories' => $this->getCategoriesByGroupId($query->row['product_attribute_group_id']),
            'attributes' => $this->getAttributesByGroupId($query->row['product_attribute_group_id'])
        );
        
		return $return;
	}
	
	/**
	 * ModelStoreAttribute::getById()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return array sql record
	 */
	public function getCategoriesByGroupId($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute_to_category WHERE product_attribute_group_id = '" . (int)$id . "'");
		return $query->rows;
	}
	
	/**
	 * ModelStoreAttribute::getById()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return array sql record
	 */
	public function getAttributesByGroupId($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_attribute_group_id = '" . (int)$id . "'");
		return $query->rows;
	}
	
	/**
	 * ModelStoreAttribute::getTotalGroups()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return int Count sql records
	 */
	public function getTotalGroups($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute_group ag ";
		
        $criteria = array();
        
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$criteria[] = " LCASE(ag.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute_to_category a2c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (a2c.category_id = cd.category_id) ";
            $criteria[] = " LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_category'])) . "%'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " ag.status = '" . (int)$data['filter_status'] . "'";
		}
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ",$criteria);
        }

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	/**
	 * ModelStoreAttribute::getAllGroups()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return int Count sql records
	 */
     public function getAllGroups($data=array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product_attribute_group ag ";
		
        $criteria = array();
        
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$criteria[] = " LCASE(ag.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute_to_category a2c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (a2c.category_id = cd.category_id) ";
            $criteria[] = " LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_category'])) . "%'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " ag.status = '" . (int)$data['filter_status'] . "'";
		}
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ",$criteria);
        }

		$sort_data = array(
		  'ag.name',
		  'cd.name',
		  'ag.status'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];	
		} else {
            $sql .= " ORDER BY ag.name";	
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
	 * ModelStoreAttribute::sortProduct()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortProduct($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET sort_order = '" . (int)$pos . "' WHERE product_id = '" . (int)$id . "'");
            $pos++;
       }
	   return true;
	}
	
    /**
     * ModelStoreAttribute::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = '1' WHERE `product_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreAttribute::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = '0' WHERE `product_id` = '" . (int)$id . "'");
        return $query;
     }
     
}
