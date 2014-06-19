<?php
/**
 * ModelContentPage
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelContentPage extends Model {
	/**
	 * ModelContentPage::add()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "post SET 
        parent_id   = '" . (int)$this->request->post['parent_id'] . "', 
        date_publish_start = '" . $this->db->escape($data['date_publish_start']) . "', 
        date_publish_end = '" . $this->db->escape($data['date_publish_end']) . "', 
        template    = '" . $this->db->escape($data['template']) . "', 
        post_type   = 'page', 
        status      = 1, 
        sort_order  = 0, 
        date_added  = NOW()");

		$post_id = $this->db->getLastId(); 
		
		foreach ($data['page_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description SET 
            post_id     = '" . (int)$post_id . "', 
            language_id = '" . (int)$language_id . "', 
            title       = '" . $this->db->escape($value['title']) . "', 
            description = '" . $this->db->escape($value['description']) . "', 
            seo_title  = '" . $this->db->escape($value['seo_title']) . "', 
            meta_description = '" . $this->db->escape($value['meta_description']) . "', 
            meta_keywords    = '" . $this->db->escape($value['meta_keywords']) . "'");
            
    		if (!empty($value['keyword'])) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int)$language_id . "', 
                object_id   = '" . (int)$post_id . "', 
                object_type = 'page', 
                query       = 'page_id=" . (int)$post_id . "', 
                keyword     = '" . $this->db->escape($value['keyword']) . "'");
    		}
		}
		
            foreach ($data['stores'] as $store) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_store SET 
                store_id  = '". intval($store) ."', 
                post_id = '". intval($post_id) ."'");
            }
        
		$this->cache->delete("page");
        return $post_id;
	}
	
	/**
	 * ModelContentPage::editPage()
	 * 
	 * @param int $post_id
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function update($post_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "post SET 
        parent_id   = '" . (int)$this->request->post['parent_id'] . "',
        publish     = '" . (int)$this->request->post['publish'] . "', 
        date_publish_start = '" . $this->db->escape($data['date_publish_start']) . "', 
        date_publish_end = '" . $this->db->escape($data['date_publish_end']) . "', 
        template    = '" . $this->db->escape($data['template']) . "', 
        sort_order  = '" . (int)$this->request->post['sort_order'] . "', 
        date_modified  = NOW() 
        WHERE post_id = '" . (int)$post_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");
		foreach ($data['page_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description SET 
            post_id     = '" . (int)$post_id . "', 
            language_id = '" . (int)$language_id . "', 
            title       = '" . $this->db->escape($value['title']) . "', 
            description = '" . $this->db->escape($value['description']) . "', 
            seo_title  = '" . $this->db->escape($value['seo_title']) . "', 
            meta_description = '" . $this->db->escape($value['meta_description']) . "', 
            meta_keywords    = '" . $this->db->escape($value['meta_keywords']) . "'");
            
    		if (!empty($value['keyword'])) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias 
                WHERE object_id = '" . (int)$post_id . "' 
                AND language_id = '" . (int)$language_id . "' 
                AND object_type = 'page'");
                
    			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int)$language_id . "', 
                object_id    = '" . (int)$post_id . "', 
                object_type = 'page', 
                query       = 'page_id=" . (int)$post_id . "', 
                keyword     = '" . $this->db->escape($value['keyword']) . "'");
    		}
		}
		
        $this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE post_id = '". (int)$post_id ."'");
        foreach ($data['stores'] as $store) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "post_to_store SET 
                store_id  = '". intval($store) ."', 
                post_id = '". intval($post_id) ."'");
        }
        
		$this->cache->delete("page");
        return $post_id;
	}
	
	/**
	 * ModelStoreProduct::copy()
	 * 
	 * @param int $product_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function copy($post_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        WHERE p.post_id = '" . (int)$post_id . "' 
        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data = array_merge($data, array('page_description' => $this->getDescriptions($post_id)));
			$data['keyword'] = $data['keyword'] . uniqid("-");
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
	public function delete($post_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "post WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "stat WHERE object_id = '" . (int)$post_id . "' AND object_type = 'page'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE object_id='" . (int)$post_id. "' AND object_type = 'page'");

        if ((int)$post_id) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description 
            WHERE post_id IN 
                (SELECT post_id 
                FROM " . DB_PREFIX . "post
                WHERE parent_id = '" . (int)$post_id . "')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_store 
            WHERE post_id IN 
                (SELECT post_id 
                FROM " . DB_PREFIX . "post 
                WHERE parent_id = '" . (int)$post_id . "' AND post_type = 'page')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "stat 
            WHERE object_type = 'page' 
            AND object_id IN 
                (SELECT post_id 
                FROM " . DB_PREFIX . "post
                WHERE parent_id = '" . (int)$post_id . "')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias 
            WHERE object_type = 'page' AND object_id IN 
                (SELECT post_id 
                FROM " . DB_PREFIX . "post 
                WHERE parent_id = '" . (int)$post_id . "' AND post_type = 'page')");
                
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post WHERE parent_id = '" . (int)$post_id . "'");
        }
        
		$this->cache->delete("page");
	}	

	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_to_store WHERE post_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}
    
	/**
	 * ModelContentPage::getPage()
	 * 
	 * @param int $post_id
     * @see DB
	 * @return array sql record
	 */
	public function getPage($post_id) {
	   $query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'post_id=" . (int)$post_id . "') AS keyword 
            FROM " . DB_PREFIX . "post  p
            LEFT JOIN ". DB_PREFIX . "post_description pd ON (pd.post_id=p.post_id) 
            WHERE p.post_id = '" . (int)$post_id . "' 
            AND post_type = 'page'");
		  return $query->row;
	}
		
	/**
	 * ModelContentPage::_getAll()
	 * 
	 * @param int $parent_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function _getAll($parent_id) {
		$post_data = $this->cache->get('post_admin.' . $this->config->get('config_language_id') . '.' . $parent_id);
	
		if (!$post_data) {
			$post_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post c 
                LEFT JOIN " . DB_PREFIX . "post_description cd ON (c.post_id = cd.post_id) 
            WHERE c.parent_id = '" . (int)$parent_id . "' 
                AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND post_type = 'page' 
            ORDER BY c.sort_order, cd.title ASC");
		
			foreach ($query->rows as $result) {
				$post_data[] = array(
					'post_id' => $result['post_id'],
					'title'        => $this->getPath($result['post_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$post_data = array_merge($post_data, $this->getCategories($result['post_id']));
			}	
	
			$this->cache->set('post_admin.' . $this->config->get('config_language_id') . '.' . $parent_id, $post_data);
		}
		
		return $post_data;
	}
	
	/**
	 * ModelContentPage::getCategories()
	 * 
	 * @param int $parent_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getCategories($parent_id=0, $data=array()) {
		      
			$sql = "SELECT * 
            FROM " . DB_PREFIX . "post c 
                LEFT JOIN " . DB_PREFIX . "post_description cd ON (c.post_id = cd.post_id) 
            WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND post_type = 'page' 
            AND parent_id = '". (int)$parent_id ."'
             ORDER BY c.sort_order, cd.title ASC";
			
            $query = $this->db->query($sql);
		return $query->rows;
	}
	
	/**
	 * ModelStoreCategory::getPath()
	 * 
	 * @param int $post_id
	 * @return string 
	 */
	public function getPath($post_id) {
		$query = $this->db->query("SELECT title, parent_id 
        FROM " . DB_PREFIX . "post c 
            LEFT JOIN " . DB_PREFIX . "post_description cd ON (c.post_id = cd.post_id) 
        WHERE c.post_id = '" . (int)$post_id . "' 
            AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND post_type = 'page' 
        ORDER BY c.sort_order, cd.title ASC");
		
		$post_info = $query->row;
		
		if ($post_info['parent_id']) {
			return $this->getPath($post_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $post_info['title'];
		} else {
			return $post_info['title'];
		}
	}
	
	/**
	 * ModelContentPage::getAll()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getAll($data = array()) {
	   $sql = "SELECT * FROM " . DB_PREFIX . "post pa 
            LEFT JOIN " . DB_PREFIX . "post_description pad ON (pa.post_id = pad.post_id) 
            WHERE pad.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND post_type = 'page' ";
            
	   $implode = array();
    		
    		if (isset($data['filter_title']) && !is_null($data['filter_title'])) {
    			$implode[] = "LCASE(title) LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
    		}
    		
    		if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
    			$implode[] = "parent_id = (SELECT pa.post_id FROM " . DB_PREFIX . "post pa 
                LEFT JOIN " . DB_PREFIX . "post_description pad ON (pa.post_id = pad.post_id) 
                WHERE pad.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                AND post_type = 'page' 
                AND LCASE(title) LIKE '%" . $this->db->escape($data['filter_parent']) . "%')";
    		}
    		
    		if ($data['filter_date_start'] && $data['filter_date_end']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif ($data['filter_date_start']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
    		if ($implode) {
    			$sql .= " AND " . implode(" AND ", $implode);
    		}
    		
			$sort_data = array(
				'title',
				'date_publish_start',
				'date_publish_end',
				'pa.sort_order'
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
	
	/**
	 * ModelContentPage::getDescriptions()
	 * 
	 * @param int $post_id
     * @see DB
     * @see Cache
	 * @return array sql records
	 */
	public function getDescriptions($post_id) {
		$post_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_description pd
        LEFT JOIN " . DB_PREFIX . "post p ON (pd.post_id =p.post_id) 
        WHERE pd.post_id = '" . (int)$post_id . "' 
        AND p.`post_type` = 'page'");
        
		foreach ($query->rows as $result) {
			$post_description_data[$result['language_id']]['title'] = $result['title'];
			$post_description_data[$result['language_id']]['description'] = $result['description'];
			$post_description_data[$result['language_id']]['seo_title'] = $result['seo_title'];
			$post_description_data[$result['language_id']]['meta_keywords'] = $result['meta_keywords'];
			$post_description_data[$result['language_id']]['meta_description'] = $result['meta_description'];
		}
		
		$keywords = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias
        WHERE object_id = '" . (int)$post_id . "' 
        AND object_type = 'page'");

		foreach ($keywords->rows as $result) {
			$post_description_data[$result['language_id']]['keyword'] = $result['keyword'];
		}
		
		return $post_description_data;
	}
	
	/**
	 * ModelContentPage::getTotalPages()
	 * 
     * @see DB
	 * @return int Count sql records
	 */
	public function getTotalPages() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post WHERE post_type = 'page'");
		
		return $query->row['total'];
	}	
    
	/**
	 * ModelContentProduct::sortProduct()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortPage($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "post SET sort_order = '" . (int)$pos . "' WHERE post_id = '" . (int)$id . "' AND post_type = 'page'");
            $pos++;
       }
	   return true;
	}
	
    /**
     * ModelContentProduct::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "post` SET `status` = '1' WHERE `post_id` = '" . (int)$id . "' AND post_type = 'page'");
        return $query;
     }
    
    /**
     * ModelContentProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "post` SET `status` = '0' WHERE `post_id` = '" . (int)$id . "' AND post_type = 'page'");
        return $query;
     }

    /**
     * ModelContentPage::getProperty()
     * 
     * Obtener una propiedad de la pagina
     * 
     * @param int $id post_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_property 
        WHERE `post_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
  
		return unserialize(str_replace("\'","'",$query->row['value']));
	}
	
    /**
     * ModelContentPage::setProperty()
     * 
     * Asigna una propiedad de la pagina
     * 
     * @param int $id post_id
     * @param varchar $group
     * @param varchar $key
     * @param mixed $value
     * @return void
     * */
	public function setProperty($id, $group, $key, $value) {
		$this->deleteProperty($id, $group, $key);
		$this->db->query("INSERT INTO " . DB_PREFIX . "post_property SET
        `post_id`   = '" . (int)$id . "',
        `group`     = '" . $this->db->escape($group) . "',
        `key`       = '" . $this->db->escape($key) . "',
        `value`     = '" . $this->db->escape(str_replace("'","\'",serialize($value))) . "'");
	}
	
    /**
     * ModelContentPage::deleteProperty()
     * 
     * Elimina una propiedad de la pagina
     * 
     * @param int $id post_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
	public function deleteProperty($id, $group, $key) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_property 
        WHERE `post_id` = '" . (int)$id . "' 
        AND `group` = '". $this->db->escape($group) ."'
        AND `key` = '". $this->db->escape($key) ."'");
	}
	
    /**
     * ModelContentPage::getAllProperties()
     * 
     * Obtiene todas las propiedades de la pagina
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($post_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($post_id, 'NombreDelGrupo');
     * 
     * @param int $id post_id
     * @param varchar $group
     * @return array all properties
     * */
	public function getAllProperties($id, $group='*') {
        if ($group=='*') {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_property 
            WHERE `post_id` = '" . (int)$id . "'");
        } else {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_property 
            WHERE `post_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
        
		return $query->rows;
	}
	
    /**
     * ModelContentPage::setAllProperties()
     * 
     * Asigna todas las propiedades de la pagina
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
     * @param int $id post_id
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
     * ModelContentPage::deleteAllProperties()
     * 
     * Elimina todas las propiedades de la pagina
     * 
     * Si quiere eliminar todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = deleteAllProperties($post_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = deleteAllProperties($post_id, 'NombreDelGrupo');
     * 
     * @param int $id post_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
	public function deleteAllProperties($id, $group='*') {
        if ($group=='*') {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_property 
            WHERE `post_id` = '" . (int)$id . "'");
        } else {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "post_property 
            WHERE `post_id` = '" . (int)$id . "' 
            AND `group` = '". $this->db->escape($group) ."'");
        }
	}
}