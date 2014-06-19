$(function(){
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    
    $('.dd').click(function () {
		$('ul.menu_body').slideUp(200);
		$(this).find('ul.menu_body').slideDown(200);
	});
	
	$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if (! $clicked.parents().hasClass("dd"))
		$("ul.menu_body").slideUp(200);
	});
});

function menuOn(e) {
    $("ul.menu li").removeClass("on");
    $(e).addClass('on');
}

/*################ Menu Funciotns ####################*/

function addLink(token) {
    var k = $('.items li:last-child').index() + 1 * 1;
    var link = $('#external_link').val();
    var tag = $('#external_tag').val();
             
    var labelLink = $(document.createElement('label'))
        .attr({
            'for':'link_'+ k +'_link'
        })
        .addClass('neco-label')
        .text('Url:');
                
    var inputLink = $(document.createElement('input'))
        .addClass('menu_link')
        .css({
            width:'60%'
        })
        .attr({
            value:link,
            type:'url',
            name:'link['+ k +'][link]',
            id:'link_'+ k +'_link'
        });
                
    var labelTag = $(document.createElement('label'))
        .attr({
            'for':'link_'+ k +'_tag'
        })
        .addClass('neco-label')
        .text('Etiqueta:');
                
    var inputTag = $(document.createElement('input'))
        .addClass('menu_tag')
        .css({
            width:'60%'
        })
            .attr({
            value:tag,
            type:'text',
            name:'link['+ k +'][tag]',
            id:'link_'+ k +'_tag'
        });
                       
    var labelKeyword = $(document.createElement('label'))
        .attr({
            'for':'link_'+ k +'_keyword'
        })
        .addClass('neco-label')
        .text('Slug:');
         
    var inputKeyword = $(document.createElement('input'))
        .addClass('menu_keyword')
        .css({
            width:'60%'
        })
        .attr({
            value:tag,
            type:'text',
            name:'link['+ k +'][keyword]',
            id:'link_'+ k +'_keyword'
        });
          
    var itemForm1 = $(document.createElement('div'))
        .addClass('row')
        .append(labelLink)
        .append(inputLink);
          
    var itemForm2 = $(document.createElement('div'))
        .addClass('row')
        .append(labelTag)
        .append(inputTag);
          
    var itemForm3 = $(document.createElement('div'))
        .addClass('row')
        .append(labelKeyword)
        .append(inputKeyword);
          
    /* creamos el div con los campos y las opciones del enlace */
    var itemOptions = $(document.createElement('div'))
        .addClass('itemOptions')
        .attr({
            id:'linkOptions'+ k,
        })
        .append(itemForm1)
        .append('<div class="clear"></div>')
        .append(itemForm2)
        .append('<div class="clear"></div>')
        .append(itemForm3)
        .append('<div class="clear"></div>')
        .append('<a style="float:right;font-size:10px;" onclick="$(\'#li_'+ k +'\').remove()">[ Eliminar ]</a>');
          
    /* creamos el div.item que va a contener todo */
    var div = $(document.createElement('div'))
        .addClass('item')
        .append('<b>'+ tag +'</b><a class="showOptions" onclick="$(\'#linkOptions'+ k +'\').slideToggle(\'fast\')">&darr;</a>');
                
    var li = $(document.createElement('li'))
    .attr({
        id:'li_'+ k
    })
    .append(div)
    .append(itemOptions)
    .appendTo('.items');
    
    $.getJSON('index.php?r=common/home/slug&token=' + token,
    { 
        slug : tag 
    },
    function (data){
        inputKeyword.val(data.slug);
    });
}


