/**
 *
 * NecoTolltips
 * Author: Yosiet Serga
 * Version: 1.0.0
 * 
 * Dual licensed under the MIT and GPL licenses
 * 
 */
;(function ( $, window, document, undefined ) {
    
    var pluginName = "ntTips",
        defaults = {
            classname:  'neco-tips',
            closeButton: true,
            ajax:       false,
            type:       'get',
            dataType:   'json',
            loading:    {
                title:'Cargando...',
                image:'../loader.gif',
                classname:'neco-tips-loading'
            },
            error:      {
                classname:'neco-tips-error',
                text:'Lo sentimos pero no se pudo cargar el contenido',
            },
            start:     function(){},
            stop:     function(){},
            beforeSend: function(){},
            complete:   function(){},
            success:    function(){}
        },
        data = {};

    function _ntTips( element, options ) {
        this.element = element;
        this.options = $.extend( {}, this.defaults, options );
        this._name = pluginName;
        this.init();
    }

    _ntTips.prototype = {
        init: function() {
            this._start();
            this._render();
            $(body,html).on('hover',function(e){
                $(this.container).show();
            }, function(e){
                $(this.container).hide();
            });
            this._stop();
        },
        _start: function() {
            if (typeof this.options.start == "function") {
                this.options.start();
            }
        },
        _stop: function() {
            if (typeof this.options.stop == "function") {
                this.options.stop();
            }
        },
        _render: function() {
            this._renderBox();
        },
        _renderBox: function() {
            this.container = $(document.createElement('div'))
            .addClass('neco-tips-container')
            .appendTo(this.element);
            
            if (this.element.data('target')) {
                clone = $(this.element.data('target')).get(0);
                this.container.html(clone);
            } else if (this.options.target.length) {
                clone = $(this.options.target).get(0);
                this.container.html(clone);
            } else {
                this.container.html(this.element.attr('title'));
            }
        }
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "nt_" + pluginName)) {
                $.data(this, "nt_" + pluginName, new _ntTips( this, options ));
            }
        });
    };
})( jQuery, window, document );