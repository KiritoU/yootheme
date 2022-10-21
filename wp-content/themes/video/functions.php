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



function chap()
{
	ob_start();
	
	echo $trailer;
	$ID_parent = get_the_ID();
	$trailer = get_field('trailer');
    $args2 = array(
        'post_type'      => 'chap',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_parent'    => $ID_parent,
        'order'          => 'ASC',
        'orderby'		 => 'ID'
      );
	$chap_first = new WP_Query( $args2 );
	// echo '<pre>';
	// print_r($chap_first);
	// echo '</pre>';
	// echo 'xin chao';
	$chap = '';
	$chap_lastes = '';
	
	if($chap_first->have_posts( ))
	{
		$chap .='<ul class="uk-list list-film">';
		$key = 0;
		while($chap_first->have_posts( ))
		{
			$chap_first->the_post();
			$chap .= '<li><a href="'.get_the_permalink().'">'.get_the_title( ).'</a></li>';
			if($key == 0)
			{	
				$chap_lastes = '<a href="'.get_the_permalink().'"> '.get_the_title().'</a>';
			}
			$key++;
		}
		$chap .='</ul>';
		wp_reset_postdata();
	}
	?>
	<div class="box-film">
	<div uk-grid class="uk-grid-medium">
	  	<div class="uk-width-1-3@s">
	  		<?php 
				echo get_the_post_thumbnail(NULL,'full',['class'=>'uk-width-1-1']);
			 ?>
			
		</div>
		<div class="uk-width-2-3@s">
			<ul class="uk-list">
				<li class="lastest">Latest Episode: <span><?= $chap_lastes ?></span></li>
				<li class="book"><span uk-icon="bookmarks"></span> Click to manage book marks</li>
				<li class="content_short">
					<span><?= get_the_title() ?>: </span><?=  get_the_content() ?>
				</li>
				<li >
					<button class="uk-button uk-button-text" uk-toggle="target: #my-video" type="button" ><span uk-icon="video"></span> Trailer</button>
					<div id="my-video" class="uk-flex-top" uk-modal>
    					<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
					 <iframe id="iframe-trailer"width="1920" height="1080" uk-video uk-responsive src="<?= $trailer ?>?autoplay=1&amp;showinfo=0&amp;rel=0&amp;modestbranding=1&amp;playsinline=1"></iframe>
					</div>
				</div>
				</li>
			</ul>
	  		
		</div>
	</div>
	<?php 
		$country = get_the_terms($ID_parent,'country');
		$release = get_the_terms($ID_parent,'release');
	?>
	<div uk-grid class="uk-width-auto uk-margin-small-top">
		<?php if($country != ''): ?>
	  			<div class="contry ">Country: <a class="uk-text-primary" href="<?= get_term_link($country[0]->term_id) ?>"><?= $country[0]->name ?></a></div>
		<?php endif; ?>
		<?php if($release != ''): ?>
				<div class="release ">Release: <a class="uk-text-primary" href="<?= get_term_link($release[0]->term_id) ?>"><?= $release[0]->name?></a></div>
		<?php endif; ?>
			 </div>
	</div>
	<div class="uk-text-primary uk-margin"> MORE EPISODE</div>
	<?php
	echo $chap;
	wp_reset_postdata( );
	return ob_get_clean();
}
add_shortcode('chap','chap');
// function add_news_to_category( $query ) {
// 	$object = get_queried_object();
// 	// var_dump($object);
//     if ( ! is_admin() && $query->is_category() && $query->is_main_query() && $object->name =='TV Show') {
//          $query->set( 'posts_per_page', '100' );
//     }
// }
// add_action( 'pre_get_posts', 'add_news_to_category' );
function bo_loc()
{
	ob_start();
	$coutry
	?>
	<h5 class="uk-text-primary uk-margin-remove">WATCHTV SHOW LIST</h5>
	<p class="uk-text-meta uk-margin-remove">Use Search Page or Use the List Below</p>
	<div>
<?php

      echo "<ul class='uk-list list'>";
	  echo '<li class="hide_m" style="">Select :</li>';
      foreach (range('A', 'Z') as $column){
         echo "<li><a href='/tv-shows?key={$column}' rel='{$column}'>{$column}</a></li>";
      }   
      echo "</ul>";
   
?>

	</div>
	<?php
		$boloc = get_field('bo_loc','option');
		$countrys = explode(",",$boloc['country']);
		$releaseds = explode("-",$boloc['released']);
		$genres = explode(",",$boloc['genre']);
		$qualitys = explode(",",$boloc['quality']);
		$film_types = explode(",",$boloc['film_type']);
		// var_dump($countrys);

	?>
	<form class="" action="https://m4uhd.top/tv-shows" method="get">
	<div class="uk-child-width-1-3@s uk-child-width-1-2 uk-grid-medium" uk-grid>
	  	<div class="uk-flex uk-flex-middle">
		  <span class="title_sort">Country:</span>
			<select class="uk-select" name="country">
	  			<?php 
				
					echo '<option value="">All</option>';
					foreach($countrys as $country)
					{
						if(isset($_GET['country']) && $_GET['country'] == $country)
						{
							echo '<option value="'.$country.'" selected>'.$country.'</option>';
						}
						else
						{
							echo '<option value="'.$country.'">'.$country.'</option>';
						}
							
	
						// echo '<option value="'.$country.'">'.$country.'</option>';
					}
				?>
			</select>
		</div>
		<div class="uk-flex uk-flex-middle">
		<span class="title_sort">Released:</span>
			<select class="uk-select"  name="released">
	  			<?php 
					echo '<option value="">All</option>';
					for($i = $releaseds[1];$i>=$releaseds[0];$i--)
					{
						if(isset($_GET['released']) && $_GET['released'] == $i)
						{
							echo '<option selected value="'.$i.'">'.$i.'</option>';
						}
						else
						{
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						
					}
				?>
			</select>
		</div>
		<div class="uk-flex uk-flex-middle">
		<span class="title_sort">Genre:</span>
			<select class="uk-select" name="genre">
	  			<?php 
					echo '<option value="">All</option>';
					foreach($genres  as $genre )
					{
						
						if(isset($_GET['genre']) && $_GET['genre'] == $genre)
						{
							echo '<option selected value="'.$genre.'">'.$genre.'</option>';
						}
						else
						{
							echo '<option value="'.$genre.'">'.$genre.'</option>';
						}
						
					}
				?>
			</select>
		</div>
		<div class="uk-flex uk-flex-middle">
		<span class="title_sort">Quality:</span>
			<select class="uk-select" name="quality">
	  			<?php 
					echo '<option value="">All</option>';
					foreach($qualitys  as $quality )
					{
						if(isset($_GET['quality']) && $_GET['quality'] == $quality)
						{
							echo '<option selected value="'.$quality.'">'.$quality.'</option>';
						}
						else
						{
							echo '<option value="'.$quality.'">'.$quality.'</option>';
						}
						
					}
				?>
			</select>
		</div>
		<div class="uk-flex uk-flex-middle">
			<span class="title_sort">Film Type:</span>
			<select class="uk-select" name="film_type">
	  			<?php 
					echo '<option value="">All</option>';
					foreach($film_types  as $film_type )
					{
						if(isset($_GET['film_type']) && $_GET['film_type'] == $film_type)
						{
							echo '<option selected value="'.$film_type.'">'.$film_type.'</option>';
						}
						else
						{
							echo '<option value="'.$film_type.'">'.$film_type.'</option>';
						}
						
					}
				?>
			</select>
		</div>
		<div class="uk-text-right">
			<button class="uk-button uk-button-primary uk-width-1-1 button-filter">Filter</button>
		</div>
	</div>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode('bo_loc','bo_loc');
add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

        // Register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Bộ Lọc'),
            'menu_title'    => __('Bộ Lọc'),
            'menu_slug'     => 'bo-loc',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
}
function footer_view()
{ 
	ob_start();
	global $post;
	// echo '<pre>';
	// print_r($parent);
	// echo '</pre>';
	$id =  $post->ID;
	$meta = get_post_meta($id);
	// echo '<pre>';
	// print_r($meta);
	// echo '</pre>';
	$link = get_field('field_601d685ea50eb');
	$trailer = get_field('trailer');
	?>
	<div class="box-infor">
		<div class="uk-child-width-auto list-icon uk-grid-medium " uk-grid >
			<div class="detail" href="#toggle-animation" uk-toggle="target: #toggle-animation; animation: uk-animation-fade"><span uk-icon="icon:info;ratio:0.8"></span> Detail</div>
			<div>
				<button class="uk-button  uk-button-link" uk-toggle="target: #my-video" type="button" ><span uk-icon="video"></span> Trailer</button>
				<div id="my-video" class="uk-flex-top" uk-modal>
							<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
						<iframe id="iframe-trailer"width="1920" height="1080" uk-video uk-responsive src="<?= $trailer ?>?autoplay=0&amp;showinfo=0&amp;rel=0&amp;modestbranding=1&amp;playsinline=1"></iframe>
					</div>
				</div>
			</div>
			<div><a href="https://twitter.com/intent/tweet?text=<?= get_the_permalink() ?>"><span uk-icon="icon:twitter;ratio:0.8"></span> Twitter</a></div>
			<div><a href="https://www.facebook.com/sharer/sharer.php?u=<?= get_the_permalink() ?>" ><span uk-icon="icon:facebook;ratio:0.8"></span> Facebook</a></div>
			<div><a href="https://www.instagram.com/?url=<?= get_the_permalink() ?>" target="_blank" rel="noopener"><span uk-icon="icon:instagram;ratio:0.8"></span> Instagram</a></div>
			<div><a class="" href="#modal-center" uk-toggle>Report</a>
			<div id="modal-center" class="uk-flex-top" uk-modal>
				<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

					<button class="uk-modal-close-default" type="button" uk-close></button>
					<form id="report" action="">
					<div uk-dark class="report">

						<h6>Report</h6>
						<div class="uk-grid-small uk-child-width-1-1 uk-grid-collapse"  uk-grid>
							<div>
								
									<h6>Movie</h6>
									<div>
										<label><input type="checkbox" class="uk-checkbox" name="movie"> Broken </label>
										<label><input type="checkbox" class="uk-checkbox" name="movie"> Wrong movie </label>
										<label><input type="checkbox" class="uk-checkbox" name="movie"> Others</label>
									</div>
								
							</div>
							<div>
								
								<h6>Audio</h6>
								<div>
									<label><input type="checkbox" class="uk-checkbox" name="audio"> Not Synced  </label>
									<label><input type="checkbox" class="uk-checkbox" name="audio"> There's no Audio </label>
									<label><input type="checkbox" class="uk-checkbox" name="audio"> Others</label>
								</div>
								
							</div>
							<div>
								
								<h6>Subtitle</h6>
								<div>
									<label><input type="checkbox" class="uk-checkbox" name="subtitle"> Not Synced  </label>
									<label><input type="checkbox" class="uk-checkbox" name="subtitle"> Wrong subtitle </label>
									<label><input type="checkbox" class="uk-checkbox" name="subtitle"> Missing subtitle</label>
								</div>
							</div>
							<div class="not-box">
								<textarea name="des" rows="3" cols="10"  class="uk-textarea"  placeholder="Describe the issue here (Optional)"></textarea>
							</div>
							<div class="uk-text-right not-box">
								<button class="uk-button uk-button-primary uk-button-small submit" type="submit">Report</button><button class="reset uk-button uk-button-primary uk-button-small uk-margin-left" type="reset">Reset</button>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
			<div id="success_form" class="uk-flex-top uk-dark" uk-modal >
				<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
					<h3 class="uk-modal-title">Notification</h3>
					<div class="uk-padding-small">
					<p>Thanks you for choosing us. Your submission has been accepted. We will respond to you shortly via your email!</p>
					<p class="uk-text-right">
						<button class="uk-button uk-button-primary uk-modal-close" type="button">OK</button>
					</p>
					</div>
				</div>
			</div>
			</div>
			<?php 
			$list_gen = get_the_terms($post->ID,'genres');
			$country = get_the_terms($post->ID,'country');
			$release = get_the_terms($post->ID,'release'); 
			$list = '';
			// var_dump($list_gen);
			foreach ( $list_gen as $value)
			{
				$list .='<a href="'.get_site_url().'/genres/'.$value->slug.'">'.$value->name.'</a>, ';
			}
			// $list_gen = explode(',',get_field('genre'));
			// $list = '';
			// foreach($list_gen as $value)
			// {

			// 	$list .='<a href="https://m4uhd.top/genres/'.trim($value).'">'.$value.'</a>, ';
			// }
			// $country = str_replace(' ','-',get_field('country'));

			?>
			<div id="toggle-animation" hidden>
				<ul class="uk-list">
					<li><?= get_the_title() ?></li>
					<li>Genre: <span><?= $list  ?></span></li>
					<?php if( $country[0]->name ==''): ?>
					<li>Country: <span><a href="https://m4uhd.top/country/<?= $country[0]->slug ?>"><?= $country[0]->name ?></a></span></li>
					<?php endif; ?>
					<?php if($release[0]->name == ''): ?>
					<li>Release: <span><a href="https://m4uhd.top/release/<?= $release[0]->slug ?>"><?= $release[0]->name ?></a></span></li>
					<?php endif ?>
					<li><?= get_the_title() ?>: <?= get_the_excerpt() ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="uk-margin uk-child-width-1-1 uk-grid-divider uk-grid-small list-server" uk-grid >
		<div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-width-expand"><span class="icon-1"></span> SERVER HD </div><button onclick="change_video(this)" class="uk-button uk-button-primary uk-active" data-video="<?= $link ?>">CHOOSE THIS SERVER</button>
			</div>
		</div>
		<?php if( $meta['video_link_1']['0']): ?>
		<div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-width-expand"><span class="icon-2"></span> MIRROR SERVER 1 </div><button onclick="change_video(this)" class="uk-button uk-button-primary" data-video="<?= $meta['video_link_1']['0'] ?>">CHOOSE THIS SERVER</button>
			</div>
		</div>
		<?php endif ?>
		<?php if( $meta['video_link_2']['0']): ?>
		<div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-width-expand"><span class="icon-2"></span> MIRROR SERVER 2 </div><button onclick="change_video(this)" class="uk-button uk-button-primary " data-video="<?= $meta['video_link_2']['0'] ?>">CHOOSE THIS SERVER</button>
			</div>
		</div>
		<?php endif ?>
		<?php if( $meta['video_link_3']['0']): ?>
		<div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-width-expand"><span class="icon-2"></span> MIRROR SERVER 3 </div><button onclick="change_video(this)" class="uk-button uk-button-primary " data-video="<?= $meta['video_link_3']['0'] ?>">CHOOSE THIS SERVER</button>
			</div>
		</div>
		<?php endif ?>
		<?php if( $meta['video_link_4']['0']): ?>
		<div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-width-expand"><span class="icon-2"></span> MIRROR SERVER 4  </div><button onclick="change_video(this)" class="uk-button uk-button-primary " data-video="<?= $meta['video_link_4']['0'] ?>">CHOOSE THIS SERVER</button>
			</div>
		</div>
		<?php endif ?>
		<?php if( $meta['video_link_5']['0']): ?>
		<div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-width-expand"><span class="icon-2"></span>MIRROR SERVER 5  </div><button onclick="change_video(this)" class="uk-button uk-button-primary " data-video="<?= $meta['video_link_5']['0'] ?>">CHOOSE THIS SERVER</button>
			</div>
		</div>
		<?php endif ?>
	</div>
	<?php
	
	// echo '<pre>';
	// print_r($meta);
	// echo '</pre>';
	
	$args2 = array(
        'post_type'      => 'chap',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_parent'    => $post->post_parent,
        'order'          => 'ASC',
        'orderby'		 => 'ID',
		'post__not_in' 	=> array($id),
      );
	$chap ='';
	$chap_first = new WP_Query( $args2);
	echo '<h5 class="uk-text-uppercase uk-text-primary">'.get_the_title($post->post_parent).'</h5>';
	if($chap_first->have_posts( ))
	{
		$chap .='<ul class="uk-list list-film list-post">';
		$key = 0;
		while($chap_first->have_posts( ))
		{
			$chap_first->the_post();
			$chap .= '<li ><a href="'.get_the_permalink().'" class="uk-flex uk-flex-middle"><div class="uk-width-expand">'.get_the_title( ).'</div><div class="uk-width-auto">'.get_the_date().'</div></a></li>';
			
		}
		$chap .='</ul>';
		wp_reset_postdata();
	}
	echo $chap;
	?>
	<script id="ajax-js-extra">
	var ajax_object = {"ajaxurl":"<?=  admin_url( 'admin-ajax.php' ) ?>"};
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode('footer_view','footer_view');

function search_filter($query) {
	if ( !is_admin() && $query->is_main_query() ) {
	  if ($query->is_search) {
			$query->set('paged', ( get_query_var('paged') ) ? get_query_var('paged') : 1 );
			$query->set('posts_per_page',60);
	  }
	  	$object = get_queried_object();
		if($object->name =='TV Show')
		{
			$query->set( 'posts_per_page', 60);
			
			// if( $_GET['country'] != '' || $_GET['released'] != '' || $_GET['genre'] != '' || $_GET['chat_luong_video'] != '' || $_GET['film_type'] != '')
			// {
			// 	// var_dump($_GET);
			// 	$query->set( 'meta_query', [
			// 		'relation' => 'AND',
			// 		[
			// 		 'key'     => 'country',
			// 		 'value'   => $_GET['country'],
			// 		 'compare' 	=> 'like',
			// 		],
			// 		[
			// 			'key'     => 'released',
			// 			'value'   => $_GET['released'],
			// 			'compare' 	=> 'like',
			// 		],
			// 		[
			// 			'key'     => 'genre',
			// 			'value'   => $_GET['genre'],
			// 			'compare' 	=> 'like',
			// 		],
	
			// 		[
			// 			'key'     => 'chat_luong_video',
			// 			'value'   => $_GET['quality'],
			// 			'compare' 	=> 'like',
			// 		],
			// 		[
			// 			'key'     => 'film_type',
			// 			'value'   => $_GET['film_type'],
			// 			'compare' 	=> 'like',
			// 		],
			// 	  ]);
			// 	// $query->set('meta_key', 'country');
			// 	// $query->set('meta_value', $_GET['country']);
			// }
		}
	}
  }
  add_action( 'pre_get_posts', 'search_filter' );
  // Change posts per page in the design category
// add_action( 'pre_get_posts', 'mp_design_cat_posts_per_page' );
// function mp_design_cat_posts_per_page( $query ) {
// 	if( $query->is_main_query() && is_category( 'tv-show' ) && ! is_admin() ) {
// 		$query->set( 'posts_per_page', '10');
// 		if(isset($_GET['country']))
// 		{
// 			echo $_GET['country'];
// 			// $query->set('meta_key', 'country');
// 			// $query->set('meta_value', $_GET['country']);
// 			// $query->set();
// 		}

// 	}
// }
function title_parent()
{
	global $post;
	ob_start();
	// var_dump($post->post_parent);
	$id = $post->post_parent;
	echo '<h1 class="uk-h6 uk-text-bold uk-text-primary uk-text-uppercase uk-margin-remove"><a href="'.get_the_permalink($id).'">'.get_the_title().'</a></h1>';
	
	return ob_get_clean();

}
add_shortcode('title_parent','title_parent' );
add_action( 'wp_ajax_send_data', 'send_data' );
add_action( 'wp_ajax_nopriv_send_data', 'send_data' );
function send_data() {
	
	$date = date("Y-m-d h:i:sa");
	$movie = (isset($_POST['movie']))?esc_attr($_POST['movie']) : '';
	$subtitle = (isset($_POST['subtitle']))?esc_attr($_POST['subtitle']) : '';
	$audio = (isset($_POST['audio']))?esc_attr($_POST['audio']) : '';
	$des = (isset($_POST['des']))?esc_attr($_POST['des']) : '';
	$request =  ob_get_clean();
	$to = 'brokensocial@protonmail.com';
	$subject = 'Báo cáo lỗi';
	ob_start();
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>m4uhd.top</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body style="margin: 0; padding: 0;">
    <table border="1" cellpadding="0" cellspacing="0" width="100%">
		<tbody >
            <tr><td>Date</td><td>Movie</td><td>Audio</td><td>Subtitle</td><td>Des</td></tr>
			<tr><td><?= $date ?></td><td><?= $movie ?></td><td><?= $audio ?></td><td><?= $subtitle ?></td><td><?= $des ?></td></tr>
		</tbody>
	</table>
	</body>
	</html>
	<?php
	$bodymail = ob_get_clean();
	$send = wp_mail( $to, $subject,$bodymail);
	wp_send_json_success('Thành Công');
	die();
}
add_filter('wp_mail_content_type', function( $content_type ) {
    return 'text/html';
});
