<?php
/**
 * ControllerStyleWidget
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerStyleWidget extends Controller { 
	private $error = array();
  
  	/**
  	 * ControllerStyleWidget::index()
     * 
  	 * @see Load
  	 * @see Document
  	 * @see Language
  	 * @see getList
  	 * @return void
  	 */
  	public function index() {
		$this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');
        
		$extensions = $this->modelExtension->getInstalled('module');
		$this->data['extensions'] = array();
		$modules = glob(DIR_APPLICATION . "controller/module/$filter_name*");
		if ($modules) {
			foreach ($modules as $module) {
                if (!file_exists($module . '/widget.php')) continue; 
				$extension = basename($module,'/widget.php');
				$this->load->language('module/'. $extension);
				$action = array();
                
				if (in_array($extension, $extensions)) {
    				$this->data['modules'][] = array(
    					'widget'      => $extension,
    					'name'        => $this->language->get('heading_title'),
    					'description' => $this->language->get('description')
    				);
                }
			}
		}
		
        /*
        //TODO: model template
        $template_info = $this->modelTemplate->getByName($this->config->get('config_template'));
        $this->setvar('hasColumnLeft',$template_info);
        $this->setvar('hasColumnRight',$template_info);
        $this->setvar('hasFooter',$template_info);
        $this->setvar('hasHeader',$template_info);
        $this->setvar('hasMain',$template_info);
        */
        
        $this->data['hasFeaturedContent'] = true;
        $this->data['hasColumnLeft'] = true;
        $this->data['hasColumnRight'] = true;
        $this->data['hasFooter'] = true;
        //$this->data['hasHeader'] = true;
        $this->data['hasMain'] = true;
        
        $this->data['stores'] = $this->modelStore->getAll();
        $this->data['store_id'] = ($this->request->hasQuery('store_id')) ? $this->request->getQuery('store_id') : 0;
        if ((int)$this->data['store_id']!=0) $this->data['store_exists'] = $this->modelStore->getById($this->data['store_id']);
        if ((int)$this->data['store_id']==0) $this->data['store_exists'] = true;
        
        $data['store_id'] = $this->data['store_id'];
        $this->data['widgets'] = $this->modelWidget->getAll($data);
        
        
  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('style/widget') . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
        
 		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
			$this->session->clear('success');
		} else {
			$this->data['success'] = '';
		}
        
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_ADMIN_CSS;
        $styles[] = array(
            'media'=>'screen',
            'href'=> $csspath .'widgets.css'
        );
        $this->styles = array_merge($this->styles,$styles);
        
		$this->template = 'style/widget.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
  	}
  
    /**
     * ControllerStoreCategory::delete()
     * elimina un objeto
     * @return boolean
     * */
    public function delete() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            foreach ($this->request->post['selected'] as $id) {
                $this->modelWidget->delete($id);
            }
		} else {
            $this->modelWidget->delete($_GET['name']);
		}
    }
    
    /**
     * ControllerStoreCategory::sortable()
     * ordenar el listado actualizando la posición de cada objeto
     * @return boolean
     * */
     public function sortable() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->modelWidget->sortWidget($this->request->post);
        }
     }
}