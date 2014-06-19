<?php   
class ControllerCommonCallback extends Controller {
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
	private $r_method ="json";
    
    public function index() {
	   /**
        * 1. verificar las api keys con el usuario
        * 2. verificar si la ip o el dominio no está baneado
        * 3. verificar que el archivo exista
        * 4. verificar que la función o el método exista
        * 5. limpiar todos los datos pasado
        * 6. generar el bloque de código que procesará todo (return array)
        * 7. limpiar datos y devolver a través de json
        * */
  	}
	
    private function modules() {
		$this->data['extensions'] = array();
		$extensions = $this->modelExtension->getInstalled('module');
		$files = glob(DIR_APPLICATION . 'controller/module/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				$this->load->language('module/' . $extension);
				$action = array();
                
				if (in_array($extension, $extensions)) {
					$action[] = array(
						'action' => 'edit',
						'img' => 'edit.png',
						'text' => $this->language->get('text_edit'),
						'href' => Url::createAdminUrl('module/' . $extension . '')
					);		
					$action[] = array(
						'action' => 'install',
						'img' => 'uninstall.png',
						'text' => $this->language->get('text_uninstall'),
						'href' => Url::createAdminUrl('extension/module/uninstall') . '&extension=' . $extension
					);
				}
							
				$this->data['extensions'][] = array(
					'extension'   => $extension,
					'name'        => $this->language->get('heading_title'),
					'desc'        => $this->language->get('overview'),
					'action'      => $action
				);
			}
		}
    }
}