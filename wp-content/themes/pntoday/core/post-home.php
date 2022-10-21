<?php function post_home($args){
global $post;
ob_start();

$num = $args['num'];
$term_id = $args['tax'];
$term_pro = get_term_by( 'id', $term_id,'category' );
$terms = get_term_children( $term_id, 'category' );

$args = array(
    'posts_per_page' => $num,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
    'suppress_filters' => true,
);
if($term_id != '') {
$args['tax_query'] = array(
    array(
        'taxonomy' => 'category',
        'field' => 'term_id',
        'terms' => $term_id,
    ),
);
}
$recent_posts = wp_get_recent_posts($args); ?>
<h2 class="home-title uk-heading-divider"><span uk-icon="icon: bookmark; ratio: 1.5"></span> <?php echo get_cat_name( $term_id ) ?> </h2><a href="<?php echo get_term_link($term_pro); ?>" class="uk-icon-link home-read-more" uk-icon="triangle-right">Xem thêm</a>
      <div class="uk-child-width-1-6@l uk-child-width-1-5@m uk-child-width-1-4@s uk-child-width-1-2 uk-grid-match uk-grid" uk-grid>
    	<?php foreach( $recent_posts as $recent ){ ?>
            <div class="uk-cover-container uk-inline uk-inline-clip uk-transition-toggle">
              <div class="uk-transition-scale-up uk-transition-opaque">
    						<img data-src="<?php echo tw_get_thumbnail($recent["ID"]) ?>" alt="<?php echo $recent["post_title"] ?>" class="slider-image"  width="220" height="320" uk-img>
    						<span class="tien-to"><?php get_tien_to($recent["ID"]); get_trangthai($recent["ID"]); ?></span>
    						<div class="uk-position-bottom uk-overlay uk-overlay-primary uk-text-center">
    							<h3 class="de-cu-title"><?php echo $recent["post_title"] ?></h3>
    						</div>
    					</div>
            <a class="uk-position-cover" href="<?php echo get_permalink($recent["ID"]) ?>"></a>
            </div>
        <?php 	}	wp_reset_query(); ?>
    </div>
<?php
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
add_shortcode('post_home','post_home');
?>
