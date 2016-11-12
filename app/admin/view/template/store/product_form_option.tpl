<div>
    
    <ul id="vtabs" class="vtabs">
        <?php foreach ($product_options as $_row => $product_option) { ?>
        <li><a data-target="#tab_option_<?php echo (int)$_row; ?>" id="option_<?php echo (int)$_row; ?>" onclick="showTab(this)">
        <?php foreach ($languages as $language) { ?><?php if ($language['language_id'] == $language_id) { ?><?php echo $product_option['language'][$language['language_id']]['name']; ?><?php } ?><?php } ?>
        <span title="Eliminar Opci&oacute;n" onclick="$('.vtabs_page').hide();$('.vtabs_page:first-child').show();$('#option_<?php echo (int)$_row; ?>').remove(); $('#tab_option_<?php echo (int)$_row; ?>').remove();" class="remove">&nbsp;</span>
        </a></li>
        <?php } ?>
        <li><a title="<?php echo $Language->get('button_add_option'); ?>" id="option_add" onclick="addRow();"><?php echo $Language->get('button_add_option'); ?>&nbsp;
        <span class="add">&nbsp;</span>
        </a></li>
    </ul>
    
    <div id="options">
    <?php foreach ($product_options as $_row => $product_option) { ?>
        <div id="tab_option_<?php echo (int)$_row; ?>" class="vtabs_page">
        <?php foreach ($languages as $language) { ?>
            <?php if ($language['language_id'] == $language_id) { ?>
            <h2><?php echo $product_option['language'][$language['language_id']]['name']; ?></h2><br />
            <?php } ?>
            
            <input showquick="off" title="<?php echo $Language->get('help_option'); ?>" name="product_option[<?php echo (int)$_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option['language'][$language['language_id']]['name']; ?>" placeholder="<?php echo $Language->get('entry_option'); ?>"<?php if ($language['language_id'] == $language_id) { ?> onkeyup="$('#option_<?php echo (int)$_row; ?>').text(this.value);$('#tab_option_<?php echo (int)$_row; ?> h2').text(this.value);"<?php } ?> />
            <img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
            <br /><br />
        <?php } ?>
            
            <table id="table_options_<?php echo (int)$_row; ?>" class="list">
                <thead>
                    <tr>
                        <th><?php echo $Language->get('entry_option_value'); ?></th>
                        <th><?php echo $Language->get('entry_quantity'); ?></th>
                        <th><?php echo $Language->get('entry_price'); ?></th>
                        <th><?php echo $Language->get('entry_prefix'); ?></th>
                        <th><?php echo $Language->get('entry_subtract'); ?></th>
                        <th><?php echo $Language->get('entry_sort_order'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($product_option['product_option_value']) { ?>
                <?php foreach ($product_option['product_option_value'] as $option_value_row => $product_option_value) { ?>
                
                    <tr id="option_<?php echo (int)$_row; ?>_value_<?php echo (int)$option_value_row; ?>">
                    
                        <td>
                        <?php foreach ($languages as $language) { ?>
                            <input showquick="off" title="<?php echo $Language->get('help_option_value'); ?>" type="text" name="product_option[<?php echo (int)$_row; ?>][product_option_value][<?php echo (int)$option_value_row; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo $product_option_value['language'][$language['language_id']]['name']; ?>" />
                            <img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                        <?php } ?>
                        </td>
                        
                        <td>
                            <input showquick="off" title="<?php echo $Language->get('help_quantity'); ?>" type="number" name="product_option[<?php echo (int)$_row; ?>][product_option_value][<?php echo (int)$option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="2" style="width:40px" />
                        </td>
                        
                        <td>
                            <input showquick="off" title="<?php echo $Language->get('help_price'); ?>" type="text" name="product_option[<?php echo (int)$_row; ?>][product_option_value][<?php echo (int)$option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" style="width:60px" />
                        </td>
                        
                        <td>
                            <select showquick="off" title="<?php echo $Language->get('help_prefix'); ?>" name="product_option[<?php echo (int)$_row; ?>][product_option_value][<?php echo (int)$option_value_row; ?>][prefix]">
                                <option value="+"<?php if ($product_option_value['prefix'] != '-') { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_plus'); ?></option>
                                <option value="-"<?php if ($product_option_value['prefix'] == '-') { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_minus'); ?></option>
                            </select>
                        </td>
                        
                        <td>
                            <select showquick="off" title="<?php echo $Language->get('help_subtract'); ?>" name="product_option[<?php echo (int)$_row; ?>][product_option_value][<?php echo (int)$option_value_row; ?>][subtract]">
                                <option value="1"<?php if ($product_option_value['subtract']) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_yes'); ?></option>
                                <option value="0"<?php if (!$product_option_value['subtract']) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_no'); ?></option>
                            </select>
                        </td>
                        
                        <td>
                            <input showquick="off" title="<?php echo $Language->get('help_sort_order'); ?>" type="number" name="product_option[<?php echo (int)$_row; ?>][product_option_value][<?php echo (int)$option_value_row; ?>][sort_order]" value="<?php echo $product_option_value['sort_order']; ?>" size="2" style="width:40px" />
                        </td>
                        
                        <td>
                            <a onclick="$('#option_<?php echo (int)$_row; ?>_value_<?php echo (int)$option_value_row; ?>').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a>
                        </td>
                        
                    </tr>
                    
                <?php } ?>
                <?php } ?>
                </tbody>
                
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                        <td><a onclick="addValue(<?php echo (int)$_row; ?>);" class="button"><?php echo $Language->get('button_add_option_value'); ?></a></td>
                    </tr>
                </tfoot>
                
            </table>
                
        </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
function addRow() {
    var _row = $('.vtabs_page:last-child').index() + 1 * 1;
    
    var html  = '<div id="tab_option_'+ _row +'" class="vtabs_page">';
    
    html += '<h2>Opci\u00F3n '+ _row +'</h2>tab_option_'+ _row;
    html += '<br />';
    
    <?php foreach ($languages as $language) { ?>
	html += '<input title="<?php echo $Language->get('help_option'); ?>" name="product_option['+ _row +'][language][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $Language->get('entry_option'); ?>"<?php if ($language['language_id'] == $language_id) { ?> onkeyup="$(\'#option_'+ _row +'\').text(this.value);$(\'#tab_option_'+ _row +' h2\').text(this.value);"<?php } ?> />';
	html += '<img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
    html += '<br />';
    html += '<br />';
    <?php } ?>
    
	html += '<table id="table_options_'+ _row +'" class="list">';
    
	html += '<thead>';
	html += '<tr>';
	html += '<th><?php echo $Language->get('entry_option_value'); ?></th>';
	html += '<th><?php echo $Language->get('entry_quantity'); ?></th>';
	html += '<th><?php echo $Language->get('entry_price'); ?></th>';
	html += '<th><?php echo $Language->get('entry_prefix'); ?></th>';
	html += '<th><?php echo $Language->get('entry_subtract'); ?></th>';
	html += '<th><?php echo $Language->get('entry_sort_order'); ?></th>';
	html += '<th>&nbsp;</th>';
	html += '</tr>';
	html += '</thead>';
    
	html += '<tbody></tbody>';
    
	html += '<tfoot>';
	html += '<tr>';
	html += '<td colspan="6"></td>';
	html += '<td><a onclick="addValue('+ _row +');" class="button"><?php echo $Language->get('button_add_option_value'); ?></a></td>';
	html += '</tr>';
	html += '</tfoot>';
    
	html += '</table>';
    
	html += '</div>';     
            
	$('#options').append(html);
	
    li = '<li>';
    li += '<a data-target="#tab_option_'+ _row +'" id="option_'+ _row +'" onclick="showTab(this)">';
    li += '<?php echo $Language->get('tab_option'); ?> '+ _row;
    li += '<span title="Eliminar Opci&oacute;n" onclick="$(\'.vtabs_page\').hide();$(\'.vtabs_page:first-child\').show();$(\'#option_'+ _row +'\').remove(); $(\'#tab_option_'+ _row +'\').remove();" class="remove">&nbsp;</span>';
    li += '</a>';
    li += '</li>';
    
    $('#option_add').before(li);
    $('.vtabs_page').hide();
    $('#tab_option_'+ _row).show();
}

function addValue(_row) {
    
    if (typeof _row == 'undefined') {
        var _row = $('.vtabs_page:last-child').index() + 1 * 1;
    }
    
    var _value_row = $('#table_options_'+ _row +' tbody tr:last-child').index() + 1 * 1;
    
    if (!_value_row) {
        _value_row = 0;
    }
    
	var html = '<tr id="option_'+ _row +'_value_'+ _value_row +'">'; 
    
    html += '<td>option_'+ _row +'_value_'+ _value_row;
    <?php foreach ($languages as $language) { ?>
    html += '<input title="<?php echo $Language->get('help_option_value'); ?>" type="text" name="product_option['+ _row +'][product_option_value]['+ _value_row +'][language][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $Language->get('entry_option_value'); ?>" />';
    html += '<img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    html += '<div class="clear"></div>';
    <?php } ?>
    html += '</td>';
                    
    html += '<td>';
    html += '<input title="<?php echo $Language->get('help_quantity'); ?>" type="number" name="product_option['+ _row +'][product_option_value]['+ _value_row +'][quantity]" value="" size="2" style="width:40px" />';
    html += '</td>';
    
    html += '<td>';
    html += '<input title="<?php echo $Language->get('help_price'); ?>" type="text" name="product_option['+ _row +'][product_option_value]['+ _value_row +'][price]" value="" style="width:60px" />';
    html += '</td>';
    
    html += '<td>';
    html += '<select title="<?php echo $Language->get('help_prefix'); ?>" name="product_option['+ _row +'][product_option_value]['+ _value_row +'][prefix]">';
    html += '<option value="+"><?php echo $Language->get('text_plus'); ?></option>';
    html += '<option value="-"><?php echo $Language->get('text_minus'); ?></option>';
    html += '</select>';
    html += '</td>';
    
    html += '<td>';
    html += '<select title="<?php echo $Language->get('help_subtract'); ?>" name="product_option['+ _row +'][product_option_value]['+ _value_row +'][subtract]">';
    html += '<option value="1"><?php echo $Language->get('text_yes'); ?></option>';
    html += '<option value="0"><?php echo $Language->get('text_no'); ?></option>';
    html += '</select>';
    html += '</td>';
    
    html += '<td>';
    html += '<input title="<?php echo $Language->get('help_sort_order'); ?>" type="number" name="product_option['+ _row +'][product_option_value]['+ _value_row +'][sort_order]" value="" size="2" style="width:40px" />';
    html += '</td>';
    
    html += '<td>';
    html += '<a onclick="$(\'#option_'+ _row +'_value_'+ _value_row +'\').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a>';
    html += '</td>';

	html += '</tr>';
	
	$('#table_options_'+ _row +' tbody').append(html);
}
</script>