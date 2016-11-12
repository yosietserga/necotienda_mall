<?php

class ControllerAccountProfile extends Controller {

    public function index() {
        $Url = new Url($this->registry);
        if (!$this->customer->isLogged()) {
            $this->session->set('redirect', Url::createUrl("account/account"));
            $this->redirect($Url::createUrl("account/login"));
        }

        $this->language->load('account/account');

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => $Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->document->breadcrumbs[] = array(
            'href' => $Url::createUrl("account/account"),
            'text' => $this->language->get('text_account'),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('account/customer');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $images = $this->upload($this->request->files['files']);
            if ($images) {
                foreach ($images as $k => $v) {
                    $this->request->post['Images'][$k] = $v['name'];
                }
                $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'images', serialize($this->request->getPost('Images')));
            }
            $logos = $this->upload($this->request->files['client_logo']);
            if ($logos) {
                foreach ($logos as $k => $v) {
                    $this->request->post['client_list'][$k]['client_logo'] = $v['name'];
                    $this->request->post['client_list'][$k]['client_name'] = $this->request->post['client_name'][$k];
                }
                $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'client_list', serialize($this->request->getPost('client_list')));
            }
            
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'description', $this->request->getPost('description'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'history', $this->request->getPost('history'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'mission', $this->request->getPost('mission'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'vision', $this->request->getPost('vision'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'values', $this->request->getPost('values'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'policies', $this->request->getPost('policies'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'date_established', $this->request->getPost('date_established'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'experience_years', $this->request->getPost('experience_years'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'enterprise_type',  $this->request->getPost('enterprise_type'));
            $this->modelCustomer->setProperty($this->customer->getId(), 'company', 'google_map', $this->request->getPost('google_map'));
                
            $this->session->set('success', $this->language->get('text_success'));
            
            if (!$this->modelCustomer->getProperty($this->customer->getId(), 'rewards', 'profile_completed')) {
                $this->modelCustomer->setProperty($this->customer->getId(), 'rewards', 'profile_completed', 1);
                
                $this->modelCustomer->addNecoexp($this->customer->getId(), 10);
                $this->modelCustomer->addNecopoints($this->customer->getId(), 2);
            }
        }

        if ($this->session->has('success')) {
            $this->data['success'] = $this->session->get('success');
            $this->session->clear('success');
        } else {
            $this->data['success'] = '';
        }
        
        $this->data['description'] = ($this->request->hasPost('description')) ? $this->request->getPost('description') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'description');
        $this->data['history'] = ($this->request->hasPost('history')) ? $this->request->getPost('history') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'history');
        $this->data['mission'] = ($this->request->hasPost('mission')) ? $this->request->getPost('mission') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'mission');
        $this->data['vision'] = ($this->request->hasPost('vision')) ? $this->request->getPost('vision') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'vision');
        $this->data['values'] = ($this->request->hasPost('values')) ? $this->request->getPost('values') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'values');
        $this->data['policies'] = ($this->request->hasPost('policies')) ? $this->request->getPost('policies') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'policies');
        $this->data['experience_years'] = ($this->request->hasPost('experience_years')) ? $this->request->getPost('experience_years') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'experience_years');
        $this->data['enterprise_type'] = ($this->request->hasPost('enterprise_type')) ? $this->request->getPost('enterprise_type') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'enterprise_type');
        $this->data['google_map'] = ($this->request->hasPost('google_map')) ? $this->request->getPost('google_map') : $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'google_map');
        $this->data['client_list'] = ($this->request->hasPost('client_list')) ? $this->request->getPost('client_list') : unserialize($this->modelCustomer->getProperty($this->customer->getId(), 'company', 'client_list'));
        $this->data['images'] = ($this->request->hasPost('Images')) ? $this->request->getPost('Images') : unserialize($this->modelCustomer->getProperty($this->customer->getId(), 'company', 'images'));

        list($day, $month, $year) = explode('-', $this->modelCustomer->getProperty($this->customer->getId(), 'company', 'date_established'));
        
        $this->setvar('day', null, $day);
        $this->setvar('month', null, $month);
        $this->setvar('year', null, $year);
        
        $this->data['action'] = $Url::createUrl('account/profile');
        $this->data['Image'] = new NTImage;
        
        $this->loadWidgets();

        if ($scripts)
            $this->scripts = array_merge($this->scripts, $scripts);

        $this->template = 'default/account/profile.tpl';

        $this->children[] = 'common/nav';
        $this->children[] = 'account/column_left';
        $this->children[] = 'common/footer';
        $this->children[] = 'common/header';

        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    protected function upload($post) {
        $directory = $this->getUploadFolder();
        foreach ($this->getFilesArray($post) as $key => $file) {
            if (empty($file['name']))
                continue;
            $name = $file['name'];
            $ext = strtolower(substr($file['name'], (strrpos($file['name'], '.') + 1)));
            $tmp_name = $file['tmp_name'];
            $size = $file['size'];
            $type = $file['type'];
            $error = $file['error'];

            $token = uniqid() . strtotime('d-m-Y H:i:s') . $this->customer->getId() . $key;

            $name = str_replace('.' . $ext, '', $name);
            $name = $token . "-" . $this->config->get('config_name') . "-" . $this->request->post['name'];
            if ($name !== mb_convert_encoding(mb_convert_encoding($name, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                $name = mb_convert_encoding($name, 'UTF-8', mb_detect_encoding($name));
            $name = htmlentities($name, ENT_NOQUOTES, 'UTF-8');
            $name = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $name);
            $name = html_entity_decode($name, ENT_NOQUOTES, 'UTF-8');
            $name = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $name);
            $name = strtolower(trim($name, '-'));

            if (($size / 1024 / 1024) > 2 && !$return['error']) {
                return $this->language->get('error_file_size');
            }

            if ($size == 0) {
                return $this->language->get('error_file_empty');
            }

            $mime_types_allowed = array(
                'image/jpg',
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/x-png'
            );

            if (!in_array(strtolower($type), $mime_types_allowed)) {
                return $this->language->get('error_file_mime_type');
            }

            $extension_allowed = array(
                'jpg',
                'jpeg',
                'pjpeg',
                'png'
            );

            if (!in_array(strtolower($ext), $extension_allowed)) {
                return $this->language->get('error_file_extension');
            }

            if ($file['error'] == UPLOAD_ERR_INI_SIZE)
                return $this->language->get('UPLOAD_ERR_INI_SIZE');
            if ($file['error'] == UPLOAD_ERR_FORM_SIZE)
                return $this->language->get('UPLOAD_ERR_FORM_SIZE');
            if ($file['error'] == UPLOAD_ERR_PARTIAL)
                return $this->language->get('UPLOAD_ERR_PARTIAL');
            if ($file['error'] == UPLOAD_ERR_NO_FILE)
                return $this->language->get('UPLOAD_ERR_NO_FILE');
            if ($file['error'] == UPLOAD_ERR_NO_TMP_DIR)
                return $this->language->get('UPLOAD_ERR_NO_TMP_DIR');
            if ($file['error'] == UPLOAD_ERR_CANT_WRITE)
                return $this->language->get('UPLOAD_ERR_CANT_WRITE');
            if ($file['error'] == UPLOAD_ERR_EXTENSION)
                return $this->language->get('UPLOAD_ERR_EXTENSION');

            $filename = basename($name . '.' . $ext);
            if (@move_uploaded_file($tmp_name, $directory[0] . '/' . $filename)) {
                $_files[] = array(
                    'name' => $directory[1] . "/" . $name . '.' . $ext,
                    'ext' => $ext,
                    'size' => $size,
                    'type' => $type,
                    'response' => $return
                );
            } else {
                return $this->language->get('error_file_uploaded');
            }
        }
        return $_files;
    }
    
    protected function getFilesArray($files) {
        $i = 0;
        foreach ($files as $key => $file) {
            $arr[$i] = array(
                'name' => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'size' => $files['size'][$i],
                'type' => $files['type'][$i],
                'error' => $files['error'][$i]
            );
            $i++;
        }
        return $arr;
    }

    protected function getUploadFolder() {
        $dir = "data/" . date('m-Y');
        $directory = DIR_IMAGE . $dir;
        if (!is_dir($directory)) {
            mkdir($directory, 0777);
        }
        return array($directory, $dir);
    }

    private function validate() {
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function loadWidgets() {
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