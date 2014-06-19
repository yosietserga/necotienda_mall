<?php if ($products) { ?>
<li class="nt-editable box randomWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <?php foreach ($products as $product) { ?>
            <a title="<?php echo $product['name']; ?>" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" class="thumb"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></a>
            <a title="<?php echo $product['name']; ?>" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $product['name']; ?></a>
            <?php if ($display_price) { ?>
                <?php if (!$product['special']) { ?>
                    <p class="price"><?php echo $product['price']; ?></p>
                <?php } else { ?>
                    <p class="old_price"><?php echo $product['price']; ?></p> 
                    <p class="new_price"><?php echo $product['special']; ?></p>
                <?php } ?>
            <a title="<?php echo $button_see_product; ?>" class="button_see_small" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $button_see_product; ?></a>
            <a title="<?php echo $button_add_to_cart; ?>" class="button_add_small" href="<?php echo str_replace('&', '&amp;', $product['add']); ?>"><?php echo $button_add_to_cart; ?></a>
            <?php } ?>
            <?php if ($product['rating']) { ?>
                <img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>">
            <?php } ?>
        <?php } ?>
    </div>
    <div class="clear"></div><br />
</li>
<?php } ?>