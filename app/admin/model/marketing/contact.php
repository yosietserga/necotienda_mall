<?php
class ModelMarketingContact extends Model {
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "contact SET 
          name          = '" . $this->db->escape($data['name']) . "',
          email         = '" . $this->db->escape($data['email']) . "', 
          customer_id   = '" . (int)$data['customer_id'] . "',
          date_added    = NOW()");
          
       $contact_id = $this->db->getLastId();
       
       if (!empty($data['contact_list'])) {
            foreach ($data['contact_list'] as $id) {
                $this->db->query("INSERT INTO ". DB_PREFIX ."contact_to_list SET 
                contact_list_id = '". (int)$id ."',
                contact_id      = '". (int)$contact_id ."',
                date_added      = NOW()");
            }
       }
       
       return $contact_id;
	}
	
	public function update($contact_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "contact SET 
          name = '" . $this->db->escape($data['name']) . "',
          email = '" . $this->db->escape($data['email']) . "', 
          customer_id = '" . (int)$data['customer_id'] . "',
          date_modified = NOW()
        WHERE contact_id = '" . (int)$contact_id . "'");
        
        if (!empty($data['contact_list'])) {
            $this->db->query("DELETE FROM ". DB_PREFIX ."contact_to_list WHERE contact_id = '". (int)$contact_id ."'");
            foreach ($data['contact_list'] as $id) {
                $this->db->query("INSERT INTO ". DB_PREFIX ."contact_to_list SET 
                contact_list_id = '". (int)$id ."',
                contact_id      = '". (int)$contact_id ."',
                date_added      = NOW()");
            }
       }
       
	}
	
	public function getContact($contact_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "contact ab
        WHERE contact_id = '" . (int)$contact_id . "'");
		return $query->row;
	}
    
	public function getContactExport($contact_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "contact ab 
        LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ab.customer_id) 
        LEFT JOIN " . DB_PREFIX . "address a ON (a.address_id = c.address_id) 
        WHERE contact_id = '" . (int)$contact_id . "'");
		return $query->row;
	}
	
    public function getContactsByListId($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contact c 
        LEFT JOIN " . DB_PREFIX . "contact_to_list c2l ON (c.contact_id=c2l.contact_id) 
        WHERE contact_list_id = ". (int)$id);
        
        $data = array();
        foreach ($query->rows as $row) {
            $data[] = $row['contact_id'];
        }
        return $data;
    }	
    
    public function getAllByContactId($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contact_list l 
        LEFT JOIN " . DB_PREFIX . "contact_to_list c2l ON (l.contact_list_id=c2l.contact_list_id) 
        WHERE c2l.contact_id = ". (int)$id);
        
        $data = array();
        foreach ($query->rows as $row) {
            $data[] = $row['contact_list_id'];
        }
        return $data;
    }	
    
	public function getContacts($data = array()) {
	   $cache_id = "admin.contacts". implode(".",$data);
       $cached = $this->cache->get($cache_id);
       if (!$cached) {
    		$sql = "SELECT *, co.email AS mail, co.date_added AS created
            FROM " . DB_PREFIX . "contact co 
            LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = co.customer_id) ";
    
    		$implode = array();
    		
    		if (!empty($data['filter_name'])) {
    			$implode[] = "co.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if (!empty($data['filter_email'])) {
    			$implode[] = "co.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
    		}
            
    		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
                $implode[] = " co.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif (!empty($data['filter_date_start'])) {
                $implode[] = " co.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
    		if ($implode) {
    			$sql .= " WHERE " . implode(" AND ", $implode);
    		}
    		
    		$sort_data = array(
    			'co.name',
    			'co.email',
    			'co.date_added'
    		);	
    			
    		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
    			$sql .= " ORDER BY " . $data['sort'];	
    		} else {
    			$sql .= " ORDER BY co.name";	
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
            
    		$this->cache->set($cache_id,$query->rows);
            
    		return $query->rows;
        } else {
            return $cached;
        }	
	}
	
	public function getTotalContacts($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contact co";
		
		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "co.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_email'])) {
			$implode[] = "co.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
        
		if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $implode[] = " co.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
		} elseif (!empty($data['filter_date_start'])) {
            $implode[] = " co.date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
			
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
    
    public function delete($id) {
        //TODO: verificar que no tiene tareas programadas como enviar campaña u otros, alertar si tiene alguna tarea pendiente
        $this->db->query("DELETE FROM ".DB_PREFIX."contact WHERE contact_id = '". (int)$id ."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."contact_to_list WHERE contact_id = '". (int)$id ."'");
    }
}
