<?php
class ModelSaleOffer extends Model {
    
	/**
	 * ModelSaleOffer::add()
	 * 
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "offer SET
         `product_id`       = '". (int)$data['product_id'] ."',
         `customer_id`      = '". (int)$data['customer_id'] ."',
         `offer_status_id`  = '". (int)$data['offer_status_id'] ."',
         `length_class_id`  = '". (int)$data['length_class_id'] ."',
         `store_id`         = '". (int)STORE_ID ."',
         `availability`     = '". $this->db->escape($data['availability']) ."',
         `quantity`         = '". (int)$data['quantity'] ."',
         `price`            = '". (float)$data['price'] ."',
         `iva`              = '". (int)$data['iva'] ."',
         `delivery`         = '". (int)$data['delivery'] ."',
         `delivery_time`    = '". $this->db->escape($data['delivery_time']) ."',
         `isnew`            = '". (int)$data['isnew'] ."',
         `payment_methods`  = '". $this->db->escape($data['payment_methods']) ."',
         `shipping_methods` = '". $this->db->escape($data['shipping_methods']) ."',
         `uncensored`       = '". $this->db->escape($data['uncensored']) ."',
         `comment`          = '". $this->db->escape($data['comment']) ."',
         `date_added`       = NOW()");
		return $this->db->getLastId();
	}
    
	/**
	 * ModelSaleOffer::update()
	 * 
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function update($id,$data) {
		$this->db->query("UPDATE " . DB_PREFIX . "offer SET
         `product_id`       = '". (int)$data['product_id'] ."',
         `customer_id`      = '". (int)$data['customer_id'] ."',
         `offer_status_id`  = '". (int)$data['offer_status_id'] ."',
         `length_class_id`  = '". (int)$data['length_class_id'] ."',
         `store_id`         = '". (int)STORE_ID ."',
         `availability`     = '". $this->db->escape($data['availability']) ."',
         `quantity`         = '". (int)$data['quantity'] ."',
         `price`            = '". (float)$data['price'] ."',
         `iva`              = '". (int)$data['iva'] ."',
         `delivery`         = '". (int)$data['delivery'] ."',
         `delivery_time`    = '". $this->db->escape($data['delivery_time']) ."',
         `isnew`            = '". (int)$data['isnew'] ."',
         `payment_methods`  = '". $this->db->escape($data['payment_methods']) ."',
         `shipping_methods` = '". $this->db->escape($data['shipping_methods']) ."',
         `uncensored`       = '". $this->db->escape($data['uncensored']) ."',
         `comment`          = '". $this->db->escape($data['comment']) ."',
         `date_modified`    = NOW()
         WHERE offer_id = '". (int)$id ."'");
	}
    
    public function getAllByProduct($id) {
		$query = $this->db->query("SELECT *, o.date_added AS created, o.customer_id AS customer 
            FROM ". DB_PREFIX ."offer o 
            LEFT JOIN ". DB_PREFIX ."customer cu ON (cu.customer_id=o.customer_id)
            LEFT JOIN ". DB_PREFIX ."product p ON (p.product_id=o.product_id)
            LEFT JOIN ". DB_PREFIX ."store s ON (s.store_id=o.store_id)
            WHERE o.product_id = '". (int)$id ."'
            AND o.status = 1
        ");
		return $query->rows;
    }
    
    public function getAllByBuyer($id) {
		$query = $this->db->query("SELECT * 
            FROM ". DB_PREFIX ."offer ca 
            LEFT JOIN ". DB_PREFIX ."customer cu ON (cu.customer_id=ca.buyer_id)
            LEFT JOIN ". DB_PREFIX ."product p ON ('p.product_id=ca.product_id)
            LEFT JOIN ". DB_PREFIX ."store s ON ('s.store_id=ca.store_id)
            WHERE ca.buyer_id = '". (int)$id ."'
        ");
		return $query->rows;
    }
}