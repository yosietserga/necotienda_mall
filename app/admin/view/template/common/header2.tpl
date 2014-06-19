<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="expires" content="tue, 01 Jun 2015 19:45:00 GMT" />
    <title><?php echo $title; ?></title>
    
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <base href="<?php echo $base; ?>" />
    <link href="http://www.necotienda.com/assets/images/data/favicon.png" rel="icon" />
    <link rel="stylesheet" type="text/css" href="css/layouts/main.css" />
    <link rel="stylesheet" type="text/css" href="css/layouts/screen.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="css/layouts/twocolscenterfeatured.css" />

    <script type="text/javascript" src="js/vendor/jquery.min-1.8.1.js"></script>
    <script type="text/javascript" src="js/vendor/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/necojs/neco.colorpicker.js"></script>
</head>

<body>