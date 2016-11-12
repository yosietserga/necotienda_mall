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

<?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
    <table id="list">
        <thead>
            <tr>
                <th><input title="Seleccionar Todos" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_name; ?>')"<?php if ($sort == 'name') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_name'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_subject; ?>')"<?php if ($sort == 'subject') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_subject'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_newsletter; ?>')"<?php if ($sort == 'newsletter') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_newsletter'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_contacts; ?>')"<?php if ($sort == 'contacts') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_contacts'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_links; ?>')"<?php if ($sort == 'links') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_links'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_repeat; ?>')"<?php if ($sort == 'repeat') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_repeat'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_date_start; ?>')"<?php if ($sort == 'date_start') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_date_start'); ?></a></th>
                <th><a onclick="$('#gridWrapper').load('<?php echo $sort_date_end; ?>')"<?php if ($sort == 'date_end') { ?> class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $Language->get('column_date_end'); ?></a></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($campaigns) { ?>
            <?php foreach ($campaigns as $campaign) { ?>
            <tr id="tr_<?php echo $campaign['campaign_id']; ?>">
                <td><input title="Seleccionar para una acci&oacute;n" type="checkbox" name="selected[]" value="<?php echo $campaign['campaign_id']; ?>" <?php if ($campaign['selected']) { ?>checked="checked"<?php } ?>/></td>
                <td><?php echo $campaign['name']; ?></td>
                <td><?php echo $campaign['subject']; ?></td>
                <td><a href="<?php echo $Url::createAdminUrl("marketing/newsletter/update",array('newsletter_id'=>$campaign['newsletter']['newsletter_id'])); ?>"><?php echo $campaign['newsletter']['name']; ?></a></td>
                <td><?php echo count($campaign['contacts']); ?></td>
                <td><?php echo count($campaign['links']); ?></td>
                <td><?php echo ($campaign['repeat']) ? $Language->get('text_'. $campaign['repeat']) : $Language->get('text_once'); ?></td>
                <td><?php echo $campaign['date_start']; ?></td>
                <td><?php echo $campaign['date_end']; ?></td>
                <td>
                <?php foreach ($campaign['action'] as $action) { ?>
                <?php 
                    if ($action['action'] == "activate") { 
                        $jsfunction = "activate(". $campaign['campaign_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "delete") {
                        $jsfunction = "eliminar(". $campaign['campaign_id'] .")";
                        $href = "";
                    } elseif ($action['action'] == "edit") {
                        $href = "href='" . $action['href'] ."'";
                        $jsfunction = "";
                    } elseif ($action['action'] == "duplicate") {
                        $jsfunction = "copy(". $campaign['campaign_id'] .")";
                        $href = "";
                    }
                ?>
                <a title="<?php echo $action['text']; ?>" <?php echo $href; ?> onclick="<?php echo $jsfunction; ?>"><img id="img_<?php echo $campaign['campaign_id']; ?>" src="image/<?php echo $action['img']; ?>" alt="<?php echo $action['text']; ?>" /></a>
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
<?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>