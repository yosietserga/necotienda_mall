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
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_name; ?>')"<?php if ($sort == 'pd.name') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_product'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_author; ?>')"<?php if ($sort == 'r.author') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_author'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_rating; ?>')"<?php if ($sort == 'r.rating') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_rating'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_status; ?>')"<?php if ($sort == 'r.status') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_status'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_date_added; ?>')"<?php if ($sort == 'r.date_added') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $Language->get('column_date_added'); ?></a></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($reviews) { ?>
            <?php foreach ($reviews as $review) { ?>
            <tr id="tr_<?php echo $review['review_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" <?php if ($review['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><a href="<?php echo $review['product_url']; ?>" target="_blank"><?php echo $review['name']; ?></a></td>
                <td><?php echo $review['author']; ?></td>
                <td><?php echo $review['rating']; ?></td>
                <td><?php echo $review['status']; ?></td>
                <td><?php echo $review['date_added']; ?></td>
                <td>
                <?php foreach ($review['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "activate") { 
                        $jsfunction = "activate(". $review['review_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $review['review_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "edit") {
                        $href = "href='" . $action['href'] ."'";
                        $jsfunction = "";
                    }
                ?>
                <a title="<?php echo $action['text']; ?>" <?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $review['review_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
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