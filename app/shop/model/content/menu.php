<?php
/**
 * ModelContentMenu
 * 
 * @package NecoTienda
 * @author NecoTienda
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 */
class ModelContentMenu extends Model {
	/**
	 * ModelContentMenu::getLinks()
	 * 
	 * @param int $menu_id
     * @see DB
	 * @return array sql record
	 */
	public function getLinks($menu_id,$parent_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "menu_link ml 
        LEFT JOIN " . DB_PREFIX . "menu_to_store m2s ON (ml.menu_id = m2s.menu_id) 
        WHERE  ml.menu_id = '" . (int)$menu_id . "' 
        AND ml.parent_id = '" . (int)$parent_id . "'
        AND m2s.store_id = '". (int)STORE_ID ."'");
        
        foreach ($query->rows as $value) {
            $keyword = $this->db->query("SELECT keyword 
            FROM " . DB_PREFIX . "url_alias 
            WHERE query = '" . $this->db->escape($value['link']) . "'
            AND language_id = '". (int)$this->config->get('config_language_id') ."'");
            $links[] = array(
                'menu_link_id'   =>$value['menu_link_id'],
                'menu_id'   =>$value['menu_id'],
                'parent_id' =>$value['parent_id'],
                'link'      =>$value['link'],
                'tag'      =>$value['tag'],
                'sort_order'=>$value['sort_order'],
                'keyword'   => $keyword->row['keyword']
            );
        }
        
		return $links;
	} 
	
	/**
	 * ModelContentMenu::getMenu()
	 * 
	 * @param int $menu_id
     * @see DB
	 * @return array sql record
	 */
	public function getMenu($menu_id) {
		$query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "menu_link ml 
        LEFT JOIN " . DB_PREFIX . "menu_to_store m2s ON (ml.menu_id = m2s.menu_id) 
        WHERE  menu_id = '" . (int)$menu_id . "' 
        AND m2s.store_id = '". (int)STORE_ID ."'");
        
        foreach ($query->rows as $value) {
            $keyword = $this->db->query("SELECT keyword 
            FROM " . DB_PREFIX . "url_alias 
            WHERE query = '" . $this->db->escape($value['link']) . "'
            AND language_id = '". (int)$this->config->get('config_language_id') ."'");
            $links[] = array(
                'menu_id'   =>$value['menu_id'],
                'parent_id' =>$value['parent_id'],
                'link'      =>$value['link'],
                'sort_order'=>$value['sort_order'],
                'keyword'   => $keyword->row['keyword']
            );
        }
        
		$query2 = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "menu m 
        LEFT JOIN " . DB_PREFIX . "menu_to_store m2s ON (m.menu_id = m2s.menu_id) 
        WHERE  m.menu_id = '" . (int)$menu_id . "' 
        AND m2s.store_id = '". (int)STORE_ID ."'");
        
        $return = array(
            'menu_id'   =>$query2->row['menu_id'],
            'position'  =>$query2->row['position'],
            'route'     =>$query2->row['route'],
            'name'      =>$query2->row['name'],
            'sort_order'=>$query2->row['sort_order'],
            'links'     =>$links
        );
        
		return $return;
	} 
    
	/**
	 * ModelContentMenu::getMainMenu()
	 * 
	 * @param int $menu_id
     * @see DB
	 * @return array sql record
	 */
	public function getMainMenu() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "menu m
        LEFT JOIN " . DB_PREFIX . "menu_to_store m2s ON (m.menu_id = m2s.menu_id) 
        WHERE `default` = '1'
        AND m2s.store_id = '". (int)STORE_ID ."' ");
		return $query->row;
	} 
}
