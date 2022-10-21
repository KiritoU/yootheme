<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#category
 */

get_header();

?>

<?php if (have_posts()) :

    // Header
    $description = get_the_archive_description();

    $attrs_title = [];
    $attrs_description = [];

    if ($description) {
        $attrs_description['class'][] = 'uk-margin-small-bottom';
    } else {
        $attrs_title['class'][] = 'uk-margin-small-bottom';
    }

    if ($theme->get('post.header_align')) {
        $attrs_title['class'][] = 'uk-text-center';
        $attrs_description['class'][] = 'uk-text-center';
    }

    // Grid
    $columns = $theme->get('blog.column', 1);

    $attrs = [];
    $options = [];

    $options[] = $theme->get('blog.grid_masonry') ? 'masonry: true' : '';
    $options[] = $theme->get('blog.grid_parallax') ? "parallax: {$theme->get('blog.grid_parallax')}" : '';
    $attrs['uk-grid'] = implode(';', array_filter($options)) ?: true;

    // Columns
    $breakpoint = $theme->get('blog.grid_breakpoint');
    $breakpoints = ['s', 'm', 'l', 'xl'];
    $pos = array_search($breakpoint, $breakpoints);

    if ($pos === false || $columns === 1) {
        $attrs['class'][] = "uk-child-width-1-{$columns}";
    } else {
        for ($i = $columns; $i > 0; $i--) {
            if (($pos > -1) && ($i > 1)) {
                $attrs['class'][] = "uk-child-width-1-{$i}@{$breakpoints[$pos]}";
                $pos--;
            }
        }
    }

    $column_gap = $theme->get('blog.grid_column_gap');
    $row_gap = $theme->get('blog.grid_row_gap');

    if ($column_gap == $row_gap) {
        $attrs['class'][] = $column_gap ? "uk-grid-{$column_gap}" : '';
    } else {
        $attrs['class'][] = $column_gap ? "uk-grid-column-{$column_gap}" : '';
        $attrs['class'][] = $row_gap ? "uk-grid-row-{$row_gap}" : '';
    }

    ?>
    <div class="uk-grid-small uk-grid-margin-small uk-grid" uk-grid="">
        <div class="uk-width-expand@m page-content">
          <h1 class="home-title uk-heading-divider"><span uk-icon="icon: bookmark; ratio: 1.5"></span> <?= single_cat_title() ?></h1>
          <div class="uk-child-width-1-6@l uk-child-width-1-5@m uk-child-width-1-3@s uk-child-width-1-2 uk-grid-match uk-grid-small uk-grid" uk-grid>
              <?php while(have_posts()) : the_post(); ?>
              <div class="cat-truyen"><?php get_template_part('templates/post/content', get_post_format()); ?></div>
              <?php endwhile ?>
          </div>
          <?php
          get_template_part('templates/pagination', 'archive'); ?>
        </div>
        <div class="uk-width-medium@m uk-flex-first@m uk-first-column">
          <?php echo do_shortcode('[user_info]'); ?>
        </div>
    </div>
  <?php  else : ?>
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
