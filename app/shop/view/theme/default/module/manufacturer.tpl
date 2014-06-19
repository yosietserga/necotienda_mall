<li class="nt-editable box manufacturerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <select onchange="location = this.value">
            <option value=""><?php echo $Language->get('text_select'); ?></option>
            <?php foreach ($manufacturers as $manufacturer) { ?>
                <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                    <option value="<?php echo str_replace('&', '&amp;', $manufacturer['href']); ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                <?php } else { ?>
                    <option value="<?php echo str_replace('&', '&amp;', $manufacturer['href']); ?>"><?php echo $manufacturer['name']; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <div class="clear"></div><br />
</li>
