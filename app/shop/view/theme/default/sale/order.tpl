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
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    
    <body>
        <?php if ($isLogged) { ?>
        <h1>Datos del Pedido</h1>
        <h2>Direcci&oacute;n y M&eacute;todo de Env&iacute;o</h2>
        <table>
            <tr>
                <td>Direcci&oacute;n</td>
                <td><textarea name="shipping_address" id="shipping_address"></textarea></td>
            </tr>
            <tr>
                <td>DHL&nbsp;<img src="assets/images/dhl-icon.jpg" src="DHL" /></td>
                <td><input type="radio" name="shipping_method" id="dhl" value="dhl" /></td>
            </tr>
            <tr>
                <td>MRW&nbsp;<img src="assets/images/mrw-icon.jpg" src="MRW" /></td>
                <td><input type="radio" name="shipping_method" id="mrw" value="mrw" /></td>
            </tr>
            <tr>
                <td>Zoom&nbsp;<img src="assets/images/zoom-icon.jpg" src="ZOOM" /></td>
                <td><input type="radio" name="shipping_method" id="zoom" value="zoom" /></td>
            </tr>
            <tr>
                <td>Tealca&nbsp;<img src="assets/images/tealca-icon.jpg" src="Tealca" /></td>
                <td><input type="radio" name="shipping_method" id="tealca" value="tealca" /></td>
            </tr>
            <tr>
                <td>Veh&iacute;culo Propio&nbsp;<img src="assets/images/pickup-icon.jpg" src="Pickup" /></td>
                <td><input type="radio" name="shipping_method" id="pickup" value="pickup" /></td>
            </tr>
        </table>
        <h2>&iquest;Cu&aacute;ntos Quieres?</h2>
        <table>
            <tr>
                <td>Ingresa la Cantidad</td>
                <td><input type="number" name="quantity" id="quantity" value="" required="required" /></td>
            </tr>
        </table>
        <p><b>NOTA:</b>Este es un acuerdo exclusivamente entre el vendedor <?php echo $seller; ?> y el comprador <?php echo $buyer; ?>. En ning&uacute;n momento la empresa formar&aacute; parte de la negociaci&oacute;n, liber&aacute;ndola de toda responsabilidad por lo que pudiera suceder entre los interesados. <?php echo $owner; ?> solo se limita a ofrecer un medio digital para la comunicaci&oacute;n.</p>
        <?php } else { ?>
        <h2>Por favor reg&iacute;strese primero</h2>
        <p>Para poder contactar al vendedor de este art&iacute;culo debe estar registrado</p>
        <a href="<?php echo $Url::createUrl("account/login"); ?>" title="Iniciar Sesi&oacute;n">Iniciar Sesi&oacute;n</a>
        <a href="<?php echo $Url::createUrl("account/register"); ?>" title="Crear Cuenta">Crear Cuenta</a>
        <?php } ?>
    </body>
</html>