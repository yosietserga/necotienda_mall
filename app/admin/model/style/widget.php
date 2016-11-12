<?php
/**
 * ModelStoreWidget
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelStyleWidget extends Model {
	/**
	 * ModelStoreWidget::deleteWidget()
	 * 
	 * @param int $widget_id
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function delete($name) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "widget_landing_page WHERE widget_id = (SELECT DISTINCT widget_id FROM " . DB_PREFIX . "widget WHERE `name` = '" . $this->db->escape($name) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "widget WHERE `name` = '" . $this->db->escape($name) . "'");
	}	
	
	public function getStores($id) {
		$data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "widget_to_store WHERE widget_id = '" . (int)$id . "'");
		foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
		}
		return $data;
	}	
	
	/**
	 * ModelStoreWidget::deleteWidget()
	 * 
	 * @param int $widget_id
     * @see DB
     * @see Cache
	 * @return void 
	 */
	public function deleteAll($name) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "widget_landing_page WHERE widget_id IN 
        (SELECT widget_id FROM " . DB_PREFIX . "widget WHERE `extension` = '" . $this->db->escape($name) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "widget WHERE `extension` = '" . $this->db->escape($name) . "'");
	}	
	
	/**
	 * ModelStoreWidget::getWidget()
	 * 
	 * @param int $widget_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function getWidget($widget_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."widget t 
        WHERE t.widget_id = '" . (int)$widget_id . "'");
		return $query->row;
	}
	
	/**
	 * ModelStoreWidget::getWidget()
	 * 
	 * @param int $widget_id
     * @see DB
     * @see Cache
	 * @return array sql record 
	 */
	public function getByName($name) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."widget WHERE `name` = '" . $this->db->escape($name) . "'");
        return array(
            'widget_id'=>$query->row['widget_id'],
            'code'=>$query->row['code'],
            'name'=>$query->row['name'],
            'position'=>$query->row['position'],
            'extension'=>$query->row['extension'],
            'status'=>$query->row['status'],
            'app'=>$query->row['app'],
            'order'=>$query->row['order'],
            'settings'=>$query->row['settings'],
            'landing_pages'=>$this->getLandingPages($query->row['widget_id']),
        );
	}
	
	/**
	 * ModelStoreWidget::getWidgets()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records 
	 */
    public function getAll($data=array()) {
        $widgets = $this->db->query("SELECT * FROM `" . DB_PREFIX . "widget` WHERE store_id = '". (int)$data['store_id'] ."' ORDER BY `order` ASC");
        $return = array();
        foreach ($widgets->rows as $widget) {
            $return[$widget['position']][] = array(
                'widget_id'=>$widget['widget_id'],
                'code'=>$widget['code'],
                'name'=>$widget['name'],
                'position'=>$widget['position'],
                'extension'=>$widget['extension'],
                'status'=>$widget['status'],
                'app'=>$widget['app'],
                'order'=>$widget['order'],
                'settings'=>$widget['settings'],
                'landing_pages'=>$this->getLandingPages($widget['widget_id']),
            );
        }
        return $return;
	}

	/**
	 * ModelStoreWidget::getWidgets()
	 * 
	 * @param mixed $data
     * @see DB
     * @see Cache
	 * @return array sql records 
	 */
    public function getLandingPages($id) {
        $widgets = $this->db->query("SELECT * FROM `" . DB_PREFIX . "widget_landing_page` WHERE widget_id = '". (int)$id ."'");
        return $widgets->rows;
	}

	/**
	 * ModelStoreWidget::getTotalWidgets()
	 * 
     * @see DB
	 * @return int Count sql records 
	 */
	public function getTotalWidgets($data = null) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "widget m";
			
        $implode = array();
    		
    	if (!empty($data['filter_name'])) {
    	   $implode[] = "LCASE(name) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
    	}
    		
    	if (!empty($data['filter_template'])) {
    	   $implode[] = "template_id IN (SELECT template_id FROM " . DB_PREFIX . "template WHERE LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_template'])) . "%')";
    	}
    		
    	if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s',strtotime($data['filter_date_end'])) . "'";
   		} elseif (!empty($data['filter_date_start'])) {
            $implode[] = " date_added BETWEEN '" . date('Y-m-d h:i:s',strtotime($data['filter_date_start'])) . "' AND '" . date('Y-m-d h:i:s') . "'";
   		}
    
    	if ($implode) {
    	   $sql .= " WHERE " . implode(" AND ", $implode);
    	}
            
    	$query = $this->db->query($sql);
            
		return $query->row['total'];
	}	
	/**
	 * ModelStoreProduct::sortWidget()
	 * @param array $data
     * @see DB
     * @see Cache
	 * @return void
	 */
	public function sortWidget($data) {
	   if (!is_array($data)) return false;
       foreach ($data as $widgetName => $value) {
            $this->db->query("UPDATE ". DB_PREFIX ."widget SET 
            `order` = '". (int)$value['order'] ."',
            `position` = '". $this->db->escape($value['position']) ."'
             WHERE `name` = '" . $this->db->escape($value['name']) . "'");
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
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "widget` SET `status` = '1' WHERE `widget_id` = '" . (int)$id . "'");
        return $query;
     }
    
    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
     public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "widget` SET `status` = '0' WHERE `widget_id` = '" . (int)$id . "'");
        return $query;
     }
}