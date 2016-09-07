<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="es"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />

    <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/i/378 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title><?php echo $title; ?></title>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    
    <!-- Mobile viewport optimized: h5bp.com/viewport -->
    <meta name="viewport" content="width=device-width" />

    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    
    <?php if ($css) { ?><style><?php echo $css; ?></style><?php } ?>
    
    <?php if (count($styles) > 0) { ?>
        <?php foreach ($styles as $style) { ?>
        <?php if (empty($style['href'])) continue; ?>
    <link rel="stylesheet" type="text/css" media="<?php echo $style['media']; ?>" href="<?php echo $style['href']; ?>" />
        <?php } ?>
    <?php } ?>
    
    <script src="<?php echo HTTP_JS; ?>modernizr.js"></script>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
    <script>window.$ || document.write('<script src="<?php echo HTTP_JS; ?>vendor/jquery.min.js"><\/script>')</script>
</head>
<body id="mainbody">
<section id="overheader" class="nt-editable">

    <div class="container_16">
    
        <div class="grid_8">
            <ul id="links" class="nt-editable">
                <li><a href="<?php echo HTTP_HOME; ?>" title="<?php echo $Language->get('text_home'); ?>"><?php echo $Language->get('text_home'); ?></a></li>
                <li><a href="<?php echo $Url::createUrl('store/special'); ?>" title="<?php echo $Language->get('text_special'); ?>"><?php echo $Language->get('text_special'); ?></a></li>
                <li><a href="<?php echo $Url::createUrl('checkout/cart'); ?>" title="<?php echo $Language->get('text_cart'); ?>"><?php echo $Language->get('text_cart'); ?></a></li>
                <li><a href="<?php echo $Url::createUrl('page/sitemap'); ?>" title="<?php echo $Language->get('text_sitemap'); ?>"><?php echo $Language->get('text_sitemap'); ?></a></li>
                <li><a href="<?php echo $Url::createUrl('page/contact'); ?>" title="<?php echo $Language->get('text_contact'); ?>"><?php echo $Language->get('text_contact'); ?></a></li>
            </ul>
        </div>
        
    </div>
</section>

<div class="container_16">
    <header id="header" class="nt-editable">
        <div class="grid_5">
            <div id="logo" class="nt-editable">
                <?php if ($logo) { ?>
                    <a title="<?php echo $store; ?>" href="<?php echo $Url::createUrl("common/home"); ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a>
                <?php } else { ?>
                    <a title="<?php echo $store; ?>" href="<?php echo $Url::createUrl("common/home"); ?>"><?php echo $text_store; ?></a>
                <?php } ?>
            </div>
        </div>
        
        <div class="grid_10" style="text-align:right;">
        
            <?php if ($isLogged) { ?>
            <b><?php echo $greetings; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("account/account"); ?>" title="<?php echo $Language->get("text_my_account"); ?>"><?php echo $Language->get("text_my_account"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("account/logout"); ?>" title="<?php echo $Language->get("text_logout"); ?>"><?php echo $Language->get("text_logout"); ?></a>
            <?php } else { ?>
            <a href="<?php echo $Url::createUrl("account/register"); ?>" title="<?php echo $Language->get("text_register"); ?>"><?php echo $Language->get("text_register"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("account/login"); ?>" title="<?php echo $Language->get("text_login"); ?>"><?php echo $Language->get("text_login"); ?></a>
            <?php } ?>
            
            <div class="clear"></div>
            <a class="button" style="float: right;" href="<?php echo $Url::createUrl("sale/create"); ?>" title="<?php echo $Language->get("text_sale"); ?>"><?php echo $Language->get("text_sale"); ?></a>
            <div class="clear"></div>
            
            <div id="accountPanel" class="nt-dd1 nt-editable">
                <p><?php echo $text_my_account; ?>&nbsp;&nbsp;<b>&nbsp;</b></p>
                <ul>
                    <li><a href="<?php echo $Url::createUrl("account/activities"); ?>" title="<?php echo $text_my_actitivties;?>"><?php echo $text_my_actitivties;?></a></li>
                    <li><a href="<?php echo $Url::createUrl("account/lists"); ?>" title="<?php echo $text_my_lists;?>"><?php echo $text_my_lists;?></a></li>
                    <li><a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_my_orders;?>"><?php echo $text_my_orders;?></a></li>
                    <li><a href="<?php echo $Url::createUrl("account/payment"); ?>" title="<?php echo $text_payments;?>"><?php echo $text_payments;?></a></li>
                    <li><a href="<?php echo $Url::createUrl("account/balance"); ?>" title="<?php echo $text_credits;?>"><?php echo $text_credits;?></a></li>
                    <li><a href="<?php echo $Url::createUrl("account/review"); ?>" title="<?php echo $text_my_reviews;?>"><?php echo $text_my_reviews;?></a></li>
                    <li><a href="<?php echo $Url::createUrl("account/address"); ?>" title="<?php echo $text_my_addresses;?>"><?php echo $text_my_addresses;?></a></li>
                    <li><a href="#" title="<?php echo $text_compare;?>"><?php echo $text_compare;?></a></li>
                </ul>
            </div>
        
            <div id="cartPanel" class="nt-dd1 nt-editable">
                <p><?php echo $text_cart; ?>&nbsp;&nbsp;<b>&nbsp;</b></p>
                <ul>
                    <?php if ($cartHasProducts) { ?>
                    <li><h2>Productos</h2></li>
                    <li>
                        <img src="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                        <a href="<?php echo $product['href']; ?>" title="<?php echo $product['name'];?>"><?php echo $product['name'];?><div><?php echo $product['model'];?></div></a>
                        <div><?php echo $product['qty'];?></div>
                        <?php if ($display_prices) { ?>
                        <div><?php echo $product['price'];?></div>
                        <?php } ?>
                    </li>
                    <?php } else { ?>
                    <li>No ha agregado productos al carrito</li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </header>
</div>
<script>
$(function(){
    $('#filter_keyword').on('keydown',function(e){
        var code = e.keyCode || e.which;
        if ($(this).val().length > 0 && code == 13){
            moduleSearch($('#filter_keyword').val());
        }
    });
});
</script>