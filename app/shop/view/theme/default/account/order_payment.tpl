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

                <div class="clear"></div><br />

                <div class="sort">
                    <input type="text" name="filter_order" id="filter_order" value="" placeholder="Buscar Pedido..." />
                    <?php echo $text_sort; ?>
                    <a href="#" id="filter" class="button" style="padding: 3px 4px;">Filtrar</a>
                </div> 

                <div class="clear"></div><br />

                <ul id="paymentMethods" class="nt-editable">
                <?php foreach ($payment_methods as $payment_method) { ?>
                    <li>
                        <a id="<?php echo $payment_method['id']; ?>" title="<?php echo $payment_method['title']; ?>"><?php echo $payment_method['title']; ?></a>
                        {%<?php echo $payment_method['id']; ?>%}
                    </li>
                <?php } ?>
                </ul>
    
            </div>
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>