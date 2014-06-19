<?php
/**
 * ModelSaleCustomerGroup
 * 
 * @package   NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelSaleCustomerGroup extends Model {
	/**
	 * ModelSaleCustomerGroup::add()
	 * 
	 * @param mixed $data
     * @see DB
	 * @return void
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET 
        `name` = '" . $this->db->escape($data['name']) . "',
        `params` = '" . $this->db->escape($data['params']) . "',
        `status` = '1',
        `date_added` = NOW()");
        return $this->db->getLastId();
	}
	
	/**
	 * ModelSaleCustomerGroup::update()
	 * 
	 * @param int $customer_group_id
	 * @param mixed $data
     * @see DB
	 * @return void
	 */
	public function update($customer_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET 
        `name` = '" . $this->db->escape($data['name']) . "',
        `params` = '" . $this->db->escape($data['params']) . "',
        `date_modified` = NOW()
        WHERE customer_group_id = '" . (int)$customer_group_id . "'");
	}
	
	/**
	 * ModelStoreProduct::copy()
	 * 
	 * @param int $product_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function copy($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group 
        WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['name'] = $data['name'] . " - copia";
			$this->add($data);
		}
	}
	
	/**
	 * ModelSaleCustomerGroup::deleteCustomerGroup()
	 * 
	 * @param int $customer_group_id
     * @see DB
	 * @return void
	 */
	public function delete($customer_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = 0 WHERE customer_group_id = '" . (int)$customer_group_id . "'");
	}
	
	/**
	 * ModelSaleCustomerGroup::getById()
	 * 
	 * @param int $customer_group_id
     * @see DB
	 * @return array sql record
	 */
	public function getById($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		return $query->row;
	}
	
	/**
	 * ModelSaleCustomerGroup::getAll()
	 * 
	 * @param mixed $data
     * @see DB
	 * @return array sql records
	 */
	public function getAll($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg";
		
		$implode = array();
    		
    	if ($data['filter_name']) {
    	   $implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    	}
    		
    	if ($data['filter_date_start'] && $data['filter_date_end']) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
   		} elseif ($data['filter_date_start']) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
   		}
    
        if ($data['filter_customer']) {
            $implode[] = " cg.customer_group_id IN (SELECT customer_group_id 
                FROM " . DB_PREFIX . "customer c
                WHERE LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(strtolower($data['filter_customer'])) . "%')";
        } 
            
  		if ($implode) {
    	   $sql .= " WHERE " . implode(" AND ", $implode);
    	}
    		
		$sql .= " ORDER BY name";	
			
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
	 * ModelSaleCustomerGroup::getAllTotal()
	 * 
     * @see DB
	 * @return int Count sql records
	 */
	public function getAllTotal() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group cg";
		
		$implode = array();
    		
    	if ($data['filter_name']) {
    	   $implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    	}
    		
    	if ($data['filter_date_start'] && $data['filter_date_end']) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
   		} elseif ($data['filter_date_start']) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
   		}
    
        if ($data['filter_customer']) {
            $implode[] = " cg.customer_group_id IN (SELECT customer_group_id 
                FROM " . DB_PREFIX . "customer c
                WHERE LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(strtolower($data['filter_customer'])) . "%')";
        } 
            
  		if ($implode) {
    	   $sql .= " WHERE " . implode(" AND ", $implode);
    	}
    		
		$query = $this->db->query($sql);
        
		return $query->row['total'];
	}
	
	/**
	 * ModelSaleCustomerGroup::getAllTotal()
	 * 
     * @see DB
	 * @return int Count sql records
	 */
	public function getAllTotalByGroup($id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" .(int)$id. "'");
		
		return $query->row['total'];
	}
    
    /**
     * ModelCatalogProduct::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "customer_group` SET `status` = '1' WHERE `customer_group_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelCatalogProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "customer_group` SET `status` = '0' WHERE `customer_group_id` = '" . (int)$id . "'");
        return $query;
     }
}
