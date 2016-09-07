<a title="<?php echo $Language->get('button_pay'); ?>" id="debitCheckoutCheckout" class="button"><?php echo $Language->get('button_pay'); ?></a>
<?php if (!empty($payable) || !empty($address)) { ?>
<div class="guide" id="debitGuide">

    <p><?php echo $Language->get('text_amount_available'); ?></p>
    <h2><?php echo $balance['available']; ?></h2>
    
    <div class="clear"></div>
    
    <form id="debitCheckout" name="debitCheckout" method="post">
    
        <div class="row">
            <label for="amount_available"><?php echo $Language->get('text_amount_available'); ?></label>
            <input type="date" name="amount_available" value="" placeholder="Ingrese la fecha del dep&oacute;sito" required="required" />
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="amount"><?php echo $Language->get('entry_debit_amount'); ?></label>
            <input type="money" name="amount" value="" placeholder="Ingrese el monto del dep&oacute;sito" required="required" />
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="order_id"><?php echo $Language->get('entry_debit_order_id'); ?></label>
            <select name="order_id" showquick="off">
                <option value="">Seleccione el ID del Pedido</option>
                <?php foreach ($orders as $order) { ?>
                <option value="<?php echo $order['order_id']; ?>"<?php if ($_GET['order_id'] == $order['order_id']) echo ' selected="selected"'; ?>><?php echo "#". $order['order_id']." - ". $order['date_added'] ." - ". $order['total']; ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="comment"><?php echo $Language->get('entry_debit_order_id'); ?></label>
            <textarea name="comment" placeholder="Ingresa tu comentario aqu&iacute;" showquick="off"></textarea>
        </div>
        
        <input type="hidden" name="payment_method" value="debit" />
        
        <div class="clear"></div>
        
    </form>
</div>
<?php } ?>
<script type="text/javascript" src="<?php echo HTTP_JS; ?>necojs/neco.form.js"></script>
<script type="text/javascript">
$(function(){
    $('#debitCheckout').ntForm({
        lockButton: false,
        ajax:true,
        url:'<?php echo $Url::createUrl("payment/debit/confirm"); ?>',
        success:function(data) {
            if (typeof data.error != 'undefined' && typeof data.msg != 'undefined') {
                alert(data.msg);
            }
            if (typeof data.redirect != 'undefined') {
                window.location.href = data.redirect;
            }
        }
    });
    $('#debitCheckout select').ntSelect();
    $('#debitCheckout textarea').ntTextArea();
    $('#debitCheckoutCheckout').on('click',function() {
        if ($('#debitGuide').hasClass('on')) {
            $('#debitGuide').removeClass('on').slideUp();
        } else {
            $('#debitGuide').addClass('on').slideDown();
        }
    });
});
</script>