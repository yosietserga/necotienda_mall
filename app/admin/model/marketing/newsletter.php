<?php
/**
 * ModelMarketingNewsletter
 * 
 * @package NecoTienda
 * @author Inversiones Necoyoad, C.A.
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class ModelMarketingNewsletter extends Model {
	/**
	 * ModelMarketingNewsletter::add()
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
      	$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET 
          `name`        = '" . $this->db->escape($data['name']) . "',
          `textbody`    = '" . $this->db->escape($data['textbody']) . "',
          `htmlbody`    = '" . $this->db->escape($data['htmlbody']) . "',
          `date_added`  = NOW()");
        return $this->db->getLastId();
	}
	
	/**
	 * ModelMarketingNewsletter::update()
     * 
	 * Actualiza la información de la campaña en la base de datos y coloca todos los
     * enlaces a la url completa
     * 
     * @see DB::escape()
     * @see DB::query()
     * @see DB::getLastId()
	 * @return void
	 */
	public function update($newsletter_id, $data) {
      	return $this->db->query("UPDATE " . DB_PREFIX . "newsletter SET 
          `name`        = '" . $this->db->escape($data['name']) . "',
          `textbody`    = '" . $this->db->escape($data['textbody']) . "',
          `htmlbody`    = '" . $this->db->escape($data['htmlbody']) . "',
          `date_modified`  = NOW() 
          WHERE `newsletter_id` = '".(int)$newsletter_id."'");
	}
    
    public function copy($newsletter_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . (int)$newsletter_id . "'");
		
		if ($query->num_rows) {
			$data = array();
			$data = $query->row;
			$data['name'] = $data['name'] ." - copia";
			$this->add($data);
		}
	}
    
    /**
     * ModelMarketingNewsletter::delete()
     * 
     * @return
     */
    public function delete($newsletter_id) {
		//TODO: validar que no tenga trabajos de envío pendientes, si es así mostrar una confirmación            
        $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE `newsletter_id` = '".(int)$newsletter_id."'");
	}
    
    /**
     * ModelMarketingNewsletter::getNewsletter()
     * 
     * @return
     */
    public function getById($newsletter_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . (int)$newsletter_id . "'");
		return $query->row;
    }
	
    /**
     * ModelMarketingNewsletter::getNewsletters()
     * 
     * @return
     */
    public function getAll($data = array()) {	
	    $sql = "SELECT * FROM " . DB_PREFIX . "newsletter ";
            if ($data) {
    		$implode = array();
    		
    		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
    			$implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    		}
    		
    		if ($data['filter_date_start'] && $data['filter_date_end']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
    		} elseif ($data['filter_date_start']) {
                $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
    		}
    
    		if ($implode) {
    			$sql .= " WHERE " . implode(" AND ", $implode);
    		}
    		
    		$sort_data = array(
    			'name',
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
  		}
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
    
	/**
	 * ModelMarketingNewsletter::getTotalNewsletters()
	 * 
	 * @return
	 */
	public function getTotalNewsletters($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter";
		
		$implode = array();
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if ($data['filter_date_start'] && $data['filter_date_end']) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
		} elseif ($data['filter_date_start']) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}

    /**
     * ModelMarketingNewsletter::toggleStatus()
     * Alterna el status
     * @return integer status
     */
    public function toggleStatus($newsletter_id) {
        $result = $this->db->query("SELECT DISTINCT * FROM ".DB_PREFIX."newsletter WHERE newsletter_id = '" . (int)$newsletter_id . "'");
        $status = ($result->row['status']) ? 0 : 1;
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter SET status = '". (int)$status ."' WHERE newsletter_id = '" . (int)$newsletter_id . "'");
        return $status;
	}
    
    /**
     * ModelMarketingNewsletter::activate()
     * activa el objeto
     * @return void
     */
    public function activate($newsletter_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter SET status = '1' WHERE newsletter_id = '" . (int)$newsletter_id . "'");
	}
    
    /**
     * ModelMarketingNewsletter::desactivate()
     * desactva el objeto
     * @return void
     */
    public function desactivate($newsletter_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter SET status = '0' WHERE newsletter_id = '" . (int)$newsletter_id . "'");
	}
        
}
