<?php  
/**
 * ModelSaleOrder
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 */
class ModelSaleOrder extends Model {
	
	public function add($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET 
        store_name = '" . $this->db->escape($data['store_name']) . "', 
        store_url = '" . $this->db->escape($data['store_url']) . "', 
        firstname = '" . $this->db->escape($data['firstname']) . "', 
        lastname = '" . $this->db->escape($data['lastname']) . "', 
        telephone = '" . $this->db->escape($data['telephone']) . "', 
        email = '" . $this->db->escape($data['email']) . "', 
        shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
        shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
        shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
        shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
        shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
        shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
        shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
        shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
        shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
        shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
        payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
        payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
        payment_company = '" . $this->db->escape($data['payment_company']) . "', 
        payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
        payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
        payment_city = '" . $this->db->escape($data['payment_city']) . "', 
        payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
        payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
        payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
        payment_country = '" . $this->db->escape($data['payment_country']) . "', 
        payment_country_id = '" . (int)$data['payment_country_id'] . "', 
        ip = '" . $this->db->escape('0.0.0.0') . "', 
        total = '" . $this->db->escape(preg_replace("/[^0-9.]/",'', $data['total'])) . "', 
        date_modified = NOW()");
		
		$order_id = $this->db->getLastId();
		
		if (isset($data['product'])) {
			foreach ($data['product'] as $product) {
				if ($product['product_id']) {
					$product_query = $this->db->query("SELECT * 
                    FROM " . DB_PREFIX . "product p 
                    LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                    WHERE p.product_id='" . (int)$product['product_id'] . "'");
										
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET 
                    order_id = '" . (int)$order_id . "', 
                    product_id = '" . (int)$product['product_id'] . "', 
                    name = '" . $this->db->escape($product_query->row['name']) . "', 
                    model = '" . $this->db->escape($product_query->row['model']) . "', 
                    price = '" . $this->db->escape(preg_replace("/[^0-9.]/",'', $product['price'])) . "', 
                    total = '" . $this->db->escape(preg_replace("/[^0-9.]/",'', $product['total'])) . "', 
                    quantity = '" . $this->db->escape($product['quantity']) . "'");
				}
			}
		}
	}
	
	public function update($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
        telephone = '" . $this->db->escape($data['telephone']) . "', 
        email = '" . $this->db->escape($data['email']) . "', 
        shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
        shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
        shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
        shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
        shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
        shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
        shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
        shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
        shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
        shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
        payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
        payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
        payment_company = '" . $this->db->escape($data['payment_company']) . "', 
        payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
        payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
        payment_city = '" . $this->db->escape($data['payment_city']) . "', 
        payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
        payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
        payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
        payment_country = '" . $this->db->escape($data['payment_country']) . "', 
        payment_country_id = '" . (int)$data['payment_country_id'] . "', 
        shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
        payment_method = '" . $this->db->escape($data['payment_method']) . "', 
        date_modified = NOW() 
        WHERE order_id = '" . (int)$order_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
		if (isset($data['product'])) {
			foreach ($data['product'] as $product) {
				if ($product['product_id']) {
					$product_query = $this->db->query("SELECT * 
                    FROM " . DB_PREFIX . "product p 
                    LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                    WHERE p.product_id='" . (int)$product['product_id'] . "'");
										
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET 
                    order_id = '" . (int)$order_id . "', 
                    product_id = '" . (int)$product['product_id'] . "', 
                    name = '" . $this->db->escape($product_query->row['name']) . "', 
                    model = '" . $this->db->escape($product_query->row['model']) . "', 
                    price = '" . $this->db->escape(preg_replace("/[^0-9.]/",'', $product['price'])) . "', 
                    total = '" . $this->db->escape(preg_replace("/[^0-9.]/",'', $product['total'])) . "', 
                    quantity = '" . $this->db->escape($product['quantity']) . "'");
				}
			}
		}
		
		foreach ($data['totals'] as $key => $value) {
			$this->db->query("UPDATE " . DB_PREFIX . "order_total SET text = '" . $this->db->escape($value) . "' WHERE order_total_id = '" . (int)$key . "'");
		}
	}
	
	public function delete($order_id) {
		if ($this->config->get('config_stock_subtract')) {
			$order_query = $this->db->query("SELECT * 
            FROM `" . DB_PREFIX . "order` 
            WHERE order_status_id > '0' 
            AND order_id = '" . (int)$order_id . "'");
			
			if ($order_query->num_rows) {
				$product_query = $this->db->query("SELECT * 
                FROM " . DB_PREFIX . "order_product 
                WHERE order_id = '" . (int)$order_id . "'");
				
				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET 
                    quantity = (quantity + " . (int)$product['quantity'] . ") 
                    WHERE product_id = '" . (int)$product['product_id'] . "'");
					
					$option_query = $this->db->query("SELECT * 
                    FROM " . DB_PREFIX . "order_option 
                    WHERE order_id = '" . (int)$order_id . "' 
                    AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET 
                        quantity = (quantity + " . (int)$product['quantity'] . ") 
                        WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' 
                        AND subtract = '1'");
					}				
				}
			}
		}
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
	  	$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function addHistory($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
        order_status_id = '" . (int)$data['order_status_id'] . "', 
        date_modified = NOW() 
        WHERE order_id = '" . (int)$order_id . "'");

		if ($data['append']) {
      		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET 
              order_id = '" . (int)$order_id . "', 
              order_status_id = '" . (int)$data['order_status_id'] . "', 
              notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', 
              comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', 
              date_added = NOW()");
		}

      	if ($data['notify']) {
        	$order_query = $this->db->query("SELECT *, os.name AS status 
            FROM `" . DB_PREFIX . "order` o 
            LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) 
            LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) 
            WHERE o.order_id = '" . (int)$order_id . "'");
	    	
			if ($order_query->num_rows) {
				$language = new Language($order_query->row['directory']);
				$language->load($order_query->row['filename']);
				//$language->load('mail/order');
                //TODO: cargar la plantilla de email asociada con esta accion

				$subject = sprintf($language->get('text_subject'), $order_query->row['store_name'], $order_id);
	
				$message  = "<p><b>". $language->get('text_order') . ' ' . $order_id ."</b></p>";
				$message .= "<p>". $language->get('text_date_added') . ' ' . date('d-m-Y', strtotime($order_query->row['date_added'])) ."</p>";
				$message .= "<p>". $language->get('text_order_status') . '&nbsp;<b>' . $order_query->row['status'] ."</b></p>";
				$message .= "<p>". $language->get('text_invoice') ."</p>";
				$message .= "<a href=\"". html_entity_decode($order_query->row['store_url'] . 'index.php?r=account/invoice&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\">Ver Pedido</a>";
				
				if ($data['comment']) { 
					$message .= "<br /><p>". $language->get('text_comment') . "</p>";
					$message .= "<br /><p>". strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "</p>";
				}
				
				$message .= $language->get('text_footer');

                $this->load->library('email/mailer');
                $mailer = new Mailer;
                    if ($this->config->get('config_smtp_method')=='smtp') {
                        $mailer->IsSMTP();
            			$mailer->Hostname = $this->config->get('config_smtp_host');
            			$mailer->Username = $this->config->get('config_smtp_username');
            			$mailer->Password = base64_decode($this->config->get('config_smtp_password'));
            			$mailer->Port     = $this->config->get('config_smtp_port');
                        $mailer->Timeout  = $this->config->get('config_smtp_timeout');
                        $mailer->SMTPSecure = $this->config->get('config_smtp_ssl');
                        $mailer->SMTPAuth = ($this->config->get('config_smtp_auth')) ? true : false;
                        
                    } elseif ($this->config->get('config_smtp_method')=='sendmail') {
                        $mailer->IsSendmail();
                    } else {
                        $mailer->IsMail();
                    }
                    $mailer->IsHTML();
        			$mailer->AddAddress($order_query->row['email'],$order_query->row['payment_firstname']);
        			$mailer->SetFrom($this->config->get('config_email'),$this->config->get('config_name'));
        	  		$mailer->Subject = $subject;
        	  		$mailer->Body = $message;
                    $mailer->Send();
			}
		}
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			
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
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
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
	
	public function getAll($data = array()) {
		$sql = "SELECT *, CONCAT(o.firstname, ' ', o.lastname) AS name, os.name AS status
                FROM `" . DB_PREFIX . "order` o
                LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id)";

		$implode = array();
        
        $implode[] = " os.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_order_status_id'])) {
			$implode[] = " o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$implode[] = " o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_order_id'])) {
			$implode[] = " o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = " LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . strtolower($this->db->escape($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $implode[] = " o.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
		} elseif (!empty($data['filter_date_start'])) {
            $implode[] = " o.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}
    
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$implode[] = " o.total = '" . (float)$data['filter_total'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'o.order_id',
			'name',
			'status',
			'o.date_added',
			'o.total'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY o.order_id";	
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
	
	public function generateInvoiceId($order_id) {
		$query = $this->db->query("SELECT MAX(invoice_id) AS invoice_id FROM `" . DB_PREFIX . "order`");
		
		if ($query->row['invoice_id']) {
			$invoice_id = (int)$query->row['invoice_id'] + 1;
		} elseif ($this->config->get('config_invoice_id')) {
			$invoice_id = $this->config->get('config_invoice_id');
		} else {
			$invoice_id = 1;
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_id = '" . (int)$invoice_id . "', invoice_prefix = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
		return $this->config->get('config_invoice_prefix') . $invoice_id;
	}
	
	public function getProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->rows;
	}

	public function getOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
	
		return $query->rows;
	}
	
	public function getTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
	
		return $query->rows;
	}	

	public function getHistory($order_id) { 
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
	
		return $query->rows;
	}	

	public function getDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' ORDER BY name");
	
		return $query->rows; 
	}	
				
	public function getAllTotal($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o";

		$implode = array();
		
		if (!empty($data['filter_order_status_id'])) {
			$implode[] = " o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$implode[] = " o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_order_id'])) {
			$implode[] = " o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = " LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . strtolower($this->db->escape($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $implode[] = " o.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
		} elseif (!empty($data['filter_date_start'])) {
            $implode[] = " o.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}
    
		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$implode[] = " o.total = '" . (float)$data['filter_total'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	} 
	
	public function getHistoryTotalByOrderStatusId($order_status_id) {
	  	$query = $this->db->query("SELECT oh.order_id FROM " . DB_PREFIX . "order_history oh LEFT JOIN `" . DB_PREFIX . "order` o ON (oh.order_id = o.order_id) WHERE oh.order_status_id = '" . (int)$order_status_id . "' AND o.order_status_id> '0' GROUP BY order_id");

		return $query->num_rows;
	}

	public function getAllTotalWithoutInvoice() {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order WHERE invoice_id < 0");

		return $query->row['total'];
	}

	public function getAllTotalByOrderStatusId($order_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id> '0'");
		
		return $query->row['total'];
	}
	
	public function getAllTotalByLanguageId($language_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id> '0'");
		
		return $query->row['total'];
	}	
	
	public function getAllTotalByCurrencyId($currency_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id> '0'");
		
		return $query->row['total'];
	}	
	
	public function getTotalSales() {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id> '0'");
		
		return $query->row['total'];
	}
	
	public function getTotalSalesByYear($year) {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id> '0' AND YEAR(date_added) = '" . (int)$year . "'");
		
		return $query->row['total'];
	}	
}
