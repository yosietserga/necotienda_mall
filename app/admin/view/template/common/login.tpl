<?php echo $header; ?>
<div id="login">
    <h1><a href="http://www.necotienda.com/" title="NecoTienda.com"><img src="image/logo.png" alt="NecoTienda" /></a></h1>
    <?php if (!empty($error_warning)) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>
    <div class="box">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="formLogin">
            <label><?php echo $Language->get('entry_username'); ?></label>
            <input type="text" name="username" value="<?php echo $username; ?>" showquick="off" />
            
            <div class="clear"></div>
            
            <label><?php echo $Language->get('entry_password'); ?></label>
            <input type="password" name="password" value="<?php echo $password; ?>" showquick="off" />
            
            <div class="clear"></div>
            
            <a onclick="submit();" class="button"><?php echo $Language->get('button_login'); ?></a>
            
            <div class="clear"></div>
            <a href="<?php echo $Url::createAdminUrl("common/login/recover"); ?>" title="Haga click aqu&iacute; si olvid&oacute; su contrase&ntilde;a">&iquest;Olvid&oacute; su contrase&ntilde;a?</a>
            <?php if ($redirect) { ?>
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            <?php } ?>
        </form>
    </div>
</div>
<?php echo $footer; ?> 