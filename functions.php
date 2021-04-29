<?php
// Reviews utility function. Can serve product-specific reviews

// Usage:
/*
$reviews = get_field('reviews_repeater', 'option');
$args = array(
    'slider' => false,
    'quotes' => true,
    'before-author' => '- ',
    'stars' => true,
    'aggregate' => false, // also accepts true, 'before', or 'after'
    'review_count' => -1,
    'review_length' => -1,
    'random' => true,
    'product_types' => false // Used for reviews specific to page's product_type
);
get_reviews($args, $reviews);
*/

if( !function_exists('get_reviews') ) {
	function get_reviews($args = array(), $reviews = ''){
		// Setting Review Option Defaults
		if(!is_array($args) || empty($args)){ $args = array(); }
		if(!isset($args['slider'])){ $args['slider'] = true; }
		if(!isset($args['quotes'])){ $args['quotes'] = true; }
		if(!isset($args['before-author'])){ $args['before-author'] = ''; }
		if(!isset($args['stars'])){ $args['stars'] = true; }
        if(!isset($args['aggregate'])){ $args['aggregate'] = false; }
        if(!isset($args['review_count'])){ $args['review_count'] = -1; }
        if(!isset($args['review_length'])){ $args['review_length'] = -1; }
        if(!isset($args['random'])){ $args['random'] = true; }
        if(!isset($args['product_types'])){ $args['product_types'] = false; }
        $page_product_type = '';
        if( $args['product_types'] ) {
            $page_product_type = get_field('product_type') ? get_field('product_type') : 'general';
        }
 
		$reviews_repeater = $reviews;
		if($reviews_repeater){
            
            if( $args['random'] ) { shuffle($reviews_repeater); }
            $general_reviews = array();
            $review_output = '';
            $rating_total = 0;
            $has_match = false;
 
			// Only show one review if slider option = false
			if($args['review_count'] !== -1){
				$reviews_repeater = array_slice($reviews_repeater, 0, $args['review_count']);
			}
            
			foreach($reviews_repeater as $review){
				$author = isset($review['author']) ? $review['author'] : "";
				$rating = isset($review['rating']) ? $review['rating'] : 5;
				$review_content = isset($review['review_content']) ? $review['review_content'] : "";
                $rating_total = $rating_total + $review['rating'];
                $review_product_type = isset($review['product_type']) ? $review['product_type'] : 'general';
                
                if( $args['product_types'] && $review_product_type == 'general' ) {
                    $general_reviews[] = $review;
                }
                
                if( !$args['product_types'] || ( $args['product_types'] && $page_product_type == $review_product_type ) ) {
                    $has_match = true;
				
                    if($args['slider'] == true){ $review_output .= '<div>'; }
                        
                    ob_start(); ?>
 
                        <blockquote class="custom-review">
                            <?php if($args['stars'] == true): ?>
                                <div class="stars">
                                    <?php if($review_content){ for($i = 0; $i < $rating; ++$i){ echo '<svg version="1.1" x="0px" y="0px" viewBox="0 0 95.2 93" class="custom-star"><polygon class="st0" points="48,70.8 18.4,91.4 28.8,56.9 0.1,35.1 36.2,34.3 48,0.2 59.8,34.3 95.9,35.1 67.2,56.9 77.6,91.4"/></svg>'; } } ?>
                                </div>
                            <?php endif; ?>
                            <p class="review-text">
                                <?php 
                                if( $args['quotes'] == true ){ echo '“'; }
                                if($review_content){ 
                                    if( $args['review_length'] !== -1 ) {
                                        if(strlen($review_content)<=$args['review_length']) {
                                            echo $review_content;
                                        }else {
                                            $y=substr($review_content,0,$args['review_length']) . '...';
                                            echo $y;
                                        }
                                    }else {
                                        echo $review_content;
                                    }
                                }
                                if( $args['quotes'] == true ){ echo '”'; }
                                 ?>
                            </p>
                            <span class="h2 review-author">
                                <?php if($author){ echo $args['before-author'] . $author; }else{ echo $args['before-author'] . 'Happy Customer'; } ?>
                            </span>
                        </blockquote>
                        
                    <?php 
                    $review_output .= ob_get_clean();
 
                    if($args['slider'] == true){ $review_output .= '</div>'; }
                }
			}
            
            if( $has_match == false && $args['product_types'] ) {
                $args['product_types'] = false;
                get_reviews($args, $general_reviews);
                return;
            }
            
            if( $args['aggregate'] ) {
                // Get real aggregate rating to 1 decimal.
                $aggregate_rating = round(($rating_total / count($reviews_repeater) ), 1);
                // Round any rating value decimal under .3 down and anything above to .5 for star value display.
                $aggregate_floor = floor($aggregate_rating);
                $fraction = $aggregate_rating - $aggregate_floor; 
                if( $fraction <= .3 ) {
                    $aggregate_stars = $aggregate_floor;
                }else {
                    $aggregate_stars = $aggregate_floor + .5;
                }
                
                for($i=0; $i<floor($aggregate_stars); $i++) {
                    $aggregate_stars_output .= '<svg version="1.1" x="0px" y="0px" viewBox="0 0 95.2 93" class="custom-star"><g><polygon class="st0" points="48,70.8 18.4,91.4 28.8,56.9 0.1,35.1 36.2,34.3 48,0.2 59.8,34.3 95.9,35.1 67.2,56.9 77.6,91.4"/></g></svg>';
                }
                if(floor($aggregate_stars)!=$aggregate_stars) {
                    $aggregate_stars_output .= '<svg version="1.1" x="0px" y="0px" viewBox="0 0 95.2 93" class="custom-star"><g><polygon class="st0" points="48,70.8 18.4,91.4 28.8,56.9 0.1,35.1 36.2,34.3 48,0.2 59.8,34.3 95.9,35.1 67.2,56.9 77.6,91.4"/></g></svg>';
                }
                
                if($args['slider'] == false){ echo '<div class="reviews-list">'; }
                $aggregate = '
                <div class="aggregate-rating">
                    <div class="rating-number">' . $aggregate_rating . ' Stars </div>
                    <div class="out-of">Out of ' . count($reviews_repeater) . ' Reviews</div>
                    <div class="stars">' . $aggregate_stars_output . '</div>
                </div>';
            }
            
            if( ($args['aggregate'] && $args['aggregate'] === 'before') || $args['aggregate'] == true ){ echo $aggregate; }
            
            if($args['slider'] == true){ echo '<div class="reviews-slider">'; }elseif( $args['slider'] == false && !$args['aggregate'] ) { echo '<div class="reviews-list">'; }
            
                echo $review_output;
                
            if($args['slider'] == true){ echo '</div>'; }
            
            if( $args['aggregate'] && $args['aggregate'] === 'after' ){ echo $aggregate; }
            
            if($args['slider'] == false){ echo '</div>'; }
		}
	}
}