<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <?php if($featuredWidgets) { ?>
        <div class="grid_16">
            <ul class="widgets"><?php foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul>
        </div>
        <?php } ?>
            
        <?php if(!$paid && $isOwner) { ?>
        <div class="grid_16">
            <div class="message warning"><?php echo $Language->get('text_has_to_paid'); ?></div>
        </div>
        <?php } ?>
            
        <?php if($expire && $isOwner) { ?>
        <div class="grid_16">
            <div class="message warning"><?php echo sprintf($Language->get('text_has_expire'), $Url::createUrl("sale/republish",array('product_id'=>$product_id))); ?></div>
        </div>
        <?php } ?>
            
        <div class="grid_16">
            <ul id="breadcrumbs" class="nt-editable">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
            </ul>
        </div>
        
        <?php if (isset($_GET['np'])) { ?>
        <ol id="stepsNewProduct" class="joyRideTipContent">
            <li data-button="<?php echo $Language->get('button_next'); ?>">
                <h2><?php echo $Language->get('heading_tour_welcome'); ?></h2>
                <p><?php echo $Language->get('help_tour_welcome'); ?></p>
            </li>
            <li data-id="images" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:right">
                <h2><?php echo $Language->get('heading_product_images'); ?></h2>
                <p><?php echo $Language->get('help_product_images'); ?></p>
            </li>
            <li data-id="productRelated" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:right">
                <h2><?php echo $Language->get('heading_product_related'); ?></h2>
                <p><?php echo $Language->get('help_product_related'); ?></p>
            </li>
            <li data-id="productData" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:left">
                <h2><?php echo $Language->get('heading_product_data'); ?></h2>
                <p><?php echo $Language->get('help_product_data'); ?></p>
            </li>
            <li data-id="productSocial" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:left">
                <h2><?php echo $Language->get('heading_product_social'); ?></h2>
                <p><?php echo $Language->get('help_product_social'); ?></p>
            </li>
            <li data-id="contact" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:top">
                <h2><?php echo $Language->get('heading_product_contact'); ?></h2>
                <p><?php echo $Language->get('help_product_contact'); ?></p>
            </li>
            <li data-id="buy" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:top">
                <h2><?php echo $Language->get('heading_product_buy'); ?></h2>
                <p><?php echo $Language->get('help_product_buy'); ?></p>
            </li>
            <li data-id="sellerInfo" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:left">
                <h2><?php echo $Language->get('heading_seller_info'); ?></h2>
                <p><?php echo $Language->get('help_seller_info'); ?></p>
            </li>
            <li data-id="productTabs" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:top">
                <h2><?php echo $Language->get('heading_product_tabs'); ?></h2>
                <p><?php echo $Language->get('help_product_tabs'); ?></p>
            </li>
            <li data-id="shippingMethods" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:left">
                <h2><?php echo $Language->get('heading_product_shipping'); ?></h2>
                <p><?php echo $Language->get('help_product_shipping'); ?></p>
            </li>
            <li data-id="paymentMethods" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:left">
                <h2><?php echo $Language->get('heading_product_payment'); ?></h2>
                <p><?php echo $Language->get('help_product_payment'); ?></p>
            </li>
            <li data-id="productPromotion" data-button="<?php echo $Language->get('button_next'); ?>" data-options="tipLocation:left">
                <h2><?php echo $Language->get('heading_product_promotion'); ?></h2>
                <p><?php echo $Language->get('help_product_promotion'); ?></p>
            </li>
            <li data-button="<?php echo $Language->get('button_close'); ?>">
                <h2><?php echo $Language->get('heading_steps_final'); ?></h2>
                <p><?php echo $Language->get('help_steps_final'); ?></p>
            </li>
        </ol>
        <?php } ?>
        
        <div class="clear"></div><br /><br />
        
        <div class="grid_7" style="padding: 0px 40px;">
            <div class="nt-editable" id="images">
                <div id="popup">
                    <ul class="nt-editable" id="productImages">
                    <?php foreach ($images as $k => $image) { ?>
                    <li>
                        <img class="etalage_thumb_image" src="<?php echo $image['preview']; ?>" alt="<?php echo $heading_title; ?>" />
                        <img class="etalage_source_image" src="<?php echo $image['popup']; ?>" alt="<?php echo $heading_title; ?>" />
                    </li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            
            <div class="clear"></div>
            
            <div class="nt-editable" id="productRelated">
                <h3><?php echo $Language->get('text_other_products_from_seller'); ?></h3>
                <div id="related" class="box nt-editable"></div>
            </div>
        </div>
        
        <div class="grid_7" id="productData">
        
            <?php if ($expire) { ?>
            <div class="message warning"><?php echo $Language->get('text_product_finished'); ?></div>
            <?php } ?>
            
            <div class="property nt-editable" id="productSocial" style="display: none;"></div>
            
            <div class="clear"></div>
            
            <h1 class="nt-editable" id="productName"><?php echo $heading_title; ?></h1>
            
            <a class="button yellow" style="float:right" href="<?php echo $Url::createUrl("sale/create",array("product_id"=>$product_id)); ?>">Publicar Uno Igual</a>
            <p>Publicaci&oacute;n #<?php echo $product_id; ?>&nbsp;&nbsp;&nbsp;&nbsp;Finaliza el <?php echo date('d-m-Y',strtotime($product_info['date_end'])); ?></p>
            <div class="clear"></div>
            
            <div class="property model nt-editable" id="productModel"><?php echo $model; ?></div>
            
            <div class="clear"></div>
            
            <?php if ($review_status) { ?>
            <div class="property average nt-editable" id="productAverage">
                <img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo (int)$average . '.png'; ?>" alt="<?php echo $Language->get('text_stars'); ?>" />
            </div>
            <?php } ?>
            
            <div class="clear"></div>
            
            <p class="price nt-editable" id="productPrice"><?php echo $price; ?></p>
            
            <div class="clear"></div>
            
            <div class="property availability nt-editable" id="productAvailability">
                <p><b><?php echo $Language->get('text_availability'); ?></b>&nbsp;<?php echo $stock; ?></p>
            </div>
            
            <div class="clear"></div>
                    
            <span class="grid_2 tag green"><?php echo (int)$total_offers; ?>&nbsp;&nbsp;Ofertas</span>
            <span class="grid_2 tag green"><?php echo (int)$total_visits; ?>&nbsp;&nbsp;Visitas</span>
            <span class="grid_2 tag green"><?php echo (int)$total_comments; ?>&nbsp;&nbsp;Comentarios</span>
                    
            <div class="clear"></div><br />
                  
            <?php if ($tags || $categories || $manufacturer) { ?>
            <ul class="tags nt-editable" id="productTags">
            <?php if ($manufacturer) { ?>
                <li><a class="manufacturer nt-editable" id="productManufacturer" title="<?php echo $manufacturer; ?>" href="<?php echo str_replace('&', '&amp;', $manufacturers); ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <?php foreach ($categories as $tag) { ?>
                <li><a class="category nt-editable" id="productCategory<?php echo $tag['category_id']; ?>" title="<?php echo $tag['name']; ?>" href="<?php echo str_replace('&', '&amp;', $Url::createUrl('store/category',array('path'=>$tag['category_id']))); ?>"><?php echo $tag['name']; ?></a></li> 
            <?php } ?>
            <?php foreach ($tags as $tag) { ?>
                <li><a title="<?php echo $tag['tag']; ?>" href="<?php echo str_replace('&', '&amp;', $tag['href']); ?>"><?php echo $tag['tag']; ?></a></li> 
            <?php } ?>
            </ul>
            <?php } ?>
            
            <div class="clear"></div><br /><br /><br />
            <script type="text/javascript">
            <?php
            echo 'var data = '. json_encode(array(
                'seller_id'=>$product_info['owner_id'],
                'buyer_name'=>$this->customer->getFirstName() .' '. $this->customer->getLastName(),
                'product_id'=>$product_id
            )) .';';
            echo($contactData);
            ?>
            </script>
            <div class="property quantity">
                <a title="Contactar" id="contact" class="button blue" onclick="productContact('<?php echo ($this->customer->isLogged()); ?>','<?php echo HTTP_HOME; ?>','<?php echo ($this->session->get('token')); ?>',data)">Contactar</a>
                <a title="Comprar" id="buy" class="button blue" onclick="productCart('<?php echo ($this->customer->isLogged()); ?>','<?php echo HTTP_HOME; ?>','<?php echo ($this->session->get('token')); ?>',data)">Comprar</a>
            </div>
            
            <div class="clear"></div>
            
            <div class="nt-editable" id="sellerInfo">
                <h3>Datos del Vendedor</h3>
                <?php if ($owner['photo']) { ?><img src="<?php echo $Image::resizeAndSave($owner['photo'],100,100); ?>" alt="<?php echo $owner['company']; ?>" /><?php } ?>
                <div>
                    <b><?php echo $owner['company']; ?></b>
                    
                    <?php if ($owner['telephone']) { ?>
                    <p><a onclick="$.get('<?php echo $Url::createUrl("store/product/productcalled",array('product_id'=>$product_id)); ?>',function(){window.location.href='<?php echo ($browser->isMobile()) ? 'tel:'.$owner['telephone'] : 'callto:'.$owner['telephone']; ?>';});return false;" title="<?php echo $Language->get('text_call'); ?>"><?php echo $owner['telephone']; ?></a></p>
                    <?php } ?>
                    
                    <?php if ($owner['email']) { ?>
                    <p><a onclick="$.get('<?php echo $Url::createUrl("store/product/emailsent",array('product_id'=>$product_id)); ?>',function(){window.location.href='mailto:<?php echo $owner['email']; ?>';});return false;" title="<?php echo $Language->get('text_send_email'); ?>"><?php echo $owner['email']; ?></a></p>
                    <?php } ?>
                    
                    <?php if ($owner['website']) { ?>
                    <p><a href="<?php echo $Url::createUrl("store/product/web",array('product_id'=>$product_id,'redirect'=>urlencode($owner['website']))); ?>" title="<?php echo $Language->get('text_visit_web'); ?>"><?php echo $owner['website']; ?></a></p>
                    <?php } ?>
                    
                    <?php if ($owner['address']) { ?><p><?php echo $owner['address']; ?></p><?php } ?>
                </div>
                
                <div class="clear"></div>
                
                <div class="nt-editable" id="sellerReviews">
                    Comentarios Recibidos: <?php echo $owner_reviews; ?>&nbsp;|&nbsp;
                    Art&iacute;culos Publicados: <?php echo $owner_total_products; ?>&nbsp;|&nbsp;
                    Art&iacute;culos Activos:<?php echo $owner_total_active_products; ?>&nbsp;|&nbsp;
                </div>
            </div>
        
            <div class="clear"></div><br /><hr /><br />
            
        </div>
        
        <div class="clear"></div>
        <div class="grid_16"><div class="message info"><?php echo $Language->get('text_service_policy'); ?></div></div>
        <div class="clear"></div>
        
        <div class="grid_12 product_tabs nt-editable" id="productTabs">
            <ul class="tabs nt-editable" id="pTabs">
                <li class="tab" id="description">Descripci&oacute;n</li>
                <li class="tab" id="attributes">Especificac&oacute;n</li>
                <li class="tab" id="comments">Preguntas</li>
                <li class="tab" id="seller">Conoce Al Vendedor</li>
            </ul>
        
            <div class="clear"></div>
            
            <div id="_description">
                <div class="product_description"><?php echo $description; ?></div>
            </div>
                
            <div id="_attributes">
                <?php if ($attributes) { ?>
                    <?php foreach ($attributes as $attribute) { ?>
                    <div class="grid_6"><?php echo $attribute['key']; ?></div>
                    <div class="grid_12"><?php echo $attribute['value']; ?></div>
                    <div class="clear"></div>
                    <?php } ?>
                <?php } ?>
            </div>
                    
            <div id="_comments">
                <div id="comment" class="box nt-editable"><img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' /></div>
                <div class="clear"></div>
                <div id="review" class="content nt-editable"><img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' /></div>
            </div>
            
            <div id="_seller">
                <img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' />
            </div>
            
        </div>
        
        <div class="aside">
        
            <div class="box" id="shippingMethods">
                <div class="header"><hgroup><h1><?php echo $Language->get('text_shipping_methods'); ?></h1></hgroup></div>
                <div class="content">
                    <ul class="tags nt-editable" id="shippingMethodsTags">
                    <?php foreach (unserialize($shipping_methods[0]['value']) as $key => $value) { ?>
                    <li><a href="<?php echo HTTP_HOME; ?>buscar/<?php echo $heading_title; ?>_Envio_<?php echo $value; ?>"><?php echo $value; ?></a></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            
            <div class="clear"></div>
        
            <div class="box" id="paymentMethods">
                <div class="header"><hgroup><h1><?php echo $Language->get('text_payment_methods'); ?></h1></hgroup></div>
                <div class="content">
                    <ul class="tags nt-editable" id="paymentMethodsTags">
                    <?php foreach (unserialize($payment_methods[0]['value']) as $key => $value) { ?>
                    <li><a href="<?php echo HTTP_HOME; ?>buscar/<?php echo $heading_title; ?>_Pago_<?php echo $value; ?>"><?php echo $value; ?></a></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            
            <div class="clear"></div>
        
            <div class="box" id="zones">
                <div class="header"><hgroup><h1><?php echo $Language->get('text_zones'); ?></h1></hgroup></div>
                <div class="content">
                    <ul class="tags nt-editable" id="zonesTags">
                    <?php foreach ($zones as $value) { ?>
                    <li><a href="<?php echo HTTP_HOME; ?>buscar/<?php echo $heading_title; ?>_Estado_<?php echo $value['name']; ?>"><?php echo $value['name']; ?></a></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            
            <?php if(!$expire && $paid) { ?>
            <div class="clear"></div>
            <div class="box" id="productPromotion">
                <div class="header"><hgroup><h1><?php echo $Language->get('text_promotes_this_product'); ?></h1></hgroup></div>
                <div class="content">
                
                    <?php if ($google_client_id) { ?>
                    <a href="<?php echo $Url::createUrl("api/google",array('redirect'=>'promote')); ?>" class="socialSmallButton googleButton"><?php echo $Language->get('button_promote_in_google'); ?></a>
                    <?php } ?>
                    
                    <?php if ($live_client_id) { ?>
                    <a href="<?php echo $Url::createUrl("api/live",array('redirect'=>'promote')); ?>" class="socialSmallButton liveButton"><?php echo $Language->get('button_promote_in_live'); ?></a>
                    <?php } ?>
                    
                    <?php if ($facebook_app_id) { ?>
                    <a href="<?php echo $Url::createUrl("api/facebook",array('redirect'=>'promote')); ?>" class="socialSmallButton facebookButton"><?php echo $Language->get('button_promote_in_facebook'); ?></a>
                    <?php } ?>
                    
                    <?php if ($twitter_oauth_token_secret) { ?>
                    <a href="<?php echo $Url::createUrl("api/twitter",array('redirect'=>'promote')); ?>" class="socialSmallButton twitterButton"><?php echo $Language->get('button_promote_in_twitter'); ?></a>
                    <?php } ?>
                    
                </div>
            </div>
            <?php } ?>
            
        </div>
        
        <div class="clear"></div>
        
        <div class="grid_16">
            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
        </div>
            
    </section>
    
</section>

<script type="text/javascript" src="<?php echo HTTP_JS; ?>necojs/neco.carousel.js"></script>
<script type="text/javascript" src="<?php echo HTTP_JS; ?>vendor/jquery.etalage.js"></script>
<script type="text/javascript" src="<?php echo HTTP_JS; ?>vendor/joyride/jquery.joyride-2.1.js"></script>
<script>
$(window).load(function() {
    $('#stepsNewProduct').joyride({
        autoStart : true,
        postStepCallback : function (index, tip) {
            if (index == 2) {
                $(this).joyride('set_li', false, 1);
            }
        },
        modal:true,
        expose: true
    });
});
$(function(){
    $("#related").ntCarousel({
        url:'<?php echo $Url::createUrl("store/product/relatedJson",array("product_id"=>$product_id)); ?>',
        image: {
          width:80,
          height:80  
        },
        loading: {
          image: '<?php echo HTTP_IMAGE; ?>loader.gif'
        },
        options: {
            scroll: 1
        }
    });
    
    $('.tab').each(function(){
        $(this).removeClass('active'); 
        $('#_' + this.id).hide(); 
    });
    
    $("#description").addClass('active');
    $('#_description').show(); 
    
    $('.tab').on('click',function() {
        $('.tab').each(function(){
           $(this).removeClass('active'); 
           $('#_' + this.id).hide(); 
        });
        $(this).addClass('active');
        $('#_' + this.id).show(); 
    });
    
    $('#review').load('<?php echo $Url::createUrl("store/product/review",array("product_id"=>$product_id)); ?>');
    $('#comment').load('<?php echo $Url::createUrl("store/product/comment",array("product_id"=>$product_id)); ?>');
    $('#_seller').load('<?php echo $Url::createUrl("module/seller/fullinfo",array("seller_id"=>$product_info['owner_id'])); ?>');
    
    $('#productImages').etalage({
        thumb_image_width: <?php echo (int)$config_image_thumb_width; ?>,
        thumb_image_height: <?php echo (int)$config_image_thumb_height; ?>,
        source_image_width: <?php echo (int)$config_image_popup_width; ?>,
        source_image_height: <?php echo (int)$config_image_popup_height; ?>,
        zoom_area_width: 400,
        zoom_area_height: 400,
        magnifier_invert: false,
        hide_cursor: true,
        speed: 400
    });
});
</script>
<script>
$(window).load(function(){
    var html = '';
    
    html += '<div class="grid_1" style="margin-right: 25px;">';
    html += '<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo str_replace("&","&amp",$Url::createUrl("store/product",array('product_id'=>$product_id))); ?>" data-count="vertical" data-via="lahoralocavzla" data-related="lahoralocavzla:Confites y Accesorios para Fiestas" data-lang="es">Tweet</a>';
    html += '<script type="text\/javascript" src="\/\/platform.twitter.com\/widgets.js"><\/script>';
    html += '</div>';
    
    html += '<div class="grid_1">';
    html += '<script type="text\/javascript" src="https:\/\/apis.google.com\/js\/plusone.js">{lang: \'es-419\'}<\/script>';
    html += '<g:plusone size="tall" callback="googleMas1" href="<?php echo str_replace("&","&amp",$Url::createUrl("store/product",array('product_id'=>$product_id))); ?>"></g:plusone>';
    html += '</div>';
    
    html += '<div class="grid_1" style="margin-right: 30px;">';
    html += '<div class="fb-like" data-href="<?php echo str_replace("&","&amp",$Url::createUrl("store/product",array('product_id'=>$product_id))); ?>" data-layout="box_count" data-width="450" data-show-faces="true" data-font="verdana"></div>';
    html += '</div>';
    
    html += '<div class="grid_1" style="margin-left: 15px;">';
    html += '<a href="http://pinterest.com/pin/create/button/?url=<?php echo rawurlencode(str_replace("&","&amp",$Url::createUrl("store/product",array('product_id'=>$product_id)))); ?>&media=<?php echo rawurlencode($thumb); ?>&description=<?php echo rawurlencode($description); ?>" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
    html += '</div>';
    
    $('#productSocial').delay(600).append(html).show();
});
</script>
<?php echo $footer; ?>