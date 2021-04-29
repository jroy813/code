<?php 
$gallery_visibility = get_field('gallery_visibility') ? get_field('gallery_visibility') : 'Show';
if( $gallery_visibility !== 'Hide' ) :
    
    $gallery_small_title = get_field('gallery_small_title', 'option') ? get_field('gallery_small_title', 'option') : '';
    $gallery_title = get_field('gallery_title', 'option') ? get_field('gallery_title', 'option') : '';
    $gallery_repeater = get_field('gallery_repeater', 'option');
    $page_service_type = get_field('service_type') ? get_field('service_type') : 'general';

    function gallery_image_html($image, $classes = '', $gallery_type = '', $service_type = false, $hidden = false) {
        $offer_link = get_field('offer_link', $image['ID']) ? get_field('offer_link', $image['ID']) : '';
        $service_type = $service_type ? $service_type : $gallery_type;
        if( $hidden ) {
            $display = 'display: none;';
        }else {
            $display = '';
        }
        $output = '
            <a class="single-img-box custom-lightbox ' . $classes . '" href="' . $image['url'] . '" data-fancybox="gallery-' . $gallery_type . '" data-service="' . $service_type . '" data-offer-id="' . $offer_link . '" style="' . $display . '">
                <div class="image-bg lazyload" data-bg="' . $image['sizes']['gallery_large'] . '"></div>
                <div class="overlay">
                    <i class="fa fa-search"></i>
                </div>
            </a>';
        return $output;
    }

    if( $gallery_repeater ) :
        $counter = 0;
        $found_match = false;
        ?>
        
        <div class="section-gallery <?php if( !is_front_page() && $page_service_type == 'general'  ) { echo 'smaller-padding'; } ?>">
            <div class="custom-gallery-container">
                <div class="tab-container">
                    <?php if( is_front_page() ) : ?>
                        <div class="svg-contain">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1941" height="100vw" viewBox="0 0 1941 1920" xml:space="preserve" class="gallery-top-home">
                                <defs>
                                    <mask id="gallery_mask">
                                        <linearGradient id="gallerygradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:rgb(255,255,255);stop-opacity:0"></stop>
                                            <stop offset="5%" style="stop-color:rgb(255,255,255);stop-opacity:1"></stop>
                                        </linearGradient>
                                        <rect x="0" y="0" width="100%" height="100%" fill="url(#gallerygradient)"></rect>
                                    </mask>
                                </defs>
                                <g style="width: 100%; height: 100%;" mask="url(#gallery_mask)">
                                    <rect class="funstuff" x="0" y="0" width="100%" height="100%" fill="#fefbea"></rect>
                                    <image y="0" x="0" width="1941" height="1920" xlink:href="<?php echo get_template_directory_uri(); ?>/images/pattern-yellow.svg" style="opacity: 1;"></image>
                                </g>
                            </svg>
                        </div>
                    <?php else: ?>
                        <div class="gallery-top-internal lazyload" data-bg="<?php echo get_template_directory_uri(); ?>/images/gallery-top-internal.png"></div>
                    <?php endif; ?>
                    <div class="container-fluid">
                        <div class="row m-0">
                            <div class="col-md-10 offset-md-1 p-0">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="h1">Inspiration Gallery</div>
                                    </div>
                                    <div class="col-lg-7">
                                        <ul class="nav <?php if( !is_front_page() && $page_service_type !== 'general'  ) { echo 'hide'; } ?>" role="tablist">
                                            
                                            <li>
                                                <a  id="gal-tab-0" data-toggle="tab" href="#gal-content-0" role="tab" aria-controls="gal-content-0" aria-selected="true">View All</a>
                                            </li>
                                            
                                            <?php 
                                            $counter = 1;
                                            $view_all_gallery = array();
                                            foreach( $gallery_repeater as $gallery ) :
                                                $image_count = 0;
                                                foreach( $gallery['gallery_images'] as $gallery_image ) {
                                                    $gallery['gallery_images'][$image_count]['service_type'] = $gallery['gallery_category']['service_type'];
                                                    $image_count++;
                                                }
                                                $gallery_tab = $gallery['gallery_name'];
                                                $view_all_gallery = array_merge($view_all_gallery, $gallery['gallery_images']);
                                                if( $gallery['gallery_category']['service_type'] == $page_service_type && !$found_match ) {
                                                    $found_match = true;
                                                }
                                                ?>
                                                
                                                <li>
                                                    <a class="<?php if( $gallery['gallery_category']['service_type'] == $page_service_type ){ echo 'active'; } ?>" id="gal-tab-<?php echo $counter; ?>" data-toggle="tab" href="#gal-content-<?php echo $counter; ?>" role="tab" aria-controls="gal-content-<?php echo $counter; ?>" aria-selected="true"><?php echo $gallery_tab; ?></a>
                                                </li>
                                                
                                                <?php $counter++; ?>
                                                
                                            <?php endforeach; ?>
                                        
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-content">
                    
                <?php shuffle($view_all_gallery); ?>
                    
                    <!-- View All Pane -->
                    <div class="tab-pane fade" id="gal-content-0" role="tabpanel" aria-labelledby="gal-tab-0">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-12 col-lg-5 left-col">
                                    <div class="row">
                                        <div class="col-6 col-lg-7">
                                            <?php echo gallery_image_html($view_all_gallery[1], 'large', 'general', $view_all_gallery[1]['service_type'], false); ?>
                                        </div>
                                        <div class="col-3 col-lg-5">
                                            <?php echo gallery_image_html($view_all_gallery[2], '', 'general', $view_all_gallery[2]['service_type'], false); ?>
                                            <?php echo gallery_image_html($view_all_gallery[3], '', 'general', $view_all_gallery[3]['service_type'], false); ?>
                                        </div>
                                        <div class="col-3 col-lg-12">
                                            <div class="row">
                                                <div class="col-12 col-lg-5">
                                                    <?php echo gallery_image_html($view_all_gallery[4], '', 'general', $view_all_gallery[4]['service_type'], false); ?>
                                                </div>
                                                <div class="col-12 col-lg-7">
                                                    <?php echo gallery_image_html($view_all_gallery[5], '', 'general', $view_all_gallery[5]['service_type'], false); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7 right-col">
                                    <div class="row">
                                        <div class="col-6">
                                            <?php echo gallery_image_html($view_all_gallery[6], '', 'general', $view_all_gallery[6]['service_type'], false); ?>
                                            <?php echo gallery_image_html($view_all_gallery[7], 'large', 'general', $view_all_gallery[7]['service_type'], false); ?>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col">
                                                    <?php echo gallery_image_html($view_all_gallery[8], 'large', 'general', $view_all_gallery[8]['service_type'], false); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <?php echo gallery_image_html($view_all_gallery[9], '', 'general', $view_all_gallery[9]['service_type'], false); ?>
                                                </div>
                                                <div class="col-6">
                                                    <?php echo gallery_image_html($view_all_gallery[10], '', 'general', $view_all_gallery[10]['service_type'], false); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                        $hidden_array = array_slice($view_all_gallery, 11);
                        foreach( $hidden_array as $item ) {
                            echo gallery_image_html($item, '', 'general', $item['service_type'], true);
                        }
                        ?>
                    </div>
                    
                    <?php 
                    $counter = 1;
                    foreach( $gallery_repeater as $gallery ) : 
                        $gallery_images = $gallery['gallery_images'];
                        if( $gallery_images ) : 
                            // Placeholder make numbering easier, starts with 1
                            array_unshift($gallery_images, 'placeholder');
                            ?>
                        
                            <div class="tab-pane fade <?php if( $gallery['gallery_category']['service_type'] == $page_service_type ){ echo 'show active'; } ?>" id="gal-content-<?php echo $counter; ?>" role="tabpanel" aria-labelledby="gal-tab-<?php echo $counter; ?>">
                                <div class="container-fluid p-0">
                                    <div class="row">
                                        <div class="col-12 col-lg-5 left-col">
                                            <div class="row">
                                                <div class="col-6 col-lg-7">
                                                    <?php echo gallery_image_html($gallery_images[1], 'large', $gallery['gallery_category']['service_type'], false); ?>
                                                </div>
                                                <div class="col-3 col-lg-5">
                                                    <?php echo gallery_image_html($gallery_images[2], '', $gallery['gallery_category']['service_type'], false); ?>
                                                    <?php echo gallery_image_html($gallery_images[3], '', $gallery['gallery_category']['service_type'], false); ?>
                                                </div>
                                                <div class="col-3 col-lg-12">
                                                    <div class="row">
                                                        <div class="col-12 col-lg-5">
                                                            <?php echo gallery_image_html($gallery_images[4], '', $gallery['gallery_category']['service_type'], false); ?>
                                                        </div>
                                                        <div class="col-12 col-lg-7">
                                                            <?php echo gallery_image_html($gallery_images[5], '', $gallery['gallery_category']['service_type'], false); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-7 right-col">
                                            <div class="row">
                                                <div class="col-6">
                                                    <?php echo gallery_image_html($gallery_images[6], '', $gallery['gallery_category']['service_type'], false); ?>
                                                    <?php echo gallery_image_html($gallery_images[7], 'large', $gallery['gallery_category']['service_type'], false); ?>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row">
                                                        <div class="col">
                                                            <?php echo gallery_image_html($gallery_images[8], 'large', $gallery['gallery_category']['service_type'], false); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <?php echo gallery_image_html($gallery_images[9], '', $gallery['gallery_category']['service_type'], false); ?>
                                                        </div>
                                                        <div class="col-6">
                                                            <?php echo gallery_image_html($gallery_images[10], '', $gallery['gallery_category']['service_type'], false); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endif; ?>
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                
                </div>
            </div>
        </div>
            
    <?php endif; ?>
<?php endif; ?>