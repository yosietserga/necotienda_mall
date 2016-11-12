/**
 * NecoWizard
 * Author: Yosiet Serga
 * Version: 1.0.0
 * 
 * Dual licensed under the MIT and GPL licenses
 * 
 */
(function($) {
    $.fn.ntWizard = function(method) {
        var defaults = {
            navControl: 'li',
            handle: 'div',
            nextText: 'Siguiente',
            prevText: 'Atr\u00E1s',
            ignoreText: 'Omitir',
            showIgnore: false,
            begin: 0,
            error:      {
                classname:'neco-form-error',
                text:'Lo sentimos pero no se pudo procesar el formulario',
            },
            options:    {},
            create:     function(){},
            start: function(){},
            stop:   function(){},
            next:   function(){},
            prev:   function(){},
            ignore: function(){}
        };
        
        var settings = {};
        var data = {};
        var methods = {
            init : function(options) {
                return this.each(function() {
                    settings = $.extend({}, defaults, options);
                    data.element = $(this);
                    helpers._create();
                });
            }
        };
 
        var helpers = {
            _create: function() {

                if ($(data.element).length > 0) {
                    $(data.element).addClass('neco-wizard');
                    
                    data.navCount = data.stepCount = 0;
                    
                    $('.neco-wizard-controls').find(settings.navControl).each(function(e) {
                        $(this).addClass('neco-wizard-control').attr({
                            'id':'necoWizardControl_' + ($(this).index() + 1) 
                        });
                        if ($(this).index() == 0 && !settings.begin) {
                            $(this).show().addClass('neco-wizard-control-active');
                        } else if ($(this).index() == settings.begin) {
                            $(this).show().addClass('neco-wizard-control-active');
                        }
                        data.navCount++;
                    });
                    
                    $('.neco-wizard-steps > ' + settings.handle).each(function(e) {
                        $(this).addClass('neco-wizard-step').attr({
                            'id':'necoWizardStep_' + ($(this).index() + 1) 
                        }).hide();
                        if ($(this).index() == 0 && !settings.begin) {
                            $(this).show().addClass('neco-wizard-step-active');
                        } else if ($(this).index() == settings.begin) {
                            $(this).show().addClass('neco-wizard-step-active');
                        }
                        data.stepCount++;
                    });
                    
                    /* if (navCount == stepCount) { */
                        
                        var nextBtn = $(document.createElement('a')).attr({
                            'class':'button neco-wizard-next'
                        })
                        .text(settings.nextText)
                        .on('click',function(e){
                            helpers._next(e);
                        });
                        
                        var prevBtn = $(document.createElement('a')).attr({
                            'class':'button neco-wizard-prev'
                        })
                        .text(settings.prevText)
                        .on('click',function(e){
                            helpers._prev(e);
                        });
                        
                        var ignoreBtn = $(document.createElement('a')).attr({
                            'class':'button neco-wizard-ignore'
                        })
                        .text(settings.ignoreText)
                        .on('click',function(e){
                            helpers._ignore(e);
                        });
                        
                        data.element.append(nextBtn);
                        data.element.append(prevBtn);
                        if (settings.ignoreShow) {data.element.append(ignoreBtn);}
                    /* } else {
                        //TODO: error, no cargar wizard
                    } */
                    
                }
                if (typeof settings.create == 'function') {
                    settings.create(data.element);
                }
            },
            _start: function() {
                if (typeof settings.start == "function") {
                    settings.start();
                }
            },
            _stop: function() {
                if (typeof settings.stop == "function") {
                    settings.stop();
                }
            },
            _next: function(e) {
                helpers._start();
                
                if (typeof settings.next == "function") {
                    error = settings.next(data);
                    
                    if (typeof error != 'undefined' && error) {
                        return false;
                    } else {
                        var c = $('.neco-wizard-step-active');
                        var n = c.next();
                        var cc = $('.neco-wizard-control-active');
                        var cn = cc.next();
                        
                        c.animate({
                            opacity:0,
                            marginLeft:'-' + $('.neco-wizard-steps').width(),
                            marginTop:'-' + $('.neco-wizard-steps').height()
                        }).css({
                            display:'none'
                        }).removeClass('neco-wizard-step-active');
                        
                        $(n).css({
                            marginLeft:$('.neco-wizard-steps').width() + 'px',
                            marginTop:$('.neco-wizard-steps').height() + 'px',
                            display:'block'
                        }).animate({
                            opacity:1,
                            marginLeft:'0px',
                            marginTop:'0px',
                        }).addClass('neco-wizard-step-active');
                        
                        cc.removeClass('neco-wizard-control-active').addClass('neco-wizard-control-done');
                        cn.removeClass('neco-wizard-control-done').addClass('neco-wizard-control-active');
                    }
                } else {
                        var c = $('.neco-wizard-step-active');
                        var n = c.next();
                        var cc = $('.neco-wizard-control-active');
                        var cn = cc.next();
                        
                        c.animate({
                            opacity:0,
                            marginLeft:'-' + $('.neco-wizard-steps').width(),
                            marginTop:'-' + $('.neco-wizard-steps').height()
                        }).css({
                            display:'none'
                        }).removeClass('neco-wizard-step-active');
                        
                        $(n).css({
                            marginLeft:$('.neco-wizard-steps').width() + 'px',
                            marginTop:$('.neco-wizard-steps').height() + 'px',
                            display:'block'
                        }).animate({
                            opacity:1,
                            marginLeft:'0px',
                            marginTop:'0px',
                        }).addClass('neco-wizard-step-active');
                        
                        cc.removeClass('neco-wizard-control-active').addClass('neco-wizard-control-done');
                        cn.removeClass('neco-wizard-control-done').addClass('neco-wizard-control-active');
                }
                
                helpers._stop();
            },
            _prev: function() {
                helpers._start();
                
                if (typeof settings.prev == "function") {
                    res = settings.prev();
                    
                    if (typeof res != 'undefined' && res.error) {
                        
                    } else {
                        var c = $('.neco-wizard-step-active');
                        var b = c.prev();
                        var cc = $('.neco-wizard-control-active');
                        var cb = cc.prev();
                        
                        c.animate({
                            opacity:0,
                            marginLeft:'-' + $('.neco-wizard-steps').width(),
                            marginTop:'-' + $('.neco-wizard-steps').height()
                        }).css({
                            display:'none'
                        }).removeClass('neco-wizard-step-active');
                        
                        $(b).css({
                            marginLeft:$('.neco-wizard-steps').width() + 'px',
                            marginTop:$('.neco-wizard-steps').height() + 'px',
                            display:'block'
                        }).animate({
                            opacity:1,
                            marginLeft:'0px',
                            marginTop:'0px',
                        }).addClass('neco-wizard-step-active');
                        
                        cc.removeClass('neco-wizard-control-active').removeClass('neco-wizard-control-done');
                        cb.removeClass('neco-wizard-control-done').addClass('neco-wizard-control-active');
                    }
                } else {
                    var c = $('.neco-wizard-step-active');
                    var b = c.prev();
                    var cc = $('.neco-wizard-control-active');
                    var cb = cc.prev();
                            
                    c.animate({
                        opacity:0,
                        marginLeft:'-' + $('.neco-wizard-steps').width(),
                        marginTop:'-' + $('.neco-wizard-steps').height()
                    }).css({
                        display:'none'
                    }).removeClass('neco-wizard-step-active');
                            
                    $(b).css({
                        marginLeft:$('.neco-wizard-steps').width() + 'px',
                        marginTop:$('.neco-wizard-steps').height() + 'px',
                        display:'block'
                    }).animate({
                        opacity:1,
                        marginLeft:'0px',
                        marginTop:'0px',
                    }).addClass('neco-wizard-step-active');
                            
                    cc.removeClass('neco-wizard-control-active').addClass('neco-wizard-control-done');
                    cb.removeClass('neco-wizard-control-done').addClass('neco-wizard-control-active');
                }
                helpers._stop();
            },
            _ignore: function() {
                if (typeof settings.ignore == "function") {
                    settings.ignore();
                }
            }
        };
        
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error( 'Method "' +  method + '" does not exist in ntWizard plugin!');
        }
    }
})(jQuery);