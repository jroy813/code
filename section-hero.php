<?php 
$homepage_hero = get_field('homepage_hero', 'option');
$type_of_hero = $homepage_hero['type_of_hero'];
?>

<div class="section-hero-home">
	<div class="hero-slider lazy-slider">
		
		<?php		
		if( $type_of_hero == 'Image Slider' ) :
			$slides = $homepage_hero['hero_slides'];
			
			if( !empty($slides) ) :
					
				foreach( $slides as $slide ) : 
					$slide_small_title = $slide['small_text'];
					$slide_large_title = $slide['large_title'];
					$image = isset($slide['image']['sizes']) ? $slide['image']['sizes'] : '';
					?>
				
					<div class="slide">
						<div class="bg-image slide-bg-lazy lazyload-bgset" data-bg-lg="<?php echo $image['extra_large']; ?>" data-bg-md="<?php echo $image['large']; ?>" data-bg-xs="<?php echo $image['medium_large']; ?>"></div>
						<div class="slide-text to-animate fadeInUp">
							<div class="small-text"><?php echo $slide_small_title; ?></div>
							<div class="large-text"><?php echo $slide_large_title; ?></div>
						</div>
					</div>
				
				<?php endforeach; ?>
				
			<?php endif; ?>
			
		<?php else : 
			
			$small_title = $homepage_hero['small_title'];
			$large_title = $homepage_hero['large_title'];
			$length_of_video = $homepage_hero['length_of_video'];
			$type_of_video = $homepage_hero['type_of_video'];
			if( $type_of_video == 'File' ) {
				$video_url = $homepage_hero['video_upload'];
			}else if( $type_of_video == 'Youtube' ) {
				$video_url = 'https://www.youtube.com/embed/' . $homepage_hero['youtube_video_id'] . '?controls=0&showinfo=0&loop=1&rel=0&autoplay=1&mute=1';
			}else if( $type_of_video == 'Vimeo' ) {
				$video_url = 'https://player.vimeo.com/video/' . $homepage_hero['vimeo_video_id'] . '?loop=1&autoplay=1&muted=1';
			}
			?>
		
			<div class="slide video-slide" data-video-length="<?php  echo $length_of_video; ?>">
				<div class="bg-video lazyload lazyload-webp" data-bg="<?php echo get_template_directory_uri(); ?>/images/video-poster.jpg" data-bg-webp="<?php echo get_template_directory_uri(); ?>/images/video-poster.webp">
					<?php if( $type_of_video == 'Youtube' || $type_of_video == 'Vimeo' ) : ?>
					
						<iframe width="1920" height="800" src="<?php echo $video_url; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						
					<?php else : ?>
					
						<video autoplay muted id="myVideo">
							<source src="<?php echo $video_url; ?>" type="video/mp4">
						</video>
					
					<?php endif; ?>
				</div>
				<div class="slide-text">
					<div class="small-text"><?php echo $small_title; ?></div>
					<div class="large-text"><?php echo $large_title; ?></div>
				</div>
			</div>
			
		<?php endif; ?>
		
	</div>
	<?php echo hero_form(); ?>
</div>