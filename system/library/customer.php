<?php
final class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private	$rif;
	private	$company;
	private	$photo;
	private	$birthday;
	private	$blog;
	private	$website;
	private	$telephone;
	private	$profesion;
	private	$titulo;
	private	$msn;
	private	$gmail;
	private	$yahoo;
	private	$skype;
	private	$facebook;
	private	$twitter;
	private	$complete;
	private	$sex;
	private	$customer_group_id;
	private	$address_id;
	private	$canPublish;
	private	$canBuy;
	private	$canAsk;
	private	$banned;
    
    private $key = "946VBA4kg84tbsdowyJF63KGUn4f3mj32nci34JQ53Ejnejs";
	private $registry;
    
	public $skey; //utilizado para verificar la sesi�n del usuario
	
  	public function __construct($registry) {
		$this->config  = $registry->get('config');
		$this->db      = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->registry= $registry;
		$this->skey    = md5($this->session->get('token')).$this->key."_".$this->getId();
		//if ($this->validSession()) { 
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->get('customer_id') . "' AND status = '1'");
			
			if ($customer_query->num_rows) {
				$this->customer_id   = $customer_query->row['customer_id'];
				$this->firstname     = $customer_query->row['firstname'];
				$this->lastname      = $customer_query->row['lastname'];
				$this->email         = $customer_query->row['email'];
    			$this->rif           = $customer_query->row['rif'];
    			$this->company       = $customer_query->row['company'];
    			$this->photo         = $customer_query->row['photo'];
    			$this->birthday      = $customer_query->row['birthday'];
    			$this->blog          = $customer_query->row['blog'];
    			$this->website       = $customer_query->row['website'];
    			$this->telephone     = $customer_query->row['telephone'];
    			$this->profesion     = $customer_query->row['profesion'];
    			$this->titulo        = $customer_query->row['titulo'];
    			$this->msn           = $customer_query->row['msn'];
    			$this->gmail         = $customer_query->row['gmail'];
    			$this->yahoo         = $customer_query->row['yahoo'];
    			$this->skype         = $customer_query->row['skype'];
    			$this->facebook      = $customer_query->row['facebook'];
    			$this->twitter       = $customer_query->row['twitter'];
    			$this->complete      = $customer_query->row['complete'];
    			$this->sex          = $customer_query->row['sex'];
    			$this->customer_group_id = $customer_query->row['customer_group_id'];
    			$this->address_id    = $customer_query->row['address_id'];
    			$this->canPublish    = $customer_query->row['can_publish'];
    			$this->canBuy    = $customer_query->row['can_buy'];
    			$this->canAsk    = $customer_query->row['can_ask'];
    			$this->banned    = $customer_query->row['banned'];
                
                $this->skey=$this->session->get('skey');
							
      		} else {
				$this->logout();
			}
  		//}
	}
    
    public function validSession() {        
        if (!$this->session->has('customer_id') || !$this->session->has('skey') || ($this->session->get('skey') != $this->skey)) return false;
        //TODO: verificar la llave de la sesi�n del usuario
        $customer_id = substr($this->session->get('skey'),strpos($this->session->get('skey'),'_')+1);
        if ($this->session->get('customer_id') != $customer_id) return false;
        return true;
    }
		
  	public function login($email, $password, $hash=true) {
        if (empty($email) || empty($password)) return false;
  	     if ($hash) {
  	         $password = md5($password);
  	     }
		if (!$this->config->get('config_customer_approval')) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE 
            email = '" . $this->db->escape($email) . "' 
            AND password = '" . $this->db->escape($password) . "' 
            AND status = '1'
            AND banned = '0'");
		} else {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE 
            email = '" . $this->db->escape($email) . "' 
            AND password = '" . $this->db->escape($password) . "' 
            AND status = '1' 
            AND banned = '0'
            AND approved = '1'");
		}
		
		if ($customer_query->num_rows) {
			$this->session->set('customer_id',$customer_query->row['customer_id']);
            $tk = $this->session->has('token') ? $this->session->get('token') : strtotime(date('d-m-Y h:i:s')) . mt_rand(1000000000,9999999999);
            $this->session->set('token',$tk);
			$this->skey = md5($this->session->get('token')).$this->key."_".$customer_query->row['customer_id'];	
			$this->session->set('skey',$this->skey);	
            
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->get('cart'))) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id   = $customer_query->row['customer_id'];
			$this->firstname     = $customer_query->row['firstname'];
			$this->lastname      = $customer_query->row['lastname'];
			$this->email         = $customer_query->row['email'];
			$this->rif           = $customer_query->row['rif'];
			$this->company       = $customer_query->row['company'];
			$this->photo         = $customer_query->row['photo'];
			$this->birthday      = $customer_query->row['birthday'];
			$this->blog          = $customer_query->row['blog'];
			$this->website       = $customer_query->row['website'];
			$this->profesion     = $customer_query->row['profesion'];
			$this->titulo        = $customer_query->row['titulo'];
			$this->msn           = $customer_query->row['msn'];
			$this->gmail         = $customer_query->row['gmail'];
			$this->yahoo         = $customer_query->row['yahoo'];
			$this->skype         = $customer_query->row['skype'];
			$this->facebook      = $customer_query->row['facebook'];
			$this->twitter       = $customer_query->row['twitter'];
			$this->complete      = $customer_query->row['complete'];
            $this->telephone     = $customer_query->row['telephone'];
			$this->sex          = $customer_query->row['sex'];
			$this->newsletter    = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id    = $customer_query->row['address_id'];
    			$this->canPublish    = $customer_query->row['can_publish'];
    			$this->canBuy    = $customer_query->row['can_buy'];
    			$this->canAsk    = $customer_query->row['can_ask'];
    			$this->banned    = $customer_query->row['banned'];
      
	  		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function loginWithGoogle($data) {
        if (empty($data['google_oauth_id']) || empty($data['email'])) return false;
        $sql = "SELECT * 
        FROM ". DB_PREFIX ."customer 
        WHERE google_oauth_id = '". $this->db->escape($data['google_oauth_id']) ."' 
        AND email = '". $this->db->escape($data['email']) ."'
        AND status = '1'
        AND banned = '0'";
        
		$customer_query = $this->db->query($sql);
		
		if ($customer_query->num_rows) {
			$this->session->set('customer_id',$customer_query->row['customer_id']);
            $tk = $this->session->has('token') ? $this->session->get('token') : strtotime(date('d-m-Y h:i:s')) . mt_rand(1000000000,9999999999);
            $this->session->set('token',$tk);
			$this->skey = md5($this->session->get('token')).$this->key."_".$customer_query->row['customer_id'];	
			$this->session->set('skey',$this->skey);	
            
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->get('cart'))) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id   = $customer_query->row['customer_id'];
			$this->firstname     = $customer_query->row['firstname'];
			$this->lastname      = $customer_query->row['lastname'];
			$this->email         = $customer_query->row['email'];
			$this->rif           = $customer_query->row['rif'];
			$this->company       = $customer_query->row['company'];
			$this->photo          = $customer_query->row['photo'];
			$this->birthday      = $customer_query->row['birthday'];
			$this->blog          = $customer_query->row['blog'];
			$this->website       = $customer_query->row['website'];
			$this->profesion     = $customer_query->row['profesion'];
			$this->titulo        = $customer_query->row['titulo'];
			$this->msn           = $customer_query->row['msn'];
			$this->gmail         = $customer_query->row['gmail'];
			$this->yahoo         = $customer_query->row['yahoo'];
			$this->skype         = $customer_query->row['skype'];
			$this->facebook      = $customer_query->row['facebook'];
			$this->twitter       = $customer_query->row['twitter'];
			$this->complete      = $customer_query->row['complete'];
            $this->telephone     = $customer_query->row['telephone'];
			$this->sex           = $customer_query->row['sex'];
			$this->newsletter    = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id    = $customer_query->row['address_id'];
    			$this->canPublish    = $customer_query->row['can_publish'];
    			$this->canBuy    = $customer_query->row['can_buy'];
    			$this->canAsk    = $customer_query->row['can_ask'];
    			$this->banned    = $customer_query->row['banned'];
	  		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function loginWithLive($data) {
        if (empty($data['live_oauth_id']) || empty($data['email'])) return false;
        $sql = "SELECT * 
        FROM ". DB_PREFIX ."customer 
        WHERE live_oauth_id = '". $this->db->escape($data['live_oauth_id']) ."' 
        AND email = '". $this->db->escape($data['email']) ."'
        AND status = '1'
        AND banned = '0'";
        
		$customer_query = $this->db->query($sql);
		
		if ($customer_query->num_rows) {
			$this->session->set('customer_id',$customer_query->row['customer_id']);
            $tk = $this->session->has('token') ? $this->session->get('token') : strtotime(date('d-m-Y h:i:s')) . mt_rand(1000000000,9999999999);
            $this->session->set('token',$tk);
			$this->skey = md5($this->session->get('token')).$this->key."_".$customer_query->row['customer_id'];	
			$this->session->set('skey',$this->skey);	
            
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->get('cart'))) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id   = $customer_query->row['customer_id'];
			$this->firstname     = $customer_query->row['firstname'];
			$this->lastname      = $customer_query->row['lastname'];
			$this->email         = $customer_query->row['email'];
			$this->rif           = $customer_query->row['rif'];
			$this->company       = $customer_query->row['company'];
			$this->photo          = $customer_query->row['photo'];
			$this->birthday    = $customer_query->row['birthday'];
			$this->blog          = $customer_query->row['blog'];
			$this->website       = $customer_query->row['website'];
			$this->profesion     = $customer_query->row['profesion'];
			$this->titulo        = $customer_query->row['titulo'];
			$this->msn           = $customer_query->row['msn'];
			$this->gmail         = $customer_query->row['gmail'];
			$this->yahoo         = $customer_query->row['yahoo'];
			$this->skype         = $customer_query->row['skype'];
			$this->facebook      = $customer_query->row['facebook'];
			$this->twitter       = $customer_query->row['twitter'];
			$this->complete      = $customer_query->row['complete'];
            $this->telephone     = $customer_query->row['telephone'];
			$this->sex          = $customer_query->row['sex'];
			$this->newsletter    = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id    = $customer_query->row['address_id'];
    			$this->canPublish    = $customer_query->row['can_publish'];
    			$this->canBuy    = $customer_query->row['can_buy'];
    			$this->canAsk    = $customer_query->row['can_ask'];
    			$this->banned    = $customer_query->row['banned'];
	  		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function loginWithTwitter($data) {
		$customer_query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "customer 
        WHERE twitter_oauth_id = '" . $this->db->escape($data['oauth_id']) . "' 
        AND twitter_oauth_token_secret = '" . $this->db->escape($data['oauth_token_secret']) . "' 
        AND company = '" . $this->db->escape($data['company']) . "' 
        AND status = '1'
        AND banned = '0'");
		
		if ($customer_query->num_rows) {
			$this->session->set('customer_id',$customer_query->row['customer_id']);
            $tk = $this->session->has('token') ? $this->session->get('token') : strtotime(date('d-m-Y h:i:s')) . mt_rand(1000000000,9999999999);
            $this->session->set('token',$tk);
			$this->skey = md5($this->session->get('token')).$this->key."_".$customer_query->row['customer_id'];	
			$this->session->set('skey',$this->skey);	
            
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->get('cart'))) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id   = $customer_query->row['customer_id'];
			$this->firstname     = $customer_query->row['firstname'];
			$this->lastname      = $customer_query->row['lastname'];
			$this->email         = $customer_query->row['email'];
			$this->rif           = $customer_query->row['rif'];
			$this->company       = $customer_query->row['company'];
			$this->photo          = $customer_query->row['photo'];
			$this->birthday    = $customer_query->row['birthday'];
			$this->blog          = $customer_query->row['blog'];
			$this->website       = $customer_query->row['website'];
			$this->profesion     = $customer_query->row['profesion'];
			$this->titulo        = $customer_query->row['titulo'];
			$this->msn           = $customer_query->row['msn'];
			$this->gmail         = $customer_query->row['gmail'];
			$this->yahoo         = $customer_query->row['yahoo'];
			$this->skype         = $customer_query->row['skype'];
			$this->facebook      = $customer_query->row['facebook'];
			$this->twitter       = $customer_query->row['twitter'];
			$this->complete      = $customer_query->row['complete'];
            $this->telephone     = $customer_query->row['telephone'];
			$this->sex          = $customer_query->row['sex'];
			$this->newsletter    = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id    = $customer_query->row['address_id'];
    			$this->canPublish    = $customer_query->row['can_publish'];
    			$this->canBuy    = $customer_query->row['can_buy'];
    			$this->canAsk    = $customer_query->row['can_ask'];
    			$this->banned    = $customer_query->row['banned'];
      
	  		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function loginWithFacebook($data) {
		$customer_query = $this->db->query("SELECT * 
        FROM " . DB_PREFIX . "customer 
        WHERE facebook_oauth_id = '" . $this->db->escape($data['oauth_id']) . "' 
        AND email = '" . $this->db->escape($data['email']) . "' 
        AND status = '1'
        AND banned = '0'");
		
		if ($customer_query->num_rows) {
			$this->session->set('customer_id',$customer_query->row['customer_id']);
            $tk = $this->session->has('token') ? $this->session->get('token') : strtotime(date('d-m-Y h:i:s')) . mt_rand(1000000000,9999999999);
            $this->session->set('token',$tk);
			$this->skey = md5($this->session->get('token')).$this->key."_".$customer_query->row['customer_id'];	
			$this->session->set('skey',$this->skey);	
            
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->get('cart'))) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id   = $customer_query->row['customer_id'];
			$this->firstname     = $customer_query->row['firstname'];
			$this->lastname      = $customer_query->row['lastname'];
			$this->email         = $customer_query->row['email'];
			$this->rif           = $customer_query->row['rif'];
			$this->company       = $customer_query->row['company'];
			$this->photo          = $customer_query->row['photo'];
			$this->birthday    = $customer_query->row['birthday'];
			$this->blog          = $customer_query->row['blog'];
			$this->website       = $customer_query->row['website'];
			$this->profesion     = $customer_query->row['profesion'];
			$this->titulo        = $customer_query->row['titulo'];
			$this->msn           = $customer_query->row['msn'];
			$this->gmail         = $customer_query->row['gmail'];
			$this->yahoo         = $customer_query->row['yahoo'];
			$this->skype         = $customer_query->row['skype'];
			$this->facebook      = $customer_query->row['facebook'];
			$this->twitter       = $customer_query->row['twitter'];
			$this->complete      = $customer_query->row['complete'];
            $this->telephone     = $customer_query->row['telephone'];
			$this->sex          = $customer_query->row['sex'];
			$this->newsletter    = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id    = $customer_query->row['address_id'];
    			$this->canPublish    = $customer_query->row['can_publish'];
    			$this->canBuy    = $customer_query->row['can_buy'];
    			$this->canAsk    = $customer_query->row['can_ask'];
    			$this->banned    = $customer_query->row['banned'];
      
	  		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function logout() {
		$this->session->clear('customer_id');
		$this->session->clear('skey');
		$this->session->clear('token');

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->rif = '';
		$this->company = '';
		$this->photo = '';
		$this->birthday = '';
		$this->blog = '';
		$this->website = '';
		$this->profesion = '';
		$this->titulo = '';
		$this->msn = '';
		$this->gmail = '';
		$this->yahoo = '';
		$this->skype = '';
		$this->facebook = '';
		$this->twitter = '';
		$this->telephone = '';
		$this->sex = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
		$this->canPublish = '';
		$this->canBuy    = '';
		$this->canAsk    = '';
		$this->banned    = '';
  	}
  
  	public function isLogged() {
    	return $this->customer_id;
  	}
    
    public function activateUser($codigo) {
        $result = $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE activation_code = '" . $this->db->escape($codigo) . "'");
            return $result;
    }
    
    public function setComplete() {
        $result = $this->db->query("UPDATE " . DB_PREFIX . "customer SET complete= '1' WHERE customer_id = '" . (int)$this->getId() . "'");
            return $result;
    }
    
  	public function getId() {
    	return $this->customer_id;
  	}
      
  	public function getFirstName() {
		return $this->firstname;
  	}
  
  	public function getLastName() {
		return $this->lastname;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getRif() {
		return $this->rif;
  	}
  
  	public function getCompany() {
		return $this->company;
  	}
  
  	public function getPhoto() {
		return $this->photo;
  	}
  
  	public function getBirthday() {
		return $this->birthday;
  	}
  
  	public function getBlog() {
		return $this->blog;
  	}
  
  	public function getWebsite() {
		return $this->website;
  	}
  
  	public function getProfesion() {
		return $this->profesion;
  	}
  
  	public function getTitulo() {
		return $this->titulo;
  	}
  
  	public function getMsn() {
		return $this->msn;
  	}
  
  	public function getGmail() {
		return $this->gmail;
  	}
  
  	public function getYahoo() {
		return $this->yahoo;
  	}
  
  	public function getSkype() {
		return $this->skype;
  	}
  
  	public function getFacebook() {
		return $this->facebook;
  	}
  
  	public function getTwitter() {
		return $this->twitter;
  	}
    
    public function getComplete() {
        return $this->complete;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}
    
    public function emailExist($email) {
        $correo = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE email = '".$this->db->escape($email)."'");
        return $correo;
  	}
  
  	public function getNewsletter() {
		return $this->newsletter;	
  	}

  	public function canPublish() {
		return $this->canPublish;	
  	}

  	public function canBuy() {
		return $this->canBuy;	
  	}

  	public function canAsk() {
		return $this->canAsk;	
  	}

  	public function isBanned() {
		return $this->banned;	
  	}

  	public function getCustomerGroupId() {
		return $this->customer_group_id;	
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}
}