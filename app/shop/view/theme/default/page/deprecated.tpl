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

            <div class="clear"></div><br /><br />

            <div class="grid_12">
                <h1 style="background:#900;font:bold 24px Arial;color:#fff;width:96%;padding: 10px 5px;"><?php echo $heading_title; ?></h1>
            </div>

            <div class="clear"></div>

            <div class="grid_5">
                <img src="assets/images/firefox.png" alt="Mozilla Firefox" />
            </div>

            <div class="grid_6">
                <br /><br />
                <h2 style="font:bold 24px Arial;color:#000;">Te Recomendamos Mozilla Firefox</h2>
                <br /><br />
                <a href="http://www.mozilla.org/es-ES/firefox/new/" class="button" style="font: bold 20px Arial;height:auto;">Descargar Mozilla Firefox</a>
            </div>
            
            <div class="clear"></div><br /><br />

            <div class="grid_3">
                <img src="assets/images/chrome.png" alt="Google Chrome" />
                <a href="https://www.google.com/intl/es/chrome/browser/?hl=es" class="button">Descargar Google Chrome</a>
            </div>

            <div class="grid_3">
                <img src="assets/images/safari.png" alt="Safari" />
                <a href="http://www.apple.com/es/safari/" class="button">Descargar Safari</a>
            </div>

            <div class="grid_3">
                <img src="assets/images/opera.png" alt="Opera" />
                <a href="http://www.opera.com/es-419/" class="button">Descargar Opera</a>
            </div>

            <div class="grid_3">
                <img src="assets/images/ie.png" alt="Internet Explorer" />
                <a href="http://windows.microsoft.com/es-es/windows-8/internet-explorer" class="button">Descargar Internet Explorer</a>
            </div>
            
        </section>
    </section>
</div>
<?php echo $footer; ?>