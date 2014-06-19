<div>
    <table id="special" class="list">
        <thead>
            <tr>
                <th><?php echo $Language->get('entry_customer_group'); ?></th>
                <th><?php echo $Language->get('entry_priority'); ?></th>
                <th><?php echo $Language->get('entry_price'); ?></th>
                <th><?php echo $Language->get('entry_date_start'); ?></th>
                <th><?php echo $Language->get('entry_date_end'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($product_specials as $special_row => $product_special) { ?>
            <tr id="special_row<?php echo $special_row; ?>" class="special_row">
                <td>
                    <select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                        <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                        <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                    </select>
                </td>
                <td><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2"></td>
                <td><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>"></td>
                <td><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo date('d-m-Y',strtotime($product_special['date_start'])); ?>" class="date"></td>
                <td><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo date('d-m-Y',strtotime($product_special['date_end'])); ?>" class="date"></td>
                <td><a onclick="$('#special_row<?php echo $special_row; ?>').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"></td>
                <td><a onclick="addSpecial();" class="button"><?php echo $Language->get('button_add_special'); ?></a></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
$(function(){
    $('.date').datepicker({dateFormat: 'dd-mm-yy'});
});
function addSpecial() {
    _row = $('.special_row:last-child').index() + 1 * 1;
	html = '<tr id="special_row' + _row + '" class="special_row">'; 
    html += '<td class="left"><select name="product_special[' + _row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '</select></td>';		
    html += '<td class="left"><input type="number" name="product_special[' + _row + '][priority]" value="" size="2" style="width:40px"></td>';
	html += '<td class="left"><input type="text" name="product_special[' + _row + '][price]" value=""></td>';
    html += '<td class="left"><input type="text" name="product_special[' + _row + '][date_start]" value="" class="date"></td>';
	html += '<td class="left"><input type="text" name="product_special[' + _row + '][date_end]" value="" class="date"></td>';
	html += '<td class="left"><a onclick="$(\'#special_row' + _row + '\').remove();" class="button"><span><?php echo $Language->get('button_remove'); ?></span></a></td>';
	html += '</tr>';
	$('#special tbody').append(html);
 
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
}
</script>             