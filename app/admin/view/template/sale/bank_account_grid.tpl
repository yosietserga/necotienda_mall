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
                <th><?php echo $Language->get('column_number'); ?></th>
                <th><?php echo $Language->get('column_bank'); ?></th>
                <th><?php echo $Language->get('column_date_added'); ?></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($bank_accounts) { ?>
            <?php foreach ($bank_accounts as $bank_account) { ?>
            <tr id="tr_<?php echo $bank_account['bank_account_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $bank_account['bank_account_id']; ?>" <?php if ($bank_account['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><?php echo $bank_account['number']; ?></td>
                <td><?php echo $bank_account['bank']; ?></td>
                <td><?php echo $bank_account['date_added']; ?></td>
                <td>
                <?php foreach ($bank_account['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $bank_account['bank_account_id'] .")";
                        $href = "";
                        $img_id = "img_del_" . $bank_account['bank_account_id'];
                    } elseif ($action['action'] == "edit") {
                        $href = "href='" . $action['href'] ."'";
                        $jsfunction = "";
                        $img_id = "img_edit_" . $bank_account['bank_account_id'];
                    } 
                ?>
                <a title="<?php echo $action['text']; ?>" <?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $bank_account['bank_account_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
                <?php } ?>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="5" style="text-align:center"><?php echo $Language->get('text_no_results'); ?></td></tr>
        <?php } ?>
        </tbody>
    </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>