<?php 
class ModelLocalisationStockStatus extends Model {

	public function getStockStatuses($data = array()) {
		
			$stock_status_data = $this->cache->get('stock_status.' . $this->config->get('config_language_id'));
		
			if (!$stock_status_data) {
				$query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
	
				$stock_status_data = $query->rows;
			
				$this->cache->set('stock_status.' . $this->config->get('config_language_id'), $stock_status_data);
			}	
	
			return $stock_status_data;
	}

}