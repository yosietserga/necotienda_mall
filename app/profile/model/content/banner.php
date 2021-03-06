<?php
/**
 * ModelContentBanner
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelContentBanner extends Model {
	/**
	 * ModelContentBanner::getById()
	 * 
	 * @param int $banner_id
     * @see DB
     * @see Cache
	 * @return array sql record
	 */
	public function getById($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner b
        LEFT JOIN " . DB_PREFIX . "banner_to_store b2s ON (b.banner_id=b2s.banner_id)
        WHERE b.banner_id = '" . (int)$banner_id . "' 
        AND b.publish_date_start <= NOW() 
        AND (b.publish_date_end >= NOW() OR b.publish_date_end = '0000-00-00')
        AND b.status = '1'
        AND b2s.store_id = '". (int)STORE_ID ."'"); //TODO: asociar con multitiendas
        
        $return = array();
        if ($query->num_rows) {
            $return = array_merge($query->row,array('items'=>$this->getItems($banner_id)));
        }
		return $return;
	}
	
	/**
	 * ModelContentCategory::getItems()
	 * 
	 * @param int $banner_id
     * @see DB
	 * @return array sql records
	 */
	public function getItems($banner_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_item bi
        LEFT JOIN " . DB_PREFIX . "banner_item_description bid ON (bi.banner_item_id=bid.banner_item_id)
        WHERE bi.banner_id = '" . (int)$banner_id . "' 
        AND bi.status = '1'
        AND bid.language_id = '". (int)$this->config->get('config_language_id') ."'");
		return $query->rows;
	}
}