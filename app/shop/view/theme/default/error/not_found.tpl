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

            <div class="grid_12">
                <ul id="breadcrumbs" class="nt-editable">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
                </ul>
            </div>

            <div class="clear"></div><br /><br />
        
            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>
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
                
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
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