<li class="nt-editable box lightboxWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>"><span<?php if ($settings['width'] || $settings['height']) { ?> style="<?php echo ($settings['width']) ? "width:".$settings['width'] : ""; ?>;<?php echo ($settings['height']) ? "height:".$settings['height'] : ""; ?>"<?php } ?>>
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<div class="content" id="<?php echo $widgetName; ?>Content"><?php echo html_entity_decode($page['description']); ?></div>
</span>
<div class="close">X</div>
</li>
<script>
$(function(){
    $('#<?php echo $widgetName; ?> .close').on('click',function(){
        $('#<?php echo $widgetName; ?>').remove();
    }).css({
        
    });
    lightBoxWindowResize();
    $(window).resize(function() {
        lightBoxWindowResize();
    });
});
function lightBoxWindowResize() {
    var width  = window.innerWidth;
    var height = window.innerHeight;
    
    width  = width - $('#<?php echo $widgetName; ?> span').width();
    height = height - $('#<?php echo $widgetName; ?> span').height();
    
    width  = width/2;
    height = height/2;
    
    $('#<?php echo $widgetName; ?> span').css({
        'marginTop': height +'px',
        'marginLeft': width +'px'
    });
    
    var closeX = window.innerWidth - width;
    var closeY = $('#<?php echo $widgetName; ?> span').offset().top - 10;
    
    $('#<?php echo $widgetName; ?> .close').css({
        'top': closeY +'px',
        'left': closeX +'px'
    });
}
</script>