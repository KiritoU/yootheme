<?php
function relative_post()
{
    global $post;
    ob_start();
$related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 4, 'post__not_in' => array($post->ID) ) ); ?>
<h3> Truyện Cùng Thể Loại</h3>
<div class="bai-lien-quan" >
<?php if( $related ) foreach( $related as $post ) {
setup_postdata($post); ?>
<div class="uk-cover-container uk-inline uk-inline-clip uk-transition-toggle">
  <div class="uk-transition-scale-up uk-transition-opaque">
    <img data-src="<?php echo tw_get_thumbnail($recent["ID"]) ?>" alt="<?php echo $recent["post_title"] ?>" class="slider-image"  width="220" height="320" uk-img>
    <span class="tien-to"><?php get_tien_to($recent["ID"]); get_trangthai($recent["ID"]); ?></span>
    <div class="uk-position-bottom uk-overlay uk-overlay-primary uk-text-center">
      <h3 class="de-cu-title"><?php echo $recent["post_title"] ?></h3>
    </div>
  </div>
  <a class="uk-position-cover" href="<?php echo get_permalink($recent["ID"]) ?>"></a>
</div>
<?php } echo '</div>';
wp_reset_postdata(); ?>
<?php
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
add_shortcode('relative_post','relative_post');
?>
