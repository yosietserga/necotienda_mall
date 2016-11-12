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
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>