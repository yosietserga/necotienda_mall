<li class="nt-editable box searchWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <input id="<?php echo $widgetName; ?>Keyword" type="text" value="" autocomplete="off" placeholder="Buscar" />
        <select id="<?php echo $widgetName; ?>Category">
            <option value="">Todas Las Categor&iacute;as</option>
            <?php echo $categories; ?>
        </select>
        <select id="<?php echo $widgetName; ?>Zone">
            <option value="">Todas Los Estados</option>
            <?php echo $zones; ?>
        </select>
        <a title="Buscar" class="button" onclick="moduleSearch($('#<?php echo $widgetName; ?>Keyword'));"><?php echo $Language->get('text_search'); ?></a>
    </div>
    <div class="clear"></div><br />
</li>
<script>
$(function(){
    $('#<?php echo $widgetName; ?>Keyword').on('keyup',function(e){
        var code = e.keyCode || e.which;
        if ($(this).val().length > 0 && code == 13){
            moduleSearch(this, $('#<?php echo $widgetName; ?>Category').val());
        }
    });
});
</script>