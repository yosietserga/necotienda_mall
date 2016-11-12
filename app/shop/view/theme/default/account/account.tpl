<?php echo $header; ?>
<?php echo $navigation; ?>
<div class="container">
    <section id="maincontent">
        <section id="content">
            <div class="grid_12">
                <div id="featuredContent">
                <ul class="widgets"><?php if($featuredWidgets) { foreach ($featuredWidgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } } ?></ul>
                </div>
            </div>

            <div class="clear"></div>

            <?php if ($column_left) { ?><aside id="column_left" class="grid_3"><?php echo $column_left; ?></aside><?php } ?>

            <?php if ($column_left && $column_right) { ?>
            <div class="grid_6">
            <?php } elseif ($column_left || $column_right) { ?>
            <div class="grid_9">
            <?php } else { ?>
            <div class="grid_12">
            <?php } ?>

                <h1><?php echo $heading_title; ?></h1>
                <?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
                <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
                
                <div class="clear"></div>
                
                <div class="grid_4 boxIcon orange">
                    <i class="fa fa-home fa-3x"></i>
                    <b>Bs. 213.000,00</b>
                    <span class="title">Ventas</span>
                </div>
                
                <div class="grid_4 boxIcon orange">
                    <i class="fa fa-home fa-3x"></i>
                    <b>Bs. 213.000,00</b>
                    <span class="title">Ventas</span>
                </div>
                
                <div class="grid_4 boxIcon orange">
                    <i class="fa fa-home fa-3x"></i>
                    <b>Bs. 213.000,00</b>
                    <span class="title">Ventas</span>
                </div>
                    
                <div class="box">
                    <div class="header">
                        <h2>Mensajes</h2>
                    </div>
                    <div class="content"></div>

                </div>
            
                <div class="box">
                    <div class="header">
                        <h2>Actividades Recientes</h2>
                    </div>
                    <div class="content"></div>
                </div>

                <div class="box">
                    <div class="header">
                        <h2>Últimos Pedidos</h2>
                    </div>
                    <div class="content"></div>
                </div>

                <div class="box">
                    <div class="header">
                        <h2>Recomendaciones</h2>
                    </div>
                    <div class="content"></div>
                </div>
            </div>
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>