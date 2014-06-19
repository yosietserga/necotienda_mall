<?php
class ModelStoreReview extends Model {		
	public function addReview($object_id, $data, $object_type = 'product') {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET 
        author      = '" . $this->db->escape($this->customer->getFirstName() ." ". $this->customer->getLastName()) . "', 
        customer_id = '" . (int)$this->customer->getId() . "', 
        store_id   = '" . (int)STORE_ID . "', 
        object_id   = '" . (int)$object_id . "', 
        object_type = '" . $this->db->escape($object_type) . "', 
        text        = '" . $this->db->escape(strip_tags($data['text'])) . "', 
        rating      = '" . (int)$data['rating'] . "', 
        status      = '" . (int)$data['status'] . "', 
        date_added  = NOW()");
        return $this->db->getLastId();
	}

	public function addReply($data, $object_type = 'product') {
        if (!(int)$data['review_id'] && !(int)$data['object_id']) return false;
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET 
        author      = '". $this->db->escape($this->customer->getFirstName() ." ". $this->customer->getLastName()) . "', 
        parent_id   = '". (int)$data['review_id'] ."', 
        customer_id = '". (int)$this->customer->getId() ."', 
        store_id   = '" . (int)STORE_ID . "', 
        object_id   = '". (int)$data['object_id'] ."', 
        object_type = '" . $this->db->escape($object_type) . "', 
        text        = '". $this->db->escape(strip_tags($data['text'])) ."', 
        rating      = '0', 
        status      = '" . (int)$data['status'] . "', 
        date_added  = NOW()");
	}
    
