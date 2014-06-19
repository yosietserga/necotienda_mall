<li class="nt-editable box bestsellerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <hgroup><h1><?php echo $seller_info['company']; ?></h1></hgroup>
        
        <?php
// Get lat and long by address      
        $address = 'Calle Coromoto Calicanto, Maracay, Venezuela'; // Google HQ
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        echo $latitude = $output->results[0]->geometry->location->lat;
        echo $longitude = $output->results[0]->geometry->location->lng;

?>
<!--
<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.ve/?ie=UTF8&amp;ll=<?php echo $latitude; ?>,<?php echo $longitude; ?>&amp;spn=0.195601,0.338173&amp;t=m&amp;z=15&amp;output=embed"></iframe><br /><small><a href="https://maps.google.co.ve/?ie=UTF8&amp;ll=10.267184,-67.60541&amp;spn=0.195601,0.338173&amp;t=m&amp;z=12&amp;source=embed" style="color:#0000FF;text-align:left">Ver mapa más grande</a></small>
-->
        <h3><?php echo $Language->get('text_telephone'); ?></h3>
        <p><?php echo $seller['rif']; ?></p>
        
        <h3><?php echo $Language->get('text_telephone'); ?></h3>
        <p><?php echo $seller['telephone']; ?></p>
        
        <h3><?php echo $Language->get('text_email'); ?></h3>
        <p><?php echo $seller['email']; ?></p>
        
        <h3><?php echo $Language->get('text_address'); ?></h3>
        <p><?php echo $seller['address']['address'] .', '. $seller_info['address']['city'] .', '. $seller_info['address']['zone'] .', '. $seller_info['address']['country']; ?></p>
    </div>
    <div class="clear"></div><br />
</li>