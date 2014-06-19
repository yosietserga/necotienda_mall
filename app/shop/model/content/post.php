<?php
class ModelContentPost extends Model {
	public function getById($id) {
		$query = $this->db->query("SELECT DISTINCT * 
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id)
        WHERE p.post_id = '" . (int)$id . "' 
        AND post_type = 'post'
        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND date_publish_start <= NOW()
        AND (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00')
        AND p.status = '1'
        AND p2s.store_id = '". (int)STORE_ID ."'");
	
		return $query->row;
	}
	
	public function getAllByCategoryId($data) {
		if ($data['start'] < 0) $data['start'] = 0;
		if ($data['limit'] <= 0) $data['limit'] = 25;
        
		$sql = "SELECT * 
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id)";
        
        $criteria = array();
        $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        $criteria[] = " post_type = 'post' ";
        $criteria[] = " date_publish_start <= NOW() ";
        $criteria[] = " (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00') ";
        $criteria[] = " p.status = '1' ";
        $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
        
        if (!empty($data['category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (p.post_id = p2c.post_id) ";
            $criteria[] = " p2c.post_category_id = '" . (int)$data['category_id'] . "' ";
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
    
	public function getTotalByCategoryId($data) {
		$sql = "SELECT COUNT(*) AS total
        FROM " . DB_PREFIX . "post p 
        LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id)
        LEFT JOIN " . DB_PREFIX . "post_to_store p2s ON (p.post_id=p2s.post_id) ";
        
        $criteria = array();
        $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        $criteria[] = " post_type = 'post' ";
        $criteria[] = " date_publish_start <= NOW() ";
        $criteria[] = " (date_publish_end >= NOW() OR date_publish_end = '0000-00-00 00:00:00') ";
        $criteria[] = " p.status = '1' ";
        $criteria[] = " p2s.store_id = '". (int)STORE_ID ."' ";
        
        if (!empty($data['category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (p.post_id = p2c.post_id) ";
            $criteria[] = " p2c.post_category_id = '" . (int)$data['category_id'] . "' ";
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
        `object_type`   = 'post',
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
