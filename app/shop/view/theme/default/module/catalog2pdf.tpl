<li class="nt-editable box catalog2pdfWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <a href="<?php echo str_replace("&","&amp;",$href); ?>" title="<?php echo $Language->get('text_download_pdf'); ?>" class="button"><?php echo $Language->get('text_download_pdf'); ?></a>
    </div>
    <div class="clear"></div><br />
</li>
