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
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_title; ?>')"<?php if ($sort == 'title') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_title'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_code; ?>')"<?php if ($sort == 'code') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_code'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_date_modified; ?>')"<?php if ($sort == 'date_modified') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_date_modified'); ?></a></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($currencies) { ?>
            <?php foreach ($currencies as $currency) { ?>
            <tr id="tr_<?php echo $currency['currency_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" <?php if ($currency['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><?php echo $currency['title']; ?></td>
                <td><?php echo $currency['code']; ?></td>
                <td><?php echo $currency['date_modified']; ?></td>
                <td>
                <?php foreach ($currency['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "activate") { 
                        $jsfunction = "activate(". $currency['currency_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $currency['currency_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "edit") {
                        $href = "href='" . $action['href'] ."'";
                        $jsfunction = "";
                    }
                ?>
                <a title="<?php echo $action['text']; ?>" <?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $currency['currency_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
                <?php } ?>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="6" style="text-align:center"><?php echo $Language->get('text_no_results'); ?></td></tr>
        <?php } ?>
        </tbody>
    </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>