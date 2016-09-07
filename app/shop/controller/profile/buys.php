<?php

class ControllerProfileBuys extends Controller {

    public function index() {
        $this->load->auto('account/customer');
        $this->language->load('profile/profile');

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("profile/profile"),
            'text' => $this->language->get('text_profile'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');
        
        $pname = substr($this->request->getQuery('_route_'), 0, strpos($this->request->getQuery('_route_'), '/'));

        $profile = ($this->request->hasQuery('profile_id')) ? $this->request->getQuery('profile_id') : $pname;
        $customer = $this->modelCustomer->getCustomerByProfile($profile);
        
        if (!$customer) {
            $this->error404();
        } else {
        
            $image = $this->modelCustomer->getProperty($customer['customer_id'], 'profile', 'banner');

            $banner = ($image) ? $image : 'no_banner.jpg';
            $photo = ($customer['photo']) ? $customer['photo'] : 'no_image.gif';
            
            $this->data['profile'] = array(
                'firstname' => $customer['firstname'],
                'lastname' => $customer['lastname'],
                'company' => $customer['company'],
                'profile' => $customer['profile'],
                'banner' => NTImage::resizeAndSave($banner, 1100, 300),
                'photo' => NTImage::resizeAndSave($photo, 150, 150),
                'show_controls' => ($this->customer->getId() === $customer['customer_id']),
            );

            $this->loadWidgets();

            if ($scripts)
                $this->scripts = array_merge($this->scripts, $scripts);

            $this->template = 'default/profile/profile.tpl';

            $this->children[] = 'common/nav';
            $this->children[] = 'common/footer';
            $this->children[] = 'common/header';

            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
    }

    public function rateBox() {
        $this->load->auto('json');
        $this->load->auto('account/customer');
        $this->load->auto('account/order');
        $this->load->auto('store/product');
        
        if ($this->request->hasQuery('profile_id')) {
            $model = $this->modelCustomer->getCustomerByProfile($this->request->getQuery('profile_id'));
            if ($model['customer_id']) {
                $data['ratings'] = $this->modelCustomer->getBuyerRatings($model['customer_id']);
                $data['qty_orders'] = $this->modelOrder->getTotalBuysWaitingByCustomerId($model['customer_id']);
                $data['qty_sales'] = $this->modelOrder->getTotalBuysCompleteByCustomerId($model['customer_id']);
                $data['qty_returns'] = $this->modelOrder->getTotalBuysReturnsByCustomerId($model['customer_id']);
                $data['qty_nulled'] = $this->modelOrder->getTotalBuysNulledByCustomerId($model['customer_id']);
            }
        }
        
        $this->response->setOutput(Json::encode($data), $this->config->get('config_compression'));
    }

    protected function loadWidgets() {
        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $csspath = str_replace("%theme%", $this->config->get('config_template'), $csspath);
        } else {
            $csspath = str_replace("%theme%", "default", $csspath);
        }
        if (fopen($csspath . str_replace('controller', '', strtolower(__CLASS__) . '.css'), 'r')) {
            $styles[] = array('media' => 'all', 'href' => $csspath . str_replace('controller', '', strtolower(__CLASS__) . '.css'));
        }
        $styles[] = array('media' => 'all', 'href' => $csspath . 'profileprofile.css');
        if (count($styles)) {
            $this->data['styles'] = $this->styles = array_merge($this->styles, $styles);
        }

        $this->load->helper('widgets');
        $widgets = new NecoWidget($this->registry, $this->Route);
        foreach ($widgets->getWidgets('main') as $widget) {
            $settings = (array) unserialize($widget['settings']);
            if ($settings['asyn']) {
                $url = Url::createUrl("{$settings['route']}", $settings['params']);
                $scripts[$widget['name']] = array(
                    'id' => $widget['name'],
                    'method' => 'ready',
                    'script' =>
                    "$(document.createElement('div'))
                        .attr({
                            id:'" . $widget['name'] . "'
                        })
                        .html(makeWaiting())
                        .load('" . $url . "')
                        .appendTo('" . $settings['target'] . "');"
                );
            } else {
                if (isset($settings['route'])) {
                    if ($settings['autoload'])
                        $this->data['widgets'][] = $widget['name'];
                    $this->children[$widget['name']] = $settings['route'];
                    $this->widget[$widget['name']] = $widget;
                }
            }
        }

        foreach ($widgets->getWidgets('featuredContent') as $widget) {
            $settings = (array) unserialize($widget['settings']);
            if ($settings['asyn']) {
                $url = Url::createUrl("{$settings['route']}", $settings['params']);
                $scripts[$widget['name']] = array(
                    'id' => $widget['name'],
                    'method' => 'ready',
                    'script' =>
                    "$(document.createElement('div'))
                        .attr({
                            id:'" . $widget['name'] . "'
                        })
                        .html(makeWaiting())
                        .load('" . $url . "')
                        .appendTo('" . $settings['target'] . "');"
                );
            } else {
                if (isset($settings['route'])) {
                    if ($settings['autoload'])
                        $this->data['featuredWidgets'][] = $widget['name'];
                    $this->children[$widget['name']] = $settings['route'];
                    $this->widget[$widget['name']] = $widget;
                }
            }
        }
    }

}
