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
class ModelSaleBankAccount extends Model {    
    public function add($data) {
        $sql = "INSERT INTO `". DB_PREFIX ."bank_account` SET 
        `bank_id` = '". intval($data['bank_id']) ."',
        `number` = '". $this->db->escape($data['number']) ."',
        `accountholder` = '". $this->db->escape($data['accountholder']) ."',
        `type` = '". $this->db->escape($data['type']) ."',
        `rif` = '". $this->db->escape($data['rif']) ."',
        `email` = '". $this->db->escape($data['email']) ."',
        `status` = '1',
        `date_added` = NOW()";
        
        $this->db->query($sql);
        
        $bank_account_id = $this->db->getLastId();
        
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "bank_account_to_store SET 
                store_id  = '". intval($store) ."', 
                bank_account_id = '". intval($bank_account_id) ."'");
            }
        
        
        return $bank_account_id;
    }
    
    public function update($id,$data) {
        $sql = "UPDATE `". DB_PREFIX ."bank_account` SET 
        `bank_id` = '". intval($data['bank_id']) ."',
        `number` = '". $this->db->escape($data['number']) ."',
        `accountholder` = '". $this->db->escape($data['accountholder']) ."',
        `type` = '". $this->db->escape($data['type']) ."',
        `rif` = '". $this->db->escape($data['rif']) ."',
        `email` = '". $this->db->escape($data['email']) ."',
        `date_added` = NOW()
        WHERE bank_account_id = '". (int)$id ."'";
        
            $this->db->query("DELETE FROM " . DB_PREFIX . "bank_account_to_store WHERE bank_account_id = '". (int)$id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "bank_account_to_store SET 
                store_id  = '". intval($store) ."', 
                bank_account_id = '". intval($id) ."'");
            }
        
        
        $this->db->query($sql);
    }
    
    public function getAll($data=null) {
        $sql = "SELECT *,b.name AS bank, ba.date_added AS dateAdded FROM `". DB_PREFIX ."bank_account` ba 
        LEFT JOIN `". DB_PREFIX ."bank` b ON (b.bank_id=ba.bank_id)";
        
        $criteria = array();
        
        if (!empty($data['number'])) {
            $criteria[] = " ba.`number` LIKE '%". intval($data['number']) ."%'";
        }
        
        if (!empty($data['bank'])) {
            $criteria[] = " LCASE(b.`name`) LIKE '%". $this->db->escape(strtolower($data['bank'])) ."%'";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $sql .= " ORDER BY `number` ASC ";
        
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
        $sql = "SELECT COUNT(*) AS total FROM `". DB_PREFIX ."bank_account` ba 
        LEFT JOIN `". DB_PREFIX ."bank` b ON (b.bank_id=ba.bank_id)";
        
        $criteria = array();
        
        if (!empty($data['number'])) {
            $criteria[] = " ba.`number` LIKE '%". intval($data['number']) ."%'";
        }
        
        if (!empty($data['bank'])) {
            $criteria[] = " LCASE(b.`name`) LIKE '%". $this->db->escape(strtolower($data['bank'])) ."%'";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $result = $this->db->query($sql);
        return $result->row['total'];
    }
    
    public function getById($id) {
        $sql = "SELECT DISTINCT * FROM `". DB_PREFIX ."bank_account` ba 
        LEFT JOIN `". DB_PREFIX ."bank` b ON (b.bank_id=ba.bank_id)";
        
        $criteria = array();
        
        $criteria[] = " bank_account_id = '". (int)$id ."'";
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $result = $this->db->query($sql);
        return $result->row;
    }
    
	public function copy($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bank_account WHERE bank_account_id = '" . (int)$id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$this->add($data);
		}
	}
    
    public function delete($id) {
        $this->db->query("DELETE FROM ". DB_PREFIX ."bank_account WHERE bank_account_id = '". (int)$id ."'");
        $this->db->query("DELETE FROM ". DB_PREFIX ."bank_account_to_store WHERE bank_account_id = '". (int)$id ."'");
    }
    
	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bank_account_to_store WHERE bank_account_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
}
