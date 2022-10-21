<?php

class BT_BXH extends WP_Widget {

    function __construct() {
        parent::__construct(
            'bt_bxh',
            'BT Bảng xếp hạng',
            array( 'description'  =>  'Widget hiển thị bảng xếp hạng.' )
        );
    }

    function form( $instance ) {
 	$options = get_option('my_option_name');
        $default = array(
            'title'       => '',
            'post_number' => 10,

        );
        $instance = wp_parse_args( (array) $instance, $default );
        $title = esc_attr($instance['title']);
        $post_number = esc_attr($instance['post_number']);


        echo '<p>Tiêu đề <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
        echo '<p>Số lượng <input type="number" class="widefat" name="'.$this->get_field_name('post_number').'" value="'.$post_number.'" placeholder="'.$post_number.'" max="30" /></p>';

        if($title) $title = ': '.$title;
        echo '<script> $(".widget-title h2").last().html("BT Bảng xếp hạng'.$title.'"); </script>';

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['post_number'] = strip_tags($new_instance['post_number']);

        return $instance;
    }

    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters( 'widget_title', $instance['title'] ).$title;
        $post_number = $instance['post_number'];

        echo $before_widget;?>
        <div class="uk-tile uk-tile-xsmall index-sidebar uk-margin-small-bottom" style="padding-left: 15px !important; padding-right: 10px !important;">

          <h2 class="home-title uk-heading-divider"><img src="/wp-content/themes/pntoday/image/icon-hot.png" width="30" style="vertical-align: text-bottom;"/> Top Views </h2>


            <?php  $i = 1;
              $args = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 10,
                'post__not_in'   => array($ID_parent),
                'orderby'        => 'meta_value_num',
                'meta_key'       => 'tw_views_post',
                'ignore_sticky_posts' => -1,
                'order'          => 'DESC'
              );

        $query = new WP_Query($args);
        while($query->have_posts()): $query->the_post();?>
          <div class="inrow_bxh uk-grid-small uk-position-relative" uk-grid>
            <div class="uk-width-1-3">
              <div class="uk-position-absolute top-<?php echo $i;?> top-views"><?php echo $i;?></div>
              <img data-src="<?php echo tw_get_thumbnail($recent["ID"]) ?>" alt="<?php echo $recent["post_title"] ?>" class="slider-image"  width="220" height="320" uk-img>
            </div>
            <div class="s-title uk-width-2-3">
              <h3 itemprop="name" style="margin:0 !important;">
                <a href="<?php echo get_the_permalink(); ?>" itemprop="url" title="<?php the_title(); ?>"><?php the_title();?></a>
              </h3>
              <span style="opacity:0.8">
                <?php echo tw_get_views(get_the_ID()); ?> lượt xem<br/>
                <?php echo last_update(); ?>
              </span>
            </div>
        </div><hr>
         <?php $i++; endwhile;
          wp_reset_query();
          echo '</div>';
        echo $after_widget;
    }

}

add_action( 'widgets_init', 'create_bt_bxh_widget' );
function create_bt_bxh_widget() {
    register_widget('BT_BXH');
}
wp_reset_query(); ?>
