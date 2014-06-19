<?php
class ModelContentPage extends Model {
	public function getById($id) {
		$query = $this->db->query("SELECT DISTINCT * 
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id)
        WHERE p.post_id = '" . (int)$id . "' 
        AND p2s.store_id = '". (int)STORE_ID ."'
        AND post_type = 'page'
        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND date_publish_start <= NOW()
        AND (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00')
        AND p.status = '1'");
	
		return $query->row;
	}
	
	public function getAll() {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id)
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND p2s.store_id = '". (int)STORE_ID ."'
        AND post_type = 'page'
        AND date_publish_start <= NOW()
        AND (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00')
        AND p.status = '1' 
        ORDER BY p.sort_order, LCASE(pd.title) ASC");
		
		return $query->rows;
	}
    
	public function getLatest($data) {
		if ($data['start'] < 0) $data['start'] = 0;
		if ($data['limit'] <= 0) $data['limit'] = 25;
        
		$sql = "SELECT * 
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id)";
        
        $criteria = array();
        $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        $criteria[] = " post_type = 'page' ";
        $criteria[] = " date_publish_start <= NOW() ";
        $criteria[] = " (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00') ";
        $criteria[] = " p.status = '1' ";
        $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
        
        if (!empty($data['page_id'])) {
            $criteria[] = " p.parent_id = '" . (int)$data['page_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
            
		$sort_data = array(
			'pd.title',
			'p.sort_order',
			'date_publish_start'
		);
			
		if (in_array($data['sort'], $sort_data)) {
			$sql .=  ($data['sort'] == 'pd.title') ? " ORDER BY LCASE(" . $data['sort'] . ")" : " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.date_publish_start ";	
		}
			
		$sql .= ($data['order'] == 'ASC') ? " ASC" : " DESC";        
        $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        
        $query = $this->db->query($sql);
		return $query->rows;
	}
    
	public function getTotalLatest($data) {
		$sql = "SELECT COUNT(*) AS total
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id)";
        
        $criteria = array();
        $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        $criteria[] = " post_type = 'page' ";
        $criteria[] = " date_publish_start <= NOW() ";
        $criteria[] = " (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00') ";
        $criteria[] = " p.status = '1' ";
        $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
        
        if (!empty($data['page_id'])) {
            $criteria[] = " p.parent_id = '" . (int)$data['page_id'] . "' ";
        }
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
        
        $query = $this->db->query($sql);
		return $query->row['total'];
	}
    
	public function updateStats($id) {
	   $this->load->library('browser');
       $browser = new Browser;
		$this->db->query("UPDATE " . DB_PREFIX . "post SET viewed = viewed + 1 WHERE post_id = '" . (int)$id . "'");
		$this->db->query("INSERT " . DB_PREFIX . "stat SET 
        `object_id`     = '". (int)$id ."',
        `store_id`      = '". (int)STORE_ID ."',
        `customer_id`   = '". (int)$this->reseller->getId() ."',
        `object_type`   = 'page',
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
}
