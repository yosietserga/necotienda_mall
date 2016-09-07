<?php

class ControllerProfileInformation extends Controller {

    public function index() {
        $this->load->auto('account/customer');
        $this->load->auto('store/store');
        $this->language->load('profile/information');

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("profile/information"),
            'text' => $this->language->get('text_information'),
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
            
            $this->load->auto('store/store');
            $store = $this->modelStore->getByCustomerId($customer['customer_id']);
            
            $this->data['profile']['firstname'] = $customer['firstname'];
            $this->data['profile']['lastname'] = $customer['lastname'];
            $this->data['profile']['profile'] = $customer['profile'];
            $this->data['profile']['email'] = $customer['email'];
            $this->data['profile']['rif'] = $customer['rif'];
            $this->data['profile']['store'] = $store;
            $this->data['profile']['banner'] = NTImage::resizeAndSave($banner, 1100, 300); //TODO: parametrizar esta variable por admin
            $this->data['profile']['photo'] = NTImage::resizeAndSave($photo, 150, 150); //TODO: parametrizar esta variable por admin
            $this->data['profile']['show_controls'] = ($this->customer->getId() === $customer['customer_id']);
            $this->data['profile']['google_map'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'google_map');
            if (!strpos(html_entity_decode($this->data['profile']['google_map']), '<iframe') > 0
               && strpos($this->data['profile']['google_map'],'src="https://maps.google.com/maps/') > 0) {
                unset($this->data['profile']['google_map']);
            }
            
            $this->data['company']['images'] = unserialize($this->modelCustomer->getProperty($customer['customer_id'], 'company', 'images'));
            $this->data['company']['name'] = $customer['company'];
            $this->data['company']['description'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'description');
            $this->data['company']['history'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'history');
            $this->data['company']['mission'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'mission');
            $this->data['company']['vision'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'vision');
            $this->data['company']['values'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'values');
            $this->data['company']['policies'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'policies');
            $this->data['company']['client_list'] = unserialize($this->modelCustomer->getProperty($customer['customer_id'], 'company', 'client_list'));
            $this->data['company']['date_established'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'date_established');
            $this->data['company']['experience_years'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'experience_years');
            /*
             * opciones enterprise_type
             * - importadora
             * - distribuidora
             * - manufactura
             * - comercio al detal
             * - comercio al mayor
             * - profesional independiente
             * - servicios
             */
            $this->data['company']['enterprise_type'] = $this->modelCustomer->getProperty($customer['customer_id'], 'company', 'enterprise_type');

            $this->data['customer'] = $customer;            
            $this->data['Image'] = new NTImage;
            
            $this->loadWidgets();

            if ($scripts)
                $this->scripts = array_merge($this->scripts, $scripts);

            $this->template = 'default/profile/information.tpl';

            $this->children[] = 'common/nav';
            $this->children[] = 'common/footer';
            $this->children[] = 'common/header';

            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
    }
    
    public function infobox() {
        $this->load->auto('json');
        $this->load->auto('account/customer');
        
        if ($this->request->hasQuery('profile_id')) {
            $data = $this->modelCustomer->getCustomerByProfile($this->request->getQuery('profile_id'));
            $data['mapAddress'] = $this->modelCustomer->getProperty($data['customer_id'], 'profile', 'map');
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
