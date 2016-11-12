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
                <label>Nombre:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required="true" style="width:40%" />
            </div>
                        
            <div class="clear"></div>
                        
            <div class="row">
                <label>Precio:</label>
                <input type="money" id="price" name="price" value="<?php echo $price; ?>" required="true" style="width:40%" />
            </div>
                        
            <div class="clear"></div>
                         
            <div class="row">
                <label>&Iacute;cono del Plan:</label>
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                <img alt="&Iacute;cono de plan" src="<?php echo $preview; ?>" id="preview" class="image" onclick="image_upload('image', 'preview');" width="100" height="100" />
                <br />
                <a onclick="image_upload('image', 'preview');" style="margin-left: 220px;color:#FFA500;font-size:10px">[ Cambiar ]</a>
                <a onclick="image_delete('image', 'preview');" style="color:#FFA500;font-size:10px">[ Quitar ]</a>
            </div>
                   
            <div class="clear"></div><br />
               
            <div class="row">
                <label>Cantidad de D&iacute;as Publicado:</label>
                <input type="number" name="qty_days" value="<?php echo $qty_days; ?>" style="width:40%" />
            </div>
            
            <div class="clear"></div>
              
            <div class="row">
                <label>Cantidad de Im&aacute;genes:</label>
                <input type="number" name="qty_images" value="<?php echo $qty_images; ?>" style="width:40%" />
            </div>
            
            <div class="clear"></div>
              
            <div class="row">
                <label>Cantidad de Videos:</label>
                <input type="number" name="qty_videos" value="<?php echo $qty_videos; ?>" style="width:40%" />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label>Mostrar En Portada:</label>
                <input type="checkbox" name="show_in_home" value="1" style="width:40%"<?php if ($show_in_home) { ?> checked="checked"<?php } ?> />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label>Mostrar En Recomendados:</label>
                <input type="checkbox" name="featured" value="1" style="width:40%"<?php if ($featured) { ?> checked="checked"<?php } ?> />
            </div>
            
            <div class="clear"></div>
            
            <div class="row">
                <label>Prioridad:</label>
                <input type="number" name="sort_order" value="<?php echo $sort_order; ?>" style="width:40%" />
            </div>
            
            <div class="clear"></div>
            
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
<?php echo $footer; ?>