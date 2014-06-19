<div id="scrollbar" class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Perfil</h3>
    <ul class="menu" id="profile">
		<li>
            <a href="<?php echo $Url::createUrl("account/profile"); ?>" title="<?php echo $text_profile; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_profile; ?></a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/profile"); ?>" title="<?php echo $text_profile; ?>"><i class="fa fa-home"></i>&nbsp;Reputaci&oacute;n</a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/edit"); ?>" title="<?php echo $text_my_account; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_my_account; ?></a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/password"); ?>" title="<?php echo $text_password; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_password; ?></a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/password"); ?>" title="<?php echo $text_password; ?>"><i class="fa fa-home"></i>&nbsp;Notificaciones</a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Ventas</h3>
    <ul class="menu" id="sales">
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Publicaciones</a>
        </li>
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Ofertas</a>
        </li>
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Cupones</a>
        </li>
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Preguntas</a>
        </li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Ventas</a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Compras</h3>
    <ul class="menu" id="sales">
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Preguntas</a>
        </li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Compras</a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Listas de Compras</a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Publicidad</h3>
    <ul class="menu" id="sales">
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Campañas</a>
        </li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Newsletter</a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Listas de Contactos</a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Contactos</a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Tienda</h3>
    <ul class="menu" id="sales">
        <li>
            <a href="<?php echo $Url::createUrl("account/sale"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Configuración</a>
        </li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;P&aacute;ginas</a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Apariencia</a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Plantillas</a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/order"); ?>" title="<?php echo $text_history; ?>"><i class="fa fa-home"></i>&nbsp;Aplicaciones</a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Saldos</h3>
    <ul class="menu" id="profile">
		<li>
			<a href="<?php echo $Url::createUrl("account/activities"); ?>" title="<?php echo $text_my_activities; ?>"><i class="fa fa-home"></i>&nbsp;Saldos</a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/payment"); ?>" title="<?php echo $text_payments; ?>"><i class="fa fa-home"></i>&nbsp;Movimientos</a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/hobbies"); ?>" title="<?php echo $text_hobbies; ?>"><i class="fa fa-home"></i>&nbsp;Pagos Pendientes</a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/social"); ?>" title="<?php echo $text_social_networks; ?>"><i class="fa fa-home"></i>&nbsp;Cobros Pendientes</a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/newsletter"); ?>" title="<?php echo $text_newsletter; ?>"><i class="fa fa-home"></i>&nbsp;Cuentas Bancarias Autorizadas</a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo $text_messages; ?></h3>
    <ul class="menu" id="messages">
		<li>
			<a href="<?php echo $Url::createUrl("account/message/create"); ?>" title="<?php echo $text_create_message; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_create_message; ?></a>
		</li>
		<li>
			<a href="<?php echo $Url::createUrl("account/message"); ?>" title="<?php echo $text_inbounce; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_inbounce; ?></a>
		</li>
		<li>
            <a href="<?php echo $Url::createUrl("account/message/sent"); ?>" title="<?php echo $text_outbounce; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_outbounce; ?></a>
		</li>
    </ul>
    
    <h3><i class="fa fa-home"></i>&nbsp;&nbsp;Terminar Cuenta</h3>
    <ul class="menu" id="finish">
		<li>
            <a href="<?php echo $Url::createUrl("account/cancel "); ?>" title="<?php echo $text_cancel_account; ?>"><i class="fa fa-home"></i>&nbsp;<?php echo $text_cancel_account; ?></a>
		</li>
    </ul>
</nav>
</div>
<script type="text/javascript">
$(function(){
    if (!$.fn.slimScroll) {
        $(document.createElement('script')).attr({
            'src':'<?php echo HTTP_PROFILE_JS; ?>jquery.slimscroll.min.js',
            'type':'text/javascript',
            'async':1
        }).appendTo('head');
    }
    
    $('#cbp-spmenu-s1').slimScroll();
    
    $(window).on('resize',function(e){
        $('#cbp-spmenu-s1').slimScroll();
    });

    $('body').addClass('cbp-spmenu-push');
    var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
        body = document.body;

    showLeft.onclick = function() {
	   classie.toggle( this, 'active' );
	   classie.toggle( body, 'cbp-spmenu-push-toright' );
	   classie.toggle( menuLeft, 'cbp-spmenu-open' );
       /* disableOther( 'showLeft' ); */
	};
    
    $('.cbp-spmenu h3').on('click',function(e){
        if ($(this).next().prop('tagName') == 'UL') {
            $(this).next().slideToggle();
        }
    });
});
function disableOther( button ) {
    if( button !== 'showLeft' ) {
	   classie.toggle( showLef, 'disabled' );
	}
}
</script>