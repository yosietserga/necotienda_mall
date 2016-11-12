<?php

class ControllerCommonLogin extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('common/login');

        $this->document->title = $this->language->get('heading_title');

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->get('ukey'))) {
            $this->redirect(Url::createAdminUrl('common/home'));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            //$this->session->set('token',$this->session->get('ukey')); // esta linea esta en la lib user.php
            if (isset($this->request->post['redirect'])) {
                $this->redirect(Url::createUrl($this->request->post['redirect'], array('token' => $this->session->get('ukey'))));
            } else {
                $this->redirect(Url::createUrl('common/home', array('token' => $this->session->get('ukey'))));
            }
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_login'] = $this->language->get('text_login');
        $this->data['entry_username'] = $this->language->get('entry_username');
        $this->data['entry_password'] = $this->language->get('entry_password');
        $this->data['button_login'] = $this->language->get('button_login');

        if (!$this->session->has('ukey') || !isset($this->request->get['token']) || ($this->request->get['token'] != $this->session->get('ukey'))) {
            $this->error['warning'] = $this->language->get('error_token');
        }

        $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

        $this->data['action'] = Url::createAdminUrl('common/login');

        $this->setvar('username');
        $this->setvar('password');

        if (isset($this->request->get['r'])) {
            $route = $this->request->get['r'];
            unset($this->request->get['r']);
            if (isset($this->request->get['token'])) {
                unset($this->request->get['token']);
            }
            $url = '';
            if ($this->request->get) {
                $url = '&' . http_build_query($this->request->get);
            }
            $this->data['redirect'] = $route;
        } else {
            $this->data['redirect'] = '';
        }

        $this->session->set('fid', md5(rand()));
        $scripts[] = array('id' => 'login', 'method' => 'ready', 'script' =>
            "$('#formLogin input').keydown(function(e) {
        		if (e.keyCode == 13) {
  		            submit();
        		}
        	});");
        $scripts[] = array('id' => 'loginFunction', 'method' => 'function', 'script' =>
            "function submit() {
                $('#formLogin .button').before('<div id=\"loading\" style=\"margin:5px auto;width:210px;\"><img src=\"" . HTTP_IMAGE . "loader.gif\" alt=\"cargando...\" /><div>');
                if(window.jQuery) {
  		            if (typeof jQuery.ui != 'undefined') {
                        $.post('" . Url::createAdminUrl("common/login/login") . "',
                        {
                            username:$('input[name=username]').val(),
                            password:$('input[name=password]').crypt({method:'md5'}),
                            fid:'" . $this->session->get('fid') . "'
                        },
                        function(response){
                            data = $.parseJSON(response);
                            if (typeof data.success != 'undefined') {
                                window.location.href = data.redirect;
                            } else {
                                $('#formLogin').effect('shake');
                            }
                            $('#loading').remove();
                        });
                    } else {
                        $('#formLogin').submit();
                        $('#loading').remove();
                    }
                } else {
                    document.forms['formLogin'].submit();
                    $('#loading').remove();
                }
      		}");

        $this->scripts = array_merge($scripts, $this->scripts);

        $this->template = 'common/login.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    public function login() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/json");

        $this->load->language('common/login');
        $json = array();

        if ($this->user->isLogged() || ($this->request->server['REQUEST_METHOD'] != 'POST') || ($this->session->get('fid') != $this->request->post['fid'])) {
            $json['error'] = 1;
        }

        if (!$this->user->login($this->request->post['username'], $this->request->post['password'], false)) {
            $json['error'] = 1;
        } elseif (!$json['error']) {
            $json['redirect'] = isset($this->request->post['redirect']) ?
                    Url::createUrl($this->request->post['redirect'], array('token' => $this->session->get('ukey'))) :
                    Url::createUrl('common/home', array('token' => $this->session->get('ukey')));
            $json['success'] = 1;
        }

        $this->load->auto('json');
        $this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }

    public function recover() {
        $this->load->language('common/login');
        $this->data['error_warning'] = '';

        $this->document->title = $this->language->get('heading_recover_title');

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->get('ukey'))) {
            $this->redirect(Url::createAdminUrl('common/home'));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRecover()) {
            $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->request->getPost('username') . "' AND email = '" . $this->request->getPost('email') . "'");
            if ($result->num_rows) {
                $password = substr(md5(rand()), 0, 7);
                $this->db->query("UPDATE " . DB_PREFIX . "user SET 
                `password` = '" . md5($password) . "' 
                WHERE username = '" . $this->request->getPost('username') . "' 
                AND email = '" . $this->request->getPost('email') . "'");

                $this->load->auto('email/mailer');
                $mailer = new Mailer;

                $message = "<h1>Hola " . $this->request->getPost('username') . ",</h1>\n\n";
                $message .= "<p>Tu nueva contrase&ntilde;a es:</p>\n";
                $message .= "<h1>" . $password . "</h1>\n";

                if ($this->config->get('config_smtp_method') == 'smtp') {
                    $mailer->IsSMTP();
                    $mailer->Hostname = $this->config->get('config_smtp_host');
                    $mailer->Username = $this->config->get('config_smtp_username');
                    $mailer->Password = base64_decode($this->config->get('config_smtp_password'));
                    $mailer->Port = $this->config->get('config_smtp_port');
                    $mailer->Timeout = $this->config->get('config_smtp_timeout');
                    $mailer->SMTPSecure = $this->config->get('config_smtp_ssl');
                    $mailer->SMTPAuth = ($this->config->get('config_smtp_auth')) ? true : false;
                } elseif ($this->config->get('config_smtp_method') == 'sendmail') {
                    $mailer->IsSendmail();
                } else {
                    $mailer->IsMail();
                }
                $mailer->IsHTML();
                $mailer->AddAddress($this->request->getPost('email'), $this->request->getPost('username'));
                $mailer->SetFrom('soporte@necotienda.com', 'NecoTienda');
                $mailer->Subject = 'Recuperacion de Contrasena';
                $mailer->Body = $message;
                $mailer->Send();

                $this->redirect(Url::createUrl('common/login'));
            } else {
                $this->data['error_warning'] = $this->language->get('error_user_unknown');
            }
        }

        if (isset($this->error['warning']))
            $this->data['error_warning'] = $this->error['warning'];

        $this->data['action'] = Url::createAdminUrl('common/login/recover');

        $this->setvar('username');
        $this->setvar('email');

        $scripts[] = array('id' => 'login', 'method' => 'ready', 'script' =>
            "$('#form input').keydown(function(e) {
        		if (e.keyCode == 13) {
                    $('#form').submit();
        		}
        	});");
        $this->scripts = array_merge($scripts, $this->scripts);

        $this->template = 'common/recover.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->login($this->request->post['username'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function validateRecover() {
        if (empty($this->request->post['username'])) {
            $this->error['warning'] = $this->language->get('error_recover');
        }
        if (empty($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_recover');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}
