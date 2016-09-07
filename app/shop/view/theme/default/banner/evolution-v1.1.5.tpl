<li class="nt-editable box bannerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<?php if (count($banner['items'])) { ?>
<div class="content" id="<?php echo $widgetName; ?>Content">
    <div id="<?php echo $widgetName; ?>slideshow">
    <?php foreach ($banner['items'] as $item) { ?>
        <?php if (empty($item['image'])) continue; ?>
        <div>
            <?php if (!empty($item['link'])) { ?><a href="<?php echo $item['link']; ?>" title="<?php echo $item['title']; ?>"><?php } ?>
            <img src="<?php echo HTTP_IMAGE . $item['image']; ?>"<?php if (!empty($item['title']) && !empty($item['description'])) { ?> alt="<?php if (!empty($item['title'])) { echo '<h3>'. $item['title'] .'</h3>'; } if (!empty($item['description'])) { echo '<em>'. htmlentities($item['description']) .'</em>'; } ?>"<?php } ?> />
            <?php if (!empty($item['link'])) { ?></a><?php } ?>
        </div>
    <?php } ?>
    </div>
</div>
<div class="clear"></div><br />
<script>
if (!$.fx.slideshow) {
    $(document.createElement('link')).attr({
        'href':'<?php echo HTTP_CSS; ?>sliders/evolution-v1.1.5/slider.css',
        'rel':'stylesheet',
        'media':'screen'
    }).appendTo('head');
    $(document.createElement('script')).attr({
        'src':'<?php echo HTTP_JS; ?>sliders/evolution-v1.1.5/slider.js',
        'type':'text/javascript',
    }).appendTo('head');
}
$(function(){
    $("#<?php echo $widgetName; ?>slideshow").slideshow({
        width      : $('#<?php echo $widgetName; ?>').width(),
        height     : 400,
        transition : 'explode'
    });
});
</script>
<?php } ?>
</li>