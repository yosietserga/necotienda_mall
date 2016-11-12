$(function() {    
    var elements = {
        'body': {
            childrens:  [
                '#overheader',
                '#overheader .nt-editable',
                '#header',
                '#header .nt-editable',
                '#logo',
                '#search',
                '#nav',
                '#nav .nt-editable',
                '#maincontent',
                '#content',
                '#column_left',
                '#column_right',
                '#footer',
                '#footer .nt-editable'
            ],
            selector:   'body'
        },
    	'column_left_box': {
            childrens:  ['.header','.content'],
            selector:   '#column_left li.nt-editable',
            parents:    ['column_left'],
            canMove:    true,
            canDelete:  true
        },
    	'column_right_box': {
            childrens:  ['.header','.content'],
            selector:   '#column_right li.nt-editable',
            parents:    ['column_right'],
            canMove:    true,
            canDelete:  true
        },
    	'footer_widgets': {
            childrens:  ['.header','.content'],
            selector:   '#footerWidgets li.nt-editable',
            parents:    ['footerWidgets'],
            canMove:    true,
            canDelete:  true
        },
    	'content_widgets': {
            childrens:  ['.header','.content'],
            selector:   '#content li.nt-editable',
            parents:    ['maincontent'],
            canMove:    true,
            canDelete:  true
        }
    };
    
    addAdminControls(elements);
    
	$('ul.widgets').sortable({
		forceHelperSize: true,
		forcePlaceholderSize: true,
        connectWith: 'ul.widgets',
        handle: '.move',
		opacity: 0.8,
		dropOnEmpty: true,
		placeholder: 'placeholder',
		update: function(){
			/* update widget position and config */
		}
	})
    .disableSelection()
    .append('<li>&nbsp;</li>');
    
    $('.panelWrapper').accordion({
        collapsible: true
    });
    
    var width = $(window).width();
    var height = $(window).height();
    var tooltip = $('<div id="tooltip" />').css({
        position: 'absolute',
        top: -25,
        left: -10
    }).hide();

    var ttip = tooltip.clone();
    $("#marginSlider").find(".ui-slider-handle").append(ttip);
    $("#marginSlider").slider({
        range: "min",
		min: -width,
		max: width,
		slide: function(event, ui) {
            $("#margin").val(ui.value);
            $("#marginTop").val(ui.value);
            $("#marginRight").val(ui.value);
            $("#marginBottom").val(ui.value);
            $("#marginLeft").val(ui.value);
                
            $("#marginTopSlider").slider('option', 'value', parseInt(ui.value));
            $("#marginRightSlider").slider('option', 'value', parseInt(ui.value));
            $("#marginBottomSlider").slider('option', 'value', parseInt(ui.value));
            $("#marginLeftSlider").slider('option', 'value', parseInt(ui.value));
            
            setStyle();
        }
	});
    
    
    $("#marginTopSlider").slider({
        range: "min",
		min: -height,
		max: height,
		slide: function(event, ui) {
            $("#marginTop").val(ui.value);
            setStyle();
        }
	});
    
    $("#marginRightSlider").slider({
        range: "min",
		min: -width,
		max: width,
		slide: function(event, ui) {
            $("#marginRight").val(ui.value);
            setStyle();
        }
	});
    
    $("#marginBottomSlider").slider({
        range: "min",
		min: -height,
		max: height,
		slide: function(event, ui) {
            $("#marginBottom").val(ui.value);
            setStyle();
        }
	});
    
    $("#marginLeftSlider").slider({
        range: "min",
		min: -width,
		max: width,
		slide: function(event, ui) {
            $("#marginLeft").val(ui.value);
            setStyle();
        }
	});
    
    $("#paddingSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#padding").val(ui.value);
            $("#paddingTop").val(ui.value);
            $("#paddingRight").val(ui.value);
            $("#paddingBottom").val(ui.value);
            $("#paddingLeft").val(ui.value);
                
            $("#paddingTopSlider").slider('option', 'value', parseInt(ui.value));
            $("#paddingRightSlider").slider('option', 'value', parseInt(ui.value));
            $("#paddingBottomSlider").slider('option', 'value', parseInt(ui.value));
            $("#paddingLeftSlider").slider('option', 'value', parseInt(ui.value));
                
            setStyle();
        }
	});
    $("#paddingTopSlider").slider({
        range: "min",
		min: 0,
		max: height,
		slide: function(event, ui) {
            $("#paddingTop").val(ui.value);
            setStyle();
        }
	});
    
    $("#paddingRightSlider").slider({
        range: "min",
		min: 0,
		max: width,
		slide: function(event, ui) {
            $("#paddingRight").val(ui.value);
            setStyle();
        }
	});
    
    $("#paddingBottomSlider").slider({
        range: "min",
		min: 0,
		max: height,
		slide: function(event, ui) {
            $("#paddingBottom").val(ui.value);
            setStyle();
        }
	});
    
    $("#paddingLeftSlider").slider({
        range: "min",
		min: 0,
		max: width,
		slide: function(event, ui) {
            $("#paddingLeft").val(ui.value);
            setStyle();
        }
	});
    
    $("#topSlider").slider({
        range: "min",
		min: -height,
		max: height,
		slide: function(event, ui) {
            $("#top").val(ui.value);
            setStyle();
        }
	});
    $("#leftSlider").slider({
        range: "min",
		min: -width,
		max: width,
		slide: function(event, ui) {
            $("#left").val(ui.value);
            setStyle();
        }
	});
    $("#widthSlider").slider({
        range: "min",
		min: 0,
		max: width,
		slide: function(event, ui) {
            $("#width").val(ui.value);
            setStyle();
        }
	});
    $("#heightSlider").slider({
        range: "min",
		min: 0,
		max: height,
		slide: function(event, ui) {
            $("#height").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderRadiusSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderRadius").val(ui.value);
            $("#borderRadiusTopLeft").val(ui.value);
            $("#borderRadiusTopRight").val(ui.value);
            $("#borderRadiusBottomLeft").val(ui.value);
            $("#borderRadiusBottomRight").val(ui.value);
                
            $("#borderRadiusTopLeftSlider").slider('option', 'value', parseInt(ui.value));
            $("#borderRadiusTopRightSlider").slider('option', 'value', parseInt(ui.value));
            $("#borderRadiusBottomLeftSlider").slider('option', 'value', parseInt(ui.value));
            $("#borderRadiusBottomRightSlider").slider('option', 'value', parseInt(ui.value));
                
            setStyle();
        }
	});
    $("#borderRadiusTopLeftSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderRadiusTopLeft").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderRadiusTopRightSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderRadiusTopRight").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderRadiusBottomLeftSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderRadiusBottomLeft").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderRadiusBottomRightSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderRadiusBottomRight").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderWidthSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderWidth").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderTopWidthSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderTopWidth").val(ui.value);
            setStyle();
        }
	});
    
    $("#borderRightWidthSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderRightWidth").val(ui.value);
            setStyle();
        }
	});
    $("#borderBottomWidthSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderBottomWidth").val(ui.value);
            setStyle();
        }
	});
    $("#borderLeftWidthSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#borderLeftWidth").val(ui.value);
            setStyle();
        }
	});
    
    $("#boxShadowXSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#boxShadowX").val(ui.value);
            setStyle();
        }
	});
    
    $("#boxShadowYSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#boxShadowY").val(ui.value);
            setStyle();
        }
	});
    
    $("#boxShadowBlurSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#boxShadowBlur").val(ui.value);
            setStyle();
        }
	});
    
    $("#boxShadowSpreadSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#boxShadowSpread").val(ui.value);
            setStyle();
        }
	});
    
    $("#letterSpacingSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#letterSpacing").val(ui.value);
            setStyle();
        }
	});
    $("#wordSpacingSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#wordSpacing").val(ui.value);
            setStyle();
        }
	});
    $("#lineHeightSlider").slider({
        range: "min",
		min: 0,
		max: 250,
		slide: function(event, ui) {
            $("#lineHeight").val(ui.value);
            setStyle();
        }
	});
    $('#selectors').on('change', function(e){
        setElementToStyle( $('#selectors:selected').val());
    });
    $('.style-panel').each(function() {
        that = this;
        $(this).on('change', function(e) {
            var slider = $('#'+ that.id +'Slider');
            if (typeof slider != 'undefined' && slider.length > 0) {
                $(slider).slider('option', 'value', parseInt($(that).val()));
            }
            setStyle();
       	});
    });
            
    if ($.jStorage.get('currentBlock',null)) {
        renderPanels('#' + $.jStorage.get('currentBlock',''));
    } else if ($('#selector').val().length > 0){
        renderPanels($('#selector').val());
    } else {
        renderPanels('body');
    }
});

