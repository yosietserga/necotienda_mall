<li class="nt-editable box categoryWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
    <?php if ($heading_title) { ?>
    <div class="header" id="<?php echo $widgetName; ?>Header">
        <hgroup>
            <h1><?php echo $heading_title; ?></h1>
        </hgroup>
    </div>
    <?php } ?>
    
    <div class="content" id="<?php echo $widgetName; ?>Content">
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
    </div>
    
    <div class="clear"></div><br />
</li>
