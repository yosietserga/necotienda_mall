<div>
    <table id="discount" class="list">
        <thead>
            <tr>
                <th><?php echo $Language->get('entry_customer_group'); ?></th>
                <th><?php echo $Language->get('entry_quantity'); ?></th>
                <th><?php echo $Language->get('entry_priority'); ?></th>
                <th><?php echo $Language->get('entry_price'); ?></th>
                <th><?php echo $Language->get('entry_date_start'); ?></th>
                <th><?php echo $Language->get('entry_date_end'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($product_discounts as $discount_row => $product_discount) { ?>
            <tr id="discount_row<?php echo $discount_row; ?>" class="discount_row">
                <td>
                    <select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                        <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                        <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                    </select>
                </td>
                <td><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2"></td>
                <td><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2"></td>
                <td><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>"></td>
                <td><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo date('d-m-Y',strtotime($product_discount['date_start'])); ?>" class="date"></td>
                <td><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo date('d-m-Y',strtotime($product_discount['date_end'])); ?>" class="date"></td>
                <td><a onclick="$('#discount_row<?php echo $discount_row; ?>').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"></td>
                <td><a onclick="addDiscount();" class="button"><?php echo $Language->get('button_add_discount'); ?></a></td>
            </tr>
        </tfoot>
    </table>
                
</div>
<script type="text/javascript">
$(function(){
    $('.date').datepicker({dateFormat: 'dd-mm-yy'});
});
function addDiscount() {
    _row = ($('.discount_row:last-child').index() + 1);
	html = '<tr id="discount_row' + _row + '" class="discount_row">'; 
    html += '<td class="left"><select name="product_discount[' + _row + '][customer_group_id]" style="margin-top: 3px;">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '</select></td>';
    html += '<td class="left"><input type="text" name="product_discount[' + _row + '][quantity]" value="" size="2" style="width:40px"></td>';
    html += '<td class="left"><input type="text" name="product_discount[' + _row + '][priority]" value="" size="2" style="width:40px"></td>';
	html += '<td class="left"><input type="text" name="product_discount[' + _row + '][price]" value=""></td>';
    html += '<td class="left"><input type="text" name="product_discount[' + _row + '][date_start]" value="" class="date"></td>';
	html += '<td class="left"><input type="text" name="product_discount[' + _row + '][date_end]" value="" class="date"></td>';
	html += '<td class="left"><a onclick="$(\'#discount_row' + _row + '\').remove();" class="button"><span><?php echo $Language->get('button_remove'); ?></span></a></td>';
	html += '</tr>';
	
	$('#discount tbody').append(html);
		
	$('.date').datepicker({dateFormat: 'dd-mm-yy'});
}
</script>