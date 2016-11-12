<?php

class ModelCheckoutOrder extends Model {

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $order_data = $order_query->row;

            $order_data['shipping_zone_code'] = $shipping_zone_code;
            $order_data['shipping_iso_code_2'] = $shipping_iso_code_2;
            $order_data['shipping_iso_code_3'] = $shipping_iso_code_3;
            $order_data['payment_zone_code'] = $payment_zone_code;
            $order_data['payment_iso_code_2'] = $payment_iso_code_2;
            $order_data['payment_iso_code_3'] = $payment_iso_code_3;

            return $order_data;
        } else {
            return false;
        }
    }

    public function create($data) {
        $query = $this->db->query("SELECT order_id 
        FROM `" . DB_PREFIX . "order` 
        WHERE date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' 
        AND order_status_id = '0'");

        foreach ($query->rows as $result) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $result['order_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int) $result['order_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $result['order_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $result['order_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $result['order_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $result['order_id'] . "'");
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET 
        store_id          = '" . (int) STORE_ID . "',
        customer_id         = '" . (int) $data['customer_id'] . "', 
        customer_group_id   = '" . (int) $data['customer_group_id'] . "', 
        firstname           = '" . $this->db->escape($data['firstname']) . "', 
        lastname            = '" . $this->db->escape($data['lastname']) . "', 
        email               = '" . $this->db->escape($data['email']) . "', 
        telephone           = '" . $this->db->escape($data['telephone']) . "', 
        fax                 = '" . $this->db->escape($data['fax']) . "', 
        total               = '" . (float) $data['total'] . "', 
        language_id         = '" . (int) $data['language_id'] . "', 
        currency            = '" . $this->db->escape($data['currency']) . "', 
        currency_id         = '" . (int) $data['currency_id'] . "', 
        value               = '" . (float) $data['value'] . "', 
        coupon_id           = '" . (int) $data['coupon_id'] . "', 
        ip                  = '" . $this->db->escape($data['ip']) . "', 
        order_status_id     = '" . $this->config->get('config_order_status_id') . "', 
        shipping_firstname  = '" . $this->db->escape($data['shipping_firstname']) . "', 
        shipping_lastname   = '" . $this->db->escape($data['shipping_lastname']) . "', 
        shipping_company    = '" . $this->db->escape($data['shipping_company']) . "', 
        shipping_address_1  = '" . $this->db->escape($data['shipping_address_1']) . "', 
        shipping_address_2  = '" . $this->db->escape($data['shipping_address_2']) . "', 
        shipping_city       = '" . $this->db->escape($data['shipping_city']) . "', 
        shipping_postcode   = '" . $this->db->escape($data['shipping_postcode']) . "', 
        shipping_zone       = '" . $this->db->escape($data['shipping_zone']) . "', 
        shipping_zone_id    = '" . (int) $data['shipping_zone_id'] . "', 
        shipping_country    = '" . $this->db->escape($data['shipping_country']) . "', 
        shipping_country_id = '" . (int) $data['shipping_country_id'] . "', 
        shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
        shipping_method     = '" . $this->db->escape($data['shipping_method']) . "', 
        payment_firstname   = '" . $this->db->escape($data['payment_firstname']) . "', 
        payment_lastname    = '" . $this->db->escape($data['payment_lastname']) . "', 
        payment_company     = '" . $this->db->escape($data['payment_company']) . "', 
        payment_address_1   = '" . $this->db->escape($data['payment_address_1']) . "', 
        payment_address_2   = '" . $this->db->escape($data['payment_address_2']) . "', 
        payment_city        = '" . $this->db->escape($data['payment_city']) . "', 
        payment_postcode    = '" . $this->db->escape($data['payment_postcode']) . "', 
        payment_zone        = '" . $this->db->escape($data['payment_zone']) . "', 
        payment_zone_id     = '" . (int) $data['payment_zone_id'] . "', 
        payment_country     = '" . $this->db->escape($data['payment_country']) . "', 
        payment_country_id  = '" . (int) $data['payment_country_id'] . "', 
        payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
        payment_method      = '" . $this->db->escape($data['payment_method']) . "', 
        comment             = '" . $this->db->escape($data['comment']) . "', 
        date_modified       = NOW(),
        date_added          = NOW()");

        $order_id = $this->db->getLastId();

        foreach ($data['products'] as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET 
            order_id = '" . (int) $order_id . "', 
            product_id = '" . (int) $product['product_id'] . "', 
            name = '" . $this->db->escape($product['name']) . "', 
            model = '" . $this->db->escape($product['model']) . "', 
            price = '" . (float) $product['price'] . "', 
            total = '" . (float) $product['total'] . "', 
            tax = '" . (float) $product['tax'] . "', 
            quantity = '" . (int) $product['quantity'] . "'");

            $order_product_id = $this->db->getLastId();

            foreach ($product['option'] as $option) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET 
                order_id = '" . (int) $order_id . "', 
                order_product_id = '" . (int) $order_product_id . "', 
                product_option_value_id = '" . (int) $option['product_option_value_id'] . "', 
                name = '" . $this->db->escape($option['name']) . "', 
                `value` = '" . $this->db->escape($option['value']) . "', 
                price = '" . (float) $product['price'] . "', 
                prefix = '" . $this->db->escape($option['prefix']) . "'");
            }

            foreach ($product['download'] as $download) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET 
                order_id = '" . (int) $order_id . "', 
                order_product_id = '" . (int) $order_product_id . "', 
                name = '" . $this->db->escape($download['name']) . "', 
                filename = '" . $this->db->escape($download['filename']) . "', 
                mask = '" . $this->db->escape($download['mask']) . "', 
                remaining = '" . (int) ($download['remaining'] * $product['quantity']) . "'");
            }
        }

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET 
            order_id = '" . (int) $order_id . "', 
            title = '" . $this->db->escape($total['title']) . "', 
            text = '" . $this->db->escape($total['text']) . "', 
            `value` = '" . (float) $total['value'] . "', 
            sort_order = '" . (int) $total['sort_order'] . "'");
        }

        return $order_id;
    }

    public function confirm($order_id, $order_status_id, $comment = '') {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
            order_status_id = '" . (int) $order_status_id . "' 
            WHERE order_id = '" . (int) $order_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET 
            order_id = '" . (int) $order_id . "', 
            order_status_id = '" . (int) $order_status_id . "', 
            notify = '1', 
            comment = '" . $this->db->escape($comment) . "', 
            date_added = NOW()");

        $order_product_query = $this->db->query("SELECT * 
            FROM " . DB_PREFIX . "order_product 
            WHERE order_id = '" . (int) $order_id . "'");

        foreach ($order_product_query->rows as $product) {
            if ($product['subtract']) {
                $this->db->query("UPDATE " . DB_PREFIX . "product SET 
                    quantity = (quantity - " . (int) $product['quantity'] . ") 
                    WHERE product_id = '" . (int) $product['product_id'] . "'");
            }
            /*
              $order_option_query = $this->db->query("SELECT *
              FROM " . DB_PREFIX . "order_option
              WHERE order_id = '" . (int)$order_id . "'
              AND order_product_id = '" . (int)$product['order_product_id'] . "'");

              foreach ($order_option_query->rows as $option) {
              $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET
              quantity = (quantity - " . (int)$product['quantity'] . ")
              WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "'
              AND subtract = '1'");
              }
             */
            $this->cache->delete('product');
        }
    }

    public function update($order_id, $order_status_id, $comment = '', $notify = false) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "' AND o.order_status_id> '0'");

        if ($order_query->num_rows) {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            if ($notify) {
                $language = new Language($order_query->row['directory']);
                $language->load($order_query->row['filename']);
                $language->load('mail/order_update');

                $subject = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

                $message = $language->get('text_order') . ' ' . $order_id . "\n";
                $message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";

                $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_query->row['language_id'] . "'");

                if ($order_status_query->num_rows) {
                    $message .= $language->get('text_order_status') . "\n\n";
                    $message .= $order_status_query->row['name'] . "\n\n";
                }

                $message .= $language->get('text_invoice') . "\n";
                $message .= $order_query->row['store_url'] . 'index.php?r=account/invoice&order_id=' . $order_id . "\n\n";

                if ($comment) {
                    $message .= $language->get('text_comment') . "\n\n";
                    $message .= $comment . "\n\n";
                }

                $message .= $language->get('text_footer');

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($order_query->row['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($order_query->row['store_name']);
                $mail->setSubject($subject);
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }
        }
    }

}
