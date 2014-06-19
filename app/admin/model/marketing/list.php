<?php 
class ModelMarketingList extends Model {
	public function add($data) {
	    $total_contacts = sizeof($data['contact_list']);
        $this->db->query("INSERT INTO " . DB_PREFIX . "contact_list SET 
        `name` = '" . $this->db->escape($data['name']) . "',
        `description` = '" . $this->db->escape($data['description']) . "',
        `total_contacts` = '" . (int)$total_contacts . "',
        `date_added` = NOW()");
        
        $id = $this->db->getLastId();
        
        if ($data['contact_list']) {
            foreach ($data['contact_list'] as $contact_id) {
               $this->db->query("REPLACE INTO " . DB_PREFIX . "contact_to_list SET 
                `contact_id` = '" . (int)$contact_id . "',
                `contact_list_id` = '" . (int)$id . "',
                `date_added` = NOW()");
            }
        }
        return $id;
	}
	
	public function update($contact_list_id,$data) {
	    $total_contacts = sizeof($data['contact_list']);
        $this->db->query("UPDATE " . DB_PREFIX . "contact_list SET 
        `name` = '" . $this->db->escape($data['name']) . "',
        `description` = '" . $this->db->escape($data['description']) . "',
        `total_contacts` = '" . (int)$total_contacts . "',
        `date_modified` = NOW()
        WHERE `contact_list_id` = ". (int)$contact_list_id);
        
        if ($data['contact_list']) {
            foreach ($data['contact_list'] as $contact_id) {
               $this->db->query("REPLACE INTO " . DB_PREFIX . "contact_to_list SET 
                `contact_id` = '" . (int)$contact_id . "',
                `contact_list_id` = '" . (int)$contact_list_id . "',
                `date_added` = NOW()");
            }
        }
	}
    
    public function addContact($id,$contact_id) {
        $this->db->query("REPLACE INTO " . DB_PREFIX . "contact_to_list SET 
            `contact_id` = '" . (int)$contact_id . "',
            `contact_list_id` = '" . (int)$id . "',
            `date_added` = NOW()");
    }
    
    public function getById($contact_list_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contact_list WHERE `contact_list_id` = ".(int)$contact_list_id);
        return $query->row;
    }
    
    public function getAll($data = array()) {
		$sql = "SELECT *, 
        (SELECT COUNT(*) FROM " . DB_PREFIX . "contact_to_list c2ll WHERE c2ll.contact_list_id = cl.contact_list_id) AS total_contacts 
        FROM " . DB_PREFIX . "contact_list cl";
        if ($data) {
    		$implode = array();
    		
    		if ($data['filter_name']) {
    			$implode[] = "cl.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if ($data['filter_contacts']) {
    		      $sql .= " LEFT JOIN " . DB_PREFIX . "contact_to_list c2l ON (c2l.contact_list_id = cl.contact_list_id) ";
    		      $sql .= " LEFT JOIN " . DB_PREFIX . "contact co ON (c2l.contact_id = co.contact_id) ";
    			$implode[] = "co.email LIKE '%" . $this->db->escape($data['filter_contacts']) . "%' OR co.name LIKE '%" . $this->db->escape($data['filter_contacts']) . "%'";
    		}
            
    		if ($data['filter_date_start'] && $data['filter_date_end']) {
                $implode[] = " cl.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif ($data['filter_date_start']) {
                $implode[] = " cl.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
    		if ($implode) {
    			$sql .= " WHERE " . implode(" AND ", $implode);
    		}
    			
    		$sort_data = array(
    			'cl.name',
    			'cl.date_added',
    			'total_contacts',
    		);	
    			
    		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
    			$sql .= " ORDER BY " . $data['sort'];	
    		} else {
    			$sql .= " ORDER BY cl.name";	
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
		}
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
    
    public function getAllTotal($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "contact_list` cl";
		
		$implode = array();
		
		if ($data['filter_name']) {
			$implode[] = "cl.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if ($data['filter_email']) {
		      $sql .= " LEFT JOIN " . DB_PREFIX . "contact_to_list c2l ON (c2l.contact_list_id = cl.contact_list_id) ";
		      $sql .= " LEFT JOIN " . DB_PREFIX . "contact co ON (c2l.contact_id = co.contact_id) ";
			$implode[] = "co.email LIKE '%" . $this->db->escape($data['filter_email']) . "%' OR co.name LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
        
		if ($data['filter_date_start'] && $data['filter_date_end']) {
            $implode[] = " cl.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
		} elseif (!empty($data['filter_date_start'])) {
            $implode[] = " cl.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
			
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	} 

	/**
	 * ModelMarketingList::copy()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function copy($id) {		
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "contact_list WHERE contact_list_id = '" . (int)$id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['contact_list'] = $this->getContacts(array($id));
			$data['name'] = $data['name'] ." - copia";
			$this->add($data);
		}
	}
	
	/**
	 * ModelMarketingList::delete()
	 * 
	 * @param int $id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function delete($id) {
	   //TODO: verificar que no tenga ninguna cola de trabajo pendiente
		$this->db->query("DELETE FROM " . DB_PREFIX . "contact_list WHERE contact_list_id = '" . (int)$id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contact_to_list WHERE contact_list_id = '" . (int)$id . "'");
	}
	
    
    public function getContacts($data) {
        if ($data) {
    		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "contact_to_list c2l 
            LEFT JOIN " . DB_PREFIX . "contact co ON (c2l.contact_id=co.contact_id) 
            WHERE c2l.contact_list_id IN (". implode(",",$data) .")";
    		$query = $this->db->query($sql);
    		
            foreach ($query->rows as $row) {
                $return[] = array(
                    "contact_id"=>$row['contact_id'],
                    "email"=>$row['email'],
                    "name"=>$row['name']
                );
            }
        }
		return $return;
	}	
    
}
