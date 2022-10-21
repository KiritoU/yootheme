<?php
// TW_GET_chap_OPTION
// function tw_get_chap_option($id, $chap){
//
//     $args = array(
//             'post_type'      => 'chap',
//             'post_status'    => 'publish',
//             'posts_per_page' => -1,
//             'post_parent'    => $id,
//             'order'          => 'ASC'
//         );
//     $wp_query = new wp_query($args);
//     echo '<select id="chap_jump" class="btn btn-success form-control" onchange="window.location.href=this.value">';
//     while($wp_query->have_posts()){
//         $wp_query->the_post();
//         $title = explode(':', mb_substr(get_the_title() ,0, 23, 'utf-8'));
//         echo '<option value="'.get_the_permalink().'"';
//         if($chap == get_the_ID()) echo 'selected';
//         echo '>'.$title[0].'</option>';
//     }
//     echo '</select>';
// }





// TW_GET_NEXT_chap
function tw_get_next_chap($id){

    global $wpdb;
    $current_post_id = get_the_ID();
    $query = $wpdb->get_results("select * from  ".$wpdb->posts." where ID > '$current_post_id' AND post_type = 'chap' and post_parent = '$id' and post_status = 'publish' ORDER BY ID ASC LIMIT 1");
    if($query){
        foreach($query as $chap) {
            echo '<a class="uk-button uk-button-primary" id="next_chap" href="'.get_the_permalink($chap->ID).'"><span class="hidden-xs">Next</span> <span uk-icon="icon:  chevron-right; ratio: 1.3"></span></a>';
        }
    }
    else
        echo '<a class="uk-button uk-button-primary disabled" href="javascript:void(0)" title="Not or no next chap" id="next_chap"><span class="hidden-xs">Next</span> <span uk-icon="icon:chevron-right; ratio: 1.3"></span> </a>';
}





// TW_GET_PREV_chap
function tw_get_prev_chap($id){

    global $wpdb;
    $current_post_id = get_the_ID();
    $query = $wpdb->get_results("select * from  ".$wpdb->posts." where ID < '$current_post_id' AND post_type = 'chap' and post_parent = '$id' and post_status = 'publish' ORDER BY ID DESC LIMIT 1");
    if($query){
        foreach($query as $chap) {
            echo '<a class="uk-button uk-button-primary" id="prev_chap" href="'.get_the_permalink($chap->ID).'"><span uk-icon="icon:  chevron-left; ratio: 1.3"></span> <span class="hidden-xs">Prev</span> </a>';
        }
    }
    else
        echo '<a class="uk-button uk-button-primary disabled" href="javascript:void(0)" title="There is no previous chap" id="prev_chap"><span uk-icon="icon: chevron-left; ratio: 1.3"></span> <span class="hidden-xs">Prev</span></a>';
}