function resetPanel() {
    // reiniciamos todos los campos del formulario
    $('#selectors').val('null');
    $('#backgroundCss').val('');
    $('#backgroundColor').val('');
    $('#backgroundImage').val('');
    $('#backgroundRepeat').val('');
    $('#backgroundPositionX').val('');
    $('#backgroundPositionY').val('');
    $('#backgroundAttachment').removeAttr('checked');
                
    $('#borderColor').val('');
    $('#borderStyle').val('');
    $('#borderWidth').val('');
                
    $('#borderTopColor').val('');
    $('#borderTopStyle').val('');
    $('#borderTopWidth').val('');
            
    $('#borderRightColor').val('');
    $('#borderRightStyle').val('');
    $('#borderRightWidth').val('');
                
    $('#borderBottomColor').val('');
    $('#borderBottomStyle').val('');
    $('#borderBottomWidth').val('');
                
    $('#borderLeftColor').val('');
    $('#borderLeftStyle').val('');
    $('#borderLeftWidth').val('');
                
    $('#borderRadius').val('');
    $('#borderRadiusTopLeft').val('');
    $('#borderRadiusTopRight').val('');
    $('#borderRadiusBottomLeft').val('');
    $('#borderRadiusBottomRight').val('');
                
    $('#margin').val('');
    $('#marginTop').val('');
    $('#marginRight').val('');
    $('#marginBottom').val('');
    $('#marginLeft').val('');
                
    $('#padding').val('');
    $('#paddingTop').val('');
    $('#paddingRight').val('');
    $('#paddingBottom').val('');
    $('#paddingLeft').val('');
                
    $('#width').val('');
    $('#height').val('');
    $('#top').val('');
    $('#left').val('');
                
    $('#boxColor').val('');
    $('#boxShadowInset').removeAttr('checked');
    $('#boxShadowX').val(0);
    $('#boxShadowY').val(0);
    $('#boxShadowBlur').val(0);
    $('#boxShadowSpread').val(0);
                
    $('#fontColor').val('');
    $('#fontFamily').val('');
    $('#fontWeight').val('');
    $('#fontStyle').val('');
    $('#fontSize').val('');
    $('#textAlign').val('');
    $('#textDecoration').val('');
    $('#textTransform').val('');
    $('#letterSpacing').val('');
    $('#wordSpacing').val('');
    $('#lineHeight').val('');
            
    // reiniciamos todos los botones y helpers
    $('#bold').removeClass('boldOn');
    $('#italic').removeClass('italicOn');
    $('#underline').removeClass('underlineOn');
    $('#lineThrough').removeClass('line-throughOn');
    $('#alignLeft').removeClass('align-leftOn');
    $('#alignCenter').removeClass('align-centerOn');
    $('#alignRight').removeClass('align-rightOn');
    $('#alignJustify').removeClass('align-justifyOn');
}

