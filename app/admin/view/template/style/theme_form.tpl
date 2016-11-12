<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<div class="box">
        <h1><?php echo $Language->get('heading_title'); ?></h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
            <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>
                                
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <div class="row">
                <label><?php echo $Language->get('entry_name'); ?></label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" title="<?php echo $Language->get('help_name'); ?>" required="true" style="width:40%" />
            </div>
                        
            <div class="clear"></div>
                       
            <div class="row">
                <label><?php echo $Language->get('entry_default'); ?></label>
                <input type="checkbox" id="default" name="default" value="1" title="<?php echo $Language->get('help_default'); ?>"<?php if ($default) { ?> checked="checked"<?php } ?> required="true" />
            </div>
                   
            <div class="clear"></div>
                   
            <div class="row">
                <label><?php echo $Language->get('entry_date_start'); ?></label>
                <input type="necoDate" name="date_publish_start" id="date_publish_start" value="<?php echo isset($date_publish_start) ? $date_publish_start : ''; ?>" title="<?php echo $Language->get('help_date_start'); ?>" style="width:40%" />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_date_end'); ?></label>
                <input type="necoDate" name="date_publish_end" id="date_publish_end" value="<?php echo isset($date_publish_end) ? $date_publish_end : ''; ?>" title="<?php echo $Language->get('help_date_end'); ?>" style="width:40%" />
            </div>
            
            <div class="clear"></div>
                   
            <div class="row">
                <label><?php echo $Language->get('entry_template'); ?></label>
                <select name="template" onchange="$('#template').load('<?php echo $Url::createAdminUrl("setting/setting/template"); ?>&template=' + encodeURIComponent(this.value));" title="<?php echo $Language->get('help_template'); ?>">
                <?php foreach ($templates as $_template) { ?>
                    
                    <option value="<?php echo $_template; ?>"<?php if ($template == $_template) { ?> selected="selected"<?php } ?>><?php echo $_template; ?></option>
                <?php } ?>
                </select>
                <div class="clear"></div>
                <div style="margin-left: 220px;" id="template"></div>
            </div>
             
            <div class="clear"></div>
                   
            <div class="row">
             <?php if ($isSaved) { ?>
                <label><?php echo $Language->get('entry_theme_editor'); ?></label>
                <a href="<?php echo  HTTP_CATALOG; ?>/index.php?theme_editor=1&theme_id=<?php echo $theme_id; ?>&template=<?php echo $template; ?>" class="button" target="_blank"><?php echo $Language->get('text_open_theme_editor'); ?></a>
             <?php }else { ?>
            <div class="warning">Debes guardar el tema primero para poder ir al editor.</div>
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
<?php echo $footer; ?>