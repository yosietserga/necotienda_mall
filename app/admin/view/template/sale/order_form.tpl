<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
<div class="box">
    <h1><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons">
        <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
        <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
        <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
        <a onclick="window.open('<?php echo $invoice; ?>');" class="button"><?php echo $Language->get('button_invoice'); ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
    </div>
    
    <div class="clear"></div>
    
    <ul id="vtabs" class="vtabs">
        <li><a data-target="#tab_order" onclick="showTab(this)"><?php echo $Language->get('tab_order'); ?></a></li>
        <li><a data-target="#tab_product" onclick="showTab(this)"><?php echo $Language->get('tab_product'); ?></a></li>
        <li><a data-target="#tab_shipping" onclick="showTab(this)"><?php echo $Language->get('tab_shipping'); ?></a></li>
        <li><a data-target="#tab_payment" onclick="showTab(this)"><?php echo $Language->get('tab_payment'); ?></a></li>
        <li><a data-target="#tab_history" onclick="showTab(this)"><?php echo $Language->get('tab_history'); ?></a></li>
    </ul>
    
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
    
        <div class="vtabs_page" id="tab_order"><?php require_once(dirname(__FILE__)."/order_form_order.tpl"); ?></div>
        <div class="vtabs_page" id="tab_product"><?php require_once(dirname(__FILE__)."/order_form_product.tpl"); ?></div>
        <div class="vtabs_page" id="tab_shipping"><?php require_once(dirname(__FILE__)."/order_form_shipping.tpl"); ?></div>
        <div class="vtabs_page" id="tab_payment"><?php require_once(dirname(__FILE__)."/order_form_payment.tpl"); ?></div>
        <div class="vtabs_page" id="tab_history"><?php require_once(dirname(__FILE__)."/order_form_history.tpl"); ?></div>
        
    </form>
    
</div>
<?php echo $footer; ?>