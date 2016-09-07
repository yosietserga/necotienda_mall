<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
    
        <?php if ($success) { ?><div class="message success"><?php echo $success; ?></div><?php } ?>
        <?php if ($error_warning) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>
        
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
            <h1><?php echo $heading_title; ?></h1>
            <?php if ($error_warning) { ?><div class="message warning"><?php echo $error_warning; ?></div><?php } ?>
              
            <div class="clear"></div>
            
            <?php foreach ($addresses as $result) { ?>
            <div class="content">
              <table>
                <tr>
                  <td><?php echo $result['address']; if ($result['default']) { echo " (Predeterminada)"; } ?></td>
                  <td style="text-align: right;width:200px;"><a title="<?php echo $button_edit; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $result['update']); ?>'" class="button"><span><?php echo $button_edit; ?></span></a>&nbsp;<a title="<?php echo $button_delete; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $result['delete']); ?>'" class="button"><span><?php echo $button_delete; ?></span></a></td>
                </tr>
              </table>
            </div>
            <?php } ?>
            <a title="<?php echo $button_new_address; ?>" onclick="location = '<?php echo str_replace('&', '&amp;', $insert); ?>'" class="button"><?php echo $button_new_address; ?></a>
            
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>