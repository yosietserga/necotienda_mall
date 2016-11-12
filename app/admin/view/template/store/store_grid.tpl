<select id="batch">
    <option value="">Procesamiento en lote</option>
    <!--
    <option value="editAll">Editar</option>
    <option value="addToList">Agregar a una lista</option>
    -->
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
                <th><?php echo $Language->get('column_image'); ?></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_name; ?>')"<?php if ($sort == 'config_name') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_name'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_name; ?>')"<?php if ($sort == 'config_folder') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_folder'); ?></a></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($stores) { ?>
            <?php foreach ($stores as $store) { ?>
            <tr id="tr_<?php echo $store['store_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" <?php if ($store['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><img alt="<?php echo $store['name']; ?>" src="<?php echo $store['image']; ?>" style="padding: 1px; border: 1px solid #000;" /></td>
                <td><?php echo $store['name']; ?></td>
                <td><?php echo $store['folder']; ?></td>
                <td>
                <?php foreach ($store['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "activate") { 
                        $jsfunction = "activate(". $store['store_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $store['store_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "edit") {
                        $href = "href='" . $action['href'] ."'";
                        $jsfunction = "";
                    }
                ?>
                <a title="<?php echo $action['text']; ?>" <?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $store['store_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
                <?php } ?>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="8" style="text-align:center"><?php echo $Language->get('text_no_results'); ?></td></tr>
        <?php } ?>
        </tbody>
    </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>