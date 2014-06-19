<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">

    <section id="content">
    
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
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
        
    </section>
    
</section>
<?php echo $footer; ?>