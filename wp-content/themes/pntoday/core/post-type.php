<?php
add_theme_support('post-thumbnails', array('post', 'chap'));
//add_image_size('image', 215, 322, true);
//set_post_thumbnail_size( 215, 322, true );
add_theme_support( 'custom-logo' );
add_filter('excerpt_length', 'custom_excerpt_length', 999 );
add_filter('wp_trim_excerpt', 'tw_excerpt_more' );
add_filter('parse_query', 'tw_add_filter');

add_action('init', 'tw_chap_post_type', 0);
add_action('wp_ajax_tw_ajax', 'tw_ajax');
add_action('wp_ajax_nopriv_tw_ajax', 'tw_ajax');
add_action('save_post', 'tw_save_post');
add_action('save_post', 'bt_save_post');

// xóa bộ lọc của wordpress
//remove_filter( 'the_title', 'wptexturize' );


//-------function------//

function tw_save_post($post_id){

    $chapID = isset($_POST['tw_parent']) ? $_POST['tw_parent'] : false;
    if (!wp_is_post_revision($post_id) && $chapID){
        remove_action('save_post', 'tw_save_post');
        $postdata = array(
            'ID' => $post_id,
            'post_parent' => $chapID
        );
        wp_update_post( $postdata );
    }
}
function tw_search_filter( $query ) {

    if ( $query->is_search && $query->is_main_query() )
        $query->set('post_type', array('post', 'tac-gia'));
}

function tw_add_filter($query){

    global $pagenow;
    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['parent_chap']) && $_GET['parent_chap'] != '') {
        $query->query_vars['post_parent'] = $_GET['parent_chap'];
    }
}


// TW_GET_FIRST_CHAP
function tw_get_first_chap($custom = false){
    $options = get_option('my_option_name');
    global $post;
    $args = array(
        'post_type'      => 'chap',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'post_parent'    => $ID_parent,
        'order'          => 'DESC'
    );
    $c_query = new wp_query($args);
    /* if (get_post_meta($post->ID, 'tw_status', true) == 'Hoàn Thành') {
        echo 'Full';
    }
    else { */
        if($c_query->have_posts()){
            while($c_query->have_posts()){
                $c_query->the_post();
                $title = __( 'Đọc mới nhât', 'nettruyen' );
                $title = explode(':', get_the_title());
                if($custom){
                   echo $title[0];
                }
                else {
                    echo '<a class="btn btn-success" id="prev_chap" href="'.get_the_permalink($chap->ID).'"><span class="glyphicon glyphicon-chevron-left"></span> <span class="hidden-xs">Chương trước</a>';
                    echo '<a title="'.get_the_title($post->post_parent).' - '.get_the_title().'" href="'.get_the_permalink().'"><span class="chapter-text"><span>'.$title[0].'</span></span></a>';
                }
            }
        }
        else {
        if(get_post_meta($post->ID,'tw_multi_chap',true) == 1){
            echo $options['chuaconoidung'] ? $options['chuaconoidung'] : 'Chưa có nội dung';
        } else {
            echo $options['chuaconoidung2'] ? $options['chuaconoidung2'] : 'Chưa có nội dung';
        }
        unset($c_query);
        }
    //}
}
// TW_GET_LAST_CHAP
function tw_get_last_chap($id){
    global $wpdb;
    $first_post_id = $_POST['1'];
    $query = $wpdb->get_results("select * from  ".$wpdb->posts." where ID < '$first_post_id' AND post_type = 'chap' and post_parent = '$id' and post_status = 'publish' ORDER BY ID DESC LIMIT 1");
    if($query){
        foreach($query as $chap) {
            echo '<a class="btn btn-warning" id="first_chap" href="'.get_the_permalink($chap->ID).'"> <span class="hidden-xs">Đọc mới nhất</a>';
        }
    }
    else
        echo '<a class="btn btn-warning disabled" href="javascript:void(0)" title="Không có chương trước" id="first_chap"><span class="hidden-xs">Đọc mới nhất</a>';
}

