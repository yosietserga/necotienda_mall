<li class="nt-editable box subscribeWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <form name="subscribe" id="<?php echo $widgetName; ?>_subscribe_form">
            <input type="email" name="subscribe_email" id="<?php echo $widgetName; ?>_subscribe_email" value="" placeholder="Ingresa tu email" style="width: 90%;" />
            <a class="button" id="<?php echo $widgetName; ?>_submit_subscribe"><?php echo $Language->get('text_subscribe'); ?></a>
        </form>
    </div>
    <div class="clear"></div><br />
</li>
<script>
$(function(){
    $('#<?php echo $widgetName; ?>_submit_subscribe').on('click',function(event){
        $.post('<?php echo $Url::createUrl("module/subscribe/subscribe"); ?>',
        $('#<?php echo $widgetName; ?>_subscribe_form').serialize(),
        function(response){
            $('#temp').remove();
            $('#<?php echo $widgetName; ?>_subscribe_email').removeClass('neco-input-error');
            
            try {
               data = $.parseJSON(response);
            } catch(error) {
                data = response;
            }
            
            if (typeof data.success != 'undefined') {
                $('#<?php echo $widgetName; ?>_subscribe_form input').val('');
                $('#<?php echo $widgetName; ?>_subscribe_email').after('<div class="message success" id="temp">'+ data.msg +'</div>');
            }
            
            if (typeof data.error != 'undefined') {
                $('#<?php echo $widgetName; ?>_subscribe_email').addClass('neco-input-error').after('<div class="message warning" id="temp">'+ data.msg +'</div>');
            }
        });
    });
});
</script>