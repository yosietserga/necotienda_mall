<?php echo $header; ?>
<div class="grid_24">
    <div class="box">
        <div class="header">
            <h1><?php echo $Language->get('heading_title'); ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $insert; ?>'" class="button">Agregar Nuevo</a>
            </div>
        </div>    
          
        <div class="clear"></div><br />
        
        <h3>Filtros<span id="filters">[ Mostrar ]</span></h3>
        <form action="<?php echo $search; ?>" method="post" enctype="multipart/form-data" id="formFilter">        
            <div class="grid_11">
                <div class="row">       
                    <label>Nombre del Autor:</label>
                    <input type="text" name="filter_author" value="" />
                </div>
                    
                <div class="row">
                    <label>Asociado Al Producto:</label>
                    <input type="text" name="filter_product" value="" />
                </div>
                
                <div class="row">
                    <label>Ordernar Por:</label>
                    <input type="text" name="order" value="" />
                </div>
            </div>
            
            <div class="grid_11">
                <div class="row">
                    <label>Fecha Inicial:</label>
                    <input type="date" name="filter_date_start" value="" />
                </div>
                <div class="row">
                    <label>Fecha Final:</label>
                    <input type="date" name="filter_date_end" value="" />
                </div>
                <div class="row">
                    <label>Mostrar:</label>
                    <input type="number" name="limit" value="" />
                </div>
            </div>
                        
            <div class="clear"></div><br />
        </form>
    </div>
</div>
<div class="clear"></div>
<?php if ($error_warning) { ?><div class="grid_24"><div class="message warning"><?php echo $error_warning; ?></div></div><?php } ?>
<?php if ($success) { ?><div class="grid_24"><div class="message success"><?php echo $success; ?></div></div><?php } ?>
<div class="grid_24" id="msg"></div>
<div class="clear"></div>
<div class="grid_24">
    <div class="box">
        <div id="gridPreloader"></div>
        <div id="gridWrapper"></div>
    </div>
</div>
<?php echo $footer; ?>