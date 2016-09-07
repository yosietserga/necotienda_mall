<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <div id="content">
    
        <div class="grid_16">
            <ul id="breadcrumbs" class="nt-editable">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
            </ul>
        </div>
        
        <div class="clear"></div><br /><br />
        
        <div class="grid_16">
            <div id="featuredContent">
            <?php if($featuredWidgets) { ?><ul class="widgets"><?php foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
            </div>
        </div>
        <div class="clear"></div>
        
        <div class="grid_16">
        <ul class="neco-wizard-controls">
            <li>Carrito
                <span>Agrega y elimina productos del carrito de compra</span>
            </li>
             <?php if (!$isLogged) { ?>
            <li>Facturaci&oacute;n
                <span>Ingresa los datos de facturaci&oacute;n</span>
            </li>
            <?php } ?>
            <li>Despacho
                <span>Configura las direcciones de faturaci&oacute;n y entrega</span>
            </li>
            <li>Confirmaci&oacute;n y Pago
                <span>Confirmar los datos del pedido y selecciona el m&eacute;todo de pago</span>
            </li>
            <li>Procesar Pedido
                <span>Procesar el pedido y registrarlo en tu cuenta</span>
            </li>
        </ul>
        
        <div class="clear"></div>
        
        <?php if (!empty($message)) { ?><br /><div class="warning"><?php echo $message; ?></div><?php } ?>            
        
        <div class="clear"></div>
        
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="orderForm">
        <div class="neco-wizard-steps">
            <div>
                <h1><?php echo $heading_title; ?><?php if ($weight) { ?>&nbsp;(<span id="weight"><?php echo $weight; ?></span>)<?php } ?></h1>
                    <table class="cart">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php echo $column_image; ?></th>
                            <th><?php echo $column_name; ?></th>
                            <th><?php echo $column_model; ?></th>
                            <th><?php echo $column_quantity; ?></th>
                            <th><?php echo $column_price; ?></th>
                            <th><?php echo $column_total; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $class = 'odd'; ?>
                        <?php foreach ($products as $product) { ?>
                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                        <tr class="<?php echo $class; ?>">
                            <td><a class="delete-product" onclick="deleteCart(this,'<?php echo $product['key']; ?>')" title="Eliminar"></a></td>
                            <td><a title="<?php echo $product['name']; ?>" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
                            <td>
                                <a title="<?php echo $product['name']; ?>" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $product['name']; ?></a>
                                <?php if (!$product['stock']) { ?><span style="color: #FF0000; font-weight: bold;">***</span><?php } ?>
                                <div><?php foreach ($product['option'] as $option) { ?>- <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br /><?php } ?></div>
                            </td>
                            <td><?php echo $product['model']; ?></td>
                            <td>
                                <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" showquick="off" size="3" style="float:left;width:30px;" onchange="refreshCart(this,'<?php echo $product['key']; ?>')" />
                                <a class="update-product" onclick="refreshCart(this,'<?php echo $product['key']; ?>')" title="Actualizar"></a>
                            </td>
                            <td><?php echo $product['price']; ?></td>
                            <td><?php echo $product['total']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                    
                    <table id="totals" style="float: right;">
                    <?php foreach ($totals as $total) { ?>
                        <tr>
                            <td><b><?php echo $total['title']; ?></b></td>
                            <td><?php echo $total['text']; ?></td>
                        </tr>
                    <?php } ?>
                    </table>
                    
                    <a title="<?php echo $button_shopping; ?>" onclick="location = '<?php echo str_replace('&amp;', '&', $continue); ?>'" class="button"><?php echo $button_shopping; ?></a>
            </div>

            <?php if (!$isLogged) { ?>
            <div>
                <div class="grid_16">
                        <fieldset>
                            <div class="legend">Datos de Facturaci&oacute;n</div>
                            <?php if ($isLogged) { ?><a href="index.php?r=account/account" title="Actualizar Datos">Actualizar Datos</a><?php } ?>
                            <div class="property">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" required="required" title="Ingrese su email, &eacute;ste ser&aacute; verificado contra su servidor para validarlo" style="width: 220px;" <?php if ($isLogged) echo 'disabled="disabled"'; ?> />
                            </div>
                  
                            <div class="property">
                                <label for="company">Nombre Completo o Raz&oacute;n Social:</label>
                                <input type="text" id="company" name="company" required="required" value="<?php echo isset($company) ? $company : ''; ?>" style="width: 220px;" <?php if ($isLogged) echo 'disabled="disabled"'; ?> />
                            </div>
                          
                            <div class="property">
                                <label for="rif">RIF o C&eacute;dula:</label>
                                <select name="riftype" title="Selecciona el tipo de documentaci&oacute;n">
                                    <option value="V" <?php if (strtolower($rif_type) == 'v') echo 'selected="selected"'; ?>>V</option>
                                    <option value="J" <?php if (strtolower($rif_type) == 'j') echo 'selected="selected"'; ?>>J</option>
                                    <option value="E" <?php if (strtolower($rif_type) == 'e') echo 'selected="selected"'; ?>>E</option>
                                    <option value="G" <?php if (strtolower($rif_type) == 'g') echo 'selected="selected"'; ?>>G</option>
                                </select>
                                <input type="text" id="rif" name="rif" value="<?php echo isset($rif) ? $rif : ''; ?>" required="required" maxlength="10" title="Por favor ingresa tu RIF personal o el de la empresa. Si es persona natural y a&uacute;n no posee uno, ingresa tu n&uacute;mero de c&eacute;dula con un n&uacute;mero cero al final" quicktip="Ingresa tu número de cédula si eres una persona natural y no posees RIF. Ingresa solo números" <?php if ($isLogged) echo 'disabled="disabled"'; ?> />
                            </div>
                          
                            <div class="property">
                                <label for="telephone">T&eacute;lefono:</label>
                                <input type="text" id="telephone" name="telephone" required="required" value="<?php echo isset($telephone) ? $telephone : ''; ?>" style="width: 220px;" <?php if ($isLogged) echo 'disabled="disabled"'; ?> />
                            </div>
                            
                          <div class="property">
                            <label for="payment_country_id"><?php echo $entry_country; ?></label>
                            <select name="payment_country_id" id="payment_country_id" title="Selecciona el pa&iaacute;s de la facturaci&oacute;n" onchange="$('select[name=\'payment_zone_id\']').load('index.php?r=account/register/zone&country_id=' + this.value + '&zone_id=<?php echo $payment_zone_id; ?>');">
                                <option value="false">-- Por Favor Seleccione --</option>
                                <?php foreach ($countries as $country) { ?>
                                    <?php if ($country['country_id'] == $payment_country_id) { ?>
                                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                    <?php } else { ?>
                                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                      
                        <div class="property">
                            <label for="payment_zone_id"><?php echo $entry_zone; ?></label>
                            <select name="payment_zone_id" id="payment_zone_id" title="Selecciona el pa&iaacute;s de tu residencia">
                                <option value="false">-- Seleccione un pa&iacute;s --</option>
                            </select>
                        </div>
                      
                        <div class="property">
                            <label for="payment_city"><?php echo $entry_city; ?></label>
                            <input type="text" id="payment_city" name="payment_city" value="<?php echo $payment_city; ?>" required="required" title="Ingrese su nombre y apellido si es persona natural sino ingrese el nombre de su organizaci&oacute;n" />
                        </div>
                  
                        <div class="property">
                            <label for="payment_postcode"><?php echo $entry_postcode; ?></label>
                            <input type="number" id="payment_postcode" name="payment_postcode" value="<?php echo $payment_postcode; ?>" required="required" title="Ingrese su nombre y apellido si es persona natural sino ingrese el nombre de su organizaci&oacute;n" />
                        </div>
                  
                        <div class="property">
                            <label for="payment_address_1"><?php echo $entry_address_1; ?></label>
                            <input type="text" id="payment_address_1" name="payment_address_1" value="<?php echo $payment_address_1; ?>" required="required" title="Ingrese su nombre y apellido si es persona natural sino ingrese el nombre de su organizaci&oacute;n" />
                        </div>
                        </fieldset>
                        
                        <p>Al continuar con el proceso de compra, usted est&aacute; aceptando los <a href="" title="">t&eacute;rminos legales y las condiciones de uso</a> de este sitio web.</p>
                        
                </div>
            </div>
            <?php } ?>
            
            <!-- begin shipping section -->
            <div>
                <div class="grid_16">
                    <?php if (!$isLogged || ($isLogged && !$shipping_country_id)) { ?>
                    <fieldset>
                        <div class="legend">Direcci&oacute;n de Entrega</div>
                        
                        <div class="property">
                            <label for="shipping_country_id"><?php echo $entry_country; ?></label>
                            <select name="shipping_country_id" id="shipping_country_id" title="Selecciona el pa&iaacute;s de tu residencia" onchange="$('select[name=\'shipping_zone_id\']').load('index.php?r=account/register/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
                                <option value="false">-- Por Favor Seleccione --</option>
                                <?php foreach ($countries as $country) { ?>
                                    <?php if ($country['country_id'] == $shipping_country_id) { ?>
                                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                    <?php } else { ?>
                                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                      
                        <div class="property">
                            <label for="shipping_zone_id"><?php echo $entry_zone; ?></label>
                            <select name="shipping_zone_id" id="shipping_zone_id" title="Selecciona el pa&iaacute;s de tu residencia">
                                <option value="false">-- Seleccione un pa&iacute;s --</option>
                            </select>
                        </div>
                      
                        <div class="property">
                            <label for="shipping_city"><?php echo $entry_city; ?></label>
                            <input type="text" id="shipping_city" name="shipping_city" value="<?php echo $shipping_city; ?>" required="required" title="Ingrese su nombre y apellido si es persona natural sino ingrese el nombre de su organizaci&oacute;n" />
                        </div>
                  
                        <div class="property">
                            <label for="shipping_postcode"><?php echo $entry_postcode; ?></label>
                            <input type="number" id="shipping_postcode" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" required="required" title="Ingrese su nombre y apellido si es persona natural sino ingrese el nombre de su organizaci&oacute;n" />
                        </div>
                  
                        <div class="property">
                            <label for="shipping_address_1"><?php echo $entry_address_1; ?></label>
                            <input type="text" id="shipping_address_1" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" required="required" title="Ingrese su nombre y apellido si es persona natural sino ingrese el nombre de su organizaci&oacute;n" />
                        </div>
                  
                        <input type="hidden" name="payment_country_id" id="payment_country_id" value="<?php echo $payment_country_id; ?>" />
                        <input type="hidden" name="payment_zone_id" id="payment_zone_id" value="<?php echo $payment_zone_id; ?>" />
                        <input type="hidden" name="payment_city" id="payment_city" value="<?php echo $payment_city; ?>" />
                        <input type="hidden" name="payment_postcode" id="payment_postcode" value="<?php echo $payment_postcode; ?>" />
                        <input type="hidden" name="payment_address_1" id="payment_address_1" value="<?php echo $payment_address_1; ?>" />
                    </fieldset>
                    <?php } else { ?>
                        <input type="hidden" name="payment_country_id" id="payment_country_id" value="<?php echo $payment_country_id; ?>" />
                        <input type="hidden" name="payment_zone_id" id="payment_zone_id" value="<?php echo $payment_zone_id; ?>" />
                        <input type="hidden" name="payment_city" id="payment_city" value="<?php echo $payment_city; ?>" />
                        <input type="hidden" name="payment_postcode" id="payment_postcode" value="<?php echo $payment_postcode; ?>" />
                        <input type="hidden" name="payment_address_1" id="payment_address_1" value="<?php echo $payment_address_1; ?>" />
                        
                        <input type="hidden" name="shipping_country_id" id="shipping_country_id" value="<?php echo $shipping_country_id; ?>" />
                        <input type="hidden" name="shipping_zone_id" id="shipping_zone_id" value="<?php echo $shipping_zone_id; ?>" />
                        <input type="hidden" name="shipping_city" id="shipping_city" value="<?php echo $shipping_city; ?>" />
                        <input type="hidden" name="shipping_postcode" id="shipping_postcode" value="<?php echo $shipping_postcode; ?>" />
                        <input type="hidden" name="shipping_address_1" id="shipping_address_1" value="<?php echo $shipping_address_1; ?>" />
                    <?php } ?>
                    
                    <fieldset>
                        <div class="legend">Formas de Env&iacute;o</div>
                        
                        <table>
                    <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <tr>
                            <td colspan="3"><h2><?php echo $shipping_method['title']; ?></h2></td>
                        </tr>
                        <?php foreach ($shipping_method['quote'] as $quote) { ?>
                        <tr>
                            <td><input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" showquick="off" /></td>
                            <td><b><?php echo $quote['title']; ?></b></td>
                            <td><b style="font: bold 18px arial;"><?php echo $quote['text']; ?></b></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                        </table>
                    
                    </fieldset>
                </div>
                
            </div>
            <!-- end shipping section -->
            
            <!-- begin payment section -->
            <div>
                <div class="grid_16">
                <h1>Confirmaci&oacute;n del Pedido</h1>
                    <h2>Datos de Facturaci&oacute;n</h2>
                    <table class="confirmOrder">
                        <tr>
                            <td>Raz&oacute;n Social:</td>
                            <td id="confirmCompany"><?php echo $company; ?></td>
                        </tr>
                        <tr>
                            <td>RIF:</td>
                            <td id="confirmRif"><?php echo $rif; ?></td>
                        </tr>
                        <tr>
                            <td>Direcci&oacute;n:</td>
                            <td id="confirmPaymentAddress"><?php echo $payment_address; ?></td>
                        </tr>
                    </table>
                    <h2>Direcci&oacute;n y M&eacute;todo de Env&iacute;o</h2>
                    <table class="confirmOrder">
                        <tr>
                            <td>M&eacute;todo de Env&iacute;o:</td>
                            <td id="shipping_method"></td>
                        </tr>
                        <tr>
                            <td>Direcci&oacute;n:</td>
                            <td id="confirmShippingAddress"><?php echo $shipping_address; ?></td>
                        </tr>
                    </table>
                    
                    <table class="cart">
                        <thead>
                            <tr>
                                <th>Descripci&oacute;n</th>
                                <th>Modelo</th>
                                <th>Precio Unit.</th>
                                <th>Cant.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $class = 'odd'; ?>
                            <?php foreach ($products as $product) { ?>
                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                            <tr class="<?php echo $class; ?>">
                                <td>
                                    <?php echo $product['name']; ?>
                                    <div><?php foreach ($product['option'] as $option) { ?>- <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br /><?php } ?></div>
                                </td>
                                <td><?php echo $product['model']; ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo $product['price']; ?></td>
                                <td><?php echo $product['total']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <div class="clear"></div>
                    
                    <textarea name="comment" style="width: 90%;" placeholder="Ingresa tus comentarios sobre el pedido aqu&iacute;"></textarea>
                    
                </div>
            </div>
            <!-- end payment section -->
            <div>
                <div style="width:300px;margin:20% auto;text-align: center;"><img src="<?php echo HTTP_IMAGE; ?>load.gif" alt="Cargando..." /></div>
            </div>
        </div>
        </form>
        
    </div>
    
    <div class="clear"></div>
    <div class="grid_16">
            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
    </div>
    </div>
</section>
<?php echo $footer; ?> 