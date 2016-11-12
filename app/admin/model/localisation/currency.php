<?php
class ModelLocalisationCurrency extends Model {
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "currency SET 
        title = '" . $this->db->escape($data['title']) . "', 
        code = '" . $this->db->escape($data['code']) . "',
        symbol_left = '" . $this->db->escape($data['symbol_left']) . "', 
        symbol_right = '" . $this->db->escape($data['symbol_right']) . "', 
        decimal_place = '" . $this->db->escape($data['decimal_place']) . "', 
        value = '" . $this->db->escape($data['value']) . "', 
        status = '" . (int)$data['status'] . "', 
        date_modified = NOW()");
		$this->cache->delete('currency');
        return $this->db->getLastId();
	}
	
	public function update($currency_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "currency SET 
        title = '" . $this->db->escape($data['title']) . "', 
        code = '" . $this->db->escape($data['code']) . "', 
        symbol_left = '" . $this->db->escape($data['symbol_left']) . "', 
        symbol_right = '" . $this->db->escape($data['symbol_right']) . "', 
        decimal_place = '" . $this->db->escape($data['decimal_place']) . "', 
        value = '" . $this->db->escape($data['value']) . "', 
        status = '" . (int)$data['status'] . "', 
        date_modified = NOW() 
        WHERE currency_id = '" . (int)$currency_id . "'");
		$this->cache->delete('currency');
	}
	
	public function copy($currency_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['title'] = $data['title'] ." - copia";
			$data['code'] = "copia";
			$this->add($data);
		}
	}
	
	public function delete($currency_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");
		$this->cache->delete('currency');
	}

	public function getById($currency_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");
	
		return $query->row;
	}
	
	public function getAll($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "currency";

       $criteria = array();
       
        if (!empty($data['filter_title'])) {
            $criteria[] = " LCASE(title) LIKE '%". strtolower($this->db->escape($data['filter_title'])) ."%'";
        }
        
        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $criteria[] = " date_modified BETWEEN '". $this->db->escape($data['filter_date_start']) ."' AND '". $this->db->escape($data['filter_date_end']) ."'";
        } elseif (!empty($data['filter_date_start']) && empty($data['filter_date_end'])) {
            $criteria[] = " date_modified BETWEEN '". $this->db->escape($data['filter_date_start']) ."' AND '". $this->db->escape(date('Y-m-d h:i:s')) ."'";
        }
        
        if ($criteria) {
            $sql .= " WHERE ". implode(" AND ", $criteria);
        }
        
        $sort_data = array(
				'title',
				'code',
				'value',
				'date_modified'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
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

	public function updateAll() {
		if (extension_loaded('curl')) {
			$data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified> '" . date(strtotime('-1 day')) . "'");

			foreach ($query->rows as $result) {
				$data[] = $this->config->get('config_currency') . $result['code'] . '=X';
			}	
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$content = curl_exec($ch);
			
			curl_close($ch);
			
			$lines = explode("\n", trim($content));
				
			foreach ($lines as $line) {
				$currency = substr($line, 4, 3);
				$value = substr($line, 11, 6);
				
				if ((float)$value) {
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$value . "', date_modified = NOW() WHERE code = '" . $this->db->escape($currency) . "'");
				}
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = NOW() WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");
			
			$this->cache->delete('currency');
		}
	}
	
	public function getAllTotal() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");
		
		return $query->row['total'];
	}
}