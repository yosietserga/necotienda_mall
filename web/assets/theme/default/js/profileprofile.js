window.App = {};

App.getInfo = function(profile) {
    $.getJSON('index.php?r=profile/information/infobox&profile_id='+ profile).then(function(data){
        if (data.error) {
            return null;
        } else {
            App.renderInfo(data);
            return data;
        }
    });
};

App.renderInfo = function(data) {
    html = '<a href="'+ data.profile +'/informacion" style="float:right">Ver M&aacute;s</a>';
    html += '<ul>';
    if (data.rif && data.company) {
        html += '<li><i class="fa fa-building-o"></i>'+ data.company +' '+ data.rif +'</li>';
    }
    if (data.telephone) {
        html += '<li><i class="fa fa-phone"></i><a href="tel:'+ data.telephone +'">'+ data.telephone +'</a></li>';
    }
    if (data.mapAddress) {
        html += '<li><a href="'+ data.profile +'/informacion/mapa" style="float:right" title="Ver Mapa"><img src="assets/images/data/google-map.png" alt="Ver Mapa" /></a></li>';
    }
    html += '</ul>';
    
    container = App.renderContainer({
        title:'Informaci&oacute;n',
        class:'grid_4 profileBox',
        html: html        
    });
};

App.getSellerInfo = function(profile) {
    $.getJSON('index.php?r=profile/sales/rateBox&profile_id='+ profile).then(function(data){
        if (data.error) {
            return null;
        } else {
            App.renderSellerInfo(data);
            return data;
        }
    });
};

App.renderSellerInfo = function(data) {
    html = '<a href="'+ data.profile +'/ventas" style="float:right">Ver M&aacute;s</a>';
    html += '<table><tr><td>';
    html += '<table>';
    var total = 0;
    $.each(data.ratings, function(i, item){
        total = (total + item.percent * 1);
        if (item.review_type === 'seller_responsibility') {
            html += '<tr><td>Responabilidad:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'seller_customer_care') {
            html += '<tr><td>Atenci&oacute;n Al Cliente:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'seller_response_time') {
            html += '<tr><td>Tiempo de Respuesta:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'seller_quality') {
            html += '<tr><td>Calidad de Productos:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'seller_fair_prices') {
            html += '<tr><td>Precios Justos:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
    });
    html += '</table>';
    html += '</td><td>';
    
    html += '<font class="points">'+ total / 5 +'</font>';
    html += '</td></tr></table>';
    
    html += '<table><tr><td>';
    html += 'Publicaciones: '+ data.products;
    html += '</td><td>';
    html += 'Pedidos: '+ data.qty_orders;
    html += '</td></tr>';
    html += '<tr><td>';
    html += 'Ventas: '+ data.qty_sales;
    html += '</td><td>';
    html += 'Devoluciones: '+ data.qty_returns;
    html += '</td></tr></table>';
    
    container = App.renderContainer({
        title:'Como Vendedor',
        class:'grid_4 profileBox',
        html: html        
    });
};

App.getBuyerInfo = function(profile) {
    $.getJSON('index.php?r=profile/buys/rateBox&profile_id='+ profile).then(function(data){
        if (data.error) {
            return null;
        } else {
            App.renderBuyerInfo(data);
            return data;
        }
    });
};

App.renderBuyerInfo = function(data) {
    html = '<a href="'+ data.profile +'/ventas" style="float:right">Ver M&aacute;s</a>';
    html += '<table><tr><td>';
    html += '<table>';
    var total = 0;
    $.each(data.ratings, function(i, item){
        total = (total + item.percent * 1);
        if (item.review_type === 'buyer_responsibility') {
            html += '<tr><td>Responabilidad:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'buyer_amability') {
            html += '<tr><td>Amabilidad:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'buyer_response_time') {
            html += '<tr><td>Tiempo de Respuesta:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'buyer_patience') {
            html += '<tr><td>Paciencia:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
        if (item.review_type === 'buyer_good_pay') {
            html += '<tr><td>Buena Paga:</td><td><span title="'+ item.percent +'%" style="padding:0px;background:#F75959;width:'+ (100 - item.percent) +'px !important; height:5px; display:block; border-left:solid '+ item.percent +'px #4DD663;"></span></td></tr>';
        }
    });
    html += '</table>';
    html += '</td><td>';
    
    html += '<font class="points">'+ total / 5 +'</font>';
    html += '</td></tr></table>';
    
    html += '<table><tr><td>';
    html += 'Pedidos: '+ data.qty_orders;
    html += '</td><td>';
    html += 'Anulados: '+ data.qty_nulled;
    html += '</td></tr>';
    html += '<tr><td>';
    html += 'Concretadas: '+ data.qty_sales;
    html += '</td><td>';
    html += 'Devoluciones: '+ data.qty_returns;
    html += '</td></tr></table>';
    
    container = App.renderContainer({
        title:'Como Comprador',
        class:'grid_4 profileBox',
        html: html        
    });
};

App.renderContainer = function(params) {
    container = $(document.createElement('div')).attr({
        class: 'profileBox box '+ params.class
    })
    .html(params.html)
    .prepend('<h2>'+ params.title +'</h2>')
    .appendTo('#profileSideBar')
    .after('<div class="clear"></div>');
};