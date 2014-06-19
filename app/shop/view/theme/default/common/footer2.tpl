<div class="container_16">
    <footer id="footer">
        <div class="grid_16"><p><?php echo $text_powered_by; ?></p></div>
    </footer>
</div>
<?php if (count($javascripts) > 0) foreach ($javascripts as $js) { if (empty($js)) continue; ?><script type="text/javascript" src="<?php echo $js; ?>"></script><?php } ?>
<?php if ($scripts) echo $scripts; ?>
<div id="fb-root"></div>
<script type="text/javascript">
    <?php if ($google_analytics_code) { ?>
    var _gaq=[['_setAccount','<?php echo $google_analytics_code; ?>'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
    <?php } ?>
</script>
</body></html>