<?php

class ControllerCommonRedirect extends Controller {

    private $error = array();
    public $product_id;

    public function index() {
        $this->load->model('account/customer');
        //$this->modelProduct->webVisited(owner_of_website, who did click to website,'web_visited');
        $this->modelCustomer->webVisited($this->request->getQuery('seller_id'), $this->customer->getId(), 'web_visited');

        if ($this->request->hasQuery('redirect')) {
            $this->redirect($this->request->getQuery('redirect'));
        } else {
            $this->redirect(HTTP_HOME);
        }
    }

    public function blog() {
        $this->load->model('account/customer');
        //$this->modelProduct->webVisited(owner_of_website, who did click to website,'web_visited');
        $this->modelCustomer->blogVisited($this->request->getQuery('seller_id'), $this->customer->getId(), 'web_visited');

        if ($this->request->hasQuery('redirect')) {
            $this->redirect($this->request->getQuery('redirect'));
        } else {
            $this->redirect(HTTP_HOME);
        }
    }

    public function emailsent() {
        $this->load->model('account/customer');
        $this->modelCustomer->customerMailed($this->request->getQuery('seller_id'), $this->customer->getId());
    }

    public function called() {
        $this->load->model('account/customer');
        $this->modelCustomer->customerCalled($this->request->getQuery('seller_id'), $this->customer->getId());
    }

    public function facebook() {
        $this->load->model('account/customer');
        $this->modelCustomer->facebookVisited($this->request->getQuery('seller_id'), $this->customer->getId());
        if ($this->request->hasQuery('redirect')) {
            $this->redirect($this->request->getQuery('redirect'));
        } else {
            $this->redirect(HTTP_HOME);
        }
    }

    public function skype() {
        $this->load->model('account/customer');
        $this->modelCustomer->skypeVisited($this->request->getQuery('seller_id'), $this->customer->getId());
        if ($this->request->hasQuery('redirect')) {
            $this->redirect($this->request->getQuery('redirect'));
        } else {
            $this->redirect(HTTP_HOME);
        }
    }

    public function twitter() {
        $this->load->model('account/customer');
        $this->modelCustomer->twitterVisited($this->request->getQuery('seller_id'), $this->customer->getId());
        if ($this->request->hasQuery('redirect')) {
            $this->redirect($this->request->getQuery('redirect'));
        } else {
            $this->redirect(HTTP_HOME);
        }
    }
}
