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
    <h1 style="background-image: url('view/image/customer.png');"><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons"><a title="" onClick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $Language->get('button_insert'); ?></span></a><a title="" onClick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').submit();" class="button"><span><?php echo $Language->get('button_delete'); ?></span></a></div>
  </div>
  <div class="content">
    <form action="" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input title="Seleccionar Todos" type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
            <td class="left">
              <?php echo $Language->get('column_email'); ?></td>
            <td class="left">Cuota</td>              
            <td class="left">Usado</td>
            <td class="left">Porcentaje Usado</td>
          </tr>
        </thead>
        <tbody>
          <?php if ($accounts) { ?>
          <?php foreach ($accounts as $account) { ?>
          <?php if (!$account->email) continue; ?>
          <tr>
            <td style="text-align: center;"><?php if ($account['selected']) { ?>
              <input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $account->user; ?>" checked="checked" />
              <?php } else { ?>
              <input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $account->user; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $account->email; ?></td>
            <td class="left"><?php echo $account->humandiskquota; ?></td>
            <td class="left"><?php echo $account->humandiskused; ?></td>
            <td class="left"><?php echo $account->diskusedpercent; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $Language->get('text_no_results'); ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<form action="index.php?route=tool/excel&token=<?php echo $_GET['token']; ?>" name="excel_form" id="excel_form" method="post">
	<input title="" type='hidden' value='<?php echo $content_excel; ?>' name='excel_data' id='excel_data'>
</form>
<form action="index.php?route=tool/csv&token=<?php echo $_GET['token']; ?>" name="csv_form" id="csv_form" method="post">
	<input title="" type='hidden' value='<?php echo $content_csv; ?>' name='csv_data' id='csv_data'>
</form>
<script><!--
$(function(){    
	jQuery('#pdf_button img').attr('src','view/image/menu/pdf_off.png');
})
//--></script>
<script type="text/javascript"><!--
function excel() {
	jQuery('#excel_form').submit();
}
function csv() {
	jQuery('#csv_form').submit();
}
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').attr('value');
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	var filter_approved = $('select[name=\'filter_approved\']').attr('value');
	
	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>