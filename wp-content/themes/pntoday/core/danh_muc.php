<?php function danh_muc(){
  ob_start();
  $terms = get_terms( array(
                'taxonomy' => 'category',
                'hide_empty' => false,
                'order'    => 'ASC',
                'orderby' => 'id',
) ); ?>
<h3> <span uk-icon="icon: menu; ratio: 1.2"></span> Thể Loại </h3>
<div class="uk-width-1-1">
    <ul class="uk-nav uk-list uk-list-divider uk-nav-default uk-nav-parent-icon uk-nav-accordion danh-muc" uk-nav>
      <?php foreach ($terms as $term) { ?>
        <li class="cat-item">
            <a class="menu-item" href="<?php echo $term->slug; ?>"><?php echo $term->name; ?></a>
        </li>
      <?php } ?>
    </ul>
</div>


<?php $result = ob_get_contents();
ob_end_clean();
return $result;
}
add_shortcode('danh_muc', 'danh_muc'); ?>
