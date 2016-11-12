<?php

class ControllerAccountSuccess extends Controller {

    public function index() {
        $this->language->load('account/success');

        $this->document->title = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("account/account"),
            'text' => $this->language->get('text_account'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("account/success"),
            'text' => $this->language->get('text_success'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        $this->data['heading_title'] = $this->language->get('heading_title');

        if ($this->config->get('marketing_customer_added_successfully')) {
            $this->load->model("marketing/newsletter");
            
            $result = $this->modelNewsletter->getById($this->config->get('marketing_customer_added_successfully'));
            $this->data['text_message'] = $result['htmlbody'];
            

            $this->data['text_message'] = str_replace("{%store_logo%}", '<img src="' . HTTP_IMAGE . $this->config->get('config_logo') . '" alt="' . $this->config->get('config_name') . '" />', $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_url%}", HTTP_HOME, $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%url_login%}", Url::createUrl("account/login"), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_owner%}", $this->config->get('config_owner'), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_name%}", $this->config->get('config_name'), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_rif%}", $this->config->get('config_rif'), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_email%}", $this->config->get('config_email'), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_telephone%}", $this->config->get('config_telephone'), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%store_address%}", $this->config->get('config_address'), $this->data['text_message']);
            $this->data['text_message'] = str_replace("{%fullname%}", $this->customer->getFirstName() . " " . $this->customer->getLastName(), $this->data['text_message']);
            $this->data['text_message'] = html_entity_decode(htmlspecialchars_decode($this->data['text_message']));
        } elseif (!$this->config->get('config_customer_approval')) {
            $this->data['text_message'] = sprintf($this->language->get('text_message'), Url::createUrl("page/contact"));
        } else {
            $this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), Url::createUrl("page/contact"));
        }
        
        $this->loadWidgets();

        if ($scripts)
            $this->scripts = array_merge($this->scripts, $scripts);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/success.tpl')) {
            $this->template = $this->config->get('config_template') . '/common/success.tpl';
        } else {
            $this->template = 'default/common/success.tpl';
        }

        $this->children[] = 'common/column_left';
        $this->children[] = 'common/column_right';
        $this->children[] = 'common/nav';
        $this->children[] = 'common/header';
        $this->children[] = 'common/footer';

        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
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
