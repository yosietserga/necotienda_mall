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
        
                <h1><?php echo $heading_title; ?></h1>
      
                <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="forgotten">
                    <p><?php echo $text_email; ?></p>
                    <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_email; ?></b>
                    
                    <div class="row">
                        <input type="email" name="email" autocomplete="off" placeholder="Ingresa tu email" />
                    </div>

                    <div class="clear"></div>

                     
                        <a title="<?php echo $Language->get('button_continue'); ?>" onclick="$('#forgotten').submit();" class="button">
                            <?php echo $Language->get('button_continue'); ?>
                        </a>

                </form>
            </div>
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
            
        </section>
    </section>
</div>
<?php echo $footer; ?> 