function renderPanels(el,mainEl) {
    if (typeof el == 'undefined') {
        return false;
    } else {
        if (typeof mainEl == 'undefined' || mainEl.length == 0) {
            mainEl = el;
        }
        $('#selector').val(el);
        $('#mainselector').val(mainEl);
        
        var cookieName = "";
        cookieName = $('#selector').val().replace(/\s/g, '');
        cookieName = $('#selector').val().replace(/[,#\.]/g, '');
        $.jStorage.set('currentBlock',cookieName);
        
        $('#el').text(el);
        loadStyle();
        renderPanel();
    }
}

function loadStyle() {
    var elements = {};
    elements = $.jStorage.get('elements',false);
    if (elements) {
        $.each(elements, function(el,style) {
            that = $('#'+ el);
            
            if (typeof style.background.image != 'undefined' 
            && style.background.image 
            && !style.background.image.indexOf('url(')) 
            { 
                style.background.image = 'url('+ style.background.image +')'; 
            }
            
            if (IsNumber(style.background.positionX)) { style.background.positionX += 'px'; }
            if (IsNumber(style.background.positionY)) { style.background.positionY += 'px'; }
                    
            if (IsNumber(style.dimensions.top)) { style.dimensions.top += 'px'; }
            if (IsNumber(style.dimensions.left)) { style.dimensions.left += 'px'; }
            if (IsNumber(style.dimensions.width)) { style.dimensions.width += 'px'; }
            if (IsNumber(style.dimensions.height)) { style.dimensions.height += 'px'; }
                    
            if (IsNumber(style.font.size)) { style.font.size += 'px'; }
            if (IsNumber(style.font.letterspacing)) { style.font.letterspacing += 'px'; }
            if (IsNumber(style.font.wordspacing)) { style.font.wordspacing += 'px'; }
            if (IsNumber(style.font.lineheight)) { style.font.lineheight += 'px'; }
                
            if (IsNumber(style.boxshadow.x)) { style.boxshadow.x = parseInt(style.boxshadow.x) +'px '; }
            if (IsNumber(style.boxshadow.y)) { style.boxshadow.y = parseInt(style.boxshadow.y) +'px '; }
            if (IsNumber(style.boxshadow.blur)) { style.boxshadow.blur = parseInt(style.boxshadow.blur) +'px '; }
            if (IsNumber(style.boxshadow.spread)) { style.boxshadow.spread = parseInt(style.boxshadow.spread) +'px '; }
                    
            if (IsNumber(style.margin.all)) { style.margin.all += 'px'; }
            if (IsNumber(style.margin.top)) { style.margin.top += 'px'; }
            if (IsNumber(style.margin.right)) { style.margin.right += 'px'; }
            if (IsNumber(style.margin.bottom)) { style.margin.bottom += 'px'; }
            if (IsNumber(style.margin.left)) { style.margin.left += 'px'; }
                    
            if (IsNumber(style.padding.all)) { style.padding.all += 'px'; }
            if (IsNumber(style.padding.top)) { style.padding.top += 'px'; }
            if (IsNumber(style.padding.right)) { style.padding.right += 'px'; }
            if (IsNumber(style.padding.bottom)) { style.padding.bottom += 'px'; }
            if (IsNumber(style.padding.left)) { style.padding.left += 'px'; }
                    
            that.css({
                'backgroundColor':style.background.color,
                'backgroundImage':style.background.image,
                'backgroundRepeat':style.background.repeat,
                'backgroundPosition':style.background.positionX +' '+ style.background.positionY,
                'backgroundAttachment':style.background.attachment,
                     
                'top':style.dimensions.top,
                'left':style.dimensions.left,
                'width':style.dimensions.width,
                'height':style.dimensions.height,
                'float':style.dimensions._float,
                'position':style.dimensions.position,
                'overflow':style.dimensions.overflow,
                        
                'fontColor':style.font.color,
                'fontFamily':style.font.family,
                'fontWeight':style.font.weight,
                'fontStyle':style.font.style,
                'fontSize':style.font.size,
                'textAlign':style.font.align,
                'textDecoration':style.font.decoration,
                'textTransform':style.font.transform,
                'letterSpacing':style.font.letterspacing,
                'wordSpacing':style.font.wordspacing,
                'lineHeight':style.font.lineheight
            });
                    
            $('#bold').removeClass('boldOn');
            $('#italic').removeClass('italicOn');
            $('#underline').removeClass('underlineOn');
            $('#lineThrough').removeClass('line-throughOn');
            $('#upper').removeClass('uppercaseOn');
            $('#lower').removeClass('lowercaseOn');
            $('#alignLeft').removeClass('align-leftOn');
            $('#alignCenter').removeClass('align-centerOn');
            $('#alignRight').removeClass('align-rightOn');
            $('#alignJustify').removeClass('align-justifyOn');
                
            if (style.font.weight == 'bold' || style.font.weight == '700') { $('#bold').addClass('boldOn'); }
            if (style.font.style == 'italic') { $('#italic').addClass('italicOn'); }
            if (style.font.decoration == 'underline') { $('#underline').addClass('underlineOn'); }
            if (style.font.decoration == 'line-through') { $('#lineThrough').addClass('line-throughOn'); }
            if (style.font.transform == 'uppercase') { $('#upper').addClass('uppercaseOn'); }
            if (style.font.transform == 'lowercase') { $('#lower').addClass('lowercaseOn'); }
            if (style.font.align == 'left') { $('#alignLeft').addClass('align-leftOn'); }
            if (style.font.align == 'center') { $('#alignCenter').addClass('align-centerOn'); }
            if (style.font.align == 'right') { $('#alignRight').addClass('align-rightOn'); }
            if (style.font.align == 'justify') { $('#alignJustify').addClass('align-justifyOn'); }
            
            var cssBoxShadow = "";
            if (style.boxshadow.inset) { cssBoxShadow += 'inset '; }
            cssBoxShadow += style.boxshadow.x;
            cssBoxShadow += style.boxshadow.y;
            cssBoxShadow += style.boxshadow.blur;
            cssBoxShadow += style.boxshadow.spread;
            cssBoxShadow += style.boxshadow.color;
            $(that).css('boxShadow',cssBoxShadow);
            
            if ((typeof (style.border.topcolor) == 'undefined' || !style.border.topcolor)
             && (typeof (style.border.rightcolor) == 'undefined' || !style.border.rightcolor)
             && (typeof (style.border.bottomcolor) == 'undefined' || !style.border.bottomcolor)
             && (typeof (style.border.leftcolor) == 'undefined' || !style.border.leftcolor)) {
                that.css({
                    'borderColor':style.border.color,
                    'borderStyle':style.border.style,
                    'borderWidth':style.border.width
                });
            } else {
                that.css({
                    'borderTopColor':style.border.topcolor,
                    'borderTopStyle':style.border.topstyle,
                    'borderTopWidth':style.border.topwidth,
                    
                    'borderRightColor':style.border.rightcolor,
                    'borderRightStyle':style.border.rightstyle,
                    'borderRightWidth':style.border.rightwidth,
                    
                    'borderBottomColor':style.border.botomcolor,
                    'borderBottomStyle':style.border.botomstyle,
                    'borderBottomWidth':style.border.botomwidth,
                    
                    'borderLeftColor':style.border.leftcolor,
                    'borderLeftStyle':style.border.leftstyle,
                    'borderLeftWidth':style.border.leftwidth
                });
            }
            
            if (!style.borderradius.topleft
             && !style.borderradius.topright
             && !style.borderradius.bottomleft
             && !style.borderradius.bottomright) {
                that.css({
                    'borderRadius':style.borderradius.all
                });
            } else {
                that.css({
                    'borderRadius':style.borderradius.topleft +'px '+ style.borderradius.topright +'px '+ style.borderradius.bottomright +'px '+ style.borderradius.bottomleft +'px'
                });
            }
            
            if ((typeof (style.margin.top) == 'undefined' || !style.margin.top)
             && (typeof (style.margin.right) == 'undefined' || !style.margin.right)
             && (typeof (style.margin.bottom) == 'undefined' || !style.margin.bottom)
             && (typeof (style.margin.left) == 'undefined' || !style.margin.left)) {
                that.css({
                    'margin':style.margin.all
                });
            } else {
                that.css({
                    'marginTop':style.margin.top,
                    'marginRight':style.margin.right,
                    'marginBottom':style.margin.bottom,
                    'marginLeft':style.margin.left,
                });
            }
            
            if ((typeof (style.padding.top) == 'undefined' || !style.padding.top)
             && (typeof (style.padding.right) == 'undefined' || !style.padding.right)
             && (typeof (style.padding.bottom) == 'undefined' || !style.padding.bottom)
             && (typeof (style.padding.left) == 'undefined' || !style.padding.left)) {
                that.css({
                    'padding':style.padding.all
                });
            } else {
                that.css({
                    'paddingTop':style.padding.top,
                    'paddingRight':style.padding.right,
                    'paddingBottom':style.padding.bottom,
                    'paddingLeft':style.padding.left,
                });
            }
        });
    }
}

function setElementToStyle(el) {
    var ele = $('#mainselector').val();
    var mainEl = ele;
    if (typeof el != 'undefined' || el.length > 0) {
        if (el == 'subtitle') {
            ele =  mainEl + ' h2, ';
            ele += mainEl + ' h3, ';
            ele += mainEl + ' h4, ';
            ele += mainEl + ' h5, ';
            ele += mainEl + ' h6';
        } else if (el != 'null') {
            ele += ' ' + el;
        } else {
            mainEl = '';
        }
    }
    $('body').css({
        'marginLeft':'20%'
    });
    renderPanels(ele,mainEl);
}

function translateCssProperties($cssObject) {
    if (typeof $cssObject == 'undefined') {
        return false;
    }
    style = {};
    style.background = {};
    style.border = {};
    style.boxshadow = {};
    style.borderradius = {};
    style.margin = {};
    style.padding = {};
    style.position = {};
    style.dimensions = {};
    style.font = {};
    
    $.each($cssObject, function(prop, value) {
        if (prop == 'backgroundColor') { style.background.color = value; }
        if (prop == 'backgroundImage' && !value.indexOf('/none') && !value.indexOf('/undefined') && !value.indexOf('theme_editor')) { style.background.image = value; } /* descartar las urls con la palabra none o undefined */
        if (prop == 'backgroundRepeat') { style.background.repeat = value; }
        if (prop == 'backgroundPosition') {
            position = value.split(" ");
            style.background.positionX = position[0]; // calcular de acuerdo al elemento relativo
            style.background.positionY = position[1];
        }
        if (prop == 'backgroundAttachment') { style.background.attachment = value; }
        
        if (prop == 'borderColor') { style.border.color = value; }
        if (prop == 'borderStyle') { style.border.style = value; }
        if (prop == 'borderWidth') { style.border.width = value; }
        
        if (prop == 'borderTopColor') { style.border.topcolor = value; }
        if (prop == 'borderTopStyle') { style.border.topstyle = value; }
        if (prop == 'borderTopWidth') { style.border.topwidth = value; }
        
        if (prop == 'borderRightColor') { style.border.rightcolor = value; }
        if (prop == 'borderRightStyle') { style.border.rightstyle = value; }
        if (prop == 'borderRightWidth') { style.border.rightwidth = value; }
        
        if (prop == 'borderBottomColor') { style.border.bottomcolor = value; }
        if (prop == 'borderBottomStyle') { style.border.bottomstyle = value; }
        if (prop == 'borderBottomWidth') { style.border.bottomwidth = value; }
        
        if (prop == 'borderLeftColor') { style.border.leftcolor = value; }
        if (prop == 'borderLeftStyle') { style.border.leftstyle = value; }
        if (prop == 'borderLeftWidth') { style.border.leftwidth = value; }
        
        if (prop == 'margin') { style.margin.all = value; }
        if (prop == 'marginTop') { style.margin.top = value; }
        if (prop == 'marginRight') { style.margin.right = value; }
        if (prop == 'marginBottom') { style.margin.bottom = value; }
        if (prop == 'marginLeft') { style.margin.left = value; }
        
        if (prop == 'padding') { style.padding.all = value; }
        if (prop == 'paddingTop') { style.padding.top = value; }
        if (prop == 'paddingRight') { style.padding.right = value; }
        if (prop == 'paddingBottom') { style.padding.bottom = value; }
        if (prop == 'paddingLeft') { style.padding.left = value; }
        
        if (prop == 'width') { style.dimensions.width = value; }
        if (prop == 'height') { style.dimensions.height = value; }
        if (prop == 'top') { style.dimensions.top = value; }
        if (prop == 'left') { style.dimensions.left = value; }
        if (prop == 'position') { style.dimensions.position = value; }
        if (prop == 'float') { style.dimensions._float = value; }
        if (prop == 'overflow') { style.dimensions.overflow = value; }
        
        if (prop == 'boxShadow') { 
            
        }
        
        if (prop == 'fontColor') { style.font.color = value; }
        if (prop == 'fontFamily') { style.font.family = value; }
        if (prop == 'fontWeight') { style.font.weight = value; }
        if (prop == 'fontStyle') { style.font.style = value; }
        if (prop == 'fontSize') { style.font.size = value; }
        if (prop == 'textAlign') { style.font.align = value; }
        if (prop == 'textDecoration') { style.font.decoration = value; }
        if (prop == 'textTransform') { style.font.transform = value; }
        if (prop == 'letterSpacing') { style.font.letterspacing = value; }
        if (prop == 'wordSpacing') { style.font.wordspacing = value; }
        if (prop == 'lineHeight') { style.font.lineheight = value; }
    });
    return style;
}

function renderPanel() {
    var style = {};
    var cookieName = "";
    
    $('#selectors').val('null');
    
    if (!$.jStorage.get('elements',null)) {
        var elements = {};
        $.jStorage.set('elements',elements);
    } else {
        var elements = $.jStorage.get('elements',{});
    }
    
    cookieName = $('#selector').val().replace(/\s/g, '');
    cookieName = $('#selector').val().replace(/[,#\.]/g, '');
    
    if ($('#selector').val().length) {
        var currentCss = translateCssProperties($($('#selector').val()).getStyles());
    }
    
    /* if (typeof elements[cookieName] == 'undefined') {
        style = false;
    } else */ if (typeof currentCss != 'undefined' && currentCss) {
        style = currentCss;
    } else {
        style = false;
    }
    console.log(currentCss);
    resetPanel();
    
    if (style) {
        if (typeof style.background.color != 'undefined') { $('#backgroundColor').val(style.background.color); }
        if (typeof style.background.image != 'undefined') { $('#backgroundImage').val(style.background.image); }
        if (typeof style.background.repeat != 'undefined') { $('#backgroundRepeat').val(style.background.repeat); }
        if (typeof style.background.positionX != 'undefined') { $('#backgroundPositionX').val(style.background.positionX); }
        if (typeof style.background.positionY != 'undefined') { $('#backgroundPositionY').val(style.background.positionY); }
        if (typeof style.background.attachment != 'undefined') { $('#backgroundAttachment').attr('checked','checked'); }
                
        if (typeof style.border.color != 'undefined') { $('#borderColor').val(style.border.color); }
        if (typeof style.border.style != 'undefined') { $('#borderStyle').val(style.border.style); }
        if (typeof style.border.width != 'undefined') { $('#borderWidth').val(style.border.width); }
                
        if (typeof style.border.topcolor != 'undefined') { $('#borderTopColor').val(style.border.topcolor); }
        if (typeof style.border.topstyle != 'undefined') { $('#borderTopStyle').val(style.border.topstyle); }
        if (typeof style.border.topwidth != 'undefined') { $('#borderTopWidth').val(style.border.topwidth); }
                
        if (typeof style.border.rightcolor != 'undefined') { $('#borderRightColor').val(style.border.rightcolor); }
        if (typeof style.border.rightstyle != 'undefined') { $('#borderRightStyle').val(style.border.rightstyle); }
        if (typeof style.border.rightwidth != 'undefined') { $('#borderRightWidth').val(style.border.rightwidth); }
                
        if (typeof style.border.bottomcolor != 'undefined') { $('#borderBottomColor').val(style.border.bottomcolor); }
        if (typeof style.border.bottomstyle != 'undefined') { $('#borderBottomStyle').val(style.border.bottomstyle); }
        if (typeof style.border.bottomwidth != 'undefined') { $('#borderBottomWidth').val(style.border.bottomwidth); }
                
        if (typeof style.border.leftcolor != 'undefined') { $('#borderLeftColor').val(style.border.leftcolor); }
        if (typeof style.border.leftstyle != 'undefined') { $('#borderLeftStyle').val(style.border.leftstyle); }
        if (typeof style.border.leftwidth != 'undefined') { $('#borderLeftWidth').val(style.border.leftwidth); }
                
        if (typeof style.borderradius.all != 'undefined') { $('#borderRadius').val(style.borderradius.all); }
        if (typeof style.borderradius.topleft != 'undefined') { $('#borderRadiusTopLeft').val(style.borderradius.topleft); }
        if (typeof style.borderradius.topright != 'undefined') { $('#borderRadiusTopRight').val(style.borderradius.topright); }
        if (typeof style.borderradius.bottomleft != 'undefined') { $('#borderRadiusBottomLeft').val(style.borderradius.bottomleft); }
        if (typeof style.borderradius.bottomright != 'undefined') { $('#borderRadiusBottomRight').val(style.borderradius.bottomright); }
                
        if (typeof style.margin.all != 'undefined') { $('#margin').val(style.margin.all); }
        if (typeof style.margin.top != 'undefined') { $('#marginTop').val(style.margin.top); }
        if (typeof style.margin.right != 'undefined') { $('#marginRight').val(style.margin.right); }
        if (typeof style.margin.bottom != 'undefined') { $('#marginBottom').val(style.margin.bottom); }
        if (typeof style.margin.left != 'undefined') { $('#marginLeft').val(style.margin.left); }
                
        if (typeof style.padding.all != 'undefined') { $('#padding').val(style.padding.all); }
        if (typeof style.padding.top != 'undefined') { $('#paddingTop').val(style.padding.top); }
        if (typeof style.padding.right != 'undefined') { $('#paddingRight').val(style.padding.right); }
        if (typeof style.padding.bottom != 'undefined') { $('#paddingBottom').val(style.padding.bottom); }
        if (typeof style.padding.left != 'undefined') { $('#paddingLeft').val(style.padding.left); }
                
        if (typeof style.dimensions.top != 'undefined') { $('#top').val(style.dimensions.top); }
        if (typeof style.dimensions.left != 'undefined') { $('#left').val(style.dimensions.left); }
        if (typeof style.dimensions.width != 'undefined') { $('#width').val(style.dimensions.width); }
        if (typeof style.dimensions.height != 'undefined') { $('#height').val(style.dimensions.height); }
        if (typeof style.dimensions.position != 'undefined') { $('#position').val(style.dimensions.position); }
        if (typeof style.dimensions.overflow != 'undefined') { $('#overflow').val(style.dimensions.overflow); }
        if (typeof style.dimensions._float != 'undefined') { $('#float').val(style.dimensions._float); }
                
        if (typeof style.boxshadow.color != 'undefined') { $('#boxColor').val(style.boxshadow.color); }
        if (typeof style.boxshadow.inset != 'undefined') { $('#boxShadowInset').attr('checked','checked'); }
        if (typeof style.boxshadow.x != 'undefined') { $('#boxShadowX').val(style.boxshadow.x); }
        if (typeof style.boxshadow.y != 'undefined') { $('#boxShadowY').val(style.boxshadow.y); }
        if (typeof style.boxshadow.blur != 'undefined') { $('#boxShadowBlur').val(style.boxshadow.blur); }
        if (typeof style.boxshadow.spread != 'undefined') { $('#boxShadowSpread').val(style.boxshadow.spread); }
                
        if (typeof style.font.color != 'undefined') { $('#fontColor').val(style.font.color); }
        if (typeof style.font.family != 'undefined') { $('#fontFamily').val(style.font.family); }
        if (typeof style.font.weight == 'undefined') { $('#fontWeight').val(''); }
        if (typeof style.font.style == 'undefined') { $('#fontStyle').val(''); }
        if (typeof style.font.size != 'undefined') { $('#fontSize').val(style.font.size); }
        if (typeof style.font.align != 'undefined') { $('#textAlign').val(style.font.align); }
        if (typeof style.font.decoration != 'undefined') { $('#textDecoration').val(style.font.decoration); }
        if (typeof style.font.transform != 'undefined') { $('#textTransform').val(style.font.transform); }
        if (typeof style.font.letterspacing != 'undefined') { $('#letterSpacing').val(style.font.letterspacing); }
        if (typeof style.font.wordspacing != 'undefined') { $('#wordSpacing').val(style.font.wordspacing); }
        if (typeof style.font.lineheight != 'undefined') { $('#lineHeight').val(style.font.lineheight); }
                
        if (style.font.weight) { $('#bold').addClass('boldOn'); }
        if (style.font.style) { $('#italic').addClass('italicOn'); }
        if (style.font.decoration == 'underline') { $('#underline').addClass('underlineOn'); }
        if (style.font.decoration == 'line-through') { $('#lineThrough').addClass('line-throughOn'); }
        if (style.font.transform == 'uppercase') { $('#upper').addClass('uppercaseOn'); }
        if (style.font.transform == 'lowercase') { $('#lower').addClass('lowercaseOn'); }
        if (style.font.align == 'left') { $('#alignLeft').addClass('align-leftOn'); }
        if (style.font.align == 'center') { $('#alignCenter').addClass('align-centerOn'); }
        if (style.font.align == 'right') { $('#alignRight').addClass('align-rightOn'); }
        if (style.font.align == 'justify') { $('#alignJustify').addClass('align-justifyOn'); }
        
        setStyle();
    }
    
    $('#background-colorpicker').ntColorPicker({
        type: 'rgba',
        change:function(color){
            if (color.length > 0) {
                $('#backgroundColor').val(color);
                setStyle();
            }
        }
    });
    $('#font-colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#fontColor').val(color);
                setStyle();
            }
        }
    });
    $('#border-colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#borderColor').val(color);
                setStyle();
            }
        }
    });
    $('#border_top_colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#borderTopColor').val(color);
                setStyle();
            }
        }
    });
    $('#border-right-colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#borderRightColor').val(color);
                setStyle();
            }
        }
    });
    $('#border-bottom-colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#borderBottomColor').val(color);
                setStyle();
            }
        }
    });
    $('#border-left-colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#borderLeftColor').val(color);
                setStyle();
            }
        }
    });
    $('#box-colorpicker').ntColorPicker({
        type: 'hex',
        change:function(color){
            if (color.length > 0) {
                $('#boxColor').val(color);
                setStyle();
            }
        }
    });
}

