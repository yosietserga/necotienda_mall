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
                <?php if ($error_warning) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>

                <div class="clear"></div>

                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
                    <fieldset>
                        <legend>Fotos del Local</legend>
                        <div class="row">
                            <ul id="product_images">
                                <?php
                                if ($images) {
                                    $_images = 5 - count($images); 
                                } else {
                                    $_images = 5;
                                }
                                foreach ($images as $key => $value) {
                                ?>
                                <li>
                                    <img src="<?php echo $Image->resizeAndSave($value,100,100); ?>" width="100" height="100" />
                                    <input class="image" type="file" name="files[]" id="image<?php echo $key; ?>" value="" showquick="off" accept="image/gif, image/jpeg, image/png" />
                                    <div id="preview<?php echo $key; ?>" class="uploadPreview"></div>
                                    <div class="clear">&nbsp;</div>
                                </li>
                                <?php
                                }
                                for ($i=$_images; $i>0; $i--) {
                                ?>
                                <li>
                                    <img src="<?php echo $Image->resizeAndSave('no_image.jpg',100,100); ?>" width="100" height="100" />
                                    <input class="image" type="file" name="files[]" id="image<?php echo $i; ?>" value="" showquick="off" accept="image/gif, image/jpeg, image/png" />
                                    <div id="preview<?php echo $i; ?>" class="uploadPreview"></div>
                                    <div class="clear">&nbsp;</div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Filosofía Corporativa</legend>
                        <div class="row">
                            <label><b>Descripci&oacute;n del Negocio:</b></label>
                            <textarea id="description" name="description" placeholder="<?php echo $Language->get('entry_description'); ?>"><?php echo $description; ?></textarea>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Rese&ntilde;a Hist&oacute;rica:</b></label>
                            <textarea id="history" name="history" placeholder="<?php echo $Language->get('entry_history'); ?>"><?php echo $history; ?></textarea>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Misi&oacute;n:</b></label>
                            <textarea id="mission" name="mission" placeholder="<?php echo $Language->get('entry_mission'); ?>"><?php echo $mission; ?></textarea>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Visi&oacute;n:</b></label>
                            <textarea id="vision" name="vision" placeholder="<?php echo $Language->get('entry_vision'); ?>"><?php echo $vision; ?></textarea>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Valores:</b></label>
                            <textarea id="values" name="values" placeholder="<?php echo $Language->get('entry_values'); ?>"><?php echo $values; ?></textarea>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Pol&iacute;ticas:</b></label>
                            <textarea id="policies" name="policies" placeholder="<?php echo $Language->get('entry_policies'); ?>"><?php echo $policies; ?></textarea>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>A&ntilde;os de Experiencia:</b></label>
                            <input type="number" id="experience_years" name="experience_years" value="<?php echo $experience_years; ?>" placeholder="<?php echo $Language->get('entry_experience_years'); ?>" />
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Fecha de Registro:</b></label>
                            <select name="bday" style="width: 60px;" showquick="off">
                            <?php
                            $day = 1;
                            $toDay = 31;
                            while ($toDay >= $day) { ?>
                            <?php $day = ($day < 10) ? '0'.$day : $day; ?>
                                <option value="<?php echo $day; ?>"<?php if ($day == $bday) { ?> selected="selected"<?php } ?>><?php echo $day; ?></option>
                            <?php
                                $day++; 
                            }
                            ?>
                            </select>
                            <select name="bmonth" style="width: 60px;" showquick="off">
                            <?php
                            $month = 1;
                            $toMonth = 12;
                            while ($toMonth >= $month) { ?>
                            <?php $month = ($month < 10) ? '0'.$month : $month; ?>
                                <option value="<?php echo $month; ?>"<?php if ($month == $bmonth) { ?> selected="selected"<?php } ?>><?php echo $month; ?></option>
                            <?php
                                $month++; 
                            }
                            ?>
                            </select>
                            <select name="byear" style="width: 80px;" showquick="off">
                            <?php
                            $currentYear = date('Y');
                            $fromYear = $currentYear - 100;
                            while ($fromYear < $currentYear) { ?>
                                <option value="<?php echo $currentYear; ?>"<?php if ($currentYear == $byear) { ?> selected="selected"<?php } ?>><?php echo $currentYear; ?></option>
                            <?php
                                $currentYear--; 
                            }
                            ?>
                            </select>
                        </div>

                        <div class="clear"></div>

                        <div class="row">
                            <label><b>Tipo de Empresa:</b></label>
                            <select id="enterprise_type" name="enterprise_type">
                                <option value="">Seleccione</option>
                                <option value="Importadora"<?php if ($enterprise_type == 'Importadora') { echo ' selected="selected"'; } ?>>Importadora</option>
                                <option value="Distribuidora"<?php if ($enterprise_type == 'Distribuidora') { echo ' selected="selected"'; } ?>>Distribuidora</option>
                                <option value="Manufactura"<?php if ($enterprise_type == 'Manufactura') { echo ' selected="selected"'; } ?>>Manufactura</option>
                                <option value="Comercio Al Detal"<?php if ($enterprise_type == 'Comercio Al Detal') { echo ' selected="selected"'; } ?>>Comercio Al Detal</option>
                                <option value="Comercio Al Mayor"<?php if ($enterprise_type == 'Comercio Al Mayor') { echo ' selected="selected"'; } ?>>Comercio Al Mayor</option>
                                <option value="Profesional Independiente"<?php if ($enterprise_type == 'Profesional Independiente') { echo ' selected="selected"'; } ?>>Profesional Independiente</option>
                                <option value="Servicios"<?php if ($enterprise_type == 'Servicios') { echo ' selected="selected"'; } ?>>Servicios</option>
                            </select>
                        </div>
                        
                    </fieldset>

                    <fieldset>
                        <legend>Listado de Clientes</legend>
                        <div class="row">
                            <a class="button addClient">Agregar Cliente</a>
                            <div class="clear"></div>
                            <table id="client_list">
                                <?php foreach ($client_list as $k => $v) { ?>
                                <tr id="clientList<?php echo $k; ?>">
                                    <td data-title="Logotipo:" style="position: relative !important; float:left !important;height:110px;">
                                        <img src="<?php echo $Image->resizeAndSave($v['client_logo'],100,100); ?>" width="100" height="100" />
                                        <input class="image" type="file" onchange="changeLogo(this)" name="client_logo[<?php echo $k; ?>]" id="clientLogo<?php echo $k; ?>" value="<?php echo $v['client_logo']; ?>" showquick="off" accept="image/gif, image/jpeg, image/png" />
                                        <div id="previe<?php echo $k; ?>" class="uploadPreview"></div>
                                    </td>
                                    <td data-title="Nombre del Cliente:">
                                        <input type="text" name="client_name[<?php echo $k; ?>]" value="<?php echo $v['client_name']; ?>" showquick="off" placeholder="Nombre del Cliente" />
                                    </td>
                                    <td>
                                        <a class="button" onclick="$('#clientList<?php echo $k; ?>').remove()">Eliminar</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                            <div class="clear"></div>
                            <a class="button addClient">Agregar Cliente</a>
                                <script>
                                    $('.addClient:last-child').hide();
                                    $('input.image').on('change',function(e){
                                        var input = this;
                                        if (input.files && input.files[0]) {
                                            var reader = new FileReader();
                                            reader.onload = function (e) {
                                                $(input).closest('li').find('img').attr('src', e.target.result);
                                            };
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    });
                                    function changeLogo(input) {
                                        if (input.files && input.files[0]) {
                                            var reader = new FileReader();
                                            reader.onload = function (e) {
                                                $(input).closest('td').find('img').attr('src', e.target.result);
                                            };
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                    $('.addClient').on('click', function(e) {
                                        var idx = ($('#client_list tr:last-child').index() + 1);
                                        var tpl = '<tr id="clientList'+ idx +'">';
                                        $('.addClient:last-child').show();
                                        tpl += '<td data-title="Logotipo:" style="position: relative !important; float:left !important;;height:110px;">';
                                        tpl += '<img src="/assets/images/no_image.jpg" width="100" height="100" />';
                                        tpl += '<input class="image" type="file" onchange="changeLogo(this)" name="client_logo['+ idx +']" id="clientLogo'+ idx +'" value="" showquick="off" accept="image/gif, image/jpeg, image/png" />';
                                        tpl += '<div id="previe'+ idx +'" class="uploadPreview"></div>';
                                        tpl += '</td>';
                                        tpl += '<td data-title="Nombre del Cliente:">';
                                        tpl += '<input type="text" name="client_name['+ idx +']" value="" showquick="off" placeholder="Nombre del Cliente" />';
                                        tpl += '</td>';
                                        tpl += '<td>';
                                        tpl += '<a class="button" onclick="$(\'#clientList'+ idx +'\').remove()">Eliminar</a>';
                                        tpl += '</td>';
                                        tpl += '</tr>';
                                        $('#client_list').append(tpl);
                                    });
                                </script>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Ubicación del Local</legend>
                        <div class="row">
                            <label><b>C&oacute;digo de Google Map:</b></label>
                            <div class="clear"></div><small>Para generar el mapa de google ingresa <a target="__blank" href="https://maps.google.co.ve/maps?ll=9.885518,-67.2097134&z=9&output=classic&dg=opt">Aqu&iacute;</small></a><br />
                            <textarea id="google_map" name="google_map" placeholder="<?php echo $Language->get('entry_google_map'); ?>"><?php echo $google_map; ?></textarea>
                            
                        </div>

                    </fieldset>

                    <div class="clear"></div>
                </form>
            
            </div>
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script>
    if (!$("link[href='http://www.echapalante.com.ve/assets/css/jquery-ui/jquery-ui.min.css']").length) {
        $(document.createElement('link')).attr({
            href:'http://www.echapalante.com.ve/assets/css/jquery-ui/jquery-ui.min.css',
            rel:'stylesheet',
            media:'screen'
        }).appendTo('head');
    }
    if (!$("link[href='http://www.echapalante.com.ve/assets/css/neco.form.css']").length) {
        $(document.createElement('link')).attr({
            href:'http://www.echapalante.com.ve/assets/css/neco.form.css',
            rel:'stylesheet',
            media:'screen'
        }).appendTo('head');
    }
    if (!$.ui) {
        $(document.createElement('script')).attr({
            src:'/assets/js/vendor/jquery-ui.min.js',
            type:'text/javascript'
        }).appendTo('head');
    }
    if (!$.fn.ntForm) {
        $(document.createElement('script')).attr({
            src:'/assets/js/necojs/neco.form.js',
            type:'text/javascript'
        }).appendTo('head');
    }
    if ($.fn.ntForm && $.ui) {
    $(function(){
        $('#form').ntForm({
            lockButton:false
        });
    });
    } else {
        console.log('fuck');
    }
</script>
<?php echo $footer; ?>