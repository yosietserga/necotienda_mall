<?php

class Activity {

    private $_id;
    private $registry;
    private $data = array();
    private $config;
    private $db;

    public function __construct($registry, $id) {
        $this->registry = $registry;
        $this->_id = $id;
        
        $this->config = $this->registry->get('config');
        $this->db = $this->registry->get('db');
        
        $query = $this->db->query("SELECT * "
                . "FROM " . DB_PREFIX . "customer_activity ca "
                . "LEFT JOIN " . DB_PREFIX . "customer c ON (ca.customer_id=c.customer_id) "
                . "WHERE customer_activity_id = '" . (int) $this->_id . "' "
                . "AND ca.status = '1'");

        if ($query->num_rows) {
            $this->customer_id  = $query->row['customer_id'];
            $this->firstname    = $query->row['firstname'];
            $this->lastname     = $query->row['lastname'];
            $this->profile      = $query->row['profile'];
            $this->email        = $query->row['email'];
            $this->rif          = $query->row['rif'];
            $this->company      = $query->row['company'];
            $this->photo        = $query->row['photo'];
            $this->birthday     = $query->row['birthday'];
            $this->blog         = $query->row['blog'];
            $this->website      = $query->row['website'];
            $this->telephone    = $query->row['telephone'];
            $this->profesion    = $query->row['profesion'];
            $this->titulo       = $query->row['titulo'];
            $this->msn          = $query->row['msn'];
            $this->gmail        = $query->row['gmail'];
            $this->yahoo        = $query->row['yahoo'];
            $this->skype        = $query->row['skype'];
            $this->facebook     = $query->row['facebook'];
            $this->twitter      = $query->row['twitter'];
            $this->complete     = $query->row['complete'];
            $this->sex          = $query->row['sex'];
            $this->customer_group_id = $query->row['customer_group_id'];
            $this->address_id   = $query->row['address_id'];
            $this->canPublish   = $query->row['can_publish'];
            $this->canBuy       = $query->row['can_buy'];
            $this->canAsk       = $query->row['can_ask'];
            $this->banned       = $query->row['banned'];
            $this->object_id    = $query->row['object_id'];
            $this->object_type  = $query->row['object_type'];
            $this->object       = $this->getObject($query->row['object_id'], $query->row['object_type']);
        } else {
            return false;
        }
    }
    
