<a title="<?php echo $Language->get('button_pay'); ?>" id="codCheckout" class="button"><?php echo $Language->get('button_pay'); ?></a>
<script type="text/javascript">
$('#codCheckout').click(function() {
	$.post('<?php echo $Url::createUrl("payment/cod/confirm"); ?>',
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
