<li class="nt-editable box inviteFriendsWidget<?php echo ($settings['class']) ? " ".$settings['class'] : ''; ?>" id="<?php echo $widgetName; ?>">
<?php if ($heading_title) { ?><div class="header" id="<?php echo $widgetName; ?>Header"><hgroup><h1><?php echo $heading_title; ?></h1></hgroup></div><?php } ?>
    <div class="content" id="<?php echo $widgetName; ?>Content">
        <?php if ($google_client_id) { ?><a class="socialSmallButton googleButton" href="<?php echo $Url::createUrl("api/google",array('redirect'=>'invitefriends')); ?>"><?php echo $Language->get('text_google_invite'); ?></a><?php } ?>
        <?php if ($live_client_id) { ?><a class="socialSmallButton liveButton" href="<?php echo $Url::createUrl("api/live",array('redirect'=>'invitefriends')); ?>"><?php echo $Language->get('text_live_invite'); ?></a><?php } ?>                        
        <?php if ($facebook_app_id) { ?><a class="socialSmallButton facebookButton" href="<?php echo $Url::createUrl("api/facebook",array('redirect'=>'invitefriends')); ?>"><?php echo $Language->get('text_facebook_invite'); ?></a><?php } ?>                        
        <?php if ($twitter_oauth_token_secret) { ?><a class="socialSmallButton twitterButton" href="<?php echo $Url::createUrl("api/twitter",array('redirect'=>'invitefriends')); ?>"><?php echo $Language->get('text_twitter_invite'); ?></a><?php } ?>                        
    </div>
    <div class="clear"></div><br />
</li>