function addPage(token) {
    
    if (!$('#pagesWrapper :checkbox').is(':checked')) {
        alert('Debe seleccionar al menos una p\u00E1gina');
        return false;
    }
    
    $.post('index.php?r=content/menu/page&token=' + token,$('#pagesWrapper :checkbox:checked').serialize(),function (response) {
        var data = $.parseJSON(response);
        $.each(data, function(i,item) {
            
            var k = $('.items li:last-child').index() + 1 * 1;
            
            var labelLink = $(document.createElement('label'))
                .attr({
                    'for':'link_'+ k +'_link'
                })
                .addClass('neco-label')
                .text('Url:');
                        
            var inputLink = $(document.createElement('input'))
                .addClass('menu_link')
                .css({
                    width:'60%'
                })
                .attr({
                    value:item.href,
                    type:'url',
                    name:'link['+ k +'][link]',
                    id:'link_'+ k +'_link'
                });
             var labelTag = $(document.createElement('label'))
                .attr({
                    'for':'link_'+ k +'_tag'
                })
                .addClass('neco-label')
                .text('Etiqueta:');
                        
            var inputTag = $(document.createElement('input'))
                .addClass('menu_tag')
                .css({
                    width:'60%'
                })
                    .attr({
                    value:item.title,
                    type:'text',
                    name:'link['+ k +'][tag]',
                    id:'link_'+ k +'_tag'
                });
                               
            var labelKeyword = $(document.createElement('label'))
                .attr({
                    'for':'link_'+ k +'_keyword'
                })
                .addClass('neco-label')
                .text('Slug:');
                 
            var inputKeyword = $(document.createElement('input'))
                .addClass('menu_keyword')
                .css({
                    width:'60%'
                })
                .attr({
                    value:item.keyword,
                    type:'text',
                    name:'link['+ k +'][keyword]',
                    id:'link_'+ k +'_keyword'
                });
            
            var itemForm1 = $(document.createElement('div'))
                .addClass('row')
                .append(labelLink)
                .append(inputLink);
                  
            var itemForm2 = $(document.createElement('div'))
                .addClass('row')
                .append(labelTag)
                .append(inputTag);
                  
            var itemForm3 = $(document.createElement('div'))
                .addClass('row')
                .append(labelKeyword)
                .append(inputKeyword);
            
            var itemOptions = $(document.createElement('div'))
                .addClass('itemOptions')
                .attr({
                    id:'linkOptions'+ k,
                })
                .append(itemForm1)
                .append('<div class="clear"></div>')
                .append(itemForm2)
                .append('<div class="clear"></div>')
                .append(itemForm3)
                .append('<div class="clear"></div>')
                .append('<a style="float:right;font-size:10px;" onclick="$(\'#li_'+ k +'\').remove()">[ Eliminar ]</a>');
            
            var div = $(document.createElement('div'))
                .addClass('item')
                .append('<b>'+ item.title +'</b><a class="showOptions" onclick="$(\'#linkOptions'+ k +'\').slideToggle(\'fast\')">&darr;</a>');
                        
            var li = $(document.createElement('li'))
            .attr({
                id:'li_'+ k
            })
            .append(div)
            .append(itemOptions)
            .appendTo('.items');
            
        });
        $('#pagesWrapper :checkbox').removeAttr('checked');
    });
}
function addCategory(token) {
    if (!$('#categoriesWrapper :checkbox').is(':checked')) {
        alert('Debe seleccionar al menos una categor\u00EDa de productos');
        return false;
    }
    
    $.post('index.php?r=content/menu/category&token=' + token,$('#categoriesWrapper :checkbox:checked').serialize(),function (response) {
        var data = $.parseJSON(response);
        $.each(data, function(i,item) {
            
            var k = $('.items li:last-child').index() + 1 * 1;
            
            var labelLink = $(document.createElement('label'))
                .attr({
                    'for':'link_'+ k +'_link'
                })
                .addClass('neco-label')
                .text('Url:');
                        
            var inputLink = $(document.createElement('input'))
                .addClass('menu_link')
                .css({
                    width:'60%'
                })
                .attr({
                    value:item.href,
                    type:'url',
                    name:'link['+ k +'][link]',
                    id:'link_'+ k +'_link'
                });
             var labelTag = $(document.createElement('label'))
                .attr({
                    'for':'link_'+ k +'_tag'
                })
                .addClass('neco-label')
                .text('Etiqueta:');
                        
            var inputTag = $(document.createElement('input'))
                .addClass('menu_tag')
                .css({
                    width:'60%'
                })
                    .attr({
                    value:item.title,
                    type:'text',
                    name:'link['+ k +'][tag]',
                    id:'link_'+ k +'_tag'
                });
                               
            var labelKeyword = $(document.createElement('label'))
                .attr({
                    'for':'link_'+ k +'_keyword'
                })
                .addClass('neco-label')
                .text('Slug:');
                 
            var inputKeyword = $(document.createElement('input'))
                .addClass('menu_keyword')
                .css({
                    width:'60%'
                })
                .attr({
                    value:item.keyword,
                    type:'text',
                    name:'link['+ k +'][keyword]',
                    id:'link_'+ k +'_keyword'
                });
            
            var itemForm1 = $(document.createElement('div'))
                .addClass('row')
                .append(labelLink)
                .append(inputLink);
                  
            var itemForm2 = $(document.createElement('div'))
                .addClass('row')
                .append(labelTag)
                .append(inputTag);
                  
            var itemForm3 = $(document.createElement('div'))
                .addClass('row')
                .append(labelKeyword)
                .append(inputKeyword);
            
            var itemOptions = $(document.createElement('div'))
                .addClass('itemOptions')
                .attr({
                    id:'linkOptions'+ k,
                })
                .append(itemForm1)
                .append('<div class="clear"></div>')
                .append(itemForm2)
                .append('<div class="clear"></div>')
                .append(itemForm3)
                .append('<div class="clear"></div>')
                .append('<a style="float:right;font-size:10px;" onclick="$(\'#li_'+ k +'\').remove()">[ Eliminar ]</a>');
            
            var div = $(document.createElement('div'))
                .addClass('item')
                .append('<b>'+ item.title +'</b><a class="showOptions" onclick="$(\'#linkOptions'+ k +'\').slideToggle(\'fast\')">&darr;</a>');
                        
            var li = $(document.createElement('li'))
            .attr({
                id:'li_'+ k
            })
            .append(div)
            .append(itemOptions)
            .appendTo('.items');
            
        });
        $('#categoriesWrapper :checkbox').removeAttr('checked');
    });
}

