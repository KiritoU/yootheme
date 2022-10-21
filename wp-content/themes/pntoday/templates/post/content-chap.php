<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */


use YOOtheme\Util\Str;

global $theme;
$parent = $post->post_parent;
$chap = get_post($parent);
$ID = get_the_ID();
$params = $theme->get('post', []);

if (!is_single()) {
    $params->merge($theme->get('blog', []));
}

$attrs_container = [];

// Image
$attrs_image['class'][] = 'uk-text-center';
$attrs_image['class'][] = get_margin($params['image_margin']);

// Container


if ((!isset($params['column']) || $params['column'] == 1) && $params['content_width'] && ($params['content_width'] != $params['width'])) {
    $attrs_container['class'][] = "uk-container uk-container-{$params['content_width']}";
}

// Title
$attrs_title['class'][] = get_margin($params['title_margin']) . ' uk-margin-remove-bottom';
$attrs_title['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_title['class'][] = $params['title_style'] ? "uk-{$params['title_style']}" : 'uk-article-title';

// Content
$attrs_content['class'][] = get_margin($params['content_margin']);
$attrs_content['class'][] = $params['content_align'] ? 'uk-text-center' : '';
$attrs_content['class'][] = is_single() && $params['content_dropcap'] ? 'uk-dropcap' : '';

// Tags
$attrs_tags['class'][] = $params['header_align'] ? 'uk-text-center' : '';

// Button
$attrs_button['class'][] = "uk-button uk-button-{$params['button_style']}";
$attrs_button_container['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_button_container['class'][] = "uk-margin-{$params['button_margin']}";

/*
 * Image template
 */
$image = function ($attr) use ($params, $theme) {

    if (!$src = str_replace(get_site_url() . '/', '', get_the_post_thumbnail_url())) {
        return;
    }
    $meta = get_post_meta(get_post_thumbnail_id());
    $alt = isset($meta['_wp_attachment_image_alt']) ? $meta['_wp_attachment_image_alt'] : '';

    if ($theme->view->isImage($src) == 'svg') {
        $thumbnail = $theme->image->replace($theme->view->image($src, ['width' => $params['image_width'], 'height' => $params['image_height'], 'uk-img' => true, 'property' => 'url', 'alt' => $alt]));
    } else {
        $thumbnail = $theme->image->replace($theme->view->image([$src, 'thumbnail' => [$params['image_width'], $params['image_height']], 'srcset' => true], ['uk-img' => true, 'property' => 'url', 'alt' => $alt]));
    }

    ?>

    <?php if ($thumbnail) : ?>
        <div<?= get_attrs($attr) ?> property="image" typeof="ImageObject">
            <?php if (!is_single()) : ?>
                <a href="<?php the_permalink() ?>"><?= $thumbnail ?></a>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php
};

/*
 * Meta template
 */
$meta = function () use ($params) {

    $date = $params['date'] ? '<span>'.get_post_date().'</span>' : '';
    $author = $params['author'] ? get_post_author() : '';
    $category = $params['categories'] ? get_the_category_list(__(', ')) : '';
    $comments = $params['comments'] && !post_password_required() && (comments_open() || get_comments_number());

    if ($date || $author || $category || $comments) {

        $attrs_meta['class'][] = get_margin($params['meta_margin']) . ' uk-margin-remove-bottom';

        switch ($params['meta_style']) {

            case 'list':

                $attrs_meta['class'][] = 'uk-subnav uk-subnav-divider';
                $attrs_meta['class'][] = $params['header_align'] ? 'uk-flex-center' : '';

                ?>
                <ul<?= get_attrs($attrs_meta) ?>>
                    <?php foreach (array_filter([$date, $author]) as $part) : ?>
                    <li><?= $part ?></li>
                    <?php endforeach ?>

                    <?php if ($category && count(wp_get_post_categories(get_the_ID())) > 1) : ?>
                    <li><span><?= $category ?></span></li>
                    <?php elseif($category) : ?>
                    <li><?= $category ?></li>
                    <?php endif ?>

                    <?php if ($comments) : ?>
                    <li><?php comments_popup_link(__('0 Comments', 'yootheme'), __('1 Comment', 'yootheme'), __('% Comments', 'yootheme')) ?></li>
                    <?php endif ?>
                </ul>
                <?php
                break;

            default: // sentence

                $attrs_meta['class'][] = 'uk-article-meta';
                $attrs_meta['class'][] = $params['header_align'] ? 'uk-text-center' : '';

                ?>
                <p<?= get_attrs($attrs_meta) ?>>
                <?php

                if ($author && $date) {
                    printf(__('Written by %s on %s.', 'yootheme'), get_post_author(), get_post_date());
                } elseif ($author) {
                    printf(__('Written by %s.', 'yootheme'), get_post_author());
                } elseif ($date) {
                    printf(__('Written on %s.', 'yootheme'), get_post_date());
                }

                ?>
                <?php

                if ($category && $categories = get_the_category_list(__(', '))) {
                    printf(__('Posted in %1$s.', 'yootheme'), $categories);
                }

                ?>
                <?php

                if ($comments) {
                    comments_popup_link(__('Leave a Comment'), __('1 Comment', 'yootheme'), __('% Comments', 'yootheme'));
                }

                ?>
                </p>
                <?php
        }

    }

};

?>
<?php
    $parent = $post->post_parent;
    $story = get_post($parent);
    $ID = get_the_ID();
    sky_save_chapper_setcookie($parent, $ID);
    tw_views($parent);
    tw_views($post->ID);
    // var_dump($story);
?>

<article id="post-<?php the_ID() ?>" <?php post_class('uk-article') ?> typeof="Article">

    <meta property="name" content="<?= esc_html(get_the_title()) ?>">
    <meta property="author" typeof="Person" content="<?= esc_html(get_the_author()) ?>">
    <meta property="dateModified" content="<?= get_the_modified_date('c') ?>">
    <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?= get_the_date('c') ?>">

        <?php
            if (is_single()) { ?>
              <div class="chap-header" style="z-index: 980;" uk-sticky="bottom:#footer; top: viewport; ">
                <a class="truyen-title" href="<?php echo get_the_permalink($chap)?>" title="<?php echo $chap->post_title?>"><?php echo $chap->post_title?></a>
                <h2 style=""><a class="chapter-title" href="<?php the_permalink()?>" title="<?php echo $chap->post_title?> - <?php the_title()?>"><?php the_title()?></a></h2>
                <div class="chap-nav uk-text-center">
                        <?php tw_get_prev_chap($parent)?>
                        <?php tw_get_next_chap($parent)?>
                </div>
              </div>

              <div class="uk-width-1-1 reading">
                <?php the_content($post->ID); ?>
              </div>
                <div class="chap-nav uk-text-center">
                        <?php tw_get_prev_chap($parent)?>
                        <?php tw_get_next_chap($parent)?>
                </div>
      <?php } ?>
</article>
