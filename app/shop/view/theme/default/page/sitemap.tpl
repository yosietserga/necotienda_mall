<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    <section id="maincontent">
        <section id="content">
            <div class="grid_12">
                <div id="featuredContent">
                <ul class="widgets"><?php if($featuredWidgets) { foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } } ?></ul>
                </div>
            </div>

            <div class="clear"></div>

            <div class="grid_12">
                <ul id="breadcrumbs" class="nt-editable">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
                </ul>
            </div>

            <div class="clear"></div><br /><br />
        
            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>
                <h1><?php echo $heading_title; ?></h1>

                <div class="clear"></div><br />

                <div class="box" id="wrapperLi">
                    <ul id="sitemap">
                        <li>
                            <a title="<?php echo $Language->get('text_account'); ?>" href="<?php echo str_replace('&', '&amp;', $account); ?>"><?php echo $Language->get('text_account'); ?></a>
                            <span>
                                <p><a title="<?php echo $Language->get('text_edit'); ?>" href="<?php echo str_replace('&', '&amp;', $edit); ?>"><?php echo $Language->get('text_edit'); ?></a></p>
                                <p><a title="<?php echo $Language->get('text_password'); ?>" href="<?php echo str_replace('&', '&amp;', $password); ?>"><?php echo $Language->get('text_password'); ?></a></p>
                                <p><a title="<?php echo $Language->get('text_address'); ?>" href="<?php echo str_replace('&', '&amp;', $address); ?>"><?php echo $Language->get('text_address'); ?></a></p>
                                <p><a title="<?php echo $Language->get('text_history'); ?>" href="<?php echo str_replace('&', '&amp;', $history); ?>"><?php echo $Language->get('text_history'); ?></a></p>
                                <p><a title="<?php echo $Language->get('text_download'); ?>" href="<?php echo str_replace('&', '&amp;', $download); ?>"><?php echo $Language->get('text_download'); ?></a></p>
                            </span>
                        </li>

                        <li><a title="<?php echo $Language->get('text_special'); ?>" href="<?php echo str_replace('&', '&amp;', $special); ?>"><?php echo $Language->get('text_special'); ?></a></li>
                        <li><a title="<?php echo $Language->get('text_cart'); ?>" href="<?php echo str_replace('&', '&amp;', $cart); ?>"><?php echo $Language->get('text_cart'); ?></a></li>
                        <li><a title="<?php echo $Language->get('text_checkout'); ?>" href="<?php echo str_replace('&', '&amp;', $checkout); ?>"><?php echo $Language->get('text_checkout'); ?></a></li>
                        <li><a title="<?php echo $Language->get('text_search'); ?>" href="<?php echo str_replace('&', '&amp;', $search); ?>"><?php echo $Language->get('text_search'); ?></a></li>
                        <li><a title="<?php echo $Language->get('text_contact'); ?>" href="<?php echo str_replace('&', '&amp;', $contact); ?>"><?php echo $Language->get('text_contact'); ?></a></li>
                        <li>
                            <a title="<?php echo $Language->get('text_post_categories'); ?>" href="<?php echo str_replace('&', '&amp;', $Url::createUrl("content/category")); ?>"><?php echo $Language->get('text_post_categories'); ?></a>
                            <span>
                                <?php foreach ($post_categories as $result) { ?><p><a title="<?php echo $result['title']; ?>" href="<?php echo str_replace('&', '&amp;', $Url::createUrl("content/category",array('category_id'=>$result['category_id']))); ?>"><?php echo $result['title']; ?></a></p><?php } ?>
                            </span>
                        </li>

                        <li>
                            <a title="<?php echo $Language->get('text_pages'); ?>" href="<?php echo str_replace('&', '&amp;', $Url::createUrl("content/page/all")); ?>"><?php echo $Language->get('text_pages'); ?></a>
                            <span>
                                <?php foreach ($pages as $result) { ?><p><a title="<?php echo $result['title']; ?>" href="<?php echo str_replace('&', '&amp;', $Url::createUrl("content/page",array('page_id'=>$result['post_id']))); ?>"><?php echo $result['title']; ?></a></p><?php } ?>
                            </span>
                        </li>

                    </ul>
                </div>

                <div class="clear"></div>
                <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
                <div class="clear"></div>
            
            </div>
                
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<script>
$(function(){
    $('#sitemap li').wookmark({
        autoResize: true,
        container: $('#wrapperLi'),
        offset: 2,
        itemWidth: 200
      });
});
</script>
<?php echo $footer; ?>