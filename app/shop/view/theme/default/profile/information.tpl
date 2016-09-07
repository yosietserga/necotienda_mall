<?php require('profile_top.tpl'); ?>
<div id="profileSideBar" class="grid_3">
    <div class="box">
        <ul id="tabsLinks" class="necoTabs">
            <li>
                <i class="fa fa-home"></i>
                Información General
            </li>
            <li>
                <i class="fa fa-briefcase"></i>
                Datos de la Empresa
            </li>
            <!--
            <li>
                <i class="fa fa-group"></i>
                Perfil de Clientes
            </li>
            <li>
                <i class="fa fa-cubes"></i>
                Perfil de Proveedores
            </li>
            <li>
                <i class="fa fa-eye"></i>
                Competencia
            </li>
                        -->
        </ul>
    </div>
</div>
            
<div id="profileContainer" class="grid_9">
    <div id="tabsContent" class="box">
        <div>
            <?php if ($profile['show_controls']) { ?>
            <a href="<?php echo $Url::createUrl('account/profile'); ?>">
                <i class="fa fa-edit"></i>&nbsp;
                Editar Informaci&oacute;n
            </a>
            <div class="clear"></div>
            <?php } ?>
                        
            <h1>Información General</h1>

            <h2><?php echo $customer['firstname'] .' '. $customer['lastname']; ?></h2>

            <div class="clear"></div><br />
            <?php if ($profile['google_map']) { ?>
            <div id="profileGoogleMap">
                <address>
                    <?php if (strpos($profile['google_map'], 'iframe') > 0) { ?>
                        <?php echo html_entity_decode($profile['google_map']); ?>
                    <?php } else { ?>
                        <iframe width='600' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q=<?php echo urlencode($profile['google_map']); ?>&amp;output=embed'></iframe>
                    <?php } ?>
                </address>
                <script>
                    $(function(){
                        $('#profileGoogleMap iframe').width($('#profileContainer').width());
                    });
                </script>
            </div>
            <div class="clear"></div><br />
            <?php } ?>
                        
            <table>
                <tr>
                    <td style="width:40px"><i class="fa fa-phone fa-2x"></i></td>
                    <td>
                        <a onclick="$.get('<?php echo $Url::createUrl("common/redirect/called",array('seller_id'=>$customer['customer_id'])); ?>',function(){window.location.href='tel:<?php echo $customer['telephone']; ?>';});return false;" title="<?php echo $Language->get('text_send_email'); ?>">
                            <?php echo $customer['telephone']; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td><i class="fa fa-envelope-o fa-2x"></i></td>
                    <td>
                        <a onclick="$.get('<?php echo $Url::createUrl("common/redirect/emailsent",array('seller_id'=>$customer['customer_id'])); ?>',function(){window.location.href='mailto:<?php echo $customer['email']; ?>';});return false;" title="<?php echo $Language->get('text_send_email'); ?>">
                            <?php echo $customer['email']; ?>
                        </a>
                    </td>
                </tr>

                <?php if ($customer['website']) { ?>
                <tr>
                    <td><i class="fa fa-globe fa-2x"></i></td>
                    <td>
                        <a href="<?php echo $Url::createUrl("common/redirect",array('seller_id'=>$customer['customer_id'],'redirect'=>$customer['website'])); ?>" title="Visita Mi Sitio Web" target="_blank">Sitio Web</a>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($customer['blog']) { ?>
                <tr>
                    <td><i class="fa fa-wordpress fa-2x"></i></td>
                    <td>
                        <a href="<?php echo $Url::createUrl("common/redirect/blog",array('seller_id'=>$customer['customer_id'],'redirect'=>$customer['blog'])); ?>" title="Visita Mi Blog" target="_blank">Blog</a>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($customer['skype']) { ?>
                <tr>
                    <td><i class="fa fa-skype fa-2x"></i></td>
                    <td>
                        <a onclick="$.get('<?php echo $Url::createUrl("common/redirect/skype",array('seller_id'=>$customer['customer_id'])); ?>',function(){window.location.href='skype:<?php echo $customer['skype']; ?>';});return false;" title="Contactarme por Skype">
                            Skype
                        </a>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($customer['twitter']) { ?>
                <tr>
                    <td><i class="fa fa-twitter fa-2x"></i></td>
                    <td>
                        <a href="<?php echo $Url::createUrl("common/redirect/twitter",array('seller_id'=>$customer['customer_id'],'redirect'=>$customer['twitter'])); ?>" title="Visita Mi Perfil en Twitter" target="_blank">Twitter</a>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($customer['facebook']) { ?>
                <tr>
                    <td><i class="fa fa-facebook-square fa-2x"></i></td>
                    <td>
                        <a href="<?php echo $Url::createUrl("common/redirect/facebook",array('seller_id'=>$customer['customer_id'],'redirect'=>$customer['facebook'])); ?>" title="Visita Mi Perfil en Facebook" target="_blank">Facebook</a>
                    </td>
                </tr>
                <?php } ?>
            </table>   
        </div>
    
        <div>
            <?php if ($company['images']) { ?>
            <div style="width:350px;float:left;margin:10px;">
                <div class="slider-wrapper theme-default">
                    <div id="slider" class="nivoSlider">
                    <?php foreach ($company['images'] as $v) { ?>
                        <?php if (empty($v)) continue; ?>
                        <img src="<?php echo $Image::resizeAndSave($v, 300, 300); ?>" />
                    <?php } ?>
                    </div> 
                </div>
            </div>
            <script>
            $(function(){
                if (!$("link[href='<?php echo HTTP_CSS; ?>sliders/nivo-slider-v3.1/slider.css']").length) {
                    $(document.createElement('link')).attr({
                        'href':'<?php echo HTTP_CSS; ?>sliders/nivo-slider-v3.1/slider.css',
                        'rel':'stylesheet',
                        'media':'screen'
                    }).appendTo('head');
                }
                if (!$.fn.nivoSlider) {
                    $(document.createElement('script')).attr({
                        'src':'<?php echo HTTP_JS; ?>sliders/nivo-slider-v3.1/slider.js',
                        'type':'text/javascript'
                    }).appendTo('head');
                }
                $("#slider").nivoSlider({
                    effect:'random', 
                    slices:12,
                    animSpeed:600,
                    pauseTime:6000,
                    startSlide:0, 
                    directionNav:false, 
                    directionNavHide:true, 
                    controlNav:false, 
                    controlNavThumbs:false, 
                    controlNavThumbsFromRel:false,
                    controlNavThumbsSearch: '.jpg', 
                    controlNavThumbsReplace: '_thumb.jpg',
                    keyboardNav:true, 
                    pauseOnHover:true, 
                    manualAdvance:false, 
                    captionOpacity:0.8, 
                    beforeChange: function(){},
                    afterChange: function(){},
                    slideshowEnd: function(){} 
                });
            });
            </script>
            <?php } ?>
            
            <div>
                <div style="height:360px">
                    <h2><?php echo $company['name']; ?></h2>

                    <table>
                        <tr>
                            <td style="width:170px"><b>RIF:</b></td>
                            <td data-title="RIF:"><?php echo $profile['rif']; ?></td>
                        </tr>
                        <?php if ($company['date_established']) { ?>
                        <tr>
                            <td><b>Establecido el:</b></td>
                            <td data-title="Establecido el:"><?php echo $company['date_established']; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if ($company['experience_years']) { ?>
                        <tr>
                            <td><b>A&ntilde;os de Experiencia:</b></td>
                            <td data-title="A&ntilde;os de Experiencia:"><?php echo $company['experience_years']; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if ($company['enterprise_type']) { ?>
                        <tr>
                            <td><b>Tipo de Empresa:</b></td>
                            <td data-title="Tipo de Empresa:"><?php echo $company['enterprise_type']; ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <div class="clear"></div><br />

                </div>

                <?php if ($company['description']) { ?>
                <p><b>Descripción de la Empresa</b></p>
                <p style="text-align:justify"><?php echo $company['description']; ?></p>
                <?php } ?>

                <?php if ($company['history']) { ?>
                <p><b>Reseña Histórica</b></p>
                <p style="text-align:justify"><?php echo $company['history']; ?></p>
                <?php } ?>

                <?php if ($company['mission']) { ?>
                <p><b>Misión</b></p>
                <p style="text-align:justify"><?php echo $company['mission']; ?></p>
                <?php } ?>

                <?php if ($company['vision']) { ?>
                <p><b>Visión</b></p>
                <p style="text-align:justify"><?php echo $company['vision']; ?></p>
                <?php } ?>

                <?php if ($company['values']) { ?>
                <p><b>Valores</b></p>
                <p style="text-align:justify"><?php echo $company['values']; ?></p>
                <?php } ?>

                <?php if ($company['policies']) { ?>
                <p><b>Políticas</b></p>
                <p style="text-align:justify"><?php echo $company['policies']; ?></p>
                <?php } ?>

                <?php if ($company['google_map']) { ?>
                <p><b>Políticas</b></p>
                <p style="text-align:justify"><?php echo $company['google_map']; ?></p>
                <?php } ?>

                <?php if ($company['client_list']) { ?>
                <p><b>Algunos de Nuestros Clientes</b></p>
                <div class="neco-carousel">
                    <ul id="clientListCarousel">
                    <?php foreach($company['client_list'] as $k=>$v) { ?>
                        <li>
                            <article>
                                <img src="<?php echo $Image::resizeAndSave($v['client_logo'], 190, 190); ?>" alt="<?php echo $v['client_name']; ?>" />
                                <br /><b><?php echo $v['client_name']; ?></b>
                            </article>
                        </li>
                    <?php } ?>
                    </ul>
                    <div class="clear"></div>
                    <a id="clientListCarouselPrev" class="neco-carousel-prev"></a>
                    <a id="clientListCarouselNext" class="neco-carousel-next"></a>
                    <div id="clientListCarouselPager" class="neco-carousel-pager"></div>
                </div>
                <script>
                    $(function(){
                        if (!$.fn.carouFredSel) {
                            $(document.createElement('script')).attr({
                                src:'http://www.echapalante.com.ve/assets/js/necojs/neco.carousel.js',
                                type:'text/javascript'
                            }).appendTo('head');
                        }
                        $('#clientListCarousel').carouFredSel({
                            prev:"#clientListCarouselPrev",
                            next:"#clientListCarouselNext",
                            pagination:"#clientListCarouselPager"
                        });
                    });
                </script>
                <?php } ?>
            </div>
        </div>
        <div>Perfil de Clientes</div>
        <div>Perfil de Proveedores</div>
        <div>Competencia</div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('#tabsContent > div').hide();
    $('#tabsContent > div:first-child').show();
    
    $('#tabsLinks li').on('click', function(e) {
        $('#tabsContent > div').hide();
        $('#tabsContent > div:eq('+ $(this).index() +')').show();
    });
});
</script>
<?php require('profile_bottom.tpl'); ?>