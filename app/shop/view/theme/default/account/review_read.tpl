<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">

    <section id="content">
    
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13" id="review">
            <?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
            <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
            
            <h1><?php echo $heading_title; ?></h1>
            
            <div class="grid_2">
                <b><?php echo $review['author']; ?></b><br />
                <img src="<?php echo HTTP_IMAGE; ?>stars_<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['stars']; ?>" /><br /><br />
                <small><?php echo date('d-m-Y h:i A',strtotime($review['date_added'])); ?></small><br /><br />
            </div>
            
            <div class="grid_8"><?php echo $review['text']; ?></div>
            
            <?php if ($review['pid']) { ?>
            <div class="grid_2">
                <a href="<?php echo $Url::createUrl("store/product",array('product_id'=>$review['product_id'])); ?>">
                    <img src="<?php echo $review['thumb']; ?>" alt="<?php echo $review['name'];?>" /><br />
                    <?php echo $review['name']; ?>
                </a>
            </div>
            <?php } ?>
            
            <div class="clear"></div><br />
            
            <?php if ($replies) { ?>
            <h2><?php echo $Language->get("text_replies"); ?></h2>
            
            <div class="clear"></div>
            
            <ul class="replies">
                <?php foreach ($replies as $reply) { ?>
                <li id="reply_<?php echo $review['review_id']; ?>_<?php echo $reply['review_id']; ?>" class="reply_<?php echo $review['review_id']; ?>">
                    <div class="grid_3">
                        <b><?php echo $reply['author']; ?></b><br />
                        <small><?php echo date('d-m-Y h:i A',strtotime($reply['date_added'])); ?></small>
                    </div>
                    <div class="grid_9">
                        <?php echo $reply['text']; ?>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
            
            <div class="grid_13 review-buttons">
                <a class="review-delete" onclick="deleteReview(this,'<?php echo $review['product_id']; ?>','<?php echo $review['review_id']; ?>')">Eliminar</a>
                <a class="dislikes"><?php echo (int)$review['dislikes']; ?></a>
                <a class="review-dislike" title="<?php echo $Language->get('text_dislike'); ?>"></a>
                <a class="likes"><?php echo (int)$review['likes']; ?></a>
                <a class="review-like" title="<?php echo $Language->get('text_like'); ?>"></a>
            </div>
            
        </div>
        
    </section>
    
</section>
<script>
function deleteReview(e,p,r) {
    if (confirm('<?php echo $Language->get('text_confirm_delete'); ?>')) {
        $('#review_'+ r).slideUp(function(){
            $(this).remove();
        });
        $.post('<?php echo $Url::createUrl("store/product/deleteReview"); ?>&product_id='+ p +'&review_id='+ r,
        {
            'product_id':p,
            'review_id':r
        },function(){
            window.location = '<?php echo $Url::createUrl("account/review"); ?>';
        });
    }
}
</script>
<?php echo $footer; ?>