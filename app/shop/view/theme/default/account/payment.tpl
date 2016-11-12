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
                <h1 style="float: left !important;"><?php echo $heading_title; ?></h1>
                <a href="<?php echo $Url::createUrl("account/payment/register"); ?>" style="float: right !important;">Registrar Pago</a>

                <div class="clear"></div><br />

                <div class="sort">
                    <input type="text" name="filter_list" id="filter_list" value="" placeholder="Buscar por ID de Pago" />
                    <input type="text" name="filter_order" id="filter_order" value="" placeholder="Buscar por ID de Pedido" />
                    <select name="filter_status" id="filter_status">
                        <option value="">Todos</option>
                        <?php foreach ($statuses as $status) { ?>
                        <option value="<?php echo $status['order_payment_status_id']; ?>"><?php echo $status['name']; ?></option>
                        <?php } ?>
                    </select>
                    <select name="filter_limit" id="filter_limit">
                        <option value="5">5 por p&aacute;gina</option>
                        <option value="10">10 por p&aacute;gina</option>
                        <option value="20">20 por p&aacute;gina</option>
                        <option value="50">50 por p&aacute;gina</option>
                    </select>
                    <a onclick="filter()" id="filter" class="button" style="padding: 3px 4px;float:right !important">Filtrar</a>
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
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script>
function filter() {
     var url = '';
    
    if ($('#filter_order').val()){
        url += '&order_id=' + $('#filter_order').val();
    }
    
    if ($('#filter_list').val()){
        url += '&payment_id=' + $('#filter_list').val();
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
    
    window.location.href = '<?php echo $Url::createUrl("account/payment"); ?>' + url;
    
    return false;
}
$('#filter').on('click',function(e){
    filter();
    return false;
});
$('#filter_list').on('keydown',function(e) {
    if (e.keyCode == 13) {
        filter();
    }
});
</script>
<?php echo $footer; ?>