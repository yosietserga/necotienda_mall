<li id="module_cart" class="nt-editable box cartWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
    <?php if ($products) { ?>
        <?php foreach ($products as $product) { ?>
        <div class="cartProduct">
            <span class="cartRemove" id="remove_<?php echo $product['key']; ?>">&nbsp;</span>
            <?php echo $product['quantity']; ?>&nbsp;x&nbsp;
            <a title="<?php echo $product['name']; ?>" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $product['name']; ?></a>
            <?php foreach ($product['option'] as $option) { ?>
                <div class="cartProductOption">- <?php echo $option['name']; ?> <?php echo $option['value']; ?></div>
            <?php } ?>
        </div>
        <?php } ?>
        <br />
        <?php foreach ($totals as $total) { ?>
            <div class="cartWidgetTotal"><b><?php echo $total['title']; ?></b></div>
            <div class="cartWidgetTotal"><?php echo $total['text']; ?></div>
            <div class="clear"></div>
        <?php } ?>
        <div class="cartLinks">
            <a title="<?php echo $text_view; ?>" href="<?php echo $view; ?>"><?php echo $text_view; ?></a> | 
            <a title="<?php echo $text_checkout; ?>" href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a>
        </div>
    <?php } else { ?>
        <div style="text-align: center;"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
    <div class="clear"></div><br />
</li>