<li class="nt-editable box bannerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<?php if (count($banner['items'])) { ?>
<div class="content" id="<?php echo $widgetName; ?>Content">
    <div class="camera_wrap camera_azure_skin" id="<?php echo $widgetName; ?>camera">
    <?php foreach ($banner['items'] as $item) { ?>
        <?php if (empty($item['image'])) continue; ?>
        <div data-thumb="<?php echo $Image->resizeAndSave($item['image'],50,50); ?>" data-src="<?php echo HTTP_IMAGE . $item['image']; ?>">
            <?php if (!empty($item['link'])) { ?><a href="<?php echo $item['link']; ?>" title="<?php echo $item['title']; ?>"><?php } ?>
            <div class="camera_caption fadeFromBottom">
                <?php echo $item['title']; ?><em><?php echo $item['description']; ?></em>
            </div>
            <?php if (!empty($item['link'])) { ?></a><?php } ?>
        </div>
    <?php } ?>
    </div>
</div>
<div class="clear"></div><br />
<script>
if (!$.fx.camera) {
    $(document.createElement('link')).attr({
        'href':'<?php echo HTTP_CSS; ?>sliders/camera-v1.3.4/slider.css',
        'rel':'stylesheet',
        'media':'screen'
    }).appendTo('head');
    $(document.createElement('script')).attr({
        'src':'<?php echo HTTP_JS; ?>sliders/camera-v1.3.4/jquery.mobile.customized.min.js',
        'type':'text/javascript',
    }).appendTo('head');
    $(document.createElement('script')).attr({
        'src':'<?php echo HTTP_JS; ?>sliders/camera-v1.3.4/slider.js',
        'type':'text/javascript',
    }).appendTo('head');
}

$(function(){
    $('#<?php echo $widgetName; ?>camera').camera({
        thumbnails: true
    });
});
</script>
<?php } ?>
</li>
