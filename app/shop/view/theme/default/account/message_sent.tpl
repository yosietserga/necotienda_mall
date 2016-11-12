<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    <section id="maincontent">
        <section id="content">
            <div class="grid_12">
                <div id="featuredContent">
                <ul class="widgets"><?php if($featuredWidgets) { foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } } ?></ul>
                </div>
            </div>

            <div class="clear"></div>

            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>
        
                <h1><?php echo $heading_title; ?></h1>

                <?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
                <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>

                <div class="clear"></div><br />

                <div class="sort">
                    Buscar:
                    <input type="text" name="filter_subject" id="filter_subject" value="" placeholder="Buscar..." />
                    Status:
                    <select name="filter_status" id="filter_status">
                        <option value="">Todos</option>
                        <option value="1"><?php echo $text_read; ?></option>
                        <option value="2"><?php echo $text_non_read; ?></option>
                        <option value="-1"><?php echo $text_spam; ?></option>
                    </select>
                    Mostrar:
                    <select name="filter_limit" id="filter_limit">
                        <option value="5">5 por p&aacute;gina</option>
                        <option value="10">10 por p&aacute;gina</option>
                        <option value="20">20 por p&aacute;gina</option>
                        <option value="50">50 por p&aacute;gina</option>
                    </select>
                    <?php echo $text_sort; ?>
                    <a href="#" id="filter" class="buttonBlue" style="padding: 3px 4px;">Filtrar</a>
                </div> 

                <div class="clear"></div><br />

                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">

                    <?php if ($messages) { ?>
                    <table class="account_sale">
                        <thead>
                        <tr>
                            <th><input title="Seleccionar Todos" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" style="width: 5px !important;" /></th>
                            <th>Asunto</th>
                            <th>Mensaje</th>
                            <th>Enviado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                            <?php foreach ($messages as $value) { ?>
                        <tr id="pid_<?php echo $value['message_id']; ?>">
                            <td><input type="checkbox" name="selected[]" value="<?php echo $value['message_id']; ?>"<?php if ($value['selected']) { ?> checked="checked"<?php } ?> style="width: 5px !important;" /></td>
                            <td>
                                <a href="<?php echo $Url::createUrl("account/message/read",array("message_id"=>$value['message_id'])); ?>" title="Leer Mensaje"><?php echo $value['subject']; ?></a>
                            </td>
                            <td><?php echo substr($value['message'],0,150) . "..."; ?></td>
                            <td><?php echo $value['date_added']; ?></td>
                            <td>
                                            <a href="#" onclick="if (confirm('Seguro que desea eliminarlo?')) { $.getJSON('<?php echo $Url::createUrl("account/message/delete",array("id"=>$value['message_id'])); ?>',function(){ $('#pid_<?php echo $value['message_id']; ?>').remove(); }); } return false;" title="Finalizar">Eliminar</a>
                            </td>
                        </tr>
                            <?php } ?>
                    </table>
                    <div class="clear"></div>
                    <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
                    <?php } else { ?>
                    <div>No tiene nin&uacute;n mensaje</div>
                    <?php } ?>
                </form>
            </div>
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script>
function filterProducts() {
     var url = '';
    
    if ($('#filter_subject').val()){
        url += '&keyword=' + $('#filter_subject').val();
    }
    
    if ($('#filter_sort').val()){
        url += '&sort=' + $('#filter_sort').val();
    }
    
    if ($('#filter_status').val()){
        url += '&status=' + $('#filter_status').val();
    }
    
    if ($('#filter_limit').val()){
        url += '&limit=' + $('#filter_limit').val();
    }
    
    window.location.href = '<?php echo $Url::createUrl("account/message/sent"); ?>' + url;
    
    return false;
}
$('#filter').on('click',function(e){
    filterProducts();
    return false;
});
$('#filter_customer_product').on('keydown',function(e) {
    if (e.keyCode == 13) {
        filterProducts();
    }
});
</script>
<?php echo $footer; ?>