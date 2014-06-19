<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
<div class="box">
        <h1><?php echo $heading_title; ?></h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
            <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        
            <input type="hidden" name="customer_id" id="customer_id" value="<?php echo ($customer_id) ? $customer_id : ''; ?>" />
            
            <div class="row">
                <label>Email</label>
                <select id="_email" showquick="off"></select>
                <input type="hidden" name="email" id="email" value="<?php echo ($email) ? $email : ''; ?>" />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label>Nombre Completo</label>
                <input type="text" name="name" id="name" value="<?php echo ($name) ? $name : ''; ?>" required="required" />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label>Agregar a Lista de Contactos:</label>
                <?php if ($lists) { ?>
                <input type="text" title="Filtrar listas de contactos" value="" name="q" id="q" placeholder="Filtrar Listas de Contactos" />
                <div class="clear"></div>
                <label>&nbsp;</label>
                <ul id="contactsWrapper" class="scrollbox">
                <?php foreach ($lists as $list) { ?>
                    <li>
                        <input title="<?php echo $Language->get('help_contact'); ?>" type="checkbox" name="contact_list[]" value="<?php echo $list['contact_list_id']; ?>"<?php if (in_array($list['contact_list_id'], $contact_lists)) { ?> checked="checked"<?php } ?> showquick="off" />
                        <b><?php echo $list['name']; ?></b>
                    </li>
                <?php } ?>
                </ul>
                <?php } else { ?>
                No hay listas de contactos registradas
                <?php } ?>
            </div>
                   
    </form>
</div>

<div class="sidebar" id="feedbackPanel">
    <div class="tab"></div>
    <div class="content">
        <h2>Sugerencias</h2>
        <p style="margin: -10px auto 0px auto;">Tu opini&oacute;n es muy importante, dinos que quieres cambiar.</p>
        <form id="feedbackForm">
            <textarea name="feedback" id="feedback" cols="60" rows="10"></textarea>
            <input type="hidden" name="account_id" id="account_id" value="<?php echo C_CODE; ?>" />
            <input type="hidden" name="domain" id="domain" value="<?php echo HTTP_DOMAIN; ?>" />
            <input type="hidden" name="server_ip" id="server_ip" value="<?php echo $_SERVER['SERVER_ADDR']; ?>" />
            <input type="hidden" name="remote_ip" id="remote_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
            <input type="hidden" name="server" id="server" value="<?php echo serialize($_SERVER); ?>" />
            <div class="clear"></div>
            <br />
            <div class="buttons"><a class="button" onclick="sendFeedback()">Enviar Sugerencia</a></div>
        </form>
    </div>
</div>
<div class="sidebar" id="toolPanel">
    <div class="tab"></div>
    <div class="content">
        <h2>Herramientas</h2>
        <p>S&aacute;cale provecho a NecoTienda y aumenta tus ventas.</p>
        <ul>
            <li><a onclick="$('#addsWrapper').slideDown();$('html, body').animate({scrollTop:$('#addsWrapper').offset().top}, 'slow');">Agregar Productos</a></li>
            <li><a class="trends" data-fancybox-type="iframe" href="http://www.necotienda.com/index.php?route=api/trends&q=samsung&geo=VE">Evaluar Palabras Claves</a></li>
            <li><a>Eliminar Esta Categor&iacute;a</a></li>
        </ul>
        <div class="toolWrapper"></div>
    </div>
</div>
<div class="sidebar" id="helpPanel">
    <div class="tab"></div>
    <div class="content">
        <h2>Ayuda</h2>
        <p>No entres en p&aacute;nico, todo tiene una soluci&oacute;n.</p>
        <ul>
            <li><a>&iquest;C&oacute;mo se come esto?</a></li>
            <li><a>&iquest;C&oacute;mo relleno este formulario?</a></li>
            <li><a>&iquest;Qu&eacute; significan las figuritas al lado de los campos?</a></li>
            <li><a>&iquest;C&oacute;mo me desplazo a trav&eacute;s de las pesta&ntilde;as?</a></li>
            <li><a>&iquest;Pierdo la informaci&oacute;n si me cambio de pesta&ntilde;a?</a></li>
            <li><a>Preguntas Frecuentes</a></li>
            <li><a>Manual de Usuario</a></li>
            <li><a>Videos Tutoriales</a></li>
            <li><a>Auxilio, por favor ay&uacute;denme!</a></li>
        </ul>
    </div>
</div>
<script>
(function($){ $(window).load(function(){ 
    $('.ui-combobox-input').val('<?php echo $email; ?>').on('change',function(e){
        $('#email').val(this.value);
    });
});})(jQuery);
</script>
<?php echo $footer; ?>