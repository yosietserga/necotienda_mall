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
                <label><?php echo $Language->get('entry_title'); ?></label>
                <input type="text" name="title" id="title" value="<?php echo $title; ?>" placeholder="IVA" required="required" style="width:40%" />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_description'); ?></label>
                <input type="text" name="description" id="description" value="<?php echo $description; ?>" placeholder="Impuesto al Valor Agregado" required="required" style="width:40%" />
            </div>
            
            <div class="clear"></div><br />
            
            <table id="tax_rate" class="list">
                <thead>
                    <tr>
                        <td><?php echo $Language->get('entry_geo_zone'); ?></td>
                        <td><?php echo $Language->get('entry_description'); ?></td>
                        <td><?php echo $Language->get('entry_rate'); ?></td>
                        <td><?php echo $Language->get('entry_priority'); ?></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tax_rates as $row => $tax_rate) { ?>
                    <tr id="_row<?php echo $row; ?>">
                        <td>
                            <select name="tax_rate[<?php echo $row; ?>][geo_zone_id]" id="geo_zone_id<?php echo $row; ?>">
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php if ($geo_zone['geo_zone_id'] == $tax_rate['geo_zone_id']) { ?> selected="selected"<?php } ?>><?php echo $geo_zone['name']; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td><input type="text" name="tax_rate[<?php echo $row; ?>][description]" value="<?php echo $tax_rate['description']; ?>" required="required" /></td>
                        <td><input type="text" name="tax_rate[<?php echo $row; ?>][rate]" value="<?php echo $tax_rate['rate']; ?>" required="required" /></td>
                        <td><input type="text" name="tax_rate[<?php echo $row; ?>][priority]" value="<?php echo $tax_rate['priority']; ?>" size="1" required="required" /></td>
                        <td><a onclick="$('#_row<?php echo $row; ?>').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>
                  </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4"></td>
                    <td><a title="" onclick="addRate();" class="button"><?php echo $Language->get('button_add_rate'); ?></a></td>
                  </tr>
                </tfoot>
              </table>
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

<script type="text/javascript">
function addRate() {
    _row = ($('#tax_rate tbody tr:last-child').index() + 1);
	html = '<tr id="_row' + _row + '">';
	html += '<td class="left"><select name="tax_rate[' + _row + '][geo_zone_id]">';
    <?php foreach ($geo_zones as $geo_zone) { ?>
    html += '<option value="<?php echo $geo_zone["geo_zone_id"]; ?>"><?php echo $geo_zone["name"]; ?></option>';
    <?php } ?>
	html += '</select></td>';
	html += '<td><input type="text" name="tax_rate[' + _row + '][description]" value=""></td>';
	html += '<td><input type="text" name="tax_rate[' + _row + '][rate]" value=""></td>';
	html += '<td><input type="text" name="tax_rate[' + _row + '][priority]" value="" size="1"></td>';
	html += '<td><a onclick="$(\'#_row' + _row + '\').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>';
	html += '</tr>';
	
	$('#tax_rate tbody').append(html);
}
</script>
<?php echo $footer; ?>