<?php
function doc_tu_dau()
{
global $post;
ob_start();
$ID_parent = get_the_ID();
      $args2 = array(
        'post_type'      => 'chap',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'post_parent'    => $ID_parent,
        'order'          => 'ASC',
        'orderby'		 => 'ID',
      );
$chap_first = new WP_Query( $args2 );
// The Loop
if ( $chap_first->have_posts() ) :
while ( $chap_first->have_posts() ) : $chap_first->the_post(); ?>
      <a class="follow btn-follow" id="first_chap" href="<?php the_permalink($chap_first->ID)?>">
        <span class=" uk-button uk-button-tiki">Bắt Đầu Đọc</span>
      </a>
<?php endwhile;
 endif;          // Reset Post Data
wp_reset_postdata();
$result = ob_get_contents();
ob_end_clean();
return $result;
}
add_shortcode('doc_tu_dau','doc_tu_dau');
?>
