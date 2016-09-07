<?php

class ControllerProfileProducts extends Controller {

    public function index() {
        $this->load->auto('account/customer');
        $this->language->load('profile/products');
        $Url = new Url($this->registry);

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("common/home"),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("profile/profile"),
            'text' => $this->language->get('text_profile'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->breadcrumbs[] = array(
            'href' => Url::createUrl("profile/products"),
            'text' => $this->language->get('text_products'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title');
        
        $pname = substr($this->request->getQuery('_route_'), 0, strpos($this->request->getQuery('_route_'), '/'));

        $profile = ($this->request->hasQuery('profile_id')) ? $this->request->getQuery('profile_id') : $pname;
        $customer = $this->modelCustomer->getCustomerByProfile($profile);
        
        if ($this->customer->isLogged() && $this->customer->getProfile() === $profile) {
            $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title') .' '. $profile;
        } else {
            $this->document->title = $this->data['heading_title'] = $this->language->get('heading_title2') .' '. $profile;
        }
        
        $this->session->set('redirect', $Url::createUrl('profile/products', array('profile_id'=>$profile)));
        
        if (!$customer) {
            $this->error404();
        } else {

            $image = $this->modelCustomer->getProperty($customer['customer_id'], 'profile', 'banner');
            
            $banner = ($image) ? $image : 'no_banner.jpg';
            $photo = ($customer['photo']) ? $customer['photo'] : 'no_image.gif';
            
            $activities = $this->modelCustomer->getActivities(
                    array(
                        'customer_id'=>$customer['customer_id'],
                    )
            );
            
            $this->data['profile'] = array(
                'firstname' => $customer['firstname'],
                'lastname' => $customer['lastname'],
                'company' => $customer['company'],
                'profile' => $customer['profile'],
                'banner' => NTImage::resizeAndSave($banner, 1100, 300),
                'photo' => NTImage::resizeAndSave($photo, 150, 150),
                'image' => $customer['photo'],
                'show_controls' => ($this->customer->getId() === $customer['customer_id']),
                'activities' => $activities,
            );
            
            $this->data['Image'] = NTImage;
            
            $this->document->title = $this->language->get('heading_title');

            $this->data['breadcrumbs'] = $this->document->breadcrumbs;
            $data['filter_keyword'] = $this->request->hasQuery('q') ? $this->request->getQuery('q') : '';
            $data['filter_price_start'] = $this->request->hasQuery('ps') ? $this->request->getQuery('ps') : '';
            $data['filter_price_end'] = $this->request->hasQuery('pe') ? $this->request->getQuery('pe') : '';
            $data['filter_color'] = $this->request->hasQuery('co') ? $this->request->getQuery('co') : '';
            $data['filter_category'] = $this->request->hasQuery('c') ? $this->request->getQuery('c') : '';
            $data['filter_manufacturer'] = $this->request->hasQuery('m') ? $this->request->getQuery('m') : '';
            $data['filter_seller_id'] = $customer['customer_id'];

            $data['page'] = $this->request->hasQuery('page') ? $this->request->getQuery('page') : 1;
            $data['sort'] = $this->request->hasQuery('sort') ? $this->request->getQuery('sort') : 'pd.name';
            $data['order'] = $this->request->hasQuery('order') ? $this->request->getQuery('order') : 'ASC';
            $data['limit'] = $this->request->hasQuery('limit') ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');

            $this->data['sorts'] = array();

            $url = '&profile_id='.$profile;
            if ($this->request->hasQuery('q')) {
                $url .= '&q=' . $this->request->getQuery('q');
            }
            if ($this->request->hasQuery('ps')) {
                $url .= '&ps=' . $this->request->getQuery('ps');
            }
            if ($this->request->hasQuery('pe')) {
                $url .= '&pe=' . $this->request->getQuery('pe');
            }
            if ($this->request->hasQuery('co')) {
                $url .= '&co=' . $this->request->getQuery('co');
            }
            if ($this->request->hasQuery('c')) {
                $url .= '&c=' . $this->request->getQuery('c');
            }
            if ($this->request->hasQuery('m')) {
                $url .= '&m=' . $this->request->getQuery('m');
            }
            if ($this->request->hasQuery('page')) {
                $url .= '&page=' . $this->request->getQuery('page');
            }
            if ($this->request->hasQuery('limit')) {
                $url .= '&limit=' . $this->request->getQuery('limit');
            }
            if ($this->request->hasQuery('v')) {
                $url .= '&v=' . $this->request->getQuery('v');
            }
            /*
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $Url::createUrl("profile/products", '&sort=p.sort_order&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $Url::createUrl("profile/products", '&sort=pd.name&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $Url::createUrl("profile/products", '&sort=pd.name&order=DESC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $Url::createUrl("profile/products", '&sort=p.price&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $Url::createUrl("profile/products", '&sort=p.price&order=DESC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_rating_asc'),
                'value' => 'p.rating-ASC',
                'href' => $Url::createUrl("profile/products", '&sort=p.rating&order=ASC' . $url)
            );

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_rating_desc'),
                'value' => 'p.rating-DESC',
                'href' => $Url::createUrl("profile/products", '&sort=p.rating&order=DESC' . $url)
            );
             * 
             */

            $this->load->model('store/product');
            $this->load->model('store/review');
            $data['start'] = ($data['page'] - 1) * $data['limit'];
            $product_total = $this->modelProduct->getTotalByKeyword($data);
            if ($product_total) {
                $url = '';
                if ($this->request->hasQuery('q')) {
                    $url .= '&q=' . $this->request->getQuery('q');
                }
                if ($this->request->hasQuery('ps')) {
                    $url .= '&ps=' . $this->request->getQuery('ps');
                }
                if ($this->request->hasQuery('pe')) {
                    $url .= '&pe=' . $this->request->getQuery('pe');
                }
                if ($this->request->hasQuery('co')) {
                    $url .= '&co=' . $this->request->getQuery('co');
                }
                if ($this->request->hasQuery('c')) {
                    $url .= '&c=' . $this->request->getQuery('c');
                }
                if ($this->request->hasQuery('m')) {
                    $url .= '&m=' . $this->request->getQuery('m');
                }
                if ($this->request->hasQuery('order')) {
                    $url .= '&order=' . $this->request->getQuery('order');
                }
                if ($this->request->hasQuery('sort')) {
                    $url .= '&sort=' . $this->request->getQuery('sort');
                }
                if ($this->request->hasQuery('limit')) {
                    $url .= '&limit=' . $this->request->getQuery('limit');
                }

                $this->data['products'] = array();
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

                    $add = $Url::createUrl('checkout/cart', array('product_id' => $result['product_id']));

                    $this->data['products'][] = array(
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
                        'href' => $Url::createUrl('store/product', array('product_id' => $result['product_id'])),
                        'add' => $add
                    );
                }

                if (!$this->config->get('config_customer_price')) {
                    $this->data['display_price'] = true;
                } elseif ($this->customer->isLogged()) {
                    $this->data['display_price'] = true;
                } else {
                    $this->data['display_price'] = false;
                }

                $this->load->library('pagination');
                $pagination = new Pagination(true);
                $pagination->total = $product_total;
                $pagination->page = $data['page'];
                $pagination->limit = $data['limit'];
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = $Url::createUrl("profile/products", $url . '&page={page}');

                $this->data['pagination'] = $pagination->render();

                if ($this->request->hasQuery('v')) {
                    $url .= '&v=' . $this->request->getQuery('v');
                }

                $this->data['url'] = $url;
            }

        $this->loadWidgets();

        if ($scripts)
            $this->scripts = array_merge($this->scripts, $scripts);

        $this->children[] = 'common/footer';
        $this->children[] = 'common/column_left';
        $this->children[] = 'common/nav';
        $this->children[] = 'common/header';

        $template = ($this->config->get('default_view_product_all')) ? $this->config->get('default_view_product_all') : 'profile/products.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/' . $template)) {
            $this->template = $this->config->get('config_template') . '/' . $template;
        } else {
            $this->template = 'default/' . $template;
        }

        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
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
        $styles[] = array('media' => 'all', 'href' => $csspath . 'profileprofile.css');
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
