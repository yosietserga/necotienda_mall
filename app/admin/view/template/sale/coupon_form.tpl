<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
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
      
        <div id="languages" class="htabs">
        <?php foreach ($languages as $language) { ?>
            <a tab="#language<?php echo $language['language_id']; ?>" class="htab"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
        </div> 
      
        <?php foreach ($languages as $language) { ?>
        <div id="language<?php echo $language['language_id']; ?>">
                        
            <div class="row">
                <label><?php echo $Language->get('entry_name'); ?></label>
                <input id="coupon_description<?php echo $language['language_id']; ?>_name" name="coupon_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['name'] : ''; ?>" required="true" title="<?php echo $Language->get('help_name'); ?>" />
            </div>
                                
            <div class="clear"></div>
                                         
            <div class="row">
                <label><?php echo $Language->get('entry_description'); ?></label>
                <textarea title="<?php echo $Language->get('help_description'); ?>" name="coupon_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['description'] : ''; ?></textarea>
            </div>
                                  
        </div>
        <?php } ?>
          
        <div class="clear"></div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_code'); ?></label>
            <input id="code" name="code" value="<?php echo isset($code) ? $code : ''; ?>" required="true" title="<?php echo $Language->get('help_code'); ?>" />
        </div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_type'); ?></label>
            <select title="<?php echo $Language->get('help_type'); ?>" name="type">
              <option value="P"<?php if ($type == 'P') { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_percent'); ?></option>
              <option value="F"<?php if ($type == 'F') { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_amount'); ?></option>
            </select>
        </div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_discount'); ?></label>
            <input id="discount" name="discount" value="<?php echo isset($discount) ? $discount : ''; ?>" required="true" title="<?php echo $Language->get('help_discount'); ?>" />
        </div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_total'); ?></label>
            <input type="number" id="total" name="total" value="<?php echo isset($total) ? $total : ''; ?>" required="true" title="<?php echo $Language->get('help_total'); ?>" />
        </div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_logged'); ?></label>
            <input type="checkbox" id="logged" name="logged" value="1" title="<?php echo $Language->get('help_logged'); ?>" showquick="off"<?php if ($logged) { ?> checked="checked"<?php } ?> />
        </div>

        <div class="row">
            <label><?php echo $Language->get('entry_shipping'); ?></label>
            <input type="checkbox" id="shipping" name="shipping" value="1" title="<?php echo $Language->get('help_shipping'); ?>" showquick="off"<?php if ($shipping) { ?> checked="checked"<?php } ?> />
        </div>
        
        <div class="clear"></div>
            
        <div class="row">
            <label><?php echo $Language->get('entry_date_start'); ?></label>
            <input type="date" title="<?php echo $Language->get('help_date_start'); ?>" name="date_start" value="<?php echo $date_start; ?>" size="12" />
        </div>
            
        <div class="clear"></div>
            
        <div class="row">
            <label><?php echo $Language->get('entry_date_end'); ?></label>
            <input type="date" title="<?php echo $Language->get('help_date_end'); ?>" name="date_end" value="<?php echo $date_end; ?>" size="12" />
        </div>
            
        <div class="clear"></div>
            
        <div class="row">
            <label><?php echo $Language->get('entry_uses_total'); ?></label>
            <input type="number" title="<?php echo $Language->get('help_uses_total'); ?>" name="uses_total" value="<?php echo $uses_total; ?>" />
        </div>
            
        <div class="clear"></div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_uses_customer'); ?></label>
            <input type="number" title="<?php echo $Language->get('help_uses_customer'); ?>" name="uses_customer" value="<?php echo $uses_customer; ?>" />
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label><?php echo $Language->get('entry_status'); ?></label>
            <select title="<?php echo $Language->get('help_status'); ?>" name="status">
                <option value="1"<?php if ($status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_enabled'); ?></option>
                <option value="0"<?php if (!$status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_disabled'); ?></option>
            </select>
        </div>

            <?php if ($stores) { ?>
            <div class="clear"></div>
            <div class="row">
                <label><?php echo $Language->get('entry_store'); ?></label><br />
                <input type="text" title="Filtrar listado de tiendas y sucursales" value="" name="q" id="q" placeholder="Filtrar Tiendas" />
                <div class="clear"></div>
                <a onclick="$('#storesWrapper input[type=checkbox]').attr('checked','checked');">Seleccionar Todos</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a onclick="$('#storesWrapper input[type=checkbox]').removeAttr('checked');">Seleccionar Ninguno</a>
                <div class="clear"></div>
                <ul id="storesWrapper" class="scrollbox">
                    <li class="stores">
                        <input type="checkbox" name="stores[]" value="0"<?php if (in_array(0, $_stores)) { ?> checked="checked"<?php } ?> showquick="off" />
                        <b><?php echo $Language->get('text_default'); ?></b>
                        <div class="clear"></div>
                    </li>
                <?php foreach ($stores as $store) { ?>
                    <li class="stores">
                        <input type="checkbox" name="stores[]" value="<?php echo (int)$store['store_id']; ?>"<?php if (in_array($store['store_id'], $_stores)) { ?> checked="checked"<?php } ?> showquick="off" />
                        <b><?php echo $store['name']; ?></b>
                        <div class="clear"></div>
                    </li>
                <?php } ?>
                </ul>
            </div> 
            <?php } else { ?>
                <input type="hidden" name="stores[]" value="0" />
            <?php } ?>
            
        <div class="clear"></div><br />
            
        <div id="addsPanel"><b>Agregar / Eliminar Productos</b></div>
        <div id="addsWrapper"><div id="gridPreloader"></div></div>
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