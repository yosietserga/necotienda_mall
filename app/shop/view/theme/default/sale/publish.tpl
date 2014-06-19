<?php echo $header; ?>
<aside id="featured"></aside>
<section id="maincontent">
    <div class="container_24">
        <section>
        
            <div class="grid_24">
            
                <h1>Crear Lista de Necesidades</h1>
                
                <div>
                    <input type="hidden" name="list_id" id="list_id" value="" />
                    <input name="list_name" id="listName" value="" placeholder="Nombre de la Lista" style="width: 300px;" />
                    <div class="clear"></div>
                    <textarea name="list_description" id="listDescription" placeholder="Descripci&oacute;n de la Lista" style="width: 300px;"></textarea>
                </div>
                
                <div class="clear"></div>
                <h2>Agregar Art&iacute;culo</h2>
                <div class="createItem">
                    <div class="createItemWrapper">
                        <div class="imageWrapper"><input type="file" name="image" class="image" /></div>
                            
                        <div class="grid_6">
                            <input type="text" name="name" value="" placeholder="Nombre del art&iacute;lo" />
                            <input type="text" name="model" value="" placeholder="Modelo del art&iacute;lo" />
                            <input type="text" name="qty" value="" placeholder="&iquest;Cu&aacute;ntos necesitas?" />
                        </div>
                            
                        <div id="categoriesWrapper" class="grid_7">
                            <select id="category_0" multiple="true" style="300px"></select>
                            <input type="hidden" id="category0" name="Categories[]" value="" />
                            <div class="clear"></div>
                        </div>
                            
                        <div class="grid_7 plansWrapper">
                            <div class="plan_selected">Selecciona un plan</div>
                            <input type="hidden" id="plan_id" value="" />
                            <ul class="plans">
                            <?php foreach ($plans as $plan) { ?>
                                <li onclick="$('.plan_selected').html('<?php echo $plan['name']; ?>');$('#plan_id').val('<?php echo $plan['plan_id']; ?>');">
                                    <img src="<?php echo $plan['image']; ?>" alt="<?php echo $plan['name']; ?>" />
                                    <div>
                                        <b><?php echo $plan['name']; ?></b>
                                        <span class="planDays"><?php echo $plan['qty_days']; ?> D&iacute;as</span>
                                        <span>Recomendado: <?php echo ($plan['featured']) ? "Si" : "No"; ?></span>
                                        <span>Portada: <?php echo ($plan['show_in_home']) ? "Si" : "No"; ?></span>
                                        <span class="planPrice"><?php echo $plan['price']; ?></span>
                                    </div>
                                </li>
                            <?php } ?>
                            </ul>
                            
                        </div>
                        
                        <div class="clear"></div>
                        <a class="buttonBlue" onclick="addItem()" style="float: right;margin-top:-10px;">Agregar a la Lista</a>
                            
                    </div>
                </div>
                    
                <div class="clear"></div>
              
                <div id="itemsWrapper">
                    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="formSale">
                    <div class="grid_24 product">
                        <input type="hidden" name="Items[0][image]" value="blanco.png">
                        <input type="hidden" name="Items[0][model]" value="dsfds">
                        <input type="hidden" name="Items[0][qty]" value="12">
                        <input type="hidden" name="Items[0][plan_id]" value="1">
                        <div class="grid_2">
                            <img width="100" height="100" alt="sfdsf" src="assets/images/no_image.jpg" />
                        </div>
                        <div class="grid_17">
                            <a class="button red itemClose">Cerrar</a>
                            <b class="itemName">sfdsf</b>
                            <div class="itemModel">dsfds</div>
                            <div class="itemPlan">Gratis</div>
                            <div class="itemCategories">
                                <span>Accesorios</span>
                                <span>Cuadros y Pinturas</span>
                                <span>Esculturas</span>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <a class="button" onclick="$('#attributesWrapper0').slideToggle();return false;" href="#">text_attributes</a>
                        <a class="button" onclick="$('#descriptionWrapper0').slideToggle();return false;" href="#">text_description</a>
                        <div class="clear"></div>
                        <div class="itemAtrributes" id="attributesWrapper0" style="display:none;">text_no_attributes</div>
                        <div class="clear"></div>
                        <div class="itemDescription" id="descriptionWrapper0" style="display:none;"></div>

                </div>
                    </form>
            
            </div>
        </section>
    </div>
</section>
<script>
$(function(){
    $('input.image').on('change',function(e){
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
	        reader.onload = function (e) {
                $(input).closest('.imageWrapper').prepend('<img src=\"'+ e.target.result +'\" width=\"100\" height=\"100\" />');		
            };
            reader.readAsDataURL(input.files[0]);
         }
    });
    $('#listName, #listDescription').on('change',function(e){
        var list_id = $('#list_id').val();
        if (!list_id && $('#listName').val().length > 0) {
            $.post('<?php echo $Url::createUrl("sale/publish/savelist"); ?>',
            {
                'name':$('#listName').val(),
                'description':$('#listDescription').val()
            },
            function(response){
                data = $.parseJSON(response);
                if (typeof data.success != 'undefined') {
                    $('#list_id').val(data.list_id);
                } else {
                    $('#list_id').val('');
                }
            });
        } else {
            $.post('<?php echo $Url::createUrl("sale/publish/savelist"); ?>',
            {
                'list_id':list_id,
                'name':$('#listName').val(),
                'description':$('#listDescription').val()
            });
        }
    });
});
function addItem() {
    $.psot('<?php echo $Url::createUrl("sale/publish/add"); ?>',
    {
        data: $().serialize()
    },
    function(response){
        data = $.parseJSON(response);
    });
    
    var index   = ($('.product:last-child').index() + 1); 
    var image   = $('.createItemWrapper input.image').val();
    var preview = $('.imageWrapper img').attr('src');
    var name    = $('.createItemWrapper input[name=name]').val();
    var model   = $('.createItemWrapper input[name=model]').val();
    var qty     = $('.createItemWrapper input[name=qty]').val();
    var plan_id = $('#plan_id').val();
    var plan    = $('.plan_selected').text();
    
    var div = $(document.createElement('div')).addClass('grid_24 product');

    var categories = [],
    categoriesHtml = '<span>'+ $('#category0').text() +'</span>';
    categories.push($('#category0').val());
    $('.Categories').each(function(){
        categories.push($(this).val());
        categoriesHtml += '<span>'+ $(this).text() +'</span>';
    });
    
    var inputImage = $(document.createElement('input')).attr({
        name:'Items['+ index +'][image]',
        type:'hidden',
        value:image
    }).appendTo(div);

    var inputModel = $(document.createElement('input')).attr({
        name:'Items['+ index +'][model]',
        type:'hidden',
        value:model
    }).appendTo(div);

    var inputQty = $(document.createElement('input')).attr({
        name:'Items['+ index +'][qty]',
        type:'hidden',
        value:qty
    }).appendTo(div);

    var inputPlanId = $(document.createElement('input')).attr({
        name:'Items['+ index +'][plan_id]',
        type:'hidden',
        value:plan_id
    }).appendTo(div);
    
    var tpl = '';
    tpl += '<img src="'+ preview +'" alt="'+ name +'" width="100" height="100" />';
    tpl += '<b class="itemName">'+ name +'</b>';
    tpl += '<div class="itemModel">'+ model +'</div>';
    tpl += '<div class="itemPlan">'+ plan +'</div>';
    tpl += '<div class="itemCategories">'+ categoriesHtml +'</div>';
    
    tpl += '<a href="#" class="button" onclick="$(\'#attributesWrapper'+ index +'\').slideToggle();return false;"><?php echo $Language->get('text_advanced'); ?></a>';
    tpl += '<div id="attributesWrapper'+ index +'" style="display:none;"><?php echo $Language->get('text_no_attributes'); ?></div>';
    
    tpl += '<a href="#" class="button" onclick="$(\'#imagesWrapper'+ index +'\').slideToggle();return false;"><?php echo $Language->get('text_images'); ?></a>';
    tpl += '<div id="imagesWrapper'+ index +'" style="display:none;"><?php echo $Language->get('text_no_images'); ?></div>';
    
    tpl += '<a href="#" class="button" onclick="$(\'#descriptionWrapper'+ index +'\').slideToggle();return false;"><?php echo $Language->get('text_description'); ?></a>';
    tpl += '<div id="descriptionWrapper'+ index +'" style="display:none;"><textarea name="description'+ index +'" id="description'+ index +'"></textarea></div>';
    
    $(div).append(tpl);
    
    $('#formSale').append(div);
    $(div).after('<div class="clear"></div>');
    
    CKEDITOR.replace('description'+ index);
    
    var list_id = $('#list_id').val();
    if (list_id) {
        $.post('<?php echo $Url::createUrl("sale/publish/saveproduct"); ?>',
        {
            'list_id':list_id,
            'name'   :name,
            'description':$('#description'+ index).val(),
            'model'  :model,
            'qty'    :qty,
            'Categories':categories,
            'plan_id':plan_id
        },
        function(response){
            data = $.parseJSON(response);
            if (typeof data.success != 'undefined') {
                
                if (typeof data.attributes != 'undefined') {
                    var t = '';
                    $.each(data.attributes, function(i, item) {
                        t += '<div class="row">';
                        t += '<label for="'+ item.id + index +'"></label>';
                        t += '<input name="'+ item.name +'" id="'+ item.id + index +'" value="'+ item.value +'" class="Attribute" />';
                        t += '<div class="clear"></div>';
                        t += '</div>';
                    });
                    $('#attributesWrapper'+ index).append(t);
                }
                
                if (typeof data.plan != 'undefined') {
                    var t = '';
                    i=1;
                    for(i; i < data.plan.qtyImages; i++) {
                        t += '<li>';
                        t += '<input class="image" type="file" name="files[]" id="image'+ i +'_" value="" showquick="off" accept="image/gif, image/jpeg, image/png" />';
                        t += '<div id="preview'+ i +'" class="uploadPreview"></div>';
                        t += '<div class="clear">&nbsp;</div>';
                        t += '</li>';
                    }
                    $('#imagesWrapper'+ index).append(t);
                }
            }
        });
    }
    $(div).find('input, textarea, select').each(function(){
        $.post('<?php echo $Url::createUrl("sale/publish/saveproduct"); ?>',
        {
            'list_id':list_id,
            'name'   :$(inputName).val(),
            'description':$('#description'+ index).val(),
            'attributes':$('.Attribute').serializeArray(),
            'model'  :$(inputModel).val(),
            'qty'    :$(inputQty).val()
        });
    });
}
</script>
<?php echo $footer; ?>
