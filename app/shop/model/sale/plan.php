<?php

class ModelSalePlan extends Model {

    /**
     * ModelSalePlanStore::getPlan()
     * 
     * @param int $plan_id
     * @see DB
     * @see Cache
     * @return array sql record 
     */
    public function getPlan($plan_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "plan WHERE plan_id = '" . (int) $plan_id . "'");
        return $query->row;
    }

    /**
     * ModelSalePlanStore::getPlans()
     * 
     * @param mixed $data
     * @see DB
     * @see Cache
     * @return array sql records 
     */
    public function getPlans() {
        $sql = "SELECT * FROM " . DB_PREFIX . "plan p 
       WHERE status = '1' 
       ORDER BY sort_order ASC";

        $query = $this->db->query($sql);

        return $query->rows;
    }

}
