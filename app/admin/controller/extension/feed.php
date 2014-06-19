<?php
/**
 * ControllerExtensionFeed
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerExtensionFeed extends Controller {
	/**
	 * ControllerExtensionFeed::index()
	 * 
     * @see Load
     * @see Language
     * @see Document
     * @see Session
     * @see Response
	 * @return void
	 */
	public function index() {
		$this->load->language('extension/feed');
		 
		$this->document->title = $this->language->get('heading_title'); 

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('extension/feed'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

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

		$extensions = $this->modelExtension->getInstalled('feed');
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/feed/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
			
				$this->load->language('feed/' . $extension);

				$action = array();
			
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'action' => 'install',
						'img' => 'install.png',
						'text' => $this->language->get('text_install'),
						'href' => Url::createAdminUrl('extension/feed/install') . '&extension=' . $extension
					);
				} else {
					$action[] = array(
						'action' => 'edit',
						'img' => 'edit.png',
						'text' => $this->language->get('text_edit'),
						'href' => Url::createAdminUrl('feed/' . $extension . '')
					);		
					$action[] = array(
						'action' => 'install',
						'img' => 'uninstall.png',
						'text' => $this->language->get('text_uninstall'),
						'href' => Url::createAdminUrl('extension/feed/uninstall') . '&extension=' . $extension
					);
				}
                
                		
				$this->data['extensions'][] = array(
					'name'   => $this->language->get('heading_title'),
					'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'action' => $action
				);
			}
		}
		
		$this->template = 'extension/feed.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
	
	/**
	 * ControllerExtensionFeed::install()
	 * 
     * @see Load
     * @see Language
     * @see Document
     * @see Session
     * @see Response
	 * @return void
	 */
	public function install() {
    	if (!$this->user->hasPermission('modify', 'extension/feed')) {
      		$this->session->set('error',$this->language->get('error_permission')); 
			
			$this->redirect(Url::createAdminUrl('extension/feed'));
    	} else {
			$this->modelExtension->install('feed', $this->request->get['extension']);
		
			$this->load->auto('user/usergroup');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'feed/' . $this->request->get['extension']);
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'feed/' . $this->request->get['extension']);
		
			$this->redirect(Url::createAdminUrl('extension/feed'));			
		}
	}
	
	/**
	 * ControllerExtensionFeed::uninstall()
	 * 
     * @see Load
     * @see Language
     * @see Document
     * @see Session
     * @see Response
	 * @return void
	 */
	public function uninstall() {
    	if (!$this->user->hasPermission('modify', 'extension/feed')) {
      		$this->session->set('error',$this->language->get('error_permission')); 
			
			$this->redirect(Url::createAdminUrl('extension/feed'));
    	} else {		
			$this->modelExtension->uninstall('feed', $this->request->get['extension']);
		
			$this->modelSetting->delete($this->request->get['extension']);
		
			$this->redirect(Url::createAdminUrl('extension/feed'));
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