	public function likeReview($review_id=null, $object_id=null, $object_type = 'product') {
        if (!$review_id && !$object_id) return false;
        
       $result = $this->db->query("SELECT * 
           FROM ". DB_PREFIX ."review_likes 
           WHERE review_id = '". (int)$review_id ."' 
           AND customer_id = '". (int)$this->customer->getId() ."'");
       
       if ($result->num_rows) {
            if ($result->row['like'] == 0) {
                $this->db->query("UPDATE " . DB_PREFIX . "review_likes SET 
                `like`        = 1,
                `dislike`     = 0, 
                `date_added`  = NOW()
                WHERE review_id = '". (int)$review_id ."' 
                AND customer_id = '". (int)$this->customer->getId() ."'
                AND object_id   = '". (int)$object_id ."'
                AND object_type = '". $this->db->escape($object_type) ."'");
            }
       } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "review_likes SET  
            `review_id`   = '". (int)$review_id ."', 
            `customer_id` = '". (int)$this->customer->getId() ."', 
            `object_id`   = '". (int)$object_id ."', 
            `object_type` = '". $this->db->escape($object_type) ."', 
            `store_id`    = '". (int)STORE_ID ."', 
            `like`        = 1, 
            `dislike`     = 0, 
            `date_added`  = NOW()");
       }
       $result = $this->db->query("SELECT SUM(`like`) AS likes, SUM(dislike) AS dislikes FROM ". DB_PREFIX ."review_likes WHERE review_id = '". (int)$review_id ."'");
       return $result->row;
	}
    
	public function dislikeReview($review_id=null,$object_id=null) {
        if (!$review_id && !$object_id) return false;
       $result = $this->db->query("SELECT * 
           FROM ". DB_PREFIX ."review_likes 
           WHERE review_id = '". (int)$review_id ."' 
           AND customer_id = '". (int)$this->customer->getId() ."'");
       
       if ($result->num_rows) {
            if ($result->row['dislike'] == 0) {
                $this->db->query("UPDATE " . DB_PREFIX . "review_likes SET 
                `like`        = 0,
                `dislike`     = 1, 
                `date_added`  = NOW()
                WHERE review_id = '". (int)$review_id ."' 
                AND customer_id = '". (int)$this->customer->getId() ."'
                AND object_id   = '". (int)$object_id ."'
                AND object_type = '". $this->db->escape($object_type) ."'");
            }
       } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "review_likes SET  
            `review_id`   = '". (int)$review_id ."', 
            `customer_id` = '". (int)$this->customer->getId() ."', 
            `object_id`   = '". (int)$object_id ."', 
            `object_type` = '". $this->db->escape($object_type) ."', 
            `store_id`    = '". (int)STORE_ID ."', 
            `dislike`     = 1, 
            `like`        = 0, 
            `date_added`  = NOW()");
       }
       $result = $this->db->query("SELECT SUM(`like`) AS likes, SUM(dislike) AS dislikes FROM ". DB_PREFIX ."review_likes WHERE review_id = '". (int)$review_id ."'");
       return $result->row;
	}
    
    public function deleteReview($id) {
        $this->db->query("DELETE FROM ". DB_PREFIX ."review WHERE review_id = '". (int)$id ."'");
        $this->db->query("DELETE FROM ". DB_PREFIX ."review WHERE parent_id = '". (int)$id ."'");
        $this->db->query("DELETE FROM ". DB_PREFIX ."review_likes WHERE review_id = '". (int)$id ."'");
    }
	
	public function getReviewsByProductId($object_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT r.customer_id, r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added, SUM(rl.like) AS likes, SUM(rl.dislike) AS dislikes 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "review_likes rl ON (r.review_id = rl.review_id) 
            LEFT JOIN " . DB_PREFIX . "product p ON (r.object_id = p.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        WHERE p.product_id = '" . (int)$object_id . "' 
            AND p.date_available <= NOW() 
            AND p.status = '1' 
            AND r.status = '1' 
            AND r.parent_id = '0' 
            AND r.object_type = 'product' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        GROUP BY r.review_id
        ORDER BY r.date_added DESC 
        LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
    
	public function getCustomersReviewsByProductId($object_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = r.customer_id) 
        WHERE r.object_id = '" . (int)$object_id . "'
            AND r.object_type = 'product'
            AND r.store_id = '". (int)STORE_ID ."'
        GROUP BY r.customer_id");
		return $query->rows;
	}
    
	public function getReviewsByPostId($object_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT r.customer_id, r.review_id, r.author, r.rating, r.text, p.post_id, pd.title, p.image, r.date_added, SUM(rl.like) AS likes, SUM(rl.dislike) AS dislikes 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "review_likes rl ON (r.review_id = rl.review_id) 
            LEFT JOIN " . DB_PREFIX . "post p ON (r.object_id = p.post_id) 
            LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        WHERE p.post_id = '" . (int)$object_id . "' 
            AND (p.date_publish_start <= NOW() OR p.date_publish_start = '0000-00-00 00:00:00') 
            AND (p.date_publish_end >= NOW() OR p.date_publish_end = '0000-00-00 00:00:00') 
            AND p.status = '1' 
            AND r.status = '1' 
            AND r.parent_id = '0' 
            AND r.object_type = 'post' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        GROUP BY r.review_id
        ORDER BY r.date_added DESC 
        LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
    
	public function getCustomersReviewsByPostId($object_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = r.customer_id) 
        WHERE r.object_id = '" . (int)$object_id . "'
            AND r.store_id = '". (int)STORE_ID ."'
            AND r.object_type = 'post'");
		return $query->rows;
	}
    
	public function getReviewsByPageId($object_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT r.customer_id, r.review_id, r.author, r.rating, r.text, p.post_id, pd.title, p.image, r.date_added, SUM(rl.like) AS likes, SUM(rl.dislike) AS dislikes 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "review_likes rl ON (r.review_id = rl.review_id) 
            LEFT JOIN " . DB_PREFIX . "post p ON (r.object_id = p.post_id) 
            LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
        WHERE p.post_id = '" . (int)$object_id . "' 
            AND (p.date_publish_start <= NOW() OR p.date_publish_start = '0000-00-00 00:00:00') 
            AND (p.date_publish_end >= NOW() OR p.date_publish_end = '0000-00-00 00:00:00') 
            AND p.status = '1' 
            AND r.status = '1' 
            AND r.parent_id = '0' 
            AND r.object_type = 'page' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        GROUP BY r.review_id
        ORDER BY r.date_added DESC 
        LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
    
	public function getCustomersReviewsByPageId($object_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = r.customer_id) 
        WHERE r.object_id = '" . (int)$object_id . "'
            AND r.store_id = '". (int)STORE_ID ."'
            AND r.object_type = 'page'");
		return $query->rows;
	}
    
    public function getReplies($review_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "review r 
        WHERE parent_id = ". (int)$review_id ." 
        AND status = '1' 
        ORDER BY date_added ASC");
		return $query->rows;
	}
    
	public function getAverageRating($object_id, $object_type='product') {
		$query = $this->db->query("SELECT AVG(rating) AS total 
        FROM " . DB_PREFIX . "review 
        WHERE status = '1' 
            AND object_id = '" . (int)$object_id . "' 
            AND object_type = '". $this->db->escape($object_type) ."'
        GROUP BY object_id");
		
		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}	
	
	public function getTotalReviews($object_id, $object_type='product') {
		$query = $this->db->query("SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "review r 
        WHERE status = '1' 
            AND object_id = '". (int)$object_id ."'
            AND store_id = '". (int)STORE_ID ."'
            AND object_type = '". $this->db->escape($object_type) ."' ");
		return $query->row['total'];
	}
    
	public function getTotalReviewsByProductId($object_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "product p ON (r.object_id = p.product_id) 
        WHERE p.product_id = '" . (int)$object_id . "' 
            AND p.date_available <= NOW() 
            AND p.status = '1' 
            AND r.parent_id = '0' 
            AND r.status = '1' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND r.object_type = 'product' ");
		return $query->row['total'];
	}
    
	public function getTotalReviewsByPostId($object_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "post p ON (r.object_id = p.post_id) 
        WHERE p.post_id = '" . (int)$object_id . "' 
            AND (p.date_publish_start <= NOW() OR p.date_publish_start = '0000-00-00 00:00:00') 
            AND (p.date_publish_end >= NOW() OR p.date_publish_end = '0000-00-00 00:00:00') 
            AND p.status = '1' 
            AND r.parent_id = '0' 
            AND r.status = '1' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND r.object_type = 'post' ");
		return $query->row['total'];
	}
    
	public function getTotalReviewsByPageId($object_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "post p ON (r.object_id = p.post_id) 
        WHERE p.post_id = '" . (int)$object_id . "' 
            AND (p.date_publish_start <= NOW() OR p.date_publish_start = '0000-00-00 00:00:00') 
            AND (p.date_publish_end >= NOW() OR p.date_publish_end = '0000-00-00 00:00:00') 
            AND p.status = '1' 
            AND r.parent_id = '0' 
            AND r.status = '1' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND r.object_type = 'page' ");
		return $query->row['total'];
	}
    
   	public function getById($id) {
        if (!$id) return false;
		$query = $this->db->query("SELECT *, p.product_id AS pid, r.date_added AS dateAdded 
        FROM " . DB_PREFIX . "review r 
            LEFT JOIN " . DB_PREFIX . "product p ON (r.object_id = p.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        WHERE r.review_id = '" . (int)$id . "' 
            AND p.date_available <= NOW() 
            AND p.status = '1' 
            AND r.store_id = '". (int)STORE_ID ."'
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
    
    public function getAllByCustomerTotal($id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r 
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.object_id = pd.product_id) 
        WHERE r.customer_id = '" . (int)$id . "'
            AND r.store_id = '". (int)STORE_ID ."'
        AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
    }
    
	public function getAllByCustomer($id,$data) {
		if ($data['start'] < 0) $data['start'] = 0;
		if ($data['limit'] <= 0) $data['limit'] = 25;
        
		$sql = "SELECT *, r.date_added AS dateAdded, pd.name AS product 
        FROM `" . DB_PREFIX . "review` r 
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.object_id = pd.product_id) ";	
	
        $criteria = array();
        
        $criteria[] = " r.store_id = '". (int)STORE_ID ."' ";
        $criteria[] = " r.customer_id = '" . (int)$id . "' ";
        $criteria[] = " pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
        
        if ($criteria) {
            $sql .= " WHERE " . implode(" AND ",$criteria);
        }
            
        $sql .= "ORDER BY r.date_added DESC, pd.name DESC ";
        $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
}