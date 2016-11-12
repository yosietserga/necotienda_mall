<?php
/**
 * CronSeller
 * 
 * Clase que asiste en los contactos iniciales y preventas brindando
 * un vendedor automatico que cumplira con las fases establecidas en el proceso
 * de venta, viene siendo como un consultor CRM automatico
 * */
class CronSeller {
    
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
        $this->registry   = $registry;
        $this->load   = $registry->get('load');
        $this->mailer = $registry->get('mailer');
        $this->config = $registry->get('config');
        $this->cache  = $registry->get('cache');
        $this->db     = $registry->get('db');
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
        }
        /**
         * 
         * array (
         * send_campaign,               // enviar campa�a de email marketing
         * send_birthday,               // enviar felicitaciones de cumplea�os a todos los clientes que cumplan a�o
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
    
    private function isLocked($job) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX ."task_exec WHERE `type` = '". $this->db->escape($job) ."'");
        if (count($query->rows)) {
            return true;
        } else {
            return false;
        }
    }
}