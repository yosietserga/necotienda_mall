<?php 
class ModelLocalisationStockStatus extends Model {
	public function add($data) {
		foreach ($data['stock_status'] as $language_id => $value) {
			if (isset($stock_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
				
				$stock_status_id = $this->db->getLastId();
			}
		}
		
		$this->cache->delete('stock_status');
	}

	public function update($stock_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		foreach ($data['stock_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->cache->delete('stock_status');
	}
	
	public function delete($stock_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");
	
		$this->cache->delete('stock_status');
	}
		
	public function getById($stock_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getAll($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
      		
			$sql .= " ORDER BY name";	
			
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
		} else {
			$stock_status_data = $this->cache->get('stock_status.' . $this->config->get('config_language_id'));
		
			if (!$stock_status_data) {
				$query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
	
				$stock_status_data = $query->rows;
			
				$this->cache->set('stock_status.' . $this->config->get('config_language_id'), $stock_status_data);
			}	
	
			return $stock_status_data;			
		}
	}
	
	public function getDescriptions($stock_status_id) {
		$stock_status_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");
		
		foreach ($query->rows as $result) {
			$stock_status_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $stock_status_data;
	}
	
	public function getAllTotal() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}	
}