<?php echo $header;  if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<div class="box">
        <h1><?php echo $heading_title; ?></h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $button_save_and_exit; ?></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $button_save_and_keep; ?></a>
            <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $button_save_and_new; ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
        </div>
        
        <div class="clear"></div>
                                
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div class="row">
                <label>Nombre del Grupo de Atributos:</label>
                <input class="category" id="name" name="name" value="<?php echo $attribute['name']; ?>" required="true" style="width:40%" />
            </div>
            
            <div class="clear"></div>
                        
            <div class="row">
                <label>Seleccione las categor&iacute;as asociadas:</label>
                <input type="text" title="Filtrar listado de categor&iacute;as" placeholder="Filtrar Categorias" value="" name="q" id="q" />
                <div class="clear"></div>
                <ul id="categoriesWrapper" class="scrollbox">
                    <?php foreach ($categories as $category) { ?>
                    <li class="categories">
                        <input title="<?php echo $help_category; ?>" type="checkbox" name="Categories[]" value="<?php echo $category['category_id']; ?>"<?php if (in_array($category['category_id'], $attributes_to_categories)) { ?> checked="checked"<?php } ?> showquick="off" />
                        <b><?php echo $category['name']; ?></b>
                    </li>
                    <?php } ?>
                </ul>
            </div>
                    
            <div class="clear"></div><br />
            
            <div>
                <table id="special" class="list">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Etiqueta del Atributo</th>
                            <th>Nombre del Atributo</th>
                            <th>Valor Predeterminado</th>
                            <th>Requerido</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="rows">
                    <?php foreach ($attribute['attributes'] as $row => $property) { ?>
                        <tr>
                            <td>
                                <select name="Properties[<?php echo $row; ?>][type]" showquick="off">
                                    <option value="text"<?php if ($property['type'] == 'text') { echo ' selected="selected"'; } ?>>Texto</option>
                                    <option value="checkbox"<?php if ($property['type'] == 'checkbox') { echo ' selected="selected"'; } ?>>Checkbox</option>
                                    <option value="radio"<?php if ($property['type'] == 'radio') { echo ' selected="selected"'; } ?>>Radio</option>
                                    <option value="email"<?php if ($property['type'] == 'email') { echo ' selected="selected"'; } ?>>Email</option>
                                    <option value="number"<?php if ($property['type'] == 'number') { echo ' selected="selected"'; } ?>>N&uacute;mero</option>
                                    <option value="date"<?php if ($property['type'] == 'date') { echo ' selected="selected"'; } ?>>Fecha (dd/mm/yyyy)</option>
                                    <option value="password"<?php if ($property['type'] == 'password') { echo ' selected="selected"'; } ?>>Contrase&ntilde;a</option>
                                </select>
                            </td>
                            <td><input type="text" name="Properties[<?php echo $row; ?>][label]" id="label_<?php echo $row; ?>" value="<?php echo $property['label']; ?>" placeholder="Etiqueta del Atributo" showquick="off" /></td>
                            <td><input type="text" name="Properties[<?php echo $row; ?>][name]" id="name_<?php echo $row; ?>" value="<?php echo $property['name']; ?>" placeholder="Nombre del Atributo" showquick="off" /></td>
                            <td><input type="text" name="Properties[<?php echo $row; ?>][default]" id="default_<?php echo $row; ?>" value="<?php echo $property['default']; ?>" placeholder="Valor Predeterminado" showquick="off" /></td>
                            <td><input type="checkbox" name="Properties[<?php echo $row; ?>][required]" id="required_<?php echo $row; ?>" value="1" title="Campo Obligatorio"<?php if ($property['required']) { echo ' checked="checked"'; } ?> showquick="off" /></td>
                            <td><a onclick="$(this).closest('tr').remove();" class="button"><?php echo $button_remove; ?></a>
                        <script>
                        $(function(){
                            $('#label_<?php echo $row; ?>').on('change',function(event) {
                                $.getJSON('<?php echo $Url::createAdminUrl("common/home/slug"); ?>&slug='+ $(this).val(),
                                function(response) {
                                    $('#name_<?php echo $row; ?>').val(response.slug);
                                });
                            });
                        });
                        </script></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"></td>
                            <td><a onclick="addSpecial();" class="button">Agregar Atributo</a></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <script type="text/javascript">
            function addSpecial() {
                var row = ($('#rows tr:last-child').index() + 1);
                var html = "";
            	html += '<tr>';
                html += '<td><select name="Properties['+ row +'][type]">';
                html += '<option value="text">Texto</option>';
                html += '<option value="checkbox">Checkbox</option>';
                html += '<option value="radio">Radio</option>';
                html += '<option value="email">Email</option>';
                html += '<option value="number">N&uacute;mero</option>';
                html += '<option value="date">Fecha (dd/mm/yyyy)</option>';
                html += '<option value="password">Contrase&ntilde;a</option>';
                html += '</select></td>';
            	html += '<td><input type="text" name="Properties['+ row +'][label]" id="label_'+ row +'" value="" placeholder="Etiqueta del Atributo"></td>';
            	html += '<td><input type="text" name="Properties['+ row +'][name]" id="name_'+ row +'" value="" placeholder="Nombre del Atributo"></td>';
            	html += '<td><input type="text" name="Properties['+ row +'][default]" id="default_'+ row +'" value="" placeholder="Valor Predeterminado"></td>';
            	html += '<td><input type="checkbox" name="Properties['+ row +'][required]" id="required_'+ row +'" value="1" title="Campo Obligatorio"></td>';
            	html += '<td><a onclick="$(this).closest(\'tr\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
            	html += '</tr>';
            	$('#rows').append(html);
                $('#label_'+ row).on('change',function(event) {
                    $.getJSON('<?php echo $Url::createAdminUrl("common/home/slug"); ?>&slug='+ $(this).val(),
                    function(response) {
                        $('#name_'+ row).val(response.slug);
                    });
                });
            }
            </script>             
        </form>
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
            <input type="hidden" name="server" id="server" value="<?php echo serialize($_SERVER); ?>" />
            <div class="clear"></div>
            <br />
            <div class="buttons"><a class="button" onclick="sendFeedback()">Enviar Sugerencia</a></div>
        </form>
    </div>
</div>
<div class="sidebar" id="toolPanel">
    <div class="tab"></div>
    <div class="content">
        <h2>Herramientas</h2>
        <p>S&aacute;cale provecho a NecoTienda y aumenta tus ventas.</p>
        <ul>
            <li><a onclick="$('#addProductsWrapper').slideDown();$('html, body').animate({scrollTop:$('#addProductsWrapper').offset().top}, 'slow');">Agregar Productos</a></li>
            <li><a class="trends" data-fancybox-type="iframe" href="http://www.necotienda.com/index.php?route=api/trends&q=samsung&geo=VE">Evaluar Palabras Claves</a></li>
            <li><a>Eliminar Esta Categor&iacute;a</a></li>
        </ul>
        <div class="toolWrapper"></div>
    </div>
</div>
<div class="sidebar" id="helpPanel">
    <div class="tab"></div>
    <div class="content">
        <h2>Ayuda</h2>
        <p>No entres en p&aacute;nico, todo tiene una soluci&oacute;n.</p>
        <ul>
            <li><a>&iquest;C&oacute;mo se come esto?</a></li>
            <li><a>&iquest;C&oacute;mo relleno este formulario?</a></li>
            <li><a>&iquest;Qu&eacute; significan las figuritas al lado de los campos?</a></li>
            <li><a>&iquest;C&oacute;mo me desplazo a trav&eacute;s de las pesta&ntilde;as?</a></li>
            <li><a>&iquest;Pierdo la informaci&oacute;n si me cambio de pesta&ntilde;a?</a></li>
            <li><a>Preguntas Frecuentes</a></li>
            <li><a>Manual de Usuario</a></li>
            <li><a>Videos Tutoriales</a></li>
            <li><a>Auxilio, por favor ay&uacute;denme!</a></li>
        </ul>
    </div>
</div>
<?php echo $footer; ?>