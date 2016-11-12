<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
<div class="grid_24"><div id="msg"></div></div>
<div class="clear"></div>
<div class="grid_24">
    <div class="box">
        <h1><?php echo $Language->get('heading_title'); ?></h1>
        
        <div class="clear"></div><br />
        
        <ul id="vtabs" class="vtabs">
            <li><a data-target="#tab_visits" onclick="showTab(this)">Visitas</a></li>
            <li><a data-target="#tab_orders" onclick="showTab(this)">Pedidos</a></li>
            <li><a data-target="#tab_sales" onclick="showTab(this)">Ventas</a></li>
            <li><a data-target="#tab_comments" onclick="showTab(this)">Comentarios</a></li>
        </ul>
            
        <div id="tabs">
        
            <div id="tab_visits" class="vtabs_page">
                <div class="grid_24">
                    <div class="box">
                        <div class="header">
                            <hgroup><h1>Estad&iacute;sticas de Visitas</h1></hgroup>
                        </div>
                        <div class="clear"></div><br />
                        <div id="chartVisits" style="height: 300px; min-width: 500px"></div>
                        <div class="clear"></div><br />
                        <div id="visitsStats">Cargando...</div>
                    </div>
                </div>
            </div>
            
            <div id="tab_orders" class="vtabs_page">
                <div class="grid_24">
                    <div class="box">
                        <div class="header">
                            <hgroup><h1>Estad&iacute;sticas de Visitas</h1></hgroup>
                        </div>
                        <div class="clear"></div><br />
                        <div id="chartOrders"></div>
                        <div class="clear"></div><br />
                        <div id="ordersStats">Cargando...</div>
                    </div>
                </div>
            </div>
            
            <div id="tab_sales" class="vtabs_page">
                <div class="grid_24">
                    <div class="box">
                        <div class="header">
                            <hgroup><h1>Estad&iacute;sticas de Visitas</h1></hgroup>
                        </div>
                        <div class="clear"></div><br />
                        <div id="chartSales" style="height: 300px; min-width: 500px"></div>
                        <div class="clear"></div><br />
                        <div id="salesStats">Cargando...</div>
                    </div>
                </div>
            </div>
            
            <div id="tab_comments" class="vtabs_page">
                <div class="grid_24">
                    <div class="box">
                        <div class="header">
                            <hgroup><h1>Estad&iacute;sticas de Visitas</h1></hgroup>
                        </div>
                        <div class="clear"></div><br />
                        <div id="chartComments" style="height: 300px; min-width: 500px"></div>
                        <div class="clear"></div><br />
                        <div id="commentsStats">Cargando...</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?php echo $footer; ?>