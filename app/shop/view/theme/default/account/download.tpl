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

                <?php if ($downloads) { ?>
                <div class="clear"></div><br />

                <div class="sort">
                    Buscar:
                    <input type="text" name="filter_name" id="filter_name" value="" placeholder="Buscar..." />
                    <select name="filter_limit" id="filter_limit">
                        <option value="5">5 por p&aacute;gina</option>
                        <option value="10">10 por p&aacute;gina</option>
                        <option value="20">20 por p&aacute;gina</option>
                        <option value="50">50 por p&aacute;gina</option>
                    </select>
                    <a href="#" id="filter" class="button" style="padding: 3px 4px;">Filtrar</a>
                </div> 

                <div class="clear"></div><br />

                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">

                    <table>
                        <thead>
                        <tr>
                            <th><input title="Seleccionar Todos" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" style="width: 5px !important;" /></th>
                            <th><?php echo $text_order; ?></th>
                            <th><?php echo $text_name; ?></th>
                            <th><?php echo $text_size; ?></th>
                            <th><?php echo $text_remaining; ?></th>
                            <th><?php echo $text_date_added; ?></th>
                            <th><?php echo $text_download; ?></th>
                        </tr>
                        </thead>
                            <?php foreach ($downloads as $value) { ?>
                        <tr id="pid_<?php echo $value['order_id']; ?>">
                            <td><input type="checkbox" name="selected[]" value="<?php echo $value['order_id']; ?>"<?php if ($value['selected']) { ?> checked="checked"<?php } ?> style="width: 5px !important;" /></td>
                            <td><b>#<?php echo $value['order_id']; ?></b></td>
                            <td><?php echo $value['name']; ?></td>
                            <td><?php echo $value['size']; ?></td>
                            <td><?php echo $value['remaining']; ?></td>
                            <td><?php echo $value['date_added']; ?></td>
                            <td>
                                            <a href="<?php echo str_replace('&', '&amp;', $value['href']); ?>" title="<?php echo $text_download; ?>" class="button"><?php echo $text_download; ?></a>
                            </td>
                        </tr>
                            <?php } ?>
                    </table>
                    <div class="clear"></div>
                    <?php if ($pagination) { ?><div class="pagination"><?php echo $pagination; ?></div><?php } ?>
                </form>
                <?php } else { ?>
                <div>No tiene ning&uacute;na descarga disponible</div>
                <?php } ?>
            </div>
        
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script>
function filterProducts() {
     var url = '';
    
    if ($('#filter_name').val()){
        url += '&keyword=' + $('#filter_name').val();
    }
    
    if ($('#filter_sort').val()){
        url += '&sort=' + $('#filter_sort').val();
    }
    
    if ($('#filter_limit').val()){
        url += '&limit=' + $('#filter_limit').val();
    }
    
    window.location.href = '<?php echo $Url::createUrl("account/download"); ?>' + url;
    
    return false;
}
$('#filter').on('click',function(e){
    filterProducts();
    return false;
});
$('#filter_name').on('keydown',function(e) {
    if (e.keyCode == 13) {
        filterProducts();
    }
});
</script>
<?php echo $footer; ?>