function _IsNumber(_id) {
    if (IsNumber($('#'+ _id).val())) {
        a = $('#'+ _id).val();
        $('#'+ _id).val(a +'px '); 
    }
}
function IsNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
/**
 * Establece los estilos del documento o del elemento seleccionado. Utiliza un campo oculto para saber cual elemento esta seleccionado
 *
 * @return void.
 */
function setStyle() {
    var that = $($('#selector').val()).not('.panel-lateral,.panel-lateral *, #adminTopNav, #adminTopNav *, .actions, .actions *'); /* $($('#selector').val()).get(0); */
    var cookieName = "";
    var style = {};
    var elements = {};
    
    if (!$.jStorage.get('elements',null)) {
        $.jStorage.set('elements',elements);
    } else {
        var elements = $.jStorage.get('elements',{});
    }
    
    cookieName = $('#selector').val().replace(/\s/g, '');
    cookieName = $('#selector').val().replace(/[,#\.]/g, '');
    
    if (typeof elements[cookieName] == 'undefined') {
        style = {};
    } else {
        style = elements[cookieName];
    }
    
    style.background = {};
    style.border = {};
    style.boxshadow = {};
    style.borderradius = {};
    style.margin = {};
    style.padding = {};
    style.position = {};
    style.dimensions = {};
    style.font = {};
    
            if ($('#backgroundImage').val().length && !$('#backgroundImage').val().indexOf('url(')) {
                a = $('#backgroundImage').val();
                $('#backgroundImage').val('url('+ a +')'); 
            }
            
            _IsNumber('backgroundPositionX');
            _IsNumber('backgroundPositionY');
            _IsNumber('top');
            _IsNumber('left');
            _IsNumber('width');
            _IsNumber('height');
            _IsNumber('fontSize');
            _IsNumber('letterSpacing');
            _IsNumber('wordSpacing');
            _IsNumber('lineHeight');
            _IsNumber('borderWidth');
            _IsNumber('borderTopWidth');
            _IsNumber('borderRightWidth');
            _IsNumber('borderBottomWidth');
            _IsNumber('borderLeftWidth');
            _IsNumber('borderRadius');
            _IsNumber('borderRadiusTopLeft');
            _IsNumber('borderRadiusTopRight');
            _IsNumber('borderRadiusBottomLeft');
            _IsNumber('borderRadiusBottomRight');
            _IsNumber('margin');
            _IsNumber('marginTop');
            _IsNumber('marginRight');
            _IsNumber('marginBottom');
            _IsNumber('marginLeft');
            _IsNumber('padding');
            _IsNumber('paddingTop');
            _IsNumber('paddingRight');
            _IsNumber('paddingBottom');
            _IsNumber('paddingLeft');
            _IsNumber('boxShadowX');
            _IsNumber('boxShadowY');
            _IsNumber('boxShadowBlur');
            _IsNumber('boxShadowSpread');
            
            // backgrounds
            if ($('#backgroundColor').val().length) {
                style.background.color = $('#backgroundColor').val();
                $(that).css({'backgroundColor': $('#backgroundColor').val()});
            }
            if ($('#backgroundImage').val().length) {
                style.background.image = $('#backgroundImage').val();
                $(that).css('backgroundImage',$('#backgroundImage').val());
            }
            if ($('#backgroundRepeat').val().length) {
                style.background.repeat = $('#backgroundRepeat').val();
                $(that).css('backgroundRepeat',$('#backgroundRepeat').val());
            }
            if ($('#backgroundPositionX').val().length) {
                style.background.positionX = $('#backgroundPositionX').val();
            }
            if ($('#backgroundPositionY').val().length) {
                style.background.positionY = $('#backgroundPositionY').val();
            }
            if ($('#backgroundPositionX').val().length && $('#backgroundPositionY').val().length) {
                $(that).css({ 'backgroundPosition': $('#backgroundPositionX').val() +' '+ $('#backgroundPositionY').val() });
            }
            if ($('#backgroundAttachment:checked').length > 0) {
                style.background.attachment = 'fixed';
                $(that).css({'backgroundAttachment':'fixed'});
            } else {
                style.background.attachment = null;
                $(that).css({'backgroundAttachment':'scroll'});
            }
        
            // borders
            if ($('#borderAdvanced').val() == 0) {
                if ($('#borderColor').val().length) {
                    style.border.color = $('#borderColor').val();
                    $(that).css('borderColor',$('#borderColor').val());
                }
                if ($('#borderStyle').val().length) {
                    style.border.style = $('#borderStyle').val();
                    $(that).css('borderStyle',$('#borderStyle').val());
                }
                if ($('#borderWidth').val().length) {
                    style.border.width = $('#borderWidth').val();
                    $(that).css('borderWidth',$('#borderWidth').val());
                }
            } else {
                if ($('#borderTopColor').val().length) {
                    style.border.topcolor = $('#borderTopColor').val();
                    $(that).css('borderTopColor',$('#borderTopColor').val());
                }
                if ($('#borderTopStyle').val().length) {
                    style.border.topstyle = $('#borderTopStyle').val();
                    $(that).css('borderTopStyle',$('#borderTopStyle').val());
                }
                if ($('#borderTopWidth').val().length) {
                    style.border.topwidth = $('#borderTopWidth').val();
                    $(that).css('borderTopWidth',$('#borderTopWidth').val());
                }
                
                if ($('#borderRightColor').val().length) {
                    style.border.rightcolor = $('#borderRightColor').val();
                    $(that).css('borderRightColor',$('#borderRightColor').val());
                }
                if ($('#borderRightStyle').val().length) {
                    style.border.rightstyle = $('#borderRightStyle').val();
                    $(that).css('borderRightStyle',$('#borderRightStyle').val());
                }
                if ($('#borderRightWidth').val().length) {
                    style.border.rightwidth = $('#borderRightWidth').val();
                    $(that).css('borderRightWidth',$('#borderRightWidth').val());
                }
                
                if ($('#borderBottomColor').val().length) {
                    style.border.bottomcolor = $('#borderBottomColor').val();
                    $(that).css('borderBottomColor',$('#borderBottomColor').val());
                }
                if ($('#borderBottomStyle').val().length) {
                    style.border.bottomstyle = $('#borderBottomStyle').val();
                    $(that).css('borderBottomStyle',$('#borderBottomStyle').val());
                }
                if ($('#borderBottomWidth').val().length) {
                    style.border.bottomwidth = $('#borderBottomWidth').val();
                    $(that).css('borderBottomWidth',$('#borderBottomWidth').val());
                }
                
                if ($('#borderLeftColor').val().length) {
                    style.border.leftcolor = $('#borderLeftColor').val();
                    $(that).css('borderLeftColor',$('#borderLeftColor').val());
                }
                if ($('#borderLeftStyle').val().length) {
                    style.border.leftstyle = $('#borderLeftStyle').val();
                    $(that).css('borderLeftStyle',$('#borderLeftStyle').val());
                }
                if ($('#borderLeftWidth').val().length) {
                    style.border.leftwidth = $('#borderLeftWidth').val();
                    $(that).css('borderLeftWidth',$('#borderLeftWidth').val());
                }
            }
            
            // borderRadius
            if ($('#borderRadiusAdvanced').val() == 0) {
                style.borderradius.all = $('#borderRadius').val();
                $(that).css('borderRadius',$('#borderRadius').val());
            } else {
                var cssBorderRadius = "";
                
                style.borderradius.topleft = $('#borderRadiusTopLeft').val();
                cssBorderRadius += parseInt($('#borderRadiusTopLeft').val()); 
                
                style.borderradius.topright = $('#borderRadiusTopRight').val();
                cssBorderRadius += parseInt($('#borderRadiusTopRight').val());
                
                style.borderradius.bottomleft = $('#borderRadiusBottomLeft').val();
                cssBorderRadius += parseInt($('#borderRadiusBottomLeft').val()); 
                
                style.borderradius.bottomright = $('#borderRadiusBottomRight').val();
                cssBorderRadius += parseInt($('#borderRadiusBottomRight').val()); 
                
                $(that).css({'borderRadius':cssBorderRadius});
            }
            
            // dimensions and positions
            style.dimensions.width = $('#width').val();
            $(that).css('width',$('#width').val());
            
            style.dimensions.height = $('#height').val();
            $(that).css('height',$('#height').val());
            
            style.dimensions.top = $('#top').val();
            $(that).css('top',$('#top').val());
            
            style.dimensions.left = $('#left').val();
            $(that).css('left',$('#left').val());
            
            style.dimensions.position = $('#position').val();
            $(that).css('position',$('#position').val());
            
            // margin
            if ($('#marginAdvanced').val() == 0) {
                style.margin.all = $('#margin').val();
                $(that).css('margin',$('#margin').val());
            } else {
                style.margin.top = $('#marginTop').val();
                $(that).css({'marginTop':$('#marginTop').val()});
                
                style.margin.right = $('#marginRight').val();
                $(that).css({'marginRight':$('#marginRight').val()});
                
                style.margin.left = $('#marginLeft').val();
                $(that).css({'marginLeft':$('#marginLeft').val()});
                
                style.margin.bottom = $('#marginBottom').val();
                $(that).css({'paddingBottom':$('#paddingBottom').val()});
            }
            
            // padding
            if ($('#paddingAdvanced').val() == 0) {
                style.padding.all = $('#padding').val();
                $(that).css('padding',$('#padding').val());
            } else {
                style.padding.top = $('#paddingTop').val();
                $(that).css({'paddingTop':$('#paddingTop').val()});
                
                style.padding.right = $('#paddingRight').val();
                $(that).css({'paddingRight':$('#paddingRight').val()});
                
                style.padding.left = $('#paddingLeft').val();
                $(that).css({'paddingLeft':$('#paddingLeft').val()});
                
                style.padding.bottom = $('#paddingBottom').val();
                $(that).css({'paddingBottom':$('#paddingBottom').val()});
            }
            
            // boxShadow
            var cssBoxShadow = "";
            if ($('#boxShadowInset').is(':checked')) { 
                style.boxshadow.inset = 1;
                cssBoxShadow += 'inset '; 
            }
            
            style.boxshadow.x = $('#boxShadowX').val();
            cssBoxShadow += parseInt($('#boxShadowX').val());
            
            style.boxshadow.y = $('#boxShadowY').val();
            cssBoxShadow += parseInt($('#boxShadowY').val());
                
            style.boxshadow.blur = $('#boxShadowBlur').val();
            cssBoxShadow += parseInt($('#boxShadowBlur').val());
                
            style.boxshadow.spread = $('#boxShadowSpread').val();
            cssBoxShadow += parseInt($('#boxShadowSpread').val());
                
            style.boxshadow.color = $('#boxColor').val();
            cssBoxShadow += $('#boxColor').val();
            
            $(that).css('boxShadow',cssBoxShadow);
            
            // fonts
            var cssFont = "";
            if ($('#fontColor').val()) { 
                style.font.color = $('#fontColor').val();
                $(that).css('color',$('#fontColor').val());
            }
            if ($('#fontFamily option').is(':selected')) { 
                style.font.family = $('#fontFamily').val();
                $(that).css('fontFamily',$('#fontFamily').val());
            }
            if ($('#fontSize option').is(':selected')) { 
                style.font.size = $('#fontSize').val();
                $(that).css('fontSize',$('#fontSize').val());
            }
            if ($('#fontStyle').val()) { 
                style.font.style = $('#fontStyle').val();
                $(that).css('fontStyle','italic');
            }
            if ($('#fontWeight').val()) { 
                style.font.weight = $('#fontWeight').val();
                $(that).css('fontWeight','bold');
            }
            if ($('#textDecoration').val()) { 
                style.font.decoration = $('#textDecoration').val();
                $(that).css('textDecoration',$('#textDecoration').val());
            }
            if ($('#textAlign').val()) { 
                style.font.align = $('#textAlign').val();
                $(that).css('textAlign',$('#textAlign').val());
            }
            if ($('#textTransform').val()) { 
                style.font.transform = $('#textTransform').val();
                $(that).css('textTransform',$('#textTransform').val());
            }
            if ($('#letterSpacing').val()) { 
                style.font.transform = $('#letterSpacing').val();
                $(that).css('letterSpacing',$('#letterSpacing').val());
            }
            if ($('#wordSpacing').val()) { 
                style.font.transform = $('#wordSpacing').val();
                $(that).css('wordSpacing',$('#wordSpacing').val());
            }
            if ($('#lineHeight').val()) { 
                style.font.transform = $('#lineHeight').val();
                $(that).css('lineHeight',$('#lineHeight').val());
            }
            
    elements[cookieName] = style;
    $.jStorage.set('elements',elements);
 }

/**
 * Helper que establece el valor de la alineacion del texto del elemento seleccionado.
 *
 * @return void.
 */
function setAlign(e,v,c) {
    $('#alignLeft').removeClass('align-leftOn');
    $('#alignCenter').removeClass('align-centerOn');
    $('#alignRight').removeClass('align-rightOn');
    $('#alignJustify').removeClass('align-justifyOn');
    $(e).toggleClass(c);
    $('#fontAlign').val(v);
    setStyle();
}

/**
 * Helper que establece el valor de la decoracion del texto del elemento seleccionado.
 *
 * @return void.
 */
function setDecoration(e,v) {
    if (v == 'underline') {
        $('#underline').toggleClass('underlineOn');
        $('#lineThrough').removeClass('line-throughOn');
        if ($('#textDecoration').val() == 'underline') {
            $('#textDecoration').val('none');
        } else {
            $('#textDecoration').val(v);
        }
    }
    if (v == 'line-through') {
        $('#lineThrough').toggleClass('line-throughOn');
        $('#underline').removeClass('underlineOn');
        if ($('#textDecoration').val() == 'line-through') {
            $('#textDecoration').val('none');
        } else {
            $('#textDecoration').val(v);
        }
    }
    setStyle();
}

/**
 * Helper que establece el valor de la decoracion del texto del elemento seleccionado.
 *
 * @return void.
 */
function setWeight(e,v,c) {
    $(e).toggleClass(c);
    if ($('#fontWeight').val() == v) {
        $('#fontWeight').val('');
    } else {
        $('#fontWeight').val(v);
    }
    setStyle();
}

/**
 * Helper que establece el valor de la decoracion del texto del elemento seleccionado.
 *
 * @return void.
 */
function setItalic(e,v,c) {
    $(e).toggleClass(c);
    if ($('#fontStyle').val() == v) {
        $('#fontStyle').val('');
    } else {
        $('#fontStyle').val(v);
    }
    setStyle();
}

/**
 * Helper que establece el valor de la decoracion del texto del elemento seleccionado.
 *
 * @return void.
 */
function setTransform(e,v) {
    if (v == 'uppercase') {
        $('#upper').toggleClass('uppercaseOn');
        $('#lower').removeClass('lowercaseOn');
        $('#capitalize').removeClass('capitalizeOn');
        if ($('#textTransform').val() == v) {
            $('#textTransform').val('');
        } else {
            $('#textTransform').val(v);
        }
    }
    if (v == 'lowercase') {
        $('#lower').toggleClass('lowercaseOn');
        $('#upper').removeClass('uppercaseOn');
        $('#capitalize').removeClass('capitalizeOn');
        if ($('#textTransform').val() == v) {
            $('#textTransform').val('');
        } else {
            $('#textTransform').val(v);
        }
    }
    if (v == 'capitalize') {
        $('#capitalize').toggleClass('capitalizeOn');
        $('#upper').removeClass('uppercaseOn');
        $('#lower').removeClass('lowercaseOn');
        if ($('#textTransform').val() == v) {
            $('#textTransform').val('');
        } else {
            $('#textTransform').val(v);
        }
    }
    setStyle();
}

/**
 * Limpia los campos y los estilos de los fuentes.
 *
 * @return void.
 */
function resetFont() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.font.color = null;
                style.font.family = null;
                style.font.weight = null;
                style.font.style = null;
                style.font.size = null;
                style.font.align = null;
                style.font.decoration = null;
                style.font.transform = null;
                style.font.letterspacing = null;
                style.font.wordspacing = null;
                style.font.lineheight = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('h1').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('h2').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('h3').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('h4').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('h5').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('h6').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('p').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $($('#selector').val()).find('b').css({
        'color': '#000',
        'font': 'none',
        'textDecoration': 'none',
        'textAlign': 'left',
        'letterSpacing': 'normal',
        'wordSpacing': 'normal',
        'lineHeight': 'normal'
    });
    $('#fontColor').val('');
    $('#fontFamily').val('');
    $('#fontWeight').val('');
    $('#fontStyle').val('');
    $('#fontSize').val('');
    $('#fontAlign').val('');
    $('#fontDecoration').val('');
    $('#letterSpacing').val('');
    $('#wordSpacing').val('');
    $('#lineHeight').val('');
    
    $('#letterSpacingSlider').slider('option', 'value', 0);
    $('#wordSpacingSlider').slider('option', 'value', 0);
    $('#lineHeightSlider').slider('option', 'value', 0);
    
    $('#bold').removeClass('boldOn');
    $('#italic').removeClass('italicOn');
    $('#underline').removeClass('underlineOn');
    $('#lineThrough').removeClass('line-throughOn');
    $('#upper').removeClass('uppercaseOn');
    $('#lower').removeClass('lowercaseOn');
    $('#alignLeft').removeClass('align-leftOn');
    $('#alignCenter').removeClass('align-centerOn');
    $('#alignRight').removeClass('align-rightOn');
    $('#alignJustify').removeClass('align-justifyOn');
}

/**
 * Limpia los campos y los estilos del fondo.
 *
 * @return void.
 */
function resetBackground() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.background.color = null;
                style.background.image = null;
                style.background.repeat = null;
                style.background.positionX = null;
                style.background.positionY = null;
                style.background.attachment = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'background': 'none'
    });
    $('#backgroundColor').val('');
    $('#backgroundImage').val('');
    $('#backgroundRepeat').val('');
    $('#backgroundPositionX').val('');
    $('#backgroundPositionY').val('');
    $('#backgroundAttachment').removeAttr('checked');
}

