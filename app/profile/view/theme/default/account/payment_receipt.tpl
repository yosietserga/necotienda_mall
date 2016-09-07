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
        
        <div class="grid_8">
            <img src="<?php echo HTTP_IMAGE . $Config->get('config_logo'); ?>" alt="<?php echo $Config->get('config_name'); ?>" /><br />
            <?php echo $Config->get('config_owner'); ?><br />
            <?php echo $Config->get('config_rif'); ?><br />
            <?php echo $Config->get('config_address'); ?>
        </div>
        <div class="grid_4">
            Control N&deg; <?php echo $order_payment_id; ?><br />
            Pedido N&deg; <?php echo $order_id; ?><br />
            Fecha de Emisi&oacute;n <?php echo date('d-m-Y h:i A',strtotime($date_added)); ?>
        </div>
        
        <div class="clear"></div><hr /><br /><br />
        
        <p>Hemos recibido un pago realizado por <?php echo $payment_firstname ." ". $payment_lastname; ?> por la cantidad de <?php echo $amount; ?> por concepto del pago/abono del pedido <?php echo $order_id; ?> con un total de <?php echo $total; ?>.</p>
        <p>El pago fue realizado a tr&aacute;ves del m&eacute;todo <b><?php echo ucfirst($payment_method); ?></b></p>
        
        <div class="clear"></div><br /><br />
        
        <h2>Datos del Pedido</h2>
        <table>
            <tr>
                <td>Pedido ID</td>
                <td><b><?php echo $order_id; ?></b></td>
            </tr>
            <tr>
                <td>Total</td>
                <td><b><?php echo $total; ?></b></td>
            </tr>
        </table>
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>