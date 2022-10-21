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
	<div class="se-pre-con"></div>
    <div class="uk-grid-small uk-grid-margin-small uk-grid" uk-grid="">
      <div class="uk-width-expand@m">
        <?php
            while (have_posts()) : the_post();
                get_template_part('templates/post/content', 'page');
            endwhile; ?>
      </div>
      <div class="uk-width-medium@m uk-flex-first@m uk-first-column">
        <?php echo do_shortcode('[user_info]'); ?>
      </div>
    </div>
<?php endif;

get_footer();
