<?php echo $header; ?>
<div class="clear"></div>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
<div class="grid_24" id="msg"></div>
<div class="clear"></div>
<div class="grid_24">
    <div class="box">
        <div class="header">
            <h1>Widgets</h1>
        </div>    
          
        <?php if ($stores) { ?>
        <div class="clear"></div><br />
        <div class="row">
            <label><?php echo $Language->get('entry_store'); ?></label><br />
            <select onchange="window.location = '<?php echo $Url::createAdminUrl("style/widget"); ?>&store_id='+ this.value">
                <option value="0"<?php if ($store_id==0) { echo ' selected="selected"'; } ?>><?php echo $Language->get('text_default'); ?></option>
                <?php foreach ($stores as $store) { ?>
                <option value="<?php echo $store['store_id']; ?>"<?php if ($store_id==$store['store_id']) { echo ' selected="selected"'; } ?>><?php echo $store['name']; ?></option>
                <?php } ?>
            </select>
        </div> 
        <?php } else { ?>
        <?php } ?>
            
        <div class="clear"></div><br />
        
        <div class="grid_5" id="widgetsWrapper">
            <input type="text" id="qWidgets" placeholder="<?php echo $Language->get('text_filter'); ?>" />
            <ul id="widgetsPanel" class="widget widgetsPanel">
                <?php foreach ($modules as $module) { ?>
                <li class="neco-widget" data-title="<?php echo $module['name']; ?>" data-widget="<?php echo $module['widget']; ?>">
                    <b><?php echo $module['name']; ?></b><br />
                    <?php echo $module['description']; ?>
                </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="grid_19" id="blocksWrapper">
            <?php if ($hasHeader) { ?>
            <div class="grid_24">
                <h2>Cabecera (Header)</h2>
                <ul id="widgetHeader" class="widgetWrapper" data-position="header">
                <?php foreach ($widgets['header'] as $widget) { ?>
                    <li class="widgetSet" id="<?php echo $widget['name']; ?>">
                        <b class="widgetTitle"><?php echo $Language->get('text_'.$widget['extension']); ?></b><br />
                        <a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />
                        <div class="attributes"></div>
                        <div style="float:right">
                            <a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>
                            <a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>
                        </div>
                    </li>
                    <script type="text/javascript">
                    $(function(){
                        $.ajaxQueue({
                            url: "index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&w=1",
                            dataType: "json",
                            data:{
                                'extension':'<?php echo $widget['extension']; ?>',
                                'position':'<?php echo $widget['position']; ?>',
                                'name':'<?php echo $widget['name']; ?>'
                            }
                        }).done(function( data ) {
                            $('#<?php echo $widget['name']; ?> .attributes').html(data.html);
                            $('#<?php echo $widget['name']; ?>_form').append('<input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][position]" value="<?php echo $widget['position']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][order]" value="<?php echo (int)$widget['order']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][name]" value="<?php echo $widget['name']; ?>" />');
                            $('.widgetWrapper').find("input, select, textarea, p")
                            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                                e.stopImmediatePropagation();
                            });
                            $('#<?php echo $widget['name']; ?>').find('input, select, textarea').on('change',function(event){
                                $('.saving').remove();
                                $('#<?php echo $widget['name']; ?> .widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                                $.post('index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&name=<?php echo $widget['name']; ?>&order=<?php echo (int)$widget['order']; ?>&position=<?php echo $widget['position']; ?>', 
                                $('#<?php echo $widget['name']; ?>_form').serialize(),
                                function(respons){
                                    $('.saving').remove();
                                    resp = $.parseJSON(respons);
                                });
                            });
                        });
                    });
                    </script>
                <?php } ?>
                </ul>
            </div>
            <div class="clear"></div>
            <?php } ?>
        
            <?php if ($hasFeaturedContent) { ?>
            <div class="grid_23">
                <h2>Contenido Destacado (Featured Content)</h2>
                <ul id="widgetFeaturedContent" class="widgetWrapper" data-position="featuredContent">
                <?php foreach ($widgets['featuredContent'] as $widget) { ?>
                    <li class="widgetSet" id="<?php echo $widget['name']; ?>">
                        <b class="widgetTitle"><?php echo $Language->get('text_'.$widget['extension']); ?></b><br />
                        <a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />
                        <div class="attributes"></div>
                        <div style="float:right">
                            <a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>
                            <a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>
                        </div>
                    </li>
                    <script type="text/javascript">
                    $(function(){
                        $.ajaxQueue({
                            url: "index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&w=1",
                            dataType: "json",
                            data:{
                                'extension':'<?php echo $widget['extension']; ?>',
                                'position':'<?php echo $widget['position']; ?>',
                                'name':'<?php echo $widget['name']; ?>'
                            }
                        }).done(function( data ) {
                            $('#<?php echo $widget['name']; ?> .attributes').html(data.html);
                            $('#<?php echo $widget['name']; ?>_form').append('<input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][position]" value="<?php echo $widget['position']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][order]" value="<?php echo (int)$widget['order']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][name]" value="<?php echo $widget['name']; ?>" />');
                            $('.widgetWrapper').find("input, select, textarea, p")
                            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                                e.stopImmediatePropagation();
                            });
                            $('#<?php echo $widget['name']; ?>').find('input, select, textarea').on('change',function(event){
                                $('.saving').remove();
                                $('#<?php echo $widget['name']; ?> .widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                                $.post('index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&name=<?php echo $widget['name']; ?>&order=<?php echo (int)$widget['order']; ?>&position=<?php echo $widget['position']; ?>', 
                                $('#<?php echo $widget['name']; ?>_form').serialize(),
                                function(respons){
                                    $('.saving').remove();
                                    resp = $.parseJSON(respons);
                                });
                            });
                        });
                    });
                    </script>
                <?php } ?>
                </ul>
            </div>
            <div class="clear"></div>
            <?php } ?>
        
            <?php if ($hasColumnLeft) { ?>
            <div class="grid_6">
                <h2>Columna Izquierda</h2>
                <ul id="widgetColumnLeft" class="widgetWrapper" data-position="column_left">
                <?php foreach ($widgets['column_left'] as $widget) { ?>
                    <li class="widgetSet" id="<?php echo $widget['name']; ?>">
                        <b class="widgetTitle"><?php echo $Language->get('text_'.$widget['extension']); ?></b><br />
                        <a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />
                        <div class="attributes"></div>
                        <div style="float:right">
                            <a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>
                            <a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>
                        </div>
                    </li>
                    <script type="text/javascript">
                    $(function(){
                        $.ajaxQueue({
                            url: "index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&w=1",
                            dataType: "json",
                            data:{
                                'extension':'<?php echo $widget['extension']; ?>',
                                'position':'<?php echo $widget['position']; ?>',
                                'name':'<?php echo $widget['name']; ?>'
                            }
                        }).done(function( data ) {
                            $('#<?php echo $widget['name']; ?> .attributes').html(data.html);
                            $('#<?php echo $widget['name']; ?>_form').append('<input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][position]" value="<?php echo $widget['position']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][order]" value="<?php echo (int)$widget['order']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][name]" value="<?php echo $widget['name']; ?>" />');
                            $('.widgetWrapper').find("input, select, textarea, p")
                            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                                e.stopImmediatePropagation();
                            });
                            $('#<?php echo $widget['name']; ?>').find('input, select, textarea').on('change',function(event){
                                $('.saving').remove();
                                $('#<?php echo $widget['name']; ?> .widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                                $.post('index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&name=<?php echo $widget['name']; ?>&order=<?php echo (int)$widget['order']; ?>&position=<?php echo $widget['position']; ?>', 
                                $('#<?php echo $widget['name']; ?>_form').serialize(),
                                function(respons){
                                    $('.saving').remove();
                                    resp = $.parseJSON(respons);
                                });
                            });
                        });
                    });
                    </script>
                <?php } ?>
                </ul>
            </div>
            <?php } ?>
            
            <?php if ($hasMain) { ?>
            <div class="grid_11" style="margin-left: 2%;">
                <h2>Principal</h2>
                <ul id="widgetMain" class="widgetWrapper" data-position="main">
                <?php foreach ($widgets['main'] as $widget) { ?>
                    <li class="widgetSet" id="<?php echo $widget['name']; ?>">
                        <b class="widgetTitle"><?php echo $Language->get('text_'.$widget['extension']); ?></b><br />
                        <a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />
                        <div class="attributes"></div>
                        <div style="float:right">
                            <a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>
                            <a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>
                        </div>
                    </li>
                    <script type="text/javascript">
                    $(function(){
                        $.ajaxQueue({
                            url: "index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&w=1",
                            dataType: "json",
                            data:{
                                'extension':'<?php echo $widget['extension']; ?>',
                                'position':'<?php echo $widget['position']; ?>',
                                'name':'<?php echo $widget['name']; ?>'
                            }
                        }).done(function( data ) {
                            $('#<?php echo $widget['name']; ?> .attributes').html(data.html);
                            $('#<?php echo $widget['name']; ?>_form').append('<input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][position]" value="<?php echo $widget['position']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][order]" value="<?php echo (int)$widget['order']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][name]" value="<?php echo $widget['name']; ?>" />');
                            $('.widgetWrapper').find("input, select, textarea, p")
                            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                                e.stopImmediatePropagation();
                            });
                            $('#<?php echo $widget['name']; ?>').find('input, select, textarea').on('change',function(event){
                                $('.saving').remove();
                                $('#<?php echo $widget['name']; ?> .widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                                $.post('index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&name=<?php echo $widget['name']; ?>&order=<?php echo (int)$widget['order']; ?>&position=<?php echo $widget['position']; ?>', 
                                $('#<?php echo $widget['name']; ?>_form').serialize(),
                                function(respons){
                                    $('.saving').remove();
                                    resp = $.parseJSON(respons);
                                });
                            });
                        });
                    });
                    </script>
                <?php } ?>
                </ul>
            </div>
            <?php } ?>
            
            <?php if ($hasColumnRight) { ?>
            <div class="grid_6" style="float: right;">
                <h2>Columna Derecha</h2>
                <ul id="widgetColumnRight" class="widgetWrapper" data-position="column_right">
                <?php foreach ($widgets['column_right'] as $widget) { ?>
                    <li class="widgetSet" id="<?php echo $widget['name']; ?>">
                        <b class="widgetTitle"><?php echo $Language->get('text_'.$widget['extension']); ?></b><br />
                        <a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />
                        <div class="attributes"></div>
                        <div style="float:right">
                            <a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>
                            <a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>
                        </div>
                    </li>
                    <script type="text/javascript">
                    $(function(){
                        $.ajaxQueue({
                            url: "index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&w=1",
                            dataType: "json",
                            data:{
                                'extension':'<?php echo $widget['extension']; ?>',
                                'position':'<?php echo $widget['position']; ?>',
                                'name':'<?php echo $widget['name']; ?>'
                            }
                        }).done(function( data ) {
                            $('#<?php echo $widget['name']; ?> .attributes').html(data.html);
                            $('#<?php echo $widget['name']; ?>_form').append('<input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][position]" value="<?php echo $widget['position']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][order]" value="<?php echo (int)$widget['order']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][name]" value="<?php echo $widget['name']; ?>" />');
                            $('.widgetWrapper').find("input, select, textarea, p")
                            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                                e.stopImmediatePropagation();
                            });
                            $('#<?php echo $widget['name']; ?>').find('input, select, textarea').on('change',function(event){
                                $('.saving').remove();
                                $('#<?php echo $widget['name']; ?> .widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                                $.post('index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&name=<?php echo $widget['name']; ?>&order=<?php echo (int)$widget['order']; ?>&position=<?php echo $widget['position']; ?>', 
                                $('#<?php echo $widget['name']; ?>_form').serialize(),
                                function(respons){
                                    $('.saving').remove();
                                    resp = $.parseJSON(respons);
                                });
                            });
                        });
                    });
                    </script>
                <?php } ?>
                </ul>
            </div>
            <?php } ?>
            
            <?php if ($hasFooter) { ?>
            <div class="clear"></div>
            <div class="grid_24">
                <h2>Pie de P&aacute;gina</h2>
                <ul id="widgetFooter" class="widgetWrapper" data-position="footer">
                <?php foreach ($widgets['footer'] as $widget) { ?>
                    <li class="widgetSet" id="<?php echo $widget['name']; ?>">
                        <b class="widgetTitle"><?php echo $Language->get('text_'.$widget['extension']); ?></b><br />
                        <a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />
                        <div class="attributes"></div>
                        <div style="float:right">
                            <a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>
                            <a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>
                        </div>
                    </li>
                    <script type="text/javascript">
                    $(function(){
                        $.ajaxQueue({
                            url: "index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&w=1",
                            dataType: "json",
                            data:{
                                'extension':'<?php echo $widget['extension']; ?>',
                                'position':'<?php echo $widget['position']; ?>',
                                'name':'<?php echo $widget['name']; ?>'
                            }
                        }).done(function( data ) {
                            $('#<?php echo $widget['name']; ?> .attributes').html(data.html);
                            $('#<?php echo $widget['name']; ?>_form').append('<input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][position]" value="<?php echo $widget['position']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][order]" value="<?php echo (int)$widget['order']; ?>" /><input type="hidden" name="Widgets[<?php echo $widget['name']; ?>][name]" value="<?php echo $widget['name']; ?>" />');
                            $('.widgetWrapper').find("input, select, textarea, p")
                            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                                e.stopImmediatePropagation();
                            });
                            $('#<?php echo $widget['name']; ?>').find('input, select, textarea').on('change',function(event){
                                $('.saving').remove();
                                $('#<?php echo $widget['name']; ?> .widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                                $.post('index.php?r=module/<?php echo $widget['extension']; ?>/widget&token=<?php echo $_GET['token']; ?>&name=<?php echo $widget['name']; ?>&order=<?php echo (int)$widget['order']; ?>&position=<?php echo $widget['position']; ?>', 
                                $('#<?php echo $widget['name']; ?>_form').serialize(),
                                function(respons){
                                    $('.saving').remove();
                                    resp = $.parseJSON(respons);
                                });
                            });
                        });
                    });
                    </script>
                <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    
    $('#qWidgets').on('change',function(e){
        var that = this;
        var valor = $(that).val().toLowerCase();
        if (valor.length <= 0) {
            $('#widgetsPanel li').show();
        } else {
            $('#widgetsPanel li b').each(function(){
                if ($(this).text().toLowerCase().indexOf( valor ) > 0) {
                    $(this).closest('li').show();
                } else {
                    $(this).closest('li').hide();
                }
            });
        }
    });
            
    bgPreloader = $(document.createElement('div')).css({
        'position':'fixed',
        'top':'0px',
        'left':'0px',
        'background':'rgba(0,0,0,0.5)',
        'height':'100%',
        'width':'100%',
        'display':'block',
        'z-index':'9998'
    }).attr({
        id:'bgPreloader'
    }).appendTo('body');
    
    divPreloader = $(document.createElement('div')).css({
        'position':'fixed',
        'top':'40%',
        'left':'40%',
        'margin':'0px auto',
        'padding':'5px',
        'background':'#fff',
        'height':'100px',
        'width':'280px',
        'display':'block',
        'boxShadow':'0px 0px 4px #000',
        'borderRadius':'4px',
        'textAlign':'center',
        'z-index':'9999'
    }).attr({
        id:'divPreloader'
    }).html('<b style="color:#000;">Por favor espere mientras se cargan los complementos.</b><br /><img src="<?php echo HTTP_ADMIN_IMAGE; ?>loader.gif" alt="Cargando..." />').appendTo('body');
    
    $.ajaxQueue().then(function(response){
        divPreloader.remove();
        bgPreloader.remove();
    });
    
    var data = {};
    
    $('a.advanced').on('click', function(e) {
        div = $(this).closest('li').find('div.attributes:eq(0)');
        if (div.hasClass('on')) {
            div.removeClass('on').slideUp();
        } else {
            div.addClass('on').slideDown();
        }
    });
    
    $("#widgetsPanel li").draggable({
        connectToSortable:'.widgetWrapper',
        revert: "invalid",
        helper: function(event){
            data.name = $(this).data('title');
            data.extension = $(this).data('widget');
            data.id = "widget_" + data.extension + "_" + rand();
            output = '';
            output += '<b class="widgetTitle">' + data.name + '</b><br />';
            output += '<a class="advanced"><?php echo $Language->get('text_advanced'); ?></a><br />';
            output += '<div class="attributes"></div>';
            output += '<div style="float:right">';
            output += '<a class="moveWidget button" style="padding:2px;cursor:move">Mover</a>';
            output += '<a class="deleteWidget button" onclick="deleteWidget(this)" style="padding:2px;">Eliminar</a>';
            output += '</div>';
            
            data.html = output;
            data.widget = $(this).clone();
            data.widget.attr('id',data.id).addClass('widgetSet').html(output);
            
            return data.widget;
        }
    });
    
    $(".widgetWrapper").sortable({
        placeholder: "widgetPlaceHolder",
        connectWith: '.widgetWrapper',
        revert: true,
        cursor: 'move',
        handle: '.moveWidget',
        start: function(event,ui){
            if (data.name) {
            $($(this).data().uiSortable.currentItem).attr('id',data.id).removeClass('neco-widget').addClass('widgetSet').html(output);
            data.item = $($(this).data().uiSortable.currentItem);
            }
        },
        receive:function(event,ui) {
            if (data.name) {
                data.position = $(this).data('position');
                data.wrapper = $(this);
                data.sort_order = ($(data.item).index() + 1);         
                
                data.inputs = '<input class="widgetName" type="hidden" name="Widgets[' + data.id + '][name]" id="' + data.id + '_name" value="' + data.id + '" /><input class="widgetPosition" type="hidden" name="Widgets[' + data.id + '][position]" id="' + data.id + '_position" value="' + data.position + '" /><input class="widgetSortOrder" type="hidden" name="Widgets[' + data.id + '][order]" id="' + data.id + '_order" value="'+ data.sort_order +'" />';
                
                var widgetId = data.id;
                
                $.ajaxQueue({
                    url: 'index.php?r=module/'+ data.extension +'/widget&token=<?php echo $_GET['token']; ?>&store_id=<?php echo $_GET['store_id']; ?>',
                    dataType: "json",
                    data:{
                        'extension':data.extension,
                        'order':data.sort_order,
                        'position':data.position,
                        'name':data.id
                    }
                }).done(function( response ) {
                    if (typeof response.html != 'undefined') {
                        $('#'+ widgetId +' div.attributes').append(response.html);
                        $('#'+ widgetId +'_form').append(data.inputs);
                        
                        $('#'+ widgetId +' a.advanced').on('click', function(e) {
                            div = $(this).closest('li').find('div.attributes:eq(0)');
                            if (div.hasClass('on')) {
                                div.removeClass('on').slideUp();
                            } else {
                                div.addClass('on').slideDown();
                            }
                        });
                        
                        var widgetModule = data.extension;
                        var widgetName = widgetId;
                        var widgetLi = $('#'+ widgetId);
                        
                        $('#'+ widgetId).find('input, select, textarea').on('change',function(event){
                            $('.widgetTitle').after('<img src="<?php echo HTTP_ADMIN_IMAGE; ?>small_loading.gif" class="saving" />');
                            $.post('index.php?r=module/'+ widgetModule +'/widget&token=<?php echo $_GET['token']; ?>&store_id=<?php echo $_GET['store_id']; ?>&name='+ widgetName +'&order='+ data.sort_order +'&position='+ data.position, 
                            $('#'+ widgetId +'_form').serialize(),
                            function(respons){
                                $('.saving').remove();
                                resp = $.parseJSON(respons);
                                if (typeof resp.error != 'undefined') {
                                    
                                }
                                if (typeof resp.success != 'undefined') {
                                    
                                }
                            });
                        });
                    }
                    $(".widgetWrapper").find("input, select, textarea")
                    .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                        e.stopImmediatePropagation();
                    });
                });
                data.name = null;
            }
        },
        stop: function () {
            $(this).find("input, select, textarea")
            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                e.stopImmediatePropagation();
            });
        },
        update: function(event,ui){
            if (!data.name) {
                setOrder();
            }
        }
    }).disableSelection();
    
});
function setOrder() {
    data = {};
    $('.widgetSet').each(function(){
        $(this).find('.widgetPosition').val( $(this).closest('.widgetWrapper').data('position') );
        $(this).find('.widgetSortOrder').val( ($(this).index() + 1) );
        data[$(this).attr('id')] = {
            'name':$(this).attr('id'),
            'position':$(this).closest('.widgetWrapper').data('position'),
            'order':($(this).index() + 1)
        };
    });
    $.post('<?php echo $Url::createAdminUrl("style/widget/sortable"); ?>',data);
}
function deleteWidget(e) {
    if (confirm("\xbfEst\u00E1 seguro que desea eliminar este widget?")) {
        var li = $(e).closest("li");
        var widgetName = $(li).attr('id');
        li.fadeOut(function(){
            li.remove();
        });
        $.getJSON('<?php echo $Url::createAdminUrl("style/widget/delete"); ?>&name='+ widgetName);
    }
}
function rand (min, max) {
    if (!min && !max) {
        min = 0;
        max = 2147483647;
    }
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
 </script>
<?php echo $footer; ?>