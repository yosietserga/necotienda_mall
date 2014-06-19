<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('image/backup.png');"><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons"><a onclick="$('#restore').submit();" class="button"><span><?php echo $Language->get('button_restore'); ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
      <table class="form">
        <tr>
          <td><?php echo $Language->get('entry_restore'); ?><a title="<?php echo $Language->get('help_restore'); ?>"> (?)</a></td>
          <td><input title="<?php echo $Language->get('help_restore'); ?>" type="file" name="import"></td>
        </tr>
      </table>
    </form>
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