<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
            <h1><?php echo $heading_title; ?></h1>
            <p><?php echo $text_message; ?></p>
              
            <div class="clear"></div>
            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
            <div class="clear"></div>
            
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>