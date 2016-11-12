<?php

class ControllerCommonHeader extends Controller {

    protected function index() {
        $Url = new Url($this->registry);
        $this->load->library('browser');
        $browser = new Browser;
        if ($browser->getBrowser() == 'Internet Explorer' && $browser->getVersion() <= 8) {
            $this->redirect($Url::createUrl("page/deprecated"));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
            $this->session->set('language', $this->request->post['language_code']);

            if (isset($this->request->post['redirect'])) {
                $this->redirect($this->request->post['redirect']);
            } else {
                $this->redirect($Url::createUrl('common/home'));
            }
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
            $this->currency->set($this->request->post['currency_code']);
            $this->session->clear('shipping_methods');
            $this->session->clear('shipping_method');
            if (isset($this->request->post['redirect'])) {
                $this->redirect($this->request->post['redirect']);
            } else {
                $this->redirect($Url::createUrl('common/home'));
            }
        }

        if (!$this->session->has('token')) {
            $this->session->set('token', md5(rand()));
        }

        $this->data['token'] = $this->session->get('token');

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $this->data['base'] = HTTPS_HOME;
        } else {
            $this->data['base'] = HTTP_HOME;
        }

        if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
            $this->data['icon'] = HTTP_IMAGE . $this->config->get('config_icon');
        } else {
            $this->data['icon'] = '';
        }

        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
            $this->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
        } else {
            $this->data['logo'] = '';
        }

        $this->data['title'] = $this->document->title;
        $this->data['keywords'] = $this->document->keywords;
        $this->data['description'] = $this->document->description;
        $this->data['template'] = $this->config->get('config_template');
        $this->data['charset'] = $this->language->get('charset');
        $this->data['lang'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');
        $this->data['links'] = $this->document->links;
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;

        // style files
        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;

        /*
          if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
          $styles[] = array('media'=>'all','href'=> str_replace('%theme%',$this->config->get('config_template'),HTTP_THEME_CSS) . 'theme.css');
          } else {
          $styles[] = array('media'=>'all','href'=> str_replace('%theme%','default',HTTP_THEME_CSS) . 'theme.css');
          }
          if (file_exists(DIR_CSS."custom-". (int)$this->config->get('theme_default_id') ."-". $this->config->get('config_template') .".css")) {
          $styles[] = array('media'=>'all','href'=>$csspath."custom-". (int)$this->config->get('theme_default_id') ."-". $this->config->get('config_template') .".css");
          }
         */

        $this->data['css'] = "";
        if (file_exists(str_replace('%theme%', $this->config->get('config_template'), DIR_THEME_CSS) . 'vendor.css')) {
            $this->data['css'] .= file_get_contents(str_replace('%theme%', $this->config->get('config_template'), DIR_THEME_CSS) . 'vendor.css');
        }
        if (file_exists(str_replace('%theme%', $this->config->get('config_template'), DIR_THEME_CSS) . 'theme.css')) {
            $this->data['css'] .= file_get_contents(str_replace('%theme%', $this->config->get('config_template'), DIR_THEME_CSS) . 'theme.css');
        }
        foreach ($this->styles as $css) {
            $this->data['css'] .= file_get_contents($css['href']);
        }
        if (file_exists(DIR_CSS . "custom-" . (int) $this->config->get('theme_default_id') . "-" . $this->config->get('config_template') . ".css")) {
            $this->data['css'] .= file_get_contents($csspath . "custom-" . (int) $this->config->get('theme_default_id') . "-" . $this->config->get('config_template') . ".css");
        }
        if ($this->data['css']) {
            $this->data['css'] = str_replace("../../../images/", HTTP_IMAGE, $this->data['css']);
            $this->data['css'] = str_replace("../images/", str_replace('%theme%', $this->config->get('config_template'), HTTP_THEME_IMAGE), $this->data['css']);
            $this->data['css'] = str_replace("../fonts/", str_replace('%theme%', $this->config->get('config_template'), HTTP_THEME_FONT), $this->data['css']);
        }

        $this->load->library('user');
        if ($this->user->getId()) {
            $this->data['is_admin'] = true;
            /*
            $styles[] = array('media' => 'screen', 'href' => HTTP_ADMIN . 'css/front/admin.css');
            $styles[] = array('media' => 'screen', 'href' => $csspath . 'neco.tips.css');
            $styles[] = array('media' => 'screen', 'href' => $csspath . 'jquery-ui/jquery-ui.min.css');

            if ($this->request->hasQuery('theme_editor')) {
                $this->data['theme_editor'] = true;

                if ($this->request->hasQuery('template') && file_exists(DIR_TEMPLATE . $this->request->getQuery('template') . '/common/header.tpl')) {
                    $styles[] = array('media' => 'screen', 'href' => $csspath . 'neco.colorpicker.css');
                    $this->config->set('config_template', $this->request->getQuery('template'));
                    $this->data['new_theme'] = Url::createAdminUrl('style/theme/insert', array(), 'NONSSL', HTTP_ADMIN);
                    $this->data['save_theme'] = Url::createAdminUrl('style/theme/save', array('theme_id' => $this->request->getQuery('theme_id'), 'template' => $this->request->getQuery('template')), 'NONSSL', HTTP_ADMIN);
                    $this->data['download_theme'] = Url::createAdminUrl('style/theme/download', array('theme_id' => $this->request->getQuery('theme_id'), 'template' => $this->request->getQuery('template')), 'NONSSL', HTTP_ADMIN);
                }
            }

            $this->data['create_product'] = Url::createAdminUrl('store/product/insert', array(), 'NONSSL', HTTP_ADMIN);
            $this->data['create_page'] = Url::createAdminUrl('content/page/insert', array(), 'NONSSL', HTTP_ADMIN);
            $this->data['create_post'] = Url::createAdminUrl('content/post/insert', array(), 'NONSSL', HTTP_ADMIN);
            $this->data['create_manufacturer'] = Url::createAdminUrl('store/manufacturer/insert', array(), 'NONSSL', HTTP_ADMIN);
            $this->data['create_product_category'] = Url::createAdminUrl('store/category/insert', array(), 'NONSSL', HTTP_ADMIN);
            $this->data['create_post_category'] = Url::createAdminUrl('content/post_category/insert', array(), 'NONSSL', HTTP_ADMIN);
             
             */
        }

        if ($styles)
            $this->data['styles'] = $this->styles = array_merge($styles, $this->styles);

        $this->data['store'] = $this->config->get('config_name');

        $this->data['text_store'] = $this->config->get('config_name');
        $this->data['text_home'] = $this->language->get('text_home');
        $this->data['text_special'] = $this->language->get('text_special');
        $this->data['text_contact'] = $this->language->get('text_contact');
        $this->data['text_sitemap'] = $this->language->get('text_sitemap');
        $this->data['text_bookmark'] = $this->language->get('text_bookmark');
        $this->data['text_account'] = $this->language->get('text_account');
        $this->data['text_login'] = $this->language->get('text_login');
        $this->data['text_logout'] = $this->language->get('text_logout');
        $this->data['text_cart'] = $this->language->get('text_cart');
        $this->data['text_checkout'] = $this->language->get('text_checkout');
        $this->data['text_keyword'] = $this->language->get('text_keyword');
        $this->data['text_category'] = $this->language->get('text_category');
        $this->data['text_advanced'] = $this->language->get('text_advanced');
        $this->data['text_my_actitivties'] = $this->language->get('text_my_actitivties');
        $this->data['text_my_reviews'] = $this->language->get('text_my_reviews');
        $this->data['text_my_orders'] = $this->language->get('text_my_orders');
        $this->data['text_my_addresses'] = $this->language->get('text_my_addresses');
        $this->data['text_my_account'] = $this->language->get('text_my_account');
        $this->data['text_credits'] = $this->language->get('text_credits');
        $this->data['text_payments'] = $this->language->get('text_payments');
        $this->data['text_messages'] = $this->language->get('text_messages');
        $this->data['text_compare'] = $this->language->get('text_compare');
        $this->data['text_my_lists'] = $this->language->get('text_my_lists');
        $this->data['text_forgotten'] = $this->language->get('text_forgotten');

        $this->data['entry_search'] = $this->language->get('entry_search');
        $this->data['button_go'] = $this->language->get('button_go');

        $this->data['isLogged'] = $this->customer->isLogged();

        if ($this->customer->isLogged()) {
            $this->data['greetings'] = 'Bienvenido(a), ' . ucwords($this->customer->getFirstName() . ' ' . $this->customer->getLastName());
            
            $this->load->auto('account/customer');
            $this->modelCustomer->checkLevel($this->customer->getId());
        }

        if (isset($this->request->get['q'])) {
            $this->data['q'] = $this->request->get['q'];
        } else {
            $this->data['q'] = '';
        }

        if (isset($this->request->get['category_id'])) {
            $this->data['category_id'] = $this->request->get['category_id'];
        } elseif (isset($this->request->get['path'])) {
            $path = explode('_', $this->request->get['path']);
            $this->data['category_id'] = end($path);
        } else {
            $this->data['category_id'] = 0;
        }

        if (isset($this->request->get['product_id'])) {
            $this->data['product_id'] = $this->request->get['product_id'];
        } else {
            $this->data['product_id'] = 0;
        }

        if (isset($this->request->get['manufacturer_id'])) {
            $this->data['manufacturer_id'] = $this->request->get['manufacturer_id'];
        } else {
            $this->data['manufacturer_id'] = 0;
        }

        /*
          // Auto suggest through email and while is online
          $this->track->autoSuggest(array(
          'category_id'       =>$this->data['category_id'],
          'product_id'        =>$this->data['product_id'],
          'manufacturer_id'   =>$this->data['manufacturer_id'],
          'q'                 =>$this->data['q']
          ));
         */

        $this->data['action'] = $Url::createUrl('common/home');

        if (!isset($this->request->get['r'])) {
            $this->data['redirect'] = $Url::createUrl('common/home');
        } else {
            $data = $this->request->get;
            unset($data['_route_']);
            $route = $data['r'];
            unset($data['r']);
            $url = '';

            if ($data) {
                $url = '&' . urldecode(http_build_query($data));
            }

            $this->data['redirect'] = $Url::createUrl($route, $url);
        }

        $this->data['language_code'] = $this->session->get('language');
        $this->data['languages'] = array();
        $results = $this->modelLanguage->getLanguages();

        foreach ($results as $result) {
            if ($result['status']) {
                $this->data['languages'][] = array(
                    'name' => $result['name'],
                    'code' => $result['code'],
                    'image' => HTTP_IMAGE . "flags/" . $result['image']
                );
            }
        }

        $this->data['currency_code'] = $this->currency->getCode();
        $this->data['currencies'] = array();
        $results = $this->modelCurrency->getCurrencies();

        foreach ($results as $result) {
            if ($result['status']) {
                $this->data['currencies'][] = array(
                    'title' => $result['title'],
                    'code' => $result['code']
                );
            }
        }

        $this->session->set('state', md5(rand()));
        $this->data['live_client_id'] = $this->config->get('social_live_client_id');
        $this->data['google_client_id'] = $this->config->get('social_google_client_id');
        $this->data['facebook_app_id'] = $this->config->get('social_facebook_app_id');
        $this->data['twitter_oauth_token_secret'] = $this->config->get('social_twitter_oauth_token_secret');

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

        $this->id = 'header';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $this->template = $this->config->get('config_template') . '/common/header.tpl';
        } else {
            $this->template = 'default/common/header.tpl';
        }

        $this->render();
    }

}
