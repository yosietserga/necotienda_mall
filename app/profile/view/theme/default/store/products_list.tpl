<?php if($products) { ?>
    <ul>
<?php foreach($productsByCategory as $category) { ?>
        <li><a href="<?php echo $category['url']; ?>"><?php echo $category['name']; ?> (<?php echo $category['total']; ?>)</a></li>
<?php } ?>
<?php foreach($productsByBrand as $brand) { ?>
        <li><a href="<?php echo $brand['url']; ?>"><?php echo $brand['name']; ?> (<?php echo $brand['total']; ?>)</a></li>
<?php } ?>
<?php foreach($productsByTag as $tag) { ?>
        <li><a href="<?php echo $tag['url']; ?>"><?php echo $tag['tag']; ?> (<?php echo $tag['total']; ?>)</a></li>
<?php } ?>
    </ul>
<div class="content">

<?php if ($sort) { ?>
    <div class="sort">
        <select name="sort" <?php echo (array_key_exists('ajax',$sorts[0])) ? "onchange='sort(this,this.value)' " : "onchange='window.location = this.value'"; ?>>
          <?php foreach ($sorts as $sorted) { ?>
              <?php if (($sort . '-' . $order) == $sorted['value']) { ?>
                <option value="<?php echo $sorted['href']; ?>" selected="selected"><?php echo $sorted['text']; ?></option>
              <?php } else { ?>
                <option value="<?php echo $sorted['href']; ?>"><?php echo $sorted['text']; ?></option>
              <?php } ?>
          <?php } ?>
        </select>
      <?php echo $Language->get('text_sort'); ?>
      
          <?php if (isset($_GET['v']) && $_GET['v']=='list') { ?>
          <a class="view_style" onclick="$('#products').html('<img src=\'<?php echo HTTP_IMAGE; ?>loader.gif\' alt=\'Cargando...\' />');$('#products').load('<?php echo str_replace('&', '&amp;', $gridView); ?>')" title="Ver en Miniaturas"  style="background-position:0 -23px">&nbsp;</a>    
          <?php } else { ?>
          <a class="view_style" onclick="$('#products').html('<img src=\'<?php echo HTTP_IMAGE; ?>loader.gif\' alt=\'Cargando...\' />');$('#products').load('<?php echo str_replace('&', '&amp;', $listView); ?>')" title="Ver en Listado">&nbsp;</a>    
          <?php } ?>
    </div> 
<?php } ?>

<?php if(isset($_GET['v']) && $_GET['v'] == 'list') { ?>
    <div class="list_view">
        <?php $class = "even"; ?>
        <?php foreach($products as $product) { ?>
        <article>
        <div class="product_preview <?php echo $class; ?>">
        <?php $class = ($class=="even") ? "odd" : "even"; ?>
            <div class="left">
                <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>" class="thumb"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
                <?php if ($product['rating']) { ?>
                    <img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>" />
                <?php } ?>
            </div>
            <div class="center">
                <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>" class="name"><?php echo $product['name']; ?></a>
                <p class="model"><?php echo $product['model']; ?></p>
                <p class="overview"><?php echo substr($product['overview'],0,150)." ... "; ?><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>">Leer M&aacute;s</a></p>
            </div>
            <div class="right">
                <?php if ($display_price) { ?>
                    <?php if (!$product['special']) { ?>
                        <p class="price"><?php echo $product['price']; ?></p>
                    <?php } else { ?>
                        <p class="old_price"><?php echo $product['price']; ?></p> 
                        <p class="new_price"><?php echo $product['special']; ?></p>
                    <?php } ?>
                <?php } ?>
                <a title="<?php echo $Language->get('button_see_product'); ?>" class="button_see_small" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $Language->get('button_see_product'); ?></a>
                <a title="<?php echo $Language->get('button_add_to_cart'); ?>" class="button_add_small" href="<?php echo str_replace('&', '&amp;', $product['add']); ?>"><?php echo $Language->get('button_add_to_cart'); ?></a>
            </div>
        </div>
        </article>
    <?php } ?>
    </div>
<?php } else { ?>
    <div class="grid_view">
    <?php foreach($products as $product) { ?>
        <article>
        <div class="product_preview">
            <a class="thumb" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
            <a class="name" href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
            <?php if ($product['rating']) { ?>
                <br /><img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo $product['rating'] . '.png'; ?>" alt="<?php echo $product['stars']; ?>" />
          <?php } ?>
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
        </div>
        </article>
    <?php } ?>
    </div>
    <div class="clear"></div>
    <?php if ($pagination) { ?> 
        <div class="pagination"><?php echo $pagination; ?></div>
    <?php } ?>
<?php } ?>
</div>
<script>
function sort(e,a) {
    if (a.length > 0) {
        $('#products').html('<img src=\'<?php echo HTTP_IMAGE; ?>loader.gif\' alt=\'Cargando...\' />');
        $("#products").load(a);
    }
}
</script>
<?php } ?>