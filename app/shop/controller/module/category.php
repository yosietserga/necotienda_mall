<?php

class ControllerModuleCategory extends Controller {

    protected $category_id = 0;
    protected $path = array();

    protected function index($widget = null) {
        $Url = new Url($this->registry);
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array) unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        }
        $this->language->load('module/category');

        if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');
        }

        $this->load->model('store/category');
        if (isset($this->request->get['path'])) {
            $this->path = explode('_', $this->request->get['path']);
            $this->category_id = end($this->path);
        }

        if (isset($settings['parent_id'])) {
            $parent_id = (int) $settings['parent_id'];
        } else {
            $parent_id = 0;
        }

        $this->loadAssets();

        $this->id = 'category';

        if ($widget['position'] == 'main') {
            
            $this->data['categories'] = array();
            $results = $this->modelCategory->getCategories(0);
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $result['image'];
                } else {
                    $image = 'no_image.jpg';
                }
                $this->data['categories'][] = array(
                    'name' => $result['name'],
                    'href' => $Url::createUrl('store/category', array("path" => $result['category_id'])),
                    'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
                );
            }
            
            $this->data['category'] = $this->getCategories($parent_id);
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/category_home.tpl')) {
                $this->template = $this->config->get('config_template') . '/module/category_home.tpl';
            } else {
                $this->template = 'default/module/category_home.tpl';
            }
        } else {
            $this->data['category'] = $this->getCategories($parent_id);
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/category.tpl')) {
                $this->template = $this->config->get('config_template') . '/module/category.tpl';
            } else {
                $this->template = 'default/module/category.tpl';
            }
        }
        $this->render();
    }

    protected function getCategories($parent_id = 0) {
        $output = '';
        $results = $this->modelCategory->getCategories($parent_id);
        if ($results) {
            $output .= '<ul class="nt-dd2">';
            foreach ($results as $result) {
                $childrens = $this->modelCategory->getCategories($result['category_id']);
                if ($childrens) {
                    $output .= '<li class="hasCategories">';
                } else {
                    $output .= '<li>';
                }

                if ($this->category_id == $result['category_id']) {
                    $output .= '<a href="' . Url::createUrl("store/category", array("path" => $result['category_id'])) . '"><b>' . $result['name'] . '</b></a>';
                } else {
                    $output .= '<a href="' . Url::createUrl("store/category", array("path" => $result['category_id'])) . '">' . $result['name'] . '</a>';
                }

                if ($childrens) {
                    $output .= '<ul>';
                    foreach ($childrens as $child) {
                        $output .= '<li><a href="' . Url::createUrl("store/category", array("path" => $result['category_id'] . '_' . $child['category_id'])) . '" title="' . $child['name'] . '">' . $child['name'] . '</a></li>';
                    }
                    $output .= '</ul>';
                }
                $output .= '</li>';
            }

            $output .= '</ul>';
        }
        return $output;
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
