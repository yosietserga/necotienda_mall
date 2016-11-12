<?php

class ControllerSaleCreate extends Controller {

    var $error = array();

    public function index() {
        $this->language->load('sale/create');

        if (!$this->customer->isLogged()) {
            $this->session->set('redirect', Url::createUrl('sale/create'));
            $this->session->set('message', $this->language->get('text_you_have_to_be_logged'));
            $this->redirect(Url::createUrl('account/login'));
        }

        if ($this->customer->isBanned()) {
            $this->session->set('redirect', Url::createUrl('sale/create'));
            $this->redirect(Url::createUrl('account/banned'));
        }

        if (!$this->customer->canPublish()) {
            $this->session->set('redirect', Url::createUrl('sale/create'));
            $this->redirect(Url::createUrl('account/permissions', array('can_publish' => 0)));
        }

        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');
        $this->load->model('store/category');
        $this->load->model('store/product');
        $this->load->model('sale/plan');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->request->post['Images'] = $this->upload();
            if (is_array($this->request->post['Images'])) {
                if (!empty($this->request->post['description'])) {
                    $dom = new DOMDocument;
                    $dom->preserveWhiteSpace = false;
                    $dom->loadHTML(html_entity_decode($this->request->post['description']));
                    $images = $dom->getElementsByTagName('img');
                    foreach ($images as $image) {
                        $src = $image->getAttribute('src');
                        if (preg_match('/data:([^;]*);base64,(.*)/', $src)) {
                            $image->removeChild();
                        }
                    }

                    $this->request->post['description'] = htmlentities($dom->saveHTML());
                    $html = html_entity_decode($this->request->post['description']);
                    $html = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', '', $html);
                    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
                    $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);
                    $html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $html);
                    $html = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is', '', $html);
                    $html = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is', '', $html);
                    $html = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is', '', $html);
                    $html = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is', '', $html);
                    $html = preg_replace('/<noembed\b[^>]*>(.*?)<\/noembed>/is', '', $html);
                    $this->request->post['description'] = htmlentities($html);
                }

