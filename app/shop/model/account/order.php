<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * 
        FROM `" . DB_PREFIX . "order` 
        WHERE order_id = '" . (int)$order_id . "' 
        AND customer_id = '" . (int)$this->customer->getId() . "' 
        AND order_status_id> '0'");
	
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * 
            FROM `" . DB_PREFIX . "country` 
            WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$order_data = array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_id'              => $order_query->row['invoice_id'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency'                => $order_query->row['currency'],
				'value'                   => $order_query->row['value'],
				'coupon_id'               => $order_query->row['coupon_id'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
			
			return $order_data;
		} else {
			return false;	
		}
	}
	 
	public function getOrders($data=array()) {
		if ($start < 0) $start = 0;
        
		$sql = "SELECT *, os.name as status, o.date_added AS dateAdded 
        FROM `" . DB_PREFIX . "order` o 
        LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id)";	
	
        $criteria = array();
        
        $criteria[] = " customer_id = '" . (int)$this->customer->getId() . "' ";
        $criteria[] = " o.order_status_id > '0' ";
        $criteria[] = " os.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        
        if ($data['order_status_id']) {
            $criteria[] = " o.order_status_id = '" . (int)$data['order_status_id'] . "' ";
        }
        
        if ($data['order_id']) {
            $criteria[] = " o.order_id = '" . (int)$data['order_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
            
        $sql .= "ORDER BY o.date_added DESC, o.order_id DESC ";
    			
	    if ($start < 0) {
    	   $start = 0;
        }
    		
        $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        
		$query = $this->db->query($sql);	
	
		return $query->rows;
	}
	
	public function addOrderHistory($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
        order_status_id = '" . (int)$data['order_status_id'] . "', 
        date_modified = NOW() 
        WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET 
              order_id = '" . (int)$order_id . "', 
              order_status_id = '" . (int)$data['order_status_id'] . "', 
              notify = '1', 
              comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', 
              date_added = NOW()");
	}

    public function updateStatus($order_id,$order_status_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
        order_status_id = '" . (int)$order_status_id . "' 
        WHERE order_id = '" . (int)$order_id . "'");
    }

    public function updatePaymentMethod($order_id,$method) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
        payment_method = '" . $this->db->escape($method) . "' 
        WHERE order_id = '" . (int)$order_id . "'");
    }

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->rows;
	}
	
	public function getOrderStatuses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->rows;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
	
		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
	
		return $query->rows;
	}	

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND oh.notify = '1' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
	
		return $query->rows;
	}	

	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' ORDER BY name");
	
		return $query->rows; 
	}	

	public function getTotalOrders($data) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o 
        LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) ";
		
        $criteria = array();
        
        $criteria[] = " customer_id = '" . (int)$this->customer->getId() . "' ";
        $criteria[] = " o.order_status_id > '0' ";
        
        if ($data['order_status_id']) {
            $criteria[] = " o.order_status_id = '" . (int)$data['order_status_id'] . "' ";
        }
        
        if ($data['order_id']) {
            $criteria[] = " o.order_id = '" . (int)$data['order_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $query = $this->db->query($sql);
        
		return $query->row['total'];
	}
		
	public function getTotalOrderProductsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->row['total'];
	}
}