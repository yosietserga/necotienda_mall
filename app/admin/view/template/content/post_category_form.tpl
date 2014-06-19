<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<div class="box">
        <h1><?php echo $Language->get('heading_title'); ?></h1>
        <?php if ($post_category_id) { ?><a href="<?php echo $Url::createUrl("content/category",array('post_category_id'=>$post_category_id),'NONSSL',HTTP_CATALOG); ?>" target="_blank"><?php echo $Language->get('text_see_category_in_storefront'); ?></a><?php } ?>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
            <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>
                                
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            
            <div class="row">
                <label><?php echo $Language->get('entry_view'); ?></label>
                <select name="view">
                    <option value=""<?php if (empty($layout)) { echo ' selected="selected"'; } ?>><?php echo $Language->get('text_default'); ?></option>
                    <?php foreach ($views as $key => $value) { ?>
                    <optgroup label="<?php echo $value['folder']; ?>">
                        <?php foreach ($value['files'] as $k => $v) { ?>
                        <option value="<?php echo basename($value['folder']) ."/". basename($v); ?>"<?php if ($layout==basename($value['folder']) ."/". basename($v)) { echo ' selected="selected"'; } ?>><?php echo basename($v); ?></option>
                        <?php } ?>
                    </optgroup>
                    <?php } ?>
                </select>
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_category'); ?></label>
                <select name="parent_id" style="width:40%">
                    <option value="0"><?php echo $Language->get('text_none'); ?></option>
	           <?php foreach ($categories as $category) { ?>
					<option value="<?php echo $category['post_category_id']; ?>"<?php if ($category['post_category_id']==$parent_id) { ?> selected="selected"<?php } ?>><?php echo $category['name']; ?></option>
               <?php } ?>
               </select>
            </div>
            
            <div class="clear"></div><br />
            
            <div id="languages" class="htabs">
                <?php foreach ($languages as $language) { ?>
                    <a tab="#language<?php echo $language['language_id']; ?>" class="htab"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
                <?php foreach ($languages as $language) { ?>
                    <div id="language<?php echo $language['language_id']; ?>">
                    
                        <div class="row">
                            <label><?php echo $Language->get('entry_name'); ?></label>
                            <input class="category" id="description_<?php echo $language['language_id']; ?>_name" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" required="true" style="width:40%" />
                        </div>
                        
                        <div class="clear"></div>
                        
                        <div class="row">
                            <label><?php echo $Language->get('entry_meta_description'); ?></label>
                            <textarea title="<?php echo $Language->get('help_meta_description'); ?>" name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5" style="width:40%"><?php echo isset($category_description[$language[ 'language_id']]) ? $category_description[$language[ 'language_id']][ 'meta_description'] : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <label><?php echo $Language->get('entry_meta_keywords'); ?></label>
                            <textarea title="<?php echo $Language->get('help_meta_keywords'); ?>" name="category_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="40" rows="5" style="width:40%"><?php echo isset($category_description[$language[ 'language_id']]) ? $category_description[$language[ 'language_id']][ 'meta_keywords'] : ''; ?></textarea>
                        </div>
                        
                        <div class="clear"></div>
                                  
                        <div class="row">
                            <label>SEO Url <b style="font:normal 10px verdana;color:#999;"><?php echo HTTP_CATALOG; ?></b></label>
                            <input type="text" id="description_<?php echo $language['language_id']; ?>_keyword" name="category_description[<?php echo $language['language_id']; ?>][keyword]" value="<?php echo isset($category_description[$language[ 'language_id']]) ? $category_description[$language[ 'language_id']][ 'keyword'] : ''; ?>" style="width:40%" />
                        </div>
            
                        <div class="clear"></div>
                                 
                        <div class="row">
                            <label><?php echo $Language->get('entry_description'); ?></label>
                            <div class="clear"></div>
                            <textarea title="<?php echo $Language->get('help_description'); ?>" name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language[ 'language_id']]) ? $category_description[$language[ 'language_id']][ 'description'] : ''; ?></textarea>
                        </div>
                        
                        <div class="clear"></div>
                                    
                    </div>
            <?php } ?>
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label><?php echo $Language->get('entry_image'); ?></label>
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                <img alt="Imagen de la categor&iacute;a" src="<?php echo $preview; ?>" id="preview" class="image" onclick="image_upload('image', 'preview');" width="100" height="100" />
                <br />
                <a onclick="image_upload('image', 'preview');" style="margin-left: 220px;color:#FFA500;font-size:10px">[ Cambiar ]</a>
                <a onclick="image_delete('image', 'preview');" style="color:#FFA500;font-size:10px">[ Quitar ]</a>
            </div>
                   
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
                        <input type="checkbox" name="stores[]" value="<?php echo (int)$store['store_id']; ?>"<?php if (in_array($store['store_id'], $_stores)) { ?> checked="checked"<?php } ?> showquick="off" />
                        <b><?php echo $store['name']; ?></b>
                        <div class="clear"></div>
                    </li>
                <?php } ?>
                </ul>
            </div> 
            <?php } else { ?>
                <input type="hidden" name="stores[]" value="0" />
            <?php } ?>
            
            <div class="clear"></div><br />
            <!--
            <div id="addsPanel"><b>Agregar / Eliminar Art&iacute;culos</b></div>
            <div id="addsWrapper"><div id="gridPreloader"></div></div>
            
            <div class="clear"></div><br />
            -->
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