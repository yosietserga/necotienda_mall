<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<?php echo $header; ?>
<?php echo $navigation; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div class="grid_10" id="content">
      <h1><?php echo $heading_title; ?></h1>
      
  <div class="middle">
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="newsletter">
    <?php echo isset($fkey)? $fkey : ''; ?>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td class="tdW150"><?php echo $entry_newsletter; ?></td>
            <td><?php if ($newsletter) { ?>
              <input type="radio" name="newsletter" value="1" checked="checked">
              <?php echo $text_yes; ?>&nbsp;
              <input type="radio" name="newsletter" value="0">
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="newsletter" value="1">
              <?php echo $text_yes; ?>&nbsp;
              <input type="radio" name="newsletter" value="0" checked="checked">
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <div class="buttons">
        <table>
          <tr>
            <td><a title="<?php echo $button_back; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td class="tdTopRight"><a title="<?php echo $button_continue; ?>" onclick="$('#newsletter').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?> 