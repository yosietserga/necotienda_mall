<?php
class ModelCatalogStore extends Model {
	public function add($data) {
        $result = $this->db->query("INSERT INTO " . DB_PREFIX . "store SET 
        customer_id     = '". (int)$data['customer_id'] ."',
        storename       = '". $this->db->escape($data['storename']) ."',
        storeurl        = '". $this->db->escape($data['storeurl']) ."',
        code            = '". $this->db->escape($data['code']) ."',
        activation_code = '". $this->db->escape($data['activation_code']) ."',
        ip              = '". $this->db->escape($_SERVER['REMOTE_ADDR']) ."',
        server          = '". $this->db->escape(serialize($_SERVER)) ."',
        status          = 1,
        date_added      = NOW()");
      	
		$store_id = $this->db->getLastId();
        
        $this->db->query("INSERT INTO ". DB_PREFIX ."store_to_plan SET 
        store_id        = '". (int)$store_id ."',
        plan_store_id   = '". (int)$data['plan_store_id'] ."',
        date_added      = NOW()");
        
        $products = $this->db->query("UPDATE ". DB_PREFIX ."product SET store_id = '". (int)$store_id ."' WHERE customer_id = '". (int)$data['customer_id'] ."'");
        
        return $store_id;	
	}
			
	public function getStoreByUrl($url) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE storeurl = '" . $this->db->escape($url) . "'");
		return $query->row;
	}
    
	public function getByCustomerId($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE customer_id = '" . intval($customer_id) . "'");
		return $query->row;
	}
    
    public function getTotalStoresByName($name) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE storename = '" . $this->db->escape($name) . "'");
		return $query->row['total'];
    }
}
?>
