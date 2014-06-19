<?php
class ModelStoreCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c 
        LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
        LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
        WHERE c.category_id = '" . (int)$category_id . "' 
        AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND c2s.store_id = '". (int)STORE_ID ."' 
        AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "category c 
        LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
        LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
        WHERE c.parent_id = '" . (int)$parent_id . "' 
        AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND c2s.store_id = '". (int)STORE_ID ."' 
        AND c.status = '1' 
        ORDER BY c.sort_order ASC");
		return $query->rows;
	}
    
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c 
        LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
        WHERE c.parent_id = '" . (int)$parent_id . "' 
        AND c2s.store_id = '". (int)STORE_ID ."' 
        AND c.status = '1'");
		return $query->row['total'];
	}	
    
	public function getCategoriesByName($name,$parent_id=0) {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            WHERE LCASE(cd.name) LIKE '%" . strtolower($this->db->escape($name)) . "%' 
                AND c.parent_id = '" . (int)$parent_id . "' 
                AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND c.status = '1' 
            ORDER BY LCASE(cd.name)");
    		return $query->rows;
	}
    
	public function getTopCategories($limit = 16) {
		$categories = $this->cache->get('topcategories.' . (int)C_CODE);
		
		if (!$categories) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            WHERE parent_id = 0
            ORDER BY viewed DESC LIMIT " . (int)$limit);
	
			$categories = $query->rows;
			
			$this->cache->set('topcategories.' . (int)C_CODE, $categories);
		}
		
		return $categories;
	}
    
	public function getTotalCategoriesByCategory($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c WHERE c.parent_id = '" . (int)$parent_id . "' AND c.status = '1'");
		
		return $query->row['total'];
	}	
    
	public function getAttributes($category_id) {
		$query = $this->db->query("SELECT *, pa.name AS attribute FROM " . DB_PREFIX . "product_attribute pa 
        LEFT JOIN " . DB_PREFIX . "product_attribute_group pag ON (pa.product_attribute_group_id = pag.product_attribute_group_id) 
        LEFT JOIN " . DB_PREFIX . "product_attribute_to_category pa2c ON (pag.product_attribute_group_id = pa2c.product_attribute_group_id) 
        LEFT JOIN " . DB_PREFIX . "category c ON (c.category_id = pa2c.category_id) 
        WHERE pa2c.category_id = '" . (int)$category_id . "' 
        AND pag.status = '1'");
		
		return $query->rows;
	}
    
	public function updateStats($category_id,$customer_id) {
	   $this->load->library('browser');
       $browser = new Browser;
		$this->db->query("UPDATE " . DB_PREFIX . "category SET viewed = viewed + 1 WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("INSERT " . DB_PREFIX . "stat SET 
        `object_id`     = '". (int)$category_id ."',
        `store_id`      = '". (int)STORE_ID ."',
        `customer_id`   = '". (int)$customer_id ."',
        `object_type`   = 'category',
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
     * ModelStoreCategory::getProperty()
     * 
     * Obtener una propiedad de la categoria
     * 
     * @param int $id category_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_property 
        WHERE `category_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
  
		return unserialize(str_replace("\'","'",$query->row['value']));
	}
    
    /**
     * ModelStoreCategory::getAllProperties()
     * 
     * Obtiene todas las propiedades de la categoria
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($category_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($category_id, 'NombreDelGrupo');
     * 
     * @param int $id category_id
     * @param varchar $group
     * @return array all properties
     * */
	public function getAllProperties($id, $group='*') {
        if ($group=='*') {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_property 
            WHERE `category_id` = '" . (int)$id . "'");
        } else {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_property 
            WHERE `category_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
        
		return $query->rows;
	}
}