<?php
/**
 * Tính năng Folow Truyện
 *
 * 
 */
 /**
 * Thêm action
 */
add_action('wp_ajax_tw_ajax_remove_follow', 'tw_ajax_remove_follow');
add_action('wp_ajax_nopriv_tw_ajax_remove_follow', 'tw_ajax_remove_follow');
add_action('wp_ajax_tw_ajax_add_follow', 'tw_ajax_add_follow');
add_action('wp_ajax_nopriv_tw_ajax_add_follow', 'tw_ajax_add_follow');
function tw_ajax_add_follow() {
    $post_id = $_POST['post_id'];
    $user_id = get_current_user_id();
    $current_history = get_user_meta( $user_id, '_wp_manga_history' );
    if ( empty($current_history) ) {
        $current_history = array($post_id);
    } elseif (is_array( $current_history )) {
        $current_history = $current_history[0];
        $current_history[] = $post_id;
    }
    update_user_meta( $user_id, '_wp_manga_history', $current_history );
}

function tw_ajax_remove_follow () {
    $post_id = $_POST['post_id'];
    $user_id = get_current_user_id();
    $current_history = get_user_meta( $user_id, '_wp_manga_history' );
    if ( !empty($current_history )) {
        $current_history = $current_history[0];
        $index = array_search($post_id, $current_history);
        unset($current_history[$index]);
    }
    update_user_meta( $user_id, '_wp_manga_history', $current_history );
}

function tw_check_follow ($user_id, $post_id) {
    $current_history = get_user_meta( $user_id, '_wp_manga_history' );
    if ( !empty($current_history )) {
        $current_history = $current_history[0];
        $index = array_search($post_id, $current_history);
        if ($index !== false) {
            return true;
        }
    } 
    return false;
}
function sky_save_chapper_setcookie($post_id, $chapper_id) {
    if (isset($_COOKIE['sky_read_chap'])) {
        $read = $_COOKIE['sky_read_chap'];
        $ary_read = unserialize($read);
        unset($ary_read[$post_id]);

        $ary_read[$post_id] = $chapper_id;
        $str_read = serialize($ary_read);
        setcookie('sky_read_chap', $str_read, time() + (86400 * 7), '/');

    } else {
        $star_read = array();
        $star_read[$post_id] = $chapper_id;
        $str_read = serialize($star_read);
        setcookie('sky_read_chap', $str_read, time() + (86400 * 7), '/');
    }
}

function sky_get_chapper_reading($post_id, $user_id = '') {
    if ( ! empty( $_COOKIE['sky_read_chap'] ) ) {
        $sky_read_chap   = unserialize( $_COOKIE[ 'sky_read_chap' ] );
        if ( !empty( $sky_read_chap[$post_id] ) ) {
            return $sky_read_chap[$post_id];
        }
    }
    return null;
}

function getReadingStory(){
    if ( ! empty( $_COOKIE['sky_read_chap'])){
        return unserialize( $_COOKIE[ 'sky_read_chap' ] );
    }
    return null;
}
add_filter( 'allowed_http_origin', 'xxxxxx_send_cors_headers' );

function xxxxxx_send_cors_headers( $origin ) {

        // Access-Control headers are received during OPTIONS requests
        if (  $origin && 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {

                if ( isset( $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] ) ) {
                        @header( 'Access-Control-Allow-Headers: '. $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] );
                }

        }

        return $origin;

}
 /**
 * GỌI HỒN MẤY THẰNG DỊCH COVID 19
 */
/**
 * Không xóa ảnh đại diện khi thu thập lại bài viết.
 * Thực hiện 4 việc sau:
 * - Không xóa ảnh đại diện.
 * - Không xóa tệp đính kèm là ảnh đại diện.
 * - Không chuẩn bị dữ liệu ảnh đại diện.
 * - Giữ lại đính kèm cho ảnh đại diện.
 */

// 1. Không xóa ảnh đại diện khi thu thập lại.
add_filter('kdn/post/allow_delete_thumbnail', 'kdn_post_allow_delete_thumbnail', 10, 11);
function kdn_post_allow_delete_thumbnail($allowRun, $data, $postData, $postBot, $postSaver, $siteId, $postUrl, $urlTuple, $isRecrawl, $postId, $isFirstPage) {
	
	// Nếu đang thu thập lại, không xóa ảnh đại diện.
	if ($isRecrawl) return false;
	
}

