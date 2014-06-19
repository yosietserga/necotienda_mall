<?php if (count($sliders)) { ?>
<link rel="stylesheet" href="<?php echo HTTP_CSS; ?>sliders/nivo-slider.css" media="screen" />
<li class="slider-wrapper theme-default sliderWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
    <div id="slider" class="nivoSlider">
        <?php foreach ($sliders as $slider) { ?>
            <?php if (empty($slider->image)) continue; ?>
            <?php if (!empty($slider->link)) { ?><a href="<?php echo $slider->link; ?>" title="<?php echo $slider->title; ?>"><?php } ?>
            <img src="<?php echo HTTP_IMAGE . $slider->image; ?>" data-thumb="<?php echo $slider->thumb; ?>" alt="<?php echo $slider->title; ?>"<?php if (!empty($slider->link)) { ?> title="<?php echo $slider->title; ?>"<?php } ?> />
            <?php if (!empty($slider->link)) { ?></a><?php } ?>
        <?php } ?>
    </div> 
</li>
<div class="clear"></div><br />
<script type="text/javascript" src="<?php echo HTTP_JS; ?>sliders/jquery.nivo.slider.pack.js"></script>
<script>
$(window).load(function() {
    $("#slider").nivoSlider({
        effect:'random', 
        slices:12,
        animSpeed:600,
        pauseTime:6000,
        startSlide:0, 
        directionNav:false, 
        directionNavHide:true, 
        controlNav:true, 
        controlNavThumbs:true, 
        controlNavThumbsFromRel:false,
        controlNavThumbsSearch: '.jpg', 
        controlNavThumbsReplace: '_thumb.jpg',
        keyboardNav:true, 
        pauseOnHover:true, 
        manualAdvance:false, 
        captionOpacity:0.8, 
        beforeChange: function(){},
        afterChange: function(){},
        slideshowEnd: function(){} 
    });
});
</script>
<?php } ?>
