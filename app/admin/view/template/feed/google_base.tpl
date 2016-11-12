<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('image/feed.png');"><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $Language->get('button_save'); ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $Language->get('button_cancel'); ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $Language->get('entry_status'); ?></td>
          <td><select name="google_base_status">
              <?php if ($google_base_status) { ?>
              <option value="1" selected="selected"><?php echo $Language->get('text_enabled'); ?></option>
              <option value="0"><?php echo $Language->get('text_disabled'); ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $Language->get('text_enabled'); ?></option>
              <option value="0" selected="selected"><?php echo $Language->get('text_disabled'); ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $Language->get('entry_data_feed'); ?></td>
          <td><textarea cols="40" rows="5"><?php echo $data_feed; ?></textarea></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>