// 2. Không xóa tệp đính kèm là ảnh đại diện khi thu thập lại.
add_filter('kdn/post/allow_delete_attached_media', 'kdn_post_allow_delete_attached_media', 10, 13);
function kdn_post_allow_delete_attached_media($allowDeleteAttachedMedia, $mediaPost, $alreadyAttachedMedia, $data, $postData, $postBot, $postSaver, $siteId, $postUrl, $urlTuple, $isRecrawl, $postId, $isFirstPage) {
	
	// Lấy ID của tệp đính kèm.
	$attachedMediaId = $mediaPost->ID;
	
	// Lấy ID của ảnh đại diện hiện tại.
	$postThumbnailId = get_post_thumbnail_id($postId);
	
	// Nếu ID của tệp đính kèm chính là ID của ảnh đại diện hiện tại, không xóa tệp đính kèm.
	if ($attachedMediaId == $postThumbnailId) {
		return false;
		
	// Nếu không, tiếp tục xóa tệp đính kèm.
	} else {
		return true;
	}
	
}

// 3. Không chuẩn bị dữ liệu ảnh đại diện cho bài viết khi thu thập lại.
add_filter('kdn/post/allow_prepare_thumbnail', 'kdn_post_allow_prepare_thumbnail', 10, 5);
function kdn_post_allow_prepare_thumbnail($allowRun, $postData, $postBot, $proxyList, $postMediaPreparer) {
	
	// Nếu đang thu thập lại, không chuẩn bị dữ liệu cho ảnh đại diện.
	if ($postBot->isRecrawl()) {
		return false;
		
	// Nếu không, tiếp tục chuẩn bị dữ liệu cho ảnh đại diện.
	} else {
		return true;
	}
	
}

// 4. Giữ lại đính kèm cho ảnh đại diện của bài viết khi thu thập lại.
add_filter('kdn/post/thumbnail_data_before_set', 'kdn_post_thumbnail_data_before_set', 10, 11);
function kdn_post_thumbnail_data_before_set($mediaFile, $data, $postData, $postBot, $postSaver, $siteId, $postUrl, $urlTuple, $isRecrawl, $postId, $isFirstPage) {
	
	// Nếu đang thu thập lại, giữ lại đính kèm cho ảnh đại diện.
	if ($isRecrawl) {
		return null;
		
	// Nếu không, tiếp tục xử lý.
	} else {
		return $mediaFile;
	}
	
}
// Tạo Label FULL HOT
function tao_taxonomy_full_hot() {
 
        /* Biến $label chứa các tham số thiết lập tên hiển thị của Taxonomy
         */
        $labels = array(
                'name' => 'TIỀN TỐ',
                'singular' => 'TIỀN TỐ',
                'menu_name' => 'TIỀN TỐ'
        );
 
        /* Biến $args khai báo các tham số trong custom taxonomy cần tạo
         */
        $args = array(
                'labels'                     => $labels,
                'hierarchical'               => false,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
        );
 
        /* Hàm register_taxonomy để khởi tạo taxonomy
         */
        register_taxonomy('label-story', 'post', $args);
 
}
 
// Hook into the 'init' action
add_action( 'init', 'tao_taxonomy_full_hot', 0 );
add_action( 'phpmailer_init', function( $phpmailer ) {
    if ( !is_object( $phpmailer ) )
    $phpmailer = (object) $phpmailer;
    $phpmailer->Mailer     = 'smtp';
    $phpmailer->Host       = 'smtp.gmail.com';
    $phpmailer->SMTPAuth   = 1;
    $phpmailer->Port       = 587;
    $phpmailer->Username   = 'ngontinhplus.com@gmail.com';
    $phpmailer->Password   = 'crgugalbcaevybwd';
    $phpmailer->SMTPSecure = 'TLS';
    $phpmailer->From       = 'ngontinhplus.com@gmail.com';
    $phpmailer->FromName   = 'Ngôn Tình Plus';
});
