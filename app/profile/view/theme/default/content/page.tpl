<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <div class="grid_13">
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
        <aside id="column_right"><?php echo $column_right; ?></aside>
    </section>
    
</section>
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