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

            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>
            
                <?php if($categories) { ?>
		<section id="grid" class="grid clearfix">
                    <?php foreach($categories as $category) { ?>
                    <a href="<?php echo str_replace('&', '&amp;', $category['href']); ?>" data-path-hover="M 0,0 0,38 90,58 180.5,38 180,0 z">
                        <figure>
                            <img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" />
                            <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 0 0 L 0 182 L 90 126.5 L 180 182 L 180 0 L 0 0 z "/></svg>
                            <figcaption>
                                <h2><?php echo $category['name']; ?></h2>
                            </figcaption>
                        </figure>
                    </a>
                    <?php } ?>
                </section>
                <?php } ?>
                
                <div class="clear"></div>
                <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
                <div class="clear"></div>
            
            </div>
                
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>