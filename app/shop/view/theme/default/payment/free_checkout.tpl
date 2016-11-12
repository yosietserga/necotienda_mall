<a title="<?php echo $Language->get('button_pay'); ?>" id="freeCheckout" class="button"><?php echo $Language->get('button_pay'); ?></a>
<script type="text/javascript">
$('#freeCheckout').click(function() {
	$.post('<?php echo $Url::createUrl("payment/freeCheckout/confirm"); ?>',
        {
            
        },
		function(response) {
            data = $.parseJSON(response);
            if (data.success) {
                
            }
            if (data.error) {
            
            }
		});
});
</script>