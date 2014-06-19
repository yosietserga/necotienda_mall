<a title="<?php echo $Language->get('button_pay'); ?>" id="chequeCheckoutCheckout" class="button"><?php echo $Language->get('button_pay'); ?></a><?php if (!empty($payable) || !empty($address)) { ?>
<div class="guide" id="chequeGuide">
    <p><b><?php echo $text_payable; ?><br /><?php echo $payable; ?></p>
    <div class="clear"></div>
    <form id="chequeCheckout" name="chequeCheckout" method="post">
    
        <div class="row">
            <label for="bank_account_id"><?php echo $Language->get('entry_cheque_bank_account'); ?></label>
            <select name="bank_account_id" id="bank_account_id" showquick="off">
                <option value="">Seleccione la Cuenta donde deposit&oacute;</option>
                <?php foreach ($bank_accounts as $bank_account) { ?>
                <option value="<?php echo $bank_account['bank_account_id']; ?>"><?php echo $bank_account['number']." - ". $bank_account['accountholder']; ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="transact"><?php echo $Language->get('entry_cheque_transact'); ?></label>
            <input type="text" name="transact" value="" placeholder="Ingrese el n&uacute;mero de dep&oacute;sito" required="required" />
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="date_added"><?php echo $Language->get('entry_cheque_date_added'); ?></label>
            <input type="date" name="date_added" value="" placeholder="Ingrese la fecha del dep&oacute;sito" required="required" />
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="amount"><?php echo $Language->get('entry_cheque_amount'); ?></label>
            <input type="money" name="amount" value="" placeholder="Ingrese el monto del dep&oacute;sito" required="required" />
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="order_id"><?php echo $Language->get('entry_cheque_order_id'); ?></label>
            <select name="order_id" showquick="off">
                <option value="">Seleccione el ID del Pedido</option>
                <?php foreach ($orders as $order) { ?>
                <option value="<?php echo $order['order_id']; ?>"<?php if ($_GET['order_id'] == $order['order_id']) echo ' selected="selected"'; ?>><?php echo "#". $order['order_id']." - ". $order['date_added'] ." - ". $order['total']; ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="clear"></div>
        
        <div class="row">
            <label for="comment"><?php echo $Language->get('entry_cheque_order_id'); ?></label>
            <textarea name="comment" placeholder="Ingresa tu comentario aqu&iacute;" showquick="off"></textarea>
        </div>
        
        <input type="hidden" name="payment_method" value="cheque" />
        
        <div class="clear"></div>
        
    </form>
</div>
<?php } ?>
<script type="text/javascript" src="<?php echo HTTP_JS; ?>necojs/neco.form.js"></script>
<script type="text/javascript">
$(function(){
    $('#chequeCheckout').ntForm({
        lockButton: false,
        ajax:true,
        url:'<?php echo $Url::createUrl("payment/cheque/confirm"); ?>',
        success:function(data) {
            if (typeof data.error != 'undefined' && typeof data.msg != 'undefined') {
                alert(data.msg);
            }
            if (typeof data.redirect != 'undefined') {
                window.location.href = data.redirect;
            }
        }
    });
    $('#chequeCheckout select').ntSelect();
    $('#chequeCheckout textarea').ntTextArea();
    $('#chequeCheckoutCheckout').on('click',function() {
        if ($('#chequeGuide').hasClass('on')) {
            $('#chequeGuide').removeClass('on').slideUp();
        } else {
            $('#chequeGuide').addClass('on').slideDown();
        }
    });
});
</script>