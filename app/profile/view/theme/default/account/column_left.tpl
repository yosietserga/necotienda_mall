<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
    <h3>Menu</h3>
    <ul class="menu" id="sales">
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>">Mis Publicaciones</a>
        </li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><?php echo $text_history; ?></a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/payment"); ?>" title="<?php echo $text_payments; ?>"><?php echo $text_payments; ?></a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/download"); ?>" title="<?php echo $text_download; ?>"><?php echo $text_download; ?></a>
		</li>
    </ul>
	<a href="#">Celery seakale</a>
	<a href="#">Dulse daikon</a>
	<a href="#">Zucchini garlic</a>
	<a href="#">Catsear azuki bean</a>
	<a href="#">Dandelion bunya</a>
	<a href="#">Rutabaga</a>
</nav>
        
<div class="box">
	<div class="header">Ventas</div>
	<div class="content">
		<ul>
	</div>
</div>
<div class="box">
	<div class="header">Perfil</div>
	<div class="content">
		<ul>
			<li>
				<a href="<?php echo $Url::createUrl("account/profile"); ?>" title="<?php echo $text_profile; ?>"><?php echo $text_profile; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/activities"); ?>" title="<?php echo $text_my_activities; ?>"><?php echo $text_my_activities; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/hobbies"); ?>" title="<?php echo $text_hobbies; ?>"><?php echo $text_hobbies; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/social"); ?>" title="<?php echo $text_social_networks; ?>"><?php echo $text_social_networks; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/newsletter"); ?>" title="<?php echo $text_newsletter; ?>"><?php echo $text_newsletter; ?></a>
			</li>
		</ul>
	</div>
</div>
<div class="box">
	<div class="header"><?php echo $text_messages; ?></div>
	<div class="content">
		<ul>
			<li>
				<a href="<?php echo $Url::createUrl("account/message/create"); ?>" title="<?php echo $text_create_message; ?>"><?php echo $text_create_message; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/message"); ?>" title="<?php echo $text_inbounce; ?>"><?php echo $text_inbounce; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/message/sent"); ?>" title="<?php echo $text_outbounce; ?>"><?php echo $text_outbounce; ?></a>
			</li>
		</ul>
	</div>
</div>
<div class="box">
	<div class="header">
		Cuenta
	</div>
	<div class="content">
		<ul>
			<li>
				<a href="<?php echo $Url::createUrl("account/edit"); ?>" title="<?php echo $text_my_account; ?>"><?php echo $text_my_account; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/balance"); ?>" title="<?php echo $text_balance; ?>"><?php echo $text_balance; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/review"); ?>" title="<?php echo $text_my_comment; ?>"><?php echo $text_my_comment; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/address"); ?>" title="<?php echo $text_address; ?>"><?php echo $text_address; ?></a>
			</li>
			<li>
				<a href="<?php echo $Url::createUrl("account/password"); ?>" title="<?php echo $text_password; ?>"><?php echo $text_password; ?></a>
			</li>
		</ul>
	</div>
</div>
<div class="box">
	<div class="header">
		Terminar Cuenta
	</div>
	<div class="content">
		<ul>
			<li>
				<a href="<?php echo $Url::createUrl("account/cancel "); ?>" title="<?php echo $text_cancel_account; ?>"><?php echo $text_cancel_account; ?></a>
			</li>
		</ul>
	</div>
</div>
<script type="text/javascript">
$(function(){
    $('body').addClass('cbp-spmenu-push');
    var animateNav = function(el) {
        var index = 0, 
        el = el, 
        limit = $('#'+ el +' li:last-child').index(), 
        timer = 0,
        loop = function(idx) {
            li = $('#'+ el +' li:nth-child('+ idx +')');
            console.log(el);
            console.log(li);
            if (li.hasClass('flipInX')) {
                li.removeClass('animated flipInX').addClass('animated flipOutX');
            } else {
                li.removeClass('animated flipOutX').addClass('animated flipInX');
            }
            return !(idx > limit);
        };
        
        var animation = setInterval(function(){
            if (loop(index)) {
                index = (index + 1);
            } else {
                clearInterval(animation);
            }
        },100);
    };
    
    $('.cbp-spmenu h3').on('click',function(e){
        ul = $(this).next();
            console.log(ul);
        if (ul.prop('tagName') == 'ul') {
            ul.show();
            animateNav(ul.id);
        }
        console.log(this);
    });
    
    var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
        body = document.body;

    showLeft.onclick = function() {
	   classie.toggle( this, 'active' );
	   classie.toggle( body, 'cbp-spmenu-push-toright' );
	   classie.toggle( menuLeft, 'cbp-spmenu-open' );
       /* disableOther( 'showLeft' ); */
	};
});
function disableOther( button ) {
    if( button !== 'showLeft' ) {
	   classie.toggle( showLef, 'disabled' );
	}
}
</script>