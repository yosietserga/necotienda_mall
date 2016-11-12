<?php echo $header; ?>
<div class="grid_24">
    <div class="box">
        <div class="header">
            <h1><?php echo $Language->get('heading_title'); ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $Language->get('button_insert'); ?></a>
                <a onclick="location = '<?php echo $import; ?>'" class="button"><?php echo $Language->get('button_import'); ?></a>
            </div>
        </div>      
        <div class="clear"></div><br />
        
        <h3>Filtros<span id="filters">[ Mostrar ]</span></h3>
        <form action="<?php echo $search; ?>" method="post" enctype="multipart/form-data" id="formFilter">        
            <div class="grid_11">
                <div class="row">       
                    <label>Nombre del M&oacute;dulo:</label>
                    <input type="text" name="filter_name" value="" />
                </div>
            </div>  
            <div class="clear"></div><br />
        </form>
    </div>
</div>
<div class="clear"></div>
<div class="grid_24">
    <div class="box">
        <div id="gridPreloader"></div>
        <div id="gridWrapper"></div>
    </div>
</div>
<?php echo $footer; ?>