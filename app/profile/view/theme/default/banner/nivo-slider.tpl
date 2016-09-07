<li class="nt-editable box bannerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<?php if (count($banner['items'])) { ?>
<div class="content" id="<?php echo $widgetName; ?>Content">
<div class="slider-wrapper theme-default">
    <div id="slider" class="nivoSlider">
        <?php foreach ($banner['items'] as $item) { ?>
            <?php if (empty($item['image'])) continue; ?>
            <?php if (!empty($item['link'])) { ?><a href="<?php echo $item['link']; ?>" title="<?php echo $item['title']; ?>"><?php } ?>
            <img src="<?php echo HTTP_IMAGE . $item['image']; ?>" data-thumb="<?php echo $Image->resizeAndSave($item['image'],50,50); ?>" alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>" />
            <?php if (!empty($item['link'])) { ?></a><?php } ?>
        <?php } ?>
    </div> 
</div>
<div class="clear"></div><br />
<script type="text/javascript" src="<?php echo HTTP_JS; ?>sliders/jquery.nivo.slider.pack.js"></script>
<script>
$(document.createElement('link')).attr({
    'href':'<?php echo HTTP_CSS; ?>sliders/nivo-slider.css',
    'rel':'stylesheet',
    'media':'screen'
}).appendTo('head');
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
</li>
