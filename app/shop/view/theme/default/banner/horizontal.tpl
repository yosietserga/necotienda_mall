<li class="nt-editable box bannerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<?php if (count($banner['items'])) { ?>
<div class="content" id="<?php echo $widgetName; ?>Content">

    <ul class="horizontal-banner">
        <?php foreach ($banner['items'] as $item) { ?>
        <li>
            <?php if (empty($item['image'])) continue; ?>
            <?php if (!empty($item['link'])) { ?><a href="<?php echo $item['link']; ?>" title="<?php echo $item['title']; ?>"><?php } ?>
            <img src="<?php echo HTTP_IMAGE . $item['image']; ?>" data-thumb="<?php echo $item['thumb']; ?>" alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>" />
            <?php if (!empty($item['link'])) { ?></a><?php } ?>
        </li>
        <?php } ?>
    </ul> 
</div>
<div class="clear"></div><br />
<?php } ?>
</li>
