<?php echo $header; ?>
<?php echo $navigation; ?>

<section id="maincontent">

    <section id="content">
    
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
        
            <?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
            <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
              
            <h1><?php echo $heading_title; ?></h1>
            <hr />
            <h2><?php echo $message['subject']; ?></h2>
            
            <div class="clear"></div><br />
            
            <p style="font:normal 10px verdana;">Enviado por: <?php echo $message['company']; ?>&nbsp;|&nbsp;<?php echo $message['created']; ?></p>
            <p style="font: normal 12px arial;"><?php echo $message['message']; ?></p>
            
            <div class="clear"></div><br /><br />
            
            <p>
                <a href="#" class="button" onclick="$('#messageForm').slideToggle();return false;">Responder al mensaje</a>
                <a href="<?php echo $Url::createUrl("account/message",array("message_id"=>$message['message_id'])); ?>" class="button">Volver</a>
            </p>
            
            <div class="clear"></div><br />
            
            <form action="<?php echo $action; ?>" method="post" id="messageForm" style="display:none">
                <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>" />
                <input type="hidden" name="subject" value="RE: <?php echo $message['subject']; ?>" />
                <input type="hidden" name="to" value="<?php echo $message['customer_id']; ?>;" />
                <table>
                    <tr>
                        <td>
                            <textarea id="message" name="message" style="width:800px;height:200px;" showquick="off" required="required"></textarea>
                        </td>
                    </tr>
                </table>
            </form>
            
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>