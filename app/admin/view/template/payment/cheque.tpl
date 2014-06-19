<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<div class="box">
        <h1><?php echo $Language->get('heading_title'); ?></h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>
                                
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <div class="row">
                <label><?php echo $Language->get('entry_payable'); ?></label>
                    <select name="cheque_email_template" title="<?php echo $Language->get('help_email_template'); ?>">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($newsletters as $newsletter) { ?>
                        <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if ($newsletter['newsletter_id'] == $cheque_email_template) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                        <?php } ?>
                  </select>
            </div>
                        
            <div class="clear"></div>
                     
            <div class="row">
                <label><?php echo $Language->get('entry_payable'); ?></label>
                <textarea style="width: 40%;height:250px;" name="cheque_payable"><?php echo $cheque_payable; ?></textarea>
            </div>
                        
            <div class="clear"></div>
                     
            <div class="row">
                <label><?php echo $Language->get('entry_order_status'); ?></label>
                <select name="cheque_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"<?php if ($order_status['order_status_id'] == $cheque_order_status_id) { ?> selected="selected"<?php } ?>><?php echo $order_status['name']; ?></option>
                <?php } ?>
                </select>
            </div>
                        
            <div class="clear"></div>
                           
            <div class="row">
                <label><?php echo $Language->get('entry_geo_zone'); ?></label>
                <select name="cheque_geo_zone_id">
                    <option value="0"><?php echo $Language->get('text_all_zones'); ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php if ($geo_zone['geo_zone_id'] == $cheque_geo_zone_id) { ?> selected="selected"<?php } ?>><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_status'); ?></label>
                <select title="<?php echo $Language->get('help_status'); ?>" name="cheque_status">
                    <option value="1"<?php if ($cheque_status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_enabled'); ?></option>
                    <option value="0"<?php if (!$cheque_status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_disabled'); ?></option>
                </select>
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_sort_order'); ?></label>
                <input title="<?php echo $Language->get('help_sort_order'); ?>" type="text" name="cheque_sort_order" value="<?php echo $cheque_sort_order; ?>" style="width: 40%;" />
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