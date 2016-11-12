<?php

class ControllerModuleBanner extends Controller {

    protected function index($widget = null) {
        $this->language->load('module/banner');

        if (isset($widget)) {
            $this->data['settings'] = $settings = (array) unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }

        if (!$this->data['settings']['module'])
            $this->data['settings']['module'] = 'banner';
        $this->data['settings']['width'] = isset($this->data['settings']['width']) ? $this->data['settings']['width'] : 80;
        $this->data['settings']['height'] = isset($this->data['settings']['height']) ? $this->data['settings']['height'] : 80;

        if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');
        }

        $this->loadAssets();

        if ($scripts)
            $this->scripts = array_merge($this->scripts, $scripts);

        if ((int) $settings['banner_id']) {
            $this->data['Image'] = new NTImage;
            $this->load->model('content/banner');
            $this->data['banner'] = $this->modelBanner->getById($settings['banner_id']);

            if (!empty($this->data['banner']['jquery_plugin'])) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/banner/' . $this->data['banner']['jquery_plugin'] . '.tpl')) {
                    $this->template = $this->config->get('config_template') . '/banner/' . $this->data['banner']['jquery_plugin'] . '.tpl';
                } else {
                    $this->template = 'choroni/banner/nivo-slider.tpl';
                }
            } else {
                $this->template = 'choroni/banner/nivo-slider.tpl';
            }

            $this->id = 'banner';
            $this->render();
        }
    }

    public function carousel() {
        $json = array();
        $this->load->model('content/banner');
        $this->load->auto('image');
        $this->load->auto('json');

        $this->data['Image'] = new NTImage;
        $banner = $this->modelBanner->getById($this->request->getQuery('banner_id'));
        $json['results'] = $banner['items'];

        $width = isset($_GET['width']) ? $_GET['width'] : 80;
        $height = isset($_GET['height']) ? $_GET['height'] : 80;
        foreach ($json['results'] as $k => $v) {
            if (!file_exists(DIR_IMAGE . $v['image']))
                $json['results'][$k]['image'] = HTTP_IMAGE . "no_image.jpg";
            $json['results'][$k]['thumb'] = NTImage::resizeAndSave($v['image'], $width, $height);
        }

        if (!count($json['results']))
            $json['error'] = 1;

        $this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }

    protected function loadAssets() {
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
    }

}
