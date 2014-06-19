$(function(){
    $('.dd').click(function () {
		$('ul.menu_body').slideUp(200);
		$(this).find('ul.menu_body').slideDown(200);
	});
	
	$(document).bind('click', function(e) {
    	var $clicked = $(e.target);
    	if (! $clicked.parents().hasClass("dd"))
    		$("ul.menu_body").slideUp(200);
	});
    if (typeof $.ntTips == 'function') {
        $('.panel-lateral .help').ntTips();
    }
    
});

/**
 * Muestra y oculta los paneles laterales
 *
 * @param panel el identificador del panel.
 * @return false si panel es undefined.
 */
function slidePanel(panel,forceSlide) {
    if (typeof panel == 'undefined') {
        return false;
    }
    if (typeof forceSlide == 'undefined') {
        forceSlide = true;
    }
    that = $('#' + panel);

    /* ocultamos todos los paneles y les quitamos las clases ON */
    $('.panel-lateral').each(function(){
        if (this.id != that.attr('id')) {
            $('body,html').animate({
                'marginLeft':'0px'
            });
                    
            $(this).removeClass('on')
                .animate({
                    'marginLeft':'0px',
                })
                .find('.label')
                .css({
                    'display':'none'
                });
        }
    });
        
    if (that.hasClass('on') && forceSlide) {
        $('body,html').animate({
            'marginLeft':'0px'
        });
        
        that.animate({
            'marginLeft':'0px'
        })
        .removeClass('on');
            
        $('.panel-lateral').find('.label')
        .css({
            'display':'block'
        });
    } else {
        $('body,html').animate({
            'marginLeft':'20%'
        });
        
        that.animate({
            'marginLeft':'30%'
        }).addClass('on')
        .find('.label')
        .css({
            'display':'block'
        });
    }
    
    /* autohide
    that.mouseover(function(){
        clearTimeout($(this).data('timeoutId'));
    }).mouseout(function(){
        var timeoutId = setTimeout(function(){
            if (that.hasClass("on")) {
                $('body,html').animate({ 'marginLeft':'0px' });
                that.removeClass('on').animate({'marginLeft':'0px'});
                $('.panel-lateral').find('.label').css({ 'display':'block' });
            }
        }, 900);
        $(this).data('timeoutId', timeoutId); 
    });
    */
}

/**
 * Muestra y oculta las opciones avanzadas de los paneles
 *
 * @param e el enlace que acciona el evento.
 * @return void.
 */
function showAdvanced(e) {
    if ($(e).hasClass('on')) {
        $(e).removeClass('on').text('Mostrar Opciones Avanzadas');
        $(e).parent().find('.advanced:eq(0)').val(0);
    } else {
        $(e).addClass('on').text('Ocultar Opciones Avanzadas');
        $(e).parent().find('.advanced:eq(0)').val(1);
    }
    $(e).parent().find('> div').slideToggle('fast');
    
}

/**
 * Obtiene los items de una lista y los seriliza
 *
 * @param id de la lista.
 * @return array lista serializada.
 */
function getItems(id) {
    return $('#' + id).sortable('toArray').join(',');
}