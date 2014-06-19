<?php echo $header; ?>
<?php echo $navigation; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div class="grid_10" id="content">
      <h1><?php echo $heading_title; ?></h1>
      
  <?php if (isset($mostrarError)) echo $mostrarError; ?>
  <div class="middle">
    <?php foreach ($orders as $order) { ?>
    <div style="display: inline-block; margin-bottom: 10px; width: 100%;">
      <div style="width: 49%; float: left; margin-bottom: 2px;"><b><?php echo $text_order; ?></b> #<?php echo $order['order_id']; ?></div>
      <div style="width: 49%; float: right; margin-bottom: 2px; text-align: right;"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></div>
      <div class="content" style="clear: both; padding: 5px;">
        <div style="padding: 5px;">
          <table>
            <tr>
              <td><?php echo $text_date_added; ?> <?php echo $order['date_added']; ?></td>
              <td><?php echo $text_customer; ?> <?php echo $order['name']; ?></td>
              <td rowspan="2" style="text-align: right;"><a title="<?php echo $button_view; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $order['href']); ?>'" class="button"><span><?php echo $button_view; ?></span></a></td>
            </tr>
            <tr>
              <td><?php echo $text_products; ?> <?php echo $order['products']; ?></td>
              <td><?php echo $text_total; ?> <?php echo $order['total']; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="pagination"><a title="<?php echo $button_back; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a><?php echo $pagination; ?></div>
  <div>
   <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $transferencia_heading; ?></h1>
    </div>
  </div>
  <div class="middle">
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="transferencia">
    <?php echo isset($fkey)? $fkey : ''; ?>
      <b style="margin-bottom: 2px; display: block;"><?php echo $transferencia_elija_pago; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td width="250"><?php echo $transferencia_elija_pago; ?></td>
            <td>
              <select name="forma_de_pago" id="forma_pago" title="Seleccione una forma de pago" style="width:130px" onchange="forma_pago();">
                <option value="">Seleccione</option>
                <option value="Deposito">Dep&oacute;sito</option>
                <option value="Transferencia">Transferencia</option>
              </select><span class="required">*</span><img class="chequea" id="checkformapago" src="image/check.png">
              <?php if (!empty($error_forma_de_pago)) { ?>
              	<script>
				  $("#forma_pago").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checkformapago").attr("src","image/unchecked.png");
                </script>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $transferencia_order; ?></td>
            <td><input type="text" name="order_id" id="order_id" value="<?php echo $order_id; ?>" title="Ingrese el ID del pedido al que desea reportar el pago"><span class="required">*</span><img class="chequea" id="checkorder" src="image/check.png">
              <?php if (!empty($error_order_id)) { ?>
                <script>
				  $("#order_id").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checkorder").attr("src","image/unchecked.png");
                </script>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $transferencia_nombre; ?></td>
            <td><input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" title="Ingrese su nombre, debe tener al menos 3 caracteres y no se permiten n&uacute;meros ni caracteres especiales"><span class="required">*</span><img class="chequea" id="checknombre" src="image/check.png">
            <?php if (!empty($error_nombre)) { ?>
                <script>
				  $("#nombre").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checknombre").attr("src","image/unchecked.png");
                </script>
             <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $transferencia_numero; ?></td>
            <td><input type="text" name="numero_transaccion" id="numero_transaccion" value="<?php echo $numero_transaccion; ?>" title="Ingrese el n&uacute;mero de dep&oacute;sito o transferencia. Si realiz&oacute; m&aacute;s de un pago para la misma orden, haga un reporte para cada uno"><span class="required">*</span><img class="chequea" id="checknumero" src="image/check.png">
            <?php if (!empty($error_numero_transaccion)) { ?>
                <script>
				  $("#numero_transaccion").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checknumero").attr("src","image/unchecked.png");
                </script>
             <?php } ?>
            </td>
          </tr>
          <tr id="subanco_tabla">
            <td><?php echo $transferencia_subanco; ?></td>
            <td>
            <select name="su_banco" id="su_banco" title="SOLO PARA TRANSFERENCIAS">
              <option value="">Seleccione</option>
              <option value="Banco Caroni">Banco Caroní</option>
              <option value="Banco Central de Venezuela">Banco Central de Venezuela</option>
              <option value="Banco de Venezuela">Banco de Venezuela</option>
              <option value="Banco del Caribe">Banco del Caribe</option>
              <option value="Banco Exterior">Banco Exterior</option>
              <option value="Banco Fondo Comun">Banco Fondo Común</option>
              <option value="Banco Guayana">Banco Guayana</option>
              <option value="Banco Industrial de Venezuela">Banco Industrial de Venezuela</option>
              <option value="Banco Mercantil">Banco Mercantil</option>
              <option value="Banco Nacional de Credito">Banco Nacional de Crédito</option>
              <option value="Banco Occidental de Descuento">Banco Occidental de Descuento</option>
              <option value="Banco Plaza">Banco Plaza</option>
              <option value="Banco Provincial">Banco Provincial</option>
              <option value="Banco Sofitasa">Banco Sofitasa</option>
              <option value="Banco Venezolano de Credito">Banco Venezolano de Crédito</option>
              <option value="Bancoro">Bancoro</option>
              <option value="Banesco">Banesco</option>
              <option value="Banfoandes">Banfoandes</option>
              <option value="Banorte">Banorte</option>
              <option value="Banplus">Banplus</option>
              <option value="Casa Propia">Casa Propia</option>
              <option value="Corp Banca">Corp Banca</option>
              <option value="Del Sur">Del Sur</option>
              <option value="Fondo Comun">Fondo Común</option>
            </select><span class="required">*</span><img class="chequea" id="checksubanco" src="image/check.png">
            </td>
          </tr>
          <tr>
            <td><?php echo $transferencia_mibanco; ?></td>
            <td><select name="mi_banco" id="mi_banco" title="Seleccione el banco donde realiz&oacute; el pago">
              <option value="">Seleccione</option>
              <option value="Banco Caroni">Banco Caroní</option>
              <option value="Banco Central de Venezuela">Banco Central de Venezuela</option>
              <option value="Banco de Venezuela">Banco de Venezuela</option>
              <option value="Banco del Caribe">Banco del Caribe</option>
              <option value="Banco Exterior">Banco Exterior</option>
              <option value="Banco Fondo Comun">Banco Fondo Común</option>
              <option value="Banco Guayana">Banco Guayana</option>
              <option value="Banco Industrial de Venezuela">Banco Industrial de Venezuela</option>
              <option value="Banco Mercantil">Banco Mercantil</option>
              <option value="Banco Nacional de Credito">Banco Nacional de Crédito</option>
              <option value="Banco Occidental de Descuento">Banco Occidental de Descuento</option>
              <option value="Banco Plaza">Banco Plaza</option>
              <option value="Banco Provincial">Banco Provincial</option>
              <option value="Banco Sofitasa">Banco Sofitasa</option>
              <option value="Banco Venezolano de Credito">Banco Venezolano de Crédito</option>
              <option value="Bancoro">Bancoro</option>
              <option value="Banesco">Banesco</option>
              <option value="Banfoandes">Banfoandes</option>
              <option value="Banorte">Banorte</option>
              <option value="Banplus">Banplus</option>
              <option value="Casa Propia">Casa Propia</option>
              <option value="Corp Banca">Corp Banca</option>
              <option value="Del Sur">Del Sur</option>
              <option value="Fondo Comun">Fondo Común</option>
            </select><span class="required">*</span><img class="chequea" id="checkmibanco" src="image/check.png">
            <?php if (!empty($error_mi_banco)) { ?>
                <script>
				  $("#mi_banco").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checkmibanco").attr("src","image/unchecked.png");
                </script>
             <?php } ?>
            </td>
          </tr>
          <tr id="tipo_deposito_tabla">
            <td><?php echo $transferencia_tipo_deposito; ?></td>
            <td><select name="tipo_deposito" id="tipo_deposito" title="Seleccione la forma en que realiz&oacute; el dep&oacute;sito bancario" style="width:130px">
              <option value="">Seleccione</option>
              <option value="Efectivo">Efectivo</option>
              <option value="Cheque">Cheque</option>
            </select><span class="required">*</span><img class="chequea" id="checktipodeposito" src="image/check.png">
            <?php if (!empty($error_tipo_deposito)) { ?>
                <script>
				  $("#tipo_deposito").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checkmibanco").attr("src","image/unchecked.png");
                </script>
             <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $transferencia_fecha; ?></td>
            <td><input type="text" name="fecha_pago" id="fecha_pago" value="<?php echo $fecha_pago; ?>" title="Ingrese la fecha cuando realiz&oacute; el pago" onblur="$('#fecha_pago').trigger('keyup');"><span class="required">*</span><img class="chequea" id="checkfecha" src="image/check.png">
            <?php if (!empty($error_fecha_pago)) { ?>
                <script>
				  $("#fecha_pago").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checkfecha").attr("src","image/unchecked.png");
                </script>
             <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $transferencia_monto; ?></td>
            <td><input type="text" name="monto_cancelado" id="monto" value="<?php echo $monto_cancelado; ?>" style="text-align:right" title="Ingrese el monto cancelado. Si realiz&oacute; m&aacute;s de un pago para la misma orden, haga un reporte para cada uno"><span class="required">*</span><img class="chequea" id="checkmonto" src="image/check.png">
            <?php if (!empty($error_monto)) { ?>
                <script>
				  $("#monto").css({"border": "2px inset #F00","background":"#F66"});
				  $("#checkmonto").attr("src","image/unchecked.png");
                </script>
             <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $transferecnia_observacion; ?></td>
            <td>
            <textarea name="observacion" id="observacion" value="<?php echo $observacion; ?>" title="Ingrese cualquier observaci&oacute;n sobre el pago o el pedido" rows="6" cols="50"></textarea>
            </td>
          </tr>
          <tr>
          <td><?php echo $entry_captcha; ?></td>
           <td>
              <input type="text" name="captcha" id="captcha" autocomplete="off" title="Ingrese el resultado de la ecuaci&oacute;n">
              <br>
              <img src="http://www.necotienda.com/index.php?route=common/captcha" />
              <?php if (!empty($error_captcha)) { ?>
                <script>
				  $("#captcha").css({"border": "2px inset #F00","background":"#F66"});
                </script>
             <?php } ?>
              </td>
          </tr>
        </table>
      </div>
      <div class="buttons">
        <table>
          <tr>
            <td class="tdTopRight"><a title="<?php echo $button_continue; ?>" onclick="$('#transferencia').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
     </form>
     </div>
      <script> 
        $(function() {
        $("#transferencia :input").tooltip({
        	position: "center right",
        	offset: [-2, 10],
        	effect: "fade",
        	opacity: 0.9});
        });
      </script> 
  </div>
</div>
</div>
<script>
$(function() { 
	$("#captcha").focus(function(){
		$("#captcha").css({"border": "2px inset #AAA","background":"#FFF"});
	});
	$("#transferencia input").css({"width": "200px"});
	$("#forma_pago").change(function(){		
		var string = $("#forma_pago").val();		
		if(string == 0)	{
				$("#forma_pago").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checkformapago").attr("src","image/unchecked.png");
		} else {
				$("#forma_pago").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checkformapago").attr("src","image/checked.png");			
		}
	});	
	$("#order_id").keyup(function(){		
		var string = $("#order_id").val();		
		if(string != 0)	{
			if(isInteger(string))	{
				$("#order_id").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checkorder").attr("src","image/checked.png");
			} else {
				$("#order_id").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checkorder").attr("src","image/unchecked.png");
			}
		}
	});	
	$("#nombre").keyup(function(){		
		var string = $("#nombre").val();		
		if(string != 0)	{
			if(noCharsEspeciales(string))	{
				$("#nombre").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checknombre").attr("src","image/unchecked.png");
			} else {
				$("#nombre").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checknombre").attr("src","image/checked.png");
			}	
			if(isOnlyChar(string))	{
				$("#nombre").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checknombre").attr("src","image/checked.png");
			} else {
				$("#nombre").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checknombre").attr("src","image/unchecked.png");
			}				
		}
	});	
	$("#numero_transaccion").keyup(function(){		
		var string = $("#numero_transaccion").val();		
		if(string != 0)	{
			if(isInteger(string))	{
				$("#numero_transaccion").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checknumero").attr("src","image/checked.png");
			} else {
				$("#numero_transaccion").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checknumero").attr("src","image/unchecked.png");				
			}
		}
	});	
	$("#su_banco").change(function(){		
		var string = $("#su_banco").val();		
		if(string == 0)	{
				$("#su_banco").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checksubanco").attr("src","image/unchecked.png");
			} else {
				$("#su_banco").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checksubanco").attr("src","image/checked.png");		
		}
	});	
	$("#mi_banco").change(function(){		
		var string = $("#mi_banco").val();		
		if(string == 0)	{
				$("#mi_banco").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checkmibanco").attr("src","image/unchecked.png");
			} else {
				$("#mi_banco").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checkmibanco").attr("src","image/checked.png");		
		}
	});	
	$("#tipo_deposito").change(function(){		
		var string = $("#tipo_deposito").val();		
		if(string == 0)	{
				$("#tipo_deposito").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checktipodeposito").attr("src","image/unchecked.png");
			} else {
				$("#tipo_deposito").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checktipodeposito").attr("src","image/checked.png");
		}
	});
	$("#fecha_pago").keyup(function(){		
		var string = $("#fecha_pago").val();		
		if(string != 0)	{
			if(esFechaCorta(string))	{				
				$("#fecha_pago").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checkfecha").attr("src","image/checked.png");
			} else {
				$("#fecha_pago").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checkfecha").attr("src","image/unchecked.png");
			}			
		}
	});	
	$("#monto").keyup(function(){		
		var string = $("#monto").val();		
		if(string != 0)	{
			if(isFloat(string))	{
				$("#monto").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
				$("#checkmonto").attr("src","image/checked.png");
			} else {
				$("#monto").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
				$("#checkmonto").attr("src","image/unchecked.png");				
			}			
		}
	});	
	$("#observacion").keyup(function(){		
		var string = $("#observacion").val();		
		if(string != 0)	{
			if(noCharsEspeciales(string))	{
				$("#observacion").css({
					"border": "2px inset #F00",
					"background":"#F66"
				});
			} else {
				$("#observacion").css({
					"border": "2px inset #0C0",
					"background":"#A7EBBE"
				});
			}			
		}
	});	
});	
function isInteger(string) {
 	var pattern = new RegExp(/^\d+$/i);
 	return pattern.test(string);
}
function isFloat(string) {
 	var pattern = new RegExp(/^\d*[0-9],\d{1,2}$/i);
 	return pattern.test(string);
}
function isOnlyChar(string) {
 	var pattern = new RegExp(/^\D+$/i);
 	return pattern.test(string);
}
function noCharsEspeciales(string) {
 	var pattern = new RegExp(/.[!"#\$%&\/\(\)=\?\|°¿\'\*¨´\+\}\{\^`\\\-_]/i);
 	return pattern.test(string);
}
function esFechaCorta(string) {
 	var pattern = new RegExp(/^(0[1-9]|[12][0-9]|3[01])+[\-\/]+(0[1-9]|1[012])+[\-\/]+(19|20)[0-9]{2}/i);
 	return pattern.test(string);
}
$("#fecha_pago").datepicker({dateFormat: 'dd/mm/yy'});
</script>
<script>
$(function($){
   $("#fecha_pago").mask("99/99/9999",{placeholder:" "});
});
$("#observacion").counter({
   type: 'char',
   goal: 140,
   count: 'up'            
});
</script>
<script type="text/javascript">
$('#nombre').capitalize();
$('#su_banco').capitalize();
$('#mi_banco').capitalize();
</script>
<script>
$(document).ready(function(){
	$("#subanco_tabla").hide();
	$("#tipo_deposito_tabla").hide();
	$("#forma_pago").change(function($){
		if ($("#forma_pago").val() == 'Transferencia') {
			$("#subanco_tabla").show();
			$("#tipo_deposito_tabla").hide();
		}
		if ($("#forma_pago").val() == 'Deposito') {
			$("#tipo_deposito_tabla").show();
			$("#subanco_tabla").hide();
		}
	});
	$('#monto').blur(function()	{
		$('#monto').formatCurrency();
		$('#monto').trigger('keyup');
	});
});
</script>
<?php echo $footer; ?> 