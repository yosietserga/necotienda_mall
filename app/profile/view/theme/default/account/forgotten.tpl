<?php echo $header; ?>
<?php echo $navigation; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div class="grid_10" id="content">
      <h1><?php echo $heading_title; ?></h1>
      
  <?php if (isset($mostrarError)) echo $mostrarError; ?>
  <div class="middle">
    <?php if ($error) { ?>
    <div class="warning"><?php echo $error; ?></div>
    <?php } ?>
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="forgotten">
      <p><?php echo $text_email; ?></p>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_email; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td class="tdW150"><?php echo $entry_email; ?></td>
            <td><input type="text" name="email"></td>
          </tr>
        </table>
      </div>
      <div class="buttons">
        <table>
          <tr>
            <td><a title="<?php echo $button_back; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td class="tdTopRight"><a title="<?php echo $button_continue; ?>" onclick="$('#forgotten').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?> 