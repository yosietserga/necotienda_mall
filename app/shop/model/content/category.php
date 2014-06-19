<?php
class ModelContentCategory extends Model {
	public function getById($id) {
		$query = $this->db->query("SELECT DISTINCT * 
        FROM " . DB_PREFIX . "post_category pc 
        LEFT JOIN " . DB_PREFIX . "post_category_description pcd ON (pc.post_category_id = pcd.post_category_id) 
        LEFT JOIN " . DB_PREFIX . "post_category_to_store pc2s ON (pc.post_category_id = pc2s.post_category_id) 
        WHERE pc.post_category_id = '" . (int)$id . "' 
        AND pc2s.store_id = '". (int)STORE_ID ."' 
        AND pcd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND pc.status = '1'");
		return $query->row;
	}
    
	public function getTotalById($id) {
		$query = $this->db->query("SELECT COUNT(*) AS total
        FROM " . DB_PREFIX . "post_category pc 
        LEFT JOIN " . DB_PREFIX . "post_category_description pcd ON (pc.post_category_id = pcd.post_category_id) 
        LEFT JOIN " . DB_PREFIX . "post_category_to_store pc2s ON (pc.post_category_id = pc2s.post_category_id) 
        WHERE pc.parent_id = '" . (int)$id . "' 
        AND pc2s.store_id = '". (int)STORE_ID ."' 
        AND pcd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND pc.status = '1'");
		return $query->row['total'];
	}
	
	public function getAllById($id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "post_category pc 
        LEFT JOIN " . DB_PREFIX . "post_category_description pcd ON (pc.post_category_id = pcd.post_category_id) 
        LEFT JOIN " . DB_PREFIX . "post_category_to_store pc2s ON (pc.post_category_id = pc2s.post_category_id) 
        WHERE pc.parent_id = '" . (int)$id . "' 
        AND pc2s.store_id = '". (int)STORE_ID ."' 
        AND pcd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND pc.status = '1'");
		return $query->rows;
	}
	
	public function updateStats($id) {
	   $this->load->library('browser');
       $browser = new Browser;
		$this->db->query("UPDATE " . DB_PREFIX . "post SET viewed = viewed + 1 WHERE post_id = '" . (int)$id . "'");
		$this->db->query("INSERT " . DB_PREFIX . "stat SET 
        `object_id`     = '". (int)$id ."',
        `store_id`      = '". (int)STORE_ID ."',
        `customer_id`   = '". (int)$this->customer->getId() ."',
        `object_type`   = 'post_category',
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
