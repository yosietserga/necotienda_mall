<?php
/**
 * ModelStoreDownload
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 */
class ModelStoreDownload extends Model {
	/**
	 * ModelStoreDownload::add()
	 * 
	 * @param mixed $data
	 * @return void
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "download SET remaining = '" . (int)$data['remaining'] . "', date_added = NOW()");

      	$download_id = $this->db->getLastId(); 

      	if (isset($data['download'])) {
        	$this->db->query("UPDATE " . DB_PREFIX . "download SET 
            filename = '" . $this->db->escape($data['download']) . "', 
            mask = '" . $this->db->escape($data['mask']) . "' 
            WHERE download_id = '" . (int)$download_id . "'");
      	}

      	foreach ($data['download_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET 
            download_id = '" . (int)$download_id . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($value['name']) . "'");
      	}
        
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "download_to_store SET 
                store_id  = '". intval($store) ."', 
                download_id = '". intval($download_id) ."'");
            }
        
        
        return $download_id;
	}
	
	/**
	 * ModelStoreDownload::editDownload()
	 * 
	 * @param int $download_id
	 * @param mixed $data
	 * @return void
	 */
	public function update($download_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "download SET remaining = '" . (int)$data['remaining'] . "' WHERE download_id = '" . (int)$download_id . "'");
      	
		if (isset($data['download'])) {
        	$this->db->query("UPDATE " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['download']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE download_id = '" . (int)$download_id . "'");
      	}
		
		if (isset($data['update'])) {
        	$query = $this->db->query("SELECT filename from " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
     		$filename = $query->row['filename'];
      		$this->db->query("UPDATE " . DB_PREFIX . "order_download SET `filename` = '" . $this->db->escape($data['download']) . "', mask = '" . $this->db->escape(basename($data['mask'])) . "' WHERE `filename` = '" . $this->db->escape($filename) . "'");
      	}
		
      	$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

      	foreach ($data['download_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
      	}
        
            $this->db->query("DELETE FROM " . DB_PREFIX . "download_to_store WHERE download_id = '". (int)$download_id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "download_to_store SET 
                store_id  = '". intval($store) ."', 
                download_id = '". intval($download_id) ."'");
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
	public function copy($download_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download d 
        LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) 
        WHERE d.download_id = '" . (int)$download_id . "' 
        AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['download'] = $data['filename'];
			$data = array_merge($data, array('download_description' => $this->getDescriptions($download_id)));
			$this->add($data);
		}
	}
	
	/**
	 * ModelContentPost::delete()
	 * 
	 * @param int $post_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function delete($download_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_to_store WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE object_id='" . (int)$download_id. "' AND object_type = 'download'");
		$this->cache->delete("download");
	}	

	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_to_store WHERE download_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelStoreDownload::getDownload()
	 * 
	 * @param int $download_id
	 * @return array sql record
	 */
	public function getDownload($download_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
		
		return $query->row;
	}

	/**
	 * ModelStoreDownload::getAll()
	 * 
	 * @param mixed $data
	 * @return array sql records
	 */
	public function getAll($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		$sort_data = array(
			'dd.name',
			'd.remaining'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY dd.name";	
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
	 * ModelStoreDownload::getDescriptions()
	 * 
	 * @param int $download_id
	 * @return array sql records
	 */
	public function getDescriptions($download_id) {
		$download_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");
		
		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $download_description_data;
	}
	
	/**
	 * ModelStoreDownload::getAllTotal()
	 * 
	 * @return int Count sql records
	 */
	public function getAllTotal() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download");
		
		return $query->row['total'];
	}	
    
    /**
     * ModelStoreDownload::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "download` SET `status` = '1' WHERE `download_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreDownload::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "download` SET `status` = '0' WHERE `download_id` = '" . (int)$id . "'");
        return $query;
     }
}
