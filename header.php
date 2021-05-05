<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<meta name="format-detection" content="telephone=no"/>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'templates/header-search' ); ?>

<?php if ( ideapark_mod( 'store_notice' ) == 'top' && function_exists( 'woocommerce_demo_store' ) ) {
	woocommerce_demo_store();
	ideapark_ra( 'wp_footer', 'woocommerce_demo_store' );
} ?>

<?php
/**
 * @var string $header_type
 * @var string $header_type_mobile
 */
extract( ideapark_header_params() );

ob_start();
$mobile_header_buttons_cnt = 0;
if ( ! ideapark_mod( 'search_disabled_mobile' ) ) {
	get_template_part( 'templates/header-search-button' );
	$mobile_header_buttons_cnt ++;
}
if ( ! ideapark_mod( 'auth_disabled_mobile' ) ) {
	get_template_part( 'templates/header-auth' );
	$mobile_header_buttons_cnt ++;
}
if ( ! ideapark_mod( 'wishlist_disabled_mobile' ) ) {
	get_template_part( 'templates/header-wishlist' );
	$mobile_header_buttons_cnt ++;
}
if ( ! ideapark_mod( 'cart_disabled_mobile' ) ) {
	ideapark_get_template_part( 'templates/header-cart', [ 'device' => 'mobile' ] );
	$mobile_header_buttons_cnt ++;
}
$mobile_header_buttons = trim( ob_get_clean() );
$top_row = '';
if ( in_array( $header_type, [
	'header-type-2',
	'header-type-3',
	'header-type-4',
	'header-type-5'
] ) ) {
	ob_start();
	get_template_part( 'templates/header-top-row' );
	$top_row = ob_get_clean();
}
?>
<div class="l-wrap">
	<header class="l-section" id="main-header">
		<div
			class="c-header__outer c-header__outer--mobile c-header__outer--<?php echo esc_attr( $header_type ); ?> c-header__outer--<?php echo esc_attr( $header_type_mobile ); ?>">
			<div
				class="c-header <?php if ( ideapark_mod( 'sticky_menu_mobile' ) ) { ?>c-header--sticky-support<?php } ?> c-header--<?php echo esc_attr( $header_type ); ?> c-header--<?php echo esc_attr( $header_type_mobile ); ?> c-header--buttons-<?php echo esc_attr( $mobile_header_buttons_cnt ); ?> c-header--mobile js-header-mobile">
				<div class="c-header__row">
					<?php if ( $header_type_mobile == 'header-type-mobile-1' ) { ?>
						<?php get_template_part( 'templates/header-logo-mobile' ); ?>
						<?php get_template_part( 'templates/header-mobile-menu-button' ); ?>
					<?php } else { ?>
						<?php get_template_part( 'templates/header-mobile-menu-button' ); ?>
						<?php get_template_part( 'templates/header-logo-mobile' ); ?>
						<?php echo ideapark_wrap( $mobile_header_buttons, '<div class="c-header__col-mobile-buttons c-header__col-mobile-buttons--' . esc_attr( $mobile_header_buttons_cnt ) . '">', '</div>' ) ?>
					<?php } ?>
				</div>
			</div>
		</div>

		<div
			class="c-header__menu c-header--mobile c-header__menu--<?php echo esc_attr( $header_type_mobile ); ?> js-mobile-menu">
			<div class="c-header__menu-shadow"></div>
			<div class="c-header__menu-buttons">
				<button type="button" class="h-cb h-cb--svg c-header__menu-back js-mobile-menu-back"><i
						class="ip-menu-left c-header__menu-back-svg"></i><?php esc_html_e( 'Back', 'luchiana' ) ?>
				</button>
				<button type="button" class="h-cb h-cb--svg c-header__menu-close js-mobile-menu-close"><i
						class="ip-close-small c-header__menu-close-svg"></i></button>
			</div>
			<div class="c-header__menu-content">
				<div class="c-header__menu-wrap js-mobile-menu-wrap"></div>
				<?php get_template_part( 'templates/header-mobile-top-menu' ); ?>
				<?php get_template_part( 'templates/header-mobile-blocks' ); ?>
			</div>
			<div class="c-header__menu-bottom">
				<?php get_template_part( 'templates/header-search-button' ); ?>
				<?php get_template_part( 'templates/header-auth' ); ?>
				<?php get_template_part( 'templates/header-wishlist' ); ?>
				<?php ideapark_get_template_part( 'templates/header-cart', [ 'device' => 'mobile' ] ); ?>
			</div>
		</div>
		<?php
		global $ideapark_advert_bar;
		echo ideapark_wrap( $ideapark_advert_bar, '<div class="c-header__advert_bar c-header--mobile">', '</div>' );
		?>
		<div
			class="c-header__outer c-header__outer--desktop c-header__outer--<?php echo esc_attr( $header_type ); ?><?php ideapark_class( $top_row, 'c-header__outer--top-row' ); ?>">
			<div
				class="c-header c-header--desktop js-header-desktop c-header--<?php echo esc_attr( $header_type ); ?> <?php ideapark_class( ideapark_is_elementor() && \Elementor\Plugin::$instance->preview->is_preview_mode(), 'c-header--preview', '' ); ?>">
				<?php if ( $header_type == 'header-type-1' ) { ?>
					<?php get_template_part( 'templates/header-1-row-1' ); ?>
				<?php } elseif ( $header_type == 'header-type-2' ) { ?>
					<div class="l-section__container l-section__container--relative">
						<?php echo ideapark_wrap( $top_row ); ?>
						<?php get_template_part( 'templates/header-2-row-2' ); ?>
						<?php get_template_part( 'templates/header-2-row-3' ); ?>
					</div>
				<?php } elseif ( $header_type == 'header-type-3' || $header_type == 'header-type-4' ) { ?>
					<?php echo ideapark_wrap( $top_row ); ?>
					<?php get_template_part( 'templates/header-1-row-1' ); ?>
				<?php } elseif ( $header_type == 'header-type-5' ) { ?>
					<?php echo ideapark_wrap( $top_row ); ?>
					<?php get_template_part( 'templates/header-5-row-1' ); ?>
				<?php } ?>
			</div>
		</div>

		<?php
		global $ideapark_advert_bar;
		echo ideapark_wrap( $ideapark_advert_bar, '<div class="c-header__advert_bar c-header--desktop">', '</div>' );
		?>

		<div class="c-header--desktop l-section__container js-simple-container"></div>

		<?php if ( ideapark_mod( 'shop_modal' ) ) { ?>
			<div
				class="c-header__callback-popup c-header__callback-popup--disabled js-callback-popup js-quickview-popup">
				<div class="c-header__callback-bg js-callback-close"></div>
				<div class="c-header__callback-wrap">
					<div class="js-quickview-container"></div>
					<button type="button" class="h-cb h-cb--svg c-header__callback-close js-callback-close"
							id="ideapark-callback-close"><i class="ip-close"></i></button>
				</div>
			</div>
		<?php } ?>
	</header>

	<div class="l-inner">
