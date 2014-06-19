<?php  
class ControllerStyleLayouts extends Controller {
	public function index() {
	    $this->load->auto("setting/extension");
        $extensions = $this->modelExtension->getInstalled('module');
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/module/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->load->language('module/' . $extension);
	
				$action = array();
                
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'action' => 'install',
						'img' => 'install.png',
						'text' => $this->language->get('text_install'),
						'href' => Url::createAdminUrl('extension/module/install') . '&extension=' . $extension
					);
				} else {
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
				
				$postion = $pos = $this->config->get($extension . '_position');						
				
				if ($postion) {
					$postion = $this->language->get('text_' . $postion);
				} else {
					$postion = "";
				}
								
				$this->data['extensions'][] = array(
					'extension'   => $extension,
					'name'        => $this->language->get('heading_title'),
					'pos'         => $pos,
					'position'    => $postion,
					'status'      => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order'  => $this->config->get($extension . '_sort_order'),
					'action'      => $action
				);
			}
		}
		
		$this->template = 'style/layouts.tpl';
		
		$this->children = array(
			'common/header2',
			'common/footer2'
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
    
    public function widget() {
        if (!$_GET['widget_id']) $json['error'] = "No se pudo obtener los parametros del widget";
        $json['widget'] = "Facebook Like Box";
        $json['params'][0] = array(
            'input'=>'input',
            'name'=>'profile_id',
            'type'=>'text',
            'label'=>'ID del Perfil',
            'title'=>'Ingrese el ID de su perfil de Facebook'
        );
        $json['params'][1] = array(
            'input'=>'input',
            'name'=>'profile_id',
            'type'=>'text',
            'label'=>'ID del Perfil',
            'title'=>'Ingrese el ID de su perfil de Facebook'
        );
        if (!$json['error']) $json['success'] = 1;
        $json = json_encode($json);
        if (isset($_GET['callback'])) {
            echo $_GET['callback'] . "(". $json .")";
        } else {
            echo $json;
        }
    }
}
