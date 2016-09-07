<?php

class ControllerApiProduct extends Controller {

    public function get() {
        $product_id = isset($this->request->get['id']) ? (int) $this->request->get['id'] : 0;
        $product_info = $this->modelProduct->getProduct($product_id);

        if ($product_info) {
            $customerGroups = $this->modelProduct->getProperty($product_id, 'customer_groups', 'customer_groups');
            if (($this->customer->isLogged() && in_array($this->customer->getCustomerGroupId(), $customerGroups)) || in_array(0, $customerGroups)) {
                $cached = $this->cache->get('product.api.json.get.' .
                        $product_id .
                        $this->config->get('config_language_id') . "." .
                        $this->config->get('config_currency') . "." .
                        $this->config->get('config_store_id')
                );
                
                $this->load->library('user');
                
                if ($cached && (!$this->user->isLogged() || $this->request->hasQuery('np'))) {
                    $this->response->setOutput($cached, $this->config->get('config_compression'));
                } else {
                    //Models
                    $this->load->auto('store/product');
                    $this->load->auto('store/category');
                    $this->load->auto('store/manufacturer');

                    $this->load->auto('tool/image');
                    $this->load->auto('store/review');

                    //Libs
                    $this->load->auto('currency');
                    $this->load->auto('tax');

                    $this->document->breadcrumbs = array();
                    $this->document->breadcrumbs[] = array(
                        'href' => Url::createUrl('store/home'),
                        'text' => $this->language->get('text_home'),
                        'separator' => false
                    );

                    if (isset($this->request->get['path'])) {
                        $path = '';
                        foreach (explode('_', $this->request->get['path']) as $path_id) {
                            $category_info = $this->modelCategory->getCategory($path_id);
                            $path .= (!$path) ? $path_id : '_' . $path_id;
                            if ($category_info) {
                                $this->document->breadcrumbs[] = array(
                                    'href' => Url::createUrl('store/category', array('path' => $path)),
                                    'text' => $category_info['name'],
                                    'separator' => $this->language->get('text_separator')
                                );
                            }
                        }
                    }

                    if (isset($this->request->get['manufacturer_id'])) {
                        $manufacturer_info = $this->modelManufacturer->getManufacturer($this->request->get['manufacturer_id']);
                        if ($manufacturer_info) {
                            $this->document->breadcrumbs[] = array(
                                'href' => Url::createUrl('store/manufacturer', array('manufacturer_id' => $this->request->get['manufacturer_id'])),
                                'text' => $manufacturer_info['name'],
                                'separator' => $this->language->get('text_separator')
                            );
                        }
                    }

                    if (isset($this->request->get['keyword'])) {
                        $url = '';
                        if (isset($this->request->get['category_id'])) {
                            $url .= '&category_id=' . $this->request->get['category_id'];
                        }
                        if (isset($this->request->get['description'])) {
                            $url .= '&description=' . $this->request->get['description'];
                        }
                        $this->document->breadcrumbs[] = array(
                            'href' => Url::createUrl('store/search', '&keyword=' . $this->request->get['keyword'] . $url),
                            'text' => $this->language->get('text_search'),
                            'separator' => $this->language->get('text_separator')
                        );
                    }

                    $this->document->breadcrumbs[] = array(
                        'href' => Url::createUrl('store/product', $url . '&product_id=' . $product_id),
                        'text' => $product_info['name'],
                        'separator' => $this->language->get('text_separator')
                    );

                    $this->data['breadcrumbs'] = $this->document->breadcrumbs;

                    $average = ($this->config->get('config_review')) ? $this->modelReview->getAverageRating($product_id) : $average = false;

                    $this->data['action'] = Url::createUrl('checkout/cart');
                    $this->data['redirect'] = Url::createUrl('store/product', $url . '&product_id=' . $product_id);

                    $image = isset($product_info['image']) ? $product_info['image'] : $image = 'no_image.jpg';
                    $this->data['popup'] = NTImage::resizeAndSave($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                    $this->data['thumb'] = NTImage::resizeAndSave($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

                    $imgProduct = array(
                        'popup' => NTImage::resizeAndSave($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                        'preview' => NTImage::resizeAndSave($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                        'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                    );

                    $this->data['product_info'] = $product_info;

                    $discount = $this->modelProduct->getProductDiscount($product_id);

                    if ($discount) {
                        $this->data['price'] = $this->currency->format($this->tax->calculate($discount, $product_info['tax_class_id'], $this->config->get('config_tax')));
                        $this->data['special'] = false;
                    } else {
                        $this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        $special = $this->modelProduct->getProductSpecial($product_id);

                        if ($special) {
                            $this->data['special'] = $this->currency->format($this->tax->calculate($special, $product_info['tax_class_id'], $this->config->get('config_tax')));
                        } else {
                            $this->data['special'] = false;
                        }
                    }

                    $discounts = $this->modelProduct->getProductDiscounts($product_id);

                    $this->data['discounts'] = array();
                    foreach ($discounts as $discount) {
                        $this->data['discounts'][] = array(
                            'quantity' => $discount['quantity'],
                            'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
                        );
                    }

                    if ($product_info['quantity'] <= 0) {
                        $this->data['stock'] = $product_info['stock'];
                    } else {
                        if ($this->config->get('config_stock_display')) {
                            $this->data['stock'] = $product_info['quantity'];
                        } else {
                            $this->data['stock'] = $this->language->get('text_instock');
                        }
                    }

                    if ($product_info['minimum']) {
                        $this->data['minimum'] = $product_info['minimum'];
                    } else {
                        $this->data['minimum'] = 1;
                    }

                    $this->data['model'] = $product_info['model'];
                    $this->data['manufacturer'] = $product_info['manufacturer'];
                    $this->data['manufacturers'] = Url::createUrl('store/manufacturer', array('manufacturer_id' => $product_info['manufacturer_id']));
                    $this->data['description'] = htmlspecialchars($product_info['description'], ENT_QUOTES, 'UTF-8');
                    $this->data['product_id'] = $product_id;
                    $this->data['average'] = $average;
                    $this->data['options'] = array();
                    $this->data['categories'] = $this->modelProduct->getCategoriesByProduct(array('product_id' => $product_id));
                    $this->data['related'] = $this->modelProduct->getProductRelated($product_id);

                    $options = $this->modelProduct->getProductOptions($product_id);

                    foreach ($options as $option) {
                        $option_value_data = array();
                        foreach ($option['option_value'] as $option_value) {
                            $option_value_data[] = array(
                                'option_value_id' => $option_value['product_option_value_id'],
                                'name' => $option_value['name'],
                                'price' => (float) $option_value['price'] ? $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false,
                                'prefix' => $option_value['prefix']
                            );
                        }

                        $this->data['options'][] = array(
                            'option_id' => $option['product_option_id'],
                            'name' => $option['name'],
                            'option_value' => $option_value_data
                        );
                    }

                    $this->data['images'] = array();
                    $results = $this->modelProduct->getProductImages($product_id);

                    foreach ($results as $k => $result) {
                        $this->data['images'][$k] = array(
                            'popup' => NTImage::resizeAndSave($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                            'preview' => NTImage::resizeAndSave($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                            'thumb' => NTImage::resizeAndSave($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                        );
                    }
                    $k = count($this->data['images']) + 1;
                    $this->data['images'][$k] = $imgProduct;

                    if (!$this->config->get('config_customer_price')) {
                        $this->data['display_price'] = true;
                    } elseif ($this->customer->isLogged()) {
                        $this->data['display_price'] = true;
                    } else {
                        $this->data['display_price'] = false;
                    }

                    list($dia, $mes, $ano) = explode('-', date('d-m-Y'));
                    list($pdia, $pmes, $pano) = explode('-', date('d-m-Y', strtotime($product_info['created'])));
                    $l = ((int) $this->config->get('config_new_days') > 30) ? 30 : $this->config->get('config_new_days');
                    if (($dia = $dia - $l) <= 0) {
                        $dia = $dia + 30;
                        if ($dia <= 0)
                            $dia = 1;
                        $mes = $mes - 1;
                        if ($mes <= 0) {
                            $mes = $mes + 12;
                            $ano = $ano - 1;
                        }
                    }

                    $this->modelProduct->updateStats($this->request->getQuery('product_id'), (int) $this->customer->getId());

                    $this->data['tags'] = array();

                    $results = $this->modelProduct->getProductTags($product_id);

                    foreach ($results as $result) {
                        if ($result['tag']) {
                            $this->data['tags'][] = array(
                                'tag' => $result['tag'],
                                'href' => Url::createUrl('store/search', array('q' => $result['tag']))
                            );
                        }
                    }

                    if (!$this->user->isLogged()) {
                        $this->cacheId = 'product.api.json.get.' .
                                $product_id .
                                $this->config->get('config_language_id') . "." .
                                $this->config->get('config_currency') . "." .
                                (int) $this->config->get('config_store_id');
                    }

                    $this->load->library('json');
                    $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
                }
            } else {
                $this->error404();
            }
        } else {
            $this->error404();
        }
    }

    protected function error404() {
        header("HTTP/1.0 404 Not Found", true, 404);
        $this->data['response'] = '404';
        $this->data['error'] = 'Product not found';
        
        $this->load->library('json');
        $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
    }

    protected function error401() {
        header("HTTP/1.0 401 Not Authorized", true, 401);
        $this->data['response'] = '401';
        $this->data['error'] = 'Access Not Authorized';
        
        $this->load->library('json');
        $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
    }

    public function all() {
        $this->setHeaders();
	if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
            $data['page'] = $this->request->hasQuery('page') ? $this->request->getQuery('page') : 1;
            $data['limit'] = $this->request->hasQuery('limit') ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');

            $this->load->model('store/product');
            $this->load->model('store/review');
            $data['start'] = ($data['page'] - 1) * $data['limit'];
            $product_total = $this->modelProduct->getTotalByKeyword($data);
            if ($product_total) {
                $results = $this->modelProduct->getByKeyword($data);
                foreach ($results as $result) {
                    $image = !empty($result['image']) ? $result['image'] : 'no_image.jpg';

                    if ($this->config->get('config_review')) {
                        $rating = $this->modelReview->getAverageRating($result['product_id']);
                    } else {
                        $rating = false;
                    }

                    $special = false;
                    $discount = $this->modelProduct->getProductDiscount($result['product_id']);

                    if ($discount) {
                        $price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                        $special = $this->modelProduct->getProductSpecial($result['product_id']);
                        if ($special) {
                            $special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
                        }
                    }

                    $options = $this->modelProduct->getProductOptions($result['product_id']);

                    if ($options) {
                        $add = Url::createUrl('store/product', array('product_id' => $result['product_id']));
                    } else {
                        $add = Url::createUrl('checkout/cart') . '&product_id=' . $result['product_id'];
                    }

                    $this->data['results'][] = array(
                        'product_id' => $result['product_id'],
                        'name' => $result['name'],
                        'model' => $result['model'],
                        'overview' => $result['meta_description'],
                        'rating' => $rating,
                        'stars' => sprintf($this->language->get('text_stars'), $rating),
                        'price' => $price,
                        'options' => $options,
                        'special' => $special,
                        'image' => NTImage::resizeAndSave($image, 38, 38),
                        'lazyImage' => NTImage::resizeAndSave('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                        'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                        'href' => Url::createUrl('store/product', array('product_id' => $result['product_id'])),
                        'add' => $add
                    );
                }
            }
        } else {
            
        }
        $this->load->library('json');
        $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
    }
    
    public function create() {
        $this->setHeaders();
        
        $this->load->library('json');
        $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
    }
    
    public function update() {
        $this->setHeaders();
        
        $this->load->library('json');
        $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
    }
    
    public function delete() {
        $this->setHeaders();
        
        $this->load->library('json');
        $this->response->setOutput(Json::encode($this->data), $this->config->get('config_compression'));
    }
    
    protected function setHeaders() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Methods: GET');
        header("HTTP/1.0 200 Success", true, 200);
    }
}
