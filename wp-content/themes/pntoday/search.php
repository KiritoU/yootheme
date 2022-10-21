<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 */

get_header();

?>

<?php if (have_posts()) :

    $attrs_title['class'][] = 'uk-margin-medium-bottom';
    $attrs_title['class'][] = $theme->get('post.header_align') ? 'uk-text-center' : '';

    ?>
    <div class="uk-grid-small uk-grid-margin-small uk-grid" uk-grid="">
      <div class="uk-width-expand@m page-content">
        <h3<?= get_attrs($attrs_title) ?>><?php printf(__( 'Search Results for &#8220;%s&#8221;' ), '<span>' . get_search_query() . '</span>') ?></h3>
        <div class="uk-child-width-1-6@l uk-child-width-1-5@m uk-child-width-1-3@s uk-child-width-1-2 uk-grid-match uk-grid " uk-grid>
        <?php while (have_posts()) : the_post();
                get_template_part('templates/post/content', 'search');
              endwhile; ?>
          </div>
        <?php        get_template_part('templates/pagination', 'search'); ?>
      </div>
      <div class="uk-width-medium@m uk-flex-first@m uk-first-column">
        <?php echo do_shortcode('[user_info]'); ?>
      </div>
    </div>
<?php else : ?>
  <div class="uk-grid-small uk-grid-margin-small uk-grid" uk-grid="">
    <div class="uk-width-expand@m page-content">
      <?php get_template_part('templates/post/content', 'none'); ?>
    </div>
    <div class="uk-width-medium@m uk-flex-first@m uk-first-column">
      <?php echo do_shortcode('[user_info]'); ?>
    </div>
</div>
<?php endif;
get_footer();
