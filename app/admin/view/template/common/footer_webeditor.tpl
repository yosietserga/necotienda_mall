</div>
</div>
<div id="toolWrapper">
    <div class="container_16">
        <div class="grid_4">
            <?php if ($_GET['step'] == 1 || !isset($_GET['step'])) { ?>
                <?php $_GET['step'] = $_SESSION['step'] = $_POST['step'] = $_GET['step'] +1; ?>
                <h1>Vistas</h1>
                <select id="r">
                    <option value="common/home">Home</option>
                    <option value="store/category&amp;path=1">Categor&iacute;as Superiores</option>
                    <option value="store/category&amp;path=3">Sub-Categor&iacute;as</option>
                    <option value="store/product&amp;product_id=43">Productos</option>
                    <option value="">B&uacute;squedas</option>
                    <option value="">Marcas y Fabricantes</option>
                </select>
                <h1>Seleccione</h1>
                <ul>
                    <li id="common/home"><a>Layout</a></li>
                    <li id="store/category&amp;path=1"><a>Fondo</a></li>
                    <li id="store/category&amp;path=3"><a>Fuentes</a></li>
                    <li id="store/product&amp;product_id=23"><a>Bordes y Sombras</a></li>
                    <li><a>Sombras</a></li>
                    <li><a>Marcas y Fabricantes</a></li>
                </ul>
                
                <script>
                $(function(){
                    $("#htmlWrapper").load('<?php echo HTTP_HOME; ?>wizard/index.php?r=layout/twocolscenterfeatured');
                    $("#r").change(function(){
                       $("#htmlWrapper").load('<?php echo HTTP_HOME; ?>index.php?r=' + this.value); 
                    });
    
                });
                </script>
            <?php } ?>
        </div>
        <div class="grid_8 layouts">
            <h1>Layout</h1>
            <p class="desc">Modifique el Layout que desea para su tienda en cada una de las vistas</p>
            <ul>
                <li id="fullContent" class="fullContent"></li>
                <li id="oneColLeft" class="oneColLeft"></li>
                <li id="oneColRight" class="oneColRight"></li>
                <li id="twoColsCenter" class="twoColsCenter"></li>
                <li id="twoColsLeft" class="twoColsLeft"></li>
                <li id="twoColsRight" class="twoColsRight"></li>
                <li id="fullContentFeatured" class="fullContentFeatured"></li>
                <li id="oneColLeftFeatured" class="oneColLeftFeatured"></li>
                <li id="oneColRightFeatured" class="oneColRightFeatured"></li>
                <li id="twoColsCenterFeatured" class="twoColsCenterFeatured"></li>
                <li id="twoColsLeftFeatured" class="twoColsLeftFeatured"></li>
                <li id="twoColsRightFeatured" class="twoColsRightFeatured"></li>
            </ul>
                <script>
                $(function(){
                    $("#layouts li").click(function(){
                       $("#layouts li").each(function(){
                            $(this).removeClass('clicked');
                       });
                       $(this).addClass('clicked');
                       if (this.id == 'fullContent') {
                            $("#column_left, #column_right").hide();
                            $("#content").addClass('fullContent');
                       }
                       if (this.id == 'oneColLeft') {
                            $("#content").removeClass('fullContent');
                            $("#column_right").hide();
                            $("#column_left").show();
                            $("#content").addClass('contentOneColLeft');
                            $("#column_left").addClass('colOneColLeft');
                       }
                       if (this.id == 'twoColsCenter') {
                            $("#content").removeClass('fullContent');
                            $("#content").removeClass('contentOneColLeft');
                            $("#column_left").removeClass('colOneColLeft');
                            $("#column_left, #column_right").show();
                       }
                    });
                });
                </script>
        </div>
    </div>
    <div id="toolToggle"><img src="<?php echo HTTP_IMAGE; ?>down.png" /></div>
 </div>
<div id="widgetsWrapper">
    <div id="widgetsToggle"><img src="<?php echo HTTP_IMAGE; ?>down.png" /></div>
</div>
 <script>
 $(function(){
    
    $("#toolWrapper").addClass('down').animate({'height':'0px'});
    $("#toolToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>up.png');
    $("#widgetsWrapper").addClass('down').animate({'width':'0px'});
    $("#widgetsToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>up.png');
    $("#widgetsToggle").css({'marginLeft':'0px'});
    
    setInterval(function(){
        $("a").each(function(){
            if ($(this).attr('href')) $(this).removeAttr('href');
        });
    }, 5000);
    
    $("#toolToggle").click(function(){
        if ($("#toolWrapper").hasClass("down")) {
            $("#toolWrapper").removeClass('down').addClass('up').animate({'height':'180px'});
            $("#toolToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>down.png');
        } else {
            $("#toolWrapper").removeClass('up').addClass('down').animate({'height':'0px'});
            $("#toolToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>up.png');
        }
    });
    
    $("#widgetsToggle").click(function(){
        if ($("#widgetsWrapper").hasClass("down")) {
            $("#widgetsWrapper").removeClass('down').addClass('up').animate({'width':'200px'});
            $("#widgetsToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>down.png');
            $("#widgetsToggle").css({'marginLeft':'200px'});
        } else {
            $("#widgetsWrapper").removeClass('up').addClass('down').animate({'width':'0px'});
            $("#widgetsToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>up.png');
            $("#widgetsToggle").css({'marginLeft':'0px'});
        }
    });
    
    $("#widgetsWrapper").mouseenter(function(){
        clearTimeout($(this).data('timeoutId'));
    }).mouseleave(function(){
        var e = this;
        var timeoutId = setTimeout(function(){
            if ($(e).hasClass("up")) {
                $("#widgetsWrapper").removeClass('up').addClass('down').animate({'width':'0px'});
                $("#widgetsToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>up.png');
                $("#widgetsToggle").css({'marginLeft':'0px'});
            }
        }, 900);
        $(this).data('timeoutId', timeoutId); 
    });
    
    $("#toolWrapper").mouseenter(function(){
        clearTimeout($(this).data('timeoutId'));
    }).mouseleave(function(){
        var e = this;
        var timeoutId = setTimeout(function(){
            if ($(e).hasClass("up")) {
                $("#toolWrapper").removeClass('up').addClass('down').animate({'height':'0px'});
                $("#toolToggle").find('img').attr('src','<?php echo HTTP_IMAGE; ?>up.png');
            }
        }, 900);
        $(this).data('timeoutId', timeoutId); 
    });
 });
 </script>
</body></html>