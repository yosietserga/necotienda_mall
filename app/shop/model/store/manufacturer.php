<?php
class ModelStoreManufacturer extends Model {
	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m 
        LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
        WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "'
        AND m2s.store_id = '". (int)STORE_ID ."' ");
	
		return $query->row;	
	}
	
	public function getManufacturers() {
		$manufacturer = $this->cache->get('manufacturer.' . (int)C_CODE);
		
		if (!$manufacturer) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m 
            LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
            WHERE m2s.store_id = '". (int)STORE_ID ."' 
            ORDER BY sort_order, LCASE(m.name) ASC");
	
			$manufacturer = $query->rows;
			
			$this->cache->set('manufacturer.' . (int)C_CODE, $manufacturer);
		}
		
		return $manufacturer;
	} 	
    
	public function updateStats($manufacturer_id,$customer_id) {
	   $this->load->library('browser');
       $browser = new Browser;
		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET viewed = viewed + 1 WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("INSERT " . DB_PREFIX . "stat SET 
        `object_id`     = '". (int)$manufacturer_id ."',
        `store_id`      = '". (int)STORE_ID ."',
        `customer_id`   = '". (int)$customer_id ."',
        `object_type`   = 'manufacturer',
        `server`        = '". $this->db->escape(serialize($_SERVER)) ."',
        `session`       = '". $this->db->escape(serialize($_SESSION)) ."',
        `request`       = '". $this->db->escape(serialize($_REQUEST)) ."',
        `store_url`     = '". $this->db->escape($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']) ."',
        `ref`           = '". $this->db->escape($_SERVER['HTTP_REFERER']) ."',
        `browser`       = '". $this->db->escape($browser->getBrowser()) ."',
        `browser_version`= '". $this->db->escape($browser->getVersion()) ."',
        `os`            = '". $this->db->escape($browser->getPlatform()) ."',
        `ip`            = '". $this->db->escape($_SERVER['REMOTE_ADDR']) ."',
        `date_added`    = NOW()");
	}
    
    /**
     * ModelStoreManufacturer::getProperty()
     * 
     * Obtener una propiedad de la pagina
     * 
     * @param int $id manufacturer_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_property 
        WHERE `manufacturer_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
  
		return unserialize(str_replace("\'","'",$query->row['value']));
	}
    
    /**
     * ModelStoreManufacturer::getAllProperties()
     * 
     * Obtiene todas las propiedades de la pagina
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($manufacturer_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($manufacturer_id, 'NombreDelGrupo');
     * 
     * @param int $id manufacturer_id
     * @param varchar $group
     * @return array all properties
     * */
	public function getAllProperties($id, $group='*') {
        if ($group=='*') {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_property 
            WHERE `manufacturer_id` = '" . (int)$id . "'");
        } else {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_property 
            WHERE `manufacturer_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
        
		return $query->rows;
	}
}
