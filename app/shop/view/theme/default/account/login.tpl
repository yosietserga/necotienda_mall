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

            <div class="grid_12">
        
                <h1><?php echo $heading_title; ?></h1>
                <?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
                <?php if ($message) { ?><div class="info"><?php echo $message; ?></div><?php } ?>
                <?php if ($error) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>

                <div class="clear"></div>

                <div id="loginForm" class="grid_5">

                    <?php if ($facebook_app_id) { ?>
                    <a class="socialButton facebookButton" href="<?php echo $Url::createUrl("api/facebook",array('redirect'=>'login')); ?>"><?php echo $Language->get('button_login_with_facebook'); ?></a>
                    <?php } ?>

                    <?php if ($twitter_oauth_token_secret) { ?>
                    <a class="socialButton twitterButton" href="<?php echo $Url::createUrl("api/twitter",array('redirect'=>'login')); ?>"><?php echo $Language->get('button_login_with_twitter'); ?></a>
                    <?php } ?>

                    <?php if ($google_client_id) { ?>
                    <a class="socialButton googleButton" href="<?php echo $Url::createUrl("api/google",array('redirect'=>'login')); ?>"><?php echo $Language->get('button_login_with_google'); ?></a>
                    <?php } ?>

                    <?php if ($live_client_id) { ?>
                    <a class="socialButton liveButton" href="<?php echo $Url::createUrl("api/live",array('redirect'=>'login')); ?>"><?php echo $Language->get('button_login_with_live'); ?></a>
                    <?php } ?>

                    <?php if ($Config->get('social_meli_app_id')) { ?>
                    <a class="socialButton meliButton" href="<?php echo $Url::createUrl("api/meli",array('redirect'=>'login')); ?>"><?php echo $Language->get('Login with <b>MercadoLibre</b>'); ?></a>
                    <?php } ?>

                    <div class="clear"></div>

                    <h2><?php echo $Language->get('text_returning_customer'); ?></h2>

                    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="login">            
                        <?php echo isset($fkey)? $fkey : ''; ?>

                        <div class="property">
                            <div class="field"><input type="text" name="email" id="email" placeholder="Email" /></div>
                        </div>

                        <div class="property">
                            <div class="field"><input type="password" name="password" id="password" autocomplete="off" placeholder="Contrase&ntilde;a" /></div>
                        </div>

                        <?php if (isset($_GET['ri'])) { ?>
                        <div class="property">
                            <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6Le5f8cSAAAAANKTNJfbv88ufw7p06EJn32gzm8I"></script>
                            <div class="field">
                                <noscript>
                                    <iframe src="http://www.google.com/recaptcha/api/noscript?k=6Le5f8cSAAAAANKTNJfbv88ufw7p06EJn32gzm8I" height="300" width="500" frameborder="0"></iframe><br />
                                    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                                    <input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
                                </noscript>
                                <div id="ntCaptcha"></div>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="property">
                            <div class="field">
                                <a title="<?php echo $Language->get('text_forgotten_password'); ?>" href="<?php echo str_replace('&', '&amp;', $forgotten); ?>"><?php echo $Language->get('text_forgotten_password'); ?></a>
                            </div>
                        </div>

                        <div class="clear"></div><br />

                        <div class="property">
                            <div class="field">
                                <a title="<?php echo $Language->get('button_login'); ?>" onclick="$('#login').submit();" class="button"><?php echo $Language->get('button_login'); ?></a>
                            </div>
                        </div>

                        <?php if ($redirect) { ?>
                        <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $redirect); ?>" />
                        <?php } ?>
                    </form>
                    
                    <div class="clear"></div><br /><br />
                    
                    <div style="background: #f2f2f2; border: solid 1px #ccc; padding-bottom: 20px;">
                        <h2><?php echo $Language->get('text_i_am_new_customer'); ?></h2>
        
                        <div class="clear"></div><br />
                        
                        <div style="text-align:center">
                            <a href="<?php echo $register; ?>" class="button"><?php echo $Language->get('text_i_am_new_customer'); ?></a>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </section>
    </section>
</div>
<script type="text/javascript">
$(function(){
    $('#login input').keydown(function(e) {
    	if (e.keyCode == 13) {
            if ($('#email').val().length > 0 && $('#password').val().length > 0) {
                $('#login').submit();
            }
    	}
    });
});
</script>
<?php echo $footer; ?>