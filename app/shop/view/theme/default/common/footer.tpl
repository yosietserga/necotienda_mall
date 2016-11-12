<div class="clear"></div>

<div class="container">
    <footer id="footer">
        <ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul>
        
        <div class="clear">&nbsp;</div>
        
        <div class="grid_12"><p><?php echo $text_powered_by; ?></p></div>
        
    </footer>
</div>
<?php if (count($javascripts) > 0) foreach ($javascripts as $js) { if (empty($js)) continue; ?><script type="text/javascript" src="<?php echo $js; ?>"></script><?php } ?>
<div id="jsWrapper"></div>
<?php if ($scripts) echo $scripts; ?>
<script>
$(function() {
    <?php if ($this->session->get('necoexp')) { ?>
    $.growl({
        title: '<?php echo $Language->get("Congratulations!"); ?>',
        message: "NecoExp <?php echo $this->session->get('necoexp'); ?>",
        style: 'necoexp',
        icon: 'star',
        duration:2600
    });
    <?php $this->session->clear('necoexp'); ?>
    <?php } ?>
    
    <?php if ($this->session->get('necopoints')) { ?>
    $.growl({
        title: '<?php echo $Language->get("Congratulations!"); ?>',
        message: "NecoPoints <?php echo $this->session->get('necopoints'); ?>",
        style: 'necopoints',
        icon: 'send',
        duration:3000
    });
    <?php $this->session->clear('necopoints'); ?>
    <?php } ?>
    
    <?php if ($this->session->get('level_upgraded')) { ?>
    $.growl({
        title: '<?php echo $Language->get("Congratulations!"); ?>',
        message: 'New Level: <?php echo $this->session->get('level_upgraded'); ?>',
        style: 'new-level'
    });
    <?php $this->session->clear('level_upgraded'); ?>
    <?php } ?>
});
</script>
<div id="fb-root"></div>
<script type="text/javascript">
    <?php if ($google_analytics_code) { ?>
    var _gaq=[['_setAccount','<?php echo $google_analytics_code; ?>'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
    <?php } ?>
    
$(function() {
    var self;
    var that;
    $('.nt-dd1 p').on('click',function(e) {
        self = $(this).closest('.nt-dd1');
        that = $(self).find('ul:eq(0)');
        $('.nt-dd1 ul').each(function(){
            if ($(that) != $(this)) 
                $(this).removeClass('on').slideUp("fast");
        });
        $(that).toggleClass('on').slideToggle("fast");
        e.stopPropagation();
    });
    $('body').on('click',function(e){
        father = $(e.target).closest('.nt-dd1');
        if ($(father).length == 0)
            $('.nt-dd1 ul').removeClass('on').slideUp("fast");
    });
    
});
</script>
</body></html>