<?php
/**
 * Template part for displaying pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

?>
<article id="post-<?php the_ID() ?>" <?php post_class('uk-article') ?> typeof="Article">

    <meta property="name" content="<?= esc_html(get_the_title()) ?>">
    <meta property="author" typeof="Person" content="<?= esc_html(get_the_author()) ?>">
    <meta property="dateModified" content="<?= get_the_modified_date('c') ?>">
    <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?= get_the_date('c') ?>">

    <h1 class="home-title uk-heading-divider"><span uk-icon="icon: bookmark; ratio: 1.5"></span> <?php the_title() ?></h1>

    <div class="uk-margin-medium" property="text"><?php the_content('') ?></div>

    <?php wp_link_pages(['before' => '<div class="uk-margin-medium">' . __('Pages:') . '<ul class="uk-pagination">', 'after'  => '</ul></div>']) ?>

    <?php if ($edit = get_edit_post_link()) : ?>
    <p>
        <a href="<?= esc_url($edit) ?>"><?= sprintf(__('%1$s Edit', 'yootheme'), '<span uk-icon="pencil"></span>') ?></a>
    </p>
    <?php endif ?>

</article>