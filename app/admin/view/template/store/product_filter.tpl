<?php echo $header; ?>
<div class="grid_24">
    <div class="box">
        <div class="header">
            <h1><?php echo $Language->get('heading_title'); ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $Language->get('button_insert'); ?></a>
                <a onclick="location = '<?php echo $import; ?>'" class="button"><?php echo $Language->get('button_import'); ?></a>
                <a onclick="location = '<?php echo $export; ?>'" class="button"><?php echo $Language->get('button_export'); ?></a>
            </div>
        </div>      
        <div class="clear"></div><br />
        
        <h3>Filtros<span id="filters">[ Mostrar ]</span></h3>
        <form action="<?php echo $search; ?>" method="post" enctype="multipart/form-data" id="formFilter">        
            <div class="grid_11">
                
                <div class="row">
                    <label>Fecha Inicial:</label>
                    <input type="date" name="filter_date_start" value="" />
                </div>
            </div>
            
            <div class="grid_11">
                <div class="row">
                    <label>Fecha Final:</label>
                    <input type="date" name="filter_date_end" value="" />
                </div>
            </div>
                        
            <div class="clear"></div><br />
        </form>
    </div>
</div>
<div class="clear"></div>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
<div class="grid_24"><div id="msg"></div></div>
<div class="clear"></div>
<div class="grid_24">
    <div class="box">
        <ul id="vtabs" class="vtabs">
            <li><a data-target="#tab_visits" onclick="showTab(this)">Visitas</a></li>
            <li><a data-target="#tab_orders" onclick="showTab(this)">Pedidos</a></li>
            <li><a data-target="#tab_sales" onclick="showTab(this)">Ventas</a></li>
            <li><a data-target="#tab_comments" onclick="showTab(this)">Comentarios</a></li>
        </ul>
            
        <div id="tabs">
            <div id="tab_visits" class="vtabs_page">
            
                <div class="grid_24">
                <div id="container" style="height: 500px; min-width: 500px"></div>
                    <div class="box">
                        <div class="header">
                            <hgroup><h1>Gr&aacute;fico Visitas por Producto</h1></hgroup>
                        </div>
                            
                        <div class="clear"></div><br />
                        <div id="chartVisits"></div>
        
                        <table class="highchart" data-graph-height="300" data-graph-container-before="1" data-graph-type="pie" data-graph-datalabels-enabled="0">
                            <thead>
                                <tr>                                  
                                    <th>Producto</th>
                                    <th>Visitas</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($topVisits as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $value['name']; ?></td>
                                    <td data-graph-name="<?php echo $value['name']; ?>"><?php echo $value['viewed']; ?></td>
                                </tr>
                                <?php if ($key >= 9) { break; } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>