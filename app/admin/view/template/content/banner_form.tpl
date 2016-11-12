<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    
    <?php if ($breadcrumbs) { ?>
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php } ?>
    
    <?php if ($success) { ?><div class="grid_12"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
    <?php if ($msg || $error_warning) { ?><div class="grid_12"><div class="message warning"><?php echo ($msg) ? $msg : $error_warning; ?></div></div><?php } ?>
    <?php if ($error) { ?><div class="grid_12"><div class="message error"><?php echo $error; ?></div></div><?php } ?>
    
    <div class="grid_12" id="msg"></div>

    <div class="box">
        <h1><?php echo $Language->get('heading_title'); ?></h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
            <a href="../../../controller/content/banner.php"></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
            <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

                <div class="row">
                    <label><?php echo $Language->get('entry_name'); ?></label>
                    <input type="text" name="name" value="<?php echo $name; ?>" required="true" style="width:40%" />
                    <?php if ($error_name) { ?><span class="error"><?php echo $error_name; ?></span><?php } ?>
                </div>

                <div class="clear"></div>

                <div class="row">
                    <label><?php echo $Language->get('entry_date_start'); ?></label>
                    <input type="necoDate" name="publish_date_start" value="<?php echo !empty($publish_date_start) ? date('d-m-Y',strtotime($publish_date_start)) : date('d/m/Y'); ?>" style="width:40%" />
                </div>

                <div class="clear"></div>

                <div class="row">
                    <label><?php echo $Language->get('entry_date_end'); ?></label>
                    <input type="necoDate" name="publish_date_end" value="<?php echo isset($publish_date_end) ? $publish_date_end : ''; ?>" style="width:40%" />
                </div>

                <div class="clear"></div><br />

                <div class="row">
                    <label><?php echo $Language->get('entry_engine'); ?></label>
                    <select name="jquery_plugin" style="width:40%">
                        <option value="0"><?php echo $Language->get('text_none'); ?></option>
                        <?php foreach ($sliders as $slider) { ?>
                        <option value="<?php echo $slider; ?>"<?php if ($jquery_plugin == $slider) {?> selected="selected"<?php } ?>><?php echo $slider; ?></option>
                        <?php } ?>
                   </select>
                </div>

                <div class="clear"></div>

                <?php if ($stores) { ?>
                <div class="clear"></div>
                <div class="row">
                    <label><?php echo $Language->get('entry_store'); ?></label><br />
                    <input type="text" title="Filtrar listado de tiendas y sucursales" value="" name="q" id="q" placeholder="Filtrar Tiendas" />
                    <div class="clear"></div>
                    <a onclick="$('#storesWrapper input[type=checkbox]').attr('checked','checked');">Seleccionar Todos</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a onclick="$('#storesWrapper input[type=checkbox]').removeAttr('checked');">Seleccionar Ninguno</a>
                    <div class="clear"></div>
                    <ul id="storesWrapper" class="scrollbox">
                        <li class="stores">
                            <input type="checkbox" name="stores[]" value="0"<?php if (in_array(0, $_stores)) { ?> checked="checked"<?php } ?> showquick="off" />
                            <b><?php echo $Language->get('text_default'); ?></b>
                            <div class="clear"></div>
                        </li>
                    <?php foreach ($stores as $store) { ?>
                        <li class="stores">
                            <input type="checkbox" name="stores[]" value="<?php echo (int)$store['store_id']; ?>"<?php if (in_array($store['store_id'], $banner_stores)) { ?> checked="checked"<?php } ?> showquick="off" />
                            <b><?php echo $store['name']; ?></b>
                            <div class="clear"></div>
                        </li>
                    <?php } ?>
                    </ul>
                </div> 
                <?php } else { ?>
                    <input type="hidden" name="stores[]" value="0" />
                <?php } ?>

                <div>
                    <a onclick="addItem();" class="button"><?php echo $Language->get('button_add_item'); ?></a>
                    <table id="items" class="list">
                        <thead>
                            <tr>
                                <th style="width: 110px;"><?php echo $Language->get('entry_image'); ?></th>
                                <th><?php echo $Language->get('entry_link'); ?></th>
                                <th><?php echo $Language->get('entry_description'); ?></th>
                                <th><?php echo $Language->get('entry_sort'); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($banner_items as $key => $banner_item) { ?>
                            <tr id="row<?php echo $key; ?>" class="row">
                                <td style="width: 110px;text-align: left;">
                                    <input type="hidden" name="items[<?php echo $key; ?>][image]" value="<?php echo $banner_item['image']; ?>" id="image<?php echo $key; ?>" />
                                    <img src="<?php echo ($banner_item['image'] && file_exists(DIR_IMAGE . $banner_item['image'])) ? $NTImage::resizeAndSave($banner_item['image'], 100, 100) : $NTImage::resizeAndSave('no_image.jpg', 100, 100); ?>" id="preview<?php echo $key; ?>" class="image" onclick="image_upload('image<?php echo $key; ?>', 'preview<?php echo $key; ?>');" />
                                    <br />
                                    <a onclick="image_upload('image<?php echo $key; ?>', 'preview<?php echo $key; ?>');" style="margin-left: 220px;color:#FFA500;font-size:10px">[ Cambiar ]</a>
                                    <a onclick="image_delete('image<?php echo $key; ?>', 'preview<?php echo $key; ?>');" style="color:#FFA500;font-size:10px">[ Quitar ]</a>
                                </td>
                                <td><input type="text" name="items[<?php echo $key; ?>][link]" value="<?php echo $banner_item['link']; ?>" placeholder="<?php echo $Language->get('entry_link'); ?>" showquick="off" /></td>
                                <td>
                                    <div class="htabs">
                                    <?php foreach ($languages as $language) { ?>
                                        <a onclick="showTab(this,'language_<?php echo $key; ?>_<?php echo $language['code']; ?>')" class="htab"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
                                    <?php } ?>
                                    </div>

                                    <div class="clear"></div>

                                    <?php foreach ($languages as $language) { ?>
                                    <div id="language_<?php echo $key; ?>_<?php echo $language['code']; ?>" class="tab">
                                         <input type="text" name="items[<?php echo $key; ?>][descriptions][<?php echo $language['language_id']; ?>][title]" value="<?php echo $banner_item['descriptions'][$language['language_id']]['title']; ?>" required="true" placeholder="<?php echo $Language->get('entry_title') ." ". $language['name']; ?>" showquick="off" />

                                        <div class="clear"></div><br />

                                         <textarea name="items[<?php echo $key; ?>][descriptions][<?php echo $language['language_id']; ?>][description]" cols="90" placeholder="<?php echo $Language->get('entry_description') ." ". $language['name']; ?>" showquick="off"><?php echo $banner_item['descriptions'][$language['language_id']]['description']; ?></textarea>

                                    </div>
                                    <?php } ?>
                                </td>
                                <td class="move">
                                    <img src="image/move.png" alt="Ordenar" title="Ordenar" style="text-align:center" />
                                    <input type="hidden" name="items[<?php echo $key; ?>][sort_order]" class="sortOrder" value="<?php echo $key; ?>" />
                                </td>
                                <td><a onclick="$('#row<?php echo $key; ?>').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a onclick="addItem();" class="button"><?php echo $Language->get('button_add_item'); ?></a>
                </div>
            </form>
    </div>
    
    <div id="products" style="display: none;">
        <table>
            <tbody>
            <?php foreach($products as $product) { ?>
                <tr id="product_<?php echo $product['product_id']; ?>" onclick="setLink('<?php echo $product['href']; ?>')">
                    <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></td>
                    <td><?php echo $product['name']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div id="categories" style="display: none;">
        <table>
            <tbody>
            <?php foreach($categories as $category) { ?>
                <tr id="category_<?php echo $category['category_id']; ?>" onclick="setLink('<?php echo $category['href']; ?>')">
                    <td><?php echo $category['name']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="sidebar" id="feedbackPanel">
    <div class="tab"></div>
    <div class="content">
        <h2>Sugerencias</h2>
        <p style="margin: -10px auto 0px auto;">Tu opini&oacute;n es muy importante, dinos que quieres cambiar.</p>
        <form id="feedbackForm">
            <textarea name="feedback" id="feedback" cols="60" rows="10"></textarea>
            <input type="hidden" name="account_id" id="account_id" value="<?php echo C_CODE; ?>" />
            <input type="hidden" name="domain" id="domain" value="<?php echo HTTP_DOMAIN; ?>" />
            <input type="hidden" name="server_ip" id="server_ip" value="<?php echo $_SERVER['SERVER_ADDR']; ?>" />
            <input type="hidden" name="remote_ip" id="remote_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
            <div class="clear"></div>
            <br />
            <div class="buttons"><a class="button" onclick="sendFeedback()">Enviar Sugerencia</a></div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $('.tab:first-child').show();
    $('#items tbody').sortable({
        opacity: 0.6, 
        cursor: 'move',
        handle: '.move',
        update: function() {
            $('#items tbody tr').each(function(){
                $(this).find('.sortOrder').val($(this).index());
            });
        }
    });
    $('.move').css('cursor','move');
});
function addItem() {
    _row = ($('#items tbody tr:last-child').index() + 1);
	html = '<tr id="row'+ _row +'" class="row">';
	html += '<td style="width: 110px;text-align: left;">';
	html += '<input type="hidden" name="items['+ _row +'][image]" value="" id="image'+ _row +'" />';
	html += '<img src="<?php echo HTTP_IMAGE; ?>cache/no_image-100x100.jpg" id="preview'+ _row +'" class="image" onclick="image_upload(\'image'+ _row +'\', \'preview'+ _row +'\');" />';
	html += '<div class="clear"></div>';
	html += '<a onclick="image_upload(\'image'+ _row +'\', \'preview'+ _row +'\');" style="margin-left: 220px;color:#FFA500;font-size:10px">[ Cambiar ]</a>';
	html += '<a onclick="image_delete(\'image'+ _row +'\', \'preview'+ _row +'\');" style="color:#FFA500;font-size:10px">[ Quitar ]</a>';
	html += '</td>';
    html += '<td><input type="text" name="items['+ _row +'][link]" value="" placeholder="<?php echo $Language->get('entry_link'); ?>" /></td>';
	html += '<td>';
	html += '<div class="htabs">';
    
    <?php foreach ($languages as $language) { ?>
	html += '<a onclick="showTab(this,\'language_'+ _row +'_<?php echo $language['code']; ?>\')" class="htab"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>';
    <?php } ?>
    
	html += '</div>';
    
    <?php foreach ($languages as $language) { ?>
	html += '<div id="language_'+ _row +'_<?php echo $language['code']; ?>">';
	html += '<input type="text" name="items['+ _row +'][descriptions][<?php echo $language['language_id']; ?>][title]" value="" required="true" placeholder="<?php echo $Language->get('entry_title') ." ". $language['name']; ?>" />';
	html += '<div class="clear"></div><br />';
	html += '<textarea name="items['+ _row +'][descriptions][<?php echo $language['language_id']; ?>][description]" style="width:90%" placeholder="<?php echo $Language->get('entry_description') ." ". $language['name']; ?>"></textarea>';
	html += '</div>';
    <?php } ?>
    
	html += '</td>';
	html += '<td class="move"><input type="hidden" name="items['+ _row +'][sort_order]" class="sortOrder" value="'+ _row +'" /><img src="image/move.png" alt="Ordenar" title="Ordenar" style="text-align:center" /></td>';
	html += '<td><a onclick="$(\'#row'+ _row +'\').remove();" class="button"><?php echo $Language->get('button_remove'); ?></a></td>';
	html += '</tr>';
    
	$('#items tbody').append(html);
}
function showTab(e,id) {
    $(e).closest('td').find('.tab').each(function(){
        $(this).hide();
    });
    $('#'+ id).show();
}
</script>
<?php echo $footer; ?>