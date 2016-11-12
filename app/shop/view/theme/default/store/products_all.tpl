<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    <section id="maincontent">
        <section id="content">
            <div class="grid_12">
                <div id="featuredContent">
                <ul class="widgets"><?php if($featuredWidgets) { foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } } ?></ul>
                </div>
            </div>

            <div class="clear"></div>

            <div class="grid_12">
                <ul id="breadcrumbs" class="nt-editable">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
                </ul>
            </div>
        
            <div class="clear"></div><br /><br />

            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>
                <h1><?php echo $heading_title; ?></h1>

                <div class="clear"></div>

                <?php if (!$products) { ?><div class="content"><?php echo $Language->get('text_error'); ?></div><?php } ?>

                <div class="clear"></div>

                <?php if($products) { ?>
                <div id="products">
                    <div class="content">

                        <?php if ($sorts) { ?>
                        <div class="sort">
                            <!--
                            <select name="sort" onchange="window.location.href = this.value">
                                <?php foreach ($sorts as $sorted) { ?>
                                <option value="<?php echo $sorted['href']; ?>"<?php if (($sort . '-' . $order) == $sorted['value']) { ?> selected="selected"<?php } ?>><?php echo $sorted['text']; ?></option>
                                <?php } ?>
                            </select>
                            -->

                            <a class="view_style" onclick="if ($('#productsWrapper').hasClass('list_view')) { $('#productsWrapper').removeClass('list_view').addClass('grid_view'); $(this).find('i').removeClass('fa-th-list').addClass('fa-th-large'); } else { $('#productsWrapper').removeClass('grid_view').addClass('list_view'); $(this).find('i').removeClass('fa-th-large').addClass('fa-th-list'); }">
                                <i class="fa fa-th-large fa-2x"></i>
                            </a>
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

                                        <a title="<?php echo $button_see_product; ?>" class="button_see_small" href="<?php echo $Url::createUrl('store/product',array('product_id'=>$product['product_id'])); ?>">
                                            <i class="fa fa-rocket"></i>&nbsp;
                                            <?php echo $Language->get('button_see_product'); ?>
                                        </a>

                                        <?php if ($Config->get('config_store_mode')=='store') { ?>
                                        <a title="<?php echo $Language->get('button_add_to_cart'); ?>" class="button_add_small" <?php if (!$this->config->get("cart_ajax")) { ?>onclick="window.location.href = '<?php echo $Url::createUrl('checkout/cart',array('product_id'=>$product['product_id'])); ?>'"<?php } else { ?>onclick="addToCart('<?php echo $product['product_id']; ?>')"<?php } ?>>
                                            <i class="fa fa-shopping-cart"></i>&nbsp;
                                            <?php echo $Language->get('button_add_to_cart'); ?>
                                        </a>
                                        <?php } ?>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div class="clear"></div>

                        <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>

                        <div class="clear"></div>
                    </div>
                    <script>
                    $(function() {
                        $("#productsWrapper img").lazyload();
                    });
                    </script>
                </div>
                <?php } ?>

                <div class="clear"></div>
                <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
                <div class="clear"></div>

            </div>
        
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>