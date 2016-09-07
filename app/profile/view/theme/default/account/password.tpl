<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
            <h1><?php echo $heading_title; ?></h1>
            <?php if ($error_warning) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>
              
            <div class="clear"></div>
            
            <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
        
                    <div class="row">
                        <label><?php echo $Language->get('entry_password'); ?></label>
                        <input type="password" name="password" id="password" value="" autocomplete="off" title="Ingrese una contrase&ntilde;a que empiece con letra, tenga una longitud m&iacute;nima de 6 caracteres, contenga al menos 1 may&uacute;scula,  1 min&uacute;scula,  1 n&uacute;mero y 1 caracter especial. Le recomendamos que no utilice fechas personales ni familiares, tampoco utilice iniciales de su nombre o familiares" required="required" />
                    <?php if ($error_password) { ?><div class="msg_error"><span class="error" id="error_password"><?php echo $error_password; ?></span></div><?php } ?>
                    </div>
                  
                    <div class="clear"></div>
                    
                    <div class="row">
                        <label><?php echo $Language->get('entry_confirm'); ?></label>
                        <input type="password" name="confirm" id="confirm" value="" autocomplete="off" title="Vuelva a escribir la contrase&ntilde;a" />
                    <?php if ($error_confirm) { ?><div class="msg_error"><span class="error" id="error_confirm"><?php echo $error_confirm; ?></span></div><?php } ?>
                    </div>
                  
                    <div class="clear"></div>
                    
            </form>
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>