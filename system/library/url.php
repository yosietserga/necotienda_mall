<?php

class Url {

    private static $db;
    private static $config;
    private static $customer;

    public function __construct($registry = null) {
        if ($registry) {
            self::$db = $registry->get('db');
            self::$config = $registry->get('config');
            self::$customer = $registry->get('customer');
        }
    }

    static public function createUrl($route, $params = null, $connection = 'NONSSL', $base = null) {
        if (empty($route))
            return false;

        if (isset($base)) {
            $url = $base . "index.php?r=" . $route;
        } else {
            $url = ($connection == 'SSL') ? HTTPS_HOME . "index.php?r=" . $route : HTTP_HOME . "index.php?r=" . $route;
        }

        if (isset($params)) {
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    if (empty($key))
                        continue;
                    $url .= "&" . trim($key) . "=" . trim($value);
                }
            } else {
                $url .= trim("&" . $params);
            }
        }

        // para habilitar el editor de temas en todas las url
        if (isset($_GET['theme_editor'])) {
            $url .= "&theme_editor=1";
        }

        if (isset($_GET['theme_id'])) {
            $url .= "&theme_id=" . urlencode(trim((int) $_GET['theme_id']));
        }

        if (isset($_GET['template'])) {
            $url .= "&template=" . urlencode(trim($_GET['template']));
        }

        if (isset(self::$config) && isset(self::$db)) {
            if (isset(self::$customer)) {
                if (self::$customer->isLogged()) {
                    $str = (self::$customer->getProfile()) ? self::$customer->getProfile() : self::$customer->getFirstName() . self::$customer->getLastName();
                    if (empty($str)) {
                        $str = self::$customer->getCompany();
                    }
                    if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                        $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
                    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
                    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
                    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
                    $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
                    $str = strtolower(trim($str, '-'));
                    $profile = $str;
                } else {
                    $profile = 'profile';
                }
            }

            if (self::$config->get('config_seo_url')) {
                $url_data = parse_url(str_replace('&amp;', '&', $url));
                $url_ = '';
                $data = array();
                parse_str($url_data['query'], $data);
                foreach ($data as $key => $value) {
                    if (($key == 'product_id' && $data['r'] == 'store/product') ||
                            ($key == 'manufacturer_id' && $data['r'] == 'store/manufacturer') ||
                            ($key == 'post_id' && $data['r'] == 'content/post') ||
                            ($key == 'category_id' && $data['r'] == 'content/category')) {
                        
                        $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . self::$db->escape($key . '=' . (int) $value) . "'");
                        if ($query->num_rows) {
                            /*
                            if ($data['r'] == 'store/product') {
                                $url_ .= '/productos';
                            } elseif ($data['r'] == 'store/manufacturer') {
                                $url_ .= '/marcas';
                            } elseif ($data['r'] == 'content/post') {
                                $url_ .= '/post';
                            } elseif ($data['r'] == 'content/category') {
                                $url_ .= '/posts';
                            }
                             * 
                             */
                            $url_ .= '/' . $query->row['keyword'];
                        }
                        
                    } elseif ($key == 'path' && $data['r'] == 'store/category') {
                        
                        $categories = explode('_', $value);
                        foreach ($categories as $category) {
                            $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int) $category . "'");

                            if ($query->num_rows) {
                                //$url_ .= '/categorias';
                                $url_ .= '/' . $query->row['keyword'];
                            }
                        }
                    } elseif ($key == 'path' && $data['r'] == 'content/category') {
                        $categories = explode('_', $value);
                        foreach ($categories as $category) {
                            $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'post_category_id=" . (int) $category . "'");

                            if ($query->num_rows) {
                                $url_ .= '/' . $query->row['keyword'];
                            }
                        }
                    } elseif ($key == 'page_id' && $data['r'] == 'content/page') {
                        $pages = explode('_', $value);
                        foreach ($pages as $page) {
                            $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'page_id=" . (int) $page . "'");

                            if ($query->num_rows) {
                                $url_ .= '/' . $query->row['keyword'];
                            }
                        }
                    } elseif ($key == 'profile_id') {
                        $query = self::$db->query("SELECT profile FROM " . DB_PREFIX . "customer WHERE `profile` = '" . self::$db->escape($value) . "'");
                        if ($query->row['profile']) {
                            if ($data['r'] == 'profile/profile') {
                                $url_ .= '/' . $query->row['profile'];
                            } elseif ($data['r'] == 'profile/information') {
                                $url_ .= '/' . $query->row['profile'] . '/informacion';
                            } elseif ($data['r'] == 'profile/products') {
                                $url_ .= '/' . $query->row['profile'] . '/productos';
                            } elseif ($data['r'] == 'profile/sales') {
                                $url_ .= '/' . $query->row['profile'] . '/ventas';
                            } elseif ($data['r'] == 'profile/buys') {
                                $url_ .= '/' . $query->row['profile'] . '/compras';
                            } elseif ($data['r'] == 'profile/store') {
                                $url_ .= '/' . $query->row['profile'] . '/tienda';
                            }
                        }
                    } elseif ($data['r'] == 'page/sitemap') {
                        $url_ .= '/sitemap';
                    } elseif ($data['r'] == 'page/contact') {
                        $url_ .= '/contacto';
                    } elseif ($data['r'] == 'store/special') {
                        $url_ .= '/ofertas';
                    } elseif ($key != 'category_id' && $data['r'] == 'content/category') {
                        $url_ .= '/blog';
                    } elseif ($key != 'page_id' && $data['r'] == 'content/page/all') {
                        $url_ .= '/paginas';
                    } elseif ($key != 'product_id' && $data['r'] == 'store/product/all') {
                        $url_ .= '/productos';
                    } elseif ($key != 'path' && $data['r'] == 'store/category/all') {
                        $url_ .= '/categorias';
                    } elseif ($data['r'] == 'store/search') {
                        $url_ .= '/buscar';
                    } elseif ($data['r'] == 'account/order') {
                        $url_ .= "/$profile/pedidos";
                    } elseif ($data['r'] == 'account/payment') {
                        $url_ .= "/$profile/pagos";
                    } elseif ($data['r'] == 'account/message') {
                        $url_ .= "/$profile/mensajes";
                    } elseif ($data['r'] == 'account/sale') {
                        $url_ .= "/$profile/anuncios";
                    } elseif ($data['r'] == 'account/review') {
                        $url_ .= "/$profile/comentarios";
                    } elseif ($data['r'] == 'checkout/cart') {
                        $url_ .= '/carrito';
                    } elseif ($data['r'] == 'sale/create') {
                        $url_ .= '/vender';
                    }
                    if ($key != 'r')
                        unset($data[$key]);
                }

                if ($url_ || $data['r'] == 'common/home') {
                    unset($data['r']);
                    $query = '';
                    if ($data) {
                        foreach ($data as $key => $value) {
                            $query .= '&' . $key . '=' . $value;
                        }
                        if ($query) {
                            $query = '?' . trim($query, '&');
                        }
                    }
                    return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url_ . $query;
                } else {
                    return $url;
                }
            }
        }
        return $url;
    }

    static public function rewrite($url) {
        // para habilitar el editor de temas en todas las url
        if (isset($_GET['theme_editor'])) {
            $url .= "&theme_editor=1";
        }

        if (isset($_GET['theme_id'])) {
            $url .= "&theme_id=" . urlencode(trim((int) $_GET['theme_id']));
        }

        if (isset($_GET['template'])) {
            $url .= "&template=" . urlencode(trim($_GET['template']));
        }

        if (isset(self::$config) && isset(self::$db)) {
            if (isset(self::$customer)) {
                if (self::$customer->isLogged()) {
                    $str = self::$customer->getFirstName() . self::$customer->getLastName();
                    if (empty($str)) {
                        $str = self::$customer->getCompany();
                    }
                    if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                        $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
                    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
                    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
                    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
                    $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
                    $str = strtolower(trim($str, '-'));
                    $profile = $str;
                } else {
                    $profile = 'profile';
                }
            }
            if (self::$config->get('config_seo_url')) {
                $url_data = parse_url(str_replace('&amp;', '&', $url));
                $url_ = '';
                $data = array();
                parse_str($url_data['query'], $data);
                foreach ($data as $key => $value) {
                    if (($key == 'product_id' && $data['r'] == 'store/product') ||
                            ($key == 'manufacturer_id' && $data['r'] == 'store/manufacturer') ||
                            ($key == 'post_id' && $data['r'] == 'content/post') ||
                            ($key == 'category_id' && $data['r'] == 'content/category')) {
                        $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . self::$db->escape($key . '=' . (int) $value) . "'");
                        if ($query->num_rows) {
                            $url_ .= '/' . $query->row['keyword'];
                            unset($data[$key]);
                        }
                    } elseif ($key == 'path' && $data['r'] == 'store/category') {
                        $categories = explode('_', $value);
                        foreach ($categories as $category) {
                            $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int) $category . "'");

                            if ($query->num_rows) {
                                $url_ .= '/' . $query->row['keyword'];
                            }
                        }
                        unset($data[$key]);
                    } elseif ($key == 'path' && $data['r'] == 'content/category') {
                        $categories = explode('_', $value);
                        foreach ($categories as $category) {
                            $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'post_category_id=" . (int) $category . "'");

                            if ($query->num_rows) {
                                $url_ .= '/' . $query->row['keyword'];
                            }
                        }
                        unset($data[$key]);
                    } elseif ($key == 'page_id' && $data['r'] == 'content/page') {
                        $pages = explode('_', $value);
                        foreach ($pages as $page) {
                            $query = self::$db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'page_id=" . (int) $page . "'");

                            if ($query->num_rows) {
                                $url_ .= '/' . $query->row['keyword'];
                            }
                        }
                        unset($data[$key]);
                    } elseif ($data['r'] == 'page/sitemap') {
                        $url_ .= '/sitemap';
                        unset($data[$key]);
                    } elseif ($data['r'] == 'page/contact') {
                        $url_ .= '/contacto';
                        unset($data[$key]);
                    } elseif ($data['r'] == 'store/special') {
                        $url_ .= '/ofertas';
                        unset($data[$key]);
                    } elseif ($key != 'category_id' && $data['r'] == 'content/category') {
                        $url_ .= '/blog';
                        unset($data[$key]);
                    } elseif ($key != 'page_id' && $data['r'] == 'content/page/all') {
                        $url_ .= '/paginas';
                        unset($data[$key]);
                    } elseif ($key != 'product_id' && $data['r'] == 'store/product/all') {
                        $url_ .= '/productos';
                        unset($data[$key]);
                    } elseif ($key != 'path' && $data['r'] == 'store/category/all') {
                        $url_ .= '/categorias';
                        unset($data[$key]);
                    } elseif ($data['r'] == 'store/search') {
                        $url_ .= '/buscar';
                        unset($data[$key]);
                    } elseif ($data['r'] == 'account/order') {
                        $url_ .= "/$profile/pedidos";
                        unset($data[$key]);
                    } elseif ($data['r'] == 'account/payment') {
                        $url_ .= "/$profile/pagos";
                        unset($data[$key]);
                    } elseif ($data['r'] == 'account/message') {
                        $url_ .= "/$profile/mensajes";
                        unset($data[$key]);
                    } elseif ($data['r'] == 'account/sale') {
                        $url_ .= "/$profile/anuncios";
                        unset($data[$key]);
                    } elseif ($data['r'] == 'profile/profile') {
                        $url_ .= "/$profile";
                        unset($data[$key]);
                    } elseif ($data['r'] == 'account/review') {
                        $url_ .= "/$profile/comentarios";
                        unset($data[$key]);
                    } elseif ($data['r'] == 'checkout/cart') {
                        $url_ .= '/carrito';
                        unset($data[$key]);
                    } elseif ($data['r'] == 'sale/create') {
                        $url_ .= '/vender';
                        unset($data[$key]);
                    }
                }

                if ($url_) {
                    unset($data['r']);
                    $query = '';
                    if ($data) {
                        foreach ($data as $key => $value) {
                            $query .= '&' . $key . '=' . $value;
                        }
                        if ($query) {
                            $query = '?' . trim($query, '&');
                        }
                    }
                    return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url_ . $query;
                } else {
                    return $url;
                }
            }
        }
        return $url;
    }

    static public function createAdminUrl($route, $params = array(), $connection = 'NONSSL', $base = null) {
        $token = ($_SESSION[C_CODE . '_ukey']) ? $_SESSION[C_CODE . '_ukey'] : $_GET['token'];
        $params = is_array($params) ? array_merge(array('token' => $token), $params) : '&token=' . $token . $params;
        return self::createUrl($route, $params, $connection, $base);
    }

}
