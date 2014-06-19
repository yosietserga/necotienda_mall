<?php 
class ControllerMarketingCampaign extends Controller {
	public function index() {
	   $this->trace();
	   $this->redirect(HTTP_HOME);
	}
    
	public function trace() {
        header('Cache-Control: no-cache');
        header('Content-type: image/gif');
        header('Content-length: 43');
        if ($this->request->hasQuery('campaign_id')) {
            $this->load->model('marketing/campaign');
            $this->modelCampaign->trackEmail($this->request->getQuery('campaign_id'), $this->request->getQuery('contact_id'));
        }
        echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
	}
    
	public function link() {
        if ($this->request->hasQuery('campaign_id')) {
            $this->load->model('marketing/campaign');
            $this->modelCampaign->trackLink($this->request->getQuery('campaign_id'), $this->request->getQuery('contact_id'), $this->request->getQuery('link_index'));
            $redirectTo = $this->modelCampaign->getLink($this->request->getQuery('link_index'));
            if ($redirectTo) {
                $this->redirect($redirectTo);
            } else {
                $this->redirect(HTTP_HOME);
            }
        }
	}
}