function last_update($custom = false){
    $options = get_option('my_option_name');
    global $post;
    $args = array(
        'post_type'      => 'chap',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'post_parent'    => $post->ID,
        'order'          => 'DESC'
    );
    $c_query = new wp_query($args);
        if($c_query->have_posts()){
            while($c_query->have_posts()){
                $c_query->the_post();
                $title = get_the_title();
                $title = explode(':', get_the_title());
                if($custom){
                   echo $title[0];
                }
                else {
                    echo '<a href="'.get_the_permalink().'"><span class="chap-text"><span>'.$title[0].'</span></span></a>';
                }
            }
        }
        else {
        if(get_post_meta($post->ID,'tw_multi_chap',true) == 1){
            echo $options['chuaconoidung'] ? $options['chuaconoidung'] : 'Chưa có nội dung';
        } else {
            echo $options['chuaconoidung2'] ? $options['chuaconoidung2'] : 'Chưa có nội dung';
        }
        unset($c_query);
        }
        wp_reset_postdata();
}



// TW_AJAX
function tw_ajax(){

    switch($_POST['type']){
        case 'pagination':
            chap_pagination(intval($_POST['id']), intval($_POST['page']));
        break;
        case 'list_chap':
            tw_get_chap_option($_POST['id'], $_POST['chap']);
        break;
        default:
        case 'raty':
        $id   = $_POST['id'];
        $rate = $_POST['score'];
        echo tw_update_rate($id, $rate);
        break;
    }
    die();
}


// TW_GET_RATE
function tw_get_rate($postID){

    $count_key = 'tw_rate';
    $count     = get_post_meta($postID, $count_key, true);
    if($count == ''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "10";
    }
    return $count;

}

