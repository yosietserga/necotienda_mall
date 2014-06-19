<?php echo $header; ?>
<div class="grid_24">
    <div class="box">
        <div class="header">
            <h1><?php echo $Language->get('heading_title'); ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $Language->get('button_insert'); ?></a>
                <a onclick="location = '<?php echo $invoice; ?>'" class="button"><?php echo $Language->get('button_invoices'); ?></a>
            </div>
        </div>    
          
        <div class="clear"></div><br />
        
        <h3>Filtros<span id="filters">[ Mostrar ]</span></h3>
        <form action="<?php echo $search; ?>" method="post" enctype="multipart/form-data" id="formFilter">        
            <div class="grid_11">
                <div class="row">       
                    <label>ID del Pedido:</label>
                    <input type="text" name="filter_order_id" value="" />
                </div>
                    
                <div class="row">
                    <label>Nombre del Cliente:</label>
                    <input type="text" name="filter_name" value="" />
                </div>
                    
                <div class="row">
                    <label>Estado del Pedido:</label>
                    <select name="filter_order_status_id">
                        <option value="*"></option>
                        <option value="0"<?php if ($filter_order_status_id == '0') { ?> selected="selected"<?php } ?>><?php echo $Language->get('text_missing_orders'); ?></option>
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"<?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?> selected="selected"<?php } ?>><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                  </select>
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