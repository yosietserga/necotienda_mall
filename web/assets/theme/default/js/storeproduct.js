function overlayHelper() {
    div = $(document.createElement('div')).attr({
        'class':'background'
    }).css({
        'height': $(window).height() +'px',
        'width': $(window).width() +'px'
    }).on('click',function(e){
        $('#overlayTemp').remove();
    });
    
    span = $(document.createElement('span')).attr({
        'class':'content'
    }).css({
        'margin':'0px '+ ($(window).width() * 0.15) +'px',
        'height': ($(window).height() * 0.7) +'px',
        'width': ($(window).width() * 0.7) +'px'
    });
    
    a = $(document.createElement('a')).attr({
        'class':'close'
    }).css({
        'left': (($(window).width() * 0.85)) +'px'
    }).html('X').on('click',function(e){
        $('#overlayTemp').remove();
    });
    
    $(document.createElement('div')).attr({
        'id':'overlayTemp'
    })
    .append(div)
    .append(span)
    .append(a)
    .appendTo('body');
}

function loginForm(httpHome,token) {
    if (!$('#overlayTemp')) {
        overlayHelper();
    }
    
    if (!$.ui) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/vendor/jquery-ui.min.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
        
        $(document.createElement('link')).attr({
            'href' : httpHome + 'assets/css/jquery-ui/jquery-ui.min.css',
            'rel': 'stylesheet',
            'media': 'all'
        }).appendTo('head');
    }
    
    if (!$.fn.ntForm) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/necojs/neco.form.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
        
        $(document.createElement('link')).attr({
            'href' : httpHome + 'assets/css/neco.form.css',
            'rel': 'stylesheet',
            'media': 'all'
        }).appendTo('head');
    }
    
    if (!$.fn.crypt) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/vendor/jquery.crypt.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
    }
    
    /* login form */
    inputEmail = $(document.createElement('input')).attr({
        'type':'email',
        'name':'email',
        'required':'required',
        'id':'productLoginEmail',
        'placeholder':'Ingrese su email'
    }).after('<div class="clear"></div>');
    
    inputPwd = $(document.createElement('input')).attr({
        'type':'password',
        'name':'password',
        'required':'required',
        'id':'productLoginPassword',
        'placeholder':'password'
    }).after('<div class="clear"></div>');
    
    submit = $(document.createElement('a')).attr({
        'class':'button',
        'title':'Login',
    })
    .html('Login')
    .after('<div class="clear"></div>')
    .on('click',function(e){
        $('.message').remove();
        if (inputPwd.val().length && inputEmail.val().length) {
            $(this).hide();
            $.post(httpHome + 'index.php?r=account/login/header',
            {
                email:inputEmail.val(),
                password:inputPwd.crypt({method:'md5'}),
                token:token
            },
            function(response) {
                var data = $.parseJSON(response);
                if (data.error==1) {
                    window.location.href = httpHome + 'index.php?r=account/login&error=true'
                } else {
                    $(this).show();
                    window.location.reload();
                }
            });
        } else {
            $(document.createElement('div')).attr({
                'class':'message warning'
            })
            .html('Debes ingresar tu Email y la contrase&ntilde;a de tu cuenta. Si aun no te has registrado, por favor rellena el formulario de la derecha.')
            .after(this);
        }
    });
    
    recovery = $(document.createElement('a')).attr({
        'title':'Recuperar Contrase&ntilde;a',
        'href':httpHome + 'index.php?r=account/forgotten',
    })
    .html('Recuperar Contrase&ntilde;a')
    .after('<div class="clear"></div>');
    
    title = $(document.createElement('div')).attr({
        'class':'header'
    })
    .html('<hgroup><h1>Iniciar Sesi&oacute;n</h1></hgroup>')
    .after('<div class="clear"></div>');
    
    container = $(document.createElement('div')).attr({
        'class':'grid_6'
    }).appendTo('#overlayTemp span.content');
    
    container2 = container.clone();
    
    form = $(document.createElement('form')).attr({
        'action':httpHome + 'index.php?r=account/login'
    })
    .append(title)
    .append(inputEmail)
    .append(inputPwd)
    .append(submit)
    .append(recovery)
    .appendTo(container)
    .ntForm({
        'submitButton':false,
        'cancelButton':false,
        'lockButton':false
    });
    
    /* register form */
    inputEmail2 = inputEmail.clone();
    inputEmail2.attr('id','pREmail');
    
    inputFirstname = $(document.createElement('input')).attr({
        'type':'text',
        'name':'firstname',
        'id':'pRFirstname',
        'required':'required',
        'placeholder':'Ingrese sus nombres'
    }).after('<div class="clear"></div>');
    
    inputLastname = $(document.createElement('input')).attr({
        'type':'text',
        'name':'lastname',
        'id':'pRLastname',
        'required':'required',
        'placeholder':'Ingrese sus apellidos'
    }).after('<div class="clear"></div>');
    
    title = $(document.createElement('div')).attr({
        'class':'header'
    })
    .html('<hgroup><h1>Crear Cuenta</h1></hgroup>')
    .after('<div class="clear"></div>');
    
    form = $(document.createElement('form')).attr({
        'action':httpHome + 'index.php?r=account/register',
        'method':'post'
    })
    .append(title)
    .append(inputFirstname)
    .append(inputLastname)
    .append(inputEmail2)
    .appendTo(container2)
    .ntForm({
        lockButton:false,
        url:httpHome + 'index.php?r=account/register'
    });
    
    container2.appendTo('#overlayTemp span.content');
    
    $(document.createElement('div')).attr({
        'class':'grid_12'
    })
    .html('<p>Su seguridad es muy importante para nosotros y por eso es necesario que para cualquier pregunta, contacto o compra que desee realizar, debe estar previamente registrado y haber iniciado sesi&oacute;n.</p>')
    .appendTo('#overlayTemp span.content');
    
    resizeLightbox(840);
    $(window).on('resize',function(e){
        resizeLightbox(840);
    });
}

function contactForm(data,httpHome,token) {
    if (!$('#overlayTemp')) {
        overlayHelper();
    }
    
    if (!$.ui) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/vendor/jquery-ui.min.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
        
        $(document.createElement('link')).attr({
            'href' : httpHome + 'assets/css/jquery-ui/jquery-ui.min.css',
            'rel': 'stylesheet',
            'media': 'all'
        }).appendTo('head');
    }
    
    if (!$.fn.ntForm) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/necojs/neco.form.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
        
        $(document.createElement('link')).attr({
            'href' : httpHome + 'assets/css/neco.form.css',
            'rel': 'stylesheet',
            'media': 'all'
        }).appendTo('head');
    }
    
    inputMsg = $(document.createElement('textarea')).attr({
        'name':'message',
        'required':'required',
        'id':'message',
        'placeholder':'Ingresa tu mensaje'
    }).css({
        width:'390px'
    }).after('<div class="clear"></div>');
    
    inputTo = $(document.createElement('input')).attr({
        'name':'to',
        'type':'hidden',
        'id':'to'
    })
    .val(data.seller_id);
    
    inputSubject = $(document.createElement('input')).attr({
        'name':'subject',
        'type':'hidden',
        'id':'subject'
    })
    .val('Contacto Nuevo de '+ data.buyer_name);
    
    submit = $(document.createElement('a')).attr({
        'class':'button',
        'title':'Contactar',
    })
    .html('Enviar Mensaje')
    .after('<div class="clear"></div>')
    .on('click',function(e){
        $('.message').remove();
        if (inputMsg.val().length) {
            $(this).hide();
            $.post(httpHome + 'index.php?r=account/message/send',
            {
                subject:inputSubject.val(),
                message:inputMsg.val(),
                to:inputTo.val(),
                token:token
            }).then(function(response) {
                var data = $.parseJSON(response);
                if (data.error!=1) {
                    $('#overlayTemp').remove();
                }
                $(this).show();
            });
        } else {
            $(document.createElement('div')).attr({
                'class':'message warning'
            })
            .after(this);
        }
    });
    
    title = $(document.createElement('div')).attr({
        'class':'header'
    })
    .html('<hgroup><h1>Contactar Al Anunciante</h1></hgroup>')
    .after('<div class="clear"></div>');
    
    container = $(document.createElement('div')).attr({
        'class':'grid_6'
    }).appendTo('#overlayTemp span.content');
    
    form = $(document.createElement('form')).attr({
        
    })
    .append(title)
    .append(inputMsg)
    .append(inputSubject)
    .append(inputTo)
    .append(submit)
    .appendTo(container)
    .ntForm({
        'submitButton':false,
        'cancelButton':false,
        'lockButton':false
    });
    
    div = $(document.createElement('div')).attr({
        'class':'grid_7'
    })
    .appendTo('#overlayTemp span.content');
    
    resizeLightbox(420);
    $(window).on('resize',function(e){
        resizeLightbox(420);
    });
}

function buyForm(data,httpHome,token) {
    if (!$('#overlayTemp')) {
        overlayHelper();
    }
    
    if (!$.ui) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/vendor/jquery-ui.min.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
        
        $(document.createElement('link')).attr({
            'href' : httpHome + 'assets/css/jquery-ui/jquery-ui.min.css',
            'rel': 'stylesheet',
            'media': 'all'
        }).appendTo('head');
    }
    
    if (!$.fn.ntForm) {
        $(document.createElement('script')).attr({
            'src' : httpHome + 'assets/js/necojs/neco.form.js',
            'type': 'text/javascript',
            'async': 1
        }).appendTo('head');
        
        $(document.createElement('link')).attr({
            'href' : httpHome + 'assets/css/neco.form.css',
            'rel': 'stylesheet',
            'media': 'all'
        }).appendTo('head');
    }
    
    inputQty = $(document.createElement('input')).attr({
        'name':'quantity',
        'type':'number',
        'required':'required',
        'placeholder':'Cantidad',
        'id':'quantity'
    }).val('1').after('<div class="clear"></div>');
    
    inputSellerId = $(document.createElement('input')).attr({
        'name':'seller_id',
        'type':'hidden',
        'id':'seller_id'
    })
    .val(data.seller_id);
    
    inputProductId = $(document.createElement('input')).attr({
        'name':'product_id',
        'type':'hidden',
        'id':'product_id'
    })
    .val(data.product_id);
    
    submit = $(document.createElement('a')).attr({
        'class':'button',
        'title':'Comprar',
    })
    .html('Comprar')
    .after('<div class="clear"></div>')
    .on('click',function(e){
        $('.message').remove();
        if (inputQty.val().length) {
            $(this).hide();
            $.post(httpHome + 'index.php?r=checkout/cart/json',
            {
                product_id:inputProductId.val(),
                quantity:inputQty.val(),
                seller_id:inputSellerId.val(),
                token:token
            }).then(
            function(response) {
                var data = $.parseJSON(response);
                if (data.error!=1) {
                    $('#overlayTemp').remove();
                }
                $(this).show();
            });
        } else {
            /* error debe ingresar la cantidad */
        }
    });
    
    title = $(document.createElement('div')).attr({
        'class':'header'
    })
    .html('<hgroup><h1>Comprar</h1></hgroup>')
    .after('<div class="clear"></div>');
    
    container = $(document.createElement('div')).attr({
        'class':'grid_6'
    }).appendTo('#overlayTemp span.content');
    
    form = $(document.createElement('form')).attr({
        
    })
    .append(title)
    .append(inputQty)
    .append(inputProductId)
    .append(inputSellerId)
    .append(submit)
    .appendTo(container)
    .ntForm({
        'submitButton':false,
        'cancelButton':false,
        'lockButton':false
    });
    
    div = $(document.createElement('div')).attr({
        'class':'grid_7'
    })
    .appendTo('#overlayTemp span.content');
    
    resizeLightbox(420);
    $(window).on('resize',function(e){
        resizeLightbox(420);
    });
}

function resizeLightbox(width,height) {
    if (typeof width == 'undefined') {
        width = $(window).width() * 0.7;
        height = $(window).height() * 0.7;
    }
    
    if (width < $(window).width()) {
        var marginLeft = ($(window).width() - width) / 2;
        var left = marginLeft + width;
    } else {
        width = $(window).width() * 0.7;
        var marginLeft = $(window).width() * 0.15;
        var left = $(window).width() * 0.85;
    }
    
    $('#overlayTemp').css({
        'height': $(window).height() +'px',
        'width': $(window).width() +'px'
    });
    
    $('#overlayTemp span.content').css({
        'margin':'0px '+ marginLeft +'px',
        'height': height +'px',
        'width': width +'px'
    });
        
    $('#overlayTemp a.close').css({
        'left': left +'px'
    });
}

function productContact(isLogged,httpHome,token,data) {
    overlayHelper();
    if (!isLogged) {
        loginForm(httpHome,token);
    } else {
        /*
            // mostrar formulario de mensajer�a
            // enviarle al anunciante un email diciendo que el usuario lo ha contactado
            // registrar mensaje con sus asociaciones
            // mostrar mensaje de Mensaje Enviado
        */
        contactForm(data,httpHome,token);
    }
}

function productCart(isLogged,httpHome,token) {
    overlayHelper();
    if (!isLogged) {
        loginForm(httpHome,token);
    } else {
        buyForm(data,httpHome,token)
        /*
            // mostrar formulario para indicar la cantidad a comprar, forma de pago y de env�o
            // mostrar en el formulario la cantidad disponible del anuncio
            // al aceptar, crear pedido con la cantidad, forma de pago y forma de env�o seleccionados
            // notificar al anunciante
            // enviar al comprador la url con los pasos a seguir seg�n el anunciante
        */
    }
}