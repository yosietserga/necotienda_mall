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

            <div id="profileHeader">
                <div id="bannerContainer">
                    <span id="bannerShadow"></span>
                    <img src="<?php echo $profile['banner']; ?>" alt="<?php $profile['company']; ?>" id="banner" />
                    <?php if ($profile['show_controls']) { ?>
                    <div id="bannerControls">
                        <div class="controls">
                            <div id="dd" class="wrapper-dropdown" tabindex="1">
                                <i class="fa fa-edit"></i>
                                <ul class="dropdown">
                                    <li><a href="#"><i class="icon-user"></i>Profile</a></li>
                                    <li><a href="#"><i class="icon-cog"></i>Settings</a></li>
                                    <li><a href="#"><i class="icon-remove"></i>Log out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <img src="<?php echo $profile['photo']; ?>" alt="<?php $profile['firstname'] .' '. $profile['lastname']; ?>" id="photo" />
                
                <div class="profileLinks">
                    <ul>
                        <li<?php if (substr_count($this->request->getQuery('r'), 'profile/profile')) { ?> class="active"<?php } ?>>
                            <a href="<?php echo $Url::createUrl('profile/profile',array('profile_id'=>$profile['profile'])); ?>" title=""><?php echo $Language->get('tab_profile'); ?></a>
                        </li>
                        <li<?php if (substr_count($this->request->getQuery('r'), 'profile/information')) { ?> class="active"<?php } ?>>
                            <a href="<?php echo $Url::createUrl('profile/information',array('profile_id'=>$profile['profile'])); ?>" title=""><?php echo $Language->get('tab_information'); ?></a>
                        </li>
                        <li<?php if (substr_count($this->request->getQuery('r'), 'profile/products')) { ?> class="active"<?php } ?>>
                            <a href="<?php echo $Url::createUrl('profile/products',array('profile_id'=>$profile['profile'])); ?>" title=""><?php echo $Language->get('tab_products'); ?></a>
                        </li>
                        <li<?php if (substr_count($this->request->getQuery('r'), 'profile/sales')) { ?> class="active"<?php } ?>>
                            <a href="<?php echo $Url::createUrl('profile/sales',array('profile_id'=>$profile['profile'])); ?>" title=""><?php echo $Language->get('tab_sales'); ?></a>
                        </li>
                        <li<?php if (substr_count($this->request->getQuery('r'), 'profile/buys')) { ?> class="active"<?php } ?>>
                            <a href="<?php echo $Url::createUrl('profile/buys',array('profile_id'=>$profile['profile'])); ?>" title=""><?php echo $Language->get('tab_buys'); ?></a>
                        </li>
                        <li<?php if (substr_count($this->request->getQuery('r'), 'profile/store')) { ?> class="active"<?php } ?>>
                            <a href="<?php echo $Url::createUrl('profile/store',array('profile_id'=>$profile['profile'])); ?>" title=""><?php echo $Language->get('tab_store'); ?></a>
                        </li>
                        <li>
                            <?php echo $Language->get('tab_more'); ?>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="clear"></div>
            