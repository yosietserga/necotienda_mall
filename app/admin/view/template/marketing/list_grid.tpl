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
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_name; ?>')"<?php if ($sort == 'name') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_name'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_contacts; ?>')"<?php if ($sort == 'contacts') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_contacts'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_date_added; ?>')"<?php if ($sort == 'date_added') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_date_added'); ?></a></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($lists) { ?>
            <?php foreach ($lists as $list) { ?>
            <tr id="tr_<?php echo $list['contact_list_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $list['contact_list_id']; ?>" <?php if ($list['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><?php echo $list['name']; ?></td>
                <td><b><?php echo $list['total_contacts']; ?></b></td>
                <td><?php echo $list['date_added']; ?></td>
                <td>
                <?php foreach ($list['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "activate") { 
                        $jsfunction = "activate(". $list['contact_list_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $list['contact_list_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "edit") {
                        $href = " href='" . $action['href'] ."'";
                        $jsfunction = "";
                    } elseif ($action['action'] == "duplicate") {
                        $jsfunction = "copy(". $list['contact_list_id'] .")";
                        $href = "";
                    }
                ?>
                <a title="<?php echo $action['text']; ?>"<?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $list['contact_list_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
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