<?php
/**
 * ModelSaleBank
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelSaleBank extends Model {    
    public function add($data) {
        $sql = "INSERT INTO `". DB_PREFIX ."bank` SET 
        `name` = '". $this->db->escape($data['name']) ."',
        `image` = '". $this->db->escape($data['image']) ."',
        `date_added` = NOW()";
        
        $this->db->query($sql);
        
        return $this->db->getLastId();
    }
    
    public function update($id,$data) {
        $sql = "UPDATE `". DB_PREFIX ."bank` SET 
        `name` = '". $this->db->escape($data['name']) ."',
        `image` = '". $this->db->escape($data['image']) ."',
        `date_modified` = NOW()
        WHERE bank_id = '". (int)$id ."'";
        
        $this->db->query($sql);
    }
    
    public function getAll($data=null) {
        $sql = "SELECT * FROM `". DB_PREFIX ."bank`";
        
        $criteria = array();
        
        if (!empty($data['name'])) {
            $criteria[] = " LCASE(`name`) LIKE '%". $this->db->escape(strtolower($data['name'])) ."%'";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $sql .= " ORDER BY name ASC ";
        
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
        
        $result = $this->db->query($sql);
        return $result->rows;
    }
    
    public function getTotalAll($data=null) {
        $sql = "SELECT COUNT(*) AS total FROM `". DB_PREFIX ."bank`";
        
        $criteria = array();
        
        if (!empty($data['name'])) {
            $criteria[] = " LCASE(`name`) LIKE '%". $this->db->escape(strtolower($data['name'])) ."%'";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $result = $this->db->query($sql);
        return $result->row['total'];
    }
    
    public function getById($id) {
        $sql = "SELECT DISTINCT * FROM `". DB_PREFIX ."bank`";
        
        $criteria = array();
        
        $criteria[] = " bank_id = '". (int)$id ."'";
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $result = $this->db->query($sql);
        return $result->row;
    }
    
	/**
	 * ModelStoreProduct::copy()
	 * 
	 * @param int $product_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function copy($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bank WHERE bank_id = '" . (int)$id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['name'] = $data['name'] ." - copia";
			$this->add($data);
		}
	}
    
    public function delete($id) {
        $result = $this->db->query("SELECT * FROM ". DB_PREFIX ."bank_account WHERE bank_id = '". (int)$id ."'");
        if (!$result->num_rows) {
            $this->db->query("DELETE FROM ". DB_PREFIX ."bank WHERE bank_id = '". (int)$id ."'");
        }
    }
}
