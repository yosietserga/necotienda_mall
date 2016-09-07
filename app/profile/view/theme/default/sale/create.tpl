<?php echo $header; ?>
<aside id="featured"></aside>
<section id="maincontent">
    <div class="container_24">
        <section>
            <?php if ($error) { ?>
            <div class="message error">
                <h2>Error</h2>
                <p>Hubo ciertos errores en el proceso, por favor revise y corrija los errores.</p>
                <ul>
                    <?php foreach ($error as $msg) { ?><li><?php echo $msg; ?></li><?php } ?>
                </ul>
            </div>
            <?php } ?>
            
            <div class="grid_23">
            
                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="formSale">
                  <div id="step1" style="position: relative;z-index:1;">
                        <h1>Selecciona Un Plan</h1>
                        <?php $width = (100 / (count($plans)) ); ?>
                        <?php foreach ($plans as $plan) { ?>
                        <div class="plan" style="width:<?php echo $width; ?>%;">
                            <img src="<?php echo $plan['image']; ?>" alt="<?php echo $plan['name']; ?>" />
                            <ul>
                                <li><?php echo $plan['name']; ?></li>
                                <li><?php echo $plan['qty_days'] ." ". $Language->get('text_days'); ?></li>
                                <li><?php echo $plan['qty_images'] ." ". $Language->get('text_images'); ?></li>
                                <!-- <li><?php echo $plan['qty_videos'] ." ". $Language->get('text_videos'); ?></li> -->
                                <li>Recomendado: <?php echo ($plan['featured']) ? $Language->get('text_yes') : $Language->get('text_no'); ?></li>
                                <li>En Portada: <?php echo ($plan['show_in_home']) ? $Language->get('text_yes') : $Language->get('text_no'); ?></li>
                                <li><?php echo $plan['price']; ?></li>
                                <li><a class="button blue" title="<?php echo $Language->get('text_publish'); ?>" onclick="setPlan('<?php echo $plan['plan_id']; ?>','<?php echo $plan['qty_days']; ?>','<?php echo $plan['qty_images']; ?>','<?php echo $plan['qty_videos']; ?>','<?php echo $plan['featured']; ?>','<?php echo $plan['show_in_home']; ?>','<?php echo $plan['featured']; ?>','<?php echo $plan['price']; ?>');"><?php echo $Language->get('text_publish'); ?></a></li>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="clear"></div>
                
                    <div id="step2" style="display:none;">
                        <h1>Selecciona Las Categor&iacute;as</h1>
                        <div id="categoriesWrapper">
                            <select id="category_0" size="30"></select>
                            <input type="hidden" id="category0" name="Categories[]" value="" />
                        </div>
                        
                        <div class="clear"></div><br /><br /><br /><br /><br /><br />
                        
                        <a class="button blue" id="goFrom2ToStep1" onclick="$('#step2').hide();$('#step1').fadeIn();$('#neco-unlock-form').hide();">Atr&aacute;s</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="button blue" id="goFrom2ToStep3">Siguiente</a>
                    </div>
                
                    <div class="clear"></div>
              
                    <div id="step3" style="display:none;">
                        <h1>Describe Tu Anuncio</h1>
                            <input type="hidden" name="plan_id" id="plan_id" value="" />
                            <input type="hidden" name="qty_days" id="qty_days" value="" />
                            
                            <div class="clear"></div>
                      
                            <div id="product_images"></div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">T&iacute;tulo del Art&iacute;culo</label>
                                <input type="text" id="name" name="name" value="<?php echo ($name) ? $name : ''; ?>" required="required" placeholder="Ingresa el nombre del producto o servicio" />
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">Modelo:</label>
                                <input type="text" id="model" name="model" value="" required="required" />
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">Resumen del Artículo</label>
                                <textarea id="meta_description" name="meta_description" maxlength="120" required="required"><?php echo ($meta_description) ? $meta_description : ''; ?></textarea>
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">Cantidad Disponible</label>
                                <input type="number" id="quantity" name="quantity" value="<?php echo ($quantity) ? $quantity : '1'; ?>" required="required" required="required" />
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">Ingresa la descripci&oacute;n del art&iacute;culo:</label>
                                <div class="clear"></div><br />
                                <textarea name="description" id="description" required="required"><?php echo ($description) ? $description : ''; ?></textarea>
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="availability">Disponibilidad</label>
                                <select name="stock_status_id">
                                    <?php foreach ($stock_statuses as $stock_status) { ?>
                                    <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?> selected="selected"<?php } ?>><?php echo $stock_status['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">Condiciones del Art&iacute;culo</label>
                                <select name="Properties[status]">
                                    <option value="Nuevo">Nuevo</option>
                                    <option value="Usado">Usado</option>
                                    <option value="Remanufacturado">Remanufacturado</option>
                                </select>
                            </div>
                            
                            <div class="clear"></div>
                      
                            <div class="row">
                                <label for="">&iquest;Cu&aacute;nto est&aacute;s dispuesto a pagar?</label>
                                <input type="money" name="price" value="" showquick="off" />
                            </div>
                            
                            <div class="clear"></div><br /><br />
                            
                            <h2>Estados / Provincias / Departamentos</h2>
                            
                            <ul id="formZones">
                            <?php foreach ($zones as $zone) { ?>
                                <li>
                                    <label for="<?php echo $zone['name']; ?>"><?php echo $zone['name']; ?></label>
                                    <input type="checkbox" name="Zones[<?php echo $zone['zone_id']; ?>]" value="<?php echo $zone['zone_id']; ?>" showquick="off" onchange="if ($(this).attr('checked')) {$(this).closest('li').addClass('selected');} else {$(this).closest('li').removeClass('selected');}" />
                                </li>
                            <?php } ?>
                            </ul>
                            
                            <div class="clear"></div><br /><br />
                      
            
                            <h2>Formas de Pagos</h2>      
                            <ul id="formPayment">
                            <?php foreach ($payment_methods as $payment_method) { ?>
                                <li>
                                    <label for="<?php echo $payment_method['id']; ?>"><?php echo $payment_method['title']; ?></label>
                                    <input type="checkbox" name="PaymentMethods[<?php echo $payment_method['id']; ?>]" value="<?php echo $payment_method['title']; ?>" showquick="off" onchange="if ($(this).attr('checked')) {$(this).closest('li').addClass('selected');} else {$(this).closest('li').removeClass('selected');}" />
                                </li>
                            <?php } ?>
                            </ul>
                            
                            <div class="clear"></div><br /><br /><br />
                      
                            <h2>Formas de Env&iacute;os</h2>
                            <ul id="formShipping">
                            <?php foreach ($shipping_methods as $shipping_method) { ?>
                                <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                <li>
                                    <label for="<?php echo $quote['id']; ?>"><?php echo $quote['title']; ?></label>
                                    <input type="checkbox" name="ShippingMethods[<?php echo $quote['id']; ?>]" value="<?php echo $quote['title']; ?>" showquick="off" onchange="if ($(this).attr('checked')) {$(this).closest('li').addClass('selected');} else {$(this).closest('li').removeClass('selected');}" />
                                </li>
                            <?php } ?>
                            <?php } ?>
                            </ul>
                            
                            <div class="clear"></div><br /><br /><br />
                      
                        
                        
                            <h2 id="headingAttributes" style="display: none;">Especificaciones T&eacute;cnicas</h2>
                          
                            <div id="formAttributes"></div>
                            
                            <div class="clear"></div><br /><br /><br /><br /><br /><br />
                      
                        <div class="message success">Para continuar debes desbloquear el formulario deslizando la flecha hacia la izquierda</div>
                        <a class="button blue" onclick="$('#step3').hide();$('#step2').fadeIn();$('#neco-unlock-form').hide();">Atr&aacute;s</a>
                    </div>
                        
                </form>
                
            </div>
            
        </section>
    </div>
</section>
<script>
$(function(){
    $('.plan').on('mouseenter',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        if (e.isImmediatePropagationStopped()) $(this).toggleClass('plan_hover',200);
    }).on('mouseleave',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        if (e.isImmediatePropagationStopped()) $(this).toggleClass('plan_hover',200);
    });
    $('#goFrom2ToStep3').on('click',function(e){
        $('#temp').remove();
        var selected = $('.Categories:last-child').val();
        if (typeof selected != 'undefined' && selected) {
            $('#step2').hide(); 
            $('#step3').fadeIn();
            $('#neco-unlock-form').show();
        } else {
            $('#goFrom2ToStep1').before('<div id="temp" class="message warning">Debes seleccionar una categor&iacute;a y todas las subsecuentes para continuar.</div>');
        }
    });
});
</script>
<?php echo $footer; ?>
