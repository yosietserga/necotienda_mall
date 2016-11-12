<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    <section id="maincontent">
        <section id="content">
            <div class="grid_12">
                <div id="featuredContent">
                <?php if($featuredWidgets) { ?><ul class="widgets"><?php foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
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
                <?php echo $description; ?>

                <div class="clear"></div>
                <div id="postSocial"></div>
                <div class="clear"></div>

                <?php if ($allow_reviews) { ?>
                <div id="comments">
                    <div id="comment" class="box nt-editable"><img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' /></div>
                    <div class="clear"></div>
                    <div id="review" class="content nt-editable"><img src='<?php echo HTTP_IMAGE; ?>data/loader.gif' alt='Cargando...' /></div>
                </div>
                <?php } ?>
                <div class="clear"></div>
                <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
                <div class="clear"></div>
            </div>

            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>

            <div class="clear"></div>

        </section>
    </section>
</div>
<script type="text/javascript" src="<?php echo HTTP_JS; ?>necojs/neco.carousel.js"></script>
<script>
$(window).load(function(){
    <?php if ($related) { ?>
    $("#related").ntCarousel({
        url:'<?php echo $Url::createUrl("content/page/relatedJson") ."&page_id=". $page_id; ?>',
        image: {
          width:80,
          height:80  
        },
        loading: {
          image: '<?php echo HTTP_IMAGE; ?>loader.gif'
        },
        options: {
            scroll: 1
        }
    });
    <?php } ?>
    
    $('#review').load('<?php echo $Url::createUrl("content/page/review") ."&page_id=". $page_id; ?>');
    $('#comment').load('<?php echo $Url::createUrl("content/page/comment") ."&page_id=". $page_id; ?>');
    
    var html = '';
    
    html += '<div class="grid_1" style="margin-right: 25px;">';
    html += '<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo str_replace("&","&amp;",$Url::createUrl("content/post",array('post_id'=>$post_id))); ?>" data-count="vertical" data-via="lahoralocavzla" data-related="lahoralocavzla:Confites y Accesorios para Fiestas" data-lang="es">Tweet</a>';
    html += '<script type="text\/javascript" src="\/\/platform.twitter.com\/widgets.js"><\/script>';
    html += '</div>';
    
    html += '<div class="grid_1">';
    html += '<script type="text\/javascript" src="https:\/\/apis.google.com\/js\/plusone.js">{lang: \'es-419\'}<\/script>';
    html += '<g:plusone size="tall" callback="googleMas1" href="<?php echo str_replace("&","&amp;",$Url::createUrl("content/post",array('post_id'=>$post_id))); ?>"></g:plusone>';
    html += '</div>';
    
    html += '<div class="grid_1" style="margin-right: 30px;">';
    html += '<div class="fb-like" data-href="<?php echo str_replace("&","&amp;",$Url::createUrl("content/post",array('post_id'=>$post_id))); ?>" data-layout="box_count" data-width="450" data-show-faces="true" data-font="verdana"></div>';
    html += '</div>';
    
    html += '<div class="grid_1" style="margin-left: 15px;">';
    html += '<a href="http://pinterest.com/pin/create/button/?url=<?php echo rawurlencode($Url::createUrl("content/post",array('post_id'=>$post_id))); ?>&media=<?php echo rawurlencode($image); ?>&description=<?php echo rawurlencode($description); ?>" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
    html += '</div>';
    
    $('#postSocial').append(html);
});
</script>
<?php echo $footer; ?>