<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <aside id="column_left"><?php echo $column_left; ?></aside>
        <div class="grid_13">
            <h1><?php echo $heading_title; ?></h1>
            
            <div class="buttons">
            	<a href="<?php echo $Url::createUrl("sale/create"); ?>" title="<?php echo $Language->get('text_add_product'); ?>" class="button blue"><?php echo $Language->get('text_sale'); ?></a>
            </div>
            <div class="clear"></div><br />
    
            <div class="sort">
                <form action="<?php echo $Url::createUrl('account/sale'); ?>" id="filterForm">
                    <input type="text" name="keyword" id="filter_customer_product" value="" placeholder="Buscar..." />
                    <select name="status" id="filter_status">
                        <option value=""><?php echo $Language->get('text_all'); ?></option>
                        <option value="1"><?php echo $Language->get('text_actived'); ?></option>
                        <option value="2"><?php echo $Language->get('text_desactived'); ?></option>
                        <option value="-1"><?php echo $Language->get('text_finished'); ?></option>
                    </select>
                    <select name="limit" id="filter_limit">
                        <option value="5">5 <?php echo $Language->get('text_per_page'); ?></option>
                        <option value="10">10 <?php echo $Language->get('text_per_page'); ?></option>
                        <option value="20">20 <?php echo $Language->get('text_per_page'); ?></option>
                        <option value="50">50 <?php echo $Language->get('text_per_page'); ?></option>
                    </select>
                    <a href="#" id="filter" class="button" style="padding: 3px 4px;"><?php echo $Language->get('text_filter'); ?></a>
                </form>
            </div> 
            
            <div class="clear"></div><br />
            <a class="button" style="padding: 7px;" href="<?php echo $Url::createUrl("account/sale"); ?>"><?php echo $Language->get('text_all'); ?></a>
        <?php foreach ($letters as $letter) { ?>
            <a class="button" style="padding: 7px;" href="<?php echo $Url::createUrl("account/sale"); ?>?letter=<?php echo $letter; ?>"><?php echo $letter; ?></a>
        <?php } ?>
        <div class="clear"></div><br />
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="formSale">
        
            <?php if ($products) { ?>
            <table class="account_sale">
                <thead>
                <tr>
                    <th><input title="<?php echo $Language->get('text_select_all'); ?>" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" style="width: 5px !important;" /></th>
                    <th><?php echo $Language->get('text_image'); ?></th>
                    <th><?php echo $Language->get('text_product_name'); ?></th>
                    <th><?php echo $Language->get('text_status'); ?></th>
                    <th><?php echo $Language->get('text_price'); ?></th>
                    <th><?php echo $Language->get('text_plan'); ?></th>
                    <th><?php echo $Language->get('text_stats'); ?></th>
                    <th><?php echo $Language->get('text_finish'); ?></th>
                    <th><?php echo $Language->get('text_actions'); ?></th>
                </tr>
                </thead>
        		<?php foreach ($products as $product) { ?>
                <tr id="pid_<?php echo $product['product_id']; ?>">
                    <td><input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>"<?php if ($product['selected']) { ?> checked="checked"<?php } ?> style="width: 5px !important;" /></td>
                    <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></td>
                    <td>
                        <a href="<?php echo $product['href']; ?>" title="<?php echo $Language->get('text_go_to_product'); ?>"><?php echo $product['name']; ?></a><br />
                        <span class="product_id"><?php echo $Language->get('text_product_id'); ?> #<?php echo $product['product_id']; ?></span>&nbsp;|&nbsp;<?php echo $Language->get('text_model'); ?>:&nbsp;<span class="model"><?php echo $product['model']; ?></span>
                    </td>
                    <td id="status<?php echo $product['product_id']; ?>"><span class="tag <?php echo $product['status_class']; ?>"><?php echo $product['status']; ?></span></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['plan']; ?></td>
                    <td><?php echo $product['viewed']; ?> <?php echo $Language->get('text_visits'); ?><br /><?php echo $product['contacts']; ?> <?php echo $Language->get('text_calls'); ?></td>
                    <td><?php echo $Language->get('text_remaining'); ?> <?php echo $product['remaining']; ?> <?php echo $Language->get('text_days'); ?></td>
                    <td>
        				<a href="<?php echo $Url::createUrl("sale/edit",array("product_id"=>$product['product_id'])); ?>" title="<?php echo $Language->get('text_edit'); ?>"><?php echo $Language->get('text_edit'); ?></a>
                        &nbsp;|&nbsp;
                        <?php if ($product['status']!='Finalizado') { ?>
        				<a href="#" onclick="$.getJSON('<?php echo $Url::createUrl("account/sale/finish",array("product_id"=>$product['product_id'])); ?>',function(){ $('#status<?php echo $product['product_id']; ?> span').text('<?php echo $Language->get('text_finished'); ?>').addClass('orange') });return false;" title="<?php echo $Language->get('text_to_finish'); ?>"><?php echo $Language->get('text_to_finish'); ?></a>
                        &nbsp;|&nbsp;
                        <?php } ?>
        				<a href="#" onclick="if (confirm('<?php echo $Language->get('help_delete'); ?>')) { $.getJSON('<?php echo $Url::createUrl("account/sale/delete",array("product_id"=>$product['product_id'])); ?>',function(){ $('#pid_<?php echo $product['product_id']; ?>').remove(); }); } return false;" title="<?php echo $Language->get('text_delete'); ?>"><?php echo $Language->get('text_delete'); ?></a>
                    </td>
                </tr>
        		<?php } ?>
            </table>
            <div class="clear"></div>
            <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
            <?php } else { ?>
            <div><?php echo $Language->get('text_not_found'); ?>, <a href="<?php echo $Url::createUrl("sale/create"); ?>" title="<?php echo $Language->get('text_add_product'); ?>"><?php echo $Language->get('text_want_to_add_a_product'); ?></a></div>
            <?php } ?>
        </form>
        </div>
    </section>
</section>
<script>
function filterProducts() {
     var url = '';
    
    if ($('#filter_customer_product').val()){
        url += '?keyword=' + $('#filter_customer_product').val();
    }
    
    if ($('#filter_sort').val() && url.length == 0) {
        url += '?sort=' + $('#filter_sort').val();
    } else if ($('#filter_sort').val()) {
        url += '&sort=' + $('#filter_sort').val();
    }
    
    if ($('#filter_status').val() && url.length == 0) {
        url += '?status=' + $('#filter_status').val();
    } else if ($('#filter_status').val()) {
        url += '&status=' + $('#filter_status').val();
    }
    
    if ($('#filter_limit').val() && url.length == 0) {
        url += '?limit=' + $('#filter_limit').val();
    } else if ($('#filter_limit').val()) {
        url += '&limit=' + $('#filter_limit').val();
    }
    
    /* $('#targetWrapper').load('<?php echo $Url::createUrl("account/sale"); ?>' + url); */
    window.location.href = '<?php echo $Url::createUrl("account/sale"); ?>' + url;
    
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