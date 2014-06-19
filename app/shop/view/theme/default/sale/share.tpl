<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title><?php echo $heading_title; ?></title>
        <base href="<?php echo $base; ?>" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.1.min.js"><\/script>')</script>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    
    <body>
        <h2>Invita a tus amigos de <?php echo ucfirst($_GET['service']); ?></h2>
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" id="email" value="" /></td>
            </tr>
            <tr>
                <td>Contrase&ntilde;a:</td>
                <td><input type="password" name="password" id="password" value="" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><a onclick="invitar()">Invitar</a></td>
            </tr>
        </table>
        <script>
            function invitar() {
                $("a").after('<img src="<?php echo HTTP_HOME; ?>assets/images/ajax_load.gif" alt="Cargando..." title="Cargando..." id="temp" />');
                $.post('<?php echo $Url::createUrl("sale/share/invite"); ?>',{
                    'product_id':'<?php echo $_GET['product_id']; ?>',
                    'email':$("#email").val(),
                    'password':$("#password").val(),
                    'service':'<?php echo $_GET['service']; ?>'
                },function(response) {
                    $("#temp").remove();
                    if (response.length > 0) {
                       $("table").before(response); 
                    }
                });
            }
        </script>
    </body>
</html>