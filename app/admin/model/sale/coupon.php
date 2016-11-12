<?php
/**
 * ModelSaleCoupon
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelSaleCoupon extends Model {
	/**
	 * ModelSaleCoupon::addCoupon()
	 * 
	 * @param mixed $data
     * @see DB
	 * @return void
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET 
          code      = '" . $this->db->escape($data['code']) . "', 
          discount  = '" . (float)$data['discount'] . "', 
          type      = '" . $this->db->escape($data['type']) . "', 
          total     = '" . (float)$data['total'] . "', 
          logged    = '" . (int)$data['logged'] . "', 
          shipping  = '" . (int)$data['shipping'] . "', 
          date_start= '" . $this->db->escape($data['date_start']) . "', 
          date_end  = '" . $this->db->escape($data['date_end']) . "', 
          uses_total= '" . (int)$data['uses_total'] . "', 
          uses_customer = '" . (int)$data['uses_customer'] . "', 
          status    = '" . (int)$data['status'] . "', 
          date_added= NOW()");

      	$coupon_id = $this->db->getLastId();

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET 
            coupon_id   = '" . (int)$coupon_id . "', 
            language_id = '" . (int)$language_id . "', 
            name        = '" . $this->db->escape($value['name']) . "', 
            description = '" . $this->db->escape($value['description']) . "'");
      	}
		
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_to_store SET 
                store_id  = '". intval($store) ."', 
                coupon_id = '". intval($coupon_id) ."'");
            }
        
		if (isset($data['Products'])) {
            foreach ($data['Products'] as $product_id => $value) {
                if ($value == 0) continue;
        		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product (product_id, coupon_id) VALUES ('" . (int)$product_id . "','" . (int)$coupon_id."')");
            }
		}
        return $coupon_id;
	}
	
	/**
	 * ModelSaleCoupon::editCoupon()
	 * 
	 * @param int $coupon_id
	 * @param mixed $data
     * @see DB
	 * @return void
	 */
	public function update($coupon_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET 
        code        = '" . $this->db->escape($data['code']) . "', 
        discount    = '" . (float)$data['discount'] . "', 
        type        = '" . $this->db->escape($data['type']) . "', 
        total       = '" . (float)$data['total'] . "', 
        logged      = '" . (int)$data['logged'] . "', 
        shipping    = '" . (int)$data['shipping'] . "', 
        date_start  = '" . $this->db->escape($data['date_start']) . "', 
        date_end    = '" . $this->db->escape($data['date_end']) . "', 
        uses_total  = '" . (int)$data['uses_total'] . "', 
        uses_customer = '" . (int)$data['uses_customer'] . "', 
        status      = '" . (int)$data['status'] . "' 
        WHERE coupon_id = '" . (int)$coupon_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET 
            coupon_id   = '" . (int)$coupon_id . "', 
            language_id = '" . (int)$language_id . "', 
            name        = '" . $this->db->escape($value['name']) . "', 
            description = '" . $this->db->escape($value['description']) . "'");
      	}
		
            $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_to_store WHERE coupon_id = '". (int)$coupon_id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_to_store SET 
                store_id  = '". intval($store) ."', 
                coupon_id = '". intval($coupon_id) ."'");
            }
        
		if (isset($data['Products'])) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
            foreach ($data['Products'] as $product_id => $value) {
                if ($value == 0) continue;
        		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product (product_id, coupon_id) VALUES ('" . (int)$product_id . "','" . (int)$coupon_id."')");
            }
		}		
	}
	
	/**
	 * ModelStoreProduct::copy()
	 * 
	 * @param int $product_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function copy($coupon_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon p 
        LEFT JOIN " . DB_PREFIX . "coupon_description pd ON (p.coupon_id = pd.coupon_id) 
        WHERE p.coupon_id = '" . (int)$coupon_id . "' 
        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['code'] = uniqid();
			$data = array_merge($data, array('coupon_description' => $this->getCouponDescriptions($coupon_id)));
			$data = array_merge($data, array('Products' => $this->getCouponProducts($coupon_id)));
			$this->addCoupon($data);
		}
	}
	
	/**
	 * ModelSaleCoupon::deleteCoupon()
	 * 
	 * @param int $coupon_id
     * @see DB
	 * @return void
	 */
	public function delete($coupon_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_to_store WHERE coupon_id = '" . (int)$coupon_id . "'");		
	}
	
	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_to_store WHERE coupon_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelSaleCoupon::getCoupon()
	 * 
	 * @param int $coupon_id
     * @see DB
	 * @return void
	 */
	public function getCoupon($coupon_id) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		return $query->row;
	}
	
	/**
	 * ModelSaleCoupon::getCoupons()
	 * 
	 * @param mixed $data
     * @see DB
	 * @return array sql records
	 */
	public function getCoupons($data = array()) {
		$sql = "SELECT c.coupon_id, cd.name, c.code, c.discount, c.date_start, c.date_end, c.status FROM " . DB_PREFIX . "coupon c LEFT JOIN " . DB_PREFIX . "coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'cd.name',
			'c.code',
			'c.discount',
			'c.date_start',
			'c.date_end',
			'c.status'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cd.name";	
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
	 * ModelSaleCoupon::getCouponDescriptions()
	 * 
	 * @param int $coupon_id
     * @see DB
	 * @return array sql records
	 */
	public function getCouponDescriptions($coupon_id) {
		$coupon_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $coupon_description_data;
	}

	/**
	 * ModelSaleCoupon::getCouponProducts()
	 * 
	 * @param int $coupon_id
     * @see DB
	 * @return array sql records
	 */
	public function getCouponProducts($coupon_id) {
		$coupon_product_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}
		
		return $coupon_product_data;
	}
	
	/**
	 * ModelSaleCoupon::getTotalCoupons()
	 * 
     * @see DB
	 * @return int Count sql records
	 */
	public function getTotalCoupons() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");
		
		return $query->row['total'];
	}		
}
