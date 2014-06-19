<?php
class NecoWidget { 
    /**
     * @param $user_banned
     * Utilizado para saber cuando usuario está bloqueado
     * */
    private $user_banned = false;
    
    private $db;
    private $user;
    private $resgistry;
    private $widgets;
    private $data = array();
    private $result = array();
    
    //TODO: construir un mapa de todas las funciones llamables a través de ajax
    public function __construct($registry,$route="all",$app='shop') {
  	 
  	 /**
      * 1. cargar todos los widgets {precargar los widgets en cache}
      * 2. almacenar los widgets en una variable pública
      * 3. 
      * 
      * */
      
        $this->landing_page = $route;
        $this->app = $app;
        $this->registry = $registry;
        $this->config = $this->registry->get('config');
        $this->cache = $this->registry->get('cache');
        $this->db = $this->registry->get('db');
        $this->ip = $_SERVER['REMOTE_ADDR'];
        
  	}
    
    public function getRoutes() {
    	$routes['text_home'] = 'common/home';
        $routes['text_account_login'] = 'account/login';
    	$routes['text_account_register'] = 'account/register';
    	$routes['text_account_forgotten'] = 'account/forgotten';
    	$routes['text_account_success'] = 'account/success';
    	$routes['text_cart_success'] = 'checkout/success';
    	$routes['text_cart'] = 'checkout/cart';
    	$routes['text_bestseller'] = 'store/bestseller';
    	$routes['text_manufacturer'] = 'store/manufacturer';
    	$routes['text_manufacturers'] = 'store/manufacturer/all';
    	$routes['text_category'] = 'store/category';
    	$routes['text_categories'] = 'store/category/all';
    	$routes['text_product'] = 'store/product';
    	$routes['text_products'] = 'store/product/all';
    	$routes['text_search'] = 'store/search';
    	$routes['text_special'] = 'store/special';
    	$routes['text_pages'] = 'content/page/all';
    	$routes['text_page'] = 'content/page';
    	$routes['text_post_category'] = 'content/category';
    	$routes['text_post_categories'] = 'content/category/all';
   		$routes['text_post'] = 'content/post';
    	$routes['text_posts'] = 'content/post/all';
    	$routes['text_sitemap'] = 'page/sitemap';
    	$routes['text_contact'] = 'page/contact';
        return $routes;
    }
    
    public function getWidgets($position="main") {
        $widgets = $this->db->query("SELECT * 
            FROM `" . DB_PREFIX . "widget` w 
            WHERE w.`position` = '". $this->db->escape($position)."'
                AND w.`store_id` = '". (int)STORE_ID."'
                AND w.`widget_id` IN (SELECT widget_id FROM ". DB_PREFIX ."widget_landing_page WHERE landing_page = '". $this->db->escape($this->landing_page)."' OR landing_page = 'all')
            ORDER BY `order` ASC");
        $this->widgets = $widgets->rows;
        $this->cache->set($cacheId,$this->widgets);
        return isset($this->widgets) ? $this->widgets : null;
    }
    
    public function save($data) {
        if (!isset($data['name']) && !isset($data['position'])) return false;
        $result = $this->db->query("SELECT *,COUNT(*) AS total 
                FROM `" . DB_PREFIX . "widget` w 
                WHERE w.`name` = '". $this->db->escape($data['name'])."'");
        if ($result->row['total']) {
            $return = $this->db->query("UPDATE `" . DB_PREFIX . "widget` SET 
                `position` = '". $this->db->escape($data['position'])."',
                `order` = '". intval($data['order'])."',
                `status` = '1',
                `settings` = '". $this->db->escape(serialize($data['settings']))."'
                WHERE `name` = '". $this->db->escape($data['name'])."'");
            if (!empty($data['landing_page'])) {
                $this->db->query("DELETE FROM ". DB_PREFIX ."widget_landing_page WHERE `widget_id` = '". intval($result->row['widget_id'])."'");
                foreach ($data['landing_page'] as $landing_page) {
                    $this->db->query("INSERT INTO ". DB_PREFIX ."widget_landing_page SET
                    `widget_id` = '". intval($result->row['widget_id'])."',
                    landing_page = '". $this->db->escape($landing_page)."'");
                }
            }
        } else {
            $return = $this->db->query("INSERT INTO `" . DB_PREFIX . "widget` SET 
                `name` = '". $this->db->escape($data['name'])."',
                `code` = '{%". $this->db->escape($data['name'])."%}',
                `position` = '". $this->db->escape($data['position'])."',
                `extension` = '". $this->db->escape($data['extension'])."',
                `app` = '". $this->db->escape($data['app'])."',
                `order` = '". intval($data['order'])."',
                `store_id` = '". intval($data['store_id'])."',
                `status` = '1',
                `settings` = '". $this->db->escape(serialize($data['settings']))."'");
                $widget_id = $this->db->getLastId();
                
            $this->db->query("INSERT INTO `" . DB_PREFIX . "widget_landing_page` SET 
                `widget_id` = '". intval($widget_id)."',
                `landing_page` = '". $this->db->escape($data['landing_page']) ."'");
                
        }
        return $return;
    }
    
    public function __get($key) {
        return $this->data[$key];
    }
    
    public function __set($key,$value) {
        $this->data[$key] = $value;
    }
    
    public function __isset($key) {
        return isset($this->data[$key]);
    }
}
