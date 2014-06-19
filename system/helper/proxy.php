<?php
final class NecoWidget { 
    /**
     * @param $public_key
     * API key pública para ser utilizada desde cualquier sitio en internet, con esta llave se autentifica
     * el usuario que está accediendo desde afuera, se verifica el status del usuario y se registra para hacer seguimientos
     * user tracker
     * */
	private $public_key = null;
    
    /**
     * @param $private_key
     * API key privada para ser utilizada solo por el usuario, esta llave será utilizada para mostrar información privada del usuario
     * y alterar los registros del usuario desde afuera, de tal manera que se puedan manipular objetos a través de otros sitios
     * */
	private $private_key = null;
    
    /**
     * @param $action
     * Es el nombre de la acción que se va a ejecutar, básicamente es el nombre del archivo que contiene las funciones
     * necesarias para manipular la información
     * */
	private $action;
    
    /**
     * @param $method
     * Es el método que se va a utilizar para hacer el llamado de la función, si es una clase o un conjunto de funciones
     * */
	private $method = "class";
    
    /**
     * @param $funcname
     * Es el nombre de la función que se va a utilizar
     * */
	private $funcname = "default";
    
    /**
     * @param $funcargs
     * Son los parámetros o los argumentos que se van a pasar a la función
     * */
	private $funcargs = array();
    
    /**
     * @param $r_method
     * Es el método en el que se va a retornar la información
     * */
	private $r_method = "json";
    
    /**
     * @param $ip_banned
     * Utilizado para saber cuando la dirección ip está bloqueada
     * */
    private $ip_banned = false;
    
    /**
     * @param $ip
     * Dirección IP del solicitante
     * */
    private $ip = null;
    
    /**
     * @param $domain_banned
     * Utilizado para saber cuando la dirección web está bloqueada
     * */
    private $domain_banned = false;
    
    /**
     * @param $user_banned
     * Utilizado para saber cuando usuario está bloqueado
     * */
    private $user_banned = false;
    
    private $db;
    private $user;
    private $resgistry;
    private $data = array();
    private $result = array();
    
    

    //TODO: construir un mapa de todas las funciones llamables a través de ajax
  	public function __construct($registry) {
        $this->registry = $registry;
        $this->user = $this->registry->get('user');
        $this->db = $this->registry->get('db');
        $this->ip = $_SERVER['REMOTE_ADDR'];
        if (!$this->check("action",$this->action)) $this->error = 100;
        if (!$this->check("funcname",$this->funcname)) $this->error = 101;
        if ($this->error) return $this->fail();
        
        return $this->result();
  	}
    
    public function __get($key) {
        return $this->data[$key];
    }
    
    public function __set($key,$value) {
        $this->data[$key] = $value;
    }
    
    public function getAction() {
        return $this->action;
    }
    
    public function setAction($action) {
        $this->action = $action;
    }
    
    public function getFuncName() {
        return $this->funcname;
    }
    
    public function setFuncName($funcname) {
        $this->funcname = $funcname;
    }
    
    private function check($varname,$value) {
        if (is_callable($varname)) {
            return $this->$varname($value);
        }
    }
    
    private function action($action) {
        $file = DIR_SYSTEM . "library" . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . $action . ".php";
        if (!is_file($file)) {
            require_once($file);
            $class = ucfirst($action);
            $this->class = new $class($this->registry);
            return true;
        }
        return false;
    }
    
    private function funcname($funcname) {
        return method_exists($this->class,$funcname);
    }
    
    private function fail() {
        if (!$this->error) return true;
        if (!is_numeric($this->error)) return false;
        switch ($this->error) {
            default: 
                $this->result['error_type'] = 0;
                $this->result['error_msg'] = "Hubo un error al intentar procesar la informaci&oacute;n. El error es desconocido, por favor p&oacute;ngase en contacto con su proveedor del Software";
                break;
            case 100: 
                $this->result['error_type'] = 100;
                $this->result['error_msg'] = "No se pudo cargar el fichero {$this->action} o el fichero no existe";
                break;
            case 101:
                $this->result['error_type'] = 101;
                $this->result['error_msg'] = "La funci&oacute;n {$this->funcname} a la que se est&aacute; intentando llamar, no existe.";
                break;
        }
    }
}
