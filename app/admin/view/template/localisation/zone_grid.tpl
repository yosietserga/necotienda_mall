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
    <h1 style="background-image: url('view/image/country.png');"><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons"><a title="" onClick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $Language->get('button_insert'); ?></span></a><a title="" onClick="$('form').submit();" class="button"><span><?php echo $Language->get('button_delete'); ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input title="Seleccionar Todos" type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
            <td class="left"><?php if ($sort == 'c.name') { ?>
              <a title="" href="<?php echo $sort_country; ?>" class="<?php echo strtolower($order); ?>"><?php echo $Language->get('column_country'); ?></a>
              <?php } else { ?>
              <a title="" href="<?php echo $sort_country; ?>"><?php echo $Language->get('column_country'); ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'z.name') { ?>
              <a title="" href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $Language->get('column_name'); ?></a>
              <?php } else { ?>
              <a title="" href="<?php echo $sort_name; ?>"><?php echo $Language->get('column_name'); ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'z.code') { ?>
              <a title="" href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $Language->get('column_code'); ?></a>
              <?php } else { ?>
              <a title="" href="<?php echo $sort_code; ?>"><?php echo $Language->get('column_code'); ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $Language->get('column_action'); ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($zones) { ?>
          <?php foreach ($zones as $zone) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($zone['selected']) { ?>
              <input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $zone['zone_id']; ?>" checked="checked">
              <?php } else { ?>
              <input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $zone['zone_id']; ?>">
              <?php } ?></td>
            <td class="left"><?php echo $zone['country']; ?></td>
            <td class="left"><?php echo $zone['name']; ?></td>
            <td class="left"><?php echo $zone['code']; ?></td>
            <td class="right"><?php foreach ($zone['action'] as $action) { ?>
              [ <a title="" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?php echo $Language->get('text_no_results'); ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>