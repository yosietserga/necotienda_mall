<li class="nt-editable box bannerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<?php if (count($banner['items'])) { ?>
<div class="content" id="<?php echo $widgetName; ?>Content"></div>
<div class="clear"></div><br />
<script>
$(function(){
    if (typeof $.fn.ntContentCarousel == 'undefined') {
        $(document.createElement('link')).attr({
            'href':'<?php echo HTTP_CSS; ?>sliders/neco-content-carousel.css',
            'rel':'stylesheet',
            'media':'screen'
        }).appendTo('head');
        $(document.createElement('script')).attr({
            'src':'<?php echo HTTP_JS; ?>necojs/neco.contentCarousel.js',
            'type':'text/javascript'
        }).appendTo('head');
    }
    if ($.fn.ntContentCarousel) {
        $("#<?php echo $widgetName; ?>Content").ntContentCarousel({
            url:'<?php echo Url::createUrl("module/". $settings['module'] ."/carousel"); if ((int)$settings['banner_id']) echo '&banner_id='.(int)$settings['banner_id'] ?>',
            image: {
              width:<?php echo (int)$settings['width']; ?>,
              height:<?php echo (int)$settings['height']; ?>  
            },
            loading: {
              image: '<?php echo HTTP_IMAGE; ?>loader.gif'
            }
            <?php if ($settings['scroll']) { ?>,
            options: {
                scroll: <?php echo (int)$settings['scroll']; ?>
            }
            <?php } ?>
        });
    }
});
</script>
<?php } ?>
</li>