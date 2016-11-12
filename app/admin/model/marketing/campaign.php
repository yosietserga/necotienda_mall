<?php
/**
 * ModelMarketingCampaign
 * 
 * @package NecoTienda
 * @author Inversiones Necoyoad, C.A.
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class ModelMarketingCampaign extends Model {
	/**
	 * ModelMarketingCampaign::add()
     * 
	 * Registra la información de la campaña en la base de datos y coloca todos los
     * enlaces a la url completa
     * 
     * @see DB::escape()
     * @see DB::query()
     * @see DB::getLastId()
	 * @return int $newsletter_id
	 */
	public function add($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "campaign SET 
          `newsletter_id`        = '" . (int)$data['newsletter_id'] . "',
          `name`            = '" . $this->db->escape($data['name']) . "',
          `subject`         = '" . $this->db->escape($data['subject']) . "',
          `from_name`       = '" . $this->db->escape($data['from_name']) . "',
          `from_email`      = '" . $this->db->escape($data['from_email']) . "',
          `replyto_email`   = '" . $this->db->escape($data['replyto_email']) . "',
          `trace_email`     = '" . (int)$data['trace_email'] . "',
          `trace_click`     = '" . (int)$data['trace_click'] . "',
          `embed_image`     = '" . (int)$data['embed_image'] . "',
          `repeat`          = '" . $this->db->escape($data['repeat']) . "',
          `date_start`      = '" . $this->db->escape($data['date_start']) . "',
          `date_end`        = '" . $this->db->escape($data['date_end']) . "',
          `date_added`      = NOW()");
        $id = $this->db->getLastId();
        
        if ($data['contacts']) {
            foreach ($data['contacts'] as $contact) {
              	$this->db->query("INSERT INTO " . DB_PREFIX . "campaign_contact SET 
                  `campaign_id`= '" . (int)$id . "',
                  `contact_id` = '" . (int)$contact['contact_id'] . "',
                  `name`       = '" . $this->db->escape($contact['name']) . "',
                  `email`      = '" . $this->db->escape($contact['email']) . "',
                  `status`     = 1");
            }
        }
        if ($data['links']) {
            foreach ($data['links'] as $link) {
              	$this->addLink($link,$id);
            }
        }
        return $id;
	}
	
	/**
	 * ModelMarketingCampaign::update()
     * 
	 * Actualiza la información de la campaña en la base de datos y coloca todos los
     * enlaces a la url completa
     * 
     * @see DB::escape()
     * @see DB::query()
     * @see DB::getLastId()
	 * @return void
	 */
	public function update($id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "campaign SET 
          `newsletter_id`        = '" . (int)$data['newsletter_id'] . "',
          `name`            = '" . $this->db->escape($data['name']) . "',
          `subject`         = '" . $this->db->escape($data['subject']) . "',
          `from_name`       = '" . $this->db->escape($data['from_name']) . "',
          `from_email`      = '" . $this->db->escape($data['from_email']) . "',
          `replyto_email`   = '" . $this->db->escape($data['replyto_email']) . "',
          `trace_email`     = '" . (int)$data['trace_email'] . "',
          `trace_click`     = '" . (int)$data['trace_click'] . "',
          `embed_image`     = '" . (int)$data['embed_image'] . "',
          `repeat`          = '" . $this->db->escape($data['repeat']) . "',
          `date_start`      = '" . $this->db->escape($data['date_start']) . "',
          `date_end`        = '" . $this->db->escape($data['date_end']) . "',
          WHERE campaign_id = '". (int)$id ."'");
        
        if ($data['contacts']) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "campaign_contact WHERE campaign_id = '". (int)$id ."'");
            foreach ($data['contacts'] as $contact) {
              	$this->db->query("INSERT INTO " . DB_PREFIX . "campaign_contact SET 
                  `campaign_id`= '" . (int)$id . "',
                  `contact_id` = '" . (int)$contact['contact_id'] . "',
                  `name`       = '" . $this->db->escape($contact['name']) . "',
                  `email`      = '" . $this->db->escape($contact['email']) . "',
                  `status`     = 1");
            }
        }
	}
    
	/**
	 * ModelStoreProduct::copy()
	 * 
	 * @param int $product_id
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function copy($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "campaign WHERE campaign_id = '" . (int)$id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['name'] = $data['name'] ." - copia";
			$data = array_merge($data, array('contacts' => $this->getContacts($id)));
			$data = array_merge($data, array('links' => $this->getLinks($id)));
			$this->add($data);
		}
	}
	
    /**
     * ModelMarketingCampaign::delete()
     * 
     * @return
     */
    public function delete($campaign_id) {
		//TODO: validar que no tenga trabajos de envío pendientes, si es así mostrar una confirmación
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "task_exec te
        LEFT JOIN " . DB_PREFIX . "task t ON (te.task_id=t.task_id) 
        WHERE object_id = '".(int)$campaign_id."' 
        AND object_type = 'campaign'");
        if (!$query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "campaign WHERE `campaign_id` = '".(int)$campaign_id."'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "campaign_contact WHERE `campaign_id` = '".(int)$campaign_id."'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "task_queue WHERE `task_id` IN (SELECT task_id FROM ". DB_PREFIX ."task WHERE object_id = '".(int)$campaign_id."' AND object_type = 'campaign')");
            $this->db->query("DELETE FROM " . DB_PREFIX . "task WHERE `task_id` = '".(int)$campaign_id."'");
        }
	}
    
    public function getContacts($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "campaign_contact WHERE campaign_id = '" . (int)$id . "'");
        return $query->rows;
    }
    
    public function getLinks($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "campaign_link WHERE campaign_id = '" . (int)$id . "'");
        return $query->rows;
    }
    
    public function getTasks($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "task WHERE object_id = '" . (int)$id . "' AND object_type = 'campaign'");
        return $query->rows;
    }
    
    public function getNewsletter($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . (int)$id . "'");
        return $query->row;
    }
    
	/**
	 * ModelMarketingCampaign::addLink()
     * Registra un enlace a la campaña para rastrearlo
     * 
     * @see DB::escape()
     * @see DB::query()
     * @see DB::getLastId()
	 * @return int $newsletter_id
	 */
	public function addLink($data,$id) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "campaign_link SET 
          `campaign_id` = '" . (int)$id . "',
          `url`         = '" . $this->db->escape($data['url']) . "',
          `redirect`    = '" . $this->db->escape($data['redirect']) . "',
          `link`        = '" . $this->db->escape($data['link_index']) . "',
          `date_added`  = NOW()");
	}
	
    /**
     * ModelMarketingCampaign::getCampaign()
     * 
     * @return
     */
    public function getById($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "campaign WHERE campaign_id = '" . (int)$id . "'");
        $return = $query->row;
        $return['links'] = $this->getLinks($id);
        $return['contacts'] = $this->getContacts($id);
        $return['tasks'] = $this->getTasks($id);
		return $return;
    }
	
    /**
     * ModelMarketingCampaign::getCampaigns()
     * 
     * @return
     */
    public function getAll($data = array()) {	
	    $sql = "SELECT * FROM " . DB_PREFIX . "campaign ";

		$criteria = array();
		
		if (!empty($data['filter_name'])) {
			$criteria[] = "LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
		}
		
		if ($criteria) {
			$sql .= " WHERE " . implode(" AND ", $criteria);
		}
		
		$sort_data = array(
			'name',
			'subject',
			'active',
			'archive',
			'date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
		
        $return = array();
		$query = $this->db->query($sql);
        
        foreach ($query->rows as $result) {
            $return[] = array(
                'campaign_id'   =>$result['campaign_id'],
                'name'          =>$result['name'],
                'subject'       =>$result['subject'],
                'status'        =>$result['status'],
                'date_added'    =>$result['date_added'],
                'date_start'    =>$result['date_start'],
                'date_end'      =>$result['date_end'],
                'repeat'        =>$result['repeat'],
                'newsletter'    =>$this->getNewsletter($result['newsletter_id']),
                'contacts'      =>$this->getContacts($result['campaign_id']),
                'links'         =>$this->getLinks($result['campaign_id']),
                'tasks'         =>$this->getTasks($result['campaign_id'])
            );
        }
        
		return $return;
    }
    
	/**
	 * ModelMarketingCampaign::getTotalCampaigns()
	 * 
	 * @return
	 */
	public function getTotalCampaigns($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "campaign";
		
		$criteria = array();
		
		if (!empty($data['filter_name'])) {
			$criteria[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_subject'])) {
			$criteria[] = "subject = '" . $this->db->escape($data['filter_subject']) . "'";
		}	
		
		if (!empty($data['filter_active'])) {
			$criteria[] = "active = '" . $this->db->escape($data['filter_active']) . "'";
		}	
		
		if (!empty($data['filter_archive'])) {
			$criteria[] = "archive = '" . $this->db->escape($data['filter_archive']) . "'";
		}				
		
		if (!empty($data['filter_date_start'])) {
			$criteria[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($criteria) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}

    /**
     * ModelMarketingCampaign::setMembersBanned()
     * 
     * @return
     */
    public function activate($id) {
		$this->db->query("UPDATE " . DB_PREFIX . "campaign SET status = '1' WHERE campaign_id = '" . (int)$id . "'");
	}

    /**
     * ModelMarketingCampaign::getActive()
     * 
     * @return
     */
    public function desactivate($id) {
		$this->db->query("UPDATE " . DB_PREFIX . "campaign SET status = '0' WHERE campaign_id = '" . (int)$id . "'");
	}
}
