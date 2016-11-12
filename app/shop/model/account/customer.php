<?php

class ModelAccountCustomer extends Model {
    
    public function generateProfileName($keyword) {
            if ($keyword !== mb_convert_encoding(mb_convert_encoding($keyword, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                $keyword = mb_convert_encoding($keyword, 'UTF-8', mb_detect_encoding($keyword));
            $keyword = htmlentities($keyword, ENT_NOQUOTES, 'UTF-8');
            $keyword = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $keyword);
            $keyword = html_entity_decode($keyword, ENT_NOQUOTES, 'UTF-8');
            $keyword = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $keyword);
            $keyword = strtolower(trim($keyword, '-'));

            if ($this->getCustomerByProfile($keyword)) {
                $keyword = md5(base_convert(rand(10e16, 10e20), 10, 36) . time() . $keyword);
            }
            return $keyword;
    }
    
    public function addCustomer($data) {
        if (!$this->getTotalCustomersByEmail($data['email'])) {
            $suffix = md5(base_convert(rand(10e16, 10e20), 10, 36) . time());

            $profile = $this->generateProfileName($data['company']);

            $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET 
              `store_id`    = '" . (int) STORE_ID . "', 
              `firstname`   = '" . $this->db->escape($data['firstname']) . "', 
              `lastname`    = '" . $this->db->escape($data['lastname']) . "', 
              `telephone`   = '" . $this->db->escape($data['telephone']) . "', 
              `email`       = '" . $this->db->escape($data['email']) . "',
              `birthday`    = '" . $this->db->escape($data['birthday']) . "', 
              `rif`         = '" . $this->db->escape($data['rif']) . "', 
              `company`     = '" . $this->db->escape($data['company']) . "', 
              `profile`    = '" . $this->db->escape($profile) . "',
              `password`    = '" . $this->db->escape(md5(md5($data['password']) . $suffix) . ':' . $suffix) . "',
              `activation_code`      = '" . $this->db->escape($data['activation_code']) . "',
              `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "', 
              `can_publish`  = '1', 
              `can_buy`      = '1', 
              `can_ask`      = '1', 
              `banned`      = '0', 
              `status`      = '1', 
              `date_added`  = NOW()");
            $customer_id = $this->db->getLastId();

            $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_to_store` SET 
              `store_id`    = '" . (int) STORE_ID . "', 
              customer_id   = '" . (int) $customer_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
              customer_id   = '" . (int) $customer_id . "', 
              firstname     = '" . $this->db->escape($data['firstname']) . "', 
              lastname      = '" . $this->db->escape($data['lastname']) . "', 
              company       = '" . $this->db->escape($data['company']) . "', 
              address_1     = '" . $this->db->escape($data['address_1']) . "',
              city          = '" . $this->db->escape($data['city']) . "', 
              postcode      = '" . $this->db->escape($data['postcode']) . "', 
              country_id    = '" . (int) $data['country_id'] . "', 
              zone_id       = '" . (int) $data['zone_id'] . "'");

            $address_id = $this->db->getLastId();

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");

            if ($this->config->get('config_customer_approval')) {
                $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `approved` = '1' WHERE `customer_id` = '" . (int) $customer_id . "'");
            }
            return $customer_id;
        }
    }

    public function addCustomerFromGoogle($data) {
        if (!$this->getTotalCustomersByEmail($data['email'])) {
            $suffix = base_convert(rand(10e16, 10e20), 10, 36); //TODO: agregar sufijo a las contrasenas
            $password = substr(md5(mt_rand()), 0, 6);
            $profile = $this->generateProfileName($data['company']);

            $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET 
              `store_id`    = '" . (int) STORE_ID . "', 
              `firstname`   = '" . $this->db->escape($data['firstname']) . "', 
              `lastname`    = '" . $this->db->escape($data['lastname']) . "', 
              `telephone`   = '" . $this->db->escape($data['telephone']) . "', 
              `email`       = '" . $this->db->escape($data['email']) . "',
              `birthday`    = '" . $this->db->escape($data['birthday']) . "', 
              `company`     = '" . $this->db->escape($data['company']) . "', 
              `sex`         = '" . $this->db->escape($data['sex']) . "', 
              `profile`    = '" . $this->db->escape($profile) . "',
              `password`    = '" . $this->db->escape(md5(md5($password) . $suffix) . ':' . $suffix) . "',
              `activation_code` = '" . $this->db->escape(md5($data['email'])) . "',
              `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "', 
              `can_publish`  = '1', 
              `can_buy`      = '1', 
              `can_ask`      = '1', 
              `banned`      = '0', 
              `status`      = '1', 
              `approved`    = '1', 
              `photo`       = '" . $this->db->escape($data['photo']) . "', 
              `google_oauth_id`    = '" . $this->db->escape($data['google_oauth_id']) . "', 
              `google_oauth_token` = '" . $this->db->escape($data['google_oauth_token']) . "', 
              `google_oauth_refresh` = '" . $this->db->escape($data['google_oauth_refresh']) . "', 
              `google_code` = '" . $this->db->escape($data['google_code']) . "', 
              `date_added`  = NOW()");

            $customer_id = $this->db->getLastId();

            if ((int) STORE_ID == 0) {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
            } else {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '0', 
                  customer_id   = '" . (int) $customer_id . "'");
            }
            return array('customer_id' => $customer_id, 'password' => $password);
        }
    }

    public function addCustomerFromMeli($data) {
        if (!$this->getTotalCustomersByEmail($data['email'])) {
            $suffix = base_convert(rand(10e16, 10e20), 10, 36); //TODO: agregar sufijo a las contrasenas
            $password = substr(md5(mt_rand()), 0, 6);
            $profile = $this->generateProfileName($data['company']);
            $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET 
              `store_id`    = '" . (int) STORE_ID . "', 
              `firstname`   = '" . $this->db->escape($data['firstname']) . "', 
              `lastname`    = '" . $this->db->escape($data['lastname']) . "', 
              `telephone`   = '" . $this->db->escape($data['telephone']) . "', 
              `email`       = '" . $this->db->escape($data['email']) . "',
              `birthday`    = '" . $this->db->escape($data['birthday']) . "', 
              `company`     = '" . $this->db->escape($data['company']) . "', 
              `sex`         = '" . $this->db->escape($data['sex']) . "', 
              `profile`    = '" . $this->db->escape($profile) . "',
              `password`    = '" . $this->db->escape(md5(md5($password) . $suffix) . ':' . $suffix) . "',
              `activation_code` = '" . $this->db->escape(md5($data['email'])) . "',
              `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "', 
              `can_publish`  = '1', 
              `can_buy`      = '1', 
              `can_ask`      = '1', 
              `banned`      = '0', 
              `status`      = '1', 
              `approved`    = '1',
              `date_added`  = NOW()");

            $customer_id = $this->db->getLastId();

                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_property SET "
                        . "`customer_id`    = '" . (int)$customer_id . "', "
                        . "`group` = 'meli', "
                        . "`key` = 'meli_oauth_id', "
                        . "`value` = '" . serialize($this->db->escape($data['meli_oauth_id'])) . "'");
                        
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_property SET "
                        . "`customer_id`    = '" . (int)$customer_id . "', "
                        . "`group` = 'meli', "
                        . "`key` = 'meli_oauth_token', "
                        . "`value` = '" . serialize($this->db->escape($data['meli_oauth_token'])) . "'");
                        
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_property SET "
                        . "`customer_id`    = '" . (int)$customer_id . "', "
                        . "`group` = 'meli', "
                        . "`key` = 'meli_oauth_refresh', "
                        . "`value` = '" . serialize($this->db->escape($data['meli_oauth_refresh'])) . "'");
                        
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_property SET "
                        . "`customer_id`    = '" . (int)$customer_id . "', "
                        . "`group` = 'meli', "
                        . "`key` = 'meli_oauth_expire', "
                        . "`value` = '" . serialize($this->db->escape($data['meli_oauth_expire'])) . "'");
                        
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_property SET "
                        . "`customer_id`    = '" . (int)$customer_id . "', "
                        . "`group` = 'meli', "
                        . "`key` = 'meli_code', "
                        . "`value` = '" . serialize($this->db->escape($data['meli_code'])) . "'");
            if ((int) STORE_ID == 0) {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
            } else {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '0', 
                  customer_id   = '" . (int) $customer_id . "'");
            }
            return array('customer_id' => $customer_id, 'password' => $password);
        }
    }

    public function addCustomerFromLive($data) {
        if (!$this->getTotalCustomersByEmail($data['email'])) {
            $suffix = base_convert(rand(10e16, 10e20), 10, 36); //TODO: agregar sufijo a las contrasenas
            $password = substr(md5(mt_rand()), 0, 6);
            $profile = $this->generateProfileName($data['company']);
            $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET 
              `store_id`    = '" . (int) STORE_ID . "', 
              `firstname`   = '" . $this->db->escape($data['firstname']) . "', 
              `lastname`    = '" . $this->db->escape($data['lastname']) . "', 
              `telephone`   = '" . $this->db->escape($data['telephone']) . "', 
              `email`       = '" . $this->db->escape($data['email']) . "',
              `birthday`    = '" . $this->db->escape($data['birthday']) . "', 
              `company`     = '" . $this->db->escape($data['company']) . "', 
              `sex`         = '" . $this->db->escape($data['sex']) . "', 
              `profile`    = '" . $this->db->escape($profile) . "',
              `password`    = '" . $this->db->escape(md5(md5($password) . $suffix) . ':' . $suffix) . "',
              `activation_code` = '" . $this->db->escape(md5($data['email'])) . "',
              `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "', 
              `can_publish`  = '1', 
              `can_buy`      = '1', 
              `can_ask`      = '1', 
              `banned`      = '0', 
              `status`      = '1', 
              `approved`    = '1', 
              `photo`       = '" . $this->db->escape($data['photo']) . "', 
              `live_oauth_id`    = '" . $this->db->escape($data['live_oauth_id']) . "', 
              `live_oauth_token` = '" . $this->db->escape($data['live_oauth_token']) . "',
              `live_code` = '" . $this->db->escape($data['live_code']) . "', 
              `date_added`  = NOW()");

            $customer_id = $this->db->getLastId();

            if ((int) STORE_ID == 0) {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
            } else {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '0', 
                  customer_id   = '" . (int) $customer_id . "'");
            }
            return array('customer_id' => $customer_id, 'password' => $password);
        }
    }

    public function addCustomerFromFacebook($data) {
        if (!$this->getTotalCustomersByEmail($data['email'])) {
            $suffix = base_convert(rand(10e16, 10e20), 10, 36); //TODO: agregar sufijo a las contrasenas
            $password = substr(md5(mt_rand()), 0, 6);
            $profile = $this->generateProfileName($data['company']);
            $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET 
              `store_id`    = '" . (int) STORE_ID . "', 
              `firstname`   = '" . $this->db->escape($data['firstname']) . "', 
              `lastname`    = '" . $this->db->escape($data['lastname']) . "', 
              `telephone`   = '" . $this->db->escape($data['telephone']) . "', 
              `email`       = '" . $this->db->escape($data['email']) . "',
              `birthday`    = '" . $this->db->escape($data['birthday']) . "', 
              `company`     = '" . $this->db->escape($data['company']) . "', 
              `sex`         = '" . $this->db->escape($data['sex']) . "', 
              `profile`    = '" . $this->db->escape($profile) . "',
              `password`    = '" . $this->db->escape(md5(md5($password) . $suffix) . ':' . $suffix) . "',
              `activation_code` = '" . $this->db->escape(md5($data['email'])) . "',
              `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "', 
              `can_publish`  = '1', 
              `can_buy`      = '1', 
              `can_ask`      = '1', 
              `banned`      = '0', 
              `status`      = '1', 
              `approved`    = '1', 
              `photo`       = '" . $this->db->escape($data['photo']) . "', 
              `facebook_oauth_id`    = '" . $this->db->escape($data['facebook_oauth_id']) . "', 
              `facebook_oauth_token` = '" . $this->db->escape($data['facebook_oauth_token']) . "',
              `facebook_code` = '" . $this->db->escape($data['facebook_code']) . "', 
              `date_added`  = NOW()");

            $customer_id = $this->db->getLastId();

            if ((int) STORE_ID == 0) {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
            } else {
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '" . (int) STORE_ID . "', 
                  customer_id   = '" . (int) $customer_id . "'");
                $result = $this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_to_store` SET 
                  `store_id`    = '0', 
                  customer_id   = '" . (int) $customer_id . "'");
            }
            return array('customer_id' => $customer_id, 'password' => $password);
        }
    }

    public function editCustomer($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        firstname = '" . $this->db->escape($data['firstname']) . "', 
        lastname = '" . $this->db->escape($data['lastname']) . "', 
        `birthday`    = '" . $this->db->escape($data['birthday']) . "', 
        telephone = '" . $this->db->escape($data['telephone']) . "', 
        fax = '" . $this->db->escape($data['fax']) . "', 
        sexo = '" . $this->db->escape($data['sexo']) . "', 
        blog = '" . $this->db->escape($data['blog']) . "', 
        website = '" . $this->db->escape($data['website']) . "', 
        profesion = '" . $this->db->escape($data['profesion']) . "', 
        titulo = '" . $this->db->escape($data['titulo']) . "', 
        msn = '" . $this->db->escape($data['msn']) . "', 
        gmail = '" . $this->db->escape($data['gmail']) . "', 
        yahoo = '" . $this->db->escape($data['yahoo']) . "', 
        skype = '" . $this->db->escape($data['skype']) . "', 
        facebook = '" . $this->db->escape($data['facebook']) . "', 
        twitter = '" . $this->db->escape($data['twitter']) . "', 
        photo = '" . $this->db->escape($data['photo']) . "',  
        rif = '" . $this->db->escape($data['rif']) . "',
        company = '" . $this->db->escape($data['company']) . "' 
        WHERE customer_id = '" . (int) $this->customer->getId() . "'");
    }

    public function addAddress($customer_id, $data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
          customer_id = '" . (int) $customer_id . "', 
          firstname = '" . $this->db->escape($data['firstname']) . "', 
          lastname = '" . $this->db->escape($data['lastname']) . "', 
          company = '" . $this->db->escape($data['company']) . "', 
          address_1 = '" . $this->db->escape($data['address_1']) . "',
          city = '" . $this->db->escape($data['city']) . "', 
          postcode = '" . $this->db->escape($data['postcode']) . "', 
          country_id = '" . (int) $data['country_id'] . "', 
          zone_id = '" . (int) $data['zone_id'] . "'");

        $address_id = $this->db->getLastId();

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
        return $address_id;
    }

    public function addPersonal($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
          customer_id = '" . (int) $customer_id . "', 
          address_1 = '" . $this->db->escape($data['address_1']) . "', 
          city = '" . $this->db->escape($data['city']) . "', 
          postcode = '" . $this->db->escape($data['postcode']) . "', 
          country_id = '" . (int) $data['country_id'] . "', 
          zone_id = '" . (int) $data['zone_id'] . "'");

        $address_id = $this->db->getLastId();

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        firstname = '" . $this->db->escape($data['firstname']) . "', 
        lastname = '" . $this->db->escape($data['lastname']) . "', 
        nacimiento = '" . $this->db->escape($data['nacimiento']) . "', 
        telephone = '" . $this->db->escape($data['telephone']) . "', 
        sexo = '" . $this->db->escape($data['sexo']) . "', 
        address_id = '" . (int) $address_id . "'
        WHERE customer_id = '" . (int) $this->customer->getId() . "'");

        return $this->db->countAffected();
    }

    public function addSocial($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        msn = '" . $this->db->escape($data['msn']) . "', 
        gmail = '" . $this->db->escape($data['gmail']) . "', 
        yahoo = '" . $this->db->escape($data['yahoo']) . "', 
        skype = '" . $this->db->escape($data['skype']) . "', 
        facebook = '" . $this->db->escape($data['facebook']) . "', 
        twitter = '" . $this->db->escape($data['twitter']) . "',
        blog = '" . $this->db->escape($data['blog']) . "', 
        website = '" . $this->db->escape($data['website']) . "'
        WHERE customer_id = '" . (int) $this->customer->getId() . "'");
        return $this->db->countAffected();
    }

    public function addProfesion($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        blog = '" . $this->db->escape($data['blog']) . "', 
        website = '" . $this->db->escape($data['website']) . "', 
        profesion = '" . $this->db->escape($data['profesion']) . "', 
        titulo = '" . $this->db->escape($data['titulo']) . "' 
        WHERE customer_id = '" . (int) $this->customer->getId() . "'");
    }

    public function addFoto($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        photo = '" . $this->db->escape($data['photo']) . "' 
        WHERE customer_id = '" . (int) $this->customer->getId() . "'");
    }

    public function completeUser() {
        $result = $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        complete = '1' WHERE customer_id = '" . (int) $this->customer->getId() . "'");
        return $result;
    }

    public function editPassword($email, $password) {
        $query = $this->db->query("SELECT `password` FROM " . DB_PREFIX . "customer WHERE `email` = '" . $this->db->escape($email) . "'");

        list($pass, $suffix) = explode(':', $query->row['password']);
        if (!$suffix) {
            $suffix = md5(base_convert(rand(10e16, 10e20), 10, 36) . time());
        }

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
          `password`    = '" . $this->db->escape(md5($password . $suffix) . ':' . $suffix) . "' 
          WHERE `email` = '" . $this->db->escape($email) . "'");
    }

    public function editNewsletter($newsletter) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET 
        newsletter = '" . (int) $newsletter . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");
    }

    public function getAll($company) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer 
        WHERE LCASE(company) LIKE '%" . $this->db->escape(strtolower($company)) . "%'");
        return $query->rows;
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

        return $query->row;
    }

    public function getCustomerByProfile($profile) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE profile = '" . $this->db->escape($profile) . "'");

        return $query->row;
    }

    public function getTotalCustomersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");

        return $query->row['total'];
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");
        return $query->row;
    }

    public function getCustomerByTwitter($data) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer 
        WHERE twitter_oauth_provider = 'twitter' 
        AND twitter_oauth_id = '" . intval($data['oauth_id']) . "' 
        AND twitter_oauth_token_secret = '" . $this->db->escape($data['oauth_token_secret']) . "'");

        return $query->row['total'];
    }

    public function getCustomerByGoogle($data) {
        $sql = "SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "customer ";

        if (!empty($data['email'])) {
            $sql .= " WHERE email = '" . $this->db->escape($data['email']) . "'";
        } else {
            $sql .= " WHERE google_oauth_id = '" . $this->db->escape($data['google_oauth_id']) . "'";
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function getCustomerByLive($data) {
        $sql = "SELECT COUNT(*) AS total 
        FROM " . DB_PREFIX . "customer ";

        if (!empty($data['email'])) {
            $sql .= " WHERE email = '" . $this->db->escape($data['email']) . "'";
        } else {
            $sql .= " WHERE live_oauth_id = '" . $this->db->escape($data['live_oauth_id']) . "'";
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function getCustomerByMeli($data) {

        if (!empty($data['email'])) {
        $sql = "SELECT COUNT(*) AS total "
        ."FROM " . DB_PREFIX . "customer "
        ." WHERE email = '" . $this->db->escape($data['email']) . "'";
        } else {
        $sql = "SELECT COUNT(*) AS total "
        ."FROM " . DB_PREFIX . "customer_property "
        ." WHERE `group` = 'meli' "
        ." AND `key` = 'meli_oauth_id' " 
        ." AND `value` = '". $this->db->escape($data['meli_oauth_id']) . "'";
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function addTransferencia($data) {
        $strError = '';
        if (!$this->checkOrderStatus($data['order_id'])) {
            $strError .= "Estado Incorrecto";
            $error = true;
        }

        if ($data['forma_de_pago'] == 'Deposito') {
            if (!$this->checkPaymentMethod($data['order_id'], 'Cheque')) {
                $strError .= "<li>Lo siento, la forma de pago elegida para este pedido es diferente a <b>Dep&oacute;sito Bancario</b>.";
                $error = true;
            }
        }
        if ($data['forma_de_pago'] == 'Transferencia') {
            if (!$this->checkPaymentMethod($data['order_id'], 'Transferencia Bancaria')) {
                $strError .= "<li>Lo siento, la forma de pago elegida para este pedido es diferente a <b>Transferencia Bancaria</b>.";
                $error = true;
            }
        }

        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $data['order_id'] . "'");
        if ($order_query->num_rows) {
            if ($this->checkTransaccionID($data['order_id'], $order_query->row['customer_id'], $data['numero_transaccion'])) {
                $strError .= "<li>El n&uacute;mero de transacci&oacute;n ya existe.</li>";
                $error = true;
            }
        }

        if (!$this->checkFechaPago($data['order_id'], $data['fecha_pago'])) {
            $strError .= "<li>Por su seguridad, no puede reportar un pago con fecha inferior a la fecha del pedido.</li>";
            $error = true;
        }
        if (!$strError) {
            $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $data['order_id'] . "'");
            if ($order_query->num_rows) {
                $customer_id = $order_query->row['customer_id'];
                $resta = (float) $order_query->row['total'] - (float) $data['monto_cancelado'];
                $monto_a_devolver = 0;
                $monto_restante = 0;
                if ($resta > 0) {
                    $monto_restante = $resta;
                } elseif ($resta < 0) {
                    $monto_a_devolver = str_replace('-', '', $resta);
                }

                $pago_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "pago` ORDER BY pago_id DESC LIMIT 1");
                if ($pago_query->num_rows) {
                    $pago_id = $pago_query->row['pago_id'] + 1;
                } else {
                    $pago_id = 1;
                }
                $codigo = sha1((int) $data['order_id'] . (int) $customer_id . $data['numero_transaccion']);
                $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "pago` SET pago_id = '" . (int) $pago_id . "', order_id = '" . (int) $data['order_id'] . "', customer_id = '" . (int) $customer_id . "', numero_transaccion = '" . $this->db->escape($data['numero_transaccion']) . "', nombre = '" . $this->db->escape($data['nombre']) . "', mi_banco = '" . $this->db->escape($data['mi_banco']) . "', forma_de_pago = '" . $this->db->escape($data['forma_de_pago']) . "', tipo_deposito = '" . $this->db->escape($data['tipo_deposito']) . "', su_banco = '" . $this->db->escape($data['su_banco']) . "', monto_cancelado = '" . (float) $data['monto_cancelado'] . "', monto_del_pedido = '" . (float) $order_query->row['total'] . "', monto_a_devolver = '" . (float) $monto_a_devolver . "', monto_restante = '" . (float) $monto_restante . "', observacion = '" . $this->db->escape($data['observacion']) . "', codigo = '" . md5($codigo) . "', fecha_pago = '" . date('Y-m-d', strtotime($data['fecha_pago'])) . "', fecha_creado = now()");
                $pago_id = $this->db->getLastId();
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '7' WHERE order_id = '" . (int) $data['order_id'] . "'");
                return $result;
            }
        }
        $strError .= "<br><h3>Si posee alguna duda o pregunta sobre el proceso, por favor cont&aacute;ctenos.</h3>.";
        return $strError;
    }

    public function getTransferenciaByOrder($order_id) {
        $query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "transferencia WHERE order_id = '" . (int) $order_id . "'");
        return $query->row['total'];
    }

    public function getTransferencia($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pago WHERE order_id = '" . (int) $order_id . "'");
        return $query->row;
    }

    public function checkOrderStatus($order_id) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        if (($order_query->row['order_status_id'] == 1) || ($order_query->row['order_status_id'] == 7)) {
            return true;
        }
    }

    public function checkPaymentMethod($order_id, $method, $language_id = 1) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int) $order_id . "' and `language_id` = '" . (int) $language_id . "'");
        if (($order_query->row['payment_method'] == $method)) {
            return true;
        }
    }

    public function checkTransaccionID($order_id, $customer_id, $transaccionID) {
        $pago_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "pago`");
        if ($pago_query->num_rows) {
            $codigo = sha1($order_id . $customer_id . $transaccionID);
            foreach ($pago_query->rows as $value) {
                if (md5($codigo) == $value['codigo']) {
                    return true;
                }
            }
        }
    }

    public function checkFechaPago($order_id, $fecha) {
        if (!empty($order_id)) {
            $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
            if ($order_query->num_rows) {
                if ($fecha > date('d-m-Y', strtotime($order_query->row['date_added']))) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * review types:
     * - seller_resposibility
     * - seller_customer_care
     * - seller_response_time
     * - seller_quality
     * - seller_fair_prices
     * @param type $id
     * @return array $results
     */
    public function getSellerRatings($id) {
        $query = $this->db->query("SELECT review_type, "
                . "SUM(rating) AS total_rating, "
                . "COUNT(review_id) AS total_reviews, "
                . "ROUND((SUM(rating) / (COUNT(review_id)*5) * 100), 0) AS percent "
                . "FROM " . DB_PREFIX . "review "
                . "WHERE object_id = '" . (int) $id . "' "
                . "AND object_type = 'seller' "
                . "AND status = 1 "
                . "AND review_type LIKE 'seller_%' "
                . "GROUP BY review_type "
                . "ORDER BY review_type");
        return $query->rows;
    }

    /**
     * review types:
     * - buyer_responsibility
     * - buyer_amability
     * - buyer_response_time
     * - buyer_patience
     * - buyer_good_pay
     * @param type $id
     * @return array $results
     */
    public function getBuyerRatings($id) {
        $query = $this->db->query("SELECT review_type, "
                . "SUM(rating) AS total_rating, "
                . "COUNT(review_id) AS total_reviews, "
                . "ROUND((SUM(rating) / (COUNT(review_id)*5) * 100), 0) AS percent "
                . "FROM " . DB_PREFIX . "review "
                . "WHERE object_id = '" . (int) $id . "' "
                . "AND object_type = 'seller' "
                . "AND status = 1 "
                . "AND review_type LIKE 'buyer_%' "
                . "GROUP BY review_type "
                . "ORDER BY review_type");
        return $query->rows;
    }

    public function getActivities($criteria) {
        $sql = "SELECT *, ca.date_added AS dateAdded "
                . "FROM `" . DB_PREFIX . "customer_activity` ca "
                . "LEFT JOIN `" . DB_PREFIX . "customer` c ON (c.customer_id=ca.customer_id) ";

        $criteria = (is_array($criteria) && !empty($criteria)) ? $criteria : array();

        if ($criteria['object_type']) {
            if ($criteria['object_type'] === 'product') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "product` p ON (ca.object_id=p.product_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id=p.product_id) ";
                $filters[] = "pd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'category') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "category` ct ON (ca.object_id=ct.category_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (pd.category_id=ct.category_id) ";
                $filters[] = "cd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'manufacturer') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (ca.object_id=m.manufacturer_id) ";
            }
            if ($criteria['object_type'] === 'post') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "post` p ON (ca.object_id=p.post_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "post_description` pd ON (pd.post_id=p.post_id) ";
                $filters[] = "pd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'page') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "post` p ON (ca.object_id=p.post_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "post_description` pd ON (pd.post_id=p.post_id) ";
                $filters[] = "pd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'post_category') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "post_category` ct ON (ca.object_id=ct.post_category_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "post_category_description` cd ON (pd.post_category_id=ct.post_category_id) ";
                $filters[] = "cd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
        }

        if ($criteria['customer_activity_id'])
            $filters[] = "`customer_activity_id` = '" . intval($criteria['customer_activity_id']) . "'";
        if ($criteria['customer_id'])
            $filters[] = "ca.`customer_id` = '" . intval($criteria['customer_id']) . "'";
        if ($criteria['object_id'])
            $filters[] = "ca.`object_id` = '" . intval($criteria['object_id']) . "'";
        if ($criteria['object_type'])
            $filters[] = "ca.`object_type` = '" . $this->db->escape($criteria['object_type']) . "'";
        if ($criteria['action_type'])
            $filters[] = "ca.`action_type` = '" . $this->db->escape($criteria['action_type']) . "'";
        if ($criteria['overview'])
            $filters[] = "LCASE(ca.`overview`) LIKE '%" . $this->db->escape(strtolower($criteria['overview'])) . "%'";
        if ($criteria['description'])
            $filters[] = "LCASE(ca.`description`) LIKE '%" . $this->db->escape(strtolower($criteria['description'])) . "%'";
        if ($criteria['status'])
            $filters[] = "ca.`status` = '" . intval($criteria['status']) . "'";

        if ($criteria['date_start'] && $criteria['date_end']) {
            $filters[] = "ca.`date_added` BETWEEN '" . $this->db->escape($criteria['date_start']) . "' AND '" . $this->db->escape($criteria['date_end']) . "'";
        } elseif ($criteria['date_start'] && !$criteria['date_end']) {
            $filters[] = "ca.`date_added` BETWEEN '" . $this->db->escape($criteria['date_start']) . "' AND '" . date('Y-m-d h:i:s') . "'";
        } elseif (!$criteria['date_start'] && $criteria['date_end']) {
            $filters[] = "ca.`date_added` <= '" . $this->db->escape($criteria['date_end']) . "'";
        }

        $sql .= " WHERE " . implode(" AND ", $filters);

        $sql .= " ORDER BY ca.date_added DESC ";

        if ($criteria['start'] && $criteria['end']) {
            $sql .= " LIMIT " . intval($criteria['start']) . "," . intval($criteria['end']);
        } elseif ($criteria['end']) {
            $sql .= " LIMIT " . intval($criteria['end']);
        } else {
            $sql .= " LIMIT 20";
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getTotalActivities($criteria) {
        $sql = "SELECT COUNT(*) AS total "
                . "FROM `" . DB_PREFIX . "customer_activity` ca "
                . "LEFT JOIN `" . DB_PREFIX . "customer` c ON (c.customer_id=ca.customer_id) ";

        $criteria = (is_array($criteria) && !empty($criteria)) ? $criteria : array();

        if ($criteria['object_type']) {
            if ($criteria['object_type'] === 'product') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "product` p ON (ca.object_id=p.product_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id=p.product_id) ";
                $filters[] = "pd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'category') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "category` ct ON (ca.object_id=ct.category_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (pd.category_id=ct.category_id) ";
                $filters[] = "cd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'manufacturer') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (ca.object_id=m.manufacturer_id) ";
            }
            if ($criteria['object_type'] === 'post') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "post` p ON (ca.object_id=p.post_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "post_description` pd ON (pd.post_id=p.post_id) ";
                $filters[] = "pd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'page') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "post` p ON (ca.object_id=p.post_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "post_description` pd ON (pd.post_id=p.post_id) ";
                $filters[] = "pd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
            if ($criteria['object_type'] === 'post_category') {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "post_category` ct ON (ca.object_id=ct.post_category_id) "
                        . "LEFT JOIN `" . DB_PREFIX . "post_category_description` cd ON (pd.post_category_id=ct.post_category_id) ";
                $filters[] = "cd.`language_id` = '" . intval($this->config->get('config_language_id')) . "'";
            }
        }

        if ($criteria['customer_activity_id'])
            $filters[] = "`customer_activity_id` = '" . intval($criteria['customer_activity_id']) . "'";
        if ($criteria['customer_id'])
            $filters[] = "ca.`customer_id` = '" . intval($criteria['customer_id']) . "'";
        if ($criteria['object_id'])
            $filters[] = "ca.`object_id` = '" . intval($criteria['object_id']) . "'";
        if ($criteria['object_type'])
            $filters[] = "ca.`object_type` = '" . $this->db->escape($criteria['object_type']) . "'";
        if ($criteria['action_type'])
            $filters[] = "ca.`action_type` = '" . $this->db->escape($criteria['action_type']) . "'";
        if ($criteria['overview'])
            $filters[] = "LCASE(ca.`overview`) LIKE '%" . $this->db->escape(strtolower($criteria['overview'])) . "%'";
        if ($criteria['description'])
            $filters[] = "LCASE(ca.`description`) LIKE '%" . $this->db->escape(strtolower($criteria['description'])) . "%'";
        if ($criteria['status'])
            $filters[] = "ca.`status` = '" . intval($criteria['status']) . "'";

        if ($criteria['date_start'] && $criteria['date_end']) {
            $filters[] = "ca.`date_added` BETWEEN '" . $this->db->escape($criteria['date_start']) . "' AND '" . $this->db->escape($criteria['date_end']) . "'";
        } elseif ($criteria['date_start'] && !$criteria['date_end']) {
            $filters[] = "ca.`date_added` BETWEEN '" . $this->db->escape($criteria['date_start']) . "' AND '" . date('Y-m-d h:i:s') . "'";
        } elseif (!$criteria['date_start'] && $criteria['date_end']) {
            $filters[] = "ca.`date_added` <= '" . $this->db->escape($criteria['date_end']) . "'";
        }
        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function customerMailed($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'profile_mailed');
    }

    public function customerCalled($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'profile_called');
    }

    public function webVisited($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'web_visited');
    }

    public function blogVisited($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'blog_visited');
    }

    public function facebookVisited($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'facebook_visited');
    }

    public function skypeVisited($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'skype_visited');
    }

    public function twitterVisited($seller_id, $customer_id) {
        $this->registerStat($seller_id, $customer_id, 'twitter_visited');
    }

    public function registerStat($seller_id, $customer_id, $object_type) {
        $this->load->library('browser');
        $browser = new Browser;
        $this->db->query("INSERT " . DB_PREFIX . "stat SET 
        `object_id`     = '" . (int) $seller_id . "',
        `store_id`      = '" . (int) STORE_ID . "',
        `customer_id`   = '" . (int) $customer_id . "',
        `object_type`   = '" . $this->db->escape($object_type) . "',
        `server`        = '" . $this->db->escape(serialize($_SERVER)) . "',
        `session`       = '" . $this->db->escape(serialize($_SESSION)) . "',
        `request`       = '" . $this->db->escape(serialize($_REQUEST)) . "',
        `store_url`     = '" . $this->db->escape($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) . "',
        `ref`           = '" . $this->db->escape($_SERVER['HTTP_REFERER']) . "',
        `browser`       = '" . $this->db->escape($browser->getBrowser()) . "',
        `browser_version`= '" . $this->db->escape($browser->getVersion()) . "',
        `os`            = '" . $this->db->escape($browser->getPlatform()) . "',
        `ip`            = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "',
        `date_added`    = NOW()");
    }

    public function addNecoexp($id, $exp) {
        $currentExp = $this->getProperty($id, 'rewards', 'necoexp');
        $this->setProperty($id, 'rewards', 'necoexp', $currentExp + $exp);
        $this->session->set('necoexp', '+'.$exp);
    }

    public function subtractNecoexp($id, $exp) {
        $currentExp = $this->getProperty($id, 'rewards', 'necoexp');
        $this->setProperty($id, 'rewards', 'necoexp', $currentExp - $exp);
        $this->session->set('necoexp', '-'.$exp);
    }

    public function resetNecoexp($id) {
        $this->setProperty($id, 'rewards', 'necoexp', 0);
        $this->session->set('necoexp', 'Reset');
    }

    public function getNecoexp($id) {
        return $this->getProperty($id, 'rewards', 'necoexp');
    }

    public function addNecopoints($id, $points) {
        $currentpoints = $this->getProperty($id, 'rewards', 'necopoints');
        $this->setProperty($id, 'rewards', 'necopoints', $currentpoints + $points);
        $this->session->set('necopoints', '+'.$points);
    }

    public function subtractNecopoints($id, $points) {
        $currentpoints = $this->getProperty($id, 'rewards', 'necopoints');
        $this->setProperty($id, 'rewards', 'necopoints', $currentpoints - $points);
        $this->session->set('necopoints', '-'.$points);
    }

    public function resetNecopoints($id) {
        $this->setProperty($id, 'rewards', 'necopoints', 0);
        $this->session->set('necopoints', 'Reset');
    }

    public function getNecopoints($id) {
        return $this->getProperty($id, 'rewards', 'necopoints');
    }

    public function getLevel($id) {
        return $this->getProperty($id, 'rewards', 'level');
    }

    public function setLevel($id, $level) {
        $this->setProperty($id, 'rewards', 'level', $level);
    }

    public function checkLevel($id) {
        $levels = array(
            1 => array('necoexp'=>55, 'necopoints'=>10),
            2 => array('necoexp'=>85, 'necopoints'=>30),
            3 => array('necoexp'=>115, 'necopoints'=>70),
            4 => array('necoexp'=>140, 'necopoints'=>150),
            5 => array('necoexp'=>90, 'necopoints'=>310),
            6 => array('necoexp'=>75, 'necopoints'=>630),
            7 => array('necoexp'=>110, 'necopoints'=>1270),
            8 => array('necoexp'=>120, 'necopoints'=>2550),
            9 => array('necoexp'=>110, 'necopoints'=>5110),
            10 => array('necoexp'=>160, 'necopoints'=>10230)
        );
        
        $currentLevel = $this->getLevel($id);
        $currentExp = $this->getNecoexp($id);
        $currentPoints = $this->getNecopoints($id);
        $nextLevel = $levels[(int)$currentLevel + 1];
        
        if ($nextLevel['necoexp'] <= $currentExp && $nextLevel['necopoints'] <= $currentPoints) {
            $this->subtractNecoexp($id, $nextLevel['necoexp']);
            $this->subtractNecopoints($id, $nextLevel['necopoints']);
            $this->setLevel($id, (int)$currentLevel + 1);
            $this->session->set('level_upgraded', (int)$currentLevel + 1);
        }
        
        return $this->getLevel($id);
    }

    /**
     * ModelAccountCustomer::getProperty($id, $group, $key)
     * 
     * Obtener una propiedad del producto
     * 
     * @param int $id customer_id
     * @param varchar $group
     * @param varchar $key
     * @return mixed value of property
     * */
    public function getProperty($id, $group, $key) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_property 
        WHERE `customer_id` = '" . (int) $id . "' 
        AND `group` = '" . $this->db->escape($group) . "'
        AND `key` = '" . $this->db->escape($key) . "'");

        return unserialize(str_replace("\'", "'", $query->row['value']));
    }

    /**
     * ModelAccountCustomer::getAllProperties()
     * 
     * Obtiene todas las propiedades del cliente
     * 
     * Si quiere obtener todos los grupos de propiedades
     * utilice * como nombre del grupo, ejemplo:
     * 
     * $properties = getAllProperties($id, '*');
     * 
     * Sino coloque el nombre del grupo de las propiedades
     * 
     * $properties = getAllProperties($id, 'NombreDelGrupo');
     * 
     * @param int $id customer_id
     * @param varchar $group
     * @return array all properties
     * */
    public function getAllProperties($id, $group = '*') {
        if ($group == '*') {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_property 
            WHERE `customer_id` = '" . (int) $id . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_property 
            WHERE `customer_id` = '" . (int) $id . "' 
            AND `group` = '" . $this->db->escape($group) . "'");
        }

        return $query->rows;
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
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_property SET
        `customer_id`   = '" . (int) $id . "',
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
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_property 
        WHERE `customer_id` = '" . (int) $id . "' 
        AND `group` = '" . $this->db->escape($group) . "'
        AND `key` = '" . $this->db->escape($key) . "'");
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
            $this->db->query("DELETE FROM " . DB_PREFIX . "customer_property 
            WHERE `customer_id` = '" . (int) $id . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "customer_property 
            WHERE `customer_id` = '" . (int) $id . "' 
            AND `group` = '" . $this->db->escape($group) . "'");
        }
    }

}
