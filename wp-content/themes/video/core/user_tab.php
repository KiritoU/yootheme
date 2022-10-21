<?php
/* add new tab called "vipuser" */

add_filter('um_account_page_default_tabs_hook', 'vipuser_tab_in_um', 100 );
function vipuser_tab_in_um( $tabs ) {
	$tabs[50]['vipuser']['icon'] = 'um-faicon-heart';
	$tabs[50]['vipuser']['title'] = 'Thông Tin VIP';
	$tabs[50]['vipuser']['custom'] = true;
	return $tabs;
}

/* make our new tab hookable */

add_action('um_account_tab__vipuser', 'um_account_tab__vipuser');
function um_account_tab__vipuser( $info ) {
	global $ultimatemember;
	extract( $info );

	 $output = $ultimatemember->account->get_tab_output('vipuser');
	 //if ( $output ) { echo $output; }
}

/* Finally we add some content in the tab */

add_filter('um_account_content_hook_vipuser', 'um_account_content_hook_vipuser');
function um_account_content_hook_vipuser( $output ){
	ob_start();
	?>
	<div class="um-field">
		<?php $current_user = wp_get_current_user(); $userid= $current_user->ID;
		$ngaysosanh = get_the_author_meta( 'vipend', $userid ); $ngayhomnay = date("d-m-Y");
		$vipdays = (strtotime($ngaysosanh) - strtotime($ngayhomnay)) / (60 * 60 * 24);

 			if(current_user_can('um_vip')) { ?>
			<div>Xin chào <?php echo $current_user->display_name . ""; ?>.</div>
			<?php if($vipdays < '0') : ?>
				<div>Bạn đã hết thời gian VIP, hãy gia hạn để tiếp tục!</div>
				<div class="um-account-heading uimob340-hide uimob500-hide"><i class="um-faicon-heart"></i>Bạn Có Thể:</div>
				<a href='/nang-cap-tai-khoan/' type="button" class="uk-button uk-button-dange">Gia Hạn Tài Khoản</a>

			<?php elseif($vipdays == '0') : ?>
				<div>Hết hôm nay bạn sẽ hết VIP</div>
				<div class="um-account-heading uimob340-hide uimob500-hide"><i class="um-faicon-heart"></i>Bạn Có Thể:</div>
				<a href='/nang-cap-tai-khoan/' type="button" class="uk-button uk-button-dange">Gia Hạn Tài Khoản</a>

			<?php  else : ?>
				<div>Số ngày còn lại của bạn là: <?php echo $vipdays; ?> ngày</div>
				<div class="um-account-heading uimob340-hide uimob500-hide"><i class="um-faicon-heart"></i>Bạn Có Thể:</div>

			<?php  endif; ?>
		<?php } else { ?>
			<div>Bạn chưa phải là thành viên VIP, vui lòng nâng cấp thành viên theo hướng dẫn</div>
			<div class="um-account-heading uimob340-hide uimob500-hide"><i class="um-faicon-heart"></i>Bạn Có Thể:</div>
			<a href='/nang-cap-tai-khoan/' type="button" class="uk-button uk-button-dange">Nâng Cấp Tài Khoản</a>

		<?php } ?>
	</div>
	<?php
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}

// Thời gian hết Vip

function add_fields_user($profile_fields){
$profile_fields['vipend'] = 'Thời gian hết Vip (dd-mm-yyyy)';
return $profile_fields;
}
add_filter('user_contactmethods', 'add_fields_user');