    public function __get($key) {
        return $this->data[$key];
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __isset($key) {
        return isset($this->data[$key]);
    }

    public function get($key) {
        return $this->data[$key];
    }

    public function getObject($id, $type) {
        switch ($type) {
            case 'product':
                $query = $this->db->query("SELECT *, "
                        . "(SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.object_id AND r.object_type = 'product' GROUP BY r.object_id) AS rating "
                        . "FROM ". DB_PREFIX ."product p "
                        . "LEFT JOIN ". DB_PREFIX ."product_description pd ON (p.product_id=pd.product_id) "
                        . "LEFT JOIN ". DB_PREFIX ."product_to_plan p2p ON (p.product_id=p2p.product_id) "
                        . "WHERE p.product_id = '". (int)$id ."' "
                        . "AND p.status = 1 "
                        . "AND p2p.date_start <= NOW() "
                        . "AND p2p.date_end >= NOW() ");
                
                $object = array(
                    'id'            =>$query->row['product_id'],
                    'name'          =>$query->row['name'],
                    'description'   =>$query->row['description'],
                    'price'         =>$query->row['price'],
                    'model'         =>$query->row['model'],
                    'image'         =>$query->row['image'],
                    'overview'      =>$query->row['meta_description'],
                    'rating'        =>$query->row['rating'],
                    'url_route'     =>'store/product',
                    'url_params'    =>array('product_id'=>$query->row['product_id'])
                );
                break;
            case 'category':
                $query = $this->db->query("SELECT *, "
                        . "(SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.category_id = r.object_id AND r.object_type = 'category' GROUP BY r.object_id) AS rating "
                        . "FROM ". DB_PREFIX ."category p "
                        . "LEFT JOIN ". DB_PREFIX ."category_description pd ON (p.category_id=pd.category_id) "
                        . "WHERE p.category_id = '". (int)$id ."' ");
                
                $object = array(
                    'id'            =>$query->row['category_id'],
                    'name'          =>$query->row['name'],
                    'description'   =>$query->row['description'],
                    'price'         =>null,
                    'model'         =>null,
                    'image'         =>$query->row['image'],
                    'overview'      =>$query->row['meta_description'],
                    'rating'        =>$query->row['rating'],
                    'url_route'     =>'store/category',
                    'url_params'    =>array('path'=>$query->row['category_id'])
                );
                break;
            case 'manufacturer':
                $query = $this->db->query("SELECT *, "
                        . "(SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.manufacturer_id = r.object_id AND r.object_type = 'manufacturer' GROUP BY r.object_id) AS rating "
                        . "FROM ". DB_PREFIX ."manufacturer p "
                        . "WHERE p.manufacturer_id = '". (int)$id ."' ");
                
                $object = array(
                    'id'            =>$query->row['manufacturer_id'],
                    'name'          =>$query->row['name'],
                    'description'   =>$query->row['description'],
                    'price'         =>null,
                    'model'         =>null,
                    'image'         =>$query->row['image'],
                    'overview'      =>$query->row['meta_description'],
                    'rating'        =>$query->row['rating'],
                    'url_route'     =>'store/manufacturer',
                    'url_params'    =>array('manufacturer_id'=>$query->row['manufacturer_id'])
                );
                break;
            case 'post':
                $query = $this->db->query("SELECT *, "
                        . "(SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.post_id = r.object_id AND r.object_type = 'post' GROUP BY r.object_id) AS rating "
                        . "FROM ". DB_PREFIX ."post p "
                        . "LEFT JOIN ". DB_PREFIX ."post_description pd ON (p.post_id=pd.post_id) "
                        . "WHERE p.post_id = '". (int)$id ."' ");
                
                $object = array(
                    'id'            =>$query->row['post_id'],
                    'name'          =>$query->row['title'],
                    'description'   =>$query->row['description'],
                    'price'         =>null,
                    'model'         =>null,
                    'image'         =>$query->row['image'],
                    'overview'      =>$query->row['meta_description'],
                    'rating'        =>$query->row['rating'],
                    'url_route'     =>'content/category',
                    'url_params'    =>array('post_id'=>$query->row['post_id'])
                );
                break;
            case 'page':
                $query = $this->db->query("SELECT *, "
                        . "(SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.post_id = r.object_id AND r.object_type = 'page' GROUP BY r.object_id) AS rating "
                        . "FROM ". DB_PREFIX ."post p "
                        . "LEFT JOIN ". DB_PREFIX ."post_description pd ON (p.post_id=pd.post_id) "
                        . "WHERE p.post_id = '". (int)$id ."' ");
                
                $object = array(
                    'id'            =>$query->row['post_id'],
                    'name'          =>$query->row['title'],
                    'description'   =>$query->row['description'],
                    'price'         =>null,
                    'model'         =>null,
                    'image'         =>$query->row['image'],
                    'overview'      =>$query->row['meta_description'],
                    'rating'        =>$query->row['rating'],
                    'url_route'     =>'content/category',
                    'url_params'    =>array('page_id'=>$query->row['post_id'])
                );
                break;
            case 'post_category':
                $query = $this->db->query("SELECT *, "
                        . "(SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.post_category_id = r.object_id AND r.object_type = 'post_category' GROUP BY r.object_id) AS rating "
                        . "FROM ". DB_PREFIX ."post_category p "
                        . "LEFT JOIN ". DB_PREFIX ."post_category_description pd ON (p.post_id=pd.post_id) "
                        . "WHERE p.post_category_id = '". (int)$id ."' ");
                
                $object = array(
                    'id'            =>$query->row['post_category_id'],
                    'name'          =>$query->row['title'],
                    'description'   =>$query->row['description'],
                    'price'         =>null,
                    'model'         =>null,
                    'image'         =>$query->row['image'],
                    'overview'      =>$query->row['meta_description'],
                    'rating'        =>$query->row['rating'],
                    'url_route'     =>'content/category',
                    'url_params'    =>array('path'=>$query->row['post_category_id'])
                );
                break;
            default:
                return false;
        }
        return $object;
    }

    public function getProduct($id) {
        return $this->getObject($id, 'product');
    }

}
