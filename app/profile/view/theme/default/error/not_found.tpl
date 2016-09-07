<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <?php if($featuredWidgets) { ?><div class="grid_16"><ul class="widgets"><?php foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul></div><?php } ?>
        <div class="grid_16">
            <ul id="breadcrumbs" class="nt-editable">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
            </ul>
        </div>
        
        <div class="clear"></div><br /><br />
        
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
            <h1><?php echo $heading_title; ?></h1>
            <p><?php echo $text_error; ?></p>
            
            <div class="clear" style="margin-bottom: 300px;"></div>
            
            <div class="box">
                <div class="content"><div id="newest"></div></div>
            </div>

            <div class="clear"></div>
            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
            <div class="clear"></div>
            
        </div>
        
    </section>
    
</section>
<script type="text/javascript" src="<?php echo HTTP_JS; ?>necojs/neco.carousel.js"></script>
 <script>
$(function(){
    $("#newest").ntCarousel({
        url:'<?php echo Url::createUrl("module/latest/carousel"); ?>',
        image: {
          width:130,
          height:100  
        },
        loading: {
          image: '<?php echo HTTP_IMAGE; ?>loader.gif'
        },
        options: {
            scroll: 1
        }
    });
});
</script>
<?php echo $footer; ?>