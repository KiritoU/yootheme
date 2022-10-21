<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

use YOOtheme\Util\Str;

global $theme;
$ID_parent = get_the_ID();

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
            <?php if (is_single()) : ?>
                <?= $thumbnail ?>
            <?php else : ?>
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

<article id="post-<?php the_ID() ?>" <?php post_class('uk-article') ?> typeof="Article">

    <meta property="name" content="<?= esc_html(get_the_title()) ?>">
    <meta property="author" typeof="Person" content="<?= esc_html(get_the_author()) ?>">
    <meta property="dateModified" content="<?= get_the_modified_date('c') ?>">
    <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?= get_the_date('c') ?>">

<?php if(is_single()) { ?>
  <?php
  	$reading_id = sky_get_chapper_reading($ID_parent);

  	function get_info($id,$info){
  		$result = get_post($id);
  		$result = $result->$info;
  		return $result;
  	}
  ?>
  <h1><a class="title-truyen" href="<?php esc_url(get_permalink()) ?>"><?php the_title(); ?></a></h1>
      <div class="uk-grid-small" uk-grid>
        <div class="uk-width-1-3@s">
            <div class="image-truyen uk-card uk-card-default uk-card-small uk-card-body"><img data-src="<?php echo tw_get_thumbnail(get_the_ID()) ?>" alt="<?php the_title()?>" class="slider-image"  width="220" height="320" uk-img></div>
            <ul class="info-truyen uk-list uk-list-divider">
                <li>
                  <b>Tác giả:</b> <?php the_terms( $post->ID, 'tac-gia', '', ',' ) ?>
                </li>
                <li>
                  <b>Thể Loại:</b>  <?php the_category(', ')?>
                </li>
                <li>
                  <b>Trạng Thái:</b> <?php echo get_post_meta($ID_parent, 'tw_status', true)?>
                </li>
            </ul>

        </div>
        <div class="uk-width-expand@s">
          <input id="id_post" type="hidden" value="<?php echo $ID_parent?>">
            <div class="uk-card uk-card-small">
              <div class="noi-dung">
              <?php  the_content(); ?>
              </div>
            </div>
            <div class="uk-padding-small">
        	  <?php
              if (is_user_logged_in()) {
                echo '<div class="uk-button uk-button-fahasa add-follow">';
                $user_id = get_current_user_id();
              if (tw_check_follow($user_id, get_the_ID())) {
                echo '<a href="javascript:void(0);" class="followed btn-follow" item-id="'. get_the_ID() .'">Bỏ theo dõi</a>';
              } else {
                echo '<a href="javascript:void(0);" class="follow btn-follow" item-id="'. get_the_ID() .'"> Theo dõi</a>';
              }
                echo '</div>';
              } else { ?>
                <!-- This is a button toggling the modal -->
                <button class="uk-button uk-button-shopee" uk-toggle="target: #login-follow" type="button">Theo dõi</button>

                <!-- This is the modal -->
                <div id="login-follow" uk-modal>
                    <div class="uk-modal-dialog uk-modal-body">
                        <h2 class="uk-modal-title uk-text-center">Đăng nhập để theo dõi truyện này</h2>
                        <?php echo do_shortcode('[ultimatemember form_id="3840"]');?>
                    </div>
                </div>

              <?php }

              if($reading_id !== null){
                echo '<a href="'.get_permalink( $reading_id ).'" class="uk-margin-small uk-button uk-button-tiki" item-id="'. get_the_ID() .'">Đọc tiếp</a>';
              } else {
                echo do_shortcode('[doc_tu_dau]');
              }
            ?>
          </div>
        </div>
      </div>
	         <ul class="uk-subnav uk-margin-top list-chap" uk-switcher="animation: uk-animation-fade">
                <li><a href="#">Danh Sách Chương</a></li>
                <li><a href="#">Bình Luận</a></li>
            </ul>

            <ul class="uk-switcher">
                <li>
                  <div class="list">
                  	<?php if(get_post_meta($ID_parent, 'tw_multi_chap', true) == 1){
                      $args = array(
          							'post_type'      => 'chap',
          							'post_status'    => 'publish',
          							'ignore_sticky_posts' => -1,
          							'posts_per_page' => -1,
          							'post_parent'    => $ID_parent,
          							'order'          => 'DESC',
          							'orderby'		 => 'ID',
          							'paged' => $paged
          							);
          						$wp_query = new wp_query($args);
          						$i = 1;
          						?>
          						<?php while($wp_query->have_posts()):?>
          						<?php $wp_query->the_post(); ?>
                      <div class="">
                        <a class="chap-title" href="<?php the_permalink()?>" title="<?php the_title()?>"><?php the_title()?></a>
                        <span class="time-ago"><?php echo human_time_diff( get_post_modified_time(), current_time('timestamp') ).' ago'; ?></span>
                      </div>
          						<?php endwhile;?>
                    <?php } wp_reset_query();?>
                </li>
                <li>
                  <div class="list">
                    <?php if (comments_open() || get_comments_number()) :
                            comments_template();
                            endif; ?>
                    </div>
                </li>
            </ul>
<?php } else { ?>
              <!-- Truyện hiển thị ngoài category -->
            <div id="page-de-cu" class="uk-cover-container uk-inline uk-inline-clip uk-transition-toggle">
              <div class="uk-transition-scale-up uk-transition-opaque">
    						<img data-src="<?php echo tw_get_thumbnail(get_the_ID()) ?>" alt="<?php the_title()?>" class="slider-image"  width="220" height="320" uk-img>
    						<span class="tien-to"><?php get_tien_to(get_the_ID()); get_trangthai(get_the_ID()); ?></span>
    						<div class="uk-position-bottom uk-overlay uk-overlay-primary uk-text-center">
    							<h3 class="de-cu-title"><?php the_title()?><div class="chap-title"><?php last_update(false)?></div></h3>
    						</div>
    					</div>
            <a class="uk-position-cover" href="<?php the_permalink()?>"></a>
            </div>

<?php } ?>
<?php setPostViews($ID_parent); ?>
</article>
