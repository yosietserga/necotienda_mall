<?php

class ControllerProfileProfile extends Controller {

    public function index() {
        $this->load->auto('account/customer');
        $this->language->load('profile/profile');
        $Url = new Url($this->registry);
        
        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => $Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );
        $this->document->breadcrumbs[] = array(
            'href' => $Url::createUrl("profile/profile"),
            'text' => $this->language->get('text_profile'),
            'separator' => $this->language->get('text_separator')
        );
        
        $profile = ($this->request->hasQuery('pid')) ? $this->request->getQuery('pid') : $this->request->getQuery('_route_');
        $customer = $this->modelCustomer->getCustomerByProfile($profile);
        
        if ($this->customer->isLogged() && $this->customer->getProfile() === $profile) {
            $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title') .' '. $profile;
        } else {
            $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title2') .' '. $profile;
        }
        
        $this->session->set('redirect', $Url::createUrl('profile/profile', array('profile_id'=>$profile)));
        
        if (!$customer) {
            $this->error404();
        } else {
        
            $image = $this->modelCustomer->getProperty($customer['customer_id'], 'profile', 'banner');
            
            $banner = ($image) ? $image : 'no_banner.jpg';
            $photo = ($customer['photo']) ? $customer['photo'] : 'no_image.gif';
            
            $activities = $this->modelCustomer->getActivities(
                    array(
                        'customer_id'=>$customer['customer_id'],
                    )
            );
            
            $this->data['profile'] = array(
                'firstname' => $customer['firstname'],
                'lastname' => $customer['lastname'],
                'company' => $customer['company'],
                'profile' => $customer['profile'],
                'banner' => NTImage::resizeAndSave($banner, 1100, 300),
                'photo' => NTImage::resizeAndSave($photo, 150, 150),
                'image' => $customer['photo'],
                'show_controls' => ($this->customer->getId() === $customer['customer_id']),
                'activities' => $activities,
            );
            
            $this->data['Image'] = NTImage;
            
            $this->loadWidgets();
            
            $scripts[] = array('id'=>'profilejs', 'method'=>'ready', 'script'=>
                "var profileInfo = App.getInfo('". $customer['profile'] ."');"
                . "var profileSales = App.getSellerInfo('". $customer['profile'] ."');"
                . "var profileBuys = App.getBuyerInfo('". $customer['profile'] ."');"
                . "$('small.date').each(function(){"
                .   "dt = $(this).html();"
                .   "$(this).html( moment(dt, 'DD-MM-YYYY').fromNow() );"
                . "});"
            );
            
            if ($scripts)
                $this->scripts = array_merge($this->scripts, $scripts);
            
            $javascripts[] = HTTP_JS . 'vendor/moment/moment.min.js';
            $javascripts[] = HTTP_JS . 'vendor/moment/es.min.js';
            $this->javascripts = array_merge($this->javascripts, $javascripts);
            
            $this->template = 'default/profile/profile.tpl';

            $this->children[] = 'common/nav';
            $this->children[] = 'common/footer';
            $this->children[] = 'common/header';

            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
    }

    protected function error404() {
        $Url = new Url($this->registry);
        $this->document->breadcrumbs[] = array(
            'href' => $Url::createUrl('profile/profile', array('profile_id'=>$profile)),
            'text' => $this->language->get('text_error'),
            'separator' => $this->language->get('text_separator')
        );
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        $this->document->title = $this->data['heading_title'] = $this->language->get('text_error');

        $this->loadWidgets();

        if ($scripts)
            $this->scripts = array_merge($this->scripts, $scripts);

        $template = ($this->config->get('default_view_product_error')) ? $this->config->get('default_view_product_error') : 'error/not_found.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/' . $template)) {
            $this->template = $this->config->get('config_template') . '/' . $template;
        } else {
            $this->template = 'choroni/' . $template;
        }

        $this->children[] = 'common/column_left';
        $this->children[] = 'common/column_right';
        $this->children[] = 'common/nav';
        $this->children[] = 'common/header';
        $this->children[] = 'common/footer';

        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    protected function loadWidgets() {
        $Url = new Url($this->registry);
        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        $jspath = defined("CDN") ? CDN_JS : HTTP_THEME_JS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $csspath = str_replace("%theme%", $this->config->get('config_template'), $csspath);
            $cssFolder = str_replace("%theme%", $this->config->get('config_template'), DIR_THEME_CSS);

            $jspath = str_replace("%theme%", $this->config->get('config_template'), $jspath);
            $jsFolder = str_replace("%theme%", $this->config->get('config_template'), DIR_THEME_JS);
        } else {
            $csspath = str_replace("%theme%", "default", $csspath);
            $cssFolder = str_replace("%theme%", "default", DIR_THEME_CSS);

            $jspath = str_replace("%theme%", "default", $jspath);
            $jsFolder = str_replace("%theme%", "default", DIR_THEME_JS);
        }

        $styles[] = array('media' => 'all', 'href' => 'http://fonts.googleapis.com/css?family=Lobster');
        if (file_exists($cssFolder . str_replace('controller', '', strtolower(__CLASS__) . '.css'))) {
            $styles[] = array('media' => 'all', 'href' => $csspath . str_replace('controller', '', strtolower(__CLASS__) . '.css'));
        }

        if (count($styles)) {
            $this->data['styles'] = $this->styles = array_merge($this->styles, $styles);
        }

        if (file_exists($jsFolder . str_replace('controller', '', strtolower(__CLASS__) . '.js'))) {
            $javascripts[] = $jspath . str_replace('controller', '', strtolower(__CLASS__) . '.js');
        }

        if (count($javascripts)) {
            $this->javascripts = array_merge($this->javascripts, $javascripts);
        }

        $this->load->helper('widgets');
        $widgets = new NecoWidget($this->registry, $this->Route);
        foreach ($widgets->getWidgets('main') as $widget) {
            $settings = (array) unserialize($widget['settings']);
            if ($settings['asyn']) {
                $url = $Url::createUrl("{$settings['route']}", $settings['params']);
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
                $url = $Url::createUrl("{$settings['route']}", $settings['params']);
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
