<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">

    <section id="content">
    
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
            <h1><?php echo $heading_title; ?></h1>
            
            <div class="grid_3" style="float: right !important;">
                <a onclick="window.print();" class="button">Imprimir</a>
                <?php //TODO: <a onclick="window.print();" class="button">PDF</a> ?>
            </div>
    
            <div class="clear"></div><br />
            
            <div class="grid_9">
                <h3><?php echo $text_payment_address; ?></h3>
                <p><?php echo $payment_address; ?></p>
                
                <div class="clear"></div><br />
                
                <?php if ($payment_method) { ?>
                <h3><?php echo $text_payment_method; ?></h3>
                <p><?php echo $payment_method; ?></p>
                <?php } ?>
                
                <div class="clear"></div><br />
                
                <?php if ($shipping_address) { ?>
                <h3><?php echo $text_shipping_address; ?></h3>
                <p><?php echo $shipping_address; ?></p>
                <div class="clear"></div><br />
                <?php } ?>
      
                <?php if ($shipping_method) { ?>
                <h3><?php echo $text_shipping_method; ?></h3>
                <p><?php echo $shipping_method; ?></p>
                <div class="clear"></div><br />
                <?php } ?>
            </div>
            
            <div class="grid_3" style="float: right !important;">
                <?php if ($invoice_id) { ?><p><b><?php echo $text_invoice_id; ?></b>: <?php echo $invoice_id; ?></p><?php } ?>
                <p><b><?php echo $text_order_id; ?></b>: #<?php echo $order_id; ?></p>
                <p><b><?php echo $column_date_added; ?></b>: <?php echo $histories[(count($histories)-1)]['date_added']; ?></p>
                <p><b><?php echo $column_status; ?></b>:<?php echo $histories[(count($histories)-1)]['status']; ?></p>
            </div>
            
            <div class="clear"></div><br />
            
            <div class="grid_13">
                <h3>Detalle de la Facturaci&oacute;n</h3>
                <table>
                    <tr class="order_table_header">
                        <th><?php echo $text_product; ?></th>
                        <th><?php echo $text_model; ?></th>
                        <th><?php echo $text_quantity; ?></th>
                        <th><?php echo $text_price; ?></th>
                        <th><?php echo $text_total; ?></th>
                    </tr>
                    <?php foreach ($products as $product) { ?>
                    <tr>
                        <td style="text-align:left">&nbsp;<a title="<?php echo $product['name']; ?>" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $product['name']; ?></a>
                        <?php foreach ($product['option'] as $option) { ?>
                        <br>
                        &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
                        <?php } ?></td>
                        <td><?php echo $product['model']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                    </tr>
                    <?php } ?>
                  </table>
            </div>
            
            <div class="grid_3" style="float: right !important;">
                <table id="orderTotals">
                    <?php foreach ($totals as $total) { ?>
                    <tr>
                        <td><b><?php echo $total['title']; ?></b></td>
                        <td><?php echo $total['text']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            
            <div class="clear"></div>
            
            <?php if ($comment) { ?>
            <div class="grid_13">
                <h3><?php echo $text_comment; ?></h3>
                <p><?php echo $comment; ?></p>
            </div>
            <?php } ?>
            
            <div class="clear"></div>
            
            <?php if ($histories) { ?>
            <div class="grid_13">
                <div class="clear"></div><br />
                <h3><?php echo $text_order_history; ?></h3>
                <?php foreach ($histories as $history) { ?>
                <div class="grid_2">
                    <?php echo $history['status']; ?><br />
                    <?php echo $history['date_added']; ?>
                </div>
                <div class="grid_9">
                    <?php echo $history['comment']; ?>
                </div>
                <div class="clear"></div><hr />
                <?php } ?>
            </div>
            <?php } ?>
            
      </div>
        
    </section>
    
</section>
<?php echo $footer; ?>