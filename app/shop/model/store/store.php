<?php

class ModelStoreStore extends Model {

    public function add($data) {
        $result = $this->db->query("INSERT INTO " . DB_PREFIX . "store SET 
        owner_id     = '" . (int) $data['customer_id'] . "',
        name       = '" . $this->db->escape($data['storename']) . "',
        folder        = '" . $this->db->escape($data['storeurl']) . "',
        status          = 1,
        date_added      = NOW()");

        $store_id = $this->db->getLastId();

        return $store_id;
    }

    public function getStoreByUrl($url) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE folder = '" . $this->db->escape($url) . "'");
        return $query->row;
    }

    public function getByCustomerId($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE owner_id = '" . intval($customer_id) . "'");
        return $query->row;
    }

    public function getTotalStoresByName($name) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE name = '" . $this->db->escape($name) . "'");
        return $query->row['total'];
    }

}