// TW_GET_TOTAL_RATE
function tw_get_total_rate($postID){

    $count_key = 'tw_total_rate';
    $count     = get_post_meta($postID, $count_key, true);
    if($count == ''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;

}

// TW_UPDATE_RATE
function tw_update_rate($postID, $rate) {

    //update rate
    $tw_rate = get_post_meta($postID, 'tw_rate', true);
    $tw_rate =  $tw_rate + $rate;
    update_post_meta($postID, 'tw_rate', $tw_rate);

    //update total rate
    $count     = get_post_meta($postID, 'tw_total_rate', true);
    $count++;
    update_post_meta($postID, 'tw_total_rate', $count);

    return json_encode(array('status' => 'success', 'rateCount' => $count, 'ratePoint' => ceil($tw_rate / $count)));
}


// TW_GET_VIEWS
function tw_get_views($postID){

    $count_key = 'tw_views_post';
    $count     = get_post_meta($postID, $count_key, true);
    if($count == ''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;

}

// TW_VIEWS
function tw_views($postID) {

    $count_key = 'tw_views_post';
    $count     = get_post_meta($postID, $count_key, true);
    if($count == ''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }
    else
    {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


// TW_GET_THUMBNAIL
function tw_get_thumbnail($id){

    global $post;
    if($id){
    	$post = get_post($id);
    }
    $thumbnail_src = get_the_post_thumbnail_url($id);
    $parent_thumbnail_src = get_the_post_thumbnail_url(wp_get_post_parent_id($id));
    if($thumbnail_src)
        return $thumbnail_src;
    elseif($parent_thumbnail_src)
        return $parent_thumbnail_src;
    // elseif(preg_match("/(http|https):\/\/[^\s]+(\.gif|\.jpg|\.jpeg|\.png)/is", $post->post_content, $thumb))
    //     return $thumb;
     else
         return '/wp-content/themes/pntoday/screenshot.png';
}

// REGISTER TAXONOMY: TAC-GIA
function tw_add_author(){

    $args = array(
        'labels'            => array(
                                'name'      => 'Tác giả',
                                'singular'  => 'Tác giả',
                                'menu-name' => 'Tác giả',
				'all_items' => 'Tất cả tác giả',
				'edit_item' => 'Chỉnh sửa tác giả',
				'view_item' => 'Xem tác giả',
				'add_new_item' => 'Thêm tác giả',
				'new_item_name' => 'Tên tác giả',
				'parent_item' => 'Tác giả cha',
				'search_items' => 'Tìm tác giả',
				'popular_items' => 'Tác giả phổ biến',
				'separate_items_with_commas' => 'Phân tách các tác giả bằng dấu phẩy.',
				'add_or_remove_items' => 'Thêm hoặc xóa tác giả',
				'choose_from_most_used' => 'Chọn tác giả dùng nhiều nhất'
                                ),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_tagcloud'     => true,
        'show_in_nav_menus' => true
        );

    register_taxonomy('tac-gia','post', $args);

}

// REGISTER POST TYPE: chap
function tw_chap_post_type(){

    $label = array(
        'name' => 'chap',
    	'singular_name' => 'chap',
    	'add_new' => 'Thêm Chương',
    	'add_new_item' => 'Thêm Chương',
    	'edit_item' => 'Chỉnh sửa chap',
    	'new_item' => 'chap',
    	'view_item' => 'Xem chap',
    	'search_items' => 'Tìm chap',
    	'not_found' => 'Không có chap nào',
    	'not_found_in_trash' => 'Không có chap nào trong thùng rác',
    	'all_items' => 'Tất cả chap',
    	'menu_name' => 'chap',
    	'name_admin_bar' => 'chap',
    );

    $args = array(
        'labels'              => $label,
        'description'         => 'Tất cả chap',
        'supports'            => array( 'title', 'editor', 'parent', 'revisions', 'thumbnail', 'comments'
                                ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => '',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post'
    );

    register_post_type('chap', $args);
    flush_rewrite_rules();

}

// GET TIEN_TO AND TRANGTHAI
function get_tien_to($id){
	$tiento = get_the_terms($id,'label-story');

	if(is_array($tiento)){
		foreach ($tiento as $e_tiento){
			$termid = $e_tiento->term_id;
			$name = $e_tiento->name;
			$slug = $e_tiento->slug;
			echo '<span class="tien_to tien_to_'.$slug.'">'.$name.'</span> ';
		}
	}
}

function get_trangthai($id){
	$trangthai = get_post_meta($id,'tw_status',true);
	$options = get_option('my_option_name');
	$hoanthanh = $options['trangthai_hoanthanh'] ? $options['trangthai_hoanthanh'] : 'Hoàn thành';
	if($trangthai == $hoanthanh){
		echo '<span class="tien_to trang_thai_full">'; echo $options['trangthai_hoanthanh'] ? $options['trangthai_hoanthanh'] : 'Full'; echo '</span> ';
	}
}

function get_tien_to_2($id){
	$tiento = get_the_terms($id,'tien_to');

	if(is_array($tiento)){
		foreach ($tiento as $e_tiento){
			$termid = $e_tiento->term_id;
			$name = $e_tiento->name;
			$slug = $e_tiento->slug;
			echo '<span class="tien_to tien_to_2 tien_to_'.$slug.'">'.$name.'</span> ';
		}
	}
}

function get_trangthai_2($id){
	$trangthai = get_post_meta($id,'tw_status',true);
	$options = get_option('my_option_name');
	$hoanthanh = $options['trangthai_hoanthanh'] ? $options['trangthai_hoanthanh'] : 'Hoàn thành';
	if($trangthai == $hoanthanh){
		echo '<img src="'; echo bloginfo('template_url'); echo '/assets/images/full-label.png" class="full_label"/>';
	}
}

// EXPCERPT
function custom_excerpt_length($length){
    return 40;
}

function tw_excerpt_more( $excerpt ) {
    return str_replace( '[...]', '...', $excerpt );
}

// chap PAGINATION
function chap_pagination($ID_parent, $page) {
	$options = get_option('my_option_name');
	$chapnum = $options['number'];
    	$args = array(
        'post_type'      => 'chap',
        'post_status'    => 'publish',
        'posts_per_page' => $chapnum,
        'paged'          => $page,
        'post_parent'    => $ID_parent,
        'order'          => 'ASC'
        );
    $my_query = new wp_query($args);
    $html = '<div class="col-xs-12 col-sm-6 col-md-6"><ul class="list-chap">';
    $i = 1;
    while($my_query->have_posts()){
        $my_query->the_post();
        if($i == ($chapnum+1))
            $html .= '</ul></div><div class="col-xs-12 col-sm-6 col-md-6"><ul class="list-chap">';
        $html .= '<li>
        <span class="glyphicon glyphicon-certificate"></span>
        <a href="'.get_the_permalink().'" title="'.get_the_title($post->post_parent).' - '.get_the_title().'">
            <span class="chap-text">'.get_the_title().'</span>
        </a>
        </li>';
        ++$i;
    }
    $html .= '</ul></div>';
    $pagination = preg_replace("/href=\"(.+?)\"/is", 'href="#"', pagination(true, $my_query->max_num_pages, $page));
    echo json_encode(array('list_chap' => $html, 'pagination' => $pagination));
}


// PAGINATION
function pagination($return = false, $max = false, $paged = false) {

    global $wp_query;

    if($wp_query->max_num_pages <= 1 && !$max)
        return 'Trang không tồn tại<br/><br/>';
    if(!$paged)
        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = (!$max) ? intval($wp_query->max_num_pages) : $max;
    $links[] = $paged;
    for($i = $paged; $i < $paged + 5; $i++){
        if($i <= $max && $i != $paged)
            $links[] = $i;
    }
    for($i = $paged; $i >= ($paged - 5);$i--){
        if($i >= 1 && $i != $paged)
            $links[] = $i;
    }
    $html = '<ul class="pagination pagination-sm">' . "\n";

    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
        $html .= '<li '.$class.'><a data-page="1" href="'.esc_url( get_pagenum_link( 1 ) ).'" title="1">Đầu</a></li>';

    }

    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $link == $paged ? ' class="active"' : '';
        $html .= '<li '.$class.'><a data-page="'.$link.'" href="'.esc_url( get_pagenum_link( $link ) ).'">'.$link.'</a></li>';
    }

    if ( ! in_array( $max, $links ) ) {
        $class = $paged == $max ? ' class="active"' : '';
        $html .= '<li '.$class.'><a data-page="'.$max.'" href="'.esc_url( get_pagenum_link( $max ) ).'" title="'.$max.'">Cuối</a></li>';
    }

    if($return)
        return $html;
    else
        echo $html;
}


//REWRITE URL
add_filter('post_type_link', 'tw_rewrite_chap_link', 10, 3);
add_action('init', 'tw_add_new_rules');
function tw_rewrite_chap_link($link, $post){
    if($post->post_type == 'chap') {
        $parents = get_post_ancestors($post->ID);
        $parent_id = ($parents) ? $parents[count($parents) - 1] : 0;
        $parent = get_post($parent_id);
        $chapname = preg_replace('/'.$parent->post_name.'-/','',$post->post_name,1);
        $newlink = $parent->post_name . '/' . $chapname;
        return home_url($newlink);
    }
    else {
        return $link;
    }
}

function tw_add_new_rules() {

	add_rewrite_rule('^tac-gia/([^/]+)'.'$','index.php?tac-gia=$matches[1]', 'top');
	add_rewrite_rule('([^/]+)/([^/]+)'.'$','index.php?post_type=chap&name=$matches[1]-$matches[2]', 'top');
}



add_filter('wp_nav_menu_args', 'prefix_nav_menu_args');
function prefix_nav_menu_args($args = ''){
    $args['container'] = false;
    return $args;
}


// ADD ALL chap MENU
add_action('admin_menu', 'add_custom_link_into_post_menu');
function add_custom_link_into_post_menu() {
    global $submenu;
    $permalink = get_site_url().'/wp-admin/edit.php?post_type=chap';
    $submenu['edit.php'][] = array( 'Tất cả chap', 'manage_options', $permalink);
}


// UPDATE NEW chap THEN SORT THE STORY FIRST
function bt_save_post($post_id){
    if(get_post_type( $post_id ) == 'chap'){
    	if(wp_get_post_parent_id($post_id) > 0){
	        $my_post = array(
		      	'ID'           => wp_get_post_parent_id($post_id),
	  	);
	  	wp_update_post( $my_post );
	  	if(!get_post_thumbnail_id($post_id)){
	  		set_post_thumbnail($post_id,get_post_thumbnail_id(wp_get_post_parent_id($post_id)));
	  	}
  	}
    }
}
