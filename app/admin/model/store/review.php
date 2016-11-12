<?php
class ModelStoreReview extends Model {
	public function add($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET 
        author = '" . $this->db->escape($data['author']) . "', 
        object_id = '" . (int)$data['product_id'] . "', 
        object_type = '" . $this->db->escape($data['object_type']) . "', 
        text = '" . $this->db->escape(strip_tags($data['text'])) . "', 
        rating = '" . (int)$data['rating'] . "', 
        status = '1', 
        date_added = NOW()");
        return $this->db->getLastId();
	}
	
	public function addReply($data) {
        if (!(int)$data['review_id'] && !(int)$data['product_id']) return false;
       
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET 
        author      = '". $this->db->escape($this->user->getUserName()) ."', 
        parent_id   = '". (int)$data['review_id'] ."', 
        customer_id = '0', 
        object_id = '" . (int)$data['product_id'] . "', 
        object_type = '" . $this->db->escape($data['object_type']) . "', 
        text        = '". $this->db->escape(strip_tags($data['text'])) ."', 
        rating      = '0',
        status      = '1', 
        date_added  = NOW()");
	}
    
	public function update($review_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "review SET 
        author = '" . $this->db->escape($data['author']) . "', 
        object_id = '" . (int)$data['product_id'] . "', 
        object_type = '" . $this->db->escape($data['object_type']) . "', 
        text = '" . $this->db->escape(strip_tags($data['text'])) . "', 
        rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', 
        date_added = NOW() 
        WHERE review_id = '" . (int)$review_id . "'");
	}
	
	public function delete($review_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE parent_id = '" . (int)$review_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review_likes WHERE review_id = '" . (int)$review_id . "'");
	}
	
	public function getById($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, r.object_id AS pid, pd.name AS product, SUM(rl.`like`) AS likes, SUM(rl.`dislike`) AS dislikes 
        FROM ". DB_PREFIX ."review r
        LEFT JOIN ". DB_PREFIX ."product_description pd ON (r.object_id=pd.product_id) 
        LEFT JOIN ". DB_PREFIX ."review_likes rl ON (r.review_id=rl.review_id) 
        WHERE r.review_id = '" . (int)$review_id . "'
        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}

	public function getAllByProductId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "review r 
        LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = r.customer_id) 
        WHERE r.object_id = '" . (int)$product_id . "' AND object_type = 'product'");
		return $query->rows;
	}
    
    public function getReplies($review_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "review r 
        WHERE parent_id = ". (int)$review_id ." 
        ORDER BY date_added DESC");
		
		return $query->rows;
	}
    
	public function getAll($data = array()) {
		$sql = "SELECT *, r.status AS rstatus, r.date_added AS created, r.review_id AS rid, SUM(rl.`like`) AS likes, SUM(rl.`dislike`) AS dislikes 
            FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.object_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "review_likes rl ON (r.review_id=rl.review_id) 
            LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = r.customer_id)";																		
            
        $criteria = array();
            
		if (!empty($data['filter_author'])) {
            $criteria[] = " LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_author'])) . "%' ";
		}

		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $criteria[] = " r.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "' ";
		} elseif (!empty($data['filter_date_start'])) {
            $criteria[] = " r.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}
        
        if (!empty($data['filter_product'])) {
            $criteria[] = " r.review_id IN (SELECT review_id 
                FROM " . DB_PREFIX . "review p2
                    LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2.object_id=pd.product_id) 
                WHERE LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_product'])) . "%'
                    AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
        }
            
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
		
        $sql .= " GROUP BY r.review_id";					  
		
        $sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
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
	
	public function getAllTotal() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review");
		
		return $query->row['total'];
	}
	
	public function getAllTotalAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");
		
		return $query->row['total'];
	}	
	/**
	 * ModelStoreProduct::sortProduct()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortProduct($data) {
	   if (!is_array($data)) return false;
       $pos = 1;
       foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "review SET sort_order = '" . (int)$pos . "' WHERE review_id = '" . (int)$id . "'");
            $pos++;
       }
	   return true;
	}
	
    /**
     * ModelStoreProduct::activate()
     * activar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function activate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "review` SET `status` = '1' WHERE `review_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "review` SET `status` = '0' WHERE `review_id` = '" . (int)$id . "'");
        return $query;
     }
}
