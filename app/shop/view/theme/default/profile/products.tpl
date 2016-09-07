<?php require('profile_top.tpl'); ?>

<div id="profileSideBar" class="grid_3">

    <?php if ($filters) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtros Seleccionados</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-selected">
            <?php foreach ($filters as $key => $value) { ?>
                <li><a href="<?php echo $value['href']; ?>"><?php echo $value['name'];?></a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!empty($filterCategories) && !isset($filters['category'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtrar Por Categor&iacute;a</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-cateogies">
            <?php foreach ($filterCategories as $key => $value) { ?>
                <li><a href="<?php echo rtrim($urlCriterias['forCategories'] .'_Cat_'. str_replace(' ','-',strtolower($value['name'])) .'?'. implode('',$urlQuery),'?'); ?>"><?php echo $value['name'];?> (<?php echo $value['total']; ?>)</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!empty($filterManufacturers) && !isset($filters['manufacturer'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Fabricante</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-manufacturers">
            <?php foreach ($filterManufacturers as $key => $value) { ?>
                <li><a href="<?php echo rtrim($urlCriterias['forManufacturers'] .'_Marca_'. str_replace(' ','-',strtolower($value['name'])) .'?'. implode('',$urlQuery),'?'); ?>"><?php echo $value['name'];?> (<?php echo $value['total']; ?>)</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!empty($filterZones) && !isset($filters['zone'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Estado</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-zones">
            <?php foreach ($filterZones as $key => $value) { ?>
                <li><a href="<?php echo rtrim($urlCriterias['forZones'] .'_Estado_'. str_replace(' ','-',strtolower($value['name'])) .'?'. implode('',$urlQuery),'?'); ?>"><?php echo $value['name'];?> (<?php echo $value['total']; ?>)</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!empty($filterStores) && !isset($filters['stores'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Tienda</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-stores">
            <?php foreach ($filterStores as $key => $value) { ?>
                <li><a href="<?php echo rtrim($urlCriterias['forStores'] .'_Tienda_'. str_replace(' ','-',strtolower($value['name'])) .'?'. implode('',$urlQuery),'?'); ?>"><?php echo $value['name'];?> (<?php echo $value['total']; ?>)</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!empty($filterSellers) && !isset($filters['seller'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Vendedor</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-sellers">
            <?php foreach ($filterSellers as $key => $value) { ?>
                <li><a href="<?php echo rtrim($urlCriterias['forSellers'] .'_Vendedor_'. str_replace(' ','-',strtolower($value['company'])) .'?'. implode('',$urlQuery),'?'); ?>"><?php echo $value['company'];?> (<?php echo $value['total']; ?>)</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!empty($filterPrices) && !isset($filters['price'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Precios</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-price">
                <li>
                    <input type="text" name="bottomPrice" id="bottomPrice" placeholder="Precio Min" style="padding:0px 4px;width:35%;height:30px;border:solid 1px #999;" />
                    <span style="margin:5px 10px;padding:0px;width:2px;float:left">&nbsp;-&nbsp;</span>
                    <input type="text" name="topPrice" id="topPrice" placeholder="Precio Max" style="padding:0px 4px;width:35%;height:30px;border:solid 1px #999" />
                    <br />
                    <a class="button" onclick="window.location.href = '<?php echo $urlCriterias['forPrices'] .'_Precio_'; ?>'+ $('#bottomPrice').val() +'-'+ $('#topPrice').val() +'<?php echo rtrim('?'. implode('',$urlQuery),'?'); ?>'">Filtrar</a>
                    <div class="clear"></div><br />
                </li>
                <?php foreach ($filterPrices as $key => $value) { ?>
                <li><a href="<?php echo rtrim($urlCriterias['forPrices'] .'_Precio_'. $value['bottomValue'] .'-'. $value['topValue'] .'?'. implode('',$urlQuery),'?'); ?>"><?php echo $value['bottomText'] .' - '. $value['topText']; ?></a><div class="clear"></div><br /></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if (!isset($filters['date'])) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Fecha</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-date">
                <li>
                    <input type="date" name="dateStart" id="dateStart" placeholder="dd/mm/yyyy" style="padding:0px 4px;width:150px;height:30px;border:solid 1px #999;" /><br />
                    <input type="date" name="dateEnd" id="dateEnd" placeholder="dd/mm/yyyy" style="padding:0px 4px;width:150px;height:30px;border:solid 1px #999;" />
                </li>
                <a class="button" onclick="window.location.href = '<?php echo $urlCriterias['forDates'] .'_Fecha_'; ?>'+ $('#dateStart').val().replace(/\//g,'-') +'+'+ $('#dateEnd').val().replace(/\//g,'-') +'<?php echo rtrim('?'. implode('',$urlQuery),'?'); ?>'">Filtrar</a>
                <script type="text/javascript" src="<?php echo HTTP_JS; ?>vendor/jquery-ui.min.js"></script>
                <script type="text/javascript" src="<?php echo HTTP_JS; ?>necojs/neco.form.js"></script>
                <script>
                    $(function(){
                        $(document.createElement('link')).attr({
                            'rel':'stylesheet',
                            'media':'screen',
                            'href':'<?php echo HTTP_CSS; ?>jquery-ui/jquery-ui.min.css'
                        }).appendTo('head');

                        $('#dateStart').delay('1600',function() { $('#dateStart').ntInput() });
                        $('#dateEnd').delay('1600',function() { $('#dateEnd').ntInput() });
                    });
                </script>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if ($filterColors) { ?>
    <div class="box">
        <div class="header"><hgroup><h1>Filtar Por Colores</h1></hgroup></div>
        <div class="content filter">
            <ul id="filter-color">
                <li>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Rojo')) . $urlPrices; ?>" class="filterColor" title="Rojo" style="background: red;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Azul')) . $urlPrices; ?>" class="filterColor" title="Azul" style="background: blue;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Amarillo')) . $urlPrices; ?>" class="filterColor" title="Amarillo" style="background: yellow;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Negro')) . $urlPrices; ?>" class="filterColor" title="Negro" style="background: #000;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Blanco')) . $urlPrices; ?>" class="filterColor" title="Blanco" style="background: #fff;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Verde')) . $urlPrices; ?>" class="filterColor" title="Verde" style="background: green;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Rojo Oscuro')) . $urlPrices; ?>" class="filterColor" title="Rojo Oscuro" style="background: #900;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Naranja')) . $urlPrices; ?>" class="filterColor" title="Naranja" style="background: #ce6b00;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Marron')) . $urlPrices; ?>" class="filterColor" title="Marr&oacute;n" style="background: #631201;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Gris')) . $urlPrices; ?>" class="filterColor" title="Gris y Plateado" style="background: #5a5856;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Azul Oscuro')) . $urlPrices; ?>" class="filterColor" title="Azul Oscuro" style="background: #002858;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Azul Claro')) . $urlPrices; ?>" class="filterColor" title="Azul Claro" style="background: #009bdb;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Morado')) . $urlPrices; ?>" class="filterColor" title="Morado" style="background: #630047;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Dorado')) . $urlPrices; ?>" class="filterColor" title="Dorado" style="background: #83600c;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Verde Claro')) . $urlPrices; ?>" class="filterColor" title="Verde Claro" style="background: #99bd73;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Amarillo Claro')) . $urlPrices; ?>" class="filterColor" title="Amarillo Claro" style="background: #fef271;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Carne')) . $urlPrices; ?>" class="filterColor" title="Carne" style="background: #e4b386;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Fucsia')) . $urlPrices; ?>" class="filterColor" title="Fucsia" style="background: #ba007c;"></a>
                    <a href="<?php echo $Url::createUrl("store/search",array('co'=>'Rosado')) . $urlPrices; ?>" class="filterColor" title="Rosado" style="background: #db9da1;"></a>
                </li>
            </ul>
        </div>
    </div>
    <?php } ?>
</div>
        
<div id="profileContainer" class="grid_9">
    <?php if ($noResults) { ?><div class="message warning">No se encontraron resultados</div><?php } ?>

    <?php if ($sorts) { ?>
    <div class="sort">
        <select name="sort" onchange="window.location.href = this.value">
        <?php foreach ($sorts as $sorted) { ?>
            <option value="<?php echo $sorted['href']; ?>"<?php if (($sort . '-' . $order) == $sorted['value']) { ?> selected="selected"<?php } ?>><?php echo $sorted['text']; ?></option>
        <?php } ?>
        </select>

        <a class="view_style" onclick="if ($('#productsWrapper').hasClass('list_view')) { $('#productsWrapper').removeClass('list_view').addClass('grid_view'); } else { $('#productsWrapper').removeClass('grid_view').addClass('list_view'); }  $(this).toggleClass('view_style_grid');">&nbsp;</a>
    </div> 
    <?php } ?>

    <div class="clear"></div>
    <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
    <div class="clear"></div>

    <div class="grid_view" id="productsWrapper">
        <ul>
        <?php foreach($products as $product) { ?>
            <li>
                <a href="<?php echo $Url::createUrl('store/product',array('product_id'=>$product['product_id'])); ?>" class="thumb" title="<?php echo $product['name']; ?>">
                    <img src="<?php echo HTTP_IMAGE; ?>data/preloader.gif" data-original="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="nt-lazyload" />
                    <a href="#" class="quick_view_button" onclick="return quickView('product', '<?php echo $product['product_id']; ?>');"><?php echo $Language->get('text_quick_view'); ?></a>
                </a>
                
                <div class="product_info nt-hoverdir">
                    <?php echo $product['sticker']; ?>

                    <a href="<?php echo $Url::createUrl('store/product',array('product_id'=>$product['product_id'])); ?>" title="<?php echo $product['name']; ?>" class="name"><?php echo $product['name']; ?></a>

                    <?php if ($product['rating']) { ?><img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>" /><?php } ?>

                    <p class="model"><?php echo $product['model']; ?></p>

                    <p class="overview"><?php echo substr($product['overview'],0,150)." ... "; ?><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>">Leer M&aacute;s</a></p>

                    <div class="description"><?php echo $product['description']; ?></div>

                    <p class="price"><?php echo $product['price']; ?></p>

                    <a title="<?php echo $button_see_product; ?>" class="button_see_small" href="<?php echo $Url::createUrl('store/product',array('product_id'=>$product['product_id'])); ?>"><?php echo $Language->get('button_see_product'); ?></a>
                    <a title="<?php echo $Language->get('button_add_to_cart'); ?>" class="button_add_small" <?php if (!$this->config->get("cart_ajax")) { ?>onclick="window.location.href = '<?php echo $Url::createUrl('checkout/cart',array('product_id'=>$product['product_id'])); ?>'"<?php } else { ?>onclick="addToCart('<?php echo $product['product_id']; ?>')"<?php } ?>><?php echo $Language->get('button_add_to_cart'); ?></a>
                </div>
            </li>
        <?php } ?>
        </ul>
    </div>

    <div class="clear"></div>
    <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
    <div class="clear"></div>
</div>
<?php require('profile_bottom.tpl'); ?>