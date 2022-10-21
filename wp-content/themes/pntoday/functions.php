<?php
// Load Theme Function
foreach (glob(dirname(__FILE__). "/core/*.php") as $file) {
	include $file;
}
add_filter('widget_text', 'do_shortcode');

function sky_add_table_columns_property( $columns ) {
	$columns[ 'featured' ] = 'Đề cử';

	return $columns;
}
add_filter( 'manage_edit-post_columns', 'sky_add_table_columns_property' );

function sky_show_table_columns_property( $column_name ) {
	global $post;
	switch ( $column_name ) {
		case 'featured':
			?>
            <style type="text/css">
                th#featured {
                    width: 75px;
                }
            </style>
			<?php
			$featured = get_post_meta( $post->ID, '_featured', true );
			$url      = wp_nonce_url( admin_url( 'admin-ajax.php?action=sky_set_feature&post_id=' . $post->ID ), 'sky-feature' );
			echo '<a href="' . esc_url( $url ) . '">';
			if ( 'yes' === $featured ) {
				echo '<span class="sky-feature" title="' . esc_html__( 'Yes', 'boxtruyen' ) . '"><i class="dashicons dashicons-star-filled "></i></span>';
			} else {
				echo '<span class="sky-feature not-featured"  title="' . esc_html__( 'No', 'boxtruyen' ) . '"><i class="dashicons dashicons-star-empty"></i></span>';
			}
			echo '</a>';
			break;
	}
}
add_filter( 'manage_post_posts_custom_column', 'sky_show_table_columns_property' );


if ( ! function_exists( 'sky_set_feature' ) ) :

	function sky_set_feature() {

		if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'sky_set_feature' ) {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'boxtruyen' ), '', array( 'response' => 403 ) );
			}

			if ( ! check_admin_referer( 'sky-feature' ) ) {
				wp_die( esc_html__( 'You have taken too long. Please go back and retry.', 'boxtruyen' ), '', array( 'response' => 403 ) );
			}

			$post_id = ! empty( $_GET[ 'post_id' ] ) ? (int) $_GET[ 'post_id' ] : '';

			if ( ! $post_id || get_post_type( $post_id ) !== 'post' ) {
				die;
			}

			$featured = get_post_meta( $post_id, '_featured', true );

			if ( 'yes' === $featured ) {
				update_post_meta( $post_id, '_featured', 'no' );
			} else {
				update_post_meta( $post_id, '_featured', 'yes' );
			}

			wp_safe_redirect( esc_url_raw( remove_query_arg( array(
				'trashed',
				'untrashed',
				'deleted',
				'ids',
			), wp_get_referer() ) ) );
			die();
		}
	}

	add_action( 'admin_init', 'sky_set_feature' );

endif;

// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
    if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}

function video_view()
{
	ob_start();
	$link = get_field('field_601d685ea50eb');
	// echo $link;
	?>
	<div class="play-video">
		<iframe src="<?= $link ?>" allowfullscreen="true" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'video_view', 'video_view' );

// Change posts per page in the design category
add_action( 'pre_get_posts', 'mp_design_cat_posts_per_page' );
function mp_design_cat_posts_per_page( $query ) {
	if( $query->is_main_query() && is_category( 'tv-show' ) && ! is_admin() ) {
		$query->set( 'posts_per_page', '100' );
	}
}

function chap()
{
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
	echo '<pre>';
	print_r($chap_first);
	echo '</pre>';

	return ob_get_clean();
}
add_shortcode('chap','chap');