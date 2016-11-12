<div id="languages" class="htabs2">
<?php foreach ($languages as $language) { ?>
    <a tab="#language<?php echo $language['language_id']; ?>" class="htab2"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
<?php } ?>
</div> 
<?php foreach ($languages as $language) { ?>
<div id="language<?php echo $language['language_id']; ?>">
                
    <div class="row">
        <label><?php echo $Language->get('entry_name'); ?></label>
        <input id="description_<?php echo $language['language_id']; ?>_name" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" required="true" title="<?php echo $Language->get('help_name'); ?>" style="width: 40%;" />
    </div>
                        
    <div class="clear"></div>
                        
    <div class="row">
        <label><?php echo $Language->get('entry_meta_keywords'); ?></label>
        <textarea title="<?php echo $Language->get('help_meta_keywords'); ?>" name="product_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="40" rows="5" style="width: 40%;"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea>
    </div>
                        
    <div class="clear"></div>
                        
    <div class="row">
        <label><?php echo $Language->get('entry_meta_description'); ?></label>
        <textarea title="<?php echo $Language->get('help_meta_description'); ?>" name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5" style="width: 40%;"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
    </div>
                        
    <div class="clear"></div>
                        
    <div class="row">
        <label><?php echo $Language->get('entry_description'); ?></label>
        <div class="clear"></div>
        <textarea title="<?php echo $Language->get('help_description'); ?>" name="product_description[<?php echo $language['language_id']; ?>][description]" id="description_<?php echo $language['language_id']; ?>_description"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
    </div>
    
    <div class="clear"></div>
                    
    <div class="row">
        <label><?php echo $Language->get('entry_keyword'); ?>(<b style="font:normal 10px verdana;color:#999;"><?php echo HTTP_CATALOG; ?></b>)</label>
        <input type="text" id="description_<?php echo $language['language_id']; ?>_keyword" title="<?php echo $Language->get('help_keyword'); ?>" name="product_description[<?php echo $language['language_id']; ?>][keyword]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['keyword'] : ''; ?>" />
    </div>
                
    <!--    
    <div class="clear"></div>
                       
    <div class="row">
        <label><?php echo $Language->get('entry_tags'); ?></label>
        <input title="<?php echo $Language->get('help_tags'); ?>" name="product_tags[<?php echo $language['language_id']; ?>]" value="<?php echo isset($product_tags[$language['language_id']]) ? $product_tags[$language['language_id']] : ''; ?>" style="width: 40%;" />
    </div>
                        
    <div class="clear"></div>
    -->
</div>
<?php } ?>      