/**
 * Limpia los campos y los margenes externos.
 *
 * @return void.
 */
function resetMargin() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.margin.all = null;
                style.margin.top = null;
                style.margin.right = null;
                style.margin.bottom = null;
                style.margin.left = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'margin': '0px auto'
    });
    $("#marginSlider").slider('option', 'value', 0);
    $("#marginTopSlider").slider('option', 'value', 0);
    $("#marginBottomSlider").slider('option', 'value', 0);
    $("#marginRightSlider").slider('option', 'value', 0);
    $("#marginLeftSlider").slider('option', 'value', 0);
    
    $('#margin').val('');
    $('#marginTop').val('');
    $('#marginBottom').val('');
    $('#marginRight').val('');
    $('#marginLeft').val('');
}

/**
 * Limpia los campos y los margenes externos.
 *
 * @return void.
 */
function resetDimensions() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.dimensions.top = null;
                style.dimensions.left = null;
                style.dimensions.width = null;
                style.dimensions.height = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'width': 'auto',
        'height': 'auto',
        'top': 'auto',
        'left': 'auto',
        'z-index': '1',
        'position':'relative'
    });
    $('#widthSlider').slider('option', 'value', 0);
    $('#heightSlider').slider('option', 'value', 0);
    $('#leftSlider').slider('option', 'value', 0);
    $('#topSlider').slider('option', 'value', 0);
    
    $('#width').val('');
    $('#height').val('');
    $('#position').val('');
    $('#left').val('');
    $('#top').val('');
}

