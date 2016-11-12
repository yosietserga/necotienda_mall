<?php
/**
 * ModelStoreManufacturer
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelStoreManufacturer extends Model {
	/**
	 * ModelStoreManufacturer::add()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET 
          name = '" . $this->db->escape($data['name']) . "', 
          sort_order = '" . (int)$data['sort_order'] . "', 
          date_added = NOW()");
		
		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}
		
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET 
                store_id  = '". intval($store) ."', 
                manufacturer_id = '". intval($manufacturer_id) ."'");
            }
        
		if (!empty($data['keyword'])) {
            $languages = $this->db->query("SELECT * FROM ". DB_PREFIX ."language");
            foreach ($languages->rows as $language) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int)$language['language_id'] ."', 
                object_id   = '" . (int)$manufacturer_id . "', 
                object_type = 'manufacturer', 
                query       = 'manufacturer_id=" . (int)$manufacturer_id . "', 
                keyword     = '" . $this->db->escape($data['keyword']) . "'");
            }
		}
		
        foreach ($data['Products'] as $product_id => $value) {
            if ($value == 0) continue;
    		$this->db->query("UPDATE " . DB_PREFIX . "product SET manufacturer_id = '" . (int)$manufacturer_id."' WHERE product_id = '" . (int)$product_id."'");
        }
        
		$this->cache->delete('manufacturer');
        return $manufacturer_id;
	}
	
	/**
	 * ModelStoreManufacturer::editManufacturer()
	 * 
	 * @param int $manufacturer_id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function update($manufacturer_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET 
          name = '" . $this->db->escape($data['name']) . "', 
          sort_order = '" . (int)$data['sort_order'] . "' 
          WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        //TODO: realizar una sola consulta al actualizar, no hace falta actualizar de nuevo si se cambio la imagen
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}
        
            $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '". (int)$manufacturer_id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET 
                store_id  = '". intval($store) ."', 
                manufacturer_id = '". intval($manufacturer_id) ."'");
            }
        
        
		if (!empty($data['keyword'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias 
                WHERE object_id = '" . (int)$manufacturer_id . "' 
                AND language_id = '" . (int)$language_id . "' 
                AND object_type = 'manufacturer'");
                
            $languages = $this->db->query("SELECT * FROM ". DB_PREFIX ."language");
            foreach ($languages->rows as $language) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int)$language['language_id'] ."', 
                object_id   = '" . (int)$manufacturer_id . "', 
                object_type = 'manufacturer', 
                query       = 'manufacturer_id=" . (int)$manufacturer_id . "', 
                keyword     = '" . $this->db->escape($data['keyword']) . "'");
            }
		}
		
		if (!empty($data['Products'])) {
            foreach ($data['Products'] as $product_id => $value) {
                if ($value == 0) continue;
        		$this->db->query("UPDATE " . DB_PREFIX . "product SET manufacturer_id = '" . (int)$manufacturer_id."' WHERE product_id = '" . (int)$product_id."'");
            }
        }
        
		$this->cache->delete('manufacturer');
        return $manufacturer_id;
	}
	
	/**
	 * ModelStoreManufacturer::deleteManufacturer()
	 * 
	 * @param int $manufacturer_id
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function delete($manufacturer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_stats WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE object_id='" . (int)$manufacturer_id. "' AND object_type = 'manufacturer'");
			
		$this->cache->delete('manufacturer');
	}	
	
	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelStoreManufacturer::getById()
	 * 
	 * @param int $manufacturer_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function getById($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias 
        WHERE object_id = '" . (int)$manufacturer_id . "'
        AND object_type = 'manufacturer') AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		
		return $query->row;
	}
	
	/**
	 * ModelStoreManufacturer::getAll()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records 
	 */
	public function getAll($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m";
			
    		$implode = array();
    		
    		if ($data['filter_name']) {
    			$implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if ($data['filter_date_start'] && $data['filter_date_end']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif ($data['filter_date_start']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
            if ($data['filter_product']) {
                $implode[] = " m.manufacturer_id IN (SELECT manufacturer_id 
                    FROM " . DB_PREFIX . "product p2
                        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2.product_id=pd.product_id) 
                    WHERE LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_product'])) . "%'
                        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
            } 
            
    		if ($implode) {
    			$sql .= " WHERE " . implode(" AND ", $implode);
    		}
    		
			$sort_data = array(
				'name',
				'date_added',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
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
		} else {
			$manufacturer_data = $this->cache->get('manufacturer');
		
			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name");
	
				$manufacturer_data = $query->rows;
			
				$this->cache->set('manufacturer', $manufacturer_data);
			}
		 
			return $manufacturer_data;
		}
	}

	/**
	 * ModelStoreManufacturer::getAllByImageId()
	 * 
	 * @param mixed $image_id
     * @see DB
	 * @return int Count sql records 
	 */
	public function getAllTotalByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}

	/**
	 * ModelStoreManufacturer::getAll()
	 * 
     * @see DB
	 * @return int Count sql records 
	 */
	public function getAllTotal() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");
		
		return $query->row['total'];
	}	
	/**
	 * ModelStoreProduct::sortProduct()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortProduct($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET sort_order = '" . (int)$pos . "' WHERE manufacturer_id = '" . (int)$id . "'");
            $pos++;
       }
	   return true;
	}
	
    /**
     * ModelStoreProduct::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `status` = '1' WHERE `manufacturer_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `status` = '0' WHERE `manufacturer_id` = '" . (int)$id . "'");
        return $query;
     }
    /**
     * ModelStoreManufacturer::getProperty()
     * 
     * Obtener una propiedad de la categoria
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
     * ModelStoreManufacturer::setProperty()
     * 
     * Asigna una propiedad de la categoria
     * 
     * @param int $id manufacturer_id
     * @param varchar $group
     * @param varchar $key
     * @param mixed $value
     * @return void
     * */
	public function setProperty($id, $group, $key, $value) {
		$this->deleteProperty($id, $group, $key);
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_property SET
        `manufacturer_id`   = '" . (int)$id . "',
        `group`     = '" . $this->db->escape($group) . "',
        `key`       = '" . $this->db->escape($key) . "',
        `value`     = '" . $this->db->escape(str_replace("'","\'",serialize($value))) . "'");
	}
	
    /**
     * ModelStoreManufacturer::deleteProperty()
     * 
     * Elimina una propiedad de la categoria
     * 
     * @param int $id manufacturer_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
	public function deleteProperty($id, $group, $key) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_property 
        WHERE `manufacturer_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
	}
	
    /**
     * ModelStoreManufacturer::getAllProperties()
     * 
     * Obtiene todas las propiedades de la categoria
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
	
    /**
     * ModelStoreManufacturer::setAllProperties()
     * 
     * Asigna todas las propiedades de la categoria
     * 
     * Pase un array con todas las propiedades y sus valores
     * eneplo:
     * 
     * $data = array(
     *    'key1'=>'abc',
     *    'key2'=>123,
     *    'key3'=>array(
     *       'subkey1'=>'value1'
     *    ),
     *    'key4'=>$object,
     * );
     * 
     * @param int $id manufacturer_id
     * @param varchar $group
     * @param array $data
     * @return void
     * */
	public function setAllProperties($id, $group, $data) {
        if (is_array($data) && !empty($data)) {
    		$this->deleteAllProperties($id, $group);
            foreach ($data as $key=>$value) {
                $this->setProperty($id, $group, $key, $value);
            }
        }
	}	
	
    /**
     * ModelStoreManufacturer::deleteAllProperties()
     * 
     * Elimina todas las propiedades de la categoria
     * 
     * Si quiere eliminar todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = deleteAllProperties($manufacturer_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = deleteAllProperties($manufacturer_id, 'NombreDelGrupo');
     * 
     * @param int $id manufacturer_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
	public function deleteAllProperties($id, $group='*') {
        if ($group=='*') {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_property 
            WHERE `manufacturer_id` = '" . (int)$id . "'");
        } else {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_property 
            WHERE `manufacturer_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
	}
}
