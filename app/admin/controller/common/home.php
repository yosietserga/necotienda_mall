<?php   
class ControllerCommonHome extends Controller {   
	public function index() {
		$this->document->title = $this->language->get('heading_title');
        
		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);
		
		$this->data['token']          = $this->session->get('ukey');
		$this->data['total_sale']     = $this->currency->format($this->modelOrder->getTotalSales(), $this->config->get('config_currency'));
		$this->data['total_sale_year']= $this->currency->format($this->modelOrder->getTotalSalesByYear(date('Y')), $this->config->get('config_currency'));
		$this->data['total_order']    = $this->modelOrder->getAllTotal();
		$this->data['total_customer'] = $this->modelCustomer->getAllTotal();
		$this->data['total_customer_approval'] = $this->modelCustomer->getAllTotalAwaitingApproval();
		$this->data['total_product']  = $this->modelProduct->getAllTotal();
		$this->data['total_review']   = $this->modelReview->getAllTotal();
		$this->data['total_review_approval'] = $this->modelReview->getAllTotalAwaitingApproval();
		
		$this->data['orders'] = array(); 
		
		$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$results = $this->modelOrder->getAll($data);
    	
    	foreach ($results as $result) {
			$action = array();
			 
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => Url::createAdminUrl('sale/order/update',array('order_id'=>$result['order_id']))
			);
					
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'action'     => $action
			);
		}

		if ($this->config->get('config_currency_auto')) {
			$this->load->auto('localisation/currency');
			$this->modelCurrency->updateAll();
		}
		
        // javascript files
        $javascripts[] = "js/vendor/highcharts/highcharts.js";
        
        $this->data['javascripts'] = $this->javascripts = array_merge($javascripts,$this->javascripts);
        
        
        // SCRIPTS
        $scripts[] = array('id'=>'categoryList','method'=>'ready','script'=>
            "$('#chartOrders').load('". Url::createAdminUrl("chart/order") ."');
            $('#chartCustomers').load('". Url::createAdminUrl("chart/customer") ."');");
        $this->scripts = array_merge($this->scripts,$scripts);
        
        
		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
    
	public function login() { 
		if (!$this->user->validSession() 
            && ($this->request->getQuery('r') != 'common/login/login') 
            && ($this->request->getQuery('r') != 'common/login/recover')) 
        {
            $this->user->logout();
			return $this->forward('common/login');
		}
        
		if (isset($this->request->get['r']) && !isset($this->request->get['token'])) {
			$route = '';
			$part = explode('/', $this->request->get['r']);
			
			if (isset($part[0])) {
				$route .= $part[0];
			}
			
			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
			
			$ignore = array(
				'common/login',
				'common/login/login',
				'common/login/recover',
				'common/logout',
				'error/not_found',
				'error/permission'
			);
			
			$config_ignore = array();
			
			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}
				
			$ignore = array_merge($ignore, $config_ignore);
						
			if (!in_array($route, $ignore)) {
				if (!isset($this->request->get['token']) || !$this->user->validSession() || ($this->request->get['token'] != $this->session->get('ukey'))) {
				    $this->user->logout();
					return $this->forward('common/login');
				}
			}
		}
        
        if (substr_count($_SERVER['QUERY_STRING'],'token') >= 2) {
            foreach ($_GET as $arg => $value) {
                if ($arg == 'token') unset($_GET[$arg]);
            }
            $this->user->logout();
            return $this->forward('common/login');
		}
        if (!isset($this->request->get['token'])) {
				$this->user->logout();
                return $this->forward('common/login');
		}
        if ($this->request->get['token'] != $this->session->get('ukey')) {
				$this->user->logout();
                return $this->forward('common/login');
		}
	}
	
	public function permission() {
		if (isset($this->request->get['r'])) {
			$route = '';
			
			$part = explode('/', $this->request->get['r']);
			
			if (isset($part[0])) {
				$route .= $part[0];
			}
			
			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
			
			if (isset($part[2]) && $part[0] == 'module') {
				$route .= '/' . $part[2];
			}
			
			$ignore = array(
				'common/home',
				'common/login/login',
				'common/login/recover',
				'common/login',
				'common/logout',
				'error/not_found',
				'error/permission',
				'error/token'		
			);			
						
			if (!in_array($route, $ignore)) {
				if (!$this->user->hasPermission('access', $route)) {
					return $this->forward('error/permission');
				}
			}
		}
	}
    
     public function slug() {
        //TODO: check if the slug doesn´t exists else put a number at the end
        $str = $_GET['slug'];
        if (isset($str)) {
        	if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        		$str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
        	$str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
        	$str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
        	$str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        	$str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
        	$str = strtolower( trim($str, '-') );
            
            $avoid = array('profile', 'products', 'productos', 'categories', 'categorias', 'carrito', 'cart', 'sitemap', 'contact', 'contacto', 'special', 'ofertas', 'blog', 'pages', 'paginas', 'buscar', 'search', 'pedidos', 'orders', 'mensajes', 'profile', 'pagos', 'payments', 'comentarios', 'reviews');
            
            if (in_array($folder,$avoid)) {
                $str .= '-2';
            }
        
            $slug = false;
            $count = 2;
            while ($slug === false) {
                $query = $this->db->query("SELECT *, COUNT(*) AS total FROM ". DB_PREFIX ."url_alias WHERE `keyword` = '". $str ."'");
                if ($query->row['total'] && $query->row['query'] != html_entity_decode($_GET['query'])) {
                    $str .= $count;
                    $count++;
                    $slug = false;
                } else {
                    $slug = true;
                }
            }
            
            $slug = false;
            $count = 2;
            while ($slug === false) {
                $query = $this->db->query("SELECT COUNT(*) AS total FROM ". DB_PREFIX ."store WHERE `folder` = '". $str ."'");
                if ($query->row['total']) {
                    $str .= '-'.$count;
                    $count++;
                    $slug = false;
                } else {
                    $slug = true;
                }
            }
            
            $return['slug'] = $str;
        } else {
            $return['error'] = 1;
        }
    	print json_encode($return);
    }
}