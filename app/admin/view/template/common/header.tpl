<!DOCTYPE html><!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]--><!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"> <![endif]--><!--[if IE 8]><html class="no-js lt-ie9"> <![endif]--><!--[if gt IE 8]><!--><html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $title; ?></title>
    <?php if (!empty($keywords)) { ?><meta name="keywords" content="<?php echo $keywords; ?>"><?php } ?>
        
    <?php if (!empty($description)) { ?><meta name="description" content="<?php echo $description; ?>"><?php } ?>
        
    <base href="<?php echo $base; ?>">
    <?php if (!empty($icon)) { ?><link href="<?php echo $icon; ?>" rel="icon"><?php } ?>
    <link href="http://www.necotienda.com/assets/images/data/favicon.png" rel="icon" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="image/mobile/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="image/mobile/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" href="image/mobile/apple-touch-icon.png" />
    <link rel="shortcut icon" href="image/apple-touch-icon.png" />
    <link rel="apple-touch-startup-image" media="(max-device-width: 480px) and not (-webkit-min-device-pixel-ratio: 2)" href="image/mobile/splash-320x460.png" />
    <link rel="apple-touch-startup-image" media="(max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2)" href="image/mobile/splash-640x920-retina.png" />
    <link rel="apple-touch-startup-image" media="(min-device-width: 768px) and (orientation: portrait)" href="image/mobile/splash-768x1004.png" />
    <link rel="apple-touch-startup-image" media="(min-device-width: 768px) and (orientation: landscape)" href="image/mobile/splash-1024x748.png" />
    <link rel="apple-touch-startup-image" media="(min-device-width: 1536px) and (orientation: portrait)" href="image/mobile/splash-1536x2008-retina.png" />
    <link rel="apple-touch-startup-image" media="(min-device-width: 2048px) and (orientation: landscape)" href="image/mobile/splash-2048x1496-retina.png" />
    
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="HandheldFriendly" content="true" />   
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <?php if (count($styles) > 0) foreach ($styles as $style) { ?>
    <link rel="stylesheet" type="text/css" media="<?php echo $style['media']; ?>" href="<?php echo $style['href']; ?>" />
    <?php } ?>
        
    <script src="js/vendor/modernizr-2.6.1.min.js"></script>
    <script src="js/vendor/jquery.min-1.8.1.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.1.min.js"><\/script>')</script>
        
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
        
    <?php if (!empty($javascripts) && is_array($javascripts)) foreach ($javascripts as $js) { ?>
    <script type="text/javascript" src="<?php echo $js; ?>"></script>
    <?php } ?>
