<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('image/log.png');"><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons"><a href="<?php echo $clear; ?>" class="button"><span><?php echo $Language->get('button_clear'); ?></span></a></div>
  </div>
  <div class="content">
    <textarea wrap="off" style="width: 99%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $log; ?></textarea>
  </div>
</div>
<script>
$(function(){    
	jQuery('#pdf_button img').attr('src','image/menu/pdf_off.png');
	jQuery('#excel_button img').attr('src','image/menu/excel_off.png');
	jQuery('#csv_button img').attr('src','image/menu/csv_off.png');
})
</script>
<?php echo $footer; ?>