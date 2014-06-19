<li class="nt-editable box plaintextWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<div class="content" id="<?php echo $widgetName; ?>Content"><p><?php echo $settings['text']; ?></p></div>
    <div class="clear"></div><br />
</li>