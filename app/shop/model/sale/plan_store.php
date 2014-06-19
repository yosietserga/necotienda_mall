<?php
class ModelSalePlanStore extends Model {
    public function getById($id) {
        
    }
    
    public function getAll() {
        $query = $this->db->query("SELECT * FROM `". DB_PREFIX ."plan_store` WHERE `status` = 1 ORDER BY sort_order ASC");
        return $query->rows;
    }
}