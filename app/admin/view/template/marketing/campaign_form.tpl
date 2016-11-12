<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
<div class="box">
        <h1>Crear Campa&ntilde;a</h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button">Guardar y Enviar</a>
            <a onclick="saveAndNew();$('#form').submit();" class="button">Enviar Prueba</a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        
            <div class="row">
                <label>Nombre de la Campa&ntilde;a</label>
                <input type="text" name="name" id="name" value="<?php echo ($name) ? $name : ''; ?>" required="required" />
            </div>
            
            <div class="row">
                <label>Asunto</label>
                <input type="text" name="subject" id="subject" value="<?php echo ($subject) ? $subject : ''; ?>" required="required" />
            </div>
            
            <div class="row">
                <label>Nombre del Remitente</label>
                <input type="text" name="from_name" id="from_name" value="<?php echo ($from_name) ? $from_name : ''; ?>" required="required" />
            </div>
            
            <div class="row">
                <label>Email del Remitente</label>
                <input type="email" name="from_email" id="from_email" value="<?php echo ($from_email) ? $from_email : ''; ?>" required="required" />
            </div>
            
            <div class="row">
                <label>Email de R&eacute;plica</label>
                <input type="email" name="replyto_email" id="replyto_email" value="<?php echo ($replyto_email) ? $replyto_email : ''; ?>" required="required" />
            </div>
            
            <div class="row">
                <label>Fecha de Env&iacute;o ( hh:mm A dd/mm/yy )</label>
                <?php  $i = 0; ?>
                <select name="start_hour">
                    <?php while ($i < 13) { ?>
                    <option value="<?php echo ($i < 10) ? "0".$i : $i; ?>"<?php if (((int)$start_hour + 1) == $i) { ?> selected="selected"<?php } ?>><?php echo ($i < 10) ? "0".$i : $i; ?></option>
                    <?php $i++; 
                     } ?>
                </select>
                <span style="float: left;">&nbsp;:&nbsp;</span>
                <select name="start_minute">
                    <?php foreach ($minutes as $min) { ?>
                    <option value="<?php echo $min; ?>"<?php if ($start_minute==$min) { echo " selected=\"selected\""; } ?>><?php echo $min; ?></option>
                    <?php } ?>
                </select>
                <select name="start_meridium" style="margin-left:5px">
                    <option value="AM"<?php if ($start_meridium=='AM') { echo " selected=\"selected\""; } ?>>AM</option>
                    <option value="PM"<?php if ($start_meridium=='PM') { echo " selected=\"selected\""; } ?>>PM</option>
                </select>
                <select name="start_day" style="margin-left:10px">
                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                    <option value="<?php echo ($i < 10) ? '0'.$i : $i; ?>"<?php if ((int)$start_day == $i) { ?> selected="selected"<?php } ?>><?php echo ($i < 10) ? '0'.$i : $i; ?></option>
                    <?php } ?>
                </select>
                <span style="float: left;">&nbsp;/&nbsp;</span>
                <select name="start_month">
                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <option value="<?php echo ($i < 10) ? '0'.$i : $i; ?>"<?php if ((int)$start_month == $i) { ?> selected="selected"<?php } ?>><?php echo ($i < 10) ? '0'.$i : $i; ?></option>
                    <?php } ?>
                </select>
                <span style="float: left;">&nbsp;/&nbsp;</span>
                <select name="start_year">
                    <?php for ($i = date("Y"); $i <= (date("Y") + 5); $i++) { ?>
                    <option value="<?php echo $i; ?>"<?php if ((int)$start_year == $i) { ?> selected="selected"<?php } ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="row">
                <label>Repetir esta campa&ntilde;a</label>
                <select name="repeat" onchange="if (this.value=='weekly') {$('#repeat_wday').show()} else {$('#repeat_wday').hide()} if (this.value.length>0) {$('#end').show()} else {$('#end').hide()} ">
                    <option value="">Una sola vez</option>
                    <option value="daily">Todos los d&iacute;as</option>
                    <option value="weekly">Semanal</option>
                    <option value="monthly">Mensual</option>
                    <option value="yearly">Anual</option>
                </select>
                <span style="float: left;">&nbsp;&nbsp;</span>
                <select name="repeat_wday" id="repeat_wday" style="display: none;">
                    <option value="sunday">Domingo</option>
                    <option value="monday">Lunes</option>
                    <option value="tuesday">Martes</option>
                    <option value="wednesday">Mi&eacute;rcoles</option>
                    <option value="thusday">Jueves</option>
                    <option value="friday">Viernes</option>
                    <option value="saturday">S&aacute;bado</option>
                </select>
            </div>
            
            <div class="row" id="end" style="display: none;">
                <label>Repetir Hasta ( hh:mm A dd/mm/yy )</label>
                <?php  $i = 0; ?>
                <select name="end_hour">
                    <?php while ($i < 13) { ?>
                    <option value="<?php echo ($i < 10) ? "0".$i : $i; ?>"<?php if (((int)$end_hour + 1) == $i) { ?> selected="selected"<?php } ?>><?php echo ($i < 10) ? "0".$i : $i; ?></option>
                    <?php $i++; 
                     } ?>
                </select>
                <span style="float: left;">&nbsp;:&nbsp;</span>
                <select name="end_minute">
                    <?php foreach ($minutes as $min) { ?>
                    <option value="<?php echo $min; ?>"<?php if ($end_minute==$min) { echo " selected=\"selected\""; } ?>><?php echo $min; ?></option>
                    <?php } ?>
                </select>
                <select name="end_meridium" style="margin-left:5px">
                    <option value="AM"<?php if ($end_meridium=='AM') { echo " selected=\"selected\""; } ?>>AM</option>
                    <option value="PM"<?php if ($end_meridium=='PM') { echo " selected=\"selected\""; } ?>>PM</option>
                </select>
                <select name="end_day" style="margin-left:10px">
                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                    <option value="<?php echo ($i < 10) ? '0'.$i : $i; ?>"<?php if ((int)$end_day == $i) { ?> selected="selected"<?php } ?>><?php echo ($i < 10) ? '0'.$i : $i; ?></option>
                    <?php } ?>
                </select>
                <span style="float: left;">&nbsp;/&nbsp;</span>
                <select name="end_month">
                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                    <option value="<?php echo ($i < 10) ? '0'.$i : $i; ?>"<?php if ((int)$end_month == $i) { ?> selected="selected"<?php } ?>><?php echo ($i < 10) ? '0'.$i : $i; ?></option>
                    <?php } ?>
                </select>
                <span style="float: left;">&nbsp;/&nbsp;</span>
                <select name="end_year">
                    <?php for ($i = date("Y"); $i <= (date("Y") + 5); $i++) { ?>
                    <option value="<?php echo $i; ?>"<?php if ((int)$end_year == $i) { ?> selected="selected"<?php } ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="row">
                <label>Rastrear Email</label>
                <input type="checkbox" name="trace_email" id="trace_email" value="1" />
            </div>
            
            <div class="row">
                <label>Rastrear Clicks</label>
                <input type="checkbox" name="trace_click" id="trace_click" value="1" />
            </div>
            
            <div class="row">
                <label>Incrustar Im&aacute;genes</label>
                <input type="checkbox" name="embed_image" id="embed_image" value="1" />
            </div>
            
            <div class="row">
                <label>Listas de Contactos</label>
                <?php if ($lists) { ?>
                <input type="text" title="Filtrar listado de categor&iacute;as" value="Ingresa el nombre de la lista" name="q" id="q" onfocus="this.value=''" />
                <div class="clear"></div>
                <label>&nbsp;</label>
                <ul id="listsWrapper" class="scrollbox">
                <?php foreach ($lists as $list) { ?>
                    <li>
                        <input type="checkbox" name="contact_list[]" value="<?php echo $list['contact_list_id']; ?>"<?php if (in_array($list['contact_list_id'], $contacts_list)) { ?> checked="checked"<?php } ?> showquick="off" />
                        <b><?php echo $list['name']; ?>&nbsp;&nbsp;(&nbsp;<?php echo $list['total_contacts']; ?>&nbsp;)</b>
                    </li>
                <?php } ?>
                </ul>
                <?php } else { ?>
                No hay listas de contactos registradas
                <?php } ?>
            </div>
            
            <div class="row">
                <label>Plantilla de Email</label>
                <?php if ($newsletters) { ?>
                <select name="newsletter_id">
                    <option value="">Seleccione</option>
                    <?php foreach ($newsletters as $newsletter) { ?>
                    <option value="<?php echo $newsletter['newsletter_id']; ?>"<?php if (in_array($newsletter['newsletter_id'], $templates)) { ?> selected="selected"<?php } ?>><?php echo $newsletter['name']; ?></option>
                    <?php } ?>
                </select>
                <a href="#" id="email_preview" title="Previsualizar Plantilla" style="margin-left: 10px;font-size: 10px;">[ Previsualizar ]</a>
                <?php } else { ?>
                No hay plantillas de email registradas
                <?php } ?>
            </div>
            
            <div class="clear"></div><br />
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
            <li><a onclick="$('#addsWrapper').slideDown();$('html, body').animate({scrollTop:$('#addsWrapper').offset().top}, 'slow');">Agregar Productos</a></li>
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