function ntSearch(q,targetId) {
    var valor = q.toLowerCase();
    if (valor.length <= 0) {
        $('#'+ targetId +' li').show();
    } else {
        $('#'+ targetId +' li b').each(function(){
            if ($(this).text().toLowerCase().indexOf( valor ) != -1) {
                $(this).closest('li').show();
            } else {
                $(this).closest('li').hide();
            }
        });
    }
}
                            
	//===== Accordion =====//		
	
	$('div.menu_body:eq(0)').show();
	$('.acc .head:eq(0)').show().css({color:"#2B6893"});
	
	$(".acc .head").click(function() {	
		$(this).css({color:"#2B6893"}).next("div.menu_body").slideToggle(300).siblings("div.menu_body").slideUp("slow");
		$(this).siblings().css({color:"#404040"});
	});
    
    
	//===== ShowCode plugin for <pre> tag =====//
/*
	$('.showCode').sourcerer('js html css php'); // Display all languages
	$('.showCodeJS').sourcerer('js'); // Display JS only
	$('.showCodeHTML').sourcerer('html'); // Display HTML only
	$('.showCodePHP').sourcerer('php'); // Display PHP only
	$('.showCodeCSS').sourcerer('css'); // Display CSS only


	//===== Calendar =====//

	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		editable: true,
		events: [
			{
				title: 'All day event',
				start: new Date(y, m, 1)
			},
			{
				title: 'Long event',
				start: new Date(y, m, 5),
				end: new Date(y, m, 8)
			},
			{
				id: 999,
				title: 'Repeating event',
				start: new Date(y, m, 2, 16, 0),
				end: new Date(y, m, 3, 18, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating event',
				start: new Date(y, m, 9, 16, 0),
				end: new Date(y, m, 10, 18, 0),
				allDay: false
			},
			{
				title: 'Actually any color could be applied for background',
				start: new Date(y, m, 30, 10, 30),
				end: new Date(y, m, d+1, 14, 0),
				allDay: false,
				color: '#B55D5C'
			},
			{
				title: 'Lunch',
				start: new Date(y, m, 14, 12, 0),
				end: new Date(y, m, 15, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday PARTY',
				start: new Date(y, m, 18),
				end: new Date(y, m, 20),
				allDay: false
			},
			{
				title: 'Click for Google',
				start: new Date(y, m, 27),
				end: new Date(y, m, 29),
				url: 'http://google.com/'
			}
		]
	});


	
	//===== Autotabs. Inline data rows =====//

	$('.onlyNums input').autotab_magic().autotab_filter('numeric');
	$('.onlyText input').autotab_magic().autotab_filter('text');
	$('.onlyAlpha input').autotab_magic().autotab_filter('alpha');
	$('.onlyRegex input').autotab_magic().autotab_filter({ format: 'custom', pattern: '[^0-9\.]' });
	$('.allUpper input').autotab_magic().autotab_filter({ format: 'alphanumeric', uppercase: true });
*/
	