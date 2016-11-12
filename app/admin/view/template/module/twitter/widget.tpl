<form id="<?php echo $name; ?>_form">
    <p style="text-align:center;font-size:10px;">{%<?php echo $name; ?>%}</p>
    <div class="row">
        <label><?php echo $Language->get('entry_title'); ?></label>
        <input name="Widgets[<?php echo $name; ?>][settings][title]" value="<?php echo isset($settings['title']) ? $settings['title'] : $Language->get('heading_title'); ?>" />
    </div>
    
    <div class="row">
        <label><?php echo $Language->get('entry_class'); ?></label>
        <input name="Widgets[<?php echo $name; ?>][settings][class]" value="<?php echo isset($settings['class']) ? $settings['class'] : ''; ?>" />
    </div>
    
    <div class="row">
        <label><?php echo $Language->get('entry_load'); ?></label>
        <input type="checkbox" name="Widgets[<?php echo $name; ?>][settings][autoload]" value="1" checked="checked" />
    </div>
    
    <div class="row">
        <label><?php echo $Language->get('entry_twitter_search'); ?></label>
        <input title="<?php echo $Language->get('help_twitter_search'); ?>" name="Widgets[<?php echo $name; ?>][settings][twitter_search]" type="text" size="20" required="required" value="<?php echo $settings['twitter_search'] ?>" />
    </div>
                   
    <div class="row">
        <label><?php echo $Language->get('entry_twitter_time'); ?></label>
        <input title="<?php echo $Language->get('help_twitter_time'); ?>" name="Widgets[<?php echo $name; ?>][settings][twitter_time]" type="text" size="20" required="required" value="<?php echo $settings['twitter_search'] ?>" />
    </div>
                   
    <div class="row">
        <label><?php echo $Language->get('entry_landing_page'); ?></label>
        <ul class="scrollbox">
            <li>
                <input type="checkbox" name="Widgets[<?php echo $name; ?>][landing_page][]" value="all"<?php if (empty($landing_pages)) { ?> checked="checked"<?php } ?> showquick="off" onclick="$('input.<?php echo $name; ?>_landing_pages').attr({'checked':this.checked,'disabled':this.checked});" />
                <b>Todo el sitio</b>
            </li>
        <?php foreach ($routes as $text_var => $landing_page) { ?>
            <li>
                <input class="<?php echo $name; ?>_landing_pages" type="checkbox" name="Widgets[<?php echo $name; ?>][landing_page][]" value="<?php echo $landing_page; ?>"<?php if (in_array($landing_page, $landing_pages)) { ?> checked="checked"<?php } elseif (empty($landing_pages)) { ?> checked="checked" disabled="disabled"<?php } ?> showquick="off" />
                <b><?php echo $Language->get($text_var); ?></b>
            </li>
        <?php } ?>
        </ul>
    </div>
    <input type="hidden" name="Widgets[<?php echo $name; ?>][settings][route]" value="module/twitter" />
</form>