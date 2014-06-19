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
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="formGrid">
    <table id="list" style="margin-bottom: 0px;">
        <thead>
            <tr>
                <th><input title="Seleccionar Todos" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                <th><?php echo $Language->get('column_name'); ?></th>
                <th><?php echo $Language->get('column_action'); ?></th>
            </tr>
        </thead>
    </table>
    <?php if ($categories) { ?>
        <?php echo $categories; ?>
    <?php } else { ?>
        <?php echo $Language->get('text_no_results'); ?>
    <?php } ?>
</form>