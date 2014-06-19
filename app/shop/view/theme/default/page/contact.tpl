<?php echo $header; ?>
<?php echo $navigation; ?>
<section id="maincontent">
    <section id="content">
        <div class="grid_16">
            <ul id="breadcrumbs" class="nt-editable">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a title="<?php echo $breadcrumb['text']; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
            </ul>
        </div>
        
        <div class="clear"></div><br /><br />
        
        <aside id="column_left"><?php echo $column_left; ?></aside>
        
        <div class="grid_13">
            <h1><?php echo $heading_title; ?></h1>
              
            <div class="clear"></div>
            
            <div class="box">
                <div class="content">
                    <hgroup><h1><?php echo $store; ?></h1></hgroup>
                    <div class="clear"></div>
                    <?php if ($contact_page) { echo $contact_page . '<div class="clear"></div>'; } ?>
                    <?php if ($google_maps) { echo $google_maps . '<div class="clear"></div>'; } ?>
                    <p><b><?php echo $Language->get('text_address'); ?></b>&nbsp;<?php echo $address; ?></p>
                    <?php if ($telephone) { ?><p><b><?php echo $Language->get('text_telephone'); ?></b>&nbsp;<?php echo $telephone; ?></p><?php } ?>
                </div>
                    
                <?php if (isset($success)) { ?><div class="message success"><?php echo $success; ?></div><?php } ?>
                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="contact">
                    
                    <div class="row">
                        <label><?php echo $Language->get('entry_name'); ?></label>
                        <input type="text" name="name" id="name" value="<?php echo $name; ?>" required="required" placeholder="Nombre Completo" />
                        <?php if ($error_name) { ?><span class="error" id="error_name"><?php echo $error_name; ?></span><?php } ?>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="row">
                        <label><?php echo $Language->get('entry_email'); ?></label>
                        <input type="email" name="email" id="email" value="<?php echo $email; ?>" required="required" placeholder="Email" />
                        <?php if ($error_email) { ?><span class="error" id="error_email"><?php echo $error_email; ?></span><?php } ?>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="row">
                        <label><?php echo $Language->get('entry_newsletter'); ?></label>
                        <input type="checkbox" name="newsletter" id="newsletter" value="1" checked="checked" showquick="off" />
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="row">
                        <label><?php echo $Language->get('entry_enquiry'); ?></label>
                        <textarea name="enquiry" id="enquiry" required="required" placeholder="Ingresa tus comentarios aqu&iacute;..."><?php echo $enquiry; ?></textarea>
                        <?php if ($error_enquiry) { ?><span class="error" id="error_enquiry"><?php echo $error_enquiry; ?></span><?php } ?>
                    </div>
                    <div class="clear"></div>
                    
                </form>
            </div>

            <div class="clear"></div>
            <?php if($widgets) { ?><ul class="widgets"><?php foreach ($widgets as $widget) { ?>{%<?php echo $widget; ?>%}<?php } ?></ul><?php } ?>
            <div class="clear"></div>
            
        </div>
        
    </section>
    
</section>
<?php echo $footer; ?>