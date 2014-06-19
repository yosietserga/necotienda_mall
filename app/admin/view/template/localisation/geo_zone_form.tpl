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
                <input type="text" name="name" id="name" value="<?php echo $name; ?>" placeholder="Venezuela" required="required" style="width:40%" />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_description'); ?></label>
                <input type="text" name="description" id="description" value="<?php echo $description; ?>" placeholder="Alguna Descripci&oacute;n" required="required" style="width:40%" />
            </div>
            
            <div class="clear"></div><br />
            
            <table id="zone_to_geo_zone" class="list">
                <thead>
                    <tr>
                        <th><?php echo $Language->get('entry_country'); ?></th>
                        <th><?php echo $Language->get('entry_zone'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($zone_to_geo_zones as $row => $zone_to_geo_zone) { ?>
                    <tr id="_row<?php echo $row; ?>">
                        <td>
                            <select name="zone_to_geo_zone[<?php echo $row; ?>][country_id]" id="country<?php echo $row; ?>" onchange="$('#zone<?php echo $row; ?>').load('<?php echo $Url::createAdminUrl("localisation/geo_zone/zone"); ?>&country_id=' + this.value + '&zone_id=0');">
                            <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['country_id']; ?>"<?php if ($country['country_id'] == $zone_to_geo_zone['country_id']) { ?> selected="selected"<?php } ?>><?php echo $country['name']; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                        <td><select name="zone_to_geo_zone[<?php echo $row; ?>][zone_id]" id="zone<?php echo $row; ?>"></select></td>
                        <td><a onclick="$('#_row<?php echo $row; ?>').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2"></td>
                    <td><a onclick="add();" class="button"><?php echo $Language->get('button_add_geo_zone'); ?></a></td>
                  </tr>
                </tfoot>
            </table>
      
            <div class="clear"></div><br />
            
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
$(function() {
    <?php foreach ($zone_to_geo_zones as $row => $zone_to_geo_zone) { ?>
    $('#zone<?php echo $row; ?>').load('<?php echo $Url::createAdminUrl("localisation/geo_zone/zone") 
    ."&country_id=". $zone_to_geo_zone['country_id'] 
    ."&zone_id=". $zone_to_geo_zone['zone_id']; ?>');
    <?php } ?>
});
function add() {
    _row = ($('#zone_to_geo_zone tbody tr:last-child').index() + 1);
	html  = '<tr id="_row'+ _row +'">';
	html += '<td><select name="zone_to_geo_zone[' + _row + '][country_id]" id="country'+ _row +'" onchange="$(\'#zone'+ _row +'\').load(\'<?php echo $Url::createAdminUrl("localisation/geo_zone/zone"); ?>&country_id=\' + this.value + \'&zone_id=0\');">';
	<?php foreach ($countries as $country) { ?>
	html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
	<?php } ?>   
	html += '</select></td>';
	html += '<td><select name="zone_to_geo_zone['+ _row +'][zone_id]" id="zone'+ _row +'"></select></td>';
	html += '<td><a title="" onclick="$(\'#zone_to_geo_zone_row'+ _row +'\').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>';
	html += '</tr>';
	
	$('#zone_to_geo_zone tbody').append(html);
		
	$('#zone' + _row).load('<?php echo $Url::createAdminUrl("localisation/geo_zone/zone"); ?>&country_id=' + $('#country' + _row).attr('value') + '&zone_id=0');
}
</script>
<?php echo $footer; ?>