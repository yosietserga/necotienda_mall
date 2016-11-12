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
                <div class="clear"></div>
                <?php if ($description) { ?><p><?php echo $description; ?></p><?php } ?>

                <?php if($categories) { ?>
                <nav class="content">
                    <ul class="category_view">
                    <?php foreach($categories as $category) { ?>
                        <li>
                            <a class="name" href="<?php echo str_replace('&', '&amp;', $category['href']); ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
                        </li>
                    <?php } ?>
                    </ul>
                </nav>
                <?php } ?>

                <div class="clear"></div>

                <div id="products"><img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' /></div>

                <div class="clear"></div>

                <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>

                <div class="clear"></div>

            </div>
                
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script>$("#products").load("index.php?r=store/category/home&category_id=<?php echo $category_id; ?>")</script>
<?php echo $footer; ?>