/**
 * Limpia los campos y los margenes internos.
 *
 * @return void.
 */
function resetPadding() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.padding.all = null;
                style.padding.top = null;
                style.padding.right = null;
                style.padding.bottom = null;
                style.padding.left = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'padding': '0px'
    });
    $("#paddingSlider").slider('option', 'value', 0);
    $("#paddingTopSlider").slider('option', 'value', 0);
    $("#paddingBottomSlider").slider('option', 'value', 0);
    $("#paddingRightSlider").slider('option', 'value', 0);
    $("#paddingLeftSlider").slider('option', 'value', 0);
    
    $('#padding').val('');
    $('#paddingTop').val('');
    $('#paddingBottom').val('');
    $('#paddingRight').val('');
    $('#paddingLeft').val('');
}

/**
 * Limpia los bordes.
 *
 * @return void.
 */
function resetBorder() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.border.color = null;
                style.border.style = null;
                style.border.width = null;
                
                style.border.topcolor = null;
                style.border.topstyle = null;
                style.border.topwidth = null;
                
                style.border.rightcolor = null;
                style.border.rightstyle = null;
                style.border.rightwidth = null;
                
                style.border.bottomcolor = null;
                style.border.bottomstyle = null;
                style.border.bottomwidth = null;
                
                style.border.leftcolor = null;
                style.border.leftstyle = null;
                style.border.leftwidth = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'border': 'none'
    });
    $('#borderWidthSlider').slider('option', 'value', 0);
    $('#borderLeftWidthSlider').slider('option', 'value', 0);
    $('#borderRightWidthSlider').slider('option', 'value', 0);
    $('#borderBottomWidthSlider').slider('option', 'value', 0);
    
    $('#borderColor').val('');
    $('#borderStyle').val('');
    $('#borderWidth').val('');
    $('#borderTopColor').val('');
    $('#borderTopStyle').val('');
    $('#borderTopWidth').val('');
    $('#borderRightColor').val('');
    $('#borderRightWidth').val('');
    $('#borderBottomColor').val('');
    $('#borderBottomStyle').val('');
    $('#borderBottomWidth').val('');
    $('#borderLeftColor').val('');
    $('#borderLeftStyle').val('');
    $('#borderLeftWidth').val('');
}

/**
 * Limpia los bordes radius.
 *
 * @return void.
 */
function resetBorderRadius() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.borderradius.all = null;
                style.borderradius.topleft = null;
                style.borderradius.topright = null;
                style.borderradius.bottomleft = null;
                style.borderradius.bottomright = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'borderRadius': 'none'
    });
    
    $('#borderRadiusSlider').slider('option', 'value', 0);
    $('#borderRadiusTopLeftSlider').slider('option', 'value', 0);
    $('#borderRadiusTopRightSlider').slider('option', 'value', 0);
    $('#borderRadiusBottomRightSlider').slider('option', 'value', 0);
    $('#borderRadiusBottomLeftSlider').slider('option', 'value', 0);
    
    $('#borderRadius').val('');
    $('#borderRadiusTopLeft').val('');
    $('#borderRadiusTopRight').val('');
    $('#borderRadiusBottomRight').val('');
    $('#borderRadiusBottomLeft').val('');
}

/**
 * Limpia las sombras.
 *
 * @return void.
 */
function resetBoxShadow() {
    this.elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            if ('#'+el == $('#selector').val()) {
                style.boxshadow.color = null;
                style.boxshadow.inset = null;
                style.boxshadow.x = null;
                style.boxshadow.y = null;
                style.boxshadow.blur = null;
                style.boxshadow.spread = null;
            }
        });
        $.jStorage.set('elements',this.elements);
    }
    $($('#selector').val()).css({
        'boxShadow': 'none',
    });
    
    $('#boxShadowXSlider').slider('option', 'value', 0);
    $('#boxShadowYSlider').slider('option', 'value', 0);
    $('#boxShadowBlurSlider').slider('option', 'value', 0);
    $('#boxShadowSpreadSlider').slider('option', 'value', 0);
    
    $('#boxColor').val('');
    $('#boxShadowX').val('');
    $('#boxShadowY').val('');
    $('#boxShadowBlur').val('');
    $('#boxShadowSpread').val('');
    $('#boxShadowInset').removeAttr('checked');
}

function cleanStyle() {
    var elements = {};
    elements = $.jStorage.get('elements',null);
    if (elements) {
        $.each(elements, function(el,style) {
            $('#selector').val('#'+ el);
            resetFont();
            resetBackground();
            resetMargin();
            resetPadding();
            resetDimensions();
            resetBorder();
            resetBorderRadius();
            resetBoxShadow();
        });
        $.removeCookie('elements');
        $.removeCookie('currentBlock');
        window.location.reload();
    }
}

function copyStyle() {
    var cookieName = $.jStorage.get('currentBlock','');
    if (cookieName.length == 0 || !cookieName) {
        alert("Debes seleccionar un elemento de la pagina para copiar");
        return false;
    }
    var elements = $.jStorage.get('elements',{});
    if (typeof elements[cookieName] != 'undefined') {
        $.jStorage.set('clipboardStyle',elements[cookieName]);
    } else {
        alert("No hay nada para copiar");
        return false;
    }
}

function pasteStyle() {
    var cookieName = $.jStorage.get('currentBlock','');
    if (cookieName.length == 0 || !cookieName) {
        alert("Debes seleccionar un elemento de la pagina para pegar");
        return false;
    }
    if (!$.jStorage.get('clipboardStyle',null)) {
        alert("No hay nada para pegar");
        return false;
    } else {
        var elements = $.jStorage.get('elements',null);
        elements[cookieName] = $.jStorage.get('clipboardStyle',{});
        $.jStorage.set('elements',elements);
        renderPanels($("#selector").val());
        setStyle();
    }
}

function saveStyle(url) {
    var theme_id = getUrlVars()["theme_id"];
    
    if (typeof theme_id != 'undefined') {
        var data = {};
        elementsToSave = $.jStorage.get('elements',null);
        $.each(elementsToSave, function(selector,properties) {
            selector = selector.replace(' ','%20');
            data[selector] = properties;
        /* descomentar si la solicitud es muy grande
            $.post(url,data);
        */
        });
        $.post(url,data);
    }
}

function printStyle() {
    /** @todo funciones para imprimir el estilo convertir el objeto json a string con estructura ecmas */
}
function getParentId(el) {
    var parent = el.parent();
        
    if (parent.length > 0 
    && $(parent).prop('tagName').toLowerCase() != 'html' 
    && $(parent).prop('tagName').toLowerCase() != 'body'
    && $(el).prop('tagName').toLowerCase() != 'body') 
    {
        var id = $(parent).attr('id');
        if (id === undefined || id === 0) {
            return getParentId($(parent));
        } else {
            return id;
        }
    } else {
        return false;
    }
}

/**
 * Reconoce todos los elementos administrables y le asigna los botones de las acciones
 *
 * @param elements object con los elementos a administrar
 * @param areChildrens boolean si los elementos pasados son hijos de otro 
 * @return void.
 */
function addAdminControls(elements,areChildrens) {
    if (typeof areChildrens == 'undefined') {
        areChildrens = false;
    }
    $.each(elements, function(i,el) {
        if (!$(el.selector) && !areChildrens) {
            return true;
        } else {
            if (areChildrens) {
                ele = $(el);
            } else {
                ele = $(el.selector);
            }
            $(ele).each(function() {
                var that = $(this);
                
                if (that.hasClass('administrable')) {
                    return true;
                }
            
                if (!that.attr('id') || that.attr('id').length == 0) {
                    that.attr('id','widget-'+ getParentId(that) +'-'+ this.tagName.toLowerCase() +'-'+ that.index());
                }
                
                var html = "";
                html += '<div class="actions actions'+ i +'">';
                html += '<a class="admin-icons style" onclick="renderPanels(\'#' + that.attr('id') +'\');slidePanel(\'style\',false)"></a>';
                
                if (el.canConfig) {
                    html += '<a class="admin-icons config" onclick=""></a>';
                }
                
                if (el.canMove) {
                    html += '<a class="admin-icons move"></a>';
                }
                
                if (el.canDelete) {
                    html += '<a class="admin-icons delete" onclick=""></a>';
                }
                
                /*  */
                html += '</div>';
            
                that.addClass('administrable').prepend(html);
                that.find('.actions'+ i).mouseenter(function(e) {
                    that.css({
                        border:'dashed 1px #900'
                    });
                }).mouseleave(function(e) {
                    that.css({
                        border:'none'
                    });
                });
                if (!areChildrens && typeof el.childrens != 'undefined') {
                    addAdminControls(el.childrens,true);
                }
            });
        }
    });
}