<li class="nt-editable box loginWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if (!$this->customer->islogged()) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
    <?php if (!$this->customer->islogged()) { ?>
        <form name="login" id="<?php echo $widgetName; ?>_login_form">
            <input type="email" name="login_email" id="<?php echo $widgetName; ?>_login_email" value="" placeholder="Ingresa tu email" autocomplete="off" style="width: 90%;" />
            <input type="password" name="login_password" id="<?php echo $widgetName; ?>_login_password" value="" placeholder="password" autocomplete="off" style="width: 90%;" />
            <input type="hidden" name="login_token" id="<?php echo $widgetName; ?>_login_token" value="<?php echo $tokenLogin; ?>" autocomplete="off" />
            <a href="<?php echo $Url::createUrl("account/forgotten"); ?>"><?php echo $Language->get('text_forgotten'); ?></a><br /><br />
            <a href="<?php echo $Url::createUrl("account/register"); ?>"><?php echo $Language->get('text_register'); ?></a><br /><br />
            <a class="button" id="<?php echo $widgetName; ?>_submit_login"><?php echo $Language->get('text_login'); ?></a>
        </form>
    <?php } else {  ?>
        <b>Bienvenido(a), <?php echo $this->customer->getFirstName() ." ". $this->customer->getLastName(); ?></b><br /><br />
        <a href="<?php echo $Url::createUrl("account/account"); ?>" title="<?php echo $Language->get("text_my_account"); ?>"><?php echo $Language->get("text_my_account"); ?></a><br />
        <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $Language->get("text_my_orders"); ?>"><?php echo $Language->get("text_my_orders"); ?></a><br />
        <a href="<?php echo $Url::createUrl("account/payment"); ?>" title="<?php echo $Language->get("text_payments"); ?>"><?php echo $Language->get("text_payments"); ?></a><br />
        <a href="<?php echo $Url::createUrl("account/logout"); ?>" title="<?php echo $Language->get("text_logout"); ?>"><?php echo $Language->get("text_logout"); ?></a>
    <?php } ?>
    </div>
    <div class="clear"></div><br />
</li>
<?php if (!$this->customer->islogged()) { ?>
<script>
$(function(){
    $('#<?php echo $widgetName; ?>_submit_login').on('click',function(event){
        $.post('<?php echo $Url::createUrl("module/login/login"); ?>',{
            email:$('#<?php echo $widgetName; ?>_login_email').val(),
            password:$('#<?php echo $widgetName; ?>_login_password').crypt({method:'md5'}), 
            token:$('#<?php echo $widgetName; ?>_login_token').val()
        },
        function(response){
            $('#temp').remove();
            $('#<?php echo $widgetName; ?>_login_email').removeClass('neco-input-error');
            
            try {
               data = $.parseJSON(response);
            } catch(error) {
                data = response;
            }
            
            if (typeof data.success != 'undefined') {
                window.location.reload();
            }
            
            if (typeof data.error != 'undefined') {
                $('#<?php echo $widgetName; ?>_login_email').addClass('neco-input-error').after('<div class="message warning" id="temp">'+ data.msg +'</div>');
            }
        })
    });
});
</script>
<?php } ?>