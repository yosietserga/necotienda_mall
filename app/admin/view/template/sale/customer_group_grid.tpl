<select id="batch">
    <option value="">Procesamiento en lote</option>
    <!--
    <option value="editAll">Editar</option>
    <option value="addToList">Agregar a una lista</option>
    -->
    <option value="copyAll">Copiar</option>
    <option value="deleteAll">Eliminar</option>
</select>
<a href="#" title="Ejecutar acci&oacute;n por lote" onclick="if ($('#batch').val().length <= 0) { return false; } else { window[$('#batch').val()](); return false;}" style="margin-left: 10px;font-size: 10px;">[ Ejecutar ]</a>
<div class="clear"></div><br />
<div class="pagination"><?php echo $pagination; ?></div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
    <table id="list">
        <thead>
            <tr>
                <th><input title="Seleccionar Todos" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_name; ?>')"<?php if ($sort == 'cd.name') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_name'); ?></a></th>
                <th><?php echo $Language->get('column_qty_customers'); ?></th>
                <th><?php echo $Language->get('column_qty_orders'); ?></th>
                <th><?php echo $Language->get('column_qty_invoices'); ?></th>
                <th><?php echo $Language->get('column_qty_reviews'); ?></th>
                <th><?php echo $Language->get('column_qty_references'); ?></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($customer_groups) { ?>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <tr id="tr_<?php echo $customer_group['customer_group_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if ($customer_group['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><?php echo $customer_group['name']; ?></td>
                <td><?php echo $customer_group['qty_customers']; ?></td>
                <td><?php echo $customer_group['params']['qty_orders']; ?></td>
                <td><?php echo $customer_group['params']['qty_invoices']; ?></td>
                <td><?php echo $customer_group['params']['qty_reviews']; ?></td>
                <td><?php echo $customer_group['params']['qty_references']; ?></td>
                <td>
                <?php foreach ($customer_group['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "activate") { 
                        $jsfunction = "activate(". $customer_group['customer_group_id'] .")";
                        $href = "";
                        $img_id = "img_activate_" . $customer_group['customer_group_id'];
                    } elseif ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $customer_group['customer_group_id'] .")";
                        $href = "";
                        $img_id = "img_del_" . $customer_group['customer_group_id'];
                    } elseif ($action['action'] == "edit") {
                        $href = "href='" . $action['href'] ."'";
                        $jsfunction = "";
                        $img_id = "img_edit_" . $customer_group['customer_group_id'];
                    } 
                ?>
                <a title="<?php echo $action['text']; ?>" <?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $customer_group['customer_group_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
                <?php } ?>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="4" style="text-align:center"><?php echo $Language->get('text_no_results'); ?></td></tr>
        <?php } ?>
        </tbody>
    </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>