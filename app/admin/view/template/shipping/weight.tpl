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

            <ul id="vtabs" class="vtabs">
                <li><a data-target="#tab_general" onclick="showTab(this)"><?php echo $Language->get('tab_general'); ?></a></li>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <li><a data-target="#tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>" onclick="showTab(this)"><?php echo $geo_zone['name']; ?></a></li>
                <?php } ?>
            </ul> 
            
            <div id="tab_general" class="vtabs_page" style="float:left">
                <h2>General</h2>
                <div class="row">
                    <label><?php echo $Language->get('entry_tax'); ?></label>
                    <select name="weight_tax_class_id">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($tax_classes as $tax_class) { ?>
                        <option value="<?php echo $tax_class['tax_class_id']; ?>" <?php if ($tax_class['tax_class_id'] == $weight_tax_class_id) { ?> selected="selected"<?php } ?>><?php echo $tax_class['title']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                            
                <div class="row">
                    <label><?php echo $Language->get('entry_status'); ?></label>
                    <select name="weight_status">
                        <option value="1"<?php if ($weight_status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_enabled'); ?></option>
                        <option value="0"<?php if (!$weight_status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_disabled'); ?></option>
                    </select>
                </div>
                   
                <div class="clear"></div>
                        
                <div class="row">
                    <label><?php echo $Language->get('entry_sort_order'); ?></label>
                    <input title="<?php echo $Language->get('help_sort_order'); ?>" type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" style="width: 40%;" />
                </div>
                   
        </div>
        
        <?php foreach ($geo_zones as $geo_zone) { ?>
        <div id="tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>" class="vtabs_page" style="float:left">
            <h2><?php echo $geo_zone['name']; ?></h2>
                <div class="row">
                    <label><?php echo $Language->get('entry_rate'); ?></label>
                    <textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea>
                </div>
                            
                <div class="row">
                    <label><?php echo $Language->get('entry_status'); ?></label>
                    <select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status">
                        <option value="1"<?php if (${'weight_' . $geo_zone['geo_zone_id'] . '_status'}) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_enabled'); ?></option>
                        <option value="0"<?php if (!${'weight_' . $geo_zone['geo_zone_id'] . '_status'}) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_disabled'); ?></option>
                    </select>
                </div>
                
        </div>  
        <?php } ?>
            
            
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