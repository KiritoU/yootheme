<?php function user_info(){
  ob_start();?>
  <?php global $current_user; wp_get_current_user();
  echo '<div class="right-menu">';
       if (is_user_logged_in()) {
         $rank = UM()->roles()->get_role_name( um_user("role") ); ?>
    <div class="uk-text-center user_info">Xin chào  <br><span><?php echo $current_user->display_name . "  "; echo '</span><br>Cấp bậc: <span class="rank">'. $rank . '</span></div>';
  }
   //menu
    wp_nav_menu( array(
        'theme_location' => 'my-custom-menu',
        'container_class' => 'custom-menu-class' ) );
    //menu
    echo do_shortcode('[danh_muc_2]'); ?>
</div>
<?php $result = ob_get_contents();
ob_end_clean();
return $result;
}
add_shortcode('user_info', 'user_info'); ?>
