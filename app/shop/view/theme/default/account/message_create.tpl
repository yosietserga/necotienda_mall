<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">

    <section id="content">
    
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
            <h1><?php echo $heading_title; ?></h1>
            
            <?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
            <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
              
            <div class="clear"></div><br />
            <form action="<?php echo $action; ?>" method="post" id="messageForm">
    
                <table>
                    <tr>
                        <td><?php echo $entry_to; ?></td>
                        <td>
                            <input type="text" id="addresses" name="addresses" value="<?php echo $to; ?>" required="required" title="Ingresa los nombres de los remitentes" style="width:400px" />
                            <input type="hidden" id="to" name="to" value="<?php echo $addresses; ?>" />
                            <?php if ($error_to) { ?><span class="error" id="error_to"><?php echo $error_to; ?></span><?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_subject; ?></td>
                        <td>
                            <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" required="required" title="Ingresa el asunto del mensaje" style="width:400px" />
                            <?php if ($error_subject) { ?><span class="error" id="error_subject"><?php echo $error_subject; ?></span><?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_message; ?></td>
                        <td>
                            <textarea id="message" name="message" style="width:400px;height:250px;" required="required"><?php echo $message; ?></textarea>
                            <?php if ($error_message) { ?><span class="error" id="error_message"><?php echo $error_message; ?></span><?php } ?>
                        </td>
                    </tr>
                </table>
            
            </form>
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>