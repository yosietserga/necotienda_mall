<?php if (!empty($instructions)) { ?>
<a id="bankTransferInstructions"><?php echo $Language->get('text_guide'); ?></a>
<div class="guide" id="bankTransferGuide">
    <?php echo $instructions; ?>
</div>
<?php } ?>
<a title="<?php echo $Language->get('button_pay'); ?>" id="bankTransferCheckout" class="button"><?php echo $Language->get('button_pay'); ?></a>