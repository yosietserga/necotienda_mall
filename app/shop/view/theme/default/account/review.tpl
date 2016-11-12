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

                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">

                    <?php if ($reviews) { ?>
                    <table class="account_sale">
                        <thead>
                        <tr>
                            <th><input title="Seleccionar Todos" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" style="width: 5px !important;" /></th>
                            <th>Comentario</th>
                            <th>Producto</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                            <?php foreach ($reviews as $value) { ?>
                        <tr id="pid_<?php echo $value['review_id']; ?>">
                            <td><input type="checkbox" name="selected[]" value="<?php echo $value['review_id']; ?>"<?php if ($value['selected']) { ?> checked="checked"<?php } ?> style="width: 5px !important;" /></td>
                            <td>
                                <a href="<?php echo $Url::createUrl("account/review/read",array("review_id"=>$value['review_id'])); ?>" title="Leer Comentario"><?php echo $value['text']; ?></a>
                            </td>
                            <td><a href="<?php echo $value['product_href']; ?>" title="Ver Producto"><?php echo $value['product']; ?></a></td>
                            <td><?php echo $value['date_added']; ?></td>
                            <td>
                                            <a href="#" onclick="deleteReview(this,'<?php echo $value['product_id']; ?>','<?php echo $value['review_id']; ?>')" title="Eliminar">Eliminar</a>
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
    
    window.location.href = '<?php echo $Url::createUrl("account/review"); ?>' + url;
    
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
<script>
function deleteReview(e,p,r) {
    if (confirm('<?php echo $Language->get('text_confirm_delete'); ?>')) {
        $('#pid_'+ r).remove();
        $('#review_'+ r).slideUp(function(){
            $(this).remove();
        });
        $.post('<?php echo $Url::createUrl("store/product/deleteReview"); ?>&product_id='+ p +'&review_id='+ r,
        {
            'product_id':p,
            'review_id':r
        });
    }
}
</script>
<?php echo $footer; ?>