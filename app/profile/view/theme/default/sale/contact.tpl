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
        <link rel="stylesheet" href="<?php echo HTTP_CSS; ?>screen.css" />
        <link rel="stylesheet" href="<?php echo HTTP_CSS; ?>neco.form.css" />
        <link rel="stylesheet" href="<?php echo HTTP_CSS; ?>salecontact.css" />
        
        <script src="assets/js/vendor/modernizr-2.6.1.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-1.8.1.min.js"><\/script>')</script>
        <script src="assets/js/vendor/jquery-ui.min.js"></script>
        <script src="assets/js/necojs/neco.form.js"></script>
        <script src="assets/js/plugins/jquery.crypt.min.js"></script>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    
    <body>
        <div class="container_24">
            <?php if ($isLogged) { ?>
            <div class="grid_20" id="wrapperOffer">
                <div class="box">
                    <div class="header"><h3>Haz Tu Mejor Oferta!</h3></div>
                    <div class="content">
                    <form method="post" id="formOffer">
                        <div class="grid_24">
                            <div class="grid_23">
                                <label for="quantity">&iquest;Cu&aacute;ntos tienes disponibles?</label><br />
                                <select name="length_class_id" id="length_class_id">
                                <?php foreach($metrics as $metric) { ?>
                                    <option value="<?php echo $metric['length_class_id'] ?>"><?php echo $metric['name'] ?></option>
                                <?php } ?>
                                </select>
                                <input type="number" name="quantity" id="quantity" value="1" style="width:50px;float:left;" showquick="off" />
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="price">&iquest;Cu&aacute;l es el precio de cada uno?</label><br />
                                <input type="money" name="price" id="price" value="0,00" showquick="off" />
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="tax">&iquest;El precio incluye IVA?</label><br />
                                <select name="tax" id="tax">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="isnew">&iquest;Cu&aacute;l es la condici&oacute;n del producto?</label><br />
                                <select name="isnew" id="isnew">
                                    <option value="1">Nueva</option>
                                    <option value="0">Usada</option>
                                </select>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="availability">&iquest;Cu&aacute;l es la disponibilidad?</label><br />
                                <select name="availability" id="availability">
                                    <option value="available">Inmediata</option>
                                    <option value="ondemand">A Pedido</option>
                                </select>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="delivery">&iquest;En cu&aacute;nto tiempo lo entregas?</label><br />
                                <select name="delivery_time" id="delivery_time">
                                    <option value="hours">Horas</option>
                                    <option value="days">D&iacute;as</option>
                                    <option value="weeks">Semanas</option>
                                    <option value="months">Meses</option>
                                </select>
                                <input type="number" name="delivery" id="delivery" value="1" style="width:50px;float:left;" showquick="off" />
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="payment_method">&iquest;C&oacute;mo te pueden pagar?</label><br />
                                <ul>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="payment_method[]" value="transferencia" showquick="off" />&nbsp;Transferencia Bancaria</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="payment_method[]" value="deposito" showquick="off" />&nbsp;Dep&oacute;sito Bancario</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="payment_method[]" value="efectivo" showquick="off" />&nbsp;Efectivo / Contra Reembolso</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="payment_method[]" value="tc" showquick="off" />&nbsp;Tarjetas de Cr&eacute;dito</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="payment_method[]" value="td" showquick="off" />&nbsp;Tarjetas de D&eacute;bito</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="payment_method[]" value="cheque" showquick="off" />&nbsp;Cheque</li>
                                </ul>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_23">
                                <label for="shipping_method">&iquest;C&oacute;mo puedes enviar la mercanc&iacute;a?</label><br />                            
                                <ul>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="mrw" />&nbsp;MRW</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="dhl" />&nbsp;DHL</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="zoom" />&nbsp;ZOOM</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="fedex" />&nbsp;FedEX</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="tealca" />&nbsp;Tealca</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="propio" />&nbsp;Transporte Propio</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="cliente" />&nbsp;Por cuenta del cliente</li>
                                    <li><input type="checkbox" style="float: left;width:20px;" name="shipping_method[]" showquick="off" value="local" />&nbsp;Deben buscarla a nuestras oficinas</li>
                                </ul>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_24">
                                <textarea name="comment" placeholder="Obervaciones y comentarios" style="width: 96%;"></textarea>
                            </div>
                            
                            <div class="clear"></div><br />
                            
                            <div class="grid_20"><a class="buttonBlue" id="offerButton">Completar Oferta</a></div>
                            
                            <div class="clear"></div><br />
                            
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <div class="grid_11" id="wrapperLogin">
                <div class="box">
                    <div class="header gradientBlue"><h3 style="color: #fff;">Iniciar Sesi&oacute;n</h3></div>
                    <div class="content">
                        <form method="post" id="formLogin">
                            <input type="hidden" id="tokenLogin" name="token" value="<?php echo $token; ?>" />
                            
                            <div class="grid_10"><label for="email">Email:</label></div>
                            <div class="grid_11"><input type="email" name="email" value="" placeholder="Correo Electr&oacute;nico" showquick="off" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="email">Contrase&ntilde;a:</label></div>
                            <div class="grid_11"><input type="password" name="password" value="" placeholder="Contrase&ntilde;a" autocomplete="off" showquick="off" /></div>
                            
                            <div class="clear"></div>
                            
                            <a href="#" onclick="parent.location.href = '<?php echo $Url::createUrl("account/forgotten"); ?>';return false;">&iquest;Olvid&oacute; su contrase&ntilde;a?</a>
                            
                            <div class="clear"></div><br /><br />
                            
                            <a class="buttonBlue" id="loginButton">Iniciar Sesi&oacute;n</a>
                            
                            <div class="clear"></div><br />
                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="grid_10" id="wrapperRegister">
                <div class="box">
                    <div class="header"><h3>Crear Cuenta</h3></div>
                    <div class="content">
                        <form method="post" id="formRegister">
                            <div class="grid_10"><label for="email">Email:</label></div>
                            <div class="grid_13"><input type="email" showquick="off" id="email" name="email" value="<?php echo $email; ?>" placeholder="Correo Electr&oacute;nico" required="required" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="firstname">Nombres:</label></div>
                            <div class="grid_13"><input type="text" showquick="off" id="firstname" name="firstname" value="<?php echo $firstname; ?>" placeholder="Nombres" required="required" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="lastname">Apellidos:</label></div>
                            <div class="grid_13"><input type="text" showquick="off" id="lastname" name="lastname" value="<?php echo $lastname; ?>" placeholder="Apellidos" required="required" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="company">Nombre de la Empresa:</label></div>
                            <div class="grid_13"><input type="text" showquick="off" id="company" name="company" value="<?php echo $company; ?>" placeholder="Nombre Completo o Empresa" required="required" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="rif">C&eacute;dula o RIF:</label></div>
                            <div class="grid_13">
                                <select name="riftype" title="Selecciona el tipo de documentaci&oacute;n" style="width: 40px;float:left">
                                    <option value="V">V</option>
                                    <option value="J">J</option>
                                    <option value="E">E</option>
                                    <option value="G">G</option>
                                </select>
                                <input type="text" showquick="off" id="rif" name="rif" value="<?php echo $rif; ?>" placeholder="C&eacute;dula o RIF" required="required" style="width:125px;float:left" maxlength="10" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="telephone">Tel&eacute;fono:</label></div>
                            <div class="grid_13"><input type="text" showquick="off" id="telephone" name="telephone" value="<?php echo $telephone; ?>" placeholder="Tel&eacute;fono" required="required" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="password">Contrase&ntilde;a:</label></div>
                            <div class="grid_13"><input type="password" showquick="off" id="password" name="password" value="" required="required" autocomplete="off" placeholder="Contrase&ntilde;a" /></div>
                            
                            <div class="clear"></div>
                            
                            <div class="grid_10"><label for="email">Confirmar Contrase&ntilde;a:</label></div>
                            <div class="grid_13"><input type="password" showquick="off" id="confirm" name="confirm" value="" autocomplete="off" placeholder="Confirmar Contrase&ntilde;a" /></div>
                            
                            <div class="clear"></div><br /><br />
                            
                            <a class="buttonBlue" id="registerButton">Crear Cuenta</a>
                            
                            <div class="clear"></div><br />
                            
                        </form>
                    </div>
                </div>
            </div>
            
            <?php } ?>
            <div id="loading"><img src="<?php echo HTTP_IMAGE; ?>data/loader.gif" alt="Cargando..." /></div>
        </div>
        <script type="text/javascript">
        $(function(){
            $('form').ntForm({
                'submitButton':false,
                'cancelButton':false,
                'lockButton':false
            });
            $('#registerButton').on('click',function(event){
                $('#wrapperLogin, #wrapperRegister').hide();
                $('#loading').show();
                $('#temp').remove();
                
                $.post('<?php echo $Url::createUrl("account/register/register"); ?>',
                    {
                        'firstname':$('#formRegister input[name=firstname]').val(),
                        'lastname':$('#formRegister input[name=lastname]').val(),
                        'company':$('#formRegister input[name=company]').val(),
                        'email':$('#formRegister input[name=email]').val(),
                        'telephone':$('#formRegister input[name=telephone]').val(),
                        'riftype':$('#formRegister input[name=riftype]').val(),
                        'rif':$('#formRegister input[name=rif]').val(),
                        'password':$('#formRegister input[name=password]').crypt({method:'md5'}),
                        'confirm':$('#formRegister input[name=confirm]').crypt({method:'md5'}),
                    },
                    function(response) {
                        data = $.parseJSON(response);
                        if (typeof data.success != 'undefined') {
                            parent.jQuery.fancybox.close();
                        } else {
                            $('#wrapperLogin, #wrapperRegister').show();
                            $('#loading').hide();
                            $('#formRegister').prepend('<div class="message warning" id="temp"><ul>'+ data.msg +'</ul></div>');
                        }
                    }
                );
            });
            $('#loginButton').on('click',function(event){
                $('#wrapperLogin, #wrapperRegister').hide();
                $('#loading').show();
                $('#temp').remove();
                
                $.post('<?php echo $Url::createUrl("account/login/login"); ?>',
                    {
                        email:$('#formLogin input[name=email]').val(), 
                        password:$('#formLogin input[name=password]').crypt({method:'md5'}), 
                        token:$('#formLogin input[name=token]').val()
                    },
                    function(response) {
                        data = $.parseJSON(response);
                        if (typeof data.success != 'undefined') {
                            parent.location.reload();
                        } else {
                            $('#wrapperLogin, #wrapperRegister').show();
                            $('#loading').hide();
                            $('#formLogin').prepend('<div class="message warning" id="temp">'+ data.msg +'</div>');
                        }
                    }
                );
            });
            
            $('#offerButton').on('click',function(event){
                $('#wrapperOffer').hide();
                $('#loading').show();
                $('#temp').remove();
                
                $.post('<?php echo $Url::createUrl("sale/offer") .'&product_id='. $product_id; ?>',
                    $('#formOffer').serialize(),
                    function(response) {
                        data = $.parseJSON(response);
                        $('#wrapperOffer').show();
                        $('#loading').hide();
                        if (typeof data.error != 'undefined') {
                            $('#formOffer').prepend('<div class="message warning" id="temp" style="float:left;"><ul style="float:left;">'+ data.msg +'</ul></div>');
                        } else {
                            $('#formOffer').prepend('<div class="message success" id="temp">'+ data.msg +'<br /><br /><a class="buttonBlue" onclick="parent.jQuery.fancybox.close()">Cerrar</a></div>');
                        }
                    }
                );
            });
        });
        </script>
    </body>
</html>