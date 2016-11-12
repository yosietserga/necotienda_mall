<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
<div class="box">
    <h1><?php echo $Language->get('heading_title'); ?></h1>
    <div class="buttons">
        <a onclick="saveAndExit();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_exit'); ?></a>
        <a onclick="saveAndKeep();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_keep'); ?></a>
        <a onclick="saveAndNew();$('#form').submit();" class="button"><?php echo $Language->get('button_save_and_new'); ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $Language->get('button_cancel'); ?></a>
    </div>
        
    <div class="clear"></div>
                                
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="htabs">
            <a tab="#general" class="htab"><?php echo $Language->get('tab_general'); ?></a>
            <a tab="#data" class="htab"><?php echo $Language->get('tab_data'); ?></a>
            <a tab="#links" class="htab"><?php echo $Language->get('tab_links'); ?></a>
            <a tab="#discount" class="htab"><?php echo $Language->get('tab_discount'); ?></a>
            <a tab="#special" class="htab"><?php echo $Language->get('tab_special'); ?></a>
            <a tab="#option" class="htab"><?php echo $Language->get('tab_option'); ?></a>
            <a tab="#images" class="htab"><?php echo $Language->get('tab_image'); ?></a>
        </div>
        
        <div id="general"><?php require_once(dirname(__FILE__)."/product_form_general.tpl"); ?></div>
        <div id="data"><?php require_once(dirname(__FILE__)."/product_form_data.tpl"); ?></div>
        <div id="links"><?php require_once(dirname(__FILE__)."/product_form_links.tpl"); ?></div>
        <div id="discount"><?php require_once(dirname(__FILE__)."/product_form_discount.tpl"); ?></div>
        <div id="special"><?php require_once(dirname(__FILE__)."/product_form_special.tpl"); ?></div>
        <div id="option"><?php require_once(dirname(__FILE__)."/product_form_option.tpl"); ?></div>
        <div id="images"><?php require_once(dirname(__FILE__)."/product_form_images.tpl"); ?></div>
        
    </form>
</div>
<script>
$(function(){
    $('.product_tab').hide();
    $('#general').show();
    $('.product_tabs .htab').on('click',function(e){
        $('.product_tab').hide();
        $($(this).attr('data-target')).show();
    });  
});
</script>
<?php echo $footer; ?>