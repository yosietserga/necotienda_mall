<?php

class ControllerApiMeli extends Controller
{

    protected $meli;
    protected $handler;
    protected $oauth_url;

    private function initialize()
    {
        if (!$this->config->get('social_meli_app_id') || !$this->config->get('social_meli_app_secret')) {
            return false;
        } else {
            $this->load->auto('meli/meli');
            $this->meli = new Meli($this->config->get('social_meli_app_id'), $this->config->get('social_meli_app_secret'));
        }
    }

    public function fetch($uri, $data=null) {
        $url = 'https://api.mercadolibre.com';

        $response = $this->handler->fetch($url . $uri . '?access_token=' . $_SESSION['mltoken'], $data);
        $response['body'] = (object)Json::decode($response['body']);
        return $response;
    }

    public function index()
    {
        
        $Url = new Url($this->registry);
        if (!$this->initialize()) {
            $this->load->library('json');
            $this->load->library('xhttp/xhttp');
            $this->handler = new xhttp;

            if ($this->request->hasQuery('redirect')) {
                $_SESSION['mlaction'] = $this->request->getQuery('redirect');
            }
            
            $redirect_uri = HTTP_HOME . 'api/meli';
            
            if (strpos($redirect_uri, 'http') === false) {
                
                if (strpos($redirect_uri, 'www.') === false) {
                    $redirect_uri = 'www.' . $redirect_uri;
                }
                
                $redirect_uri = 'http://' . $redirect_uri;
                
            } elseif (strpos($redirect_uri, 'www.') === false) {
                $protocol = substr($redirect_uri, 0, 7);
                $url = substr($redirect_uri, 7);
                $redirect_uri = $protocol . 'www.' . $url;
                
            }
            $redirect_uri = str_replace('/web', '', $redirect_uri);
            
            $this->oauth_url = 'http://auth.mercadolivre.com.br/authorization?client_id=' .
                $this->config->get('social_meli_app_id') .
                '&scope=&response_type=code&redirect_uri=' . urlencode($redirect_uri);

            $this->oauth_url = $this->meli->getAuthUrl($redirect_uri);

            if ($this->request->hasQuery('code') && !isset($_SESSION['mlcode'])) {
                $response = $this->authorize($this->request->getQuery('code'), $redirect_uri);

                $response['body'] = (object)Json::decode($response['body']);

                $_SESSION['mlcode'] = $this->request->getQuery('code');
                $_SESSION['mltoken'] = $response['body']->access_token;
                $_SESSION['mlexpire'] = time() + $response['body']->expires_in;
                $_SESSION['mlrefresh_token'] = $response['body']->refresh_token;

                unset($_GET['code']);

                if ($_SESSION['mltoken']) {
                    $this->redirect($Url::createUrl("api/meli"));
                }

            }
            
            if (!isset($_SESSION['mlcode'])) {
                unset($_SESSION['mltoken']);
                unset($_SESSION['mlexpire']);
                unset($_SESSION['mlrefresh_token']);
                unset($_SESSION['mlcode']);
                $this->redirect($this->oauth_url);
            }
            
            if (!isset($_SESSION['mlexpire']) || (isset($_SESSION['mlexpire']) && $_SESSION['mlexpire'] <
                time())) {
                try {
                    // Make the refresh proccess
                    $refresh = $this->meli->refreshAccessToken();
                    
                    // Now we create the sessions with the new parameters
                    $_SESSION['mltoken'] = $refresh['body']->access_token;
                    $_SESSION['mlexpire'] = time() + $refresh['body']->expires_in;
                    $_SESSION['mlrefresh_token'] = $refresh['body']->refresh_token;
                }
                catch (exception $e) {
                    echo "Exception: " . $e->getMessage() . "\n";
                }
            }
            
            if (isset($_SESSION['mltoken'])) {
                
                /*
                $requestData['method'] = 'post';
                $requestData['post'] = 'code=' . $_SESSION['mlcode']
                . '&client_id=' . $this->config->get('social_meli_app_id')
                . '&client_secret=' . $this->config->get('social_meli_app_secret')
                . '&redirect_uri=' . urlencode($redirect_uri)
                . '&grant_type=authorization_code';

                $response = $this->handler->fetch('https://api.mercadolibre.com/oauth/token', $requestData);
                */
                $mlactions = array(
                    'invitefriends',
                    'publish_products',
                    'import_products',
                    'import_categories',
                    'login');
                

                if (isset($_SESSION['mlaction']) && in_array($_SESSION['mlaction'], $mlactions)) {
                    $this->{$_SESSION['mlaction']}();
                } else {
                    
                    unset($_SESSION['mltoken']);
                    unset($_SESSION['mlcode']);
                    unset($_SESSION['mlexpire']);
                    unset($_SESSION['mlrefresh_token']);
                }
            } else {
                
                unset($_SESSION['mltoken']);
                unset($_SESSION['mlcode']);
                unset($_SESSION['mlexpire']);
                unset($_SESSION['mlrefresh_token']);
                //$this->redirect(Url::createUrl("api/meli", array("redirect" => $_SESSION['mlaction'])));
            }
            
            if (isset($_REQUEST['logout'])) {
                unset($_SESSION['mltoken']);
                unset($_SESSION['mlcode']);
                unset($_SESSION['mlexpire']);
                unset($_SESSION['mlrefresh_token']);
                //$this->redirect(Url::createUrl("api/meli", array("redirect" => $_SESSION['mlaction'])));
            }
            
        } else {
            echo '<script>history.back()</script>';
        }
    }

    public function authorize($code, $redirect_uri) {

        $requestData['method'] = 'post';
        $requestData['post'] = 'code=' . $code
            . '&client_id=' . $this->config->get('social_meli_app_id')
            . '&client_secret=' . $this->config->get('social_meli_app_secret')
            . '&redirect_uri=' . urlencode($redirect_uri)
            . '&grant_type=authorization_code';

        return $this->handler->fetch('https://api.mercadolibre.com/oauth/token', $requestData);
    }

    public function login()
    {
        
        if (!$this->customer->isLogged() && (!$this->config->get('social_meli_app_id') ||
            !$this->config->get('social_meli_app_secret'))) {
            $this->redirect(Url::createUrl("account/login", array("error" =>
                    "No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
        }
        
        if ($this->customer->isLogged()) {
            $this->redirect(Url::createUrl("account/account"));
        }

        if (isset($_SESSION['mltoken'])) {
            
            /*
            //TODO: pasar un token temporal para evitar csrf
            if (!isset($_SESSION['state') && $_SESSION['state') != $this->request->getQuery('state')) {
            $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
            }
            */
            $response = $this->fetch('/users/me');
            var_dump($response);
            $data = array(
                'oauth_provider' => 'meli',
                'company' => $response['body']->nickname,
                'firstname' => $response['body']->first_name,
                'lastname' => $response['body']->last_name,
                'email' => $response['body']->email,
                'meli_oauth_id' => $response['body']->id,
                'meli_oauth_token' => $_SESSION['mltoken'],
                'meli_oauth_refresh' => $_SESSION['mlrefresh_token'],
                'meli_oauth_expire' => $_SESSION['mlexpire'],
                'meli_code' => $_SESSION['mlcode']);
            
            $this->load->model('account/customer');
            
            $result = $this->modelCustomer->getCustomerByMeli($data);
            if ($result) {
                
                if ($this->customer->loginWithMeli($data)) {
                    
                    if ($this->session->has('redirect')) {
                        
                        $this->redirect(str_replace('&amp;', '&', $this->session->get('redirect')));
                    } else {
                        
                        $this->redirect(Url::createUrl("common/home"));
                    }
                    
                } else {
                    
                    $this->redirect(Url::createUrl("account/login", array("error" =>
                            "No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
                }
                
            } elseif ($customer = $this->modelCustomer->addCustomerFromMeli($data)) {
                
                if ($this->config->get('marketing_email_send_password_and_welcome')) {
                    
                    $this->load->model('marketing/newsletter');
                    
                    $newsletter = $this->modelNewsletter->getById($this->config->get('marketing_email_send_password_and_welcome'));
                    if ($newsletter) {
                        $message = $this->prepareTemplate(html_entity_decode($newsletter['htmlbody']));
                        $message = str_replace('{%password%}', $customer['password'], $message);
                        $message = str_replace("{%fullname%}", $data['firstname'] .' '. $data['lastname'], $message);
                        $message = str_replace("{%rif%}", '', $message);
                        $message = str_replace("{%company%}", $data['company'], $message);
                        $message = str_replace("{%email%}", $data['email'], $message);
                        $message = str_replace("{%telephone%}", '', $message);
                        $this->load->library('email/mailer');
                        $this->mailer = new Mailer;
                        if ($this->config->get('config_smtp_method') == 'smtp') {
                            $this->mailer->IsSMTP();
                            $this->mailer->Host = $this->config->get('config_smtp_host');
                            $this->mailer->Username = $this->config->get('config_smtp_username');
                            $this->mailer->Password = base64_decode($this->config->get('config_smtp_password'));
                            $this->mailer->Port = $this->config->get('config_smtp_port');
                            $this->mailer->Timeout = $this->config->get('config_smtp_timeout');
                            $this->mailer->SMTPSecure = $this->config->get('config_smtp_ssl');
                            $this->mailer->SMTPAuth = ($this->config->get('config_smtp_auth')) ? true : false;
                        } elseif ($this->config->get('config_smtp_method') == 'sendmail') {
                            $this->mailer->IsSendmail();
                        } else {
                            $this->mailer->IsMail();
                        }
                        
                        $this->mailer->AddAddress($data['email'], $data['firtname'] .' '. $data['lastname']);
                        $this->mailer->IsHTML();
                        $this->mailer->SetFrom($this->config->get('config_email'), $this->config->get('config_name'));
                        $this->mailer->Subject = "Bienvenidos - " . $this->config->get('config_name');
                        $this->mailer->Body = $message;
                        $this->mailer->Send();
                    }
                }
                
                if ($this->customer->loginWithMeli($data)) {
                    
                    $this->redirect(Url::createUrl("account/account"));
                } else {
                    
                    $this->redirect(Url::createUrl("account/login", array("error" =>
                            "No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
                }
            }

        } else {
            $this->redirect($this->oauth_url);
        }
    }

    public function import_products()
    {
        
        if (!$this->customer->isLogged() && ($this->config->get('social_meli_app_id') &&
            $this->config->get('social_meli_app_secret'))) {
            $this->redirect(Url::createUrl("api/meli", array("redirect" => "login")));
        } elseif (!$this->customer->isLogged() || !$this->config->get('social_meli_app_id') ||
        !$this->config->get('social_meli_app_secret')) {
            $this->redirect(Url::createUrl("account/login", array("error" =>
                    "No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
        }
        if (isset($_SESSION['mltoken'])) {
            
            /*
            //TODO: pasar un token temporal para evitar csrf
            if (!isset($_SESSION['state') && $_SESSION['state') != $this->request->getQuery('state')) {
            $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
            }
            */
            
            $seller_id = $this->modelCustomer->getProperty($this->customer->getId(), 'meli', 'meli_oauth_id');

            $response = $this->fetch("/users/$seller_id/items/search");
var_dump($response);
            $this->load->auto('store/product');
            foreach ($response['body']->results as $k => $v) {
                $resp = $this->fetch("/items/{$v}");
                $desc = $this->fetch("/items/{$v}/description");

                if ($resp['body']) {
                    
                    $query = $this->db->query("SELECT * "
                            . "FROM " . DB_PREFIX ."product_property "
                            . "WHERE `key`='meli_product_id' "
                            . "AND `value`='" . serialize($resp['body']->id) . "'");

                    if (!$query->num_rows) {
                        $data = array();
                        $c = 0;
                        foreach ($resp['body']->pictures as $j => $img) {
                            if (!$img->url) continue;
                            $fc = file_get_contents($img->url);
                            $img_folder = 'data/'. date('m-y');
                            if (!is_dir(DIR_IMAGE .$img_folder)) {
                                mkdir(DIR_IMAGE .$img_folder, '0777');
                            }
                            $img_name = 'meli-'. $seller_id .'-'. time().mt_rand(100000,9999999) . (substr($img->url, strrpos($img->url, '.')));
                            $img_file = DIR_IMAGE . $img_folder .'/'. $img_name;
                            $f = fopen($img_file, 'w+');
                            fwrite($f, $fc);
                            fclose($f);

                            $data['Images'][$c]['name'] = $img_folder .'/'. $img_name;
                            $c++;
                        }

                        $data['model'] = $resp['body']->id;
                        $data['quantity'] = $resp['body']->available_quantity;
                        $data['stock_status_id'] = 1;
                        $data['price'] = $resp['body']->price;
                        $data['tax_class_id'] = 1;
                        $data['plan_id'] = 4;
                        $data['qty_days'] = 60;
                        $data['featured'] = 1;
                        $data['homepage'] = 1;
                        $data['name'] = $resp['body']->title;
                        $data['description'] = $desc['body']->text;
                        $data['keayword'] = $this->document->slug($desc['body']->text);

                        $product_id = $this->modelProduct->add($data);

                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_product_id', $resp->id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_site_id', $resp->site_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_category_id', $resp->category_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_official_store_id', $resp->official_store_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_currency_id', $resp->currency_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_buying_mode', $resp->buying_mode);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_thumbnail', $resp->thumbnail);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_listing_type_id', $resp->listing_type_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_start_time', $resp->start_time);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_end_time', $resp->end_time);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_condition', $resp->condition);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_address_line', $resp->seller_address->address_line);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_zone', $resp->seller_address->state->name);
                    }
                }
            }

            if (!$this->modelCustomer->getProperty($this->customer->getId(), 'rewards', 'meli_products_imported') && $this->customer->isLogged()) {
                $this->modelCustomer->setProperty($this->customer->getId(), 'rewards', 'meli_products_imported', 1);
                $this->modelCustomer->addNecoexp($this->customer->getId(), 15);
                $this->modelCustomer->addNecopoints($this->customer->getId(), 3);
            }
            
        } else {
            $this->redirect($this->oauth_url);
        }
    }

    public function publish_products() {
        if (isset($_SESSION['mltoken'])) {
            //$seller_id = $this->modelCustomer->getProperty($this->customer->getId(), 'meli', 'meli_oauth_id');
            $requestData['method'] = 'post';
            $requestData['headers']['Content-Type'] = 'application/json';

            $requestData['post']['title'] = 'Item de test - No Ofertar';
            $requestData['post']['category_id'] = 'MLV5732';
            $requestData['post']['price'] = '123564';
            $requestData['post']['currency_id'] = 'VEF';
            $requestData['post']['available_quantity'] = '1';
            $requestData['post']['buying_mode'] = 'buy_it_now';
            $requestData['post']['listing_type_id'] = 'bronze';
            $requestData['post']['condition'] = 'new';
            $requestData['post']['description'] = 'TEST: creating ';
            $requestData['post']['video_id'] = 'YOUTUBE_ID_HERE';
            $requestData['post']['warranty'] = '';
            $requestData['post']['pictures'] = array(
                "source"=>"http://upload.wikimedia.org/wikipedia/commons/f/fd/Ray_Ban_Original_Wayfarer.jpg",
            );

            $response = $this->fetch("/items/validate", $requestData);
            var_dump($response);
        } else {
            $this->redirect($this->oauth_url);
        }
    }

    public function import_categories()
    {
        /*
        if (!$this->customer->isLogged() && ($this->config->get('social_meli_app_id') &&
            $this->config->get('social_meli_app_secret'))) {
            $this->redirect(Url::createUrl("api/meli", array("redirect" => "login")));
        } elseif (!$this->customer->isLogged() || !$this->config->get('social_meli_app_id') ||
        !$this->config->get('social_meli_app_secret')) {
            $this->redirect(Url::createUrl("account/login", array("error" =>
                    "No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
        }*/
        if (isset($_SESSION['mltoken'])) {
            
            /*
            //TODO: pasar un token temporal para evitar csrf
            if (!isset($_SESSION['state') && $_SESSION['state') != $this->request->getQuery('state')) {
            $this->redirect(Url::createUrl("account/login",array("error"=>"No se pudo iniciar sesion utilizando Meli, por favor intente con otro servicio")));
            }
            */

            $response = $this->fetch("/sites/MLV/categories");
            
            $this->load->auto('store/category');
            foreach ($response['body'] as $k => $v) {
                $resp = $this->fetch("/categories/{$v->id}");
                $this->crawcategories($resp['body']->id);
            }            
        } else {
            $this->redirect($this->oauth_url);
        }
    }
    
    private function crawcategories($id, $parent = null) {
        $this->load->auto('store/category');
        $resp = $this->fetch("/categories/$id");

        if ($resp['body']) {
            $query = $this->db->query("SELECT * "
                    . "FROM " . DB_PREFIX ."category_property "
                    . "WHERE `key`='meli_category_id' "
                    . "AND `value`='" . serialize($resp['body']->id) . "'");
            $query2 = $this->db->query("SELECT parent_id "
                    . "FROM " . DB_PREFIX ."category c "
                    . "LEFT JOIN " . DB_PREFIX ."category_property cp ON (c.category_id=cp.category_id) "
                    . "WHERE `parent_id`='0' "
                    . "AND `key`='meli_category_id' "
                    . "AND `value`='" . serialize($resp['body']->id) . "'");

            if (!$query2->num_rows) {
                if (!$query->num_rows) {
                    $data = array();

                    $data['name'] = $resp['body']->name;
                    if ($parent) $data['parent_id'] = $parent;

                    $category_id = $this->modelCategory->add($data);
                    $this->modelCategory->setProperty($category_id, 'meli', 'meli_category_id', $resp['body']->id);

                    foreach ($resp['body']->children_categories as $j => $child) {
                        $this->crawcategories($child->id, $category_id);
                    }
                }
            }
        }
    }

    public function mlcrawler() {
        
            $this->load->auto('meli/meli');
            $this->meli = new Meli($this->config->get('social_meli_app_id'), $this->config->get('social_meli_app_secret'));
        /**
         * 1. recorrer cada uno de los sitios de ml
         *  /sites
         * */
        $resp = $this->fetch("/sites");
        foreach ($resp['body'] as $k => $v) {
            if ($v->id !== 'MLV') continue;
            /**
             * 2. grabar todas las categorias y subcategorias de cada sitio
             **/
            $res = $this->fetch("/sites/{$v->id}/categories");
            $data[$v->name]['id'] = $v->id;
            $data[$v->name]['name'] = $v->name;
            $data[$v->name]['categories'] = $res['body'];
            foreach ($res['body'] as $cats) {
                $re = $this->fetch("/categories/{$cats->id}");
                $data[$v->name]['categories'][$cats->id]['childrens'] = $re['body']->children_categories;
                
                /**
                 * 3. obtener los top 100 productos de cada subcategoria
                 **/
                $r = $this->fetch("/sites/{$v->id}/hot_items/search?limit=100&category={$cats->id}");
                foreach ($r['body']->results as $product) {
                    if ($product->sold_quantity < 10 && $product->price < 20000 && $v->id === 'MLV') continue;
                    $resp = $this->fetch("/items/{$product->id}");
                    $desc = $this->fetch("/items/{$product->id}/description");

                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX ."product_property WHERE `key`='meli_product_id' AND `value`='" . serialize($product->id) . "'");
                    if (!$query->num_rows) {
                        $product_data[$k] = array(
                            'price' => $resp->price,
                            'quantity' => 1,
                            'minimum' => 1,
                            'subtract' => 0,
                            'stock_status_id' => 1,
                            'date_available' => date('Y-m-d'),
                            'weight' => '0.00',
                            'width' => '0.00',
                            'height' => '0.00',
                            'length' => '0.00',
                            'status' => 1,
                            'weight_class_id' => 1,
                            'length_class_id' => 1,
                            'tax_class_id' => 1
                        );
                        
                        $resp->seller_address->address_line;
                        $resp->seller_address->state->name;
    
                        if (!is_dir(DIR_IMAGE . 'data/meli')) {
                            mkdir(DIR_IMAGE . 'data/meli', '0777');
                        }
                        
                        $fimg = file_get_contents($resp->thumbnail);
                        $product_data[$k]['image']='data/meli/'.str_replace('/','',strtolower(substr($resp->thumbnail, strrpos($resp->thumbnail,'/')))).'.jpg';
                        $f = fopen(DIR_IMAGE . $product_data[$k]['image'], '0755');
                        fputs($f, $fimg);
                        fclose($f);
                        
                        foreach ($resp->pictures as $img) {
                            $fimg = file_get_contents($img->url);
                            $f = fopen(DIR_IMAGE . 'data/meli/' . strtolower($img->id) . '.jpg', '0755');
                            fputs($f, $fimg);
                            fclose($f);
                            if (file_exists(DIR_IMAGE . 'data/meli/' . strtolower($img->id) . '.jpg')) {
                                $product_data[$k]['product_image'][] = 'data/meli/' . strtolower($img->id) . '.jpg';
                            }
                        }
                        $product_data[$k]['product_description'][1]['name'] = $resp->title;
                        $product_data[$k]['product_description'][1]['description'] = $desc['body']->text;
                        
                        $str = $resp->title .'-'. mt_rand(1000,99999);
                        if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                            $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
                        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
                        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
                        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
                        $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
                        $str = strtolower(trim($str, '-'));
                        $product_data[$k]['product_description'][1]['keyword'] = $str;
                        
                        $product_data[$k]['stores'][0] = 0;
                        //TODO: add category and pass category id or just get the id from an existent category
                        //$product_data[$k]['product_category'][0] = 0;
                        
                        $product_id = $this->modelProduct->add($product_data);
                        
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_product_id', $resp->id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_site_id', $resp->site_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_category_id', $resp->category_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_official_store_id', $resp->official_store_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_currency_id', $resp->currency_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_buying_mode', $resp->buying_mode);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_thumbnail', $resp->thumbnail);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_listing_type_id', $resp->listing_type_id);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_start_time', $resp->start_time);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_end_time', $resp->end_time);
                        $this->modelProduct->setProperty($product_id, 'meli', 'meli_condition', $resp->condition);                        
                    }

                }
                
            }
            /**
             * 4. obtener los datos del perfil del vendedor de cada producto, si es lider continua
             * 5. listar todos los productos del vendedor que tengan ventas
             *      - si el producto tiene menos de 5 dias y no tiene ventas, ignorar
             *      - si tiene al menos 5 dias y 1 venta, agregar
             *      - si tiene 5 o mas ventas, agregar
             * 6. intentar auto detectar datos de contactos desde amarillasinternet y pac.com.ve
             * 7. enviar email de invitacion a que active su perfil en necotienda, en el mensaje
             *      - dirigirlo a un wizard para que agregue y confirme sus datos de contacto y apruebe la app de ml
             *      - cree y active perfil usando los plugins de google, live, facebook
             * 8. dirigirlo al tutorial rapido y tour por las funciones del sitio
             * 9. explicar el sistema de puntajes y creditos
             * */

            /**
             * 
             * 
             * /users/{seller_id}
             * {
             * "id": "100763",
             * "nickname": "NICO3",
             * "registration_date": "1999-08-16T00:00:00.000-04:00",
             * "country_id": "AR",
             * "user_type": "normal",
             * "logo": null,
             * "points": "36840",
             * "site_id": "MLA",
             * "permalink": "http://perfil.mercadolibre.com.ar/NICO3",
             * "seller_reputation": {
             * "level_id": "5_green",
             * "power_seller_status": "platinum",
             * "transactions": {
             * "period": "3 months",
             * "total": "993",
             * "completed": "992",
             * "canceled": "1",
             * "ratings": {
             * "positive": "0.99",
             * "negative": "0.01",
             * "neutral": "0",
             * },
             * },
             * },
             * "status": {
             * "site_status": "active",
             * },
             * }



             *
             * /sites/{site_id}/search?seller_id={seller_id}

             * {
             * "site_id": "MLB",
             * "seller": - {
             * "id": "123456789",
             * "seller_reputation": - {
             * "power_seller_status": null,
             * },
             * "real_estate_agency": false,
             * "car_dealer": true,
             * },
             * "paging": - {
             * "total": 30,
             * "offset": 0,
             * "limit": 50,
             * },
             * "results": - [
             * - {
             * "id": "MLB587594032",
             * "site_id": "MLB",
             * "title": "Volkswagen Jetta 2.0 Tsi Highline 200cv Gasolina 4p Tiptroni",
             * "subtitle": null,
             * "seller": - {
             * "id": "123456789",
             * "power_seller_status": null,
             * "car_dealer": true,
             * "real_estate_agency": false,
             * },
             * "price": 76990,
             * "currency_id": "BRL",
             * "available_quantity": 1,
             * "sold_quantity": 0,
             * "buying_mode": "classified",
             * "listing_type_id": "gold_premium",
             * "stop_time": "2014-09-26T05:20:48.000Z",
             * "condition": "used",
             * "permalink": "http://carro.mercadolivre.com.br/MLB-587594032-volkswagen-jetta-20-tsi-highline-200cv-gasolina-4p-tiptroni-_JM",
             * "thumbnail": "http://mlb-s2-p.mlstatic.com/18767-MLB20160773277_092014-I.jpg",
             * "accepts_mercadopago": false,
             * "installments": null,
             * "address": - {
             * "state_id": "TUxCUFJJT08xODM5Zg",
             * "state_name": "Rio De Janeiro",
             * "city_id": "TUxCQ1JKLTUzMDE",
             * "city_name": "Capital Zona Norte",
             * "area_code": "21",
             * "phone1": "22785483",
             * },
             * "shipping": - {
             * "free_shipping": false,
             * "mode": "not_specified",
             * },
             * "seller_address": - {
             * "id": 68805962,
             * "comment": "Loja 23 VILA ISABEL",
             * "address_line": "Maxwell 300",
             * "zip_code": "20541100",
             * "country": - {
             * "id": "BR",
             * "name": "Brasil",
             * },
             * "state": - {
             * "id": "BR-RJ",
             * "name": "Rio de Janeiro",
             * },
             * "city": - {
             * "id": "BR-RJ-01",
             * "name": "Rio de Janeiro",
             * },
             * "latitude": "",
             * "longitude": "",
             * },
             * "seller_contact": - {
             * "contact": "MAICAR VEICULOS",
             * "other_info": "",
             * "area_code": "21",
             * "phone": "22785483",
             * "area_code2": "",
             * "phone2": "",
             * "email": "",
             * "webpage": "",
             * },
             * "location": - {
             * "address_line": "",
             * "zip_code": "",
             * "subneighborhood": null,
             * "neighborhood": - {
             * "id": "TUxCQlZJTDE4Nzg",
             * "name": "Vila Isabel",
             * },
             * "city": - {
             * "id": "TUxCQ1JKLTUzMDE",
             * "name": "Capital Zona Norte",
             * },
             * "state": - {
             * "id": "TUxCUFJJT08xODM5Zg",
             * "name": "Rio De Janeiro",
             * },
             * "country": - {
             * "id": "BR",
             * "name": "Brasil",
             * },
             * "latitude": "",
             * "longitude": "",
             * },
             * "attributes": - [
             * - {
             * "attribute_group_id": "ADICIONALES",
             * "id": "MLB1744-TRANS",
             * "name": "Transmiss�o",
             * "value_name": "",
             * "value_id": "",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-KMTS",
             * "name": "Kil�metros",
             * "value_name": "42000",
             * "value_id": "",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-MARC",
             * "name": "Marca",
             * "value_name": "Volkswagen",
             * "value_id": "MLB1744-MARC-VOLKSWAGEN",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-MODL",
             * "name": "Modelo",
             * "value_name": "Jetta",
             * "value_id": "MLB1744-MODL-JETTA",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-YEAR",
             * "name": "Ano",
             * "value_name": "2013",
             * "value_id": "MLB1744-YEAR-2013",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB27478-VERS",
             * "name": "Vers�o",
             * "value_name": "",
             * "value_id": "",
             * "attribute_group_name": "Ficha T�cnica",
             * },
             * ],
             * "original_price": null,
             * "category_id": "MLB27478",
             * },
             * - {
             * "id": "MLB584283309",
             * "site_id": "MLB",
             * "title": "Fiat Uno 1.0 Evo Vivace 8v Flex 2p Manual 2013/2014",
             * "subtitle": null,
             * "seller": - {
             * "id": "123456789",
             * "power_seller_status": null,
             * "car_dealer": true,
             * "real_estate_agency": false,
             * },
             * "price": 27990,
             * "currency_id": "BRL",
             * "available_quantity": 1,
             * "sold_quantity": 0,
             * "buying_mode": "classified",
             * "listing_type_id": "gold_premium",
             * "stop_time": "2014-09-26T05:20:48.000Z",
             * "condition": "used",
             * "permalink": "http://carro.mercadolivre.com.br/MLB-584283309-fiat-uno-10-evo-vivace-8v-flex-2p-manual-20132014-_JM",
             * "thumbnail": "http://mlb-s2-p.mlstatic.com/18316-MLB20153206954_082014-I.jpg",
             * "accepts_mercadopago": false,
             * "installments": null,
             * "address": - {
             * "state_id": "TUxCUFJJT08xODM5Zg",
             * "state_name": "Rio De Janeiro",
             * "city_id": "TUxCQ1JKLTUzMDE",
             * "city_name": "Capital Zona Norte",
             * "area_code": "21",
             * "phone1": "22785483",
             * },
             * "shipping": - {
             * "free_shipping": false,
             * "mode": "not_specified",
             * },
             * "seller_address": - {
             * "id": 68805962,
             * "comment": "Loja 23 VILA ISABEL",
             * "address_line": "Maxwell 300",
             * "zip_code": "20541100",
             * "country": - {
             * "id": "BR",
             * "name": "Brasil",
             * },
             * "state": - {
             * "id": "BR-RJ",
             * "name": "Rio de Janeiro",
             * },
             * "city": - {
             * "id": "BR-RJ-01",
             * "name": "Rio de Janeiro",
             * },
             * "latitude": "",
             * "longitude": "",
             * },
             * "seller_contact": - {
             * "contact": "MAICAR VEICULOS",
             * "other_info": "",
             * "area_code": "21",
             * "phone": "22785483",
             * "area_code2": "",
             * "phone2": "",
             * "email": "",
             * "webpage": "",
             * },
             * "location": - {
             * "address_line": "",
             * "zip_code": "",
             * "subneighborhood": null,
             * "neighborhood": - {
             * "id": "TUxCQlZJTDE4Nzg",
             * "name": "Vila Isabel",
             * },
             * "city": - {
             * "id": "TUxCQ1JKLTUzMDE",
             * "name": "Capital Zona Norte",
             * },
             * "state": - {
             * "id": "TUxCUFJJT08xODM5Zg",
             * "name": "Rio De Janeiro",
             * },
             * "country": - {
             * "id": "BR",
             * "name": "Brasil",
             * },
             * "latitude": "",
             * "longitude": "",
             * },
             * "attributes": - [
             * - {
             * "attribute_group_id": "ADICIONALES",
             * "id": "MLB1744-TRANS",
             * "name": "Transmiss�o",
             * "value_name": "",
             * "value_id": "",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-KMTS",
             * "name": "Kil�metros",
             * "value_name": "14500",
             * "value_id": "",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-MARC",
             * "name": "Marca",
             * "value_name": "Fiat",
             * "value_id": "MLB1744-MARC-FIAT",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-MODL",
             * "name": "Modelo",
             * "value_name": "Uno",
             * "value_id": "MLB1744-MODL-UNO",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-YEAR",
             * "name": "Ano",
             * "value_name": "2014",
             * "value_id": "MLB1744-YEAR-2014",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB24322-VERS",
             * "name": "Vers�o",
             * "value_name": "",
             * "value_id": "",
             * "attribute_group_name": "Ficha T�cnica",
             * },
             * ],
             * "original_price": null,
             * "category_id": "MLB24322",
             * },
             * - {
             * "id": "MLB586465256",
             * "site_id": "MLB",
             * "title": "Nissan March 1.6 Sv 16v Flex 4p Manual 2012/2013",
             * "subtitle": null,
             * "seller": - {
             * "id": "123456789",
             * "power_seller_status": null,
             * "car_dealer": true,
             * "real_estate_agency": false,
             * },
             * "price": 30990,
             * "currency_id": "BRL",
             * "available_quantity": 1,
             * "sold_quantity": 0,
             * "buying_mode": "classified",
             * "listing_type_id": "gold_premium",
             * "stop_time": "2014-09-26T05:20:48.000Z",
             * "condition": "used",
             * "permalink": "http://carro.mercadolivre.com.br/MLB-586465256-nissan-march-16-sv-16v-flex-4p-manual-20122013-_JM",
             * "thumbnail": "http://mlb-s1-p.mlstatic.com/18642-MLB20158277401_092014-I.jpg",
             * "accepts_mercadopago": false,
             * "installments": null,
             * "address": - {
             * "state_id": "TUxCUFJJT08xODM5Zg",
             * "state_name": "Rio De Janeiro",
             * "city_id": "TUxCQ1JKLTUzMDE",
             * "city_name": "Capital Zona Norte",
             * "area_code": "21",
             * "phone1": "22785483",
             * },
             * "shipping": - {
             * "free_shipping": false,
             * "mode": "not_specified",
             * },
             * "seller_address": - {
             * "id": 68805962,
             * "comment": "Loja 23 VILA ISABEL",
             * "address_line": "Maxwell 300",
             * "zip_code": "20541100",
             * "country": - {
             * "id": "BR",
             * "name": "Brasil",
             * },
             * "state": - {
             * "id": "BR-RJ",
             * "name": "Rio de Janeiro",
             * },
             * "city": - {
             * "id": "BR-RJ-01",
             * "name": "Rio de Janeiro",
             * },
             * "latitude": "",
             * "longitude": "",
             * },
             * "seller_contact": - {
             * "contact": "MAICAR VEICULOS",
             * "other_info": "",
             * "area_code": "21",
             * "phone": "22785483",
             * "area_code2": "",
             * "phone2": "",
             * "email": "",
             * "webpage": "",
             * },
             * "location": - {
             * "address_line": "",
             * "zip_code": "",
             * "subneighborhood": null,
             * "neighborhood": - {
             * "id": "TUxCQlZJTDE4Nzg",
             * "name": "Vila Isabel",
             * },
             * "city": - {
             * "id": "TUxCQ1JKLTUzMDE",
             * "name": "Capital Zona Norte",
             * },
             * "state": - {
             * "id": "TUxCUFJJT08xODM5Zg",
             * "name": "Rio De Janeiro",
             * },
             * "country": - {
             * "id": "BR",
             * "name": "Brasil",
             * },
             * "latitude": "",
             * "longitude": "",
             * },
             * "attributes": - [
             * - {
             * "attribute_group_id": "ADICIONALES",
             * "id": "MLB1744-TRANS",
             * "name": "Transmiss�o",
             * "value_name": "",
             * "value_id": "",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB100176-VERS",
             * "name": "Vers�o",
             * "value_name": "",
             * "value_id": "",
             * "attribute_group_name": "Ficha T�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-KMTS",
             * "name": "Kil�metros",
             * "value_name": "19000",
             * "value_id": "",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-MARC",
             * "name": "Marca",
             * "value_name": "Nissan",
             * "value_id": "MLB1744-MARC-NISSAN",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-MODL",
             * "name": "Modelo",
             * "value_name": "March",
             * "value_id": "MLB1744-MODL-MARCH",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * - {
             * "attribute_group_id": "FIND",
             * "id": "MLB1744-YEAR",
             * "name": "Ano",
             * "value_name": "2013",
             * "value_id": "MLB1744-YEAR-2013",
             * "attribute_group_name": "Ficha t�cnica",
             * },
             * ],
             * "original_price": null,
             * "category_id": "MLB100176",
             * }
             * */
        }
    }

    private function prepareTemplate($newsletter)
    {
        if (!$newsletter)
            return false;
        $this->load->library('url');
        $this->load->library('BarcodeQR');
        $this->load->library('Barcode39');
        $qr = new BarcodeQR;
        $barcode = new Barcode39(C_CODE);

        $qrStore = "cache/" . $this->escape($this->config->get('config_owner')) . '.png';
        $eanStore = "cache/" . $this->escape($this->config->get('config_owner')) .
            "_barcode_39.gif";

        if (!file_exists(DIR_IMAGE . $qrStore)) {
            $qr->url(HTTP_HOME);
            $qr->draw(150, DIR_IMAGE . $qrStore);
        }

        if (!file_exists(DIR_IMAGE . $eanStore)) {
            $barcode->draw(DIR_IMAGE . $eanStore);
        }

        $newsletter = str_replace("%7B", "{", $newsletter);
        $newsletter = str_replace("%7D", "}", $newsletter);
        $newsletter = str_replace("{%store_logo%}", '<img src="' . HTTP_IMAGE . $this->
            config->get('config_logo') . '" alt="' . $this->config->get('config_name') .
            '" />', $newsletter);
        $newsletter = str_replace("{%store_url%}", HTTP_HOME, $newsletter);
        $newsletter = str_replace("{%url_login%}", Url::createUrl("account/login"), $newsletter);
        $newsletter = str_replace("{%store_owner%}", $this->config->get('config_owner'),
            $newsletter);
        $newsletter = str_replace("{%store_name%}", $this->config->get('config_name'), $newsletter);
        $newsletter = str_replace("{%store_rif%}", $this->config->get('config_rif'), $newsletter);
        $newsletter = str_replace("{%store_email%}", $this->config->get('config_email'),
            $newsletter);
        $newsletter = str_replace("{%store_telephone%}", $this->config->get('config_telephone'),
            $newsletter);
        $newsletter = str_replace("{%store_address%}", $this->config->get('config_address'),
            $newsletter);
        /*
        $newsletter = str_replace("{%products%}",$product_html,$newsletter);
        */
        $newsletter = str_replace("{%date_added%}", date('d-m-Y h:i A'), $newsletter);
        $newsletter = str_replace("{%ip%}", $_SERVER['REMOTE_ADDR'], $newsletter);
        $newsletter = str_replace("{%qr_code_store%}", '<img src="' . HTTP_IMAGE . $qrStore .
            '" alt="QR Code" />', $newsletter);
        $newsletter = str_replace("{%barcode_39_order_id%}", '<img src="' . HTTP_IMAGE .
            $eanStore . '" alt="NT Code" />', $newsletter);

        $newsletter .= "<p style=\"text-align:center\">Powered By Necotienda&reg; " .
            date('Y') . "</p>";

        return html_entity_decode(htmlspecialchars_decode($newsletter));
    }

    public function escape($str)
    {
        if (isset($str)) {
            if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'),
                'UTF-8', 'UTF-32'))
                $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
            $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
            $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i',
                '\1', $str);
            $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
            $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
            $str = strtolower(trim($str, '-'));
            return $str;
        } else {
            return false;
        }
    }

}