</head>
<body>

    <div class="container_24">
    <?php if ($logged) { ?>
    
    <!-- Top navigation bar -->
    <div id="topNav">
        <div class="fixed">
            <div class="wrapper">
                <a id="header_logo" onclick="location = '<?php echo $home; ?>'"></a>
                <div class="userNav">
                    <ul>
                        <li>
                            <a href="<?php echo $Url::createAdminUrl("setting/cache"); ?>" title="Borrar Cache">
                                <img src="image/icons/topnav/smallBrush.png" alt="Borrar Cache" />
                                <span>Borrar Cache</span>
                            </a>
                        </li>
                        <li class="dd"><img src="image/icons/topnav/register.png" alt="" /><span>Crear &darr;</span>
                            <ul class="menu_body">
                                <li><a href="<?php echo $Url::createAdminUrl('store/product/insert'); ?>" title="Crear Producto">Producto</a></li>
                                <li><a href="<?php echo $Url::createAdminUrl('content/page/insert'); ?>" title="Crear Producto">P&aacute;gina</a></li>
                                <li><a href="<?php echo $Url::createAdminUrl('content/post/insert'); ?>" title="Crear Producto">Art&iacute;culo</a></li>
                                <li><a href="<?php echo $Url::createAdminUrl('store/manufacturer/insert'); ?>" title="Crear Producto">Fabricante</a></li>
                                <li><a href="<?php echo $Url::createAdminUrl('store/category/insert'); ?>" title="Crear Producto">Categor&iacute;a de Productos</a></li>
                                <li><a href="<?php echo $Url::createAdminUrl('content/post_category/insert'); ?>" title="Crear Producto">Categor&iacute;a de Art&iacute;los</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo $Url::createAdminUrl("sale/customer"); ?>" title="Clientes Nuevos"><img src="image/icons/topnav/profile.png" alt="Clientes Nuevos" />
                                <span>Clientes</span>
                                <?php if ($new_customers) { ?><span class="numberTop"><?php echo (int)$new_customers; ?></span><?php } ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $Url::createAdminUrl("sale/order"); ?>" title="Pedidos Nuevos">
                                <img src="image/icons/topnav/cart.png" alt="Pedidos Nuevos" />
                                <span>Pedidos</span>
                                <?php if ($new_orders) { ?><span class="numberTop"><?php echo (int)$new_orders; ?></span><?php } ?>
                            </a>
                        </li>
                        <!--
                        <li class="dd"><img src="image/icons/topnav/messages.png" alt="" /><span>Mensajes</span><span class="numberTop">8</span>
                            <ul class="menu_body">
                                <li><a href="#" title="" class="sAdd">Mensaje Nuevo</a></li>
                                <li><a href="#" title="" class="sInbox">Bandeja de Entrada</a></li>
                                <li><a href="#" title="" class="sOutbox">Mensaje Enviados</a></li>
                                <li><a href="#" title="" class="sTrash">Papelera</a></li>
                            </ul>
                        </li>
                        -->
                        <li><a href="<?php echo $Url::createAdminUrl("tool/task"); ?>" title="Tareas Programadas"><img src="image/icons/topnav/todo.png" alt="" /><span>Tareas</span></a></li>
                        <li class="dd">
                            <img src="image/icons/topnav/bag.png" alt="Tiendas" />
                            <span>Tiendas &darr;</span>
                            <ul class="menu_body">
                                <li><a href="<?php echo HTTP_CATALOG; ?>" title="Tienda Principal" target="_blank">Tienda Principal</a></li>
                                <?php foreach ($stores as $store) { ?>
                                <li><a href="<?php echo str_replace("www",$store['folder'],HTTP_CATALOG); ?>" target="_blank" title="Ir a la tienda"><?php echo $store['name']; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li><a href="<?php echo $Url::createAdminUrl("setting/setting"); ?>" title="Configuraci&oacute;n"><img src="image/icons/topnav/settings.png" alt="" /><span>Configuraci&oacute;n</span></a></li>
                        <li class="BigScreen"><a onclick="if (BigScreen.enabled) { BigScreen.toggle(); } else { alert('No est\u00E1 habilitado esta opci\u00F3n en su navegador'); }"><img src="image/icons/color/arrow-in-out.png" alt="Full Screen" /></a></li>
                        <li><a href="<?php echo $Url::createAdminUrl('common/logout'); ?>" title=""><img src="image/icons/topnav/logout.png" alt="" /><span>Logout</span></a></li>
                    </ul>
                </div>
                <div class="fix"></div>
            </div>
        </div>
    </div>

        <div id="menu_h">
            <ul class="ribbon">
                <li>
                    <ul class="menu">
                        <li id="inicio" class="on" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/home.png" alt="Inicio" /><span><?php echo $Language->get('tab_home'); ?></span></a>
                            <ul>
                                <li>
                                    <a title="<?php echo $Language->get('text_home'); ?>" href="<?php echo $Url::createAdminUrl('common/home'); ?>" accesskey="2">
                                        <img src="image/menu/inicio.png" alt="<?php echo $Language->get('text_home'); ?>" />
                                        <br /><span><?php echo $Language->get('text_home'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_category'); ?>" href="<?php echo $Url::createAdminUrl('store/category',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/category.png" alt="<?php echo $Language->get('text_category'); ?>" />
                                        <br /><span><?php echo $Language->get('text_category'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_product'); ?>" href="<?php echo $Url::createAdminUrl('store/product',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/product.png" />
                                        <br /><span><?php echo $Language->get('text_product'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_attribute'); ?>" href="<?php echo $Url::createAdminUrl('store/attribute',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/attributes.png" />
                                        <br /><span><?php echo $Language->get('text_attribute'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_manufacturer'); ?>" href="<?php echo $Url::createAdminUrl('store/manufacturer',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/manufacture.png"  />
                                        <br /><span><?php echo $Language->get('text_manufacturer'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_download'); ?>" href="<?php echo $Url::createAdminUrl('store/download',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/download.png" />
                                        <br /><span><?php echo $Language->get('text_download'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_review'); ?>" href="<?php echo $Url::createAdminUrl('store/review',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/comment.png" />
                                        <br /><span><?php echo $Language->get('text_review'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_shops'); ?>" href="<?php echo $Url::createAdminUrl('store/store',array('menu'=>'inicio')); ?>">
                                        <img src="image/menu/shop.png" />
                                        <br /><span><?php echo $Language->get('text_shops'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li id="contenido" class="on" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/computer.png" alt="Inicio" /><span><?php echo $Language->get('tab_content'); ?></span></a>
                            <ul>
                                <li>
                                    <a title="<?php echo $Language->get('text_page'); ?>" href="<?php echo $Url::createAdminUrl('content/page',array('menu'=>'contenido')); ?>">
                                        <img src="image/menu/page.png" alt="<?php echo $Language->get('text_page'); ?>" />
                                        <br /><span><?php echo $Language->get('text_page'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_post_category'); ?>" href="<?php echo $Url::createAdminUrl('content/post_category',array('menu'=>'contenido')); ?>">
                                        <img src="image/menu/post-category.png" alt="<?php echo $Language->get('text_post_category'); ?>" />
                                        <br /><span><?php echo $Language->get('text_post_category'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_post'); ?>" href="<?php echo $Url::createAdminUrl('content/post',array('menu'=>'contenido')); ?>">
                                        <img src="image/menu/post.png" alt="<?php echo $Language->get('text_post'); ?>" />
                                        <br /><span><?php echo $Language->get('text_post'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_menu'); ?>" href="<?php echo $Url::createAdminUrl('content/menu',array('menu'=>'contenido')); ?>">
                                        <img src="image/menu/menu.png" alt="<?php echo $Language->get('text_menu'); ?>" />
                                        <br /><span><?php echo $Language->get('text_menu'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_banner'); ?>" href="<?php echo $Url::createAdminUrl('content/banner',array('menu'=>'contenido')); ?>">
                                        <img src="image/menu/banner.png" alt="<?php echo $Language->get('text_banner'); ?>" />
                                        <br /><span><?php echo $Language->get('text_banner'); ?></span>
                                    </a>
                                    <!--
                                    <a title="<?php echo $Language->get('text_form'); ?>" href="<?php echo $Url::createAdminUrl('content/form',array('menu'=>'contenido')); ?>">
                                        <img src="image/menu/forms.png" alt="<?php echo $Language->get('text_form'); ?>" />
                                        <br /><span><?php echo $Language->get('text_form'); ?></span>
                                    </a>
                                    -->                                
                                </li>
                            </ul>
                        </li>
                        <li id="admon" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/money.png" alt="Inicio" /><span><?php echo $Language->get('tab_admon'); ?></span></a>
                            <ul>
                                <li>
                                    <a title="<?php echo $Language->get('text_report_sale'); ?>" href="<?php echo $Url::createAdminUrl('report/sale',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/ventas.png" alt="<?php echo $Language->get('text_order'); ?>" />
                                        <br /><span><?php echo $Language->get('text_report_sale'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_order'); ?>" href="<?php echo $Url::createAdminUrl('sale/order',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/order.png" alt="<?php echo $Language->get('text_order'); ?>" />
                                        <br /><span><?php echo $Language->get('text_order'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_payment'); ?>" href="<?php echo $Url::createAdminUrl('sale/payment',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/payment.png" alt="<?php echo $Language->get('text_payment'); ?>" />
                                        <br /><span><?php echo $Language->get('text_payment'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_coupon'); ?>" href="<?php echo $Url::createAdminUrl('sale/coupon',array('menu'=>'admon')); ?>" >
                                        <img src="image/menu/coupon.png" alt="<?php echo $Language->get('text_coupon'); ?>" />
                                        <br /><span><?php echo $Language->get('text_coupon'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_customer'); ?>" href="<?php echo $Url::createAdminUrl('sale/customer',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/client.png" alt="<?php echo $Language->get('text_customer'); ?>" />
                                        <br /><span><?php echo $Language->get('text_customer'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_customer_group'); ?>" href="<?php echo $Url::createAdminUrl('sale/customergroup',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/client_group.png" alt="<?php echo $Language->get('text_customer_group'); ?>" />
                                        <br /><span><?php echo $Language->get('text_customer_group'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_bank'); ?>" href="<?php echo $Url::createAdminUrl('sale/bank',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/bank.png" alt="<?php echo $Language->get('text_bank'); ?>" />
                                        <br /><span><?php echo $Language->get('text_bank'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_bank_account'); ?>" href="<?php echo $Url::createAdminUrl('sale/bank_account',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/bank_account.png" alt="<?php echo $Language->get('text_bank_account'); ?>" />
                                        <br /><span><?php echo $Language->get('text_bank_account'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_plan');; ?>" href="<?php echo $Url::createAdminUrl('sale/plan',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/coins.png" alt="<?php echo $Language->get('text_plan');; ?>" />
                                        <br /><span><?php echo $Language->get('text_plan');; ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_plan_store');; ?>" href="<?php echo $Url::createAdminUrl('sale/plan_store',array('menu'=>'admon')); ?>">
                                        <img src="image/menu/bag.png" alt="<?php echo $Language->get('text_plan_store');; ?>" />
                                        <br /><span><?php echo $Language->get('text_plan_store');; ?></span>
                                    </a>
                                </li> 
                            </ul>
                        </li>
                        <li id="herramientas" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/dropbox.png" alt="Herramientas" /><span><?php echo $Language->get('tab_tools'); ?></span></a>
                            <ul>
                                <li>
                                    <a title="Respaldar" href="<?php echo $Url::createAdminUrl('tool/backup',array('menu'=>'herramientas')); ?>">
                                        <img src="image/menu/backup.png" alt="<?php echo $Language->get('text_backup'); ?>">
                                        <br /><span>Respaldar</span>
                                    </a>
                                    <a title="Restaurar" href="<?php echo $Url::createAdminUrl('tool/backup',array('menu'=>'herramientas')); ?>">
                                        <img src="image/menu/restore.png" alt="Restaurar" />
                                        <br /><span>Restaurar</span>
                                    </a>
                                </li>
                                <li>
                                    <a title="<?php echo $Language->get('text_module'); ?>" href="<?php echo $Url::createAdminUrl('extension/module',array('menu'=>'herramientas')); ?>">
                                        <img src="image/menu/modulos.png" />
                                        <br /><span><?php echo $Language->get('text_module'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_shipping'); ?>" href="<?php echo $Url::createAdminUrl('extension/shipping',array('menu'=>'herramientas')); ?>">
                                        <img src="image/menu/shipping.png" />
                                        <br /><span><?php echo $Language->get('text_shipping'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_payment'); ?>" href="<?php echo $Url::createAdminUrl('extension/payment',array('menu'=>'herramientas')); ?>">
                                        <img src="image/menu/payment.png" />
                                        <br /><span><?php echo $Language->get('text_payment'); ?></span>
                                    </a>
                                    <a title="<?php echo $Language->get('text_total'); ?>" href="<?php echo $Url::createAdminUrl('extension/total',array('menu'=>'herramientas')); ?>">
                                        <img src="image/menu/total.png" />
                                        <br /><span><?php echo $Language->get('text_total'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li id="reportes" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/monitor.png" alt="Reportes" /><span><?php echo $Language->get('tab_report'); ?></span></a>
                            <ul>
                                <li>
                                    <a href="<?php echo $Url::createAdminUrl('report/viewed',array('menu'=>'reportes')); ?>" title="M&aacute;s Vistos">
                                        <img src="image/menu/p_mas_visto.png" alt="M&aacute;s Vistos">
                                        <br /><span>Productos</span>
                                    </a>
                                    <a title="Por Visitas" href="<?php echo $Url::createAdminUrl('report/visited',array('menu'=>'reportes')); ?>">
                                        <img src="image/menu/c_mas_visitas.png" alt="Con M&aacute;s Visitas">
                                        <br /><span>Clientes</span>
                                    </a>
                                    <a title="Por Pedidos" href="<?php echo $Url::createAdminUrl('report/cupurchased',array('menu'=>'reportes')); ?>">
                                        <img src="image/menu/c_mas_pedidos.png" alt="Con M&aacute;s Pedidos">
                                        <br /><span>Cat. Produuctos</span>
                                    </a>
                                    <a title="Por Pedidos" href="<?php echo $Url::createAdminUrl('report/cupurchased',array('menu'=>'reportes')); ?>">
                                        <img src="image/menu/c_mas_pedidos.png" alt="Con M&aacute;s Pedidos">
                                        <br /><span>Fabricantes</span>
                                    </a>
                                    <a title="Por Pedidos" href="<?php echo $Url::createAdminUrl('report/cupurchased',array('menu'=>'reportes')); ?>">
                                        <img src="image/menu/c_mas_pedidos.png" alt="Con M&aacute;s Pedidos">
                                        <br /><span>P&aacute;ginas</span>
                                    </a>
                                    <a title="Por Pedidos" href="<?php echo $Url::createAdminUrl('report/cupurchased',array('menu'=>'reportes')); ?>">
                                        <img src="image/menu/c_mas_pedidos.png" alt="Con M&aacute;s Pedidos">
                                        <br /><span>Art&iacute;culos</span>
                                    </a>
                                    <a title="Por Pedidos" href="<?php echo $Url::createAdminUrl('report/cupurchased',array('menu'=>'reportes')); ?>">
                                        <img src="image/menu/c_mas_pedidos.png" alt="Con M&aacute;s Pedidos">
                                        <br /><span>Cat. Art&iacute;culos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li id="mercadeo" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/megaphone.png" alt="Mercadeo" /><span><?php echo $Language->get('tab_marketing'); ?></span></a>
                            <ul>
                                <li>
                                    <a href="<?php echo $Url::createAdminUrl('marketing/contact',array('menu'=>'mercadeo')); ?>">
                                        <img src="image/menu/contact.png" />
                                        <br /><span>Contactos</span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('marketing/list',array('menu'=>'mercadeo')); ?>" title="Crear una nueva lista de emails">
                                        <img src="image/menu/contact_list.png" />
                                        <br /><span>Listas de Contactos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $Url::createAdminUrl('marketing/newsletter',array('menu'=>'mercadeo')); ?>" title="Crear una nueva lista de emails">
                                        <img src="image/menu/mail.png" />
                                        <br /><span>Plantillas de Email</span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('marketing/campaign',array('menu'=>'mercadeo')); ?>" title="Crear una nueva campa&ntilde;a">
                                        <img src="image/menu/send_mail.png" />
                                        <br /><span>Campa&ntilde;as</span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("marketing/message",array('menu'=>'mercadeo')); ?>" title="Asociar Plantillas de Email y P&aacute;ginas">
                                        <img src="image/menu/asociations.png" />
                                        <br /><span>Asociaciones</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li id="apariencia" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/bigBrush.png" alt="Apariencia" /><span><?php echo $Language->get('tab_style'); ?></span></a>
                            <ul>
                                <li>
                                    <a href="<?php echo Url::createAdminUrl("style/theme"); ?>">
                                        <img src="image/menu/theme.png" alt="Fondos" />
                                        <br /><span>Temas</span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('style/layouts',array('menu'=>'apariencia')); ?>">
                                        <img src="image/menu/template.png" alt="Fondos" />
                                        <br /><span>Plantillas</span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('style/editor',array('menu'=>'apariencia')); ?>">
                                        <img src="image/menu/editor.png" alt="Fondos" />
                                        <br /><span>Editor HTML</span>
                                    </a>
                                    <a href="<?php echo Url::createAdminUrl("style/widget"); ?>">
                                        <img src="image/menu/widgets.png" alt="Widgets" />
                                        <br /><span>Widgets</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li id="sistema" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/settings.png" alt="Sistema" /><span><?php echo $Language->get('tab_system'); ?></span></a>
                            <ul>
                                <li>
                                    <a href="<?php echo $Url::createAdminUrl('setting/setting',array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_setting'); ?>">
                                        <img src="image/menu/setting.png" />
                                        <br /><span><?php echo $Language->get('text_setting'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('user/user',array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_user'); ?>">
                                        <img src="image/menu/user.png" />
                                        <br /><span><?php echo $Language->get('text_user'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('user/user_permission',array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_user_group'); ?>">
                                        <img src="image/menu/user_group.png" alt="<?php echo $Language->get('text_user_group'); ?>" />
                                        <br /><span><?php echo $Language->get('text_user_group'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("localisation/language",array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_language'); ?>">
                                        <img src="image/menu/idioma.png" alt="<?php echo $Language->get('text_language'); ?>" />
                                        <br /><span><?php echo $Language->get('text_language'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("localisation/currency",array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_currency'); ?>">
                                        <img src="image/menu/moneda.png" alt="<?php echo $Language->get('text_currency'); ?>" />
                                        <br /><span><?php echo $Language->get('text_currency'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('localisation/order_status',array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_order_status'); ?>">
                                        <img src="image/menu/order_status.png" alt="<?php echo $Language->get('text_order_status'); ?>" />
                                        <br /><span><?php echo $Language->get('text_order_status'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl('localisation/stock_status',array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_stock_status'); ?>">
                                        <img src="image/menu/stock_status.png" alt="<?php echo $Language->get('text_stock_status'); ?>" />
                                        <br /><span><?php echo $Language->get('text_stock_status'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("localisation/tax_class",array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_tax_class'); ?>">
                                        <img src="image/menu/iva.png" alt="<?php echo $Language->get('text_tax_class'); ?>" />
                                        <br /><span><?php echo $Language->get('text_tax_class'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("localisation/geo_zone",array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_geo_zone'); ?>">
                                        <img src="image/menu/geo_zona.png" alt="<?php echo $Language->get('text_geo_zone'); ?>" />
                                        <br /><span><?php echo $Language->get('text_geo_zone'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("localisation/weight_class",array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_weight_class'); ?>">
                                        <img src="image/menu/peso.png" alt="<?php echo $Language->get('text_weight_class'); ?>" />
                                        <br /><span><?php echo $Language->get('text_weight_class'); ?></span>
                                    </a>
                                    <a href="<?php echo $Url::createAdminUrl("localisation/length_class",array('menu'=>'sistema')); ?>" title="<?php echo $Language->get('text_length_class'); ?>">
                                        <img src="image/menu/metro.png" alt="<?php echo $Language->get('text_length_class'); ?>" />
                                        <br /><span><?php echo $Language->get('text_length_class'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li id="ayuda" onclick="menuOn(this);"><a class="itemenu"><img src="image/icons/middlenav/help.png" alt="Ayuda" /><span><?php echo $Language->get('tab_help'); ?></span></a>
                            <ul>
                                <li>
                                    <a href="http://www.necotienda.com/docs/" title="Documentaci&oacute;n">
                                        <img src="image/menu/guia_usuario.png" alt="Documentaci&oacute;n" />
                                        <br /><span>Documentaci&oacute;n</span>
                                    </a>
                                    <a href="http://www.youtube.com/user/necotienda" target='_blank'>
                                        <img src="image/menu/youtube.png" />
                                        <br /><span>Videos Tutoriales</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.necotienda.com/forum/" target='_blank'>
                                        <img src="image/menu/necotienda.png" alt="Foro" />
                                        <br /><span>Foros</span>
                                    </a>
                                    <a href="http://www.necotienda.com/store/" target='_blank'>
                                        <img src="image/menu/webtino.png" alt="Tienda" />
                                        <br /><span>Tienda</span>
                                    </a>
                                    <a href="http://www.necotienda.com/blog/" target='_blank'>
                                        <img src="image/menu/wordpress.png" alt="Blog" />
                                        <br /><span>Blog</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://www.necotienda.com/support/" target='_blank'>
                                        <img src="image/menu/atencion.png" />
                                        <br /><span>Soporte</span>
                                    </a>
                                    <a>
                                        <img src="image/menu/acerca_de.png" />
                                        <br /><span>Acerca De</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    <?php } ?>
    <div class="clear"></div>
    <?php if ($breadcrumbs) { ?>
    <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
    </ul>
    <?php } ?>
    <?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
    <?php if ($msg) { ?><div class="grid_24"><div class="message warning"><?php echo $msg; ?></div></div><?php } ?>
    <?php if ($error) { ?><div class="grid_24"><div class="message error"><?php echo $error; ?></div></div><?php } ?>
</div>
<div class="clear"></div>
<div class="container_24">