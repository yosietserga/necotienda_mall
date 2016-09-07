<?php require('profile_top.tpl'); ?>
            <div id="profileSideBar" class="grid_6"></div>
            
            <div id="profileContainer" class="grid_9">
                <?php $this->load->auto('activity'); ?>
                <?php foreach($profile['activities'] as $activity) { ?>
                <div class="box grid_11">
                    <div class="box-header" onmouseover="profilePreview('<?php echo $activity['profile']; ?>')">
                        <div class="box-photo">
                            <a href="<?php echo $Url::createUrl('profile/profile', array('profile_id'=>$activity['profile'])); ?>" title="<?php echo $Language->get('text_visit').' '. $activity['profile']; ?>">
                                <img src="<?php echo $Image::resizeAndSave($activity['photo'], 50, 50); ?>" alt="<?php echo $activity['profile']; ?>" />
                            </a>
                        </div>
                        <div class="box-name">
                            <b><?php echo $activity['firstname'] .' '. $activity['lastname']; ?></b><br />
                            <small class="date"><?php echo date('d-m-Y',strtotime($activity['dateAdded'])); ?></small>
                        </div>
                    </div>
                    
                    <div class="box-content">
                        <div class="box-content-description">
                            <?php echo $activity['description']; ?>
                        </div>
                        <div class="box-content-capture">
                            <a href="<?php echo $Url::createUrl($o['url_route'], $o['url_params']); ?>" title="<?php echo $Language->get('text_visit').' '. $o['name']; ?>">
                                <?php
                                $a = new Activity($this->registry, $activity['customer_activity_id']);
                                $o = $a->get('object');
                                ?>
                                <img src="<?php echo $Image::resizeAndSave($o['image'], 80, 80); ?>" alt="<?php echo $o['name']; ?>" style="float:left; margin:10px;" />
                                
                                <p>
                                    <b style="font-size: 20px"><?php echo $o['name']; ?><br /></b>
                                    <?php if ($o['model']) { echo $o['model'] .'<br />'; } ?>
                                    <?php if ($o['price']) { echo $this->currency->format($o['price']) .'<br />'; } ?>
                                    <?php if ($o['rating']) { echo '<img src="'. HTTP_IMAGE .'stars_'. (int)$o['rating'] .'.png" alt="Puntuacion '. $o['rating'] .'" /><br />'; } ?>
                                </p>
                                
                                <div class="clear"></div>
                                
                                <p><?php echo $o['overview']; ?></p>
                            </a>
                        </div>
                    </div>
                    
                    <?php if ($this->customer->isLogged()) { ?>
                    <div class="box-footer">
                        <div class="box-footer-links">
                            <a onclick="ntLike('<?php echo $activity['customer_activity_id']; ?>', '<?php echo $this->customer->getId(); ?>')"><?php echo $Language->get('text_like'); ?></a>
                            <a onclick="ntDislike('<?php echo $activity['customer_activity_id']; ?>', '<?php echo $this->customer->getId(); ?>')"><?php echo $Language->get('text_dislike'); ?></a>
                            <a onclick="ntShare('<?php echo $activity['customer_activity_id']; ?>', '<?php echo $this->customer->getId(); ?>')"><?php echo $Language->get('text_share'); ?></a>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <div class="box-footer-comments">
                            <div class="box-footer-comments-input">
                                <span><img src="<?php echo $Image::resizeAndSave($profile['image'], 35, 35); ?>" alt="<?php echo $profile['firstname'] .' '.  $profile['lastname']; ?>" /></span>
                                <input type="text" name="<?php echo $activity['']; ?>" value="" placeholder="<?php echo $activity['']; ?>" onkeyup="comment('<?php echo $activity['customer_activity_id']; ?>')" />
                            </div>
                            
                            <?php if ($activity['comments']) { ?>
                            <ul class="">
                                <?php foreach ($activity['comments'] as $comment) { ?>
                                <li>
                                    <a href="<?php echo $comment['']; ?>" title="<?php echo $comment['']; ?>">
                                        <img src="<?php echo $comment['']; ?>" alt="<?php echo $comment['']; ?>Photo" />
                                    </a>
                                    <a href="<?php echo $comment['']; ?>" title="<?php echo $comment['']; ?>">
                                        <h4><?php echo $comment['']; ?></h4>
                                    </a>
                                    <br />
                                    <p><?php echo $comment['']; ?></p>
                                    <br />
                                    <a href="<?php echo $comment['']; ?>">Me Gusta</a>
                                    <a href="<?php echo $comment['']; ?>">No Me Gusta</a>
                                </li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            
<?php require('profile_bottom.tpl'); ?>