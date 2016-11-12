<?php

/**
 * ModelStoreProduct
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Model
 */
class ModelStoreProduct extends Model {

    /**
     * ModelStoreProduct::add()
     * 
     * @param mixed $data
     * @see DB
     * @see Cache
     * @return void
     */
    public function add($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET 
        model = '" . $this->db->escape($data['model']) . "', 
        sku = '" . $this->db->escape($data['sku']) . "', 
        location = '" . $this->db->escape($data['location']) . "', 
        quantity = '" . (int) $data['quantity'] . "', 
        minimum = '" . (int) $data['minimum'] . "', 
        subtract = '" . (int) $data['subtract'] . "', 
        stock_status_id = '" . (int) $data['stock_status_id'] . "', 
        date_available = '" . $this->db->escape($data['date_available']) . "', 
        manufacturer_id = '" . (int) $data['manufacturer_id'] . "', 
        shipping = '" . (int) $data['shipping'] . "', 
        price = '" . (float) $data['price'] . "', 
        cost = '" . (float) $data['cost'] . "', 
        weight = '" . (float) $data['weight'] . "', 
        weight_class_id = '" . (int) $data['weight_class_id'] . "', 
        length = '" . (float) $data['length'] . "', 
        width = '" . (float) $data['width'] . "', 
        height = '" . (float) $data['height'] . "', 
        length_class_id = '" . (int) $data['length_class_id'] . "', 
        status = '" . (int) $data['status'] . "', 
        tax_class_id = '" . (int) $data['tax_class_id'] . "', 
        sort_order = '" . (int) $data['sort_order'] . "', 
        date_added = NOW()");

        $product_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int) $product_id . "'");
        }

        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
            product_id = '" . (int) $product_id . "', 
            language_id = '" . (int) $language_id . "', 
            name = '" . $this->db->escape($value['name']) . "', 
            meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "', 
            meta_description = '" . $this->db->escape($value['meta_description']) . "', 
            description = '" . $this->db->escape($value['description']) . "'");

            $tags = explode(',', $value['meta_keywords']);
            foreach ($tags as $tag) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_tags SET 
                product_id = '" . (int) $product_id . "', 
                language_id = '" . (int) $language_id . "', 
                tag = '" . $this->db->escape(trim($tag)) . "'");
            }

            if (!empty($value['keyword'])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int) $language_id . "', 
                object_id    = '" . (int) $product_id . "', 
                object_type = 'product', 
                query       = 'product_id=" . (int) $product_id . "', 
                keyword     = '" . $this->db->escape($value['keyword']) . "'");
            }
        }

        foreach ($data['stores'] as $store) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
                store_id  = '" . intval($store) . "', 
                product_id = '" . intval($product_id) . "'");
        }

        if (isset($data['product_option'])) {
            foreach ($data['product_option'] as $product_option) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET 
                product_id = '" . (int) $product_id . "', 
                sort_order = '" . (int) $product_option['sort_order'] . "'");

                $product_option_id = $this->db->getLastId();

                foreach ($product_option['language'] as $language_id => $language) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_description SET 
                    product_option_id = '" . (int) $product_option_id . "', 
                    language_id = '" . (int) $language_id . "', 
                    product_id = '" . (int) $product_id . "', 
                    name = '" . $this->db->escape($language['name']) . "'");
                }

                if (isset($product_option['product_option_value'])) {
                    foreach ($product_option['product_option_value'] as $product_option_value) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET 
                        product_option_id = '" . (int) $product_option_id . "', 
                        product_id = '" . (int) $product_id . "', 
                        quantity = '" . (int) $product_option_value['quantity'] . "', 
                        subtract = '" . (int) $product_option_value['subtract'] . "', 
                        price = '" . (float) $product_option_value['price'] . "', 
                        prefix = '" . $this->db->escape($product_option_value['prefix']) . "', 
                        sort_order = '" . (int) $product_option_value['sort_order'] . "'");

                        $product_option_value_id = $this->db->getLastId();

                        foreach ($product_option_value['language'] as $language_id => $language) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_description SET 
                            product_option_value_id = '" . (int) $product_option_value_id . "', 
                            language_id = '" . (int) $language_id . "', 
                            product_id = '" . (int) $product_id . "', 
                            name = '" . $this->db->escape($language['name']) . "'");
                        }
                    }
                }
            }
        }

        if (isset($data['product_discount'])) {
            foreach ($data['product_discount'] as $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET 
                product_id = '" . (int) $product_id . "', 
                customer_group_id = '" . (int) $value['customer_group_id'] . "', 
                quantity = '" . (int) $value['quantity'] . "', 
                priority = '" . (int) $value['priority'] . "', 
                price = '" . (float) $value['price'] . "', 
                date_start = '" . $this->db->escape($value['date_start']) . "', 
                date_end = '" . $this->db->escape($value['date_end']) . "'");
            }
        }

        if (isset($data['product_special'])) {
            foreach ($data['product_special'] as $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET 
                product_id = '" . (int) $product_id . "', 
                customer_group_id = '" . (int) $value['customer_group_id'] . "', 
                priority = '" . (int) $value['priority'] . "', 
                price = '" . (float) $value['price'] . "', 
                date_start = '" . $this->db->escape($value['date_start']) . "', 
                date_end = '" . $this->db->escape($value['date_end']) . "'");
            }
        }

        if (isset($data['product_image'])) {
            foreach ($data['product_image'] as $image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET 
                product_id = '" . (int) $product_id . "', 
                image = '" . $this->db->escape($image) . "'");
            }
        }

        if (isset($data['product_download'])) {
            foreach ($data['product_download'] as $download_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET 
                product_id = '" . (int) $product_id . "', 
                download_id = '" . (int) $download_id . "'");
            }
        }

        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
                product_id = '" . (int) $product_id . "',
                category_id = '" . (int) $category_id . "'");
            }
        }

        if (isset($data['product_related'])) {
            foreach ($data['product_related'] as $related_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET 
                product_id = '" . (int) $product_id . "', 
                related_id = '" . (int) $related_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET 
                product_id = '" . (int) $related_id . "', 
                related_id = '" . (int) $product_id . "'");
            }
        }

        $this->cache->delete('product');

        return $product_id;
    }

    /**
     * ModelStoreProduct::editProduct()
     * 
     * @param int $product_id
     * @param mixed $data
     * @see DB
     * @see Cache
     * @return void
     */
    public function update($product_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET 
        model = '" . $this->db->escape($data['model']) . "', 
        sku = '" . $this->db->escape($data['sku']) . "', 
        location = '" . $this->db->escape($data['location']) . "', 
        quantity = '" . (int) $data['quantity'] . "', 
        minimum = '" . (int) $data['minimum'] . "', 
        subtract = '" . (int) $data['subtract'] . "', 
        stock_status_id = '" . (int) $data['stock_status_id'] . "', 
        date_available = '" . $this->db->escape($data['date_available']) . "', 
        manufacturer_id = '" . (int) $data['manufacturer_id'] . "', 
        shipping = '" . (int) $data['shipping'] . "', 
        price = '" . (float) $data['price'] . "', 
        cost = '" . (float) $data['cost'] . "', 
        weight = '" . (float) $data['weight'] . "', 
        weight_class_id = '" . (int) $data['weight_class_id'] . "', 
        length = '" . (float) $data['length'] . "', 
        width = '" . (float) $data['width'] . "', 
        height = '" . (float) $data['height'] . "', 
        length_class_id = '" . (int) $data['length_class_id'] . "', 
        status = '" . (int) $data['status'] . "', 
        tax_class_id = '" . (int) $data['tax_class_id'] . "', 
        sort_order = '" . (int) $data['sort_order'] . "', 
        date_modified = NOW() 
        WHERE product_id = '" . (int) $product_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET 
            image = '" . $this->db->escape($data['image']) . "' 
            WHERE product_id = '" . (int) $product_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");

        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
            product_id = '" . (int) $product_id . "', 
            language_id = '" . (int) $language_id . "', 
            name = '" . $this->db->escape($value['name']) . "', 
            meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "', 
            meta_description = '" . $this->db->escape($value['meta_description']) . "', 
            description = '" . $this->db->escape($value['description']) . "'");

            $tags = explode(',', $value['meta_keywords']);
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_tags WHERE product_id = '" . (int) $product_id . "'");
            foreach ($tags as $tag) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_tags SET 
                product_id = '" . (int) $product_id . "', 
                language_id = '" . (int) $language_id . "', 
                tag = '" . $this->db->escape(trim($tag)) . "'");
            }

            if (!empty($value['keyword'])) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias 
                WHERE object_id = '" . (int) $product_id . "' 
                AND language_id = '" . (int) $language_id . "' 
                AND object_type = 'product'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                language_id = '" . (int) $language_id . "', 
                object_id    = '" . (int) $product_id . "', 
                object_type = 'product', 
                query       = 'product_id=" . (int) $product_id . "', 
                keyword     = '" . $this->db->escape($value['keyword']) . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $product_id . "'");
        foreach ($data['stores'] as $store) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
                store_id  = '" . intval($store) . "', 
                product_id = '" . intval($product_id) . "'");
        }


        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_description WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_option'])) {
            foreach ($data['product_option'] as $product_option) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET 
                product_id = '" . (int) $product_id . "', 
                sort_order = '" . (int) $product_option['sort_order'] . "'");

                $product_option_id = $this->db->getLastId();

                foreach ($product_option['language'] as $language_id => $language) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_description SET 
                    product_option_id = '" . (int) $product_option_id . "', 
                    language_id = '" . (int) $language_id . "', 
                    product_id = '" . (int) $product_id . "', 
                    name = '" . $this->db->escape($language['name']) . "'");
                }

                if (isset($product_option['product_option_value'])) {
                    foreach ($product_option['product_option_value'] as $product_option_value) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET 
                        product_option_id = '" . (int) $product_option_id . "', 
                        product_id = '" . (int) $product_id . "', 
                        quantity = '" . (int) $product_option_value['quantity'] . "', 
                        subtract = '" . (int) $product_option_value['subtract'] . "', 
                        price = '" . (float) $product_option_value['price'] . "', 
                        prefix = '" . $this->db->escape($product_option_value['prefix']) . "', 
                        sort_order = '" . (int) $product_option_value['sort_order'] . "'");

                        $product_option_value_id = $this->db->getLastId();

                        foreach ($product_option_value['language'] as $language_id => $language) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_description SET 
                            product_option_value_id = '" . (int) $product_option_value_id . "', 
                            language_id = '" . (int) $language_id . "', 
                            product_id = '" . (int) $product_id . "', 
                            name = '" . $this->db->escape($language['name']) . "'");
                        }
                    }
                }
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_discount'])) {
            foreach ($data['product_discount'] as $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET 
                product_id = '" . (int) $product_id . "', 
                customer_group_id = '" . (int) $value['customer_group_id'] . "', 
                quantity = '" . (int) $value['quantity'] . "', 
                priority = '" . (int) $value['priority'] . "', 
                price = '" . (float) $value['price'] . "', 
                date_start = '" . $this->db->escape($value['date_start']) . "', 
                date_end = '" . $this->db->escape($value['date_end']) . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_special'])) {
            foreach ($data['product_special'] as $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET 
                product_id = '" . (int) $product_id . "', 
                customer_group_id = '" . (int) $value['customer_group_id'] . "', 
                priority = '" . (int) $value['priority'] . "', 
                price = '" . (float) $value['price'] . "', 
                date_start = '" . $this->db->escape($value['date_start']) . "', 
                date_end = '" . $this->db->escape($value['date_end']) . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_image'])) {
            foreach ($data['product_image'] as $image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET 
                product_id = '" . (int) $product_id . "', 
                image = '" . $this->db->escape($image) . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_download'])) {
            foreach ($data['product_download'] as $download_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET 
                product_id = '" . (int) $product_id . "', 
                download_id = '" . (int) $download_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
                product_id = '" . (int) $product_id . "', 
                category_id = '" . (int) $category_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");
        if (isset($data['product_related'])) {
            foreach ($data['product_related'] as $related_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET 
                product_id = '" . (int) $product_id . "', 
                related_id = '" . (int) $related_id . "'");

                $this->db->query("DELETE 
                FROM " . DB_PREFIX . "product_related 
                WHERE product_id = '" . (int) $related_id . "' 
                AND related_id = '" . (int) $product_id . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET 
                product_id = '" . (int) $related_id . "', 
                related_id = '" . (int) $product_id . "'");
            }
        }

        $this->cache->delete('product');
    }

    /**
     * ModelStoreProduct::copy()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return void
     */
    public function copy($product_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = array();

            $data = $query->row;

            $data = array_merge($data, array('product_description' => $this->getDescriptions($product_id)));
            $data = array_merge($data, array('product_option' => $this->getOptions($product_id)));

            foreach ($data['product_description'] as $k => $v) {
                $data['product_description'][$k]['keyword'] = $v['keyword'] . uniqid("-");
            }

            $data['model'] = $data['model'] . uniqid("-");

            $data['product_image'] = array();

            $results = $this->getImages($product_id);

            foreach ($results as $result) {
                $data['product_image'][] = $result['image'];
            }

            $data = array_merge($data, array('product_discount' => $this->getDiscounts($product_id)));
            $data = array_merge($data, array('product_special' => $this->getSpecials($product_id)));
            $data = array_merge($data, array('product_download' => $this->getDownloads($product_id)));
            $data = array_merge($data, array('product_category' => $this->getCategories($product_id)));
            $data = array_merge($data, array('product_related' => $this->getRelated($product_id)));
            $data = array_merge($data, array('product_tags' => $this->getTags($product_id)));

            $this->add($data);
        }
    }

    public function getStores($id) {
        $data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $id . "'");
        foreach ($query->rows as $result) {
            $data[] = $result['store_id'];
        }
        return $data;
    }

    /**
     * ModelStoreProduct::deleteProduct()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return void
     */
    public function delete($product_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_description WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_plan WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_featured WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_homepage WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE object_id = '" . (int) $product_id . "' AND object_type = 'product'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_tags WHERE product_id='" . (int) $product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "stat WHERE object_id='" . (int) $product_id . "' AND object_type = 'product'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE object_id='" . (int) $product_id . "' AND object_type = 'product'");

        $this->cache->delete('product');
    }

    /**
     * ModelStoreProduct::getById()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql record
     */
    public function getById($product_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    /**
     * ModelStoreProduct::getAll()
     * 
     * @param mixed $data
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getAll($data = array()) {
        if ($data) {
            $sql = "SELECT *,p.product_id as pid,pd.description as pdescription,p.image as pimage,pd.name as pname, ss.name as ssname,m.name as mname,tc.title as tctitle, p.viewed AS pviewed FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id)
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
            LEFT JOIN " . DB_PREFIX . "tax_class tc ON (p.tax_class_id = tc.tax_class_id)
            LEFT JOIN " . DB_PREFIX . "weight_class_description wc ON (p.weight_class_id = wc.weight_class_id)
            WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

            if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
                $sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
            }

            if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
                $sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
            }

            if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
                $sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
            }

            if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
                $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
            }

            $sort_data = array(
                'pd.name',
                'p.model',
                'p.quantity',
                'p.status',
                'p.viewed',
                'p.sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY pd.name";
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

                $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            }

            $query = $this->db->query($sql);
            $this->cache->set('product.' . $this->config->get('config_language_id'), $product_data);
            return $query->rows;
        } else {
            $product_data = $this->cache->get('admin.product.all.for.list.' . $this->config->get('config_language_id'));
            if (!$product_data) {
                $query = $this->db->query("SELECT * 
                FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' 
                ORDER BY pd.name ASC");
                $product_data = $query->rows;
                $this->cache->set('admin.product.all.for.list.' . $this->config->get('config_language_id'), $product_data);
            }
            return $product_data;
        }
    }

    /**
     * ModelStoreProduct::addFeatured()
     * 
     * @param mixed $data
     * @see DB
     * @see Cache
     * @return void
     */
    public function addFeatured($data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_featured");

        if (isset($data['featured_product'])) {
            foreach ($data['featured_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_featured SET product_id = '" . (int) $product_id . "'");
            }
        }
    }

    /**
     * ModelStoreProduct::getAllFeatured()
     * 
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getAllFeatured() {
        $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_featured");

        $featured = array();

        foreach ($query->rows as $product) {
            $featured[] = $product['product_id'];
        }
        return $featured;
    }

    /**
     * ModelStoreProduct::getAllByKeyword()
     * 
     * @param mixed $keyword
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getAllByKeyword($keyword) {
        if ($keyword) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND (LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' OR LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%')");

            return $query->rows;
        } else {
            return array();
        }
    }

    /**
     * ModelStoreProduct::getAllByCategoryId()
     * 
     * @param int $category_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getAllByStoreId($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p 
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' 
        AND p2s.store_id = '" . (int) $id . "' 
        ORDER BY pd.name ASC");

        return $query->rows;
    }

    /**
     * ModelStoreProduct::getAllByCategoryId()
     * 
     * @param int $category_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getAllByCategoryId($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int) $id . "' ORDER BY pd.name ASC");

        return $query->rows;
    }

    /**
     * ModelStoreProduct::getAllByManufacturerId()
     * 
     * @param int $category_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getAllByManufacturerId($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p 
        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' 
        AND p.manufacturer_id = '" . (int) $id . "' ORDER BY pd.name ASC");

        return $query->rows;
    }

    /**
     * ModelStoreProduct::getDescriptions()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getDescriptions($product_id) {
        $product_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_description_data[$result['language_id']]['name'] = $result['name'];
            $product_description_data[$result['language_id']]['description'] = $result['description'];
            $product_description_data[$result['language_id']]['seo_title'] = $result['seo_title'];
            $product_description_data[$result['language_id']]['meta_keywords'] = $result['meta_keywords'];
            $product_description_data[$result['language_id']]['meta_description'] = $result['meta_description'];
        }

        $keywords = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias
        WHERE object_id = '" . (int) $product_id . "' 
        AND object_type = 'product'");

        foreach ($keywords->rows as $result) {
            $product_description_data[$result['language_id']]['keyword'] = $result['keyword'];
        }

        return $product_description_data;
    }

    /**
     * ModelStoreProduct::getOptions()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getOptions($product_id) {
        $product_option_data = array();

        $product_option = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "' ORDER BY sort_order");

        foreach ($product_option->rows as $product_option) {
            $product_option_value_data = array();

            $product_option_value = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int) $product_option['product_option_id'] . "' ORDER BY sort_order");

            foreach ($product_option_value->rows as $product_option_value) {
                $product_option_value_description_data = array();

                $product_option_value_description = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE product_option_value_id = '" . (int) $product_option_value['product_option_value_id'] . "'");

                foreach ($product_option_value_description->rows as $result) {
                    $product_option_value_description_data[$result['language_id']] = array('name' => $result['name']);
                }

                $product_option_value_data[] = array(
                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                    'language' => $product_option_value_description_data,
                    'quantity' => $product_option_value['quantity'],
                    'subtract' => $product_option_value['subtract'],
                    'price' => $product_option_value['price'],
                    'prefix' => $product_option_value['prefix'],
                    'sort_order' => $product_option_value['sort_order']
                );
            }

            $product_option_description_data = array();

            $product_option_description = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE product_option_id = '" . (int) $product_option['product_option_id'] . "'");

            foreach ($product_option_description->rows as $result) {
                $product_option_description_data[$result['language_id']] = array('name' => $result['name']);
            }

            $product_option_data[] = array(
                'product_option_id' => $product_option['product_option_id'],
                'language' => $product_option_description_data,
                'product_option_value' => $product_option_value_data,
                'sort_order' => $product_option['sort_order']
            );
        }

        return $product_option_data;
    }

    /**
     * ModelStoreProduct::getImages()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getImages($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");

        return $query->rows;
    }

    /**
     * ModelStoreProduct::getDiscounts()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getDiscounts($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "' ORDER BY quantity, priority, price");

        return $query->rows;
    }

    /**
     * ModelStoreProduct::getSpecials()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getSpecials($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "' ORDER BY priority, price");

        return $query->rows;
    }

    /**
     * ModelStoreProduct::getDownloads()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getDownloads($product_id) {
        $product_download_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_download_data[] = $result['download_id'];
        }

        return $product_download_data;
    }

    /**
     * ModelStoreProduct::getCategories()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getCategories($product_id) {
        $product_category_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    /**
     * ModelStoreProduct::getRelated()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getRelated($product_id) {
        $product_related_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_related_data[] = $result['related_id'];
        }

        return $product_related_data;
    }

    /**
     * ModelStoreProduct::getTags()
     * 
     * @param int $product_id
     * @see DB
     * @see Cache
     * @return array sql records
     */
    public function getTags($product_id) {
        $query = $this->db->query("SELECT * 
            FROM " . DB_PREFIX . "product_tags 
            WHERE product_id = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $query2 = $this->db->query("SELECT tag 
                FROM " . DB_PREFIX . "product_tags 
                WHERE product_id = '" . (int) $product_id . "' 
                AND language_id = '" . (int) $result['language_id'] . "'");

            $product_tags_data[$result['language_id']] = array(
                'tag' => implode(",", $query2->rows)
            );
        }

        return $product_tags_data;
    }

    /**
     * ModelStoreProduct::getAllTotal()
     * 
     * @param mixed $data
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
            $sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByStockStatusId()
     * 
     * @param int $stock_status_id
     * @see DB
     * @see Cache
     * @return int Count sql record
     */
    public function getAllTotalByStockStatusId($stock_status_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int) $stock_status_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByImageId()
     * 
     * @param int $image_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByImageId($image_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE image_id = '" . (int) $image_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByTaxClassId()
     * 
     * @param int $tax_class_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByTaxClassId($tax_class_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int) $tax_class_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByWeightClassId()
     * 
     * @param int $weight_class_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByWeightClassId($weight_class_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int) $weight_class_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByLengthClassId()
     * 
     * @param int $length_class_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByLengthClassId($length_class_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int) $length_class_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByOptionId()
     * 
     * @param int $option_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByOptionId($option_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_option WHERE option_id = '" . (int) $option_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByDownloadId()
     * 
     * @param int $download_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByDownloadId($download_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int) $download_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::getAllTotalByManufacturerId()
     * 
     * @param int $manufacturer_id
     * @see DB
     * @see Cache
     * @return int Count sql records
     */
    public function getAllTotalByManufacturerId($manufacturer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");

        return $query->row['total'];
    }

    /**
     * ModelStoreProduct::sortProduct()
     * @param array $data
     * @see DB
     * @see Cache
     * @return void
     */
    public function sortProduct($data) {
        if (!is_array($data))
            return false;
        $pos = 1;
        foreach ($data as $id) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET sort_order = '" . (int) $pos . "' WHERE product_id = '" . (int) $id . "'");
            $pos++;
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
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = '1' WHERE `product_id` = '" . (int) $id . "'");
        return $query;
    }

    /**
     * ModelStoreProduct::desactivate()
     * desactivar un objeto
     * @param integer $id del objeto
     * @return boolean
     * */
    public function desactivate($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = '0' WHERE `product_id` = '" . (int) $id . "'");
        return $query;
    }

    /**
     * ModelStoreProduct::paid()
     * publicsacion paga
     * @param integer $id del objeto
     * @return boolean
     * */
    public function paid($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `paid` = '1' WHERE `product_id` = '" . (int) $id . "'");
        return $query;
    }

    /**
     * ModelStoreProduct::unpaid()
     * publicacion no paga
     * @param integer $id del objeto
     * @return boolean
     * */
    public function unpaid($id) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `paid` = '0' WHERE `product_id` = '" . (int) $id . "'");
        return $query;
    }

    /**
     * ModelContentPage::getProperty()
     * 
     * Obtener una propiedad del producto
     * 
     * @param int $id product_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_property 
        WHERE `product_id` = '" . (int) $id . "' 
        AND `group` = '" . $this->db->escape($group) . "'
        AND `key` = '" . $this->db->escape($key) . "'");

        return unserialize(str_replace("\'", "'", $query->row['value']));
    }

    /**
     * ModelContentPage::setProperty()
     * 
     * Asigna una propiedad del producto
     * 
     * @param int $id product_id
     * @param varchar $group
     * @param varchar $key
     * @param mixed $value
     * @return void
     * */
    public function setProperty($id, $group, $key, $value) {
        $this->deleteProperty($id, $group, $key);
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_property SET
        `product_id`   = '" . (int) $id . "',
        `group`     = '" . $this->db->escape($group) . "',
        `key`       = '" . $this->db->escape($key) . "',
        `value`     = '" . $this->db->escape(str_replace("'", "\'", serialize($value))) . "'");
    }

    /**
     * ModelContentPage::deleteProperty()
     * 
     * Elimina una propiedad del producto
     * 
     * @param int $id product_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
    public function deleteProperty($id, $group, $key) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_property 
        WHERE `product_id` = '" . (int) $id . "' 
        AND `group` = '" . $this->db->escape($group) . "'
        AND `key` = '" . $this->db->escape($key) . "'");
    }

    /**
     * ModelContentPage::getAllProperties()
     * 
     * Obtiene todas las propiedades del producto
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($product_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($product_id, 'NombreDelGrupo');
     * 
     * @param int $id product_id
     * @param varchar $group
     * @return array all properties
     * */
    public function getAllProperties($id, $group = '*') {
        if ($group == '*') {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_property 
            WHERE `product_id` = '" . (int) $id . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_property 
            WHERE `product_id` = '" . (int) $id . "' 
            AND `group` = '" . $this->db->escape($group) . "'");
        }

        return $query->rows;
    }

    /**
     * ModelContentPage::setAllProperties()
     * 
     * Asigna todas las propiedades de la pagina
     * 
     * Pase un array con todas las propiedades y sus valores
     * eneplo:
     * 
     * $data = array(
     *    'key1'=>'abc',
     *    'key2'=>123,
     *    'key3'=>array(
     *       'subkey1'=>'value1'
     *    ),
     *    'key4'=>$object,
     * );
     * 
     * @param int $id post_id
     * @param varchar $group
     * @param array $data
     * @return void
     * */
    public function setAllProperties($id, $group, $data) {
        if (is_array($data) && !empty($data)) {
            $this->deleteAllProperties($id, $group);
            foreach ($data as $key => $value) {
                $this->setProperty($id, $group, $key, $value);
            }
        }
    }

    /**
     * ModelContentPage::deleteAllProperties()
     * 
     * Elimina todas las propiedades del producto
     * 
     * Si quiere eliminar todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = deleteAllProperties($product_id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = deleteAllProperties($product_id, 'NombreDelGrupo');
     * 
     * @param int $id product_id
     * @param varchar $group
     * @param varchar $key
     * @return void
     * */
    public function deleteAllProperties($id, $group = '*') {
        if ($group == '*') {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_property 
            WHERE `product_id` = '" . (int) $id . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_property 
            WHERE `product_id` = '" . (int) $id . "' 
            AND `group` = '" . $this->db->escape($group) . "'");
        }
    }

}
