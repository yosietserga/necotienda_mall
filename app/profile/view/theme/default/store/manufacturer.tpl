<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">
    <section id="content">
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
            
            <div class="clear"></div>
            
            <div id="products"><img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' /></div>
            <div class="clear"></div>
            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
            <div class="clear"></div>
            
        </div>
        
    </section>
    
</section>
<script>$("#products").load("index.php?r=store/manufacturer/home&manufacturer_id=<?php echo $manufacturer_id; ?>")</script>
<?php echo $footer; ?>