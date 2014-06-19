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
    
    <ul id="vtabs" class="vtabs">
        <li><a data-target="#tab_general" onclick="showTab(this)"><?php echo $Language->get('tab_general'); ?></a></li>
        <li><a data-target="#tab_password" onclick="showTab(this)">Contrase&ntilde;a</a></li>
        <li><a data-target="#tab_social" onclick="showTab(this)">Social</a></li>
        <?php foreach ($addresses as $address_row => $address) { ?>
        <li><a data-target="#tab_address_<?php echo $address_row; ?>" id="address_<?php echo $address_row; ?>" onclick="showTab(this)"><?php echo $Language->get('tab_address') . ' ' . $address_row; ?>
        <span title="Eliminar Direcci&oacute;n" onclick="$('#vtabs a:first').trigger('click'); $('#address_<?php echo $address_row; ?>').remove(); $('#tab_address_<?php echo $address_row; ?>').remove();" class="remove">&nbsp;</span>
        </a></li>
        <?php } ?>
        <li><a title="Agregar Direcci&oacute;n" id="address_add" onclick="addAddress();">Agregar Direcci&oacute;n&nbsp;
        <span class="add">&nbsp;</span>
        </a></li>
    </ul>
    
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
    
        <div class="vtabs_page" id="tab_general"><?php require_once(dirname(__FILE__)."/customer_form_general.tpl"); ?></div>
        <div class="vtabs_page" id="tab_password"><?php require_once(dirname(__FILE__)."/customer_form_password.tpl"); ?></div>
        <div class="vtabs_page" id="tab_social"><?php require_once(dirname(__FILE__)."/customer_form_social.tpl"); ?></div>
        
        <?php foreach ($addresses as $address_row => $address) { ?>
        <div id="tab_address_<?php echo $address_row; ?>" class="vtabs_page">
            <h2>Direcci&oacute;n <?php echo $address_row; ?></h2>
            <table class="form">
                <tr>
                    <td><?php echo $Language->get('entry_country'); ?><a title="<?php echo $Language->get('help_country'); ?>"> (?)</a></td>
                    <td>
                        <select name="addresses[<?php echo $address_row; ?>][country_id]" id="addresses[<?php echo $address_row; ?>][country_id]" onchange="$('select[name=\'addresses[<?php echo $address_row; ?>][zone_id]\']').load('<?php echo $Url::createAdminUrl('sale/customer/zone'); ?>&amp;country_id='+ this.value +'&zone_id=<?php echo $address['zone_id']; ?>');">
                            <option value="false"><?php echo $Language->get('text_select'); ?></option>
                            <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['country_id']; ?>"<?php if ($country['country_id'] == $address['country_id']) { ?> selected="selected"<?php } ?>><?php echo $country['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $Language->get('entry_zone'); ?><a title="<?php echo $Language->get('help_zone'); ?>"> (?)</a></td>
                    <td><select name="addresses[<?php echo $address_row; ?>][zone_id]"></select></td>
                </tr>
                <tr>
                    <td><?php echo $Language->get('entry_city'); ?><a title="<?php echo $Language->get('help_city'); ?>"> (?)</a></td>
                    <td><input title="<?php echo $Language->get('help_city'); ?>" name="addresses[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>"></td>
                </tr>
                <tr>
                    <td><?php echo $Language->get('entry_postcode'); ?><a title="<?php echo $Language->get('help_postcode'); ?>"> (?)</a></td>
                    <td><input title="<?php echo $Language->get('help_postcode'); ?>" name="addresses[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>"></td>
                </tr>
                <tr>
                    <td><?php echo $Language->get('entry_address_1'); ?><a title="<?php echo $Language->get('help_address_1'); ?>"> (?)</a></td>
                    <td><input title="<?php echo $Language->get('help_address_1'); ?>" name="addresses[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" size="100"></td>
                </tr>
            </table>
            <script type="text/javascript">
		  $('select[name=\'addresses[<?php echo $address_row; ?>][zone_id]\']').load('<?php echo $Url::createAdminUrl('sale/customer/zone'); ?>&country_id=<?php echo $address["country_id"]; ?>&zone_id=<?php echo $address["zone_id"]; ?>');
            </script> 
        </div>
        <?php } ?>
    </form>
</div>
<?php echo $footer; ?>