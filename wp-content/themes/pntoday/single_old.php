<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

get_header();
setPostViews(get_the_ID());

if ($theme->get('builder')) :

    echo get_section('builder');

elseif (have_posts()) : ?>
<div class="uk-grid-small uk-grid-margin-small uk-grid" uk-grid="">
  <div class="uk-width-expand@m">
    <?php

        // Container
        $attrs_container = [];

        if ($content_width = $theme->get('post.content_width')) {
            $attrs_container['class'][] = "uk-container uk-container-{$content_width}";
        }

        while (have_posts()) : the_post();

            get_template_part('templates/post/content', get_post_format());

            if ($attrs_container) :
                printf('<div%s>', get_attrs($attrs_container));
            endif;
            
            if ($attrs_container) :
                echo '</div>';
            endif;

        endwhile; ?>
  </div>
  <div class="uk-width-medium@m uk-flex-first@m uk-first-column">
    <?php echo do_shortcode('[user_info]'); ?>
  </div>
</div>

<?php
endif;

get_footer();
