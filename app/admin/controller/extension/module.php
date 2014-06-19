<?php 
/**
 * ControllerExtensionModule
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
 
class ControllerExtensionModule extends Controller {
	
    /**
     * ControllerExtensionModule::index()
     * 
     * @see Load
     * @see Document
     * @see Response
     * @see Session
     * @see Language
     * @see Load
     * @see Load
     * @return void
     */
    public function index() {
		$this->load->language('extension/module');
		 
		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title'); 

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_import'] = $this->language->get('button_import');
        
		$this->data['insert'] = Url::createAdminUrl("extension/module/insert");
		$this->data['import'] = Url::createAdminUrl("extension/module/import");
        
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('extension/module'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}

		if ($this->session->has('error')) {
			$this->data['error'] = $this->session->get('error');
		
			$this->session->clear('success');
		} else {
			$this->data['error'] = '';
		}

        // SCRIPTS
        $scripts[] = array('id'=>'sortable','method'=>'ready','script'=>
            "$('#gridWrapper').load('". Url::createAdminUrl("extension/module/grid") ."',function(e){
                $('#gridPreloader').hide();
                $('#list tbody').sortable({
                    opacity: 0.6, 
                    cursor: 'move',
                    handle: '.move',
                    update: function() {
                        $.ajax({
                            'type':'post',
                            'dateType':'json',
                            'url':'". Url::createAdminUrl("extension/shipping/sortable") ."',
                            'data': $(this).sortable('serialize'),
                            'success': function(data) {
                                if (data > 0) {
                                    var msj = '<div class=\"success\">Se han ordenado los objetos correctamente</div>';
                                } else {
                                    var msj = '<div class=\"warning\">Hubo un error al intentar ordenar los objetos, por favor intente m&aacute;s tarde</div>';
                                }
                                $('#msg').append(msj).delay(3600).fadeOut();
                            }
                        });
                    }
                }).disableSelection();
                $('.move').css('cursor','move');
            });
                
            $('#formFilter').ntForm({
                lockButton:false,
                ajax:true,
                type:'get',
                dataType:'html',
                url:'". Url::createAdminUrl("extension/module/grid") ."',
                beforeSend:function(){
                    $('#gridWrapper').hide();
                    $('#gridPreloader').show();
                },
                success:function(data){
                    $('#gridPreloader').hide();
                    $('#gridWrapper').html(data).show();
                }
            });");
             
        $this->scripts = array_merge($this->scripts,$scripts);
        
		$this->template = 'extension/module.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
    /**
     * ControllerExtensionModule::grid()
     * 
     * @see Load
     * @see Document
     * @see Response
     * @see Session
     * @see Language
     * @see Load
     * @see Load
     * @return void
     */
    public function grid() {
		$this->load->language('extension/module');
		 
		$filter_name = !empty($this->request->get['filter_name']) ? $this->request->get['filter_name'] : "";
        
        
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_position'] = $this->language->get('column_position');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$extensions = $this->modelExtension->getInstalled('module');
		$this->data['extensions'] = array();
		$modules = glob(DIR_APPLICATION . "controller/module/$filter_name*", GLOB_ONLYDIR);
		if ($modules) {
			foreach ($modules as $module) {
				$extension = basename($module,'plugin.php');
				$this->load->language('module/'. $extension);
				$action = array();
                
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'action' => 'install',
						'img' => 'install.png',
						'text' => $this->language->get('text_install'),
						'href' => Url::createAdminUrl("module/$extension/install")
					);
				} else {
				    if (file_exists(DIR_APPLICATION . "controller/module/$extension/plugin.php")) {
    					$action[] = array(
    						'action' => 'edit',
    						'img' => 'edit.png',
    						'text' => $this->language->get('text_edit'),
    						'href' => Url::createAdminUrl('module/' . $extension . '/plugin')
    					);
				    }
                    $status = $this->config->get($extension . '_status') ? 'activate' : 'desactivate';
					$action[] = array(
						'action' => $status,
						'img' => $status.'.png',
						'text' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
						'href' => Url::createAdminUrl('extension/module/'. $status) . '&extension=' . $extension
					);
					$action[] = array(
						'action' => 'install',
						'img' => 'uninstall.png',
						'text' => $this->language->get('text_uninstall'),
						'href' => Url::createAdminUrl("module/$extension/uninstall")
					);
				}
				
				$this->data['modules'][] = array(
					'module'      => $module,
					'name'        => $this->language->get('heading_title'),
					'status'      => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'action'      => $action
				);
			}
		}
		
		$this->template = 'extension/module_grid.tpl';
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	/**
	 * ControllerExtensionModule::install()
	 * 
     * @see Load
     * @see Redirect
     * @see Session
     * @see Language
     * @see Load
     * @return void
	 */
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->set('error',$this->language->get('error_permission'));
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            
			$this->modelExtension->install('module', $this->request->get['extension']);
			$this->load->auto('user/usergroup');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerExtensionModule::uninstall()
	 * 
     * @see Load
     * @see Redirect
     * @see Session
     * @see Language
     * @see Load
     * @return void
	 */
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {		
			$this->modelExtension->uninstall('module', $this->request->get['extension']);
		
			$this->modelSetting->delete($this->request->get['extension']);
		
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
    
    /**
     * ControllerCatalogCategory::sortable()
     * ordenar el listado actualizando la posici�n de cada objeto
     * @return boolean
     * */
     public function sortable() {
        $this->load->auto('setting/setting');
        $data = array();
        $i = 0;
        foreach ($_POST as $key => $value) {
            if ($key != "tr") {
                $config_key = str_replace("tr_","",$key);
                if (strpos($value,"-")) {
                    $position = substr($value,strpos($value,"-") + 1);
                    $value = str_replace("-","",$value);
                    $value = str_replace("left","",$value);
                    $value = str_replace("right","",$value);
                    $value = str_replace("center","",$value);
                    $config_key .= $value;
                } else {
                    $position = is_array($value) ? $value[0] : $value;
                }
            $data[$i]['group'] = $config_key;
            $data[$i]['position'] = $position;
            } else {
                $position = substr($value,strpos($value,"-") + 1);
                $value = str_replace("-","",$value);
                $value = str_replace("left","",$value);
                $value = str_replace("right","",$value);
                $value = str_replace("center","",$value);
                $data[$i]['group'] = $value;
                $data[$i]['position'] = $position;
            }
            $i++;
        }
        
        $result = $this->modelSetting->sortExtensions($data);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
     }
     
}
