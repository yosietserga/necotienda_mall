<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/customer.png');"><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons"><a title="" onClick="$('#form').submit();" class="button"><span><?php echo $Language->get('button_save'); ?></span></a><a title="" onClick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $Language->get('button_cancel'); ?></span></a></div>
  </div>
  <div class="content">
    <div style="display: inline-block; width: 100%;">
      <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            
            <tr>
              <td><span class="required">*</span> <?php echo $Language->get('entry_email'); ?><a title="<?php echo $Language->get('help_email'); ?>"> (?)</a></td>
              <td><input title="<?php echo $Language->get('help_email'); ?>" type="text" name="email" value="<?php echo $email; ?>">
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span><?php echo $Language->get('entry_password'); ?><a title="<?php echo $Language->get('help_password'); ?>"> (?)</a></td>
              <td><input title="<?php echo $Language->get('help_password'); ?>" type="password" name="password" value="<?php echo $password; ?>">
                <br>
                <?php if ($error_password) { ?>
                <span class="error"><?php echo $error_password; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span><?php echo $Language->get('entry_confirm'); ?><a title="<?php echo $Language->get('help_confirm'); ?>"> (?)</a></td>
              <td><input title="<?php echo $Language->get('help_confirm'); ?>" type="password" name="confirm" value="<?php echo $confirm; ?>">
                <?php if ($error_confirm) { ?>
                <span class="error"><?php echo $error_confirm; ?></span>
                <?php  } ?></td>
            </tr>
          </table>
      </form>
    </div>
  </div>
</div>
<script><!--
$(function(){    
	jQuery('#pdf_button img').attr('src','view/image/menu/pdf_off.png');
	jQuery('#excel_button img').attr('src','view/image/menu/excel_off.png');
	jQuery('#csv_button img').attr('src','view/image/menu/csv_off.png');
})
//--></script>
<?php echo $footer; ?>