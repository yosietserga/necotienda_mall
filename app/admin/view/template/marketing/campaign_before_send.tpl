<?php echo $header; ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
<div class="box">
        <h1>Resumen de la Campa&ntilde;a</h1>
        <div class="buttons">
            <a onclick="location = '<?php echo $send; ?>';" class="button">Enviar</a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button">Cancelar</a>
        </div>
        
        <div class="clear"></div>
        <table>
            <tr>
                <td><b>Comienzo de la Campa&ntilde;a</b></td>
                <td><?php echo date('d-m-Y h:i A',strtotime($date_start_exec)); ?></td>
            </tr>
            <tr>
                <td>Total Contactos Destinos</td>
                <td><?php echo $contacts; ?></td>
            </tr>
            <tr>
                <td>Total im&aacute;genes en el contenido</td>
                <td><?php echo $total_images; ?></td>
            </tr>
            <tr>
                <td>Total im&aacute;genes incrustadas</td>
                <td><?php echo $total_embed_images; ?></td>
            </tr>
            <tr>
                <td>Total enlaces en el contenido</td>
                <td><?php echo $total_links; ?></td>
            </tr>
            <tr>
                <td>Total enlaces rastreados</td>
                <td><?php echo $total_trace_links; ?></td>
            </tr>
            <tr>
                <td>Tama&ntilde;o de la Camapa&ntilde;a</td>
                <td><?php echo $size; ?></td>
            </tr>
            <tr>
                <td>Consumo de ancho de banda estimado</td>
                <td>
                
                <?php 
                $email_size = ($contacts * $email_size);
                if ($email_size >= 1000) {
                    $total_size = round(($email_size / 1000),2) . " MB";
                } else {
                    $total_size = round($email_size,2) . " KB";
                }
                echo $total_size; 
                ?>
                </td>
            </tr>
            <tr>
                <td><b>Promedio de Spam</b></td>
                <td><?php echo $spam_score; ?></td>
            </tr>
            <?php foreach ($broken_rules as $rule) { ?>
            <tr>
                <td><?php echo $rule[0]; ?></td>
                <td><?php echo $rule[1]; ?></td>
            </tr>
            <?php } ?>
        </table>
</div>
<?php echo $footer; ?>