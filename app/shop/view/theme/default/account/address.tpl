<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    <section id="maincontent">
        <section id="content">
            <div class="grid_12">
                <div id="featuredContent">
                <ul class="widgets"><?php if($featuredWidgets) { foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } } ?></ul>
                </div>
            </div>

            <div class="clear"></div>

            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>

        
                <h1><?php echo $heading_title; ?></h1>
                <?php if ($error_warning) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>

                <div class="clear"></div>
            
                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
                    <div class="row">
                        <label for="country_id"><?php echo $Language->get('entry_country'); ?></label>
                        <select name="country_id" id="country_id" title="Selecciona el pa&iaacute;s de la facturaci&oacute;n" onchange="$('select[name=\'zone_id\']').load('index.php?r=account/register/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');" showquick="off">
                            <option value="false">-- Por Favor Seleccione --</option>
                            <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['country_id']; ?>"<?php if ($country['country_id'] == $country_id) { ?> selected="selected"<?php } ?>><?php echo $country['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="clear"></div>

                    <div class="row">
                        <label for="zone_id"><?php echo $Language->get('entry_zone'); ?></label>
                        <select name="zone_id" id="zone_id" title="Selecciona el estado donde reside" showquick="off">
                            <option value="false">-- Seleccione un pa&iacute;s --</option>
                        </select>
                    </div>
                      
                    <div class="clear"></div>

                    <div class="row">
                        <label for="city"><?php echo $Language->get('entry_city'); ?></label>
                        <input type="text" id="city" name="city" value="<?php echo $city; ?>" required="required" title="Ingrese el nombre de la ciudad" />
                    </div>
                  
                    <div class="clear"></div>

                    <div class="row">
                        <label for="postcode"><?php echo $Language->get('entry_postcode'); ?></label>
                        <input type="number" id="postcode" name="postcode" value="<?php echo $postcode; ?>" required="required" title="Ingrese el c&oacute;digo postal de su residencia" />
                    </div>
                  
                    <div class="clear"></div>

                    <div class="row">
                        <label for="address_1"><?php echo $Language->get('entry_address_1'); ?></label>
                        <input type="text" id="address_1" name="address_1" value="<?php echo $address_1; ?>" required="required" title="Ingrese la direcci&oacute;n de habitaci&oacute;n" />
                    </div>
                        
                    <div class="clear"></div>
                    
                    <div class="row">
                        <label for="address_1">Predeterminada</label>
                        <input type="checkbox" id="default" name="default" value="1"<?php if ($default) { ?> checked="checked"<?php } ?> title="Seleccione si desea utilizar esta direcci&oacute;n como predeterminada" showquick="off" />
                    </div>
                        
                    <div class="clear"></div>
                    
                    <input type="hidden" name="company" value="<?php echo $company; ?>" />
                    <input type="hidden" name="firstname" value="<?php echo $firstname; ?>" />
                    <input type="hidden" name="lastname" value="<?php echo $lastname; ?>" />
                </form>
             
            </div>
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script type="text/javascript">
$('#zone_id').load('index.php?r=account/address/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
</script>
<?php echo $footer; ?>