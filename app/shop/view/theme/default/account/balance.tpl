<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">

    <section id="content">
    
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
        <h1><?php echo $heading_title; ?></h1>

        <div class="clear"></div><br />
    
        <div class="sort">
            <input type="text" name="filter_order" id="filter_order" value="" placeholder="Buscar Pago ID..." />
            <select name="filter_status" id="filter_status">
                <option value="">Todos</option>
                <option value="0">Pendientes</option>
                <option value="1">Confirmados</option>
                <option value="-1">No confirmados</option>
            </select>
            <select name="filter_limit" id="filter_limit">
                <option value="5">5 por p&aacute;gina</option>
                <option value="10">10 por p&aacute;gina</option>
                <option value="20">20 por p&aacute;gina</option>
                <option value="50">50 por p&aacute;gina</option>
            </select>
            <?php echo $text_sort; ?>
            <a href="#" id="filter" class="button" style="padding: 3px 4px;">Filtrar</a>
        </div> 
        
        <div class="clear"></div><br />
    
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
        
            <?php if ($payments) { ?>
            <table>
                <thead>
                <tr>
                    <th>ID del Pago</th>
                    <th>ID del Pedido</th>
                    <th><?php echo $text_status; ?></th>
                    <th><?php echo $text_date_added; ?></th>
                    <th><?php echo $text_total; ?></th>
                    <th>Acciones</th>
                </tr>
                </thead>
        		<?php foreach ($payments as $value) { ?>
                <tr id="pid_<?php echo $value['order_payment_id']; ?>">
                    <td><b>#<?php echo $value['order_payment_id']; ?></b></td>
                    <td><a href="<?php echo $Url::createUrl("account/invoice",array('order_id'=>$value['order_id'])); ?>"><?php echo $value['order_id']; ?></a></td>
                    <td><?php echo $value['status']; ?></td>
                    <td><?php echo $value['date_added']; ?></td>
                    <td><b><?php echo $value['amount']; ?></b></td>
                    <td><a href="<?php echo $Url::createUrl("account/payment/receipt",array('payment_id'=>$value['order_payment_id'])); ?>" class="button">Ver Recibo</a></td>
                </tr>
        		<?php } ?>
            </table>
            <div class="clear"></div>
            <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
            <?php } else { ?>
            <div>No tiene ning&uacute;n pago registrado</div>
            <?php } ?>
        </form>
        </div>
        
    </section>
    
</section>
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
    
    window.location.href = '<?php echo $Url::createUrl("account/order"); ?>' + url;
    
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