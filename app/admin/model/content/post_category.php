<?php
/**
 * ModelContentPostCategory
 * 
 * @package NecoTienda
 * @author NecoTienda
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 */
class ModelContentPostCategory extends Model {
	/**
	 * ModelContentPostCategory::add()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "post_category SET 
        parent_id = '" . (int)$data['parent_id'] . "', 
        sort_order = '0', 
        status = '1', 
        date_added = NOW()");
	
		$post_category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "post_category SET image = '" . $this->db->escape($data['image']) . "' WHERE post_category_id = '" . (int)$post_category_id . "'");
		}
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_description SET 
            post_category_id = '" . (int)$post_category_id . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($value['name']) . "', 
            meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "', 
            meta_description = '" . $this->db->escape($value['meta_description']) . "', 
            seo_title = '" . $this->db->escape($value['seo_title']) . "', 
            description = '" . $this->db->escape($value['description']) . "'");
            
    		if (!empty($value['keyword'])) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int)$language_id . "', 
                object_id    = '" . (int)$post_category_id . "', 
                object_type = 'post_category', 
                query       = 'post_category_id=" . (int)$post_category_id . "', 
                keyword     = '" . $this->db->escape($value['keyword']) . "'");
    		}
		}
		
        foreach ($data['Post'] as $post_id => $value) {
            if ($value == 0) continue;
    		$this->db->query("REPLACE INTO " . DB_PREFIX . "post_to_category (post_id, post_category_id) VALUES ('" . (int)$post_id . "','" . (int)$post_category_id."')");
        }
        
        foreach ($data['stores'] as $store) {
      		$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_to_store SET 
            store_id  = '". intval($store) ."', 
            post_category_id = '". intval($post_category_id) ."'");
        }
        
		$this->cache->delete('post_category_admin');
        
        return $post_category_id;
	}
	
	/**
	 * ModelContentPostCategory::editCategory()
	 * 
	 * @param int $post_category_id
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function update($post_category_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "post_category SET 
        parent_id = '" . (int)$data['parent_id'] . "',
        date_modified = NOW() 
        WHERE post_category_id = '" . (int)$post_category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "post_category SET image = '" . $this->db->escape($data['image']) . "' WHERE post_category_id = '" . (int)$post_category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_description WHERE post_category_id = '" . (int)$post_category_id . "'");
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_description SET 
            post_category_id = '" . (int)$post_category_id . "', 
            language_id = '" . (int)$language_id . "', 
            name = '" . $this->db->escape($value['name']) . "', 
            meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "', 
            meta_description = '" . $this->db->escape($value['meta_description']) . "', 
            seo_title = '" . $this->db->escape($value['seo_title']) . "',
            description = '" . $this->db->escape($value['description']) . "'");
            
    		if (!empty($value['keyword'])) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias 
                WHERE object_id = '" . (int)$post_category_id . "' 
                AND language_id = '" . (int)$language_id . "' 
                AND object_type = 'post_category'");
                
    			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int)$language_id . "', 
                object_id    = '" . (int)$post_category_id . "', 
                object_type = 'post_category', 
                query       = 'post_category_id=" . (int)$post_category_id . "', 
                keyword     = '" . $this->db->escape($value['keyword']) . "'");
    		}
		}
		
            $this->db->query("DELETE FROM " . DB_PREFIX . "post_category_to_store WHERE post_category_id = '". (int)$post_category_id ."'");
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_to_store SET 
                store_id  = '". intval($store) ."', 
                post_category_id = '". intval($post_category_id) ."'");
            }
        
        
		if (!empty($data['Post'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "post_to_category WHERE post_category_id='" . (int)$post_category_id."'");
            foreach ($data['Post'] as $post_id => $value) {
                if ($value == 0) continue;
        		$this->db->query("REPLACE INTO " . DB_PREFIX . "post_to_category (post_id, post_category_id) VALUES ('" . (int)$post_id . "','" . (int)$post_category_id."')");
            }
        }
        
		$this->cache->delete('post_category_admin');
	}
	
	/**
	 * ModelContentPostCategory::sortCategory()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortCategory($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "post_category SET sort_order = '" . (int)$pos . "' WHERE post_category_id = '" . (int)$id . "'");
            $pos++;
       }
		$this->cache->delete('post_category_admin');
	   return true;
	}
	
	/**
	 * ModelContentPostCategory::delete()
	 * 
	 * @param int $post_category_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function delete($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category WHERE post_category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_description WHERE post_category_id = '" . (int)$category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "post_category_to_store WHERE post_category_id = '". (int)$category_id ."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "stat WHERE object_id = '" . (int)$category_id . "' AND object_type = 'post_category'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE object_id = '" . (int)$category_id . "' AND object_type = 'post_category'");
        
        if ((int)$category_id) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_description 
            WHERE post_category_id IN 
                (SELECT post_category_id 
                FROM " . DB_PREFIX . "post_category 
                WHERE parent_id = '" . (int)$category_id . "')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "stat 
            WHERE object_type = 'post_category' 
            AND object_id IN 
                (SELECT post_category_id 
                FROM " . DB_PREFIX . "post_category 
                WHERE parent_id = '" . (int)$category_id . "')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias 
            WHERE object_type = 'post_category' 
            AND object_id IN 
                (SELECT post_category_id 
                FROM " . DB_PREFIX . "post_category 
                WHERE parent_id = '" . (int)$category_id . "')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_to_store 
            WHERE post_category_id IN 
                (SELECT post_category_id 
                FROM " . DB_PREFIX . "post_category 
                WHERE parent_id = '" . (int)$category_id . "')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category WHERE parent_id = '" . (int)$category_id . "'");
        }
        
		$this->cache->delete('post_category_admin');
	}

	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_category_to_store WHERE post_category_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelContentPostCategory::getById()
	 * 
	 * @param int $post_category_id
     * @see DB
	 * @return array sql record
	 */
	public function getById($post_category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "post_category pc 
        LEFT JOIN " . DB_PREFIX . "post_category_description pcd ON (pcd.post_category_id=pc.post_category_id) 
        WHERE pc.post_category_id = '" . (int)$post_category_id . "'");
		
		return $query->row;
	} 
	
	
	/**
	 * ModelContentPostCategory::getAll()
	 * 
	 * @param int $parent_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getAll($data=array()) {
			$post_category_data = array();
		      
		$sql = "SELECT * 
        FROM " . DB_PREFIX . "post_category c 
            LEFT JOIN " . DB_PREFIX . "post_category_description cd ON (c.post_category_id = cd.post_category_id)";
            
        $criteria = array();
        
        $criteria[] = "cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        
        if (!empty($data['parent_id'])) {
            $criteria[] = "c.parent_id = '" . (int)$data['parent_id'] . "'";
        } else {
            $criteria[] = "c.parent_id = '0'";
        }
          
        if (!empty($data['filter_name'])) {
            $criteria[] = "LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
        }
         
        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $criteria[] = "c.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
        } elseif (!empty($data['filter_date_start'])) {
            $criteria[] = "c.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
        }
        
        if ($criteria) {
            $sql .= " WHERE " .implode(" AND ",$criteria);
        }
		
        $sort_data = array(
            'cd.name',
			'c.date_added',
			'c.sort_order'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];	
		} else {
            $sql .= " ORDER BY c.sort_order, cd.name";	
        }
			
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
		} else {
            $sql .= " ASC";
		}
		
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
    		$post_category_data[] = array(
                'post_category_id' => $result['post_category_id'],
    			'name'        => $this->getPath($result['post_category_id'], $this->config->get('config_language_id')),
    			'status'  	  => $result['status'],
    			'sort_order'  => $result['sort_order']
    		);
            $data['parent_id'] = $result['post_category_id'];
    		$post_category_data = array_merge($post_category_data, $this->getAll($data));
        }
		return $post_category_data;
	}
	
	/**
	 * ModelContentPostCategory::getAllForList()
	 * 
	 * @param int $parent_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getAllForList($parent_id=0,$data=array()) {
		$sql = "SELECT * 
        FROM " . DB_PREFIX . "post_category c 
            LEFT JOIN " . DB_PREFIX . "post_category_description cd ON (c.post_category_id = cd.post_category_id) 
        WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND parent_id = " . (int)$parent_id;
            
		if (isset($data['filter_name'])) {
            $sql .= " AND LCASE(cd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_date_start'],$data['filter_date_end'])) {
            $sql .= " AND c.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
		} elseif (isset($data['filter_date_start'])) {
            $sql .= " AND c.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}

		if (isset($data['filter_post'])) {
            $sql .= " AND c.post_category_id IN (SELECT post_category_id 
                                        FROM " . DB_PREFIX . "product_to_post_category p2c
                                            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2c.product_id=pd.product_id) 
                                        WHERE LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_post'])) . "%'
                                        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
		}

		$sort_data = array(
		  'cd.name',
		  'c.date_added',
		  'c.sort_order'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];	
		} else {
            $sql .= " ORDER BY c.sort_order, cd.name";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
		    $sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
        $query = $this->db->query($sql);
		return $query->rows;
	}
	
	/**
	 * ModelContentPostCategory::getAllForMenu()
	 * 
	 * @param int $parent_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getAllForMenu($parent_id=0) {
		$sql = "SELECT * 
        FROM " . DB_PREFIX . "post_category c 
            LEFT JOIN " . DB_PREFIX . "post_category_description cd ON (c.post_category_id = cd.post_category_id) 
        WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND parent_id = " . (int)$parent_id ." ORDER BY c.sort_order, cd.name ASC";
		
        $query = $this->db->query($sql);
		return $query->rows;
	}
	
	/**
	 * ModelContentPostCategory::getPath()
	 * 
	 * @param int $post_category_id
	 * @return string 
	 */
	public function getPath($post_category_id) {
		$query = $this->db->query("SELECT name, parent_id 
        FROM " . DB_PREFIX . "post_category c 
            LEFT JOIN " . DB_PREFIX . "post_category_description cd ON (c.post_category_id = cd.post_category_id) 
        WHERE c.post_category_id = '" . (int)$post_category_id . "' 
            AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        ORDER BY c.sort_order, cd.name ASC");
		
		$post_category_info = $query->row;
		
		if ($post_category_info['parent_id']) {
			return $this->getPath($post_category_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $post_category_info['name'];
		} else {
			return $post_category_info['name'];
		}
	}
	
	/**
	 * ModelContentPostCategory::getDescriptions()
	 * 
	 * @param int $post_category_id
     * @see DB
	 * @return array sql records
	 */
	public function getDescriptions($post_category_id) {
		$post_category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_category_description WHERE post_category_id = '" . (int)$post_category_id . "'");
		
		foreach ($query->rows as $result) {
			$post_category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keywords'    => $result['meta_keywords'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		foreach ($query->rows as $result) {
			$post_category_description_data[$result['language_id']]['name'] = $result['name'];
			$post_category_description_data[$result['language_id']]['description'] = $result['description'];
			$post_category_description_data[$result['language_id']]['seo_title'] = $result['seo_title'];
			$post_category_description_data[$result['language_id']]['meta_keywords'] = $result['meta_keywords'];
			$post_category_description_data[$result['language_id']]['meta_description'] = $result['meta_description'];
		}
		
		$keywords = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias
        WHERE object_id = '" . (int)$post_category_id . "' 
        AND object_type = 'post_category'");

		foreach ($keywords->rows as $result) {
			$post_category_description_data[$result['language_id']]['keyword'] = $result['keyword'];
		}
		
		return $post_category_description_data;
	}	
	
	/**
	 * ModelContentPostCategory::getTotalCategories()
	 * 
     * @see DB
	 * @return int Count sql records
	 */
	public function getTotalCategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post_category");
		
		return $query->row['total'];
	}	
		
		
	/**
	 * ModelContentPostCategory::getTotalCategoriesByImageId()
	 * 
	 * @param int $image_id
	 * @return int Count sql records
	 */
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post_category WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}
    
    /**
     * ModelContentPostCategory::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "post_category` SET `status` = '1' WHERE `post_category_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelContentPostCategory::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "post_category` SET `status` = '0' WHERE `post_category_id` = '" . (int)$id . "'");
        return $query;
     }

    /**
     * ModelContentPostCategory::getProperty()
     * 
     * Obtener una propiedad de la categoria
     * 
     * @param int $id post_category_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_category_property 
        WHERE `post_category_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
  
		return unserialize(str_replace("\'","'",$query->row['value']));
	}
	
    /**
     * ModelContentPostCategory::setProperty()
     * 
     * Asigna una propiedad de la categoria
     * 
     * @param int $id post_category_id
     * @param varchar $group
     * @param varchar $key
     * @param mixed $value
     * @return void
     * */
	public function setProperty($id, $group, $key, $value) {
		$this->deleteProperty($id, $group, $key);
		$this->db->query("INSERT INTO " . DB_PREFIX . "post_category_property SET
        `post_category_id`   = '" . (int)$id . "',
        `group`     = '" . $this->db->escape($group) . "',
        `key`       = '" . $this->db->escape($key) . "',
        `value`     = '" . $this->db->escape(str_replace("'","\'",serialize($value))) . "'");
	}
	
    /**
     * ModelContentPostCategory::deleteProperty()
     * 
     * Elimina una propiedad de la categoria
     * 
     * @param int $id post_category_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
	public function deleteProperty($id, $group, $key) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_property 
        WHERE `post_category_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
	}
	
    /**
     * ModelContentPostCategory::getAllProperties()
     * 
     * Obtiene todas las propiedades de la categoria
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($post_category_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($post_category_id, 'NombreDelGrupo');
     * 
     * @param int $id post_category_id
     * @param varchar $group
     * @return array all properties
     * */
	public function getAllProperties($id, $group='*') {
        if ($group=='*') {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_category_property 
            WHERE `post_category_id` = '" . (int)$id . "'");
        } else {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_category_property 
            WHERE `post_category_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
        
		return $query->rows;
	}
	
    /**
     * ModelContentPostCategory::setAllProperties()
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
     * @param int $id post_category_id
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
     * ModelContentPostCategory::deleteAllProperties()
     * 
     * Elimina todas las propiedades de la categoria
     * 
     * Si quiere eliminar todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = deleteAllProperties($post_category_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = deleteAllProperties($post_category_id, 'NombreDelGrupo');
     * 
     * @param int $id post_category_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
	public function deleteAllProperties($id, $group='*') {
        if ($group=='*') {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_property 
            WHERE `post_category_id` = '" . (int)$id . "'");
        } else {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_category_property 
            WHERE `post_category_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
	}
}
