<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<div class="box">
        <h1><?php echo $Language->get('heading_title'); ?></h1>
        <div class="buttons">
            <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
            <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
            <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
        </div>
        
        <div class="clear"></div>
                                
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <div class="row">
                <label><?php echo $Language->get('entry_author'); ?></label>
                <input id="author" name="author" value="<?php echo $author; ?>" required="true" style="width:40%" />
            </div>
                        
            <div class="clear"></div>
                            
            <div class="row">
                <label><?php echo $Language->get('entry_product'); ?></label>
                <a href="<?php echo $Url::createAdminUrl("store/product/see") . "&product_id=$pid"; ?>"><b id="product_name"><?php echo $product; ?></b></a>
                <input type="hidden" name="product_id" id="product_id" value="<?php echo (int)$pid; ?>" />
            </div>
            
            <div class="clear"></div>
            
            <?php if ((int)$parent_id > 0) { ?>
            <div class="row">
                <label><?php echo $Language->get('entry_review_id'); ?></label>
                <a href="<?php echo $Url::createAdminUrl("store/review/update",array('review_id'=>$parent_id)); ?>"><b><?php echo $parent_id; ?></b></a>
            </div>
            <div class="clear"></div>
            <?php } ?>
            
            <div class="row">
                <label><?php echo $Language->get('entry_text'); ?></label>
                <textarea id="text" name="text" style="width:40%"><?php echo $text; ?></textarea>
            </div>
                   
            <div class="clear"></div><br />
            
            <?php if ($parent_id == 0) { ?>
            <div class="row">
                <label><?php echo $Language->get('entry_rating'); ?></label>
                <a class="star_review<?php if ($rating >= 1) echo ' star_clicked'; ?>" id="1"<?php if ($rating >= 1) echo ' style="background-position: left top;"'; ?>></a>
                <a class="star_review<?php if ($rating >= 2) echo ' star_clicked'; ?>" id="2"<?php if ($rating >= 2) echo ' style="background-position: left top;"'; ?>></a>
                <a class="star_review<?php if ($rating >= 3) echo ' star_clicked'; ?>" id="3"<?php if ($rating >= 3) echo ' style="background-position: left top;"'; ?>></a>
                <a class="star_review<?php if ($rating >= 4) echo ' star_clicked'; ?>" id="4"<?php if ($rating >= 4) echo ' style="background-position: left top;"'; ?>></a>
                <a class="star_review<?php if ($rating >= 5) echo ' star_clicked'; ?>" id="5"<?php if ($rating >= 5) echo ' style="background-position: left top;"'; ?>></a>
                <input type="hidden" name="rating" id="rating" value="<?php echo (int)$rating; ?>" />
            </div>
            <div class="clear"></div><br />
            <?php } ?>
            
            <div class="row">
                <label><?php echo $Language->get('entry_status'); ?></label>
                <select name="status">
                      <option value="1"<?php if ($status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_enabled'); ?></option>
                      <option value="0"<?php if (!$status) { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_disabled'); ?></option>
                </select>
            </div>
                   
            <div class="clear"></div>
            
            <?php if ($parent_id == 0) { ?>
            <div class="row">
                <label><?php echo $Language->get('entry_reply'); ?></label>
                <textarea name="reply" id="reply" style="width:40%"></textarea>
                <div class="clear"></div>
                <label>&nbsp;</label>
                <a class="button" onclick="addReply(this,'<?php echo $pid; ?>','<?php echo $review_id; ?>')"><?php echo $Language->get('button_submit'); ?></a>
            </div>
            
            <div class="clear"></div><br />
            
            <div id="replies">
                <ul>
            <?php if (count($replies)) { ?>
                    <?php foreach ($replies as $reply) { ?>
                    <li id="reply_<?php echo $reply['review_id']; ?>">
                        <div class="grid_3">
                            <b><?php echo $reply['author']; ?></b><br />
                            <small><?php echo date('d-m-Y h:i A',strtotime($reply['date_added'])); ?></small>
                        </div>
                        <div class="grid_9">
                            <?php echo $reply['text']; ?>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <?php } ?>
            <?php } ?>
                </ul>
            </div>
            
            <div class="clear"></div>
            <?php } ?>
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
<script>
$(function(){
    $('#reply').on('focus',function(e){
        $(this).animate({
            height:'100px'
        });
    }).on('blur',function(e){
        if ($(this).val().length == 0) {
            $(this).animate({
                height:'40px'
            });
        }
    });
    $('.star_review').hover(
        function() {
            var idThis = $(this).attr('id');
            $('.star_review').each (function() {
                var idStar = $(this).attr('id');
                if (idStar <= idThis) {
                    $(this).css({'background-position':'left top'});
                }
            });
        },
        function() {
            $('.star_review').each (function() {
                if (!$(this).hasClass('star_clicked'))
                    $(this).css({'background-position':'right top'});
            });
        }
    );
    $('.star_review').on('click',function(e) {
        var idThis = $(this).attr('id');
        $('input[name=rating]').val(idThis);
        $('.star_review').each(function() {
            var idStar = $(this).attr('id');
            if (idStar <= idThis) {
                $(this).addClass('star_clicked');
                $(this).css({'background-position':'left top'});
            } else {
                $(this).removeClass('star_clicked');
                $(this).css({'background-position':'right top'});
            }
        });
    });
});
function addReply(e,p,r) {
    $(e).hide();
    $('.message').fadeOut();
    if ($('#reply').val().length > 0) {
        $.post('<?php echo $Url::createAdminUrl("store/review/reply"); ?>&product_id='+ p +'&review_id='+ r,
        {
            'product_id':p,
            'review_id':r,
            'text':encodeURIComponent($('#reply').val())
        },
        function(response){
            data = $.parseJSON(response);
            if (typeof data.success != 'undefined' && data.success != 0) {
                html = '<li>';
                html += '<div class="grid_3">';
                html += '<b>'+ data.author +'</b><br />';
                html += '<small>'+ data.date_added +'</small>';
                html += '</div>';
                html += '<div class="grid_9">'+ data.text +'</div>';
                html += '<div class="clear"></div>';
                html += '</li>';
                $('#replies ul').prepend(html);
            } else {
                $('#reply').before('<div class="message warning">No se pudo agregar la r&eacute;plica. Por favor intente m&aacute;s tarde.</div>');
            }
            $('textarea[name=reply]').val('');
            $(e).show();
        });
    } else {
        $(e).show();
        $('#reply').addClass('neco-input-error');
    }
}
</script>
<?php echo $footer; ?>