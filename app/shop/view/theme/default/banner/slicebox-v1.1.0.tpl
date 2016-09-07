<li class="nt-editable box bannerWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
<?php if (count($banner['items'])) { ?>
<div class="content" id="<?php echo $widgetName; ?>Content">
    <ul id="<?php echo $widgetName; ?>slicebox" class="sb-slider">
    <?php foreach ($banner['items'] as $item) { ?>
        <?php if (empty($item['image'])) continue; ?>
        <li>
            <?php if (!empty($item['link'])) { ?><a href="<?php echo $item['link']; ?>" title="<?php echo $item['title']; ?>"><?php } ?>
            <img src="<?php echo HTTP_IMAGE . $item['image']; ?>" />
            <?php if (!empty($item['link'])) { ?></a><?php } ?>
            <?php if (!empty($item['title']) && !empty($item['description'])) { ?>
            <div class="sb-description">
                <?php if (!empty($item['title'])) { echo '<h3>'. $item['title'] .'</h3>'; } ?>
                <?php if (!empty($item['description'])) { echo '<em>'. htmlentities($item['description']) .'</em>'; } ?>"
            </div>
            <?php } ?>
        </li>
    <?php } ?>
    </ul>

    <div id="<?php echo $widgetName; ?>nav-arrows" class="nav-arrows">
        <a href="#">Next</a>
		<a href="#">Previous</a>
    </div>

    <div id="<?php echo $widgetName; ?>nav-dots" class="nav-dots">
        <?php foreach ($banner['items'] as $item) { ?>
        <span></span>
        <?php } ?>
    </div>

    <div id="<?php echo $widgetName; ?>nav-options" class="nav-options">
        <span id="<?php echo $widgetName; ?>navPlay">Play</span>
		<span id="<?php echo $widgetName; ?>navPause">Pause</span>
    </div>

</div>
<div class="clear"></div><br />
<script>
if (!$.fx.Slicebox) {
    $(document.createElement('link')).attr({
        'href':'<?php echo HTTP_CSS; ?>sliders/slicebox-v1.1.0/slider.css',
        'rel':'stylesheet',
        'media':'screen'
    }).appendTo('head');
    $(document.createElement('script')).attr({
        'src':'<?php echo HTTP_JS; ?>sliders/slicebox-v1.1.0/slider.js',
        'type':'text/javascript',
    }).appendTo('head');
}
$(function(){
    var Page = (function() {
        var $navArrows = $( '#<?php echo $widgetName; ?>nav-arrows' ).hide(),
        $navDots = $( '#<?php echo $widgetName; ?>nav-dots' ).hide(),
        $navOptions = $( '#<?php echo $widgetName; ?>nav-options' ).hide(),
        $nav = $navDots.children( 'span' ),
    	slicebox = $( '#<?php echo $widgetName; ?>slicebox' ).slicebox({
            onReady : function() {
                $navArrows.show();
				$navDots.show();
				$navOptions.show();
            },
            onBeforeChange : function( pos ) {
                $nav.removeClass( 'nav-dot-current' );
				$nav.eq( pos ).addClass( 'nav-dot-current' );
            },
    		orientation : 'r',
    		cuboidsRandom : true
    	}),
        init = function() {
            initEvents();
    	},
    	initEvents = function() {
            $navArrows.children( ':first' ).on( 'click', function() {
                slicebox.next();
    			return false;
            });
    		$navArrows.children( ':last' ).on( 'click', function() {
                slicebox.previous();
    			return false;
            });
            $nav.each( function( i ) {
                $( this ).on( 'click', function( event ) {
                    var $dot = $( this );
					if( !slicebox.isActive() ) {
                        $nav.removeClass( 'nav-dot-current' );
						$dot.addClass( 'nav-dot-current' );
					}
					slicebox.jump( i + 1 );
					return false;
				});			
            });
            $( '#<?php echo $widgetName; ?>navPlay' ).on( 'click', function() {
                slicebox.play();
				return false;
            });
            $( '#<?php echo $widgetName; ?>navPause' ).on( 'click', function() {
                slicebox.pause();
                return false;
            });
        };
        slicebox.play();
        return { init : init };
    })();
    
    Page.init();
});
</script>
<?php } ?>
</li>