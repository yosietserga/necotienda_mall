    <div id="adminTopNav">
            <ul>
                <li class="dd">
                    <span>Crear &darr;</span>
                    <ul class="menu_body">
                        <li><a href="<?php echo $create_product; ?>" title="Crear Producto">Producto</a></li>
                        <li><a href="<?php echo $create_page; ?>" title="Crear Producto">P&aacute;gina</a></li>
                        <li><a href="<?php echo $create_post; ?>" title="Crear Producto">Art&iacute;culo</a></li>
                        <li><a href="<?php echo $create_manufacturer; ?>" title="Crear Producto">Fabricante</a></li>
                        <li><a href="<?php echo $create_product_category; ?>" title="Crear Producto">Categor&iacute;a de Productos</a></li>
                        <li><a href="<?php echo $create_post_category; ?>" title="Crear Producto">Categor&iacute;a de Art&iacute;los</a></li>
                    </ul>
                </li>
                <?php if ($is_admin && $_GET['theme_editor']) { ?>
                <li>
                    <a onclick="slidePanel('style')"><img src="<?php echo HTTP_IMAGE; ?>icons/color/palette.png" /></a>
                </li>
                <?php } ?>
            </ul>
    </div>
    
    <form id="cssDataWrapper"></form>
    
    <?php if ($is_admin && $_GET['theme_editor']) { ?>
    <div class="panel-lateral" id="style">
    
        <form id="formStyle">
            <input type="hidden" id="selector" name="selector" value="" />
            <input type="hidden" id="mainselector" name="mainselector" value="" />

            <a class="style-icons nuevo" href="'<?php echo $new_theme; ?>'"></a>
            <a class="style-icons save" onclick="saveStyle('<?php echo $save_theme; ?>')"></a>
            <a class="style-icons clean" onclick="cleanStyle()"></a>
            <a class="style-icons copy" onclick="copyStyle()"></a>
            <a class="style-icons paste" onclick="pasteStyle()"></a>
            <a class="style-icons print" onclick="printStyle()"></a>
            <a class="style-icons close" onclick="slidePanel('style')">Cerrar</a>
            
            <div class="clear"></div>
            
            <select id="selectors" onchange="setElementToStyle($(this).val())">
                <option value="null">General</option>
                
                <optgroup label="Textos">
                    <option value="h1">T&iacute;tulos &lt;h1&gt;</option>
                    <option value="h1:hover">T&iacute;tulos &lt;h1:hover&gt;</option>
                    <option value="subtitle">Sub-T&iacute;tulos &lt;[h2-h6]&gt;</option>
                    <option value="p">P&aacute;rrafos &lt;p&gt;</option>
                    <option value="b">&Eacute;nfasis &lt;b&gt;</option>
                    <option value="a">Enlaces &lt;a&gt;</option>
                    <option value="a:hover">Enlaces &lt;a:hover&gt;</option>
                </optgroup>
                
                <optgroup label="Formularios">
                    <option value="input">Campos &lt;input&gt;</option>
                    <option value="input:hover">Campos &lt;input:hover&gt;</option>
                    <option value="input:focus">Campos &lt;input:focus&gt;</option>
                    <option value="select">Combos &lt;select&gt;</option>
                    <option value="select:hover">Combos &lt;select:hover&gt;</option>
                    <option value="textarea">&Aacute;reas de Texto &lt;textarea&gt;</option>
                    <option value="textarea:hover">&Aacute;reas de Texto &lt;textarea:hover&gt;</option>
                    <option value="textarea:focus">&Aacute;reas de Texto &lt;textarea:focus&gt;</option>
                    <option value="label">Etiquetas &lt;label&gt;</option>
                </optgroup>
                
                <optgroup label="Tablas">
                    <option value="th">Cabecera &lt;th&gt;</option>
                    <option value="tr">Filas &lt;tr&gt;</option>
                    <option value="td">Columnas &lt;td&gt;</option>
                    <option value="tr:first-child">Primera Fila &lt;tr:first-child&gt;</option>
                    <option value="td:first-child">Primera Columna &lt;td:first-child&gt;</option>
                </optgroup>
                
                <optgroup label="Otros">
                    <option value="li">Listas &lt;li&gt;</option>
                    <option value="li:hover">Listas &lt;li:hover&gt;</option>
                    <option value="span">Extensiones &lt;span&gt;</option>
                    <option value=".header">Cabeceras &lt;.header&gt;</option>
                    <option value=".content">Contenidos &lt;.content&gt;</option>
                </optgroup>
                
            </select>
            
            <div class="clear"></div>
            
            <h3 style="padding:10px;background: #84A150;border: solid 1px ; color:#fff;">Selector: <span id="el" style="font-weight: bold;"></span></h3>
            <div class="clear"></div>
            
            <div class="panelWrapper">
                <h3>Fondos (Background)</h3>
                <div>
                    <table>
                        <tr>
                            <td>Color:<div id="background-colorpicker"></div></td>
                            <td>
                                <input class="style-panel" type="text" id="backgroundColor" name="Style[background-color]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Imagen:</td>
                            <td><input class="style-panel" type="url" id="backgroundImage" name="Style[background-image]" value="" /></td>
                            <td><a class="style-icons help" title="Ingresa la url de la imagen. Por ejemplo, <small>http://www.necotienda.com/logo.png</small>"></a></td>
                        </tr>
                        <tr>
                            <td>Repetir la imagen:</td>
                            <td>
                                <select class="style-panel" id="backgroundRepeat" name="Style[background-repeat]">
                                    <option value="">Deafult</option>
                                    <option value="repeat">Repetir</option>
                                    <option value="repeat-x">De Izquierda a Derecha</option>
                                    <option value="repeat-y">De Arriba a Abajo</option>
                                    <option value="no-repeat">No Repetir</option>
                                </select>
                            </td>
                            <td><a class="style-icons help" title="Selecciona en que manera se va a repetir la imagen en el fondo."></a></td>
                        </tr>
                        <tr>
                            <td>Posici&oacute;n de la imagen (x,y):</td>
                            <td>
                                <input class="style-panel" type="number" id="backgroundPositionX" name="Style[background-position-x]" value="" style="width:40px" />
                                <input class="style-panel" type="number" id="backgroundPositionY" name="Style[background-position-y]" value="" style="width:40px" />
                            </td>
                            <td><a class="style-icons help" title="Ingresa las coordenadas X,Y de la imagen del fondo, la unidad de medida predeterminada es pixel."></a></td>
                        </tr>
                        <tr>
                            <td>Anclar imagen:</td>
                            <td>
                                <input class="style-panel" type="checkbox" id="backgroundAttachment" name="Style[background-attachment]" value="1" />
                            </td>
                            <td><a class="style-icons help" title="Marca si quieres que la imagen del fondo se quede est&aacute;tica o se mueva junto con la barra de desplazamiento (scroll)."></a></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a onclick="$('#bgCss').slideToggle()">Insertar c&oacute;digo CSS</a>
                                <div class="clear"></div>
                                <textarea class="style-panel" name="Style[background-css]" id="backgroundCss" style="display:none" onchange="setStyle()"></textarea>
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td><a class="button" onclick="resetBackground()">Limpiar</a></td>
                            <td><a class="button" onclick="setStyle()">Aplicar</a></td>
                        </tr>
                    </table>
                </div>
                
                <h3>Textos y Fuentes </h3>
                <div>
                    <table>
                        <tr>
                            <td>Color del Texto:<div id="font-colorpicker"></div></td>
                            <td>
                                <input class="style-panel" type="text" id="fontColor" name="Style[color]" value="" />
                                
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Tipo de letra:</td>
                            <td>
                                <select class="style-panel" id="fontFamily" name="Style[font-familiy]">
                                    <option value="">Ninguno</option>
                                    <option value="Verdana, Geneva, sans-serif">Verdana</option>
                                    <option value="Georgia, 'Times New Roman', Times, serif">Georgia</option>
                                    <option value="'Courier New', Courier, monospace">Courier New</option>
                                    <option value="Arial, Helvetica, sans-serif">Arial</option>
                                    <option value="Tahoma, Geneva, sans-serif">Tahoma</option>
                                    <option value="'Trebuchet MS', Arial, Helvetica, sans-serif">Trebuchet MS</option>
                                    <option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif">Palatino Linotype</option>
                                    <option value="'Times New Roman', Times, serif">Times New Roman</option>
                                    <option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif">Lucida Sans Unicode</option>
                                    <option value="'MS Serif', 'New York', serif">MS Serif</option>
                                    <option value="'Lucida Console', Monaco, monospace">Lucida Console</option>
                                    <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
                                </select>
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Tama&ntilde;o del Texto:</td>
                            <td>
                                <select class="style-panel" id="fontSize" name="Style[font-size]">
                                    <option value="">Ninguno</option>
                                    <option value="8px">8</option>
                                    <option value="9px">9</option>
                                    <option value="10px">10</option>
                                    <option value="11px">11</option>
                                    <option value="12px">12</option>
                                    <option value="14px">14</option>
                                    <option value="18px">18</option>
                                    <option value="22px">22</option>
                                    <option value="24px">24</option>
                                    <option value="28px">28</option>
                                    <option value="32px">32</option>
                                    <option value="36px">36</option>
                                    <option value="40px">40</option>
                                    <option value="44px">44</option>
                                    <option value="48px">48</option>
                                    <option value="54px">54</option>
                                    <option value="60px">60</option>
                                    <option value="72px">72</option>
                                </select>
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Espacio de Letras:</td>
                            <td>
                                <div id="letterSpacingSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="letterSpacing" name="Style[letter-spacing]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Espacio de Palabras:</td>
                            <td>
                                <div id="wordSpacingSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="wordSpacing" name="Style[word-spacing]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Espacio de L&iacute;neas:</td>
                            <td>
                                <div id="lineHeightSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="lineHeight" name="Style[line-height]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            
                            <a id="bold" class="bold style-icons" onclick="setWeight(this,'bold','boldOn')"></a>
                            <input class="style-panel" type="hidden" id="fontWeight" name="Style[font-weight]" value="" />
                                
                            <a id="italic" class="italic style-icons" onclick="setItalic(this,'italic','italicOn')"></a>
                            <input class="style-panel" type="hidden" id="fontStyle" name="Style[font-style]" value="" />
                                
                            <a id="underline" class="underline style-icons" onclick="setDecoration(this,'underline','underlineOn')"></a>
                            <a id="lineThrough" class="line-through style-icons" onclick="setDecoration(this,'line-through','line-throughOn')"></a>
                            <input class="style-panel" type="hidden" id="textDecoration" name="Style[text-decoration]" value="" />
                                
                            <a id="upper" class="uppercase style-icons" onclick="setTransform(this,'uppercase','uppercaseOn')"></a>
                            <a id="lower" class="lowercase style-icons" onclick="setTransform(this,'lowercase','lowercaseOn')"></a>
                            <input class="style-panel" type="hidden" id="textTransform" name="Style[text-transform]" value="" />
                            
                            <a id="alignLeft" class="align-left style-icons" onclick="setAlign(this,'left','align-leftOn')"></a>
                            <a id="alignCenter" class="align-center style-icons" onclick="setAlign(this,'center','align-centerOn')"></a>
                            <a id="alignRight" class="align-right style-icons" onclick="setAlign(this,'right','align-rightOn')"></a>
                            <a id="alignJustify" class="align-justify style-icons" onclick="setAlign(this,'justify','align-justifyOn')"></a>
                            <input class="style-panel" type="hidden" id="textAlign" name="Style[text-align]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td><a class="button" onclick="resetFont()">Limpiar</a></td>
                            <td><a class="button" onclick="setStyle()">Aplicar</a></td>
                        </tr>
                    </table>
                </div>
                
                <h3>Bordes</h3>
                <div>
                    <a onclick="showAdvanced(this)">Opciones Avanzadas</a>
                    <input class="style-panel advanced" type="hidden" id="borderAdvanced" value="" />
                    <div>
                        <table>
                            <tr>
                                <td colspan="2"><b>B&aacute;sico</b></td>
                            </tr>
                            <tr>
                                <td>Color:<div id="border-colorpicker"></div></td>
                                <td><input class="style-panel" type="text" id="borderColor" name="Style[border-color]" value="" /></td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>L&iacute;nea:</td>
                                <td>
                                    <select class="style-panel" id="borderStyle" name="Style[border-style]">
                                        <option value="">Ninguno</option>
                                        <option value="solid">Continua</option>
                                        <option value="dashed">Cortada</option>
                                        <option value="dotted">Punteada</option>
                                        <option value="double">Doble</option>
                                        <option value="groove">Ranura</option>
                                        <option value="ridge">Cresta</option>
                                    </select>
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Grosor:</td>
                                <td>
                                    <div id="borderWidthSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderWidth" name="Style[border-width]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>
                    </div>
                    <div style="display: none;">
                        <table>
                            <tr>
                                <td colspan="2"><b>Avanzado</b></td>
                            </tr>
                            <tr>
                                <td>Color Borde Superior:<div id="border_top_colorpicker"></div></td>
                                <td><input class="style-panel" type="text" id="borderTopColor" name="Style[border-top-color]" value="" /></td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>L&iacute;nea Borde Superior:</td>
                                <td>
                                    <select class="style-panel" id="borderTopStyle" name="Style[border-top-style]">
                                        <option value="">Ninguno</option>
                                        <option value="solid">Continua</option>
                                        <option value="dashed">Cortada</option>
                                        <option value="dotted">Punteada</option>
                                        <option value="double">Doble</option>
                                        <option value="groove">Continua</option>
                                        <option value="ridge">Continua</option>
                                    </select>
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Grosor Borde Superior:</td>
                                <td>
                                    <div id="borderTopWidthSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderTopWidth" name="Style[border-top-width]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr><td colspan="2"><hr /></td></tr>
                            <tr>
                                <td>Color Borde Derecho:<div id="border-right-colorpicker"></div></td>
                                <td><input class="style-panel" type="text" id="borderRightColor" name="Style[border-right-color]" value="" /></td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>L&iacute;nea Borde Derecho:</td>
                                <td>
                                    <select class="style-panel" id="borderRightStyle" name="Style[border-right-style]">
                                        <option value="">Ninguno</option>
                                        <option value="solid">Continua</option>
                                        <option value="dashed">Cortada</option>
                                        <option value="dotted">Punteada</option>
                                        <option value="double">Doble</option>
                                        <option value="groove">Continua</option>
                                        <option value="ridge">Continua</option>
                                    </select>
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Grosor Borde Derecho:</td>
                                <td>
                                    <div id="borderRightWidthSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderRightWidth" name="Style[border-right-width]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr><td colspan="2"><hr /></td></tr>
                            <tr>
                                <td>Color Borde Inferior:<div id="border-bottom-colorpicker"></div></td>
                                <td><input class="style-panel" type="text" id="borderBottomColor" name="Style[border-bottom-color]" value="" /></td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>L&iacute;nea Borde Inferior:</td>
                                <td>
                                    <select class="style-panel" id="borderBottomStyle" name="Style[border-bottom-style]">
                                        <option value="">Ninguno</option>
                                        <option value="solid">Continua</option>
                                        <option value="dashed">Cortada</option>
                                        <option value="dotted">Punteada</option>
                                        <option value="double">Doble</option>
                                        <option value="groove">Continua</option>
                                        <option value="ridge">Continua</option>
                                    </select>
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Grosor Borde Inferior:</td>
                                <td>
                                    <div id="borderBottomWidthSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderBottomWidth" name="Style[border-bottom-width]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr><td colspan="2"><hr /></td></tr>
                            <tr>
                                <td>Color Borde Izquierdo:<div id="border-left-colorpicker"></div></td>
                                <td><input class="style-panel" type="text" id="borderLeftColor" name="Style[border-left-color]" value="" /></td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>L&iacute;nea Borde Izquierdo:</td>
                                <td>
                                    <select class="style-panel" id="borderLeftStyle" name="Style[border-left-style]">
                                        <option value="">Ninguno</option>
                                        <option value="solid">Continua</option>
                                        <option value="dashed">Cortada</option>
                                        <option value="dotted">Punteada</option>
                                        <option value="double">Doble</option>
                                        <option value="groove">Continua</option>
                                        <option value="ridge">Continua</option>
                                    </select>
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Grosor Borde Izquierdo:</td>
                                <td>
                                    <div id="borderLeftWidthSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderLeftWidth" name="Style[border-left-width]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>   
                    </div>
                </div>
                
                <h3>Redondeo (border-radius)</h3>
                <div>
                    <a onclick="showAdvanced(this)">Opciones Avanzadas</a>
                    <input class="style-panel advanced" type="hidden" id="borderRadiusAdvanced" value="" />
                    <div>
                        <table>
                            <tr>
                                <td colspan="2"><b>B&aacute;sico</b></td>
                            </tr>
                            <tr>
                                <td>Redondeo:</td>
                                <td>
                                    <div id="borderRadiusSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="borderRadius" name="Style[border-radius]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>
                    </div>
                    <div style="display:none;">
                        <table>
                            <tr>
                                <td colspan="2"><b>Avanzado</b></td>
                            </tr>
                            <tr>
                                <td>Superior Izquierdo:</td>
                                <td>
                                    <div id="borderRadiusTopLeftSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderRadiusTopLeft" name="Style[border-radius-topleft]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Superior Derecho:</td>
                                <td>
                                    <div id="borderRadiusTopRightSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderRadiusTopRight" name="Style[border-radius-topright]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Inferior Derecho:</td>
                                <td>
                                    <div id="borderRadiusBottomRightSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderRadiusBottomRight" name="Style[border-radius-bottomright]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>Inferior Izquierdo:</td>
                                <td>
                                    <div id="borderRadiusBottomLeftSlider" class="slider"></div>
                                    <input class="style-panel" type="number" id="borderRadiusBottomLeft" name="Style[border-radius-bottomleft]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>   
                    </div>
                </div>
                
                <h3>Sombras</h3>
                <div>
                    <table>
                        <tr>
                            <td>Color:<div id="box-colorpicker"></div></td>
                            <td><input class="style-panel" type="text" id="boxColor" name="Style[box-shadow-color]" value="" /></td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Sombra Interna:</td>
                            <td><input class="style-panel" type="checkbox" id="boxShadowInset" name="Style[box-shadow-inset]" value="1" /></td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Distancia Horizontal:</td>
                            <td>
                                <div id="boxShadowXSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="boxShadowX" name="Style[box-shadow-x]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Distancia Vertical:</td>
                            <td>
                                <div id="boxShadowYSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="boxShadowY" name="Style[box-shadow-y]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Desenfoque (blur):</td>
                            <td>
                                <div id="boxShadowBlurSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="boxShadowBlur" name="Style[box-shadow-blur]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Difusi&oacute;n (spread):</td>
                            <td>
                                <div id="boxShadowSpreadSlider" class="slider"></div>
                                <input class="style-panel" type="number" id="boxShadowSpread" name="Style[box-shadow-spread]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                    </table>
                </div>
                
                <h3>M&aacute;rgenes Externos</h3>
                <div>
                    <a onclick="showAdvanced(this)">Opciones Avanzadas</a>
                    <input class="style-panel advanced" type="hidden" id="marginAdvanced" value="" />
                    <div>
                        <table>
                            <tr>
                                <td colspan="2"><b>B&aacute;sico</b></td>
                            </tr>
                            <tr>
                                <td>Todos los m&aacute;rgenes:</td>
                                <td>
                                    <div id="marginSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="margin" name="Style[margin]" value="" />
                                    <td><a class="style-icons help" title=""></a></td>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="display:none;">
                        <table>
                            <tr>
                                <td colspan="2"><b>Avanzado</b></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Superior:</td>
                                <td>
                                    <div id="marginTopSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="marginTop" name="Style[margin-top]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Inferior:</td>
                                <td>
                                    <div id="marginBottomSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="marginBottom" name="Style[margin-bottom]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Derecho:</td>
                                <td>
                                    <div id="marginRightSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="marginRight" name="Style[margin-right]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Izquierdo:</td>
                                <td>
                                    <div id="marginLeftSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="marginLeft" name="Style[margin-left]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h3>M&aacute;rgenes Internos</h3>
                <div>
                    <a onclick="showAdvanced(this)">Opciones Avanzadas</a>
                    <input class="style-panel advanced" type="hidden" id="paddingAdvanced" value="" />
                    <div>
                        <table>
                            <tr>
                                <td colspan="2"><b>B&aacute;sico</b></td>
                            </tr>
                            <tr>
                                <td>Todos los m&aacute;rgenes:</td>
                                <td>
                                    <div id="paddingSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="padding" name="Style[padding]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>
                    </div>
                    <div style="display:none;">
                        <table>
                            <tr>
                                <td colspan="2"><b>Avanzado</b></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Superior:</td>
                                <td>
                                    <div id="paddingTopSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="paddingTop" name="Style[padding-top]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Inferior:</td>
                                <td>
                                    <div id="paddingBottomSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="paddingBottom" name="Style[padding-bottom]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Derecho:</td>
                                <td>
                                    <div id="paddingRightSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="paddingRight" name="Style[padding-right]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                            <tr>
                                <td>M&aacute;rgen Izquierdo:</td>
                                <td>
                                    <div id="paddingLeftSlider" style="width:180px;display:block"></div>
                                    <input class="style-panel" type="number" id="paddingLeft" name="Style[padding-left]" value="" />
                                </td>
                                <td><a class="style-icons help" title=""></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h3>Dimensiones y Posiciones</h3>
                <div>
                    <table>
                        <tr>
                            <td>Ancho:</td>
                            <td>
                                <div id="widthSlider" style="width:180px;display:block"></div>
                                <input class="style-panel" type="number" id="width" name="Style[width]" value="" />
                            </td>
                                <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Alto:</td>
                            <td>
                                <div id="heightSlider" style="width:180px;display:block"></div>
                                <input class="style-panel" type="number" id="height" name="Style[height]" value="" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Float:</td>
                            <td>
                                <select class="style-panel" id="float" name="Style[float]">
                                    <option value="none">Ninguno</option>
                                    <option value="left">Izquierda</option>
                                    <option value="right">Derecha</option>
                                </select>
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Overflow:</td>
                            <td>
                                <select class="style-panel" id="overflow" name="Style[overflow]">
                                    <option value="auto">Auto</option>
                                    <option value="scroll">Scroll</option>
                                    <option value="hidden">Oculto</option>
                                </select>
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Posici&oacute;n:</td>
                            <td>
                                <select class="style-panel" id="position" name="Style[position]">
                                    <option value="static">Est&aacute;tico</option>
                                    <option value="relative">Relativo</option>
                                    <option value="fixed">Fijo</option>
                                    <option value="absolute">Absoluto</option>
                                </select>
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Posici&oacute;n X:</td>
                            <td>
                                <div id="leftSlider" style="width:180px;display:block"></div>
                                <input class="style-panel" type="number" id="left" name="Style[left]" value="auto" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                        <tr>
                            <td>Posici&oacute;n Y:</td>
                            <td>
                                <div id="topSlider" style="width:180px;display:block"></div>
                                <input class="style-panel" type="number" id="top" name="Style[top]" value="auto" />
                            </td>
                            <td><a class="style-icons help" title=""></a></td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </form>
    </div>
    <?php } ?>