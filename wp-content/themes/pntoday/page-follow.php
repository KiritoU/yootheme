<?php
/**
 * The template for displaying all pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-page
 */

get_header();

if ($theme->get('builder')) :

    echo get_section('builder');
    elseif (have_posts()) : ?>
		<?php
		$pid = get_the_ID();
		function get_info($id,$info){
				$result = get_post($id);
				$result = $result->$info;
				return $result;
			}

			if (is_user_logged_in()) {
		        $user_id = get_current_user_id();
		        $current_follow = get_user_meta( $user_id, '_wp_manga_history' );
			}
			$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
			$args = array(
				'order'      => 'ASC',
				'post_type'  => 'post',
				'post__in'   => $current_follow[0],
				'paged'      => $paged,
				'orderby'    => 'modified',
				'ignore_sticky_posts' => -1,

			);
			$wp_query = new wp_query($args);

		?>
    <div class="uk-grid-small uk-grid-margin-small uk-grid" uk-grid="">
      <div class="uk-width-expand@m page-content">
        <h1 class="home-title uk-heading-divider"><span uk-icon="icon: bookmark; ratio: 1.5"></span> <?php the_title() ?></h1>
        <div class="uk-child-width-1-6@l uk-child-width-1-5@m uk-child-width-1-3@s uk-child-width-1-2 uk-grid-match uk-grid" uk-grid>
        <?php
            while (have_posts()) : the_post(); ?>
            <div id="page-moi" class="uk-cover-container uk-inline uk-inline-clip uk-transition-toggle">
              <div class="uk-transition-scale-up uk-transition-opaque">
                <img data-src="<?php echo tw_get_thumbnail(get_the_ID()) ?>" alt="<?php echo $recent["post_title"] ?>" class="slider-image"  width="220" height="320" uk-img>
                <span class="tien-to"><?php get_tien_to(get_the_ID()); get_trangthai(get_the_ID()); ?></span>
                <div class="uk-position-bottom uk-overlay uk-overlay-primary uk-text-center">
                  <h3 class="de-cu-title"><?php the_title()?><div class="chap-title"><?php last_update(false)?></div></h3>
                </div>
              </div>
            <a class="uk-position-cover" href="<?php the_permalink()?>"></a>
            </div>
        <?php  endwhile; ?>
      </div>
      <?php pagination();?>
    </div>
      <div class="uk-width-medium@m uk-flex-first@m uk-first-column">
        <?php echo do_shortcode('[user_info]'); ?>
      </div>
    </div>
<?php endif;

get_footer();