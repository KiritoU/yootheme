<?php function danh_muc_2(){
  ob_start();
  $terms = get_terms( array(
                'taxonomy' => 'category',
                'hide_empty' => false,
                'order'    => 'ASC',
                'orderby' => 'id',
) ); ?>
<ul uk-accordion>
  <li>
      <a class="uk-accordion-title" href="#">Thể Loại</a>
      <div class="uk-accordion-content">
        <ul class="uk-nav uk-nav-default uk-nav-parent-icon uk-nav-accordion danh-muc" uk-nav>
          <?php foreach ($terms as $term) { ?>
            <li class="cat-item">
                <a class="menu-item" href="/<?php echo $term->slug; ?>"><span uk-icon="icon: triangle-right; ratio: 1.2"></span> <?php echo $term->name; ?></a>
            </li>
          <?php } ?>
        </ul>
      </div>
  </li>
</ul>
<?php $result = ob_get_contents();
ob_end_clean();
return $result;
}
add_shortcode('danh_muc_2', 'danh_muc_2'); ?>
