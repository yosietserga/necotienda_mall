<?php echo $header; ?>
<aside id="featured"></aside>
<section id="maincontent">
        <div class="grid_12">
            <div id="featuredContent">
            <?php if($featuredWidgets) { ?><ul class="widgets"><?php foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
            </div>
        </div>
            
    <div class="container_24">
        <section>
        
            <div class="grid_24">
            
                <h1><?php echo $heading_title; ?></h1>
              <div id="step1">
                    <?php $width = (100 / (count($plans) + 1) ); ?>
                    <div class="plan" style="width:<?php echo $width; ?>%;" onclick="$('.plan_id').removeAttr('checked');$('#plan_id_<?php echo $plan['plan_id']; ?>').attr('checked','checked');">
                        <div class="plan_icon"></div>
                        <div class="property odd">Plan</div>
                        <div class="property even">D&iacute;as</div>
                        <div class="property odd">Im&aacute;genes</div>
                        <div class="property even">Videos</div>
                        <div class="property odd">Recomendados</div>
                        <div class="property even">P&aacute;gina Principal</div>
                        <div class="property odd">Precio</div>
                        <div class="property even">Publica Ya!</div>
                    </div>
                    <?php foreach ($plans as $plan) { ?>
                    <div class="plan" style="width:<?php echo $width; ?>%;">
                        <div class="plan_icon">
                            <img src="<?php echo $plan['image']; ?>" alt="<?php echo $plan['name']; ?>" />
                        </div>
                        <?php $class='even'; ?>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo $plan['name']; ?></div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo $plan['qty_days']; ?> D&iacute;as</div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo $plan['qty_images']; ?> Im&aacute;genes</div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo $plan['qty_videos']; ?> Videos</div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo ($plan['featured']) ? "Si" : "No"; ?></div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo ($plan['show_in_home']) ? "Si" : "No"; ?></div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><?php echo $plan['price']; ?></div>
                        <div class="property <?php if ($class=='odd') {echo $class = 'even';} else {echo $class = 'odd';} ?>"><a class="buttonBlue" title="Publicar" onclick="setPlan('<?php echo $plan['plan_id']; ?>','<?php echo $plan['qty_days']; ?>','<?php echo $plan['qty_images']; ?>','<?php echo $plan['qty_videos']; ?>','<?php echo $plan['featured']; ?>','<?php echo $plan['show_in_home']; ?>','<?php echo $plan['featured']; ?>','<?php echo $plan['price']; ?>');">Publicar</a></div>
                        
                        
                    </div>
                    <?php } ?>
                </div>
                <div class="clear"></div>
                <div id="step2" style="display:none;">
                    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="formSale">
                        <input type="hidden" name="plan_id" id="plan_id" value="" />
                        <input type="hidden" name="qty_days" id="qty_days" value="" />
                        <input type="hidden" name="featured" id="qty_days" value="" />
                
                  <table>
                      <tr>
                          <td>Ingresa el nombre del art&iacute;culo:</td>
                          <td><input type="text" id="name" name="name" value="<?php echo ($name) ? $name : ''; ?>" required="required" /></td>
                      </tr>
                      <tr>
                          <td>Ingresa el nombre de la categor&iacute;a:</td>
                          <td id="categoriesWrapper">
                              <input type="text" id="category_0" value="" required="required" />
                              <input type="hidden" id="category0" name="Categories[]" value="" />
                          </td>
                      </tr>
                      <tr>
                          <td>Selecciona las im&aacute;genes:</td>
                          <td id="product_images">
                          </td>
                      </tr>
                      <tr>
                          <td>Ingresa la descripci&oacute;n del art&iacute;culo:</td>
                          <td><textarea name="description" id="description"><?php echo ($description) ? $description : ''; ?></textarea></td>
                      </tr>
                      <tr>
                        <td>Disponibilidad</td>
                        <td>
                            <select name="stock_status_id">
                            <?php foreach ($stock_statuses as $stock_status) { ?>
                                <option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?> selected="selected"<?php } ?>><?php echo $stock_status['name']; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                      </tr>
                      <tr>
                          <td>Cantidad Disponible:</td>
                          <td><input type="number" id="quantity" name="quantity" value="<?php echo ($quantity) ? $quantity : ''; ?>" /></td>
                      </tr>
                      <tr>
                          <td>Estado del art&iacute;culo:</td>
                          <td>
                              <select name="Properties[status]">
                                  <option value="Nuevo">Nuevo</option>
                                  <option value="Usado">Usado</option>
                              </select>
                          </td>
                      </tr>
                  </table>
                  
                  <h2>Precios</h2>
                  <table>
                    <thead>
                        <tr>
                            <th>Clase</th>
                            <th>Precio</th>
                            <th>Cantidad M&iacute;nima</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="money" name="Price[0][clase]" value="Al Detal" showquick="off" disabled="disabled" /></td>
                            <td><input type="money" name="Price[0][price]" value="" showquick="off" required="required" /></td>
                            <td><input type="number" name="Price[0][quantity]" value="1" showquick="off" required="required" /></td>
                        </tr>
                        <tr>
                            <td><input type="money" name="Price[1][clase]" value="Al Mayor" showquick="off" disabled="disabled" /></td>
                            <td><input type="money" name="Price[1][price]" value="" showquick="off" /></td>
                            <td><input type="number" name="Price[1][quantity]" value="" showquick="off" /></td>
                        </tr>
                        <tr>
                            <td><input type="money" name="Price[2][clase]" value="Oferta" showquick="off" disabled="disabled" /></td>
                            <td><input type="money" name="Price[2][price]" value="" showquick="off" /></td>
                            <td><input type="number" name="Price[2][quantity]" value="" showquick="off" /></td>
                        </tr>
                        <tr>
                            <td><input type="money" name="Price[3][clase]" value="Descuento" showquick="off" disabled="disabled" /></td>
                            <td><input type="money" name="Price[3][price]" value="" showquick="off" /></td>
                            <td><input type="number" name="Price[3][quantity]" value="" showquick="off" /></td>
                        </tr>
                    </tbody>
                  </table>
                  
                  <h2>Especificaciones</h2>
                  <table>
                    <tbody>
                        <tr>
                          <td>Modelo:</td>
                          <td><input type="text" id="model" name="model" value="" /></td>
                        </tr>
                        <tr>
                            <td>Peso:</td>
                            <td><input type="text" id="weight" name="weight" value="0 Kg" /></td>
                        </tr>
                    </tbody>
                  </table>
              </form>
              </div>
            </div>
            
        </section>
        
    </div>
</section>

<?php echo $footer; ?>
