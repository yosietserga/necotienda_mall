<?php
class ModelSaleCall extends Model {
    
	/**
	 * ModelSalePlanStore::getPlan()
	 * 
	 * @param int $plan_store_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "call SET
         `store_id`     = '". (int)$data['store_id'] ."',
         `product_id`   = '". (int)$data['product_id'] ."',
         `seller_id`    = '". (int)$data['seller_id'] ."',
         `buyer_id`     = '". (int)$data['buyer_id'] ."',
         `server`       = '". $this->db->escape($data['server']) ."',
         `date_added`   = NOW()
        ");
		return $this->db->getLastId();
	}
    
    public function getAllBySeller($id) {
		$query = $this->db->query("SELECT * 
            FROM ". DB_PREFIX ."call ca 
            LEFT JOIN ". DB_PREFIX ."customer cu ON (cu.customer_id=ca.seller_id)
            LEFT JOIN ". DB_PREFIX ."product p ON ('p.product_id=ca.product_id)
            LEFT JOIN ". DB_PREFIX ."store s ON ('s.store_id=ca.store_id)
            WHERE ca.seller_id = '". (int)$id ."'
        ");
		return $query->rows;
    }
    
    public function getAllByBuyer($id) {
		$query = $this->db->query("SELECT * 
            FROM ". DB_PREFIX ."call ca 
            LEFT JOIN ". DB_PREFIX ."customer cu ON (cu.customer_id=ca.buyer_id)
            LEFT JOIN ". DB_PREFIX ."product p ON ('p.product_id=ca.product_id)
            LEFT JOIN ". DB_PREFIX ."store s ON ('s.store_id=ca.store_id)
            WHERE ca.buyer_id = '". (int)$id ."'
        ");
		return $query->rows;
    }
    
    public function getByStoreId($id) {
		$query = $this->db->query("SELECT * 
            FROM ". DB_PREFIX ."call ca 
            LEFT JOIN ". DB_PREFIX ."customer cu ON (cu.customer_id=ca.buyer_id)
            LEFT JOIN ". DB_PREFIX ."product p ON ('p.product_id=ca.product_id)
            LEFT JOIN ". DB_PREFIX ."store s ON ('s.store_id=ca.store_id)
            WHERE ca.store_id = '". (int)$id ."'
        ");
		return $query->rows;
    }
}