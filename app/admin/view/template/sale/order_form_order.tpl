<h2>Datos del Pedido</h2>
<table class="form">
	<tr>
		<td><?php echo $Language->get('entry_order_id'); ?></td>
		<td>#<?php echo $order_id; ?></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_invoice_id'); ?></td>
		<td id="invoice">
        <?php if ($invoice_id) { ?>
            <?php echo $invoice_id; ?>
        <?php } else { ?>
            <a id="generate_button" class="button" title="Genera Factura. Para poder ver e imprimir la factura, haga clic en el bot&oacute;n Pedido"><?php echo $Language->get('button_generate'); ?></a>
        <?php } ?>
        </td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_name'); ?></td>
		<td><?php if ($customer) { ?><a href="<?php echo $customer; ?>"><?php } ?><?php echo $firstname ." ". $lastname; ?><?php if ($customer) { ?></a><?php } ?></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_customer'); ?></td>
		<td><?php if ($customer) { ?><a href="<?php echo $customer; ?>"><?php } ?><?php echo $company; ?><?php if ($customer) { ?></a><?php } ?></td>
	</tr>
    <?php if ($customer_group) { ?>
	<tr>
		<td><?php echo $Language->get('entry_customer_group'); ?></td>
		<td><?php echo $customer_group; ?></td>
	</tr>
    <?php } ?>
	<tr>
		<td><?php echo $Language->get('entry_email'); ?></td>
		<td><input type="email" name="email" value="<?php echo $email; ?>" /></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_telephone'); ?></td>
		<td><input type="text" name="telephone" value="<?php echo $telephone; ?>" /></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_ip'); ?></td>
		<td><?php echo $ip; ?></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_store_name'); ?></td>
		<td><?php echo $store_name; ?></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_store_url'); ?></td>
		<td><a onclick="window.open('<?php echo $store_url; ?>');" style="font-style: italic;"><?php echo $store_url; ?></a></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_date_added'); ?></td>
		<td><?php echo $date_added; ?></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_shipping_method'); ?></td>
		<td><input type="text" name="shipping_method" value="<?php echo $shipping_method; ?>" /></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_payment_method'); ?></td>
		<td><input type="text" name="payment_method" value="<?php echo $payment_method; ?>" /></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_total'); ?></td>
		<td><?php echo $total; ?></td>
	</tr>
	<tr>
		<td><?php echo $Language->get('entry_order_status'); ?></td>
		<td id="order_status"><?php echo $order_status; ?></td>
	</tr>
    <?php if ($comment) { ?>
    <tr>
        <td><?php echo $Language->get('entry_comment'); ?></td>
        <td><?php echo $comment; ?></td>
    </tr>
    <?php } ?>
</table>
<script type="text/javascript">
$('#generate_button').on('click', function(e) {
    $('#generate_button').attr('disabled', 'disabled');
	$.getJSON('<?php echo $Url::createAdminUrl("sale/order/generate", array('order_id'=>$order_id)); ?>', 
        function(data) {
			if (data) {
				$('#generate_button').fadeOut('slow',function() {
				    $('#generate_button').remove();
				});
   	            $('#invoice').html(data);
			} else {
                $('#generate_button').attr('disabled', '');
			}
        });
});
</script>