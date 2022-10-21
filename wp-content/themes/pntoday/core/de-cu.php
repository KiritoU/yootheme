<?php function de_cu($args){
global $post;
ob_start();

	$args = array(
	    'post_type' => 'post',
	    'ignore_sticky_posts' => 1,
      'orderby'             => 'modified',
      'meta_query' => array(
          array(
                'key'     => '_featured',
                'value'   => 'yes',
                'compare' => '=',
              ),
            ),
	    );

$recent_posts = wp_get_recent_posts($args);?>
<h2 class="home-title uk-heading-divider"><img src="/wp-content/themes/pntoday/image/featured.png" width="30" style="vertical-align: text-bottom;"/> Đề Cử </h2><a href="/de-cu/" class="uk-icon-link home-read-more" uk-icon="triangle-right">Xem thêm</a>
  <div uk-slider="autoplay: true">
      <div class="uk-position-relative">
        <div class="uk-slider-container">
        <ul class="uk-slider-items uk-child-width-1-6@l uk-child-width-1-5@m uk-child-width-1-4@s uk-child-width-1-2 uk-grid-match uk-grid">
    	<?php   foreach( $recent_posts as $recent ){ ?>
				<li class="uk-cover-container uk-inline uk-inline-clip uk-transition-toggle">
					<div class="uk-transition-scale-up uk-transition-opaque">
						<img data-src="<?php echo tw_get_thumbnail($recent["ID"]) ?>" alt="<?php echo $recent["post_title"] ?>" class="slider-image"  width="220" height="320" uk-img>
						<span class="tien-to"><?php get_tien_to($recent["ID"]); get_trangthai($recent["ID"]); ?></span>
						<div class="uk-position-bottom uk-overlay uk-overlay-primary uk-text-center">
							<h3 class="de-cu-title"><?php echo $recent["post_title"] ?></h3>
						</div>
					</div>
				<a class="uk-position-cover" href="<?php echo get_permalink($recent["ID"]) ?>"></a>
			</li>
        <?php 	}	wp_reset_query(); ?>
      </ul>
			<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
    </div>
</div>
</div>
<?php
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
add_shortcode('de_cu','de_cu');
?>
