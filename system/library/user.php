<?php
final class User {
	private $user_id;
	private $username;
  	private $permission = array();
	public $key = "H7I937HndFJE483847b3848GB4389R546:df3834ibOWFsjNEUGdb4HB3v84u2BH2ybt"; 
	public $ukey; //utilizado para verificar la sesión del usuario

  	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
        
    	if ($this->validSession()) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->get('user_id') . "'");
			
            $this->ukey=$this->session->get('ukey');
            
			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				
      			$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->get('user_id') . "'");
                //TODO: crear funcion que registre todos los datos de cada acceso ($_SERVER,ref,action,$_POST,$_GET,$_SESSION)
                //TODO: autodetectar si la url es la creacion/edición/eliminacion/activacion de un objeto
      			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
				
	  			foreach (unserialize($user_group_query->row['permission']) as $key => $value) {
	    			$this->permission[$key] = $value;
	  			}
			} else {
				$this->logout();
			}
    	} else {
			$this->logout();
		}
  	}
		
    public function validSession() {        
        if (!$this->session->has('user_id')) return false;
        if (!$this->session->has('ukey')) return false;
        if ($this->session->get('nttoken') != $this->key . $this->session->get('utoken')) return false;
        $user_id = substr($this->session->get('ukey'),strpos($this->session->get('ukey'),'_')+1);
        if ($this->session->get('user_id') != $user_id) return false;
        return true;
    }
		
  	public function login($username, $password, $hash=true) {
        if ($hash) {
  	         $password = md5($password);
  	    }
        
    	$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "'");
        

    	if ($user_query->num_rows) {
			$this->session->set('user_id',$user_query->row['user_id']);
            $utoken = $this->session->has('utoken') ? $this->session->get('utoken') : md5(date('d-m-Y').mt_rand(1000000000,9999999999));
            $this->session->set('utoken',$utoken);
			$this->ukey = md5($this->key) . ":" . $this->session->get('utoken') ."_".$user_query->row['user_id'];
            $this->session->set('token',$this->ukey);
            $this->session->set('nttoken',$this->key . $this->session->get('utoken'));
			$this->session->set('ukey',$this->ukey);
            
			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];			

      		$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

	  		foreach (unserialize($user_group_query->row['permission']) as $key => $value) {
	    		$this->permissions[$key] = $value;
	  		}
		
      		return true;
    	} else {
      		return false;
    	}
  	}

  	public function logout() {
		$this->session->clear('user_id');
		$this->session->clear('token');
		$this->session->clear('utoken');
		$this->session->clear('nttoken');
		$this->session->clear('ukey');
        foreach ($_GET as $arg => $value) {
            if ($arg == 'token') unset($_GET[$arg]);
        }
        
		$this->user_id = '';
		$this->username = '';
  	}

  	public function hasPermission($key, $value) {
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return false;
		}
  	}
  
  	public function isLogged() {
    	return $this->user_id;
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}	
}
