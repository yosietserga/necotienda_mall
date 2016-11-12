<?php echo $header; ?>
<div id="login">
    <h1><a href="http://www.necotienda.com/" title="NecoTienda.com"><img src="image/logo.png" alt="NecoTienda" /></a></h1>
    <?php if (!empty($error_warning)) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>
    <div class="box">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <label><?php echo $Language->get('entry_username'); ?></label>
            <input type="text" name="username" value="" autocomplete="off" />
            
            <div class="clear"></div>
            
            <label><?php echo $Language->get('entry_email'); ?></label>
            <input type="email" name="email" value="" autocomplete="off" />
            
            <div class="clear"></div>
            
            <a onclick="$('#form').submit();" class="button"><?php echo $Language->get('button_submit'); ?></a>
            
            <div class="clear"></div>
            <a href="<?php echo $Url::createAdminUrl("common/login"); ?>"><?php echo $Language->get('text_back'); ?></a>
        </form>
    </div>
</div>
<?php echo $footer; ?> 