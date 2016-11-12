<?php

class ControllerStoreSearch extends Controller {

    public function index($q) {
        $Url = new Url($this->registry);
        $criteria['page'] = $this->request->hasQuery('page') ? $this->request->getQuery('page') : 1;
        $criteria['sort'] = $this->request->hasQuery('sort') ? $this->request->getQuery('sort') : 'pd.name';
        $criteria['order'] = $this->request->hasQuery('order') ? $this->request->getQuery('order') : 'ASC';
        $criteria['limit'] = $this->request->hasQuery('limit') ? $this->request->getQuery('limit') : $this->config->get('config_catalog_limit');
        $criteria['start'] = ($criteria['page'] - 1) * $criteria['limit'];

        $this->data['urlQuery'] = array();
        $this->data['urlQuery']['page'] = ($this->request->hasQuery('page')) ? '&page=' . $this->request->getQuery('page') : '';
        $this->data['urlQuery']['sort'] = ($this->request->hasQuery('sort')) ? '&sort=' . $this->request->getQuery('sort') : '';
        $this->data['urlQuery']['order'] = ($this->request->hasQuery('order')) ? '&order=' . $this->request->getQuery('order') : '';
        $this->data['urlQuery']['limit'] = ($this->request->hasQuery('limit')) ? '&limit=' . $this->request->getQuery('limit') : '';

        $this->data['urlBase'] = HTTP_HOME . 'buscar/' . $_GET['q'];
        $this->data['urlSearch'] = HTTP_HOME . 'buscar/' . $_GET['q'] . '?' . implode('', $this->data['urlQuery']);

        $this->cacheId = 'search_page_' . md5($this->data['urlSearch']);

        $this->load->library('user');
        $cached = $this->cache->get($this->cacheId);
        if ($cached && !$this->user->isLogged()) {
            $this->response->setOutput($cached, $this->config->get('config_compression'));
        } else {
            $this->language->load('store/search');
            $this->load->model("store/search");

            $this->document->breadcrumbs = array();
            $this->document->breadcrumbs[] = array(
                'href' => $Url::createUrl("common/home"),
                'text' => $this->language->get('text_home'),
                'separator' => false
            );

            $this->data['urlCriterias'] = array();

            list($keyword) = explode('_', $_GET['q']);
            $params = explode('_', strtolower($_GET['q']));
            $queries[1] = $queries[2] = trim(trim($params[0], '-'));
            $criteria = array();

            $this->data['urlCriterias']['forCategories'] = $this->data['urlCriterias']['forZones'] = $this->data['urlCriterias']['forSellers'] = $this->data['urlCriterias']['forManufacturers'] = $this->data['urlCriterias']['forStores'] = $this->data['urlCriterias']['forPrices'] = $this->data['urlCriterias']['forShipping'] = $this->data['urlCriterias']['forPayments'] = $this->data['urlCriterias']['forStatus'] = $this->data['urlCriterias']['forStockStatus'] = $this->data['urlCriterias']['forDates'] = $queries[1];

            $this->document->title = (str_replace('-', ' ', $keyword)) ? str_replace('-', ' ', $keyword) : $this->language->get('heading_title');
            $this->data['heading_title'] = $this->language->get('heading_title') . ' ' . str_replace('-', ' ', $keyword);

            if (in_array('cat', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'cat') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['category'] = str_replace('-', ' ', $name);

                $this->data['urlCriterias']['forZones'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Cat_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Cat_' . $name;
            }

            if (in_array('estado', $params)) {
                $this->load->model('localisation/zone');
                foreach ($params as $key => $value) {
                    if ($value == 'estado') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['zone'] = str_replace('-', ' ', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Estado_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Estado_' . $name;
            }

            if (in_array('vendedor', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'vendedor') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['seller'] = str_replace('-', ' ', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Vendedor_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Vendedor_' . $name;
            }

            if (in_array('marca', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'marca') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['manufacturer'] = $name;

                $this->data['urlCriterias']['forCategories'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Marca_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Marca_' . $name;
            }

            if (in_array('tienda', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'tienda') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['stores'] = str_replace('-', ' ', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Tienda_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Tienda_' . $name;
            }

            if (in_array('precio', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'precio') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                list($criteria['price_start'], $criteria['price_end']) = explode('-', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Precio_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Precio_' . $name;
            }

            if (in_array('envio', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'envio') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['shipping_methods'] = explode('-', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Envio_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Envio_' . $name;
            }

            if (in_array('pago', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'pago') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['payment_methods'] = explode('-', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Pago_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Pago_' . $name;
            }

            if (in_array('disp', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'disp') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['stock_statuses'] = explode('-', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Disp_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Disp_' . $name;
            }

            if (in_array('status', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'status') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $criteria['product_status'] = explode('-', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Status_' . $name;
                $this->data['urlCriterias']['forDates'] .= '_Status_' . $name;
            }

            if (in_array('fecha', $params)) {
                foreach ($params as $key => $value) {
                    if ($value == 'fecha') {
                        $name = $params[$key + 1];
                        unset($params[$key], $params[$key + 1]);
                    }
                }
                //TODO: clean the query
                $name = str_replace(' ', '+', trim($name));
                list($criteria['date_start'], $criteria['date_end']) = explode('+', $name);

                $this->data['urlCriterias']['forCategories'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forZones'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forSellers'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forManufacturers'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forStores'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forPrices'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forShipping'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forPayments'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forStatus'] .= '_Fecha_' . $name;
                $this->data['urlCriterias']['forStockStatus'] .= '_Fecha_' . $name;
            }

            $queries[2] = str_replace('-', ' ', $queries[2]);
            if ($queries[1] != $queries[2]) {
                if ($queries[2] !== mb_convert_encoding(mb_convert_encoding($queries[2], 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                    $queries[2] = mb_convert_encoding($queries[2], 'UTF-8', mb_detect_encoding($queries[2]));
                $queries[2] = htmlentities($queries[2], ENT_NOQUOTES, 'UTF-8');
                $queries[2] = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $queries[2]);
                $queries[2] = html_entity_decode($queries[2], ENT_NOQUOTES, 'UTF-8');
                $queries[2] = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), ' ', $queries[2]);

                if (str_word_count($queries[2], 0) > 1)
                    $queries[4] = str_replace(' ', '', $queries[2]);
            } else {
                unset($queries[2]);
            }

            if (str_word_count($queries[1], 0) > 1)
                $a1 = explode(' ', $queries[1]);
            if ($queries[2])
                $a2 = explode(' ', $queries[2]);

            if ($a1)
                $queries = array_merge($queries, $a1);
            if ($a2)
                $queries = array_merge($queries, $a2);

            $deleteFromArray = array(
                'a',
                'e',
                'i',
                'o',
                'u',
                'y',
                'con',
                'de',
                'desde',
                'en',
                'entre',
                'hacia',
                'hasta',
                'mediante',
                'para',
                'por',
                'sin',
                'sobre',
                'tras',
                'versus',
                'seg�n',
                'segun',
                'un',
                'uno',
                'una',
                'el',
                'la',
                'los',
                'las',
                'ellos',
                'es'
            );

            $queries = array_unique($queries);
            foreach ($queries as $key => $value) {
                foreach ($deleteFromArray as $toDelete) {
                    if (in_array(trim($value), $toDelete) || strlen(trim($value)) <= 2) {
                        unset($queries[$key]);
                    }
                }
            }

            $criteria['queries'] = $queries;

            if (isset($criteria['category'])) {
                $this->data['filters']['category'] = array(
                    'name' => $criteria['category'],
                    'href' => rtrim($this->data['urlCriterias']['forCategories'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['zone'])) {
                $this->data['filters']['zone'] = array(
                    'name' => $criteria['zone'],
                    'href' => rtrim($this->data['urlCriterias']['forZones'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['seller'])) {
                $this->data['filters']['seller'] = array(
                    'name' => $criteria['seller'],
                    'href' => rtrim($this->data['urlCriterias']['forSellers'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['manufacturer'])) {
                $this->data['filters']['manufacturer'] = array(
                    'name' => $criteria['manufacturer'],
                    'href' => rtrim($this->data['urlCriterias']['forManufacturers'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['stores'])) {
                $this->data['filters']['stores'] = array(
                    'name' => $criteria['stores'],
                    'href' => rtrim($this->data['urlCriterias']['forStores'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['shipping_methods'])) {
                $this->data['filters']['shipping_methods'] = array(
                    'name' => $criteria['shipping_methods'],
                    'href' => rtrim($this->data['urlCriterias']['forShippingMethods'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['payment_methods'])) {
                $this->data['filters']['payment_methods'] = array(
                    'name' => $criteria['payment_methods'],
                    'href' => rtrim($this->data['urlCriterias']['forPaymentMethods'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['stock_statuses'])) {
                $this->data['filters']['stock_statuses'] = array(
                    'name' => $criteria['stock_statuses'],
                    'href' => rtrim($this->data['urlCriterias']['forStockStatuses'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['status'])) {
                $this->data['filters']['status'] = array(
                    'name' => $criteria['status'],
                    'href' => rtrim($this->data['urlCriterias']['forStatus'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['price_start']) && isset($criteria['price_end'])) {
                $this->data['filters']['price'] = array(
                    'name' => $this->currency->format($this->tax->calculate($criteria['price_start'])) . ' - ' .
                    $this->currency->format($this->tax->calculate($criteria['price_end'])),
                    'href' => rtrim($this->data['urlCriterias']['forPrices'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }
            if (isset($criteria['date_start']) && isset($criteria['date_end'])) {
                $this->data['filters']['date'] = array(
                    'name' => $criteria['date_start'] . ' / ' . $criteria['date_end'],
                    'href' => rtrim($this->data['urlCriterias']['forDates'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            } elseif (isset($criteria['date_start'])) {
                $this->data['filters']['date'] = array(
                    'name' => $criteria['date_start'] . ' / ' . date('d-m-Y'),
                    'href' => rtrim($this->data['urlCriterias']['forDates'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            } elseif (isset($criteria['date_end'])) {
                $this->data['filters']['date'] = array(
                    'name' => date('d-m-Y') . ' / ' . $criteria['date_end'],
                    'href' => rtrim($this->data['urlCriterias']['forDates'] . '?' . implode('', $this->data['urlQuery']), '?')
                );
            }

            $sortDeafultQuery = "";
            $sortDeafultQuery .= $this->data['urlQuery']['page'];
            $sortDeafultQuery .= $this->data['urlQuery']['limit'];

            $sortOrderAscQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;
            $nameAscQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;
            $nameDescQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;
            $priceAscQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;
            $priceDescQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;
            $productRatingAscQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;
            $productRatingDescQuery = $this->data['urlBase'] . '?&sort=p.sort_order&order=ASC' . $sortDeafultQuery;

            $this->data['sorts'] = array();
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href' => $sortOrderAscQuery
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $nameAscQuery
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $nameDescQuery
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $priceAscQuery
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $priceDescQuery
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_rating_asc'),
                'value' => 'p.rating-ASC',
                'href' => $productRatingAscQuery
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_rating_desc'),
                'value' => 'p.rating-DESC',
                'href' => $productRatingDescQuery
            );

            $this->load->model('store/search');
            $total = $this->modelSearch->getAllProductsTotal($criteria);
            if ($total) {

                if (!$criteria['categories'])
                    $this->data['filterCategories'] = $this->modelSearch->getCategoriesByProduct($criteria);
                if (!$criteria['manufacturer'])
                    $this->data['filterManufacturers'] = $this->modelSearch->getManufacturersByProduct($criteria);
                if (!$criteria['seller'])
                    $this->data['filterSellers'] = $this->modelSearch->getSellersByProduct($criteria);
                if (!$criteria['zone'])
                    $this->data['filterZones'] = $this->modelSearch->getZonesByProduct($criteria);
                if (!$criteria['stores'])
                    $this->data['filterStores'] = $this->modelSearch->getStoresByProduct($criteria);

                $results = $this->modelSearch->getAllProducts($criteria);

                $this->load->auto('store/review');
                $this->data['products'] = array();
                $topPrice = 0;
                $bottomPrice = 1000000000;
                foreach ($results as $result) {
                    $image = !empty($result['image']) ? $result['image'] : 'no_image.jpg';

                    $rating = ($this->config->get('config_review')) ? $this->modelReview->getAverageRating($result['product_id']) : false;


                    if ($result['price'] > $topPrice) {
                        $this->data['topPrice'] = array(
                            'value' => $result['price'],
                            'tax_class_id' => $result['tax_class_id']
                        );
                        $topPrice = $result['price'];
                    }

                    if ($result['price'] < $bottomPrice) {
                        $this->data['bottomPrice'] = array(
                            'value' => $result['price'],
                            'tax_class_id' => $result['tax_class_id']
                        );
                        $bottomPrice = $result['price'];
                    }

                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                    if (strpos(strtolower($result['name']), strtolower($queries[1]))) {
                        $name = str_replace(ucfirst($queries[1]), '<span style="background:#f60;color:#fff;">' . ucfirst($queries[1]) . '</span>', $result['name']);
                        $name = str_replace($queries[1], '<span style="background:#f60;color:#fff;">' . ucfirst($queries[1]) . '</span>', $result['name']);
                    } elseif (strpos(strtolower($result['name']), strtolower($queries[2]))) {
                        $name = str_replace(ucfirst($queries[2]), '<span style="background:#f60;color:#fff;">' . ucfirst($queries[2]) . '</span>', $result['name']);
                        $name = str_replace($queries[2], '<span style="background:#f60;color:#fff;">' . ucfirst($queries[2]) . '</span>', $result['name']);
                    } else {
                        $name = $result['name'];
                    }

                    $this->load->auto('image');
                    $this->data['products'][] = array(
                        'product_id' => $result['product_id'],
                        'title' => $name,
                        'name' => $result['name'],
                        'model' => $result['model'],
                        'overview' => $result['meta_description'],
                        'rating' => $rating,
                        'stars' => sprintf($this->language->get('text_stars'), $rating),
                        'price' => $price,
                        'image' => NTImage::resizeAndSave($image, 38, 38),
                        'lazyImage' => NTImage::resizeAndSave('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                        'thumb' => NTImage::resizeAndSave($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'))
                    );
                }

                $topPrice = $this->data['topPrice']['value'];
                $bottomPrice = $this->data['bottomPrice']['value'];
                $diff = ($topPrice - $bottomPrice) * 0.20;
                if ($diff > 0) {
                    while (true) {
                        $topPrice = $bottomPrice + $diff - + 0.01;
                        if ($topPrice >= $this->data['topPrice']['value']) {
                            $topPrice = $this->data['topPrice']['value'];
                            $break = true;
                        }

                        $this->data['filterPrices'][] = array(
                            'bottomValue' => round($bottomPrice, 2),
                            'bottomText' => $this->currency->format($this->tax->calculate($bottomPrice, $this->data['topPrice']['tax_class_id'], $this->config->get('config_tax'))),
                            'topValue' => round($topPrice, 2),
                            'topText' => $this->currency->format($this->tax->calculate($topPrice, $this->data['topPrice']['tax_class_id'], $this->config->get('config_tax')))
                        );

                        if ($break)
                            break;
                        $bottomPrice = $topPrice + 0.01;
                    }
                }

                $this->load->library('pagination');
                $pagination = new Pagination(true);
                $pagination->total = $total;
                $pagination->page = $data['page'];
                $pagination->limit = $data['limit'];
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = $urlBase . $urlQuery . '&page={page}';

                $this->data['pagination'] = $pagination->render();


                $this->modelSearch->add();
            } else {
                $this->data['noResults'] = true;
            }

            $this->data['breadcrumbs'] = $this->document->breadcrumbs;

            // SCRIPTS
            $scripts[] = array('id' => 'search-1', 'method' => 'ready', 'script' =>
                "$('#content_search input').keydown(function(e) {
                   	if (e.keyCode == 13 && $(this).val().length > 0) {
                  		contentSearch();
                   	}
                });
                if (window.location.hash.length > 0) {
                    $('#products').load('" . $Url::createUrl("store/search") . "&q='+ window.location.hash.replace('#', ''));
                }");
            $scripts[] = array('id' => 'search-2', 'method' => 'window', 'script' =>
                "$('.filter').mCustomScrollbar({
                    scrollButtons:{
                        enable:true
                    },
                    theme:'dark'
                });");

            $this->loadWidgets();

            if ($scripts)
                $this->scripts = array_merge($this->scripts, $scripts);

            $template = ($this->config->get('default_view_search')) ? $this->config->get('default_view_search') : 'store/search.tpl';
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/' . $template)) {
                $this->template = $this->config->get('config_template') . '/' . $template;
            } else {
                $this->template = 'default/' . $template;
            }

            $this->children[] = 'common/footer';
            $this->children[] = 'common/column_left';
            $this->children[] = 'common/nav';
            $this->children[] = 'common/header';

            $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
        }
    }

    protected function loadWidgets() {
        $Url = new Url($this->registry);
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
