<?php
class ModelAccountPayment extends Model {
	public function add($data) {
		$order_query = $this->db->query("INSERT INTO `" . DB_PREFIX . "order_payment` SET
         `order_id`     = '". (int)$data['order_id'] ."',
         `customer_id`  = '". (int)$this->customer->getId() ."',
         `store_id`     = '". (int)STORE_ID ."',
         `bank_account_id` = '". (int)$data['bank_account_id'] ."',
         `order_payment_status_id` = '". (int)$data['order_payment_status_id'] ."',
         `transac_number` = '". $this->db->escape($data['transac_number']) ."',
         `bank_from`    = '". $this->db->escape($data['bank_from']) ."',
         `payment_method` = '". $this->db->escape($data['payment_method']) ."',
         `amount`       = '". round((float)$data['amount'],2) ."',
         `comment`      = '". $this->db->escape($data['comment']) ."',
         `date_added`   = NOW()");
         return $this->db->getLastId();
	}
	 
	public function getById($id) {
		$sql = "SELECT *, ops.name AS status, op.date_added AS dateAdded
        FROM `" . DB_PREFIX . "order_payment` op 
        LEFT JOIN `" . DB_PREFIX . "order_payment_status` ops ON (ops.order_payment_status_id=op.order_payment_status_id)
        LEFT JOIN `" . DB_PREFIX . "bank_account` ba ON (ba.bank_account_id=op.bank_account_id)
        LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id=op.order_id)";	
	
        $criteria = array();
        
        $criteria[] = " op.customer_id = '" . (int)$this->customer->getId() . "' ";
        $criteria[] = " op.order_payment_id = '" . (int)$id . "' ";
        $criteria[] = " ops.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
            
		$query = $this->db->query($sql);	
	
		return $query->row;
	}
    
	public function getPayments($data=null) {
		if ($data['start'] < 0) $data['start'] = 0;
        
		$sql = "SELECT *, ops.name AS status, op.date_added AS dateAdded
        FROM `" . DB_PREFIX . "order_payment` op 
        LEFT JOIN `" . DB_PREFIX . "order_payment_status` ops ON (ops.order_payment_status_id=op.order_payment_status_id)
        LEFT JOIN `" . DB_PREFIX . "bank_account` ba ON (ba.bank_account_id=op.bank_account_id)
        LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id=op.order_id)";	
	
        $criteria = array();
        
        $criteria[] = " op.customer_id = '" . (int)$this->customer->getId() . "' ";
        $criteria[] = " ops.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        
        if ($data['order_payment_status_id']) {
            $criteria[] = " op.order_payment_status_id = '" . (int)$data['order_payment_status_id'] . "' ";
        }
        
        if ($data['order_payment_id']) {
            $criteria[] = " op.order_payment_id = '" . (int)$data['order_payment_id'] . "' ";
        }
        
        if ($data['order_id']) {
            $criteria[] = " op.order_id = '" . (int)$data['order_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
            
        $sql .= "ORDER BY op.date_added DESC ";
    			
	    if ($data['start'] < 0) {
    	   $data['start'] = 0;
        }
    			
        $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        
		$query = $this->db->query($sql);	
	
		return $query->rows;
	}
    
	public function getTotalPayments($data=null) {
		$sql = "SELECT COUNT(*) AS total
        FROM `" . DB_PREFIX . "order_payment` op 
        LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id=op.order_id)";	
	
        $criteria = array();
        
        $criteria[] = " op.customer_id = '" . (int)$this->customer->getId() . "' ";
        
        if ($data['order_payment_status_id']) {
            $criteria[] = " op.order_payment_status_id = '" . (int)$data['order_payment_status_id'] . "' ";
        }
        
        if ($data['order_id']) {
            $criteria[] = " op.order_id = '" . (int)$data['order_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
            
		$query = $this->db->query($sql);	
	
		return $query->row['total'];
	}
    
	public function getSumPayments($data=null) {
		$sql = "SELECT SUM(amount) AS total
        FROM `" . DB_PREFIX . "order_payment` op 
        LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id=op.order_id)";	
	
        $criteria = array();
        
        $criteria[] = " op.customer_id = '" . (int)$this->customer->getId() . "' ";
        
        if ($data['order_payment_status_id']) {
            $criteria[] = " op.order_payment_status_id = '" . (int)$data['order_payment_status_id'] . "' ";
        }
        
        if ($data['order_id']) {
            $criteria[] = " op.order_id = '" . (int)$data['order_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
		$query = $this->db->query($sql);	
	
		return $query->row['total'];
	}
    
	public function getPaymentStatuses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->rows;
	}
	
}