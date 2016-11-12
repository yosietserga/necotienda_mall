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
            
            <?php if ($column_right) { ?><aside id="column_right" class="grid_3"><?php echo $column_right; ?></aside><?php } ?>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>