<?php echo $header; ?>
<div class="grid_12" style="padding: 50px 0px;">
    <h1>Error: No se pudieron cargar los productos</h1>
    <p>Es probable que a&uacute;n no haya guardado la categor&iacute;a o el identificador de la categor&iacute;a es incorrecto.</p>
    <p>&iquest;Desea guardar la Categor&iacute;a? <button onclick="javascript:parent.jQuery('#form').submit()">Guardar</button></p>
</div>
<script>
$(function(){
    $(document.createElement('link')).attr({
       href:'<?php echo HTTP_CSS; ?>catalog_category_addproducts.css',
       rel:'stylesheet',
       media:'screen' 
    }).appendTo('head');
});
</script>
<?php echo $footer; ?>