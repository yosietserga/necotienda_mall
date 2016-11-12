<?php

class ControllerCheckoutCart extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('account/register');
        $this->language->load('checkout/cart');
        $this->load->model('localisation/country');
        $this->load->library('image');

        $this->session->set('redirect', Url::createUrl('checkout/cart'));

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->get['product_id'])) {

            if (isset($this->request->get['option'])) {
                $option = $this->request->get['option'];
            } else {
                $option = array();
            }

            if (isset($this->request->get['quantity'])) {
                $quantity = $this->request->get['quantity'];
            } else {
                $quantity = 1;
            }

            $this->session->clear('shipping_methods');
            $this->session->clear('shipping_method');
            $this->session->clear('payment_methods');
            $this->session->clear('payment_method');

            $this->cart->add($this->request->get['product_id'], $quantity, $option);
            
            if (!$this->modelCustomer->getProperty($this->customer->getId(), 'rewards', 'product_added_to_cart') && $this->customer->isLogged()) {
                $this->modelCustomer->setProperty($this->customer->getId(), 'rewards', 'product_added_to_cart', 1);
                $this->modelCustomer->addNecopoints($this->customer->getId(), 3);
                $this->modelCustomer->addNecoexp($this->customer->getId(), 15);
            }
            
            $this->redirect(Url::createUrl("checkout/cart"));
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            if (isset($this->request->post['quantity'])) {
                if (!is_array($this->request->post['quantity'])) {
                    if (isset($this->request->post['option'])) {
                        $option = $this->request->post['option'];
                    } else {
                        $option = array();
                    }

                    $this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
                } else {
                    foreach ($this->request->post['quantity'] as $key => $value) {
                        $this->cart->update($key, $value);
                    }
                }

                $this->session->clear('shipping_methods');
                $this->session->clear('shipping_method');
                $this->session->clear('payment_methods');
                $this->session->clear('payment_method');
            }

            if (isset($this->request->post['redirect'])) {
                $this->session->set('redirect', $this->request->post['redirect']);
            }

            if (!$this->modelCustomer->getProperty($this->customer->getId(), 'rewards', 'product_added_to_cart') && $this->customer->isLogged()) {
                $this->modelCustomer->setProperty($this->customer->getId(), 'rewards', 'product_added_to_cart', 1);
                $this->modelCustomer->addNecopoints($this->customer->getId(), 3);
                $this->modelCustomer->addNecoexp($this->customer->getId(), 15);
            }
            
            $this->redirect(Url::createUrl("checkout/cart"));
        }

        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("checkout/cart"),
            'text' => $this->language->get('text_basket'),
            'separator' => $this->language->get('text_separator')
        );
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;
        if ($this->cart->hasProducts()) {
            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            } elseif (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) {
                $this->data['error_warning'] = $this->language->get('error_stock');
            } else {
                $this->data['error_warning'] = '';
            }

            $this->data['action'] = Url::createUrl('checkout/confirm');

            $this->data['products'] = array();

            foreach ($this->cart->getProducts() as $result) {
                $option_data = array();

                foreach ($result['option'] as $option) {
                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => $option['value']
                    );
                }

                if ($result['image']) {
                    $image = $result['image'];
                } else {
                    $image = 'no_image.jpg';
                }

                $this->data['products'][] = array(
                    'key' => $result['key'],
                    'product_id' => $result['key'],
                    'name' => $result['name'],
                    'model' => $result['model'],
                    'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
                    'option' => $option_data,
                    'quantity' => $result['quantity'],
                    'stock' => $result['stock'],
                    'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
                    'total' => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
                    'href' => Url::createUrl("store/product", array("product_id" => $result['product_id']))
                );
            }

            if ($this->config->get('config_cart_weight')) {
                $this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class'));
            } else {
                $this->data['weight'] = false;
            }

            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('checkout/extension');

            $sort_order = array();

            $results = $this->modelExtension->getExtensions('total');
            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);
            foreach ($results as $result) {
                $this->load->model('total/' . $result['key']);

                $this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
            }

            $sort_order = array();
            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
            $this->data['totals'] = $total_data;

            if ($this->session->has('message')) {
                $this->data['message'] = $this->session->get('message');
                $this->session->clear('message');
            }

            $this->data['countries'] = $this->modelCountry->getCountries();

            if ($this->customer->isLogged()) {
                $this->data['email'] = $this->customer->getEmail();
                $this->data['firstname'] = $this->customer->getFirstName();
                $this->data['lastname'] = $this->customer->getLastName();
                $this->data['company'] = $this->customer->getCompany();
                $this->data['telephone'] = $this->customer->getTelephone();
                $this->data['rif_type'] = substr($this->customer->getRif(), 0, 1);
                $this->data['rif'] = substr($this->customer->getRif(), 1);
                $this->data['riff'] = $this->customer->getRif();
                $this->data['isLogged'] = $this->customer->isLogged();

                $this->load->auto('account/address');
                $address = $this->modelAddress->getAddress($this->customer->getAddressId());
                if ($address) {
                    $this->data['payment_country_id'] = $address['country_id'];
                    $this->data['payment_zone_id'] = $address['zone_id'];
                    $this->data['payment_city'] = $address['city'];
                    $this->data['payment_street'] = $address['street'];
                    $this->data['payment_address_1'] = $address['address_1'];
                    $this->data['payment_postcode'] = $address['postcode'];
                    $this->data['payment_address'] = $address['address_1'] . " " . $address['street'] . ", " . $address['city'] . ". " . $address['zone'] . " - " . $address['country'];
                    $this->session->set('payment_address_id', $this->customer->getAddressId());


                    $this->data['shipping_country_id'] = $address['country_id'];
                    $this->data['shipping_zone_id'] = $address['zone_id'];
                    $this->data['shipping_city'] = $address['city'];
                    $this->data['shipping_street'] = $address['street'];
                    $this->data['shipping_address_1'] = $address['address_1'];
                    $this->data['shipping_postcode'] = $address['postcode'];
                    $this->data['shipping_address'] = $address['address_1'] . " " . $address['street'] . ", " . $address['city'] . ". " . $address['zone'] . " - " . $address['country'];
                    $this->session->set('shipping_address_id', $this->customer->getAddressId());
                } else {
                    $this->data['no_address'] = true;
                }

                $this->tax->setZone($address['country_id'], $address['zone_id']);
            } else {
                $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
            }

            /*             * ****************** shipping methods ********************** */
            $quote_data = array();
            $results = $this->modelExtension->getExtensions('shipping');
            foreach ($results as $result) {
                $this->load->model('shipping/' . $result['key']);

                $quote = $this->{'model_shipping_' . $result['key']}->getQuote($address);

                if ($quote) {
                    $quote_data[$result['key']] = array(
                        'title' => $quote['title'],
                        'quote' => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error' => $quote['error']
                    );
                }
            }

            $sort_order = array();

            foreach ($quote_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $quote_data);

            $this->session->set('shipping_methods', $quote_data);
            $this->data['shipping_methods'] = $quote_data;


            $script = "";
            // SCRIPTS
            if (!$this->customer->isLogged()) {
                $scripts[] = array('id' => 'custom_0_Cart', 'method' => 'ready', 'script' =>
                    "$('#email').on('change',function(e){
                	$.post('" . Url::createUrl("account/register/checkemail") . "', {email: $(this).val()},
                  		function(response){
                  		    $('#tempLink').remove();
                  		    var data = $.parseJSON(response);
                			if (typeof data.error != 'undefined') {
                				$('#email').removeClass('neco-input-success').addClass('neco-input-error');
                                $('#email').parent().find('.neco-form-error').attr({'title':\"Este email ya existe!\"});
                                $('#email').closest('.property').after('<p id=\"tempLink\" class=\"error\">'+ data.msg +'</p>');
                			} else {
                				$('#email').addClass('neco-input-success').removeClass('neco-input-error');
                                $('#email').parent().find('.neco-form-error').attr({'title':\"No hay errores en este campo\"});
                                $('#tempLink').remove();
                			}
                  	});
                });");

                //TODO: cargar los nombres de los paises y estados en la tabla de confirmacion
                $script = "if (!$('#email').attr('disabled')) { 
                    $.post('" . Url::createUrl("account/register/register") . "', {
                        email: $('#email').val(),
                        firstname: $('#firstname').val(),
                        lastname: $('#lastname').val(),
                        company: $('#company').val(),
                        rif: $('#rif').val(),
                        telephone: $('#telephone').val(),
                        country_id: $('#payment_country_id').val(),
                        zone_id: $('#payment_zone_id').val(),
                        city: $('#payment_city').val(),
                        street: $('#payment_street').val(),
                        postcode: $('#payment_postcode').val(),
                        address_1: $('#payment_address_1').val(),
                        session_address_var: 'shipping_address_id'
                    },
                    function(data) {
                        $.post('" . Url::createUrl("checkout/cart/islogged") . "',function(data){
                            if (data) { 
                                $('#email').attr('disabled','disabled');
                                $('#firstname').attr('disabled','disabled');
                                $('#lastname').attr('disabled','disabled');
                                $('#company').attr('disabled','disabled');
                                $('#rif').attr('disabled','disabled');
                                $('#telephone').attr('disabled','disabled');
                                
                                $('#shipping_country_id').val( $('#payment_country_id').val() );
                                $('#shipping_zone_id').load('" . Url::createUrl("account/register/zone") . "&country_id='+ $('#payment_country_id').val() +'&zone_id='+ $('#payment_zone_id').val());
                                $('#shipping_street').val( $('#payment_street').val() );
                                $('#shipping_city').val( $('#payment_city').val() );
                                $('#shipping_postcode').val( $('#payment_postcode').val() );
                                $('#shipping_address_1').val( $('#payment_address_1').val() );
                                
                                $('#confirmCompany').text($('#company').val());
                                $('#confirmRif').text($('#rif').val());
                                
                               var confirmPaymentAddress = $('#payment_address_1').val() +' '+ $('#payment_street').val() +', '+ $('#payment_city').val() +'.';
                                $('#confirmPaymentAddress').text(confirmPaymentAddress);
                                $('#confirmShippingAddress').text(confirmPaymentAddress);
                            }
                        });
                    });
                    }";
                $scriptShippingAddress = "$.post('" . Url::createUrl("account/register/addAddress") . "', {
                        country_id: $('#shipping_country_id').val(),
                        zone_id: $('#shipping_zone_id').val(),
                        street: $('#shipping_street').val(),
                        city: $('#shipping_city').val(),
                        postcode: $('#shipping_postcode').val(),
                        address_1: $('#shipping_address_1').val(),
                        session_address_var: 'shipping_address_id'
                    },
                    function(data) {
                        var confirmShippingAddress = $('#shipping_address_1').val()  +' '+ $('#payment_street').val() +', '+ $('#shipping_city').val() +'.';
                        $('#confirmShippingAddress').text(confirmShippingAddress);
                    });";
            } elseif ($this->customer->isLogged() && !$this->data['shipping_country_id']) {
                $script = "$.post('" . Url::createUrl("account/register/addAddress") . "', {
                        country_id: $('#shipping_country_id').val(),
                        zone_id: $('#shipping_zone_id').val(),
                        street: $('#shipping_street').val(),
                        city: $('#shipping_city').val(),
                        postcode: $('#shipping_postcode').val(),
                        address_1: $('#shipping_address_1').val(),
                        session_address_var: 'shipping_address_id'
                    },
                    function(data) {
                        $('#payment_country_id').val($('#shipping_country_id').val());
                        $('#payment_zone_id').val($('#shipping_zone_id').val());
                        $('#payment_street').val($('#shipping_street').val());
                        $('#payment_city').val($('#shipping_city').val());
                        $('#payment_postcode').val($('#shipping_postcode').val());
                        $('#payment_address_1').val($('#shipping_address_1').val());
                        
                        var confirmShippingAddress = $('#shipping_address_1').val() +', '+ $('#shipping_city').val() +'.';
                        
                        $('#confirmShippingAddress').text(confirmShippingAddress);
                        $('#confirmPaymentAddress').text(confirmShippingAddress);
                    });";
            }

            $scripts[] = array('id' => 'scriptsCart', 'method' => 'ready', 'script' =>
                "$('#orderForm').ntForm({
                lockButton: false,
                cancelButton: false,
                submitButton: false
            });
            
            $('select[name=\'payment_zone_id\']').load('" . Url::createUrl("account/register/zone") . "&country_id=" . $address['country_id'] . "&zone_id=" . $address['zone_id'] . "');
            $('select[name=\'shipping_zone_id\']').load('" . Url::createUrl("account/register/zone") . "&country_id=" . $address['country_id'] . "&zone_id=" . $address['zone_id'] . "');
            $('#contentWrapper').ntWizard({
                next:function(data) {
                    var stepId = $('.neco-wizard-step-active').attr('id');
                    $(data.element).find('.neco-wizard-next').text('Siguiente');
                    $(data.element).find('.neco-wizard-prev').show();
                    
                    if (stepId == 'necoWizardStep_1') {
                        $(this).find('.neco-wizard-next').text('" . $this->data['button_checkout'] . "');
                        return false;
                    }
                    
                    if (stepId == 'necoWizardStep_2') {
                        var error = false;
                        var shippingMethods = " . (int) $this->data['shipping_methods'] . ";
                        var isLogged = " . (int) $this->customer->isLogged() . ";
                        var hasAddress = " . (int) $this->data['shipping_country_id'] . ";
                        
                        $(data.element).find('.neco-wizard-prev').show();
                        
                        /* si hay metodos de envios configurados y debe seleccionar uno */
                        if (shippingMethods && isLogged) {
                            var isChecked = $('input[name=shipping_method]:checked').val();
                                
                            if (!isChecked) {
                                error = true;
                                alert('Debes seleccionar un m\u00E9todo de env\u00EDo');
                            }
                        }
                        
                        if (!isLogged) {
                            $('#email,#firstname,#lastname,#company,#rif,#telephone,#city,#postcode,#address_1').each(function(e){
                                var value    = !!$(this).val();
                                var required = $(this).attr('required');
                                var type     = $(this).attr('type');
                                var top      = $(this).offset().top;
                                
                                if (!value) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('Debes rellenar todos los campos obligatorios identificados con asterisco (*)');
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'Debes rellenar este campo con la informaci\u00F3n correspondiente'});
                                }
                                    
                                var pattern = new RegExp(/.[\"\\\/\{\}\[\]\+']/i);
                                if (pattern.test($(this).val()) && !error) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('No se permiten ninguno de estos caracteres especiales [\"#$/\'+}{\u003C\u003E] en este formulario');
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'No se permiten ninguno de estos caracteres especiales [\"#$&/?\'+}{\u003C\u003E] en este campo'});
                                        top = $(this).offset().top;
                                }
                                    
                                if (type == 'email' && $(this).val()=='@') {
                                    error = true;
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'Debes ingresar una direcci\u00F3n de email v\u00E1lida'});
                                }
                                
                                if (type == 'email') {
                                    pattern = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,4})|(aero|coop|info|museum|name))$/i;
                                    $(this).on('change',function(event){
                                        err = checkPattern(pattern,$(this).val());
                                        if (!err) {
                                            $(this).removeClass('neco-input-success').addClass('neco-input-error');
                                            $(this).parent().find('.neco-form-error').attr({'title':\"Debes ingresar una direcci\u00F3n de email v\u00E1lida y que exista realmente\"});
                                            error = true;
                                        } else {
                                            $(this).parent().find('.neco-form-error').attr({'title':\"No hay errores en este campo\"});
                                            $(this).addClass('neco-input-success').removeClass('neco-input-error');
                                        }
                                    });
                                }
                                    
                                if (type == 'rif') {
                                    var pattern = /\b[JGVE]-[0-9]{8}-[0-9]{1}\b/i;
                                    $(this).on('change',function(event){
                                        err = checkPattern(pattern,$(this).val());
                                        if (!err) {
                                            $(this).parent().find('.neco-form-error').attr({'title':\"Debes ingresar un n\u00FAmero de C\u00E9dula o RIF v\u00E1lido para poder continuar\"});
                                            $(this).removeClass('neco-input-success').addClass('neco-input-error');
                                            error = true;
                                        } else {
                                            $(this).parent().find('.neco-form-error').attr({'title':\"No hay errores en este campo\"});
                                            $(this).addClass('neco-input-success').removeClass('neco-input-error');
                                        }
                                    });
                                }
                                    
                                if ($(this).hasClass('neco-input-error') && !error) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('Hay errores en el formulario, por favor revise y corr\u00EDjalos todos para poder continuar');
                                }                        
                            });
                                
                            if (!error) { " . $script . " }
                        }
                        
                        if (isLogged && !hasAddress) {
                            $('#shipping_country_id,#shipping_zone_id,#shipping_city,#shipping_street,#shipping_postcode,#shipping_address_1').each(function(e){
                                var value    = !!$(this).val();
                                var required = $(this).attr('required');
                                var type     = $(this).attr('type');
                                var top      = $(this).offset().top;
                                
                                if (!value) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('Debes rellenar todos los campos obligatorios identificados con asterisco (*)');
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'Debes rellenar este campo con la informaci\u00F3n correspondiente'});
                                }
                                    
                                var pattern = new RegExp(/.[\"\\\/\{\}\[\]\+']/i);
                                if (pattern.test($(this).val()) && !error) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('No se permiten ninguno de estos caracteres especiales [\"#$/\'+}{\u003C\u003E] en este formulario');
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'No se permiten ninguno de estos caracteres especiales [\"#$&/?\'+}{\u003C\u003E] en este campo'});
                                    top = $(this).offset().top;
                                }
                                    
                                if ($(this).hasClass('neco-input-error') && !error) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('Hay errores en el formulario, por favor revise y corr\u00EDjalos todos para poder continuar');
                                }                        
                            });
                                
                            if (!error) { " . $script . " }
                        }
                        
                        if(isLogged && hasAddress && !shippingMethods) {
                            $.post('" . Url::createUrl("checkout/confirm") . "',
                                $('#orderForm').serialize(),
                                function(){
                                    location.href = '" . Url::createUrl("checkout/success") . "';
                                }
                            );
                        }
                        return error;
                    } 
                    
                    if (stepId == 'necoWizardStep_3') {
                        var error = false;
                        var shippingMethods = " . (int) $this->data['shipping_methods'] . ";
                        var hasAddress = " . (int) $this->data['shipping_country_id'] . ";
                        var isLogged = " . (int) $this->customer->isLogged() . ";
                        
                        /* si hay metodos de envios configurados y debe seleccionar uno */
                        if (shippingMethods) {
                            var isChecked = $('input[name=shipping_method]:checked').val();
                                
                            if (!isChecked) {
                                error = true;
                                alert('Debes seleccionar un m\u00E9todo de env\u00EDo');
                            }
                        }
                        
                        if (!isLogged || !hasAddress) {
                            $('#shipping_country_id,#shipping_zone_id,#shipping_city,#shipping_street,#shipping_postcode,#shipping_address_1').each(function(e){
                                var value    = !!$(this).val();
                                var required = $(this).attr('required');
                                var type     = $(this).attr('type');
                                var top      = $(this).offset().top;
                                
                                if (!value) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('Debes rellenar todos los campos obligatorios identificados con asterisco (*)');
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'Debes rellenar este campo con la informaci\u00F3n correspondiente'});
                                }
                                    
                                var pattern = new RegExp(/.[\"\\\/\{\}\[\]\+']/i);
                                if (pattern.test($(this).val()) && !error) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('No se permiten ninguno de estos caracteres especiales [\"#$/\'+}{\u003C\u003E] en este formulario');
                                    $(this).removeClass('neco-input-success').addClass('neco-input-error')
                                        .parent()
                                        .find('.neco-form-error')
                                        .attr({'title':'No se permiten ninguno de estos caracteres especiales [\"#$&/?\'+}{\u003C\u003E] en este campo'});
                                        top = $(this).offset().top;
                                }
                                    
                                if ($(this).hasClass('neco-input-error') && !error) {
                                    error = true;
                                    $(\"#tempError\").remove();
                                    msg = $(document.createElement('p')).attr('id','tempError').addClass('neco-submit-error').text('Hay errores en el formulario, por favor revise y corr\u00EDjalos todos para poder continuar');
                                }                        
                            });
                                
                            if (!error) { " . $scriptShippingAddress . " }
                        } else {
                            $.post('" . Url::createUrl("checkout/confirm") . "',
                                $('#orderForm').serialize(),
                                function(){
                                    location.href = '" . Url::createUrl("checkout/success") . "';
                                }
                            );
                        }
                        return error;
                    }
                    
                    if (stepId == 'necoWizardStep_4') {
                        var error = false;
                        var isLogged = " . (int) $this->customer->isLogged() . ";
                        if (isLogged==0) {
                            $.post('" . Url::createUrl("checkout/confirm") . "',
                                $('#orderForm').serialize(),
                                function(){
                                    location.href = '" . Url::createUrl("checkout/success") . "';
                                }
                            );
                        }
                        return error;
                    }
                },
                prev:function(data) {
                    var stepId = $('.neco-wizard-step-active').attr('id');
                    if (stepId == 'necoWizardStep_2') {
                        $('#contentWrapper').find('.neco-wizard-prev').hide();
                        $('#contentWrapper').find('.neco-wizard-next').text('Procesar Pedido');
                        $(this).find('.neco-wizard-next').text('" . $this->data['button_checkout'] . "');
                    }
                    if (stepId == 'necoWizardStep_3') {
                        $('input').each(function(){ 
                            $(this).removeClass('neco-input-success'); 
                        }); 
                    }
                },
                create: function(e) {
                    $(e).find('.neco-wizard-next').text('" . $this->data['button_checkout'] . "');
                }
            }).find('.neco-wizard-next').text('Procesar Pedido');
            $('#contentWrapper').find('.neco-wizard-prev').hide();
                    
            $('input[name=shipping_method]').on('change',function(e){
                var tr = $('input[name=shipping_method]:checked').closest('tr');
                var title = tr.find('b:eq(0)').text();
                var price = tr.find('b:eq(1)').text();
                $('#shipping_method').html(title + ' ' + price);
            });
            ");

            $scripts[] = array('id' => 'functionsCart', 'method' => 'function', 'script' =>
                "function deleteCart(e,k) {
                $('#totals').html('<img src=\"" . HTTP_IMAGE . "load.gif\" alt=\"Cargando...\" />'); 
                $(e).closest('tr').remove();
                $('#confirmItem'+ k).remove();
                $.getJSON('" . Url::createUrl('checkout/cart/delete') . "',
                    {
                        key:k
                    },
                    function(data){
                        if (!data.error) { 
                            $('#weight').html(data.weight);
                            $('#totals').html(data.totals);
                            $('#totalsConfirm').html(data.totals); 
                        } else { 
                            $('#cart').html(data.error); 
                            $('#weight').html('0.00kg');
                        } 
                    }
                );
            }
            function refreshCart(e,k) {
                $('#totals').html('<img src=\"" . HTTP_IMAGE . "load.gif\" alt=\"Cargando...\" />'); 
                if (e.tagName != 'INPUT') {
                    e = $(e).prev('input');
                }
                var price = $(e).closest('tr').find('td:nth-child(6)');
                
                var values = price.text().split(',');
                values[0] = values[0].replace(/\./g,'').replace(/\D+/,'');
                var intValue = Number( values[0].replace(/[^0-9\.]+/g,'') );
                var floatValue = parseInt( Number( values[1].replace(/[^0-9\.]+/g,'') ) );
                price = parseFloat( intValue + '.' + floatValue );
                var totalValue = Math.round( (parseInt( $(e).val() ) * price) * 100) / 100;
                $(e).closest('tr').find('td:nth-child(7)').text('Bs. ' + totalValue );
                
                $('#confirmTotal'+ k).text( 'Bs. ' + totalValue );
                $('#confirmQty'+ k).text( $(e).val() );
                
                $.getJSON('" . Url::createUrl('checkout/cart/refresh') . "',
                    {
                        key:k,
                        quantity:$(e).val()
                    },
                    function(data){
                        if (!data.error) { 
                            $('#weight').html(data.weight);
                            $('#totals').html(data.totals); 
                            $('#totalsConfirm').html(data.totals); 
                        } else { 
                            $('#cart').html(data.error); 
                            $('#weight').html('0.00kg');
                        } 
                    }
                );
            }
            function checkPattern(pat,value) {
                pattern = new RegExp(pat);
                return pattern.test(value);  
            }");

            $this->scripts = array_merge($this->scripts, $scripts);

            // javascript files
            $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;

            $javascripts[] = $jspath . "necojs/neco.form.js";
            $javascripts[] = $jspath . "necojs/neco.wizard.js";
            $javascripts[] = $jspath . "vendor/jquery-ui.min.js";
            $this->javascripts = array_merge($this->javascripts, $javascripts);

            // style files
            $csspath = defined("CDN") ? CDN . CSS : HTTP_CSS;

            $styles[] = array('media' => 'all', 'href' => $csspath . 'jquery-ui/jquery-ui.min.css');
            $styles[] = array('media' => 'all', 'href' => $csspath . 'neco.form.css');
            $styles[] = array('media' => 'all', 'href' => $csspath . 'neco.wizard.css');

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

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/checkout/cart.tpl')) {
                $this->template = $this->config->get('config_template') . '/checkout/cart.tpl';
            } else {
                $this->template = 'choroni/checkout/cart.tpl';
            }

            $this->children[] = 'common/column_left';
            $this->children[] = 'common/column_right';
            $this->children[] = 'common/nav';
            $this->children[] = 'common/header';
            $this->children[] = 'common/footer';

            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');
            $this->data['text_error'] = $this->language->get('text_error');
            $this->data['button_continue'] = $this->language->get('button_continue');
            $this->data['continue'] = Url::createUrl("common/home");

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

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/error/not_found.tpl';
            } else {
                $this->template = 'choroni/error/not_found.tpl';
            }

            $this->children[] = 'common/column_left';
            $this->children[] = 'common/column_right';
            $this->children[] = 'common/nav';
            $this->children[] = 'common/header';
            $this->children[] = 'common/footer';

            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
    }

    public function json() {
        $this->language->load('checkout/cart');

        $product_id = ($this->request->hasQuery('product_id')) ? $this->request->getQuery('product_id') : $this->request->getPost('product_id');
        $quantity = ($this->request->hasQuery('quantity')) ? $this->request->getQuery('quantity') : $this->request->getPost('quantity');

        /*
          if ($this->request->hasQuery('option')) {
          $option = $this->request->getQuery('option');
          } elseif ($this->request->hasPost('option')) {
          $option = $this->request->getPost('option');
          } else {
          $option = array();
          }
         */

        if ($product_id && $quantity && $this->customer->isLogged()) {
            if (!is_array($quantity)) {
                $this->cart->add($product_id, $quantity);
            } else {
                foreach ($quantity as $key => $value) {
                    $this->cart->update($key, $value);
                }
            }

            $this->session->clear('shipping_methods');
            $this->session->clear('shipping_method');
            $this->session->clear('payment_methods');
            $this->session->clear('payment_method');
            $this->session->clear('shipping_address_id');

            $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('checkout/extension');
            $sort_order = array();
            $results = $this->modelExtension->getExtensions('total');
            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
            }
            array_multisort($sort_order, SORT_ASC, $results);
            foreach ($results as $result) {
                $this->load->model('total/' . $result['key']);
                $this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
            }
            $sort_order = array();
            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }
            array_multisort($sort_order, SORT_ASC, $total_data);

            $data = array();

            $data['customer_id'] = $this->customer->getId();
            $data['customer_group_id'] = $this->customer->getCustomerGroupId();
            $data['firstname'] = $this->customer->getFirstName();
            $data['lastname'] = $this->customer->getLastName();
            $data['email'] = $this->customer->getEmail();
            $data['telephone'] = $this->customer->getTelephone();

            $this->load->model('account/address');
            $this->load->model('account/customer');
            $this->load->model('checkout/order');

            $payment_address = $this->modelAddress->getAddress($this->customer->getAddressId());

            $data['payment_company'] = $data['shipping_company'] = $this->customer->getCompany();
            $data['payment_rif'] = $data['shipping_rif'] = $this->customer->getRif();
            $data['payment_firstname'] = $data['shipping_firstname'] = $this->customer->getFirstName();
            $data['payment_lastname'] = $data['shipping_lastname'] = $this->customer->getLastName();
            $data['payment_email'] = $data['shipping_email'] = $this->customer->getEmail();
            $data['payment_telephone'] = $data['shipping_telephone'] = $this->customer->getTelephone();
            $data['payment_address_1'] = $data['shipping_address_1'] = $payment_address['address_1'];
            $data['payment_address_2'] = $data['shipping_address_2'] = $payment_address['address_2'];
            $data['payment_city'] = $data['shipping_city'] = $payment_address['city'];
            $data['payment_postcode'] = $data['shipping_postcode'] = $payment_address['postcode'];
            $data['payment_zone'] = $data['shipping_zone'] = $payment_address['zone'];
            $data['payment_zone_id'] = $data['shipping_zone_id'] = $payment_address['zone_id'];
            $data['payment_country'] = $data['shipping_country'] = $payment_address['country'];
            $data['payment_country_id'] = $data['shipping_country_id'] = $payment_address['country_id'];
            $data['payment_address_format'] = $payment_address['address_format'];

            if ($this->session->has('payment_method', 'title')) {
                $data['payment_method'] = $this->session->get('payment_method', 'title');
            } else {
                $data['payment_method'] = '';
            }

            $product_data = array();

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    $option_data[] = array(
                        'product_option_value_id' => $option['product_option_value_id'],
                        'name' => $option['name'],
                        'value' => $option['value'],
                        'prefix' => $option['prefix']
                    );
                }

                $product_data[] = array(
                    'product_id' => $product['product_id'],
                    'seller_id' => $product['owner_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'download' => $product['download'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'tax' => $this->tax->getRate($product['tax_class_id'])
                );
            }

            $data['products'] = $product_data;
            $data['totals'] = $total_data;
            $data['comment'] = $this->request->getPost('comment');
            $data['total'] = $total;
            $data['language_id'] = $this->config->get('config_language_id');
            $data['currency_id'] = $this->currency->getId();
            $data['currency'] = $this->currency->getCode();
            $data['value'] = $this->currency->getValue($this->currency->getCode());
            $data['ip'] = $this->request->server['REMOTE_ADDR'];

            if ($this->request->getPost('coupon')) {
                $this->load->model('checkout/coupon');
                $coupon = $this->modelCoupon->getCoupon($this->session->get('coupon'));
                $data['coupon_id'] = ($coupon) ? $coupon['coupon_id'] : 0;
            } else {
                $data['coupon_id'] = 0;
            }

            $json['order_id'] = $this->modelOrder->create($data);
            if ($json['order_id']) {
                $this->session->set('order_id', $order_id);
                $this->modelOrder->confirm($order_id, $this->config->get('cheque_order_status_id'), $data['comment']);
                $json['success'] = 1;
                $json['msg'] = $this->language->get('text_add_to_cart_success');
                $this->notify($json['order_id'], $data);
                $this->cart->clear();
            }
        } else {
            $json['error'] = 1;
            $json['msg'] = $this->language->get('error_add_to_cart');
        }

        $this->load->library('json');
        $this->response->setOutput(Json::encode($json), $this->config->get('config_compression'));
    }

    protected function notify($order_id, $data) {
        // enviar informaci�n de pago y pasos a seguir configurados por el anunciante al comprador
        /**
         * 1. crear m�dulo de marketing del cliente donde pueda:
         *  - configurar cuentas de email para el env�o de correos masivos
         *  - crear y asociar las plantillas de email
         *  - comprar y seleccionar plantillas web para su tienda virtual
         *  - comprar una cuenta de email de necotienda o del sitio
         *  - comprar m�dulos y extensiones para su tienda
         *      - auto publicar en las redes sociales
         *      - auto promocionar los productos nuevos
         *      - env�o masivo de emails
         *      - 
         * */

        if (!$order_id)
            return false;
        $this->load->auto('email/mailer');
        $this->load->model('account/order');
        $this->load->auto('marketing/newsletter');
        $this->load->auto('BarcodeQR');
        $this->load->auto('Barcode39');
        $this->language->load('checkout/cart');

        $mailer = new Mailer;
        $qr = new BarcodeQR;
        $barcode = new Barcode39(C_CODE);

        $seller_info = $this->modelCustomer->getCustomer($data['products'][0]['seller_id']);
        
        $text = $this->config->get('config_title') . "\n";
        $text .= "Pedido ID: " . $order_id . "\t Fecha Emision: " . date('d-m-Y') . "\n";
        $text .= "Datos del Vendedor\n";
        $text .= $seller_info['company'] . " " . $seller_info['rif'] . "\n";
        $text .= $seller_info['email'] . " " . $seller_info['telephone'] . "\n";
        $text .= "Datos del Cliente\n";
        $text .= $this->customer->getCompany() . " " . $this->customer->getRif() . "\n";
        $text .= "Direccion IP: " . $data['ip'] . "\n";
        $text .= "Productos (" . count($data['products']) . ")\n";
        $text .= "Modelo\tCant.\tTotal\n";

        foreach ($data['products'] as $key => $product) {
            $text .= $product['model'] . "\t" .
                    $product['quantity'] . "\t" .
                    $this->currency->format($product['total'], $data['currency'], $data['value']) . "\n";
        }
        
        $image_prefix = str_replace(array(".", " ", ","), "_", $this->config->get('config_owner'));
        
        $qrStore = "cache/" . $image_prefix . '.png';
        $qrOrder = "cache/" . $image_prefix . "_qr_code_order_" . $order_id . '.png';
        $eanStore = "cache/" . $image_prefix . "_barcode_39_order_id_" . $order_id . '.gif';

        $qr->text($text);
        $qr->draw(250, DIR_IMAGE . $qrOrder);
        $qr->url(HTTP_HOME);
        $qr->draw(150, DIR_IMAGE . $qrStore);
        $barcode->draw(DIR_IMAGE . $eanStore);

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

        if ($this->config->get('marketing_email_seller_new_order') && $seller_info) {
            $result = $this->modelNewsletter->getById($this->config->get('marketing_email_seller_new_order'));
            $message = $result['htmlbody'];

            $message = str_replace("{%store_logo%}", '<img src="' . HTTP_IMAGE . $this->config->get('config_logo') . '" alt="' . $this->config->get('config_name') . '" />', $message);
            $message = str_replace("{%store_url%}", HTTP_HOME, $message);
            $message = str_replace("{%store_owner%}", $this->config->get('config_owner'), $message);
            $message = str_replace("{%store_name%}", $this->config->get('config_name'), $message);
            $message = str_replace("{%store_rif%}", $this->config->get('config_rif'), $message);
            $message = str_replace("{%store_email%}", $this->config->get('config_email'), $message);
            $message = str_replace("{%store_telephone%}", $this->config->get('config_telephone'), $message);
            $message = str_replace("{%store_address%}", $this->config->get('config_address'), $message);
            $message = str_replace("{%date_added%}", date('d-m-Y'), $message);
            $message = str_replace("{%order_id%}", $this->config->get('config_invoice_prefix') . $order_id, $message);

            $message = str_replace("{%qr_code_order%}", '<img src="' . HTTP_IMAGE . $qrOrder . '" alt="QR Order Code" />', $message);
            $message = str_replace("{%qr_code_store%}", '<img src="' . HTTP_IMAGE . $qrStore . '" alt="QR Store Code" />', $message);
            $message = str_replace("{%barcode_39_order_id%}", '<img src="' . HTTP_IMAGE . $eanStore . '" alt="NecoTienda Client Code" />', $message);

            $message .= "<p style=\"text-align:center\">Powered By <a href=\"http://www.necotienda.org\">Necotienda</a> " . date('Y') . "</p>";

            $subject = $this->language->get('text_new_order');

            $mailer->AddAddress($seller_info['email'], $seller_info['company']);
            $mailer->SetFrom($this->config->get('config_email'), $this->config->get('config_name'));
            $mailer->Subject = $subject;
            $mailer->Body = html_entity_decode($message);
            $mailer->Send();
        }

        if ($this->config->get('marketing_email_new_order') && $seller_info) {
            $shipping_address = $data['shipping_address_1'] . ", " . $data['shipping_city'] . ". " . $data['shipping_zone'] . " - " . $data['shipping_country'] . ". CP " . $data['shipping_zone_code'];
            $payment_address = $data['payment_address_1'] . ", " . $data['payment_city'] . ". " . $data['payment_zone'] . " - " . $data['payment_country'] . ". CP " . $data['payment_zone_code'];

            $product_html = "<table><thead><tr style=\"background:#ccc;color:#666;\"><th>Item</th><th>" . $this->language->get('column_description') . "</th><th>" . $this->language->get('column_model') . "</th><th>" . $this->language->get('column_quantity') . "</th><th>" . $this->language->get('column_price') . "</th><th>" . $this->language->get('column_total') . "</th></tr></thead><tbody>";
            foreach ($data['products'] as $key => $product) {
                $options = $this->modelOrder->getOrderOptions($data['order_id'], $product['order_product_id']);
                $option_data = "";
                foreach ($options as $option) {
                    $option_data .= "&nbsp;&nbsp;&nbsp;&nbsp;- " . $option['name'] . "<br />";
                }
                $product_html .= "<tr>";
                $product_html .= "<td style=\"width:5%\">" . (int) ($key + 1) . "</td>";
                $product_html .= "<td style=\"width:45%\">" . $product['name'] . "<br />" . $option_data . "</td>";
                $product_html .= "<td style=\"width:20%\">" . $product['model'] . "</td>";
                $product_html .= "<td style=\"width:10%\">" . $product['quantity'] . "</td>";
                $product_html .= "<td style=\"width:10%\">" . $this->currency->format($product['price'], $data['currency'], $data['value']) . "</td>";
                $product_html .= "<td style=\"width:10%\">" . $this->currency->format($product['total'], $data['currency'], $data['value']) . "</td>";
                $product_html .= "</tr>";
            }
            $product_html .= "</tbody></table>";

            $total_html = "<div class=\"clear:both;float:none;\"></div><br /><table style=\"float:right;\">";
            foreach ($data['totals'] as $total) {
                $total_html .= "<tr>";
                $total_html .= "<td style=\"text-align:right;\">" . $total['title'] . "</td>";
                $total_html .= "<td style=\"text-align:right;\">" . $total['text'] . "</td>";
                $total_html .= "</tr>";
            }
            $total_html .= "</table>";

            $result = $this->modelNewsletter->getById($this->config->get('marketing_email_new_order'));
            $message = $result['htmlbody'];

            $message = str_replace("{%title%}", 'Pedido N&deg; ' . $order_id . " - " . $this->config->get('config_name'), $message);
            $message = str_replace("{%store_logo%}", '<img src="' . HTTP_IMAGE . $this->config->get('config_logo') . '" alt="' . $this->config->get('config_name') . '" />', $message);
            $message = str_replace("{%store_url%}", HTTP_HOME, $message);
            $message = str_replace("{%store_owner%}", $this->config->get('config_owner'), $message);
            $message = str_replace("{%store_name%}", $this->config->get('config_name'), $message);
            $message = str_replace("{%store_rif%}", $this->config->get('config_rif'), $message);
            $message = str_replace("{%store_email%}", $this->config->get('config_email'), $message);
            $message = str_replace("{%store_telephone%}", $this->config->get('config_telephone'), $message);
            $message = str_replace("{%store_address%}", $this->config->get('config_address'), $message);
            
            // Seller
            $message = str_replace("{%seller_logo%}", '<img src="' . HTTP_IMAGE . $seller_info['photo'] . '" alt="' . $this->config->get('config_name') . '" />', $message);
            $message = str_replace("{%seller_url%}", HTTP_HOME . 'shop/'. $seller_info['url'], $message);
            $message = str_replace("{%seller_name%}", $seller_info['company'], $message);
            $message = str_replace("{%seller_rif%}", $seller_info['rif'], $message);
            $message = str_replace("{%seller_email%}", $seller_info['email'], $message);
            $message = str_replace("{%seller_telephone%}", $seller_info['telephone'], $message);
            //$message = str_replace("{%seller_address%}", $seller_info[''], $message);
            
            $message = str_replace("{%products%}", $product_html, $message);
            $message = str_replace("{%totals%}", $total_html, $message);
            $message = str_replace("{%order_id%}", $this->config->get('config_invoice_prefix') . $order_id, $message);
            $message = str_replace("{%invoice_id%}", $this->config->get('config_invoice_prefix') . $invoice_id, $message);
            $message = str_replace("{%rif%}", $this->customer->getRif(), $message);
            $message = str_replace("{%fullname%}", $this->customer->getFirstName() . " " . $this->customer->getFirstName(), $message);
            $message = str_replace("{%company%}", $this->customer->getCompany(), $message);
            $message = str_replace("{%email%}", $this->customer->getEmail(), $message);
            $message = str_replace("{%telephone%}", $this->customer->getTelephone(), $message);
            $message = str_replace("{%payment_address%}", $payment_address, $message);
            $message = str_replace("{%payment_method%}", $data['payment_method'], $message);
            $message = str_replace("{%shipping_address%}", $shipping_address, $message);
            $message = str_replace("{%shipping_method%}", $data['shipping_method'], $message);
            $message = str_replace("{%date_added%}", date('d-m-Y'), $message);
            $message = str_replace("{%ip%}", $data['ip'], $message);
            $message = str_replace("{%comment%}", $data['comment'], $message);
            
            $message = str_replace("{%qr_code_order%}", '<img src="' . HTTP_IMAGE . $qrOrder . '" alt="QR Order Code" />', $message);
            $message = str_replace("{%qr_code_store%}", '<img src="' . HTTP_IMAGE . $qrStore . '" alt="QR Store Code" />', $message);
            $message = str_replace("{%barcode_39_order_id%}", '<img src="' . HTTP_IMAGE . $eanStore . '" alt="NecoTienda Client Code" />', $message);

            $message .= "<p style=\"text-align:center\">Powered By <a href=\"http://www.necotienda.org\">Necotienda</a> " . date('Y') . "</p>";

            $subject = $this->language->get('text_new_order');
            
            $mailer->ClearAllRecipients();
            $mailer->AddAddress($this->customer->getEmail(), $this->customer->getCompany());
            $mailer->SetFrom($this->config->get('config_email'), $this->config->get('config_name'));
            $mailer->Subject = $subject;
            $mailer->Body = html_entity_decode($message);
            $mailer->Send();
        }
        $this->cart->clear();

        $this->session->clear('shipping_method');
        $this->session->clear('shipping_methods');
        $this->session->clear('payment_method');
        $this->session->clear('payment_methods');
        $this->session->clear('guest');
        $this->session->clear('comment');
        $this->session->clear('order_id');
        $this->session->clear('coupon');
    }

    protected function updateCart() {
        $this->load->auto('weight');
        $this->load->auto('cart');
        $this->load->auto('json');
        $this->load->auto('checkout/extension');
        $data = array();
        if ($this->cart->getProducts()) {
            if ($this->config->get('config_cart_weight')) {
                $data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class'));
            } else {
                $data['weight'] = false;
            }

            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();


            $sort_order = array();

            $results = $this->modelExtension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                $this->load->model('total/' . $result['key']);
                $this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
            }
            $sort_order = array();
            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
            $output = "";
            foreach ($total_data as $value) {
                $output .= "<tr>";
                $output .= "<td><b>" . $value['title'] . "</b></td>";
                $output .= "<td>" . $value['text'] . "</td>";
                $output .= "</tr>";
            }
            $data['totals'] = $output;
        } else {
            $data['error'] = "No hay productos en el carrito";
        }
        return Json::encode($data);
    }

    public function callback() {
        $this->language->load('module/cart');

        $output = '<table>';

        if ($this->cart->getProducts()) {
            foreach ($this->cart->getProducts() as $product) {
                $output .= '<tr>';
                //$output .= '<td class="cartRemove" id="remove_ ' . $product['key'] . '">&nbsp;</td>';
                $output .= '<td>' . $product['quantity'] . '&nbsp;x&nbsp;' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))) . '</td>';
                $output .= '<td>';
                $output .= '<a href="' . Url::createUrl("store/product", array("product_id" => $product['product_id'])) . '">' . $product['name'] . '</a>';

                if ($product['option']) {
                    $output .= '<div>';
                    foreach ($product['option'] as $option) {
                        $output .= ' - <small>' . $option['name'] . ' ' . $option['value'] . '</small><br />';
                    }
                    $output .= '</div>';
                }
                $output .= '</td>';
                $output .= '</tr>';
            }
            $output .= '</table>';
            $output .= '<br />';

            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('checkout/extension');
            $sort_order = array();
            $results = $this->modelExtension->getExtensions('total');
            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
            }
            array_multisort($sort_order, SORT_ASC, $results);
            foreach ($results as $result) {
                $this->load->model('total/' . $result['key']);

                $this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
            }
            $sort_order = array();
            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }
            array_multisort($sort_order, SORT_ASC, $total_data);

            $output .= '<table>';
            foreach ($total_data as $total) {
                $output .= '<tr>';
                $output .= '<td><strong>' . $total['title'] . '</strong></td>';
                $output .= '<td>' . $total['text'] . '</td>';
                $output .= '</tr>';
            }
            $output .= '</table>';
            $output .= '<div><a style="width:95%" class="button" href="' . Url::createUrl("checkout/cart") . '">' . $this->language->get('text_cart') . '</a></div>';
        } else {
            $output .= '<div style="text-align: center;">' . $this->language->get('text_empty') . '</div>';
        }

        $this->response->setOutput($output, $this->config->get('config_compression'));
    }

    public function refresh() {
        $this->cart->update($this->request->get['key'], $this->request->get['quantity']);
        $this->response->setOutput($this->updateCart(), $this->config->get('config_compression'));
    }

    public function delete() {
        $this->cart->remove($_GET['key']);
        $this->response->setOutput($this->updateCart(), $this->config->get('config_compression'));
    }

    public function islogged() {
        echo (int) $this->customer->islogged();
    }

}