                $token = mt_rand(1, 999) . $this->customer->getId();
                $keyword = str_replace('.' . $ext, '', $keyword);
                $keyword = $token . "-" . $this->config->get('config_name') . "-" . $this->request->post['name'];
                if ($keyword !== mb_convert_encoding(mb_convert_encoding($keyword, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                    $keyword = mb_convert_encoding($keyword, 'UTF-8', mb_detect_encoding($keyword));
                $keyword = htmlentities($keyword, ENT_NOQUOTES, 'UTF-8');
                $keyword = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $keyword);
                $keyword = html_entity_decode($keyword, ENT_NOQUOTES, 'UTF-8');
                $keyword = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $keyword);
                $keyword = strtolower(trim($keyword, '-'));

                $this->request->post['keyword'] = $keyword . ".html";
                $this->request->post['model'] =  md5(mt_rand(1, 999) . time() . uniqid() .$this->customer->getId());
                $this->request->post['price'] = str_replace(',', '.', str_replace('.', '', $this->request->getPost('price')));
                $plan = $this->modelPlan->getPlan($this->request->getPost('plan_id'));
                $product_id = $this->modelProduct->add($this->request->post);

                $data = array();

                $data['customer_id'] = $this->customer->getId();
                $data['customer_group_id'] = $this->customer->getCustomerGroupId();
                $data['firstname'] = $this->customer->getFirstName();
                $data['lastname'] = $this->customer->getLastName();
                $data['email'] = $this->customer->getEmail();
                $data['telephone'] = $this->customer->getTelephone();

                $this->load->model('account/address');
                $this->load->model('checkout/order');
                $this->load->model('sale/plan');

                $payment_address = $this->modelAddress->getAddress($this->customer->getId());

                $data['payment_company'] = $this->customer->getCompany();
                $data['payment_rif'] = $this->customer->getRif();
                $data['payment_firstname'] = $this->customer->getFirstName();
                $data['payment_lastname'] = $this->customer->getLastName();
                $data['payment_telephone'] = $this->customer->getTelephone();
                $data['payment_email'] = $this->customer->getEmail();
                $data['payment_address_1'] = $payment_address['address_1'];
                $data['payment_address_2'] = $payment_address['address_2'];
                $data['payment_city'] = $payment_address['city'];
                $data['payment_postcode'] = $payment_address['postcode'];
                $data['payment_zone'] = $payment_address['zone'];
                $data['payment_zone_id'] = $payment_address['zone_id'];
                $data['payment_country'] = $payment_address['country'];
                $data['payment_country_id'] = $payment_address['country_id'];
                $data['payment_address_format'] = $payment_address['address_format'];

                $data['shipping_firstname'] = '';
                $data['shipping_lastname'] = '';
                $data['shipping_company'] = '';
                $data['shipping_address_1'] = '';
                $data['shipping_address_2'] = '';
                $data['shipping_city'] = '';
                $data['shipping_postcode'] = '';
                $data['shipping_zone'] = '';
                $data['shipping_zone_id'] = '';
                $data['shipping_country'] = '';
                $data['shipping_country_id'] = '';
                $data['shipping_address_format'] = '';
                $data['shipping_method'] = '';

                $data['products'][] = array(
                    'product_id' => 0,
                    'name' => $this->language->get('text_sale_plan') . $plan['name'],
                    'model' => $plan['name'],
                    'price' => $plan['price'],
                    'total' => $plan['price'],
                    'tax' => 0,
                    'quantity' => 1,
                    'option' => array(
                        array(
                            'product_option_value_id' => 0,
                            'name' => $this->language->get('text_days'),
                            'value' => $plan['qty_days'],
                            'prefix' => '+'
                        ),
                        array(
                            'product_option_value_id' => 0,
                            'name' => $this->language->get('text_images'),
                            'value' => $plan['qty_images'],
                            'prefix' => '+'
                        ),
                        array(
                            'product_option_value_id' => 0,
                            'name' => $this->language->get('text_videos'),
                            'value' => $plan['qty_videos'],
                            'prefix' => '+'
                        ),
                        array(
                            'product_option_value_id' => 0,
                            'name' => $this->language->get('text_featured'),
                            'value' => ($plan['featured']) ? $this->language->get('text_yes') : $this->language->get('text_no'),
                            'prefix' => '+'
                        ),
                        array(
                            'product_option_value_id' => 0,
                            'name' => $this->language->get('text_show_in_home'),
                            'value' => ($plan['show_in_home']) ? $this->language->get('text_yes') : $this->language->get('text_no'),
                            'prefix' => '+'
                        ),
                        array(
                            'product_option_value_id' => 0,
                            'name' => 'Product ID',
                            'value' => $product_id,
                            'prefix' => '+'
                        ),
                        array(
                            'product_option_value_id' => 0,
                            'name' => $this->language->get('text_product_url'),
                            'value' => HTTP_HOME . $this->request->post['keyword'],
                            'prefix' => '+'
                        ),
                    )
                );

                $data['totals'][] = array(
                    'title' => 'Total',
                    'text' => $this->currency->format($plan['price']),
                    `value` => $plan['price'],
                    'sort_order' => 1
                );

                $data['comment'] = '';
                $data['total'] = $plan['price'];
                $data['language_id'] = $this->config->get('config_language_id');
                $data['currency_id'] = $this->currency->getId();
                $data['currency'] = $this->currency->getCode();
                $data['value'] = $this->currency->getValue($this->currency->getCode());
                $data['ip'] = $_SERVER['REMOTE_ADDR'];

                $order_id = $this->sendPaymentSteps($this->modelOrder->create($data));

                $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET 
                order_id = '" . (int) $order_id . "', 
                order_status_id = '1', 
                notify = '1', 
                comment = '" . $this->db->escape($data['comment']) . "', 
                date_added = NOW()");

                if (!$this->modelCustomer->getProperty($this->customer->getId(), 'rewards', 'first_post')) {
                    $this->modelCustomer->setProperty($this->customer->getId(), 'rewards', 'first_post', 1);
                    
                    $this->modelCustomer->addNecoexp($this->customer->getId(), 15);
                    $this->modelCustomer->addNecopoints($this->customer->getId(), 2);
                }
                
                $this->redirect(HTTP_HOME . $this->request->post['keyword'] . '?np=1');
            }
        }

        $this->data['error'] = $this->error;

        $this->data['categories'] = $this->modelCategory->getCategories(0);

        $this->data['products'] = $this->modelProduct->getProductsByCustomerId($this->customer->getId());
        $model = $this->modelProduct->getProduct($this->request->get['product_id']);

        foreach ($this->modelPlan->getPlans() as $plan) {
            if (!empty($plan['image']) && file_exists(DIR_IMAGE . $plan['image'])) {
                $image = NTImage::resizeAndSave($plan['image'], 100, 100);
            } else {
                $image = NTImage::resizeAndSave('no_image.jpg', 100, 100);
            }

            $this->data['plans'][] = array(
                'plan_id' => $plan['plan_id'],
                'name' => $plan['name'],
                'price' => $this->currency->format($this->tax->calculate($plan['price'], $this->config->get('config_taxt_id'), $this->config->get('config_tax'))),
                'image' => $image,
                'qty_days' => $plan['qty_days'],
                'qty_images' => $plan['qty_images'],
                'qty_videos' => $plan['qty_videos'],
                'featured' => $plan['featured'],
                'show_in_home' => $plan['show_in_home'],
                'sort_order' => $plan['sort_order']
            );
        }

        $this->setvar('name', $model, '');
        $this->setvar('model', $model, '');
        $this->setvar('price', $model, '');
        $this->setvar('description', $model, '');
        $this->setvar('stock_status_id', $model, '');
        $this->setvar('quantity', $model, '');
        $this->setvar('weight', $model, '');
        $this->setvar('images', $model, '');
        $this->setvar('properties', $model, '');

        $this->load->model('localisation/zone');
        $this->load->model('localisation/stockstatus');
        // shipping and payment methods
        $this->data['zones'] = $this->modelZone->getZonesByCountryId($this->config->get('config_country_id'));
        $this->data['stock_statuses'] = $this->modelStockstatus->getStockStatuses();

        /* shipping methods */
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
        $this->data['shipping_methods'] = $quote_data;
        /* /shipping methods */

        /* payment methods */
        $method_data = array();
        $results = $this->modelExtension->getExtensions('payment');
        foreach ($results as $result) {
            $this->load->model('payment/' . $result['key']);
            $this->language->load('payment/' . $result['key']);
            $method = $this->{'model_payment_' . $result['key']}->getMethod($address);
            if ($method) {
                $method_data[$result['key']] = $method;
            }
        }
        $sort_order = array();
        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
        array_multisort($sort_order, SORT_ASC, $method_data);
        $this->data['payment_methods'] = $method_data;
        /* /payment methods */

        $csspath = defined("CDN_CSS") ? CDN_CSS : HTTP_CSS;
        $styles[] = array('media' => 'screen', 'href' => $csspath . 'jquery-ui/jquery-ui.min.css');
        $styles[] = array('media' => 'screen', 'href' => $csspath . 'neco.form.css');
        $styles[] = array('media' => 'screen', 'href' => HTTP_JS . 'vendor/ckeditor/skins/moono/editor_gecko.css');

        $csspath = defined("CDN") ? CDN_CSS : HTTP_THEME_CSS;
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/common/header.tpl')) {
            $csspath = str_replace("%theme%", $this->config->get('config_template'), $csspath);
        } else {
            $csspath = str_replace("%theme%", "default", $csspath);
        }

        if (fopen($csspath . str_replace('controller', '', strtolower(__CLASS__) . '.css'), 'r')) {
            $styles[] = array('media' => 'all', 'href' => $csspath . str_replace('controller', '', strtolower(__CLASS__) . '.css'));
        }

        if ($styles)
            $this->styles = array_merge($styles, $this->styles);

        // javascript files
        $jspath = defined("CDN_JS") ? CDN_JS : HTTP_JS;
        $javascripts[] = $jspath . "vendor/jquery-ui.min.js";
        $javascripts[] = $jspath . "necojs/neco.form.js";
        $javascripts[] = $jspath . "vendor/ckeditor/ckeditor.js";
        $javascripts[] = $jspath . "plugins.js";
        if (count($javascripts))
            $this->javascripts = array_merge($this->javascripts, $javascripts);

        $scripts = array();
        $scripts[] = array('id' => 'register', 'method' => 'ready', 'script' =>
            "CKEDITOR.replace('description');
		    $('#formSale').ntForm();
            
            $('#neco-unlock-form').hide();
            
            var cache = {};
            $.getJSON( '" . Url::createUrl("store/category/callback") . "', function( data ) {
                $.each(data,function(i,item){
                    var category_id = item.id;
                    var name = item.label;
                    var value = item.id;
                    var label = item.label;
                    $(document.createElement('option')).attr({
                        value:value
                    })
                    .text(label)
                    .appendTo('#category_0');
                });
                $('#category_0').on('change',function(e){
                    $('.Categories').remove();
                    drawCategories($(this).val());
                    $('#category0').val($(this).val());
                });
            });");

        $scripts[] = array('id' => 'functios', 'method' => 'function', 'script' =>
            "function drawCategories(category_id) {
                if (typeof category_id != 'undefined' && category_id > 0) {
                    var idx = $('#categoriesWrapper select:last-child').index() + 1 * 1;
                    $('#categoriesWrapper').append('<select id=\"subcategory_'+ idx +'\" name=\"Categories[]\" size=\"30\" class=\"Categories\"><option>Cargando...</option></select>');

                    if (idx > 0) {
                        $('#categoriesWrapper').css('width', (idx * 300) +'px');
                    }
                    $('#subcategory_'+ idx)
                        .load('" . Url::createUrl("store/category/subcategories") . "&parent_id=' + category_id,function(data){
                            if (data.length == 0) {
                                $(this).remove();
                                if (idx > 0) {
                                    $('#categoriesWrapper').css('width', ((idx - 1) * 300) +'px');
                                }
                            }
                                
                            $.getJSON('" . Url::createUrl("store/category/attributes") . "&category_id=' + category_id,function(data){
                                if (typeof data.success != 'undefined') {
                                    $.each(data.items,function(i,item){
                                        div = $(document.createElement('div')).addClass('row');
                                        input = $(document.createElement('input'));
                                            
                                        if (item.name) {
                                            input.attr('name', 'Attributes['+ item.name +']');
                                        } else {
                                            input.attr('name', 'Attributes[attribute_'+ ($('.attributes:last-child').index() + 1) +']');
                                        }
                                            
                                        if (item.type) {
                                            input.attr('type', item.type);
                                        } else {
                                            input.attr('type', 'text');
                                        }
                                            
                                        if (item.value) {
                                            input.attr('value', item.value);
                                        } else {
                                            input.attr('value', '');
                                        }
                                            
                                        if (item.required) {
                                            input.attr('required', 'required');
                                        }
                                            
                                        if (item.class) {
                                            input.attr('class', item.class);
                                        }
                                            
                                        label = $(document.createElement('label')).attr({
                                            'text':item.label,
                                            'for':input.id
                                        });
                                            
                                        $(div).append('<label for=\"'+ item.name +'\" style=\"float:left;width:180px;\">'+ item.label +'</label>');
                                        $(div).append(input);
                                            
                                        $('#headingAttributes').show();
                                        $('#formAttributes').append(div);
                                        $('#formAttributes').append('<div class=\"clear\"></div>');
                                        input.ntInput();
                                    });
                                }
                            });
                        })
                        .change(function(e){
                            var idx_ = $(this).index();
                            $('#categoriesWrapper select').each(function(){
                                if ($(this).index() > idx_) {
                                    $(this).remove();
                                }
                            });
                            var id_ = $(this).val();
                            drawCategories(id_);
                        });
                }
            }
            
            function setPlan(plan_id,qty_days,qty_images,qty_videos,featured,in_home,price) {
                $('#plan_id').val(plan_id);
                $('#qty_days').val(qty_days);
                
                var countImages = $('#product_images li:last-child').index();
                if (countImages > qty_days) {
                    countImages = countImages - qty_images;
                    i=1;
                    for(i;i < countImages;i++) {
                        $('#product_images li:last-child').remove();
                    }
                }
                if (countImages < qty_days) {
                    countImages = qty_images - countImages;
                    i=1;
                    for(i;i < countImages;i++) {
                        $('#product_images').append('<li><input class=\"image\" type=\"file\" name=\"files[]\" id=\"image'+ i +'_\" value=\"\" showquick=\"off\" accept=\"image/gif, image/jpeg, image/png\" /><div id=\"preview'+ i +'\" class=\"uploadPreview\"></div><div class=\"clear\">&nbsp;</div></li>');
                    }
                }
                
                $('input.image').on('change',function(e){
                    var input = this;
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
	                    reader.onload = function (e) {
                            $(input).closest('li').prepend('<img src=\"'+ e.target.result +'\" width=\"100\" height=\"100\" />');		
	                    };
                        reader.readAsDataURL(input.files[0]);
                    }
                });
                
                $('#image0_').attr('required','required');
                
                if (featured) {
                    $('#formSale').append('<input type=\"hidden\" name=\"featured\" value=\"1\" />');
                } else {
                    $('input[name=featured]').remove();
                }
                
                if (in_home) {
                    $('#formSale').append('<input type=\"hidden\" name=\"homepage\" value=\"1\" />');
                } else {
                    $('input[name=homepage]').remove();
                }
                
                $('#step1').hide();
                $('#step2').fadeIn();
                $('#neco-unlock-form').hide();
            }");

        $this->scripts = array_merge($scripts, $this->scripts);

        $this->children[] = 'common/column_left';
        $this->children[] = 'common/column_right';
        $this->children[] = 'common/nav';
        $this->children[] = 'common/header';
        $this->children[] = 'common/footer';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/sale/create.tpl')) {
            $this->template = $this->config->get('config_template') . '/sale/create.tpl';
        } else {
            $this->template = 'default/sale/create.tpl';
        }

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    protected function validate() {
        if (empty($this->request->post['name'])) {
            $this->error['name'] = $this->language->get('error_name_empty');
        }

        if (empty($this->request->post['description'])) {
            $this->error['description'] = $this->language->get('error_description_empty');
        }

        if (!(int) $this->request->post['plan_id']) {
            $this->error['plan_id'] = $this->language->get('error_plan_id');
        }

        if (!(int) $this->request->post['quantity']) {
            $this->error['quantity'] = $this->language->get('error_quantity_empty');
        }

        if (empty($this->request->files['files']['name'])) {
            $this->error['image'] = $this->language->get('error_image_empty');
        }

        $desc = $this->request->post['description'];
        if ($desc !== mb_convert_encoding(mb_convert_encoding($desc, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32')) {
            $desc = mb_convert_encoding($desc, 'UTF-8', mb_detect_encoding($desc));
        }

        if ((strlen($desc) / 1024 / 1024) >= (int) ini_get('post_max_size')) {
            $this->error['description'] = $this->language->get('error_description_max_size');
        }

        return !($this->error);
    }

    protected function upload() {
        $dir = "data/" . date('m-Y');
        $directory = DIR_IMAGE . $dir;
        if (!is_dir($directory)) {
            mkdir($directory, 0777);
        }
        $files = $this->request->files['files'];
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

        $files = $arr;
        foreach ($files as $key => $file) {
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
            if (@move_uploaded_file($tmp_name, $directory . '/' . $filename)) {
                $_files[] = array(
                    'name' => $dir . "/" . $name . '.' . $ext,
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

    public function sendPaymentSteps($order_id) {
        $this->language->load('checkout/success');
        if ($order_id && $this->config->get('marketing_email_new_order')) {
            $this->load->model('account/order');
            $this->load->model("marketing/newsletter");
            $this->load->library('email/mailer');
            $this->load->library('BarcodeQR');
            $this->load->library('Barcode39');

            $mailer = new Mailer;
            $qr = new BarcodeQR;
            $barcode = new Barcode39(C_CODE);
            $order = $this->modelOrder->getOrder($order_id);
            $products = $this->modelOrder->getOrderProducts($order_id);
            $totals = $this->modelOrder->getOrderTotals($order_id);

            $shipping_address = $order['shipping_address_1'] . ", " .
                    $order['shipping_city'] . ". " .
                    $order['shipping_zone'] . " - " .
                    $order['shipping_country'] . ". CP " .
                    $order['shipping_zone_code'];

            $payment_address = $order['payment_address_1'] . ", " .
                    $order['payment_city'] . ". " .
                    $order['payment_zone'] . " - " .
                    $order['payment_country'] . ". CP " .
                    $order['payment_zone_code'];

            $text = $this->config->get('config_owner') . "\n";
            $text .= "Pedido ID: " . $order_id . "\n";
            $text .= "Fecha Emision: " . date('d-m-Y h:i A', strtotime($order['date_added'])) . "\n";
            $text .= "Cliente: " . $this->customer->getCompany() . "\n";
            $text .= "RIF: " . $this->customer->getRif() . "\n";
            $text .= "Direccion IP: " . $order['ip'] . "\n";
            $text .= "Productos (" . count($products) . ")\n";
            $text .= "Modelo\tCant.\tTotal\n";

            foreach ($products as $key => $product) {
                $text .= $product['model'] . "\t" .
                        $product['quantity'] . "\t" .
                        $this->currency->format($product['total'], $order['currency'], $order['value']) . "\n";
            }

            $qrStore = "cache/" . str_replace(".", "_", $this->config->get('config_owner')) . '.png';
            $qrOrder = "cache/" . str_replace(" ", "_", $this->config->get('config_owner') . "_qr_code_order_" . $order_id) . '.png';
            $eanStore = "cache/" . str_replace(" ", "_", $this->config->get('config_owner') . "_barcode_39_order_id_" . $order_id) . '.gif';

            $qr->text($text);
            $qr->draw(150, DIR_IMAGE . $qrOrder);
            $qr->url(HTTP_HOME);
            $qr->draw(150, DIR_IMAGE . $qrStore);
            $barcode->draw(DIR_IMAGE . $eanStore);

            $product_html = "<table><thead><tr style=\"background:#ccc;color:#666;\"><th>Item</th><th>" .
                    $this->language->get('column_description') . "</th><th>" .
                    $this->language->get('column_model') . "</th><th>" .
                    $this->language->get('column_quantity') . "</th><th>" .
                    $this->language->get('column_price') . "</th><th>" .
                    $this->language->get('column_total') . "</th></tr></thead><tbody>";

            foreach ($products as $key => $product) {
                $options = $this->modelOrder->getOrderOptions($order_id, $product['order_product_id']);
                $option_data = "";
                foreach ($options as $option) {
                    $option_data .= "-- " . $option['name'] . "<br />";
                }
                $product_html .= "<tr>";
                $product_html .= "<td style=\"width:5%\">" . (int) ($key + 1) . "</td>";
                $product_html .= "<td style=\"width:45%\">" . $product['name'] . "<br />" . $option_data . "</td>";
                $product_html .= "<td style=\"width:20%\">" . $product['model'] . "</td>";
                $product_html .= "<td style=\"width:10%\">" . $product['quantity'] . "</td>";
                $product_html .= "<td style=\"width:10%\">" . $this->currency->format($product['price'], $order['currency'], $order['value']) . "</td>";
                $product_html .= "<td style=\"width:10%\">" . $this->currency->format($product['total'], $order['currency'], $order['value']) . "</td>";
                $product_html .= "</tr>";
            }
            $product_html .= "</tbody></table>";

            $total_html = "<div class=\"clear:both;float:none;\"></div><br /><table style=\"float:right;\">";
            foreach ($totals as $total) {
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
            $message = str_replace("{%payment_method%}", $order['payment_method'], $message);
            $message = str_replace("{%shipping_address%}", $shipping_address, $message);
            $message = str_replace("{%shipping_method%}", $order['shipping_method'], $message);
            $message = str_replace("{%date_added%}", date('d-m-Y h:i A', strtotime($order['date_added'])), $message);
            $message = str_replace("{%ip%}", $order['ip'], $message);
            $message = str_replace("{%qr_code_store%}", '<img src="' . HTTP_IMAGE . $qrStore . '" alt="QR Code" />', $message);
            $message = str_replace("{%qr_code_order%}", '<img src="' . HTTP_IMAGE . $qrOrder . '" alt="QR Code" />', $message);
            $message = str_replace("{%barcode_39_order_id%}", '<img src="' . HTTP_IMAGE . $eanStore . '" alt="QR Code" />', $message);
            $message = str_replace("{%comment%}", $order['comment'], $message);
            $message = str_replace("{%seller_name%}", $this->config->get('config_name'), $message);
            $message = str_replace("{%seller_rif%}", $this->config->get('config_rif'), $message);
            $message = str_replace("{%seller_email%}", $this->config->get('config_email'), $message);
            $message = str_replace("{%seller_telephone%}", $this->config->get('config_telephone'), $message);
            $message = str_replace("{%seller_url%}", HTTP_HOME, $message);
            $message = str_replace("{%seller_logo%}", '<img src="' . HTTP_IMAGE . $this->config->get('config_logo') . '" alt="Logo Vendedor" />', $message);
            
            $message .= "<p style=\"text-align:center\">Powered By NecoTienda&reg; " . date('Y') . "</p>";

            $subject = $this->config->get('config_owner') . " " . $this->language->get('text_new_order') . " #" . $order_id;
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
            $mailer->AddAddress($this->customer->getEmail(), $this->customer->getFirstName() . ' ' . $this->customer->getLastName());
            $mailer->AddBCC($this->config->get('config_email'), $this->config->get('config_name'));
            $mailer->SetFrom($this->config->get('config_email'), $this->config->get('config_name'));
            $mailer->Subject = $subject;
            $mailer->Body = html_entity_decode($message);

            $mailer->Send();
        }
        return $order_id;
    }
}
