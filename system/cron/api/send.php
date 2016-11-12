<?php

class CronSend {

    /**
     * @var $registry
     * */
    protected $registry;

    /**
     * @var $load
     * */
    protected $load;

    /**
     * @var $config
     * */
    protected $config;

    /**
     * @var $db
     * */
    protected $db;

    /**
     * @var $mailer
     * */
    protected $mailer;

    /**
     * @var $cache
     * */
    protected $cache;

    public function __construct($registry) {
        $this->registry = $registry;
        $this->load = $registry->get('load');
        $this->mailer = $registry->get('mailer');
        $this->config = $registry->get('config');
        $this->cache = $registry->get('cache');
        $this->db = $registry->get('db');
    }

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }

    public function __isset($key) {
        return $this->registry->has($key);
    }

    public function run($tasks) {
        foreach ($tasks as $key => $task) {
            if (isset($task->params['job']) && $task->params['job'] == 'send_campaign') {
                $this->sendCampaign($task);
            }
            if (isset($task->params['job']) && $task->params['job'] == 'send_birthday') {
                $this->sendBirthday($task);
            }
            if (isset($task->params['job']) && $task->params['job'] == 'send_recommended_products') {
                $this->sendRecommendedProducts($task);
            }
        }
        /**
         * 
         * array (
         * ,               // enviar felicitaciones de cumplea�os a todos los clientes que cumplan a�o
         * send_new_products,           // enviar bolet�n de productos nuevos
         * send_products_of_interest,   // enviar productos de inter�s para el cliente
         * send_special,                // enviar bolet�n con los productos en ofertas
         * send_new_private_sales       // enviar bolet�n con las nuevas ventas privadas
         * send_open_orders             // enviar notificaci�n con todas las �rdenes que no se han concretado o pedidos abiertos
         * send_inactive_customers      // enviar notificaci�n a los clientes que est�n inactivos
         * send_unapproved_customers    // enviar notificaci�n a los clientes que est�n pendientes por verificaci�n
         * )
         * - 
         * */
    }

    public function sendBirthday($task) {
        if ($this->isLocked('send_birthday', $task->task_id)) {
            $task->addMinute(15);
        } else {
            $task->start();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . $task->object_id . "'");
            if ($query->num_rows) {
                $htmlbody = $this->prepareTemplate($query->row['htmlbody']);
                $count = 0;
                foreach ($task->getTaskQueue() as $key => $queue) {
                    if ($count >= 50)
                        break;

                    $params = unserialize($queue['params']);

                    $htmlbody = str_replace("{%fullname%}", $params['fullname'], $htmlbody);
                    $htmlbody = str_replace("{%rif%}", $params['rif'], $htmlbody);
                    $htmlbody = str_replace("{%company%}", $params['company'], $htmlbody);
                    $htmlbody = str_replace("{%email%}", $params['email'], $htmlbody);
                    $htmlbody = str_replace("{%telephone%}", $params['telephone'], $htmlbody);

                    $this->mailer->AddAddress($params['email'], $params['fullname']);
                    $this->mailer->IsHTML();
                    $this->mailer->SetFrom($this->config->get('config_email'), $this->config->get('config_name'));
                    $this->mailer->Subject = "Feliz Cumpleanos - " . $this->config->get('config_name');
                    $this->mailer->Body = $htmlbody;
                    $this->mailer->Send();
                    $this->mailer->ClearAllRecipients();

                    $task->setQueueDone($key);
                    $count++;
                }

                if (count($task->getTaskDos())) {
                    $task->addMinute(15);
                } else {
                    $task->setTaskDone();
                }
            }
            $task->update();
        }
    }

    public function sendRecommendedProducts($task) {
        if ($this->isLocked('send_recommended_products', $task->task_id)) {
            $task->addMinute(15);
        } else {
            $task->start();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . $task->object_id . "'");
            if ($query->num_rows) {
                $htmlbody = $this->prepareTemplate($query->row['htmlbody']);
                $count = 0;
                foreach ($task->getTaskQueue() as $key => $queue) {
                    if ($count >= 50)
                        break;

                    $params = unserialize($queue['params']);

                    $htmlbody = str_replace("{%fullname%}", $params['fullname'], $htmlbody);
                    $htmlbody = str_replace("{%rif%}", $params['rif'], $htmlbody);
                    $htmlbody = str_replace("{%company%}", $params['company'], $htmlbody);
                    $htmlbody = str_replace("{%email%}", $params['email'], $htmlbody);
                    $htmlbody = str_replace("{%telephone%}", $params['telephone'], $htmlbody);

                    $product_html = "<table><tr>";
                    $count = 0;
                    foreach ($params['products']as $key => $product) {
                        $product_html .= "<td style=\"text-align:center;\">";
                        $product_html .= "<img src=\"" . HTTP_IMAGE . $product['image'] . "\" alt=\"" . $product['name'] . "\" /><br />";
                        $product_html .= "<h3>" . $product['name'] . "</h3>";
                        $product_html .= "<p>" . $product['model'] . "<br /><b>" . $product['price'] . "</b></p>";
                        $product_html .= "<a href=\"" . $product['href'] . "\">Ver Detalles</a>";
                        $product_html .= "</td>";
                        if ($count >= 3) {
                            $count = 0;
                            $product_html .= "</tr><tr>";
                        } else {
                            $count++;
                        }
                    }
                    $product_html .= "</tr></table>";
                    $htmlbody = str_replace("{%products%}", $product_html, $htmlbody);

                    $this->mailer->AddAddress($params['email'], $params['fullname']);
                    $this->mailer->IsHTML();
                    $this->mailer->SetFrom($this->config->get('config_email'), $this->config->get('config_name'));
                    $this->mailer->Subject = "Productos Recomendados - " . $this->config->get('config_name');
                    $this->mailer->Body = $htmlbody;
                    $this->mailer->Send();
                    $this->mailer->ClearAllRecipients();

                    $task->setQueueDone($key);
                    $count++;
                }

                if (count($task->getTaskDos())) {
                    $task->addMinute(15);
                } else {
                    $task->setTaskDone();
                }
            }

            $task->update();
        }
    }

    public function sendCampaign($task) {
        if ($this->isLocked('send_campaign', $task->task_id)) {
            $task->addMinute(15);
        } else {
            $task->start();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "campaign c 
            LEFT JOIN " . DB_PREFIX . "newsletter n ON (n.newsletter_id=c.newsletter_id) 
            WHERE campaign_id = '" . (int) $task->params['campaign_id'] . "'");

            $campign_info = $query->row;

            $htmlbody = html_entity_decode($campign_info['htmlbody']);

            $count = 0;
            foreach ($task->getTaskQueue() as $key => $queue) {
                if ($count >= 50) {
                    break;
                }

                $params = unserialize($queue['params']);

                $cached = $this->cache->get("campaign.html.{$params['campaign_id']}.{$params['contact_id']}");
                if ($cached) {
                    $htmlbody = html_entity_decode($cached);
                }

                if (isset($params['product'])) {
                    $htmlbody = $this->prepareTemplateProduct($htmlbody, $params['product']);
                }
                
                $htmlbody = str_replace("%7B", "{", $htmlbody);
                $htmlbody = str_replace("%7D", "}", $htmlbody);
                $htmlbody = str_replace("{%contact_id%}", $params['contact_id'], $htmlbody);
                $htmlbody = str_replace("{%campaign_id%}", $params['campaign_id'], $htmlbody);
                $htmlbody = str_replace("{%fullname%}", $params['name'], $htmlbody);
                $htmlbody = str_replace("{%rif%}", $params['rif'], $htmlbody);
                $htmlbody = str_replace("{%company%}", $params['company'], $htmlbody);
                $htmlbody = str_replace("{%email%}", $params['email'], $htmlbody);
                $htmlbody = str_replace("{%telephone%}", $params['telephone'], $htmlbody);
                $htmlbody = $this->prepareTemplate($htmlbody, $params);

                $this->mailer->AddAddress($params['email'], $params['name']);
                $this->mailer->IsHTML();
                $this->mailer->SetFrom($campign_info['from_email'], $campign_info['from_name']);
                $this->mailer->AddReplyTo($campign_info['replyto_email'], $campign_info['from_name']);
                $this->mailer->Subject = $campign_info['subject'];
                $this->mailer->Body = $htmlbody;
                $this->mailer->Send();
                $this->mailer->ClearAllRecipients();

                $task->setQueueDone($key);
                $count++;
            }

            if (count($task->getTaskDos())) {
                $task->addMinute(15);
            } else {
                $task->setTaskDone();
            }
        }

        $task->update();

        /**
         * - detectar la hora de la ejecuci�n, si es mayor posponer tarea y actualizar el sort_order, si es menor y no se ha ejecutado, 
         * cambiar la hora de ejecuci�n para m�s tarde, si la hora se pasa el siguiente d�a entonces actualizar la fecha completa
         * 
         * 
         * - comprobar que la cola de trabajo est� libre o no est� bloqueada
         * - si est� bloqueada posponer time_exec 15 min a toda la tarea y la cola de trabajo
         * - si no lo est�, bloquearla actualizando la tabla task_exec y continuar
         * - obtener datos de la campa�a (SQL)
         * - dividir los contactos en grupos de 50
         * - agregar los destinatarios al objeto mailer
         * - enviar email
         * - actualizar queue con status 0 para indicar que est�n listas
         * - al enviar el grupo de cincuenta, 
         * - comprobar o contar cuantas actividades faltan
         * - si ya est� lista, actualizar la tarea con status cero para indicar que ya fue enviada y actualizar el registro de la campa�a y desbloquear la cola eliminando el registro de task_exec
         * - sino
         * - actualizar time_exec de la tarea sumando 15 min y time_last_exec con el tiempo ahora
         * - actualizar toda la cola de trabajo agregando 15 min a las actividades pendientes
         * */
    }

    private function isLocked($job, $current_task_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "task_exec WHERE `type` = '" . $this->db->escape($job) . "'");
        if (count($query->rows)) {
            $queue = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "task_queue WHERE `status` = '1' AND task_id = '" . (int) $query->row['task_id'] . "'");
            if ($queue->row['total'] > 0) {
                if ($current_task_id == $query->row['task_id']) {
                    return false;
                } else {
                    return true;
                }
            } else {
                $queue = $this->db->query("DELETE FROM " . DB_PREFIX . "task_exec WHERE `type` = '" . $this->db->escape($job) . "'");
                return false;
            }
        }
        return false;
    }

    private function prepareTemplateProduct($newsletter, $product = null) {
        if (!$newsletter && !$product)
            return false;
        $newsletter = str_replace("%7B", "{", $newsletter);
        $newsletter = str_replace("%7D", "}", $newsletter);
        $newsletter = str_replace("{%product_image%}", '<img src="' . $product['image'] . '" alt="' . $this->config->get('config_name') . '" />', $newsletter);
        $newsletter = str_replace("{%product_name%}", $product['name'], $newsletter);
        $newsletter = str_replace("{%product_model%}", $product['model'], $newsletter);
        $newsletter = str_replace("{%product_price%}", $product['price'], $newsletter);
        $newsletter = str_replace("{%product_special%}", $product['special'], $newsletter);
        $newsletter = str_replace("{%product_link%}", '<a href="' . $product['url'] .'">Ver Producto</a>', $newsletter);
        $newsletter = str_replace("{%product_url%}", $product['url'], $newsletter);
        
        foreach ($product['images'] as $k=>$v) {
            $html .= '<span><img src="'. $v['thumb'] .'" /></span>';
        }
        $newsletter = str_replace("{%product_images%}", $html, $newsletter);
        
        $html = '';
        foreach ($product['discounts'] as $k=>$v) {
            $i = rand(6,18);
            $html .= '<a href="'. $v['href'] .'" style="font-size:'. $i .'px">'. $v['tag'] .'</a>&nbsp;&nbsp;';
        }
        $newsletter = str_replace("{%product_tags%}", $html, $newsletter);
        
        $newsletter .= "<p style=\"text-align:center\">Powered By Necotienda&reg; " . date('Y') . "</p>";

        return html_entity_decode($newsletter);
    }

    private function prepareTemplate($newsletter, $params = null) {
        if (!$newsletter)
            return false;
        $this->load->library('url');
        $this->load->library('BarcodeQR');
        $this->load->library('Barcode39');
        $qr = new BarcodeQR;
        $barcode = new Barcode39(C_CODE);

        $qrStore = "cache/" . $this->escape($this->config->get('config_owner')) . '.png';
        $eanStore = "cache/" . $this->escape($this->config->get('config_owner')) . "_barcode_39.gif";

        if (!file_exists(DIR_IMAGE . $qrStore)) {
            $qr->url(HTTP_HOME);
            $qr->draw(150, DIR_IMAGE . $qrStore);
        }

        if (!file_exists(DIR_IMAGE . $eanStore)) {
            $barcode->draw(DIR_IMAGE . $eanStore);
        }

        $newsletter = str_replace("%7B", "{", $newsletter);
        $newsletter = str_replace("%7D", "}", $newsletter);
        $newsletter = str_replace("{%store_logo%}", '<img src="' . HTTP_IMAGE . $this->config->get('config_logo') . '" alt="' . $this->config->get('config_name') . '" />', $newsletter);
        $newsletter = str_replace("{%store_url%}", HTTP_HOME, $newsletter);
        $newsletter = str_replace("{%url_login%}", Url::createUrl("account/login"), $newsletter);
        $newsletter = str_replace("{%store_owner%}", $this->config->get('config_owner'), $newsletter);
        $newsletter = str_replace("{%store_name%}", $this->config->get('config_name'), $newsletter);
        $newsletter = str_replace("{%store_rif%}", $this->config->get('config_rif'), $newsletter);
        $newsletter = str_replace("{%store_email%}", $this->config->get('config_email'), $newsletter);
        $newsletter = str_replace("{%store_telephone%}", $this->config->get('config_telephone'), $newsletter);
        $newsletter = str_replace("{%store_address%}", $this->config->get('config_address'), $newsletter);
        /*
          $newsletter = str_replace("{%products%}",$product_html,$newsletter);
         */
        $newsletter = str_replace("{%date_added%}", date('d-m-Y h:i A'), $newsletter);
        $newsletter = str_replace("{%ip%}", $_SERVER['REMOTE_ADDR'], $newsletter);
        $newsletter = str_replace("{%qr_code_store%}", '<img src="' . HTTP_IMAGE . $qrStore . '" alt="QR Code" />', $newsletter);
        $newsletter = str_replace("{%barcode_39_order_id%}", '<img src="' . HTTP_IMAGE . $eanStore . '" alt="NT Code" />', $newsletter);

        $newsletter .= "<p style=\"text-align:center\">Powered By Necotienda&reg; " . date('Y') . "</p>";

        return html_entity_decode($newsletter);
    }

    public function escape($str) {
        if (isset($str)) {
            if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
            $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
            $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
            $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
            $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
            $str = strtolower(trim($str, '-'));
            return $str;
        } else {
            return false;
        }
    }

}
