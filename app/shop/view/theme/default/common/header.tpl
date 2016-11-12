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
    
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

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
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->                        

<div id="overheader">
    <div class="container">
        <div class="grid_7">
            <div class="links">
                <ul>
                    <li>
                        <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Inicio"); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Ofertas"); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Compras Grupales"); ?></a>
                    </li>
                    <li class="has_submenu">
                        <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Red Necoyoad"); ?></a>&nbsp;&nbsp;
                        <i class="fa fa-caret-down fa-1x"></i>
                        <ul>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Necoyoad.com"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Webtino.net"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Sergasbass.com"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("ProfesorCarlos.com"); ?></a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_submenu">
                        <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Selecciona tu pa&iacute;s"); ?></a>&nbsp;&nbsp;
                        <i class="fa fa-caret-down fa-1x"></i>
                        <ul>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Argentina"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Chile"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Colombia"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Ecuador"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("México"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Panamá"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Perú"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Uruguay"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Venezuela"); ?></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="grid_5 push_1" style="text-align:right;left:20px">
        
            <?php if ($isLogged) { ?>
            <b><?php echo $greetings; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("profile/profile", array('profile_id'=>$this->customer->getProfile())); ?>" title="<?php echo $Language->get("text_my_account"); ?>"><?php echo $Language->get("text_my_profile"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("account/account"); ?>" title="<?php echo $Language->get("text_my_account"); ?>"><?php echo $Language->get("text_my_account"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("account/logout"); ?>" title="<?php echo $Language->get("text_logout"); ?>"><?php echo $Language->get("text_logout"); ?></a>
            <?php } else { ?>
            <a href="<?php echo $Url::createUrl("account/register"); ?>" title="<?php echo $Language->get("text_register"); ?>"><?php echo $Language->get("text_register"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $Url::createUrl("account/login"); ?>" title="<?php echo $Language->get("text_login"); ?>"><?php echo $Language->get("text_login"); ?></a>
            <?php } ?>
            
            &nbsp;&nbsp;&nbsp;
            <a class="button blue" style="float: right;" href="<?php echo $Url::createUrl("sale/create"); ?>" title="<?php echo $Language->get("text_sale"); ?>"><?php echo $Language->get("text_sale"); ?></a>
            <div class="clear"></div>
            
        </div>
        
    </div>
</div>

<div class="container">
    <header id="header" class="nt-editable">
        <div class="grid_3">
            <div id="logo" class="nt-editable">
                <?php if ($logo) { ?>
                    <a title="<?php echo $store; ?>" href="<?php echo $Url::createUrl("common/home"); ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a>
                <?php } else { ?>
                    <a title="<?php echo $store; ?>" href="<?php echo $Url::createUrl("common/home"); ?>"><?php echo $text_store; ?></a>
                <?php } ?>
            </div>
        </div>
        
        <div class="grid_9" style="text-align:right;float:right;">
            <div class="grid_3 push_6">
                <i class="fa fa-exchange"></i>&nbsp;&nbsp;
                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Productos a Comparar"); ?> (<?php echo (int)$compare; ?>)</a>
            </div>
            <div class="grid_2 push_6">
                <i class="fa fa-heart"></i>&nbsp;&nbsp;
                <a href="<?php echo $Url::createUrl(""); ?>"><?php echo $Language->get("Favoritos"); ?> (<?php echo (int)$whishlist; ?>)</a>
            </div>
        
            <div class="clear"></div>
            
            <div class="grid_3 push_8" style="left:650px;">
                <div class="cartHeader">
                    <div class="grid_3">
                        <i class="fa fa-shopping-cart fa-3x"></i>
                    </div>
                    <div class="grid_7">
                        <span>
                            <b><?php echo (int)$compare; ?> Items</b>
                            $299.87
                        </span>
                    </div>
                </div>
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