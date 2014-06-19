<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
<div class="grid_24" id="msg"></div>
<div class="grid_24">
    <div class="box">
        <div class="header">
            <h1><?php echo $Language->get('heading_title'); ?></h1>
            <div class="buttons">
                <a onclick="$('.header').after('<img src=\'image/loader.gif\' alt=\'Actualizando...\' /><div class=\'message warning\'>Esto puede tardar unos minutos, no cancele ni cierre el navegador.</div>');$('#form').submit();" class="button">Actualizar</a>
            </div>
        </div>      
        <div class="clear"></div><br />
        
        <h3>Versi&oacute;n Actual</h3>
        <?php echo VERSION; ?>
        <div class="clear"></div><br />
        
        <h3>Versi&oacute;n Disponible</h3>
        <?php echo $update_info['version']; ?>
        <div class="clear"></div><br />
        
        <h3>Descripci&oacute;n</h3>
        <?php echo $update_info['description']; ?>
        <div class="clear"></div><br />
        
        <h3>Cambios Realizados (Changelog)</h3>
        <?php echo $update_info['changelog']; ?>
        <div class="clear"></div><br />
        
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        
            <input type="hidden" name="version" value="<?php echo $update_info['version']; ?>" />
                
        </form>
    </div>
</div>

<?php echo $footer; ?>