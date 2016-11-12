<?php

/**
 * @author Inversiones Necoyoad, C.A.
 * @copyright 2010
 */
class ControllerMarketingBounce extends Controller {
    private $conection;
    private $logdir = DIR_LOGS;
    private $logfile = 'bounce.log.txt';
	private $error = array();
   
  	public function index() {
		$this->load->language('marketing/bounce');
	 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->auto('marketing/bounce');
	   // TODO: pasar variables a por post y procesar formulario con ajax
		$bounce_details = array (
			'bounce_server' => $this->request->get['bounce_server'],
			'bounce_username' => $this->request->get['bounce_username'],
			'bounce_password' => base64_encode($this->request->get['bounce_password']),
			'bounce_imap' => isset($this->request->get['bounce_imap']) ? $this->request->get['bounce_imap'] : '',
			'agree_delete' => isset($this->request->get['agree_delete']) ? $this->request->get['agree_delete'] : '',
			'save_settings' => isset($this->request->get['savebounceserverdetails']) ? $this->request->get['savebounceserverdetails'] : '',
		);
        $strCombinations = '';
        if (!empty($this->request->get['extra_mail_nossl'])) {
            $strCombinations .= '/'.$this->request->get['extra_mail_nossl'];
        }
        if (!empty($this->request->get['extra_mail_notls'])) {
            $strCombinations .= '/'.$this->request->get['extra_mail_notls'];
        }
        if (!empty($this->request->get['extra_mail_novalidate'])) {
            $strCombinations .= '/'.$this->request->get['extra_mail_novalidate'];
        } 
        if (!empty($this->request->get['extra_mail_others'])) {
            $strCombinations .= $this->request->get['extra_mail_others'];
        }        
        $bounce_details['bounce_extrasettings'] = array($strCombinations);
        if (empty($bounce_details['bounce_extrasettings'])) {
			$bounce_details['bounce_extrasettings'] = $this->generateConnectionCombinations();
		}
        
        $this->session->set('bounce_details',$bounce_details);
        
		$port = false;
		if (strpos($bounce_details['bounce_server'], ':') !== false) {
			list($server, $port) = explode(':', $bounce_details['bounce_server']);
		} else {
		  $server = $bounce_details['bounce_server'];
		}
		if (!$port) {
		  if ($bounce_details['bounce_imap'] == 'i') {
		      $port = 143;
          } else {
              $port = 110;
          }
		}
        
        $fp = @fsockopen($server, $port, $errno, $errstr, 10);
        
		if (!$fp) {
            $this->data['title'] = 'Error Al Conectar...';
            $this->data['msg'] = 'Se ha producido un error en la conexi&oacute;n al servidor. Por favor verifique los datos e intente de nuevo';
            $this->data['porcentaje'] = 'jQuery("#progressbar").progressBar(70,{barImage : {
                                                                            		0:  \''.HTTP_HOME .'image/progressbg_red.gif\',
                                                                            		30: \''.HTTP_HOME .'image/progressbg_orange.gif\',
                                                                            		80: \''.HTTP_HOME .'image/progressbg_green.gif\'
                                                                            	       }
                                                                                    });';
		} else {
	       $login_ok = $this->Login();           
		  if (!$login_ok) {
            $this->data['title'] = 'Error Al Conectar...';
            $this->data['msg'] = 'Se ha producido un error en la conexi&oacute;n al servidor. Por favor verifique los datos e intente de nuevo';
            $this->data['porcentaje'] = 'jQuery("#progressbar").progressBar(70,{barImage : {
                                                                            		0:  \''.HTTP_HOME .'image/progressbg_red.gif\',
                                                                            		30: \''.HTTP_HOME .'image/progressbg_orange.gif\',
                                                                            		80: \''.HTTP_HOME .'image/progressbg_green.gif\'
                                                                            	       }
                                                                                    });';
		  } else {		      
            $this->data['title'] = 'Conexi&oacute;n &Eacute;xitosa';
            $this->data['msg'] = 'Se ha completado con &eacute;xito la conexi&oacute;n al servidor';
            $this->data['porcentaje'] = 'jQuery("#progressbar").progressBar(100,{barImage : {
                                                                            		0:  \''.HTTP_HOME .'image/progressbg_red.gif\',
                                                                            		30: \''.HTTP_HOME .'image/progressbg_orange.gif\',
                                                                            		80: \''.HTTP_HOME .'image/progressbg_green.gif\'
                                                                            	       }
                                                                                    });';
    		$count = $this->GetEmailCount();
    		$this->Logout();
		  }
		}
        
        
        $this->template = 'marketing/bounce_test.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
    
    public function test() {
		$this->load->language('marketing/bounce');
	 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->auto('marketing/bounce');
	   // TODO: pasar variables a por post y procesar formulario con ajax
		$bounce_details = array (
			'config_bounce_server' => $this->request->get['config_bounce_server'],
			'config_bounce_username' => $this->request->get['config_bounce_username'],
			'config_bounce_password' => base64_encode($this->request->get['config_bounce_password']),
			'config_bounce_protocol' => isset($this->request->get['config_bounce_protocol']) ? $this->request->get['config_bounce_protocol'] : '',
			'config_bounce_agree_delete' => isset($this->request->get['config_bounce_agree_delete']) ? $this->request->get['config_bounce_agree_delete'] : '',
			'config_save_settings' => isset($this->request->get['savebounceserverdetails']) ? $this->request->get['savebounceserverdetails'] : '',
		);
        $strCombinations = '';
        if (!empty($this->request->get['extra_mail_nossl'])) {
            $strCombinations .= '/'.$this->request->get['extra_mail_nossl'];
        }
        if (!empty($this->request->get['extra_mail_notls'])) {
            $strCombinations .= '/'.$this->request->get['extra_mail_notls'];
        }
        if (!empty($this->request->get['extra_mail_novalidate'])) {
            $strCombinations .= '/'.$this->request->get['extra_mail_novalidate'];
        } 
        if (!empty($this->request->get['extra_mail_others'])) {
            $strCombinations .= $this->request->get['extra_mail_others'];
        }        
        $bounce_details['bounce_extrasettings'] = array($strCombinations);
        if (empty($bounce_details['bounce_extrasettings'])) {
			$bounce_details['bounce_extrasettings'] = $this->generateConnectionCombinations();
		}
        
        $this->session->set('bounce_details',$bounce_details);
        
		$port = false;
		if (strpos($bounce_details['config_bounce_server'], ':') !== false) {
			list($server, $port) = explode(':', $bounce_details['config_bounce_server']);
		} else {
		  $server = $bounce_details['config_bounce_server'];
		}
		if (!$port) {
		  if ($bounce_details['config_bounce_protocol'] == 'imap') {
		      $port = 143;
          } else {
              $port = 110;
          }
		}
        
        $fp = @fsockopen($server, $port, $errno, $errstr, 10);
        
		if (!$fp) {
            $this->data['title'] = 'Error Al Conectar...';
            $this->data['msg'] = 'Se ha producido un error en la conexi&oacute;n al servidor. Por favor verifique los datos e intente de nuevo';
            $this->data['porcentaje'] = 'jQuery("#progressbar").progressBar(50,{barImage : {
                                                                            		0:  \''.HTTP_HOME .'image/progressbg_red.gif\',
                                                                            		30: \''.HTTP_HOME .'image/progressbg_orange.gif\',
                                                                            		80: \''.HTTP_HOME .'image/progressbg_green.gif\'
                                                                            	       }
                                                                                    });';
		} else {
	       $login_ok = $this->Login();           
		  if (!$login_ok) {
            $this->data['title'] = 'Error Al Conectar...';
            $this->data['msg'] = 'No se pudo iniciar sesi&oacute;n';
            $this->data['porcentaje'] = 'jQuery("#progressbar").progressBar(70,{barImage : {
                                                                            		0:  \''.HTTP_HOME .'image/progressbg_red.gif\',
                                                                            		30: \''.HTTP_HOME .'image/progressbg_orange.gif\',
                                                                            		80: \''.HTTP_HOME .'image/progressbg_green.gif\'
                                                                            	       }
                                                                                    });';
		  } else {		      
            $this->data['title'] = 'Conexi&oacute;n &Eacute;xitosa';
            $this->data['msg'] = 'Se ha completado con &eacute;xito la conexi&oacute;n al servidor';
            $this->data['porcentaje'] = 'jQuery("#progressbar").progressBar(100,{barImage : {
                                                                            		0:  \''.HTTP_HOME .'image/progressbg_red.gif\',
                                                                            		30: \''.HTTP_HOME .'image/progressbg_orange.gif\',
                                                                            		80: \''.HTTP_HOME .'image/progressbg_green.gif\'
                                                                            	       }
                                                                                    });';
    		$count = $this->GetEmailCount();
    		$this->Logout();
		  }
		}
        
        
        $this->template = 'marketing/bounce_test.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}
    
    function GetEmailCount()
	{
		if (is_null($this->connection)) {
			return false;
		}

		$display_errors = @ini_get('display_errors');
		@ini_set('display_errors', false);

		$count = imap_num_msg($this->connection);
		@ini_set('display_errors', $display_errors);
		return $count;
	}

	function Logout()
	{
		if (is_null($this->connection)) {
			return false;
		}

		imap_close($this->connection);
		$this->connection = null;

		imap_errors();
		imap_alerts();

		return true;
	}
    
    function Login() {
        $bounce_details = $this->session->get('bounce_details');
        $arrConections = array();
        //'{midominio.com:110/pop3/ssl}INBOX';
		if (is_null($bounce_details['config_bounce_username']) || is_null($bounce_details['config_bounce_password']) || is_null($bounce_details['config_bounce_server'])) {
            $this->data['title'] = 'Faltan datos';
            $this->data['msg'] = 'No se puede realizar la conexi&oacute;n';
            return false;
		}

		if ($bounce_details['config_bounce_protocol'] == 'imap') {
			if (strpos($bounce_details['config_bounce_server'], ':') === false) {
				$strConection = '{' . $bounce_details['config_bounce_server'] . ':143';
			} else {
				$strConection = '{' . $bounce_details['config_bounce_server'];
			}
		} else {
			if (strpos($bounce_details['config_bounce_server'], ':') === false) {
				$strConection = '{' . $bounce_details['config_bounce_server'] . ':110/pop3';
			} else {
				$strConection = '{' . $bounce_details['config_bounce_server'] . '/pop3';
			}
		}
        
        if (!empty($bounce_details['bounce_extrasettings'])) {
    		if (is_array($bounce_details['bounce_extrasettings'])) {
    		  foreach ($bounce_details['bounce_extrasettings'] as $extra_setting) {
    		      $arrConections[] = $strConection . $extra_setting . '}INBOX';
    		  }
    		} else {
    		      $arrConections = $strConection . $bounce_details['bounce_extrasettings'] . '}INBOX';
    		}
        }

		$password = base64_decode($bounce_details['config_bounce_password']);

		if (!empty($arrConections)) {
            foreach ($arrConections as $connection) {
                $inbox = @imap_open($connection, $bounce_details['config_bounce_username'], $password);
                if (!$inbox) {
        			$errormsg = imap_last_error();
        			$errors = imap_errors();
                    if (is_array($errors) && !empty($errors)) {
        				$errormsg = array_shift($errors);
        			} else {
        				$alerts = imap_alerts();
        				if (is_array($alerts) && !empty($alerts)) {
        					$errormsg = array_shift($alerts);
        				}
        			}
                    date_default_timezone_set('America/Caracas');
        			error_log('Line ' . __LINE__ . ' - '. date('d-m-Y h:i:s') . '; imap_errors: ' . print_r($errormsg) ."\n", 3, $this->logdir.$this->logfile);
        			error_log('Line ' . __LINE__ . ' - '. date('d-m-Y h:i:s') . '; imap_alerts: ' . print_r(imap_alerts(), true) ."\n", 3, $this->logdir.$this->logfile);
        			imap_alerts();
        			return false;
        		} else {
        		  $this->conection = $inbox;
                  $strConection = $connection;
        		  return true;
        		}
            }
		}
		imap_errors();
		imap_alerts();

		$this->connection = $inbox;
		return true;
	}

	private function generateConnectionCombinations() {
		$ssl = array('', '/ssl', '/nossl');
		$tls = array('', '/tls', '/notls');
		$cert = array('', '/novalidate-cert');

		$combinations = array();
		foreach ($ssl as $use_ssl) {
			foreach ($tls as $use_tls) {
				foreach ($cert as $cert_check) {
					$combinations[] = "{$use_ssl}{$use_tls}{$cert_check}";
				}
			}
		}

		return array_unique($combinations);
	}
}

