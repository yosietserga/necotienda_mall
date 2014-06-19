<?php 
class ControllerCommonHeader extends Controller {
	/**
	 * ControllerCommonHeader::index()
	 * 
	 * @return
	 */
	protected function index() {
		$this->load->language('common/header');
		$this->data['title']          = $this->document->title . " | " . $this->config->get("config_title");
		$this->data['base']           = HTTP_HOME;
		$this->data['charset']        = $this->language->get('charset');
		$this->data['lang']           = $this->language->get('code');	
		$this->data['direction']      = $this->language->get('direction');
		$this->data['links']          = $this->document->links;	
		$this->data['styles']         = $this->document->styles;
		$this->data['scripts']        = $this->document->scripts;
		$this->data['breadcrumbs']    = $this->document->breadcrumbs;
		$this->data['heading_title']  = $this->language->get('heading_title');
		
        $this->load->library('browser');
        $browser = new Browser;
        if ($browser->getBrowser() == 'Internet Explorer' && $browser->getVersion() <= 8) {
            $this->redirect(Url::createUrl("page/deprecated", null, 'NONSSL', HTTP_CATALOG));
        }
       
		if (!$this->user->validSession()) {
			$this->data['logged'] = '';
			$this->data['home'] = Url::createAdminUrl('common/login');
		} else {
			$this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
            
            $this->load->library('update');
            $update = new Update($this->registry);
            $update_info = $update->getInfo();
            if (version_compare(VERSION,$update_info['version'],'<')) {
                $this->data['msg'] = "Hay una nueva versi&oacute;n disponible, Para instalarla haz click <a href=\"". Url::createAdminUrl("tool/update") ."\" title=\"Actualizar\">aqu&iacute;</a>";
            }
            
            if ($this->session->has('success')) {
                $this->data['success'] = $this->session->get('success');
                $this->session->clear('success');
            }
            
            if ($this->session->has('error')) {
                $this->data['error'] = $this->session->get('error');
                $this->session->clear('error');
            }
            
            $this->load->auto('sale/customer');
            $this->load->auto('sale/order');
            $this->load->auto('store/review');
            $this->load->auto("setting/store");
            
            $this->data['new_customers']= $this->modelCustomer->getAllTotalAwaitingApproval();
            $this->data['new_reviews']  = $this->modelReview->getAllTotalAwaitingApproval();
            $this->data['new_orders']   = $this->modelOrder->getAllTotalWithoutInvoice();
            $this->data['stores']       = $this->modelStore->getAll();
		}
        
        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_ADMIN_CSS;
        $styles[] = array('media'=>'all','href'=>$csspath.'normalize.min.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'chosen.min.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'reset.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'text.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'screen.css');
        $styles[] = array('media'=>'all','href'=>$csspath.'main.css');
        if ($styles) $this->styles = $this->data['styles'] = array_merge($this->styles,$styles);
        
		$this->id       = 'header';
		$this->template = 'common/header.tpl';
		
		$this->render();
	}
}