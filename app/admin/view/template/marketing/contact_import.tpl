<?php echo $header; if ($error_warning) { ?><div class="grid_24 warning"><?php echo $error_warning; ?></div>
<?php } if ($success) { ?><div class="grid_24 success"><?php echo $success; ?></div><?php } ?>
<div class="grid_24" id="msg"></div>
<div class="clear"></div>
<div class="grid_24">
    <div class="box">
        <div id="gridPreloader"></div>
        <div id="gridWrapper"></div>
    </div>
</div>
<?php echo $footer; ?>