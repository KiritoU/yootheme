<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

?>

<div><?php _e('<a class="title-truyen">Chưa có truyện nào được đăng</a>', 'yootheme') ?></div>

<?php if (is_home() && current_user_can('publish_posts')) : ?>

    <p><?php printf(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'yootheme'), esc_url(admin_url('post-new.php'))) ?></p>

<?php elseif (is_search()) : ?>

    <p><?php _e('Tên truyện bạn tìm kiếm không tồn tại, vui lòng tìm lại với tên khác.', 'yootheme') ?></p>

    <?php get_search_form() ?>

<?php else : ?>

    <p><?php _e('Chưa có truyện nào trong mục này, vui lòng trở lại sau hoặc tìm kiếm truyện khác.', 'yootheme') ?></p>

    <?php get_search_form() ?>

<?php endif ?>
<div class="uk-position-relative uk-margin-small">
<?php echo do_shortcode('[de_cu]'); ?>
</div>
