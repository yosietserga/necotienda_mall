<?php

class ControllerCommonSeoUrl extends Controller {

    private $profile;

    public function index() {
        if ($this->request->hasQuery('_route_')) {
            $apis = array(
                'api/live',
                'api/google',
                'api/twitter',
                'api/facebook',
                'api/meli'
            );

            if (in_array($this->request->getQuery('_route_'), $apis)) {
                parse_str($_SERVER['QUERY_STRING'], $params);
                return $this->forward($this->request->getQuery('_route_'), $params);
            }

            if (strpos($this->request->getQuery('_route_'), 'buscar/') !== false) {
                $_GET['q'] = str_replace('buscar/', '', $this->request->getQuery('_route_'));
                return $this->forward('store/search');
            }

            $parts = explode('/', $this->request->getQuery('_route_'));

            if ($this->customer->isLogged()) {
                $str = ($this->customer->getProfile()) ? $this->customer->getProfile() : $this->customer->getFirstName() . $this->customer->getLastName();
                if (empty($str)) {
                    $str = $this->customer->getCompany();
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

            $this->load->auto('account/customer');
            foreach ($parts as $key => $part) {
                $stores = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE folder = '" . $this->db->escape($part) . "'");
                if ($stores->num_rows) {
                    $this->request->setQuery('_route_', str_replace($part . "/", "", $this->request->getQuery('_route_')));
                    continue;
                }

                $customer = $this->modelCustomer->getCustomerByProfile($part);
                if ($customer['profile']) {
                    $this->profile = $customer['profile'];
                    continue;
                }

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);

                    if ($url[0] == 'product_id') {
                        $this->request->get['product_id'] = $url[1];
                    }

                    if ($url[0] == 'page/sitemap') {
                        $this->request->get['product_id'] = $url[1];
                    }

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }

                    if ($url[0] == 'page_id') {
                        if (!isset($this->request->get['page_id'])) {
                            $this->request->get['page_id'] = $url[1];
                        } else {
                            $this->request->get['page_id'] .= '_' . $url[1];
                        }
                    }

                    if ($url[0] == 'manufacturer_id') {
                        $this->request->get['manufacturer_id'] = $url[1];
                    }

                    if ($url[0] == 'post_id') {
                        $this->request->get['post_id'] = $url[1];
                    }

                    if ($url[0] == 'post_category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }

                    if ($url[0] == 'post_category_id') {
                        $this->request->get['post_category_id'] = $url[1];
                    }
                } else {
                    $this->request->get['r'] = 'error/not_found';
                }
            }

            if (isset($this->request->get['product_id']) && ($this->request->get['_route_'] != 'carrito' && $this->request->get['_route_'] != 'cart')) {
                $this->request->get['r'] = 'store/product';
            } elseif (isset($this->request->get['path'])) {
                $this->request->get['r'] = 'store/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['r'] = 'store/manufacturer';
            } elseif (isset($this->request->get['page_id'])) {
                $this->request->get['r'] = 'content/page';
            } elseif (isset($this->request->get['post_id'])) {
                $this->request->get['r'] = 'content/post';
            } elseif (isset($this->request->get['post_category_id'])) {
                $this->request->get['r'] = 'content/category';
            } elseif ($this->request->get['_route_'] == 'sitemap') {
                $this->request->get['r'] = 'page/sitemap';
            } elseif ($this->request->get['_route_'] == 'contact' || $this->request->get['_route_'] == 'contacto') {
                $this->request->get['r'] = 'page/contact';
            } elseif ($this->request->get['_route_'] == 'special' || $this->request->get['_route_'] == 'ofertas') {
                $this->request->get['r'] = 'store/special';
            } elseif ($this->request->get['_route_'] == 'blog') {
                $this->request->get['r'] = 'content/category';
            } elseif ($this->request->get['_route_'] == 'pages' || $this->request->get['_route_'] == 'paginas') {
                $this->request->get['r'] = 'content/page/all';
            } elseif ($this->request->get['_route_'] == 'productos' || $this->request->get['_route_'] == 'products') {
                $this->request->get['r'] = 'store/product/all';
            } elseif ($this->request->get['_route_'] == 'categorias' || $this->request->get['_route_'] == 'categories') {
                $this->request->get['r'] = 'store/category/all';
            } elseif ($this->request->get['_route_'] == 'buscar' || $this->request->get['_route_'] == 'search') {
                $this->request->get['r'] = 'store/search';
            }
            // Cuenta del Cliente
            elseif ($this->request->get['_route_'] == $this->profile . '/pedidos' || $this->request->get['_route_'] == $this->profile . '/orders') {
                $this->request->get['r'] = 'account/order';
            } elseif ($this->request->get['_route_'] == $this->profile . '/anuncios' || $this->request->get['_route_'] == $this->profile . '/posts') {
                $this->request->get['r'] = 'account/sale';
            } elseif ($this->request->get['_route_'] == $this->profile . '/mensajes' && $this->profile != 'profile') {
                $this->request->get['r'] = 'account/message';
            } elseif ($this->request->get['_route_'] == $this->profile . '/pagos' || $this->request->get['_route_'] == $this->profile . '/payments') {
                $this->request->get['r'] = 'account/payment';
            } elseif ($this->request->get['_route_'] == $this->profile . '/comentarios' || $this->request->get['_route_'] == $this->profile . '/reviews') {
                $this->request->get['r'] = 'account/review';
            }
            // Perfil del Cliente
            elseif ($this->request->get['_route_'] == $this->profile) {
                $this->request->get['r'] = 'profile/profile';
            } elseif ($this->request->get['_route_'] == $this->profile . '/ventas' || $this->request->get['_route_'] == $this->profile . '/sales') {
                $this->request->get['r'] = 'profile/sales';
            } elseif ($this->request->get['_route_'] == $this->profile . '/informacion' || $this->request->get['_route_'] == $this->profile . '/information') {
                $this->request->get['r'] = 'profile/information';
            } elseif ($this->request->get['_route_'] == $this->profile . '/productos' || $this->request->get['_route_'] == $this->profile . '/products') {
                $this->request->get['r'] = 'profile/products';
            } elseif ($this->request->get['_route_'] == $this->profile . '/compras' || $this->request->get['_route_'] == $this->profile . '/buys') {
                $this->request->get['r'] = 'profile/buys';
            } elseif ($this->request->get['_route_'] == $this->profile . '/tienda' || $this->request->get['_route_'] == $this->profile . '/store') {
                $this->request->get['r'] = 'profile/store';
            }
            // Carrito y Publicacion
            elseif ($this->request->get['_route_'] == 'carrito' || $this->request->get['_route_'] == 'cart') {
                $this->request->get['r'] = 'checkout/cart';
            } elseif ($this->request->get['_route_'] == 'vender' || $this->request->get['_route_'] == 'sale') {
                $this->request->get['r'] = 'sale/create';
            }

            if (isset($this->request->get['r'])) {
                return $this->forward($this->request->get['r']);
            }
        }
    }

}
