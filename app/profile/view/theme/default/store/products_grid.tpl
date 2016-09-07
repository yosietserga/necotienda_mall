<div class="header">
    <h1><?php echo $heading_title; ?></h1>
</div>
<?php if($products) { ?>
<div class="content">
<?php if ($sort) { ?>
    <div class="sort">
       <div style="font:bold 9px verdana;float:left;"><?php echo $Language->get('text_sort'); ?>&nbsp;</div>
        <?php foreach ($sorts as $key => $sorts) { 
            if ($key != 'text_default') {
                if (($sort . '-' . $order) == $sorts['value']) { ?>
          <span style="font:normal 9px verdana">&nbsp;<?php echo $sorts['text']; ?>&nbsp;</span>|
          <?php } else { ?>
          <a title="<?php echo $sorts['text']; ?>" href="<?php echo str_replace('&', '&amp;', $sorts['href']); ?>" style="font:normal 9px arial" onClick="location = this.value">&nbsp;<?php echo $sorts['text']; ?>&nbsp;</a>|
            <?php } ?>
          <?php } ?>
        <?php } ?>      
    </div>
<?php } ?>   
    <div class="grid_view">
    <?php foreach($products as $product) { ?>
        <div class="product_preview">
            <a class="thumb" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /><?php echo $product['sticker']; ?></a>
            <a class="name" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
            <p class="model"><?php echo $product['model']; ?></p>
            <?php if ($display_price) { ?>
                <?php if (!$product['special']) { ?>
                    <p class="price"><?php echo $product['price']; ?></p>
                <?php } else { ?>
                    <p class="old_price"><?php echo $product['price']; ?></p> 
                    <p class="special"><?php echo $product['special']; ?></p>
                <?php } ?>
            <?php } ?>
            <a title="<?php echo $Language->get('button_see_product'); ?>" class="button_see_small" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $Language->get('button_see_product'); ?></a>
            <a title="<?php echo $Language->get('button_add_to_cart'); ?>" class="button_add_small" href="<?php echo str_replace('&', '&amp;', $product['add']); ?>"><?php echo $Language->get('button_add_to_cart'); ?></a>
            <?php if ($product['rating']) { ?>
                <img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>" />
          <?php } ?>
        </div>
    <?php } ?>
    </div>
    <?php if ($pagination) { ?> 
        <div class="pagination"><?php echo $pagination; ?></div>
    <?php } ?>
</div>
<?php } ?>