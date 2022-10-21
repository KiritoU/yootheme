<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 */

?>

<article id="post-<?php the_ID() ?>" <?php post_class('uk-article') ?>>

  <div id="page-de-cu" class="uk-cover-container uk-inline uk-inline-clip uk-transition-toggle">
    <div class="uk-transition-scale-up uk-transition-opaque">
		<img data-src="<?php echo tw_get_thumbnail(get_the_ID()) ?>" alt="<?php the_title()?>" class="slider-image"  width="220" height="320" uk-img>
		<span class="tien-to"><?php get_tien_to(get_the_ID()); get_trangthai(get_the_ID()); ?></span>
		<div class="uk-position-bottom uk-overlay uk-overlay-primary uk-text-center">
			<h3 class="de-cu-title"><?php the_title()?><div class="chap-title"><?php last_update(false)?></div></h3>
		</div>
	  </div>
  <a class="uk-position-cover" href="<?php the_permalink()?>"></a>
  </div>

</article>
