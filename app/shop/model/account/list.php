<?php
class ModelAccountList extends Model {
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "list SET 
        owner_id    = '" . (int)$this->customer->getId() . "', 
        store_id    = '" . (int)STORE_ID . "', 
        name        = '" . $this->db->escape($data['name']) . "', 
        description = '" . $this->db->escape($data['description']) . "',
        status      = 1,
        date_added  = NOW()");
		
		return $this->db->getLastId();
	}
	
	public function update($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "list SET 
        owner_id = '" . (int)$this->customer->getId() . "', 
        name        = '" . $this->db->escape($data['name']) . "', 
        description = '" . $this->db->escape($data['description']) . "',
        date_modified  = NOW()
        WHERE list_id = '" . (int)$id . "'");
	}
	
	public function delete($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "list WHERE list_id = '" . (int)$id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_list WHERE list_id = '" . (int)$id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE object_id = '" . (int)$id . "' AND  object_type = 'list'");
	}	
	
	public function getById($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "list WHERE list_id = '" . (int)$id . "'");
		return $query->row;
	}
	
	public function getAll() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "list");
		return $query->row;
	}	
	
	public function getAllTotal() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "list");
	
		return $query->row['total'];
	}
}
