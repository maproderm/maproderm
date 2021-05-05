<?php

/*------------------------------------*\
	Constants & Globals
\*------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$theme_obj = wp_get_theme( 'luchiana' );

define( 'IDEAPARK_IS_AJAX', function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) );
define( 'IDEAPARK_IS_AJAX_HEARTBEAT', IDEAPARK_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'heartbeat' ) );
define( 'IDEAPARK_IS_AJAX_SEARCH', IDEAPARK_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'ideapark_ajax_search' ) );
define( 'IDEAPARK_IS_AJAX_CSS', IDEAPARK_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'ideapark_ajax_custom_css' ) );
define( 'IDEAPARK_IS_AJAX_WISHLIST', IDEAPARK_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'ideapark_wishlist_toggle' ) );
define( 'IDEAPARK_IS_AJAX_QUICKVIEW', ! empty( $_REQUEST['wc-ajax'] ) && ( $_REQUEST['wc-ajax'] == 'ideapark_ajax_product' ) );
define( 'IDEAPARK_IS_AJAX_IMAGES', IDEAPARK_IS_AJAX && ! empty( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'ideapark_product_images' ) );
define( 'IDEAPARK_IS_AJAX_TAB', IDEAPARK_IS_AJAX && ! empty( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'ideapark_product_tab' ) );
define( 'IDEAPARK_DEMO', false );
define( 'IDEAPARK_NAME', $theme_obj['Name'] );
define( 'IDEAPARK_DIR', get_template_directory() );
define( 'IDEAPARK_URI', get_template_directory_uri() );
define( 'IDEAPARK_MANUAL', 'https://parkofideas.com/luchiana/manual/' );
define( 'IDEAPARK_VERSION', '2.4' );

$wp_upload_arr = wp_get_upload_dir();

define( "IDEAPARK_UPLOAD_DIR", $wp_upload_arr['basedir'] . "/" . strtolower( sanitize_file_name( IDEAPARK_NAME ) ) . "/" );
define( "IDEAPARK_UPLOAD_URL", $wp_upload_arr['baseurl'] . "/" . strtolower( sanitize_file_name( IDEAPARK_NAME ) ) . "/" );

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

$ideapark_theme_scripts = [];
$ideapark_theme_styles  = [];
$ideapark_is_front_page = false;
$ideapark_advert_bar    = '';

if ( ! function_exists( 'ideapark_is_requset' ) ) {
	function ideapark_is_requset( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) || ( is_admin() && ! empty( $_GET['action'] ) && ( $_GET['action'] == 'elementor' ) ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
		}
	}
}

if ( ! function_exists( 'ideapark_setup' ) ) {

	function ideapark_setup() {

		if ( ! ideapark_is_dir( IDEAPARK_UPLOAD_DIR ) ) {
			ideapark_mkdir( IDEAPARK_UPLOAD_DIR );
		}

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'custom-background' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'post-formats', [ 'standard', 'gallery', 'video' ] );

		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );

		add_theme_support( 'woocommerce', [
			'thumbnail_image_width'         => 260,
			'gallery_thumbnail_image_width' => 115,
			'single_image_width'            => 460,
			'product_grid'                  => [
				'default_rows'    => 5,
				'min_rows'        => 1,
				'max_rows'        => 100,
				'default_columns' => 4,
				'min_columns'     => 3,
				'max_columns'     => 4,
			],
		] );
		add_image_size( 'ideapark-thumbnail-image-width-2x', 520, 460, true );
		add_image_size( 'ideapark-medium-image-width-2x', 920 );

		add_image_size( 'ideapark-product-thumbnail-compact', 0, 145, true );
		add_image_size( 'ideapark-product-thumbnail-compact-2x', 0, 290, true );

		load_theme_textdomain( 'luchiana', IDEAPARK_DIR . '/languages' );

		add_action( 'load_textdomain_mofile', 'ideapark_correct_tgmpa_mofile', 10, 2 );
		load_theme_textdomain( 'tgmpa', IDEAPARK_DIR . '/plugins/languages' );
		remove_action( 'load_textdomain_mofile', 'ideapark_correct_tgmpa_mofile', 10 );

		register_nav_menus( [
			'primary' => esc_html__( 'Primary (Desktop)', 'luchiana' ),
			'top_bar' => esc_html__( 'Top Bar Menu (Desktop)', 'luchiana' ),
			'mobile'  => esc_html__( 'Primary (Mobile)', 'luchiana' ),
		] );
	}
}

if ( ! function_exists( 'ideapark_check_version' ) ) {
	function ideapark_check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && ideapark_is_requset( 'admin' ) && ( ( $current_version = get_option( 'ideapark_luchiana_theme_version', '' ) ) || ! $current_version ) && ( version_compare( $current_version, IDEAPARK_VERSION, '!=' ) ) ) {
			do_action( 'after_update_theme', $current_version, IDEAPARK_VERSION );
			add_action( 'init', function () use ( $current_version ) {
				do_action( 'after_update_theme_late', $current_version, IDEAPARK_VERSION );
			}, 999 );
			update_option( 'ideapark_luchiana_theme_version', IDEAPARK_VERSION );
			$theme = wp_get_theme();
			if ( $theme->parent() ) {
				$theme = $theme->parent();
			}
			update_option( str_replace( '-child', '', $theme->get_stylesheet() ) . '_about_page', 1 );
		}
	}
}

if ( ! function_exists( 'ideapark_set_image_dimensions' ) ) {
	function ideapark_set_image_dimensions() {

		update_option( 'woocommerce_thumbnail_cropping', 'custom' );
		update_option( 'woocommerce_thumbnail_cropping_custom_width', '26' );
		update_option( 'woocommerce_thumbnail_cropping_custom_height', '23' );

		update_option( 'thumbnail_size_w', 115 );
		update_option( 'thumbnail_size_h', 115 );

		update_option( 'medium_size_w', 460 );
		update_option( 'medium_size_h', '' );

		update_option( 'medium_large_size_w', 760 );
		update_option( 'medium_large_size_h', '' );

		update_option( 'large_size_w', '1520' );
		update_option( 'large_size_h', '' );

	}

	add_action( 'after_switch_theme', 'ideapark_set_image_dimensions', 1 );
	add_action( 'after_update_theme', 'ideapark_set_image_dimensions', 1 );
}

// Maximum width for media
if ( ! isset( $content_width ) ) {
	$content_width = 1160; // Pixels
}

require_once( IDEAPARK_DIR . '/includes/customize/ip_customize_settings.php' );
require_once( IDEAPARK_DIR . '/includes/customize/ip_customize_style.php' );
require_once( IDEAPARK_DIR . '/includes/megamenu/mega_menu.php' );

if ( is_admin() && ! IDEAPARK_IS_AJAX_SEARCH && ! IDEAPARK_IS_AJAX_CSS ) {
	require_once IDEAPARK_DIR . '/plugins/class-tgm-plugin-activation.php';
	add_action( 'tgmpa_register', 'ideapark_register_required_plugins' );
}

if ( is_admin() ) {
	require_once IDEAPARK_DIR . '/includes/theme-about/theme-about.php';
	require_once IDEAPARK_DIR . '/includes/lib/theme-fix.php';
}

if ( ! function_exists( 'ideapark_woocommerce_on' ) ) {
	function ideapark_woocommerce_on() {
		return class_exists( 'WooCommerce' );
	}
}

if ( ideapark_woocommerce_on() && ! IDEAPARK_IS_AJAX_HEARTBEAT ) {
	require_once( IDEAPARK_DIR . '/includes/woocommerce/woocommerce.php' );

	if ( ideapark_is_requset( 'frontend' ) || ideapark_is_requset( 'ajax' ) ) {
		require_once( IDEAPARK_DIR . '/includes/woocommerce/woocommerce-wishlist.php' );
	}
}

function IDEAPARK_plugin_on() {
	return defined( 'IDEAPARK_LUCHIANA_FUNC_VERSION' );
}

if ( ! function_exists( 'ideapark_get_required_plugins' ) ) {
	function ideapark_get_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		return [
			[
				'name'     => esc_html__( 'Luchiana Theme Functionality', 'luchiana' ),
				'slug'     => 'ideapark-luchiana',
				'source'   => IDEAPARK_DIR . '/plugins/ideapark-luchiana.zip',
				'required' => true,
				'version'  => '2.3',
			],

			[
				'name'     => esc_html__( 'Font Icons Loader', 'luchiana' ),
				'slug'     => 'ideapark-fonts',
				'source'   => IDEAPARK_DIR . '/plugins/ideapark-fonts.zip',
				'required' => true,
				'version'  => '1.4',
			],

			[
				'name'     => esc_html__( 'WooCommerce', 'luchiana' ),
				'slug'     => 'woocommerce',
				'required' => true
			],

			[
				'name'     => esc_html__( 'Meta Box', 'luchiana' ),
				'slug'     => 'meta-box',
				'required' => true,
			],

			[
				'name'     => esc_html__( 'Elementor', 'luchiana' ),
				'slug'     => 'elementor',
				'required' => true
			],

			[
				'name'     => esc_html__( 'Contact Form 7', 'luchiana' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			],

			[
				'name'     => esc_html__( 'Envato Market', 'luchiana' ),
				'slug'     => 'envato-market',
				'source'   => IDEAPARK_DIR . '/plugins/envato-market.zip',
				'required' => false,
				'version'  => '2.0.6',
			],

			[
				'name'           => esc_html__( 'Regenerate Thumbnails', 'luchiana' ),
				'slug'           => 'regenerate-thumbnails',
				'required'       => false,
				'notice_disable' => true,
			],

			[
				'name'           => esc_html__( 'Revolution Slider', 'luchiana' ),
				'slug'           => 'revslider',
				'source'         => IDEAPARK_DIR . '/plugins/revslider.zip',
				'version'        => '6.4.6',
				'required'       => false,
				'notice_disable' => true,
			],

		];
	}
}

if ( ! function_exists( 'ideapark_register_required_plugins' ) ) {
	function ideapark_register_required_plugins() {
		$plugins = ideapark_get_required_plugins();

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = [
			'id'           => 'luchiana',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',
			// Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins',
			// Menu slug.
			'parent_slug'  => 'themes.php',
			// Parent menu slug.
			'capability'   => 'edit_theme_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}
}

if ( ! function_exists( 'ideapark_scripts_disable_cf7' ) ) {
	function ideapark_scripts_disable_cf7() {
		if ( ! is_singular() || is_front_page() ) {
			add_filter( 'wpcf7_load_js', '__return_false' );
			add_filter( 'wpcf7_load_css', '__return_false' );
		}
	}
}

if ( ! function_exists( 'ideapark_scripts' ) ) {
	function ideapark_scripts() {

		if ( $GLOBALS['pagenow'] != 'wp-login.php' && ! is_admin() ) {

			if ( ideapark_woocommerce_on() ) {
				if ( ideapark_mod( 'disable_wc_block_styles' ) ) {
					wp_dequeue_style( 'wc-block-style' );
				}
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				wp_dequeue_script( 'prettyPhoto' );
				wp_dequeue_script( 'prettyPhoto-init' );
			}

			ideapark_add_style( 'ideapark-entry-content', IDEAPARK_URI . '/assets/css/entry-content.css', [], ideapark_mtime( IDEAPARK_DIR . '/assets/css/entry-content.css' ), 'all' );
			ideapark_add_style( 'ideapark-core', IDEAPARK_URI . '/style.css', [], ideapark_mtime( IDEAPARK_DIR . '/style.css' ), 'all' );

			if ( ideapark_is_rtl() ) {
				ideapark_add_style( 'ideapark-rtl', IDEAPARK_URI . '/assets/css/rtl.css', [], ideapark_mtime( IDEAPARK_DIR . '/assets/css/rtl.css' ), 'all' );
			}

			if ( is_customize_preview() ) {
				wp_enqueue_style( 'ideapark-customize-preview', IDEAPARK_URI . '/assets/css/admin/admin-customizer-preview.css', [], ideapark_mtime( IDEAPARK_DIR . '/assets/css/admin/admin-customizer-preview.css' ), 'all' );
			}

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply', false, [], false, true );
			}

			ideapark_add_script( 'zoom', IDEAPARK_URI . '/assets/js/jquery.zoom.min.js', [ 'jquery' ], '1.7.21', true );
			ideapark_add_script( 'owl-carousel', IDEAPARK_URI . '/assets/js/owl.carousel.min.js', [ 'jquery' ], '2.3.4', true );
			ideapark_add_script( 'fitvids', IDEAPARK_URI . '/assets/js/jquery.fitvids.min.js', [ 'jquery' ], '1.1', true );
			ideapark_add_script( 'jquery-customselect', IDEAPARK_URI . '/assets/js/jquery.customSelect.min.js', [ 'jquery' ], '0.5.1', true );
			ideapark_add_script( 'bodyscrolllock', IDEAPARK_URI . '/assets/js/bodyScrollLock.min.js', [ 'jquery' ], '1.0', true );
			ideapark_add_script( 'requirejs', IDEAPARK_URI . '/assets/js/requirejs/require.js', [], '2.3.6', true );

			if ( ideapark_woocommerce_on() && ideapark_mod( 'wishlist_page' ) ) {
				ideapark_add_script( 'ideapark-wishlist', IDEAPARK_URI . '/assets/js/wishlist.js', [ 'jquery' ], IDEAPARK_VERSION, true );
			}

			ideapark_add_script( 'ideapark-lib', IDEAPARK_URI . '/assets/js/site-lib.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_DIR . '/assets/js/site-lib.js' ), true );
			ideapark_add_script( 'ideapark-core', IDEAPARK_URI . '/assets/js/site.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_DIR . '/assets/js/site.js' ), true );

		}

	}
}

if ( ! function_exists( 'ideapark_scripts_load' ) ) {
	function ideapark_scripts_load() {
		ideapark_enqueue_style();
		ideapark_enqueue_script();
		if ( ideapark_mod( 'load_jquery_in_footer' ) ) {
			wp_scripts()->add_data( 'jquery', 'group', 1 );
			wp_scripts()->add_data( 'jquery-core', 'group', 1 );
			wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
		}

		wp_localize_script( 'ideapark-core', 'ideapark_wp_vars', ideapark_localize_vars() );

		global $wp_scripts;
		if ( ideapark_woocommerce_on() ) {
			foreach ( $wp_scripts->registered as $handler => $script ) {
				if ( $handler == 'wc-add-to-cart-variation' ) {
					wp_enqueue_script( 'wc-add-to-cart-variation-fix', IDEAPARK_URI . '/assets/js/add-to-cart-variation-fix.js', [
						'jquery',
						'wp-util',
						'wc-add-to-cart-variation'
					], IDEAPARK_VERSION, true );
					break;
				}
			}
		}

		/**
		 * @var string $header_menu_color
		 * @var string $header_text_color
		 */
		extract( ideapark_header_params() );

		$inline_css = '';
		if ( $header_menu_color || $header_text_color ) {
			$inline_css .= ( $header_menu_color ? '
				.c-header--header-type-1 .c-header__col-left, 
				.c-header--header-type-3 .c-header__col-left, 
				.c-header--header-type-1 .c-header__col-right, 
				.c-header--header-type-3 .c-header__col-right,
				.c-header--header-type-1 .c-header__col-center,
				.c-header--header-type-3 .c-header__col-center {
					color: ' . esc_attr( $header_menu_color ) . ' !important;
				}
				' : '' ) .
			               ( $header_text_color ? '
				.c-page-header {
					color: ' . esc_attr( $header_text_color ) . ' !important;
				}
				' : '' );
		}

		if ( function_exists( 'WP' ) && ! ideapark_mod( 'disable_wc_block_styles' ) ) {
			$font_path = str_replace( [
					'http:',
					'https:'
				], '', WC()->plugin_url() ) . '/assets/fonts/';

			$inline_css .= "
@font-face {
font-family: 'star';
src: url('{$font_path}star.eot');
src: url('{$font_path}star.eot?#iefix') format('embedded-opentype'),
	url('{$font_path}star.woff') format('woff'),
	url('{$font_path}star.ttf') format('truetype'),
	url('{$font_path}star.svg#star') format('svg');
font-weight: normal;
font-style: normal;
}";
		}
		if ( $inline_css ) {
			wp_add_inline_style( 'ideapark-core', $inline_css );
		}
	}
}

if ( ! function_exists( 'ideapark_widgets_init' ) ) {
	function ideapark_widgets_init() {

		register_sidebar( [
			'name'          => esc_html__( 'Post List Sidebar', 'luchiana' ),
			'id'            => 'post-sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>',
		] );

		if ( ideapark_woocommerce_on() ) {
			register_sidebar( [
				'name'          => esc_html__( 'Product list (Desktop)', 'luchiana' ),
				'id'            => 'shop-sidebar',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<div class="widget-title">',
				'after_title'   => '</div>',
			] );
			register_sidebar( [
				'name'          => esc_html__( 'Product list (Mobile)', 'luchiana' ),
				'id'            => 'filter-sidebar',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			] );
		}
	}
}

if ( ! function_exists( 'ideapark_add_style' ) ) {
	function ideapark_add_style( $handle, $src = '', $deps = [], $ver = false, $media = 'all', $path = '' ) {
		global $ideapark_theme_styles;
		if ( ! array_key_exists( $handle, $ideapark_theme_styles ) ) {
			$ideapark_theme_styles[ $handle ] = [
				'handle' => $handle,
				'src'    => $src,
				'deps'   => $deps,
				'ver'    => $ver,
				'media'  => $media,
				'path'   => $path,
			];
		}
	}
}

if ( ! function_exists( 'ideapark_enqueue_style_hash' ) ) {
	function ideapark_enqueue_style_hash( $styles ) {
		$hash = IDEAPARK_VERSION . '_' . (string) ideapark_mtime( IDEAPARK_DIR . '/includes/customize/ip_customize_style.php' ) . '_' . ( IDEAPARK_DEMO ? 'on' : 'off' );

		if ( ! empty( $styles ) ) {
			foreach ( $styles as $item ) {
				if ( is_array( $item ) ) {
					$hash .= $item['ver'] . '_';
				} else {
					$hash .= (string) ideapark_mtime( IDEAPARK_DIR . $item ) . '_';
				}
			}
		}

		return $hash ? md5( $hash ) : '';
	}
}

if ( ! function_exists( 'ideapark_editor_style' ) ) {
	function ideapark_editor_style() {

		$screen  = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		$allowed = [ 'page', 'post', 'html_block', 'customize' ];
		if ( is_object( $screen ) && ! empty( $screen->id ) && in_array( $screen->id, $allowed ) ) {
			$styles = [
				'/assets/css/entry-content.css',
			];

			if ( $hash = ideapark_enqueue_style_hash( $styles ) ) {
				if ( ! ideapark_is_dir( IDEAPARK_UPLOAD_DIR ) ) {
					ideapark_mkdir( IDEAPARK_UPLOAD_DIR );
				}
				if ( get_option( $option_name = 'ideapark_editor_styles_hash' ) != $hash || ! ideapark_is_file( IDEAPARK_UPLOAD_DIR . 'editor-styles.min.css' ) ) {
					require_once( IDEAPARK_DIR . '/includes/lib/cssmin.php' );
					$fonts = [
						ideapark_mod( 'theme_font_text' ),
						ideapark_mod( 'theme_font_header' )
					];

					$google_font_uri = ideapark_get_google_font_uri( $fonts );
					$code            = "@" . "import url('" . esc_url( $google_font_uri ) . "');";
					foreach ( $styles as $style ) {
						$code .= ideapark_fgc( IDEAPARK_DIR . $style );
					}
					$code .= ideapark_customize_css( true );

					$code = preg_replace( '~\.entry-content[\t\r\n\s]*\{~', 'body {', $code );
					$code = preg_replace( '~\.entry-content[\t\r\n\s]*~', '', $code );
					$code = preg_replace( '~(?<![a-z0-9_-])(button|input\[type=submit\])~i', '\\0:not(.components-button):not([role=presentation]):not(.mce-open)', $code );

					$code .= '
						.editor-post-title {
							padding-left: 0;
							padding-right: 0;
						}
						.editor-post-title__input {
							font-size: 54;
							line-height: 1;
							font-family: "' . esc_attr( ideapark_mod( 'theme_font_text' ) ) . '", sans-serif;
							max-width: 730;
							margin-left:  auto;
							margin-right: auto;
							font-style: normal;
							font-weight: 300;
							text-align: center;
							letter-spacing: 0.2em;
						}
						.editor-post-title__block {
							display:flex;
							align-items: center;
						}
						.editor-post-title__block > div {
							flex: 1 1 100%
						}
						
						.wp-block {
							max-width: 700px
						}
						';

					if ( ! ideapark_mod( 'sidebar_post' ) ) {
						$code .= '
						*.alignfull, .wp-block[data-align="full"] {
							margin-left:  0 !important;
							margin-right: 0 !important;
							width:        100% !important;
							max-width:    100% !important;
							padding-left: 0;
							padding-right: 0;
						}
						 *.alignwide, .wp-block[data-align="wide"] {
							margin-left:  auto !important;
							margin-right: auto !important;
							width:        100% !important;
							max-width:    914px !important;
						}
						';
					} else {
						$code .= '
						 *.alignfull, *.alignwide, .wp-block[data-align="wide"], .wp-block[data-align="full"] {
							width:        auto !important;
							margin-left:  auto !important;
							margin-right: auto !important;
						}
						';
					}

					$code = CSSMin::compressCSS( $code );

					ideapark_fpc( IDEAPARK_UPLOAD_DIR . 'editor-styles.min.css', $code );
					if ( get_option( $option_name ) !== null ) {
						update_option( $option_name, $hash );
					} else {
						add_option( $option_name, $hash );
					}
				}
			}

			add_editor_style( IDEAPARK_UPLOAD_URL . 'editor-styles.min.css' );
		}
	}
}

if ( ! function_exists( 'ideapark_enqueue_style' ) ) {
	function ideapark_enqueue_style() {
		global $ideapark_theme_styles;

		if ( ideapark_mod( 'use_minified_css' ) && ! is_customize_preview() ) {

			$lang_postfix = ideapark_get_lang_postfix();

			if ( $hash = ideapark_enqueue_style_hash( $ideapark_theme_styles ) . $lang_postfix ) {
				if ( ! ideapark_is_dir( IDEAPARK_UPLOAD_DIR ) ) {
					ideapark_mkdir( IDEAPARK_UPLOAD_DIR );
				}
				$css_path = IDEAPARK_UPLOAD_DIR . 'min' . $lang_postfix . '.css';
				$css_url  = IDEAPARK_UPLOAD_URL . 'min' . $lang_postfix . '.css';
				if ( get_option( $option_name = 'ideapark_styles_hash' . $lang_postfix ) != $hash || ! ideapark_is_file( $css_path ) ) {
					require_once( IDEAPARK_DIR . '/includes/lib/cssmin.php' );
					$code = "";
					foreach ( $ideapark_theme_styles as $style ) {
						$path = $style['path'] ? $style['path'] : ( IDEAPARK_DIR . preg_replace( '~^' . preg_quote( IDEAPARK_URI, '~' ) . '~', '', $style['src'] ) );
						$css  = ideapark_fgc( $path );
						$css  = preg_replace( '~url\("\./~', 'url("' . IDEAPARK_URI . dirname( preg_replace( '~^' . preg_quote( IDEAPARK_URI, '~' ) . '~', '', $style['src'] ) ) . '/', $css );
						$css  = preg_replace( '~\.\./fonts/~', IDEAPARK_URI . '/fonts/', $css );
						$css  = preg_replace( '~url\((assets/[^\)]+)\)~', 'url(' . IDEAPARK_URI . '/\\1)', $css );

						if ( ! ideapark_is_rtl() ) {
							$css = preg_replace( '~\.h-rtl[^,\}\{]+,~', '', $css );
							$css = preg_replace( '~\.h-rtl[^,\}\{]*\{[^\}]*\}~', '', $css );
						}
						$code .= $css;
					}
					$code .= ideapark_customize_css( true );
					$code = CSSMin::compressCSS( $code );
					ideapark_fpc( $css_path, $code );
					if ( get_option( $option_name ) !== null ) {
						update_option( $option_name, $hash );
					} else {
						add_option( $option_name, $hash );
					}
				}
			}
			wp_enqueue_style( 'ideapark-core', $css_url, [], ideapark_mtime( $css_path ), 'all' );
		} else {
			foreach ( $ideapark_theme_styles as $style ) {
				wp_enqueue_style( $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
			}
			ideapark_customize_css();
		}
	}
}

if ( ! function_exists( 'ideapark_add_script' ) ) {
	function ideapark_add_script( $handle, $src = '', $deps = [], $ver = false, $in_footer = false, $path = '' ) {
		global $ideapark_theme_scripts;
		if ( ! array_key_exists( $handle, $ideapark_theme_scripts ) ) {
			$ideapark_theme_scripts[ $handle ] = [
				'handle'    => $handle,
				'src'       => $src,
				'deps'      => $deps,
				'ver'       => $ver,
				'in_footer' => $in_footer,
				'path'      => $path,
			];
		}
	}
}

if ( ! function_exists( 'ideapark_enqueue_script' ) ) {
	function ideapark_enqueue_script() {
		global $ideapark_theme_scripts;

		$hash = '';

		if ( ideapark_mod( 'use_minified_js' ) ) {
			$deps = [];
			foreach ( $ideapark_theme_scripts as $script ) {
				$hash .= $script['ver'] . '_';
				$deps = array_merge( $deps, $script['deps'] );
			}
			$deps = array_unique( $deps );
			if ( $hash ) {
				$hash = md5( $hash );
				if ( get_option( $option_name = 'ideapark_scripts_hash' ) != $hash || ! ideapark_is_file( IDEAPARK_UPLOAD_DIR . 'min.js' ) ) {
					require_once( IDEAPARK_DIR . '/includes/lib/jsmin.php' );
					$code = "";
					foreach ( $ideapark_theme_scripts as $script ) {
						$path        = $script['path'] ? $script['path'] : ( IDEAPARK_DIR . preg_replace( '~^' . preg_quote( IDEAPARK_URI, '~' ) . '~', '', $script['src'] ) );
						$script_code = ideapark_fgc( $path );
						$code        .= strpos( $path, '.min' ) !== false ? $script_code : JSMin::minify( $script_code );
					}
					ideapark_fpc( IDEAPARK_UPLOAD_DIR . 'min.js', $code );
					if ( get_option( $option_name ) !== null ) {
						update_option( $option_name, $hash );
					} else {
						add_option( $option_name, $hash );
					}
				}

				wp_enqueue_script( 'ideapark-core', IDEAPARK_UPLOAD_URL . 'min.js', $deps, ideapark_mtime( IDEAPARK_UPLOAD_DIR . 'min.js' ), true );
			}
		}

		if ( ! $hash ) {
			foreach ( $ideapark_theme_scripts as $script ) {
				wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer'] );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_custom_excerpt_length' ) ) {
	function ideapark_custom_excerpt_length( $length ) {
		$length = ideapark_mod( 'post_layout' ) == 'list' ? 22 : 18;

		return $length;
	}
}

if ( ! function_exists( 'ideapark_excerpt_more' ) ) {
	function ideapark_excerpt_more( $more ) {
		return '&hellip;';
	}
}

if ( ! function_exists( 'ideapark_ajax_search' ) ) {
	function ideapark_ajax_search() {
		global $post, $product;
		if ( strlen( ( $s = trim( preg_replace( '~\s\s+~', ' ', $_POST['s'] ) ) ) ) > 0 ) {

			$query_args = [
				's'              => $s,
				'posts_per_page' => ideapark_mod( 'ajax_search_limit' ) ? ideapark_mod( 'ajax_search_limit' ) : 1,
				'post_status'    => 'publish'
			];
			$query      = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_type = get_post_type();
					if ( $post_type == 'product' ) {
						$product = wc_get_product( get_the_ID() );
					}
					?>
					<a class="c-header-search__link" href="<?php the_permalink(); ?>">
						<div class="c-header-search__row">
							<div class="c-header-search__thumb">
								<?php the_post_thumbnail( 'thumbnail' ); ?>
							</div>
							<div class="c-header-search__col">
								<div class="c-header-search__title">
									<?php the_title() ?>
								</div>
								<?php if ( ideapark_mod( 'product_short_description' ) && ( $short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ) ) { ?>
									<div class="c-header-search__short-desc">
										<?php echo ideapark_wrap( $short_description ); ?>
									</div>
								<?php } ?>
								<?php if ( $post_type == 'product' ) { ?>
									<?php echo ideapark_wrap( $product->get_price_html(), '<div class="c-header-search__price">', '</div>' ); ?>
								<?php } ?>
							</div>
						</div>
					</a>
				<?php } ?>
				<div class="c-header-search__view-all">
					<button class="c-button c-button--outline js-ajax-search-all"
							type="button"><?php echo esc_html__( 'View all results', 'luchiana' ); ?></button>
				</div>
			<?php } else { ?>
				<div
					class="c-header-search__no-results"><?php echo esc_html__( 'No results found', 'luchiana' ); ?></div>
			<?php }

		}
		die();
	}
}

if ( ! function_exists( 'ideapark_category' ) ) {
	function ideapark_category( $separator, $cat = null, $a_calss = '' ) {
		$catetories = [];

		if ( ! $cat ) {
			$cat = get_the_category();
		}
		foreach ( $cat as $category ) {
			$catetories[] = '<a ' . ( $a_calss ? 'class="' . esc_attr( $a_calss ) . '"' : '' ) . ' href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( esc_attr__( "View all posts in %s", 'luchiana' ), $category->name ) . '" ' . '>' . esc_html( $category->name ) . '</a>';
		}

		if ( $catetories ) {
			echo implode( $separator, $catetories );
		}
	}
}

if ( ! function_exists( 'ideapark_pagination_prev' ) ) {
	function ideapark_pagination_prev() {
		return '<i class="ip-menu-left page-numbers__prev-ico"></i>';
	}
}

if ( ! function_exists( 'ideapark_pagination_next' ) ) {
	function ideapark_pagination_next() {
		return '<i class="ip-menu-right page-numbers__prev-ico"></i>';
	}
}

if ( ! function_exists( 'ideapark_corenavi' ) ) {
	function ideapark_corenavi( $custom_query = null ) {
		global $wp_query;

		if ( ! $custom_query ) {
			$custom_query = $wp_query;
		}

		if ( $custom_query->max_num_pages < 2 ) {
			return;
		}

		if ( ! $current = get_query_var( 'paged' ) ) {
			$current = 1;
		}

		$a = [ // WPCS: XSS ok.
			'base'      => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
			'add_args'  => false,
			'current'   => $current,
			'total'     => $custom_query->max_num_pages,
			'prev_text' => ideapark_pagination_prev(),
			'next_text' => ideapark_pagination_next(),
			'type'      => 'list',
			'end_size'  => 1,
			'mid_size'  => 1,
		];

		$pages = paginate_links( $a );

		echo ideapark_wrap( $pages, '<nav class="page-numbers__wrap">', '</nav>' );
	}
}

if ( ! function_exists( 'ideapark_default_menu' ) ) {
	function ideapark_default_menu() {
		$menu = '';
		$menu .= '<ul class="menu">';

		if ( is_home() ) {
			$menu .= '<li class="current_page_item menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
		} else {
			$menu .= '<li class="menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
		}

		$menu .= '</ul>';

		return $menu;
	}
}

if ( ! function_exists( 'ideapark_post_nav' ) ) {
	function ideapark_post_nav() {
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="c-post__nav" role="navigation">
			<?php if ( is_attachment() && $previous ) { ?>

				<a class="c-post__nav-prev" href="<?php echo esc_url( get_permalink( $previous->ID ) ) ?>" rel="prev">
					<i class="c-post__nav-prev-ico ip-right"></i>
					<span class="c-post__nav-title">
							<?php echo apply_filters( 'the_title', $previous->post_title, $previous->ID ); ?>
						</span>
				</a>

			<?php } else { ?>
				<?php if ( $previous ) { ?>

					<a class="c-post__nav-prev" href="<?php echo esc_url( get_permalink( $previous->ID ) ) ?>"
					   rel="prev">
						<i class="c-post__nav-prev-ico ip-right"></i>
						<span class="c-post__nav-title">
								<?php echo apply_filters( 'the_title', $previous->post_title, $previous->ID ); ?>
							</span>
					</a>

				<?php } else { ?>
					<div class="c-post__nav-prev"></div>
				<?php } ?>

				<?php if ( $next ) { ?>

					<a class="c-post__nav-next" href="<?php echo esc_url( get_permalink( $next->ID ) ) ?>" rel="next">
							<span class="c-post__nav-title">
								<?php echo apply_filters( 'the_title', $next->post_title, $next->ID ); ?>
							</span>
						<i class="c-post__nav-next-ico ip-right"></i>
					</a>

				<?php } ?>

			<?php } ?>
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'ideapark_html5_comment' ) ) {
	function ideapark_html5_comment( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<?php $ping_track_back = $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback'; ?>
		<article id="div-comment-<?php comment_ID(); ?>"
				 class="comment-body <?php ideapark_class( $ping_track_back, 'no-avatar' ) ?>">
			<header class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] && ! $ping_track_back ) {
						echo '<div class="author-img">' . get_avatar( $comment, $args['avatar_size'] ) . '</div>';
					} ?>
					<?php printf( '<strong class="author-name">%s</strong>', get_comment_author_link() ); ?>
				</div>

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'luchiana' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>
				</div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'luchiana' ); ?></p>
				<?php endif; ?>
			</header>

			<div class="entry-content comment-content">
				<?php comment_text(); ?>
			</div>

			<div class="buttons">
				<?php comment_reply_link( array_merge( $args, [
					'reply_text' => '<i class="ip-reply reply-button"></i>' . esc_html__( 'Reply', 'luchiana' ),
					'add_below'  => 'div-comment',
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				] ) ); ?>

				<?php edit_comment_link( '<i class="ip-edit reply-button"></i>' . esc_html__( 'Edit', 'luchiana' ) ); ?>
			</div>

		</article><!-- .comment-body -->
		<?php
	}
}

if ( ! function_exists( 'ideapark_style' ) ) {
	function ideapark_style( $str ) {
		if ( is_array( $str ) ) {
			$str = implode( ';', $str );
		}
		if ( trim( $str ) != '' ) {
			echo sprintf( '%s%s%s', ' style' . '="', $str, '" ' );
		} else {
			return;
		}
	}
}

if ( ! function_exists( 'ideapark_body_class' ) ) {
	function ideapark_body_class( $classes ) {
		$classes[] = ideapark_woocommerce_on() ? 'woocommerce-on' : 'woocommerce-off';
		$classes[] = ideapark_is_rtl() ? 'h-rtl' : 'h-ltr';
		if (ideapark_mod( 'wpml_style' )) {
			$classes[] = 'h-wpml';
		}

		return $classes;
	}
}

if ( ! function_exists( 'ideapark_empty_menu' ) ) {
	function ideapark_empty_menu() {
	}
}

if ( ! function_exists( 'ideapark_search_form' ) ) {
	function ideapark_search_form( $form ) {

		$form = '<form role="search" method="get" class="js-search-form-entry" action="' . esc_url( home_url( '/' ) ) . '">
				<div class="c-search-form__wrap">
				<label class="c-search-form__label">
					<span class="screen-reader-text">' . esc_html_x( 'Search for:', 'label', 'luchiana' ) . '</span>
					<input class="c-form__input c-form__input--full" type="search" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'luchiana' ) . '" value="' . get_search_query() . '" name="s" />' .
		        '</label>
				<button type="submit" class="c-button c-search-form__button"><i class="ip-search c-search-form__svg"></i></button>
				</div>
			</form>';

		return ideapark_wrap( $form, '<div class="c-search-form">', '</div>' );
	}
}

if ( ! function_exists( 'ideapark_search_form_ajax' ) ) {
	function ideapark_search_form_ajax( $form ) {
		$form = '
<form role="search" class="js-search-form" method="get" action="' . esc_url( home_url( '/' ) ) . '">
	<div class="c-header-search__input-block">
		<input class="js-ajax-search-input h-cb c-header-search__input' . ( ! ideapark_mod( 'ajax_search' ) ? ' no-ajax' : '' ) . '" autocomplete="off" type="text" name="s" placeholder="' . esc_attr__( 'Start typing...', 'luchiana' ) . '" value="' . esc_attr( ideapark_mod( 'ajax_search' ) ? '' : get_search_query() ) . '" />
		<button class="js-search-clear h-cb c-header-search__clear' . ( ! ideapark_mod( 'ajax_search' ) ? ' no-ajax' : '' ) . '" type="button"><i class="ip-close-small c-header-search__clear-svg"></i><span class="c-header-search__clear-text">' . esc_html__( 'Clear', 'luchiana' ) . '</span></button>
		' . ( ! ideapark_mod( 'ajax_search' ) ? '<button type="submit" class="c-header-search__submit h-cb h-cb--svg"><i class="ip-search"></i></button>' : '' ) . '
	</div>
</form>';

		return $form;
	}
}

if ( ! function_exists( 'ideapark_search_form_header' ) ) {
	function ideapark_search_form_header( $form ) {
		$form = '
<form role="search" class="js-search-form" method="get" action="' . esc_url( home_url( '/' ) ) . '">
	<div class="c-header__search-form">
		<input class="js-ajax-search-input h-cb c-header__search-input' . ( ! ideapark_mod( 'ajax_search' ) ? ' no-ajax' : '' ) . '" autocomplete="off" type="text" name="s" placeholder="' . esc_attr__( 'Start typing...', 'luchiana' ) . '" value="' . esc_attr( ideapark_mod( 'ajax_search' ) ? '' : get_search_query() ) . '" />
		<button class="js-search-clear h-cb c-header__search-clear' . ( ! ideapark_mod( 'ajax_search' ) ? ' no-ajax' : '' ) . '" type="button"><i class="ip-close-small c-header__search-clear-svg"></i><span class="c-header__search-clear-text">' . esc_html__( 'Clear', 'luchiana' ) . '</span></button>
		<button type="submit" class="c-header__search-submit h-cb h-cb--svg"><i class="ip-z-search-bold"></i></button>
	</div>
</form>';

		return $form;
	}
}

if ( ! function_exists( 'ideapark_get_account_link' ) ) {
	function ideapark_get_account_link( $prefix = '' ) {
		$link_title = '<i class="ip-user"><!-- --></i>';

		return ideapark_wrap( $link_title, '<a class="c-header__button-link c-header__button-link--account" title="' . esc_attr( is_user_logged_in() ? esc_html__( 'Account', 'luchiana' ) : esc_html__( 'Login', 'luchiana' ) ) . '" href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" rel="nofollow">', '</a>' );
	}
}

if ( ! function_exists( 'ideapark_localize_vars' ) ) {
	function ideapark_localize_vars() {
		global $wp_scripts;

		$js_url_imagesloaded = '';
		$js_url_masonry      = '';
		foreach ( $wp_scripts->registered as $handler => $script ) {
			if ( $handler == 'imagesloaded' ) {
				$js_url_imagesloaded = $wp_scripts->base_url . $script->src . ( ! empty( $script->ver ) ? '?v=' . $script->ver : '' );
			}
			if ( $handler == 'masonry' ) {
				$js_url_masonry = $wp_scripts->base_url . $script->src . ( ! empty( $script->ver ) ? '?v=' . $script->ver : '' );
			}
		}

		$return = [
			'themeDir'          => IDEAPARK_DIR,
			'themeUri'          => IDEAPARK_URI,
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'searchUrl'         => home_url( '?s=' ),
			'lazyload'          => ideapark_mod( 'lazyload' ),
			'isRtl'             => ideapark_is_rtl(),
			'stickySidebar'     => ideapark_mod( 'sticky_sidebar' ),
			'stickyMenuDesktop' => ideapark_mod( 'sticky_menu_desktop' ),
			'stickyMenuMobile'  => ideapark_mod( 'sticky_menu_mobile' ),
			'headerType'        => ideapark_mod( 'header_type' ),
			'viewMore'          => esc_html__( 'View More', 'luchiana' ),
			'imagesloadedUrl'   => $js_url_imagesloaded,
			'scriptsHash'       => substr( get_option( $option_name = 'ideapark_scripts_hash' ), 0, 8 ),
			'stylesHash'        => substr( get_option( $option_name = 'ideapark_styles_hash' ), 0, 8 ),
			'cookiePath'        => COOKIEPATH ? COOKIEPATH : '/',
			'cookieDomain'      => COOKIE_DOMAIN,
			'cookieHash'        => COOKIEHASH,
			'locale'            => strtolower( get_locale() ),
			'masonryUrl'        => $js_url_masonry,
		];

		if ( ideapark_woocommerce_on() && ideapark_mod( 'wishlist_page' ) ) {
			$return = array_merge( $return, [
				'wishlistCookieName'  => ideapark_wishlist()->cookie_name,
				'wishlistTitleAdd'    => esc_html__( 'Add to Wishlist', 'luchiana' ),
				'wishlistTitleRemove' => esc_html__( 'Remove from Wishlist', 'luchiana' )
			] );
		}

		return $return;
	}
}

if ( ! function_exists( 'ideapark_disable_background_image' ) ) {
	function ideapark_disable_background_image( $value ) {
		if ( ideapark_mod( 'hide_inner_background' ) && ! is_front_page() && ! is_admin() ) {
			return '';
		} else {
			return $value;
		}
	}
}

if ( ! function_exists( 'ideapark_admin_scripts' ) ) {
	function ideapark_admin_scripts() {
		$screen  = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		$allowed = [ 'page', 'post', 'html_block', 'customize' ];
		if ( is_object( $screen ) && ! empty( $screen->id ) && in_array( $screen->id, $allowed ) ) {
			wp_enqueue_style( 'ideapark-theme-font', IDEAPARK_URI . '/assets/font/theme-icons.css', [], ideapark_mtime( IDEAPARK_DIR . '/assets/font/theme-icons.css' ) );
		}
		wp_enqueue_style( 'ideapark-admin', IDEAPARK_URI . '/assets/css/admin/admin.css', [], ideapark_mtime( IDEAPARK_DIR . '/assets/css/admin/admin.css' ) );
		wp_enqueue_script( 'ideapark-lib', IDEAPARK_URI . '/assets/js/site-lib.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_DIR . '/assets/js/site-lib.js' ), true );
		wp_enqueue_script( 'ideapark-admin-customizer', IDEAPARK_URI . '/assets/js/admin-customizer.js', [
			'jquery',
			'customize-controls'
		], ideapark_mtime( IDEAPARK_DIR . '/assets/js/admin-customizer.js' ), true );
		wp_localize_script( 'ideapark-admin-customizer', 'ideapark_dependencies', ideapark_get_theme_dependencies() );
		wp_localize_script( 'ideapark-admin-customizer', 'ideapark_ac_vars', [
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'errorText' => esc_html__( 'Something went wrong...', 'luchiana' )
		] );
	}
}

if ( ! function_exists( 'ideapark_exists_theme_addons' ) ) {
	function ideapark_exists_theme_addons() {
		return defined( 'IDEAPARK_FUNC_VERSION' );
	}
}

if ( ! function_exists( 'ideapark_wrap' ) ) {
	function ideapark_wrap( $str, $before = '', $after = '' ) {
		if ( trim( $str ) != '' ) {
			return sprintf( '%s%s%s', $before, $str, $after );
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_phone_wrap' ) ) {
	function ideapark_phone_wrap( $str, $before = '', $after = '', $add_link = true ) {
		if ( preg_match_all( '~\+?([\d \-()]{4,}|\d{3})~', $str, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $match ) {
				$prefix  = $before;
				$postfix = $after;
				if ( $add_link ) {
					$prefix  .= '<a href="tel:' . preg_replace( '~[^0-9\+]~', '', $match[0] ) . '">';
					$postfix = '</a>' . $postfix;
				}
				$str = preg_replace( '~' . preg_quote( $match[0], '~' ) . '~', $prefix . '\\0' . $postfix, $str );
			}

			return $str;
		} else {
			return $str;
		}
	}
}

if ( ! function_exists( 'ideapark_bg' ) ) {
	function ideapark_bg( $bg_color, $url = '', $text_color = '', $other = '' ) {
		$styles = [];
		$data   = '';
		if ( $bg_color ) {
			$styles[] = sprintf( 'background-color:%s', esc_attr( $bg_color ) );
		}
		if ( $text_color ) {
			$styles[] = sprintf( 'color:%s', esc_attr( $text_color ) );
		}
		if ( trim( $url ) != '' ) {
			$styles[] = sprintf( 'background-image:url(%s)', esc_url( $url ) );
		}
		if ( $other ) {
			$styles[] = esc_attr( trim( $other ) );
		}

		return trim( $data . ( $styles ? ' style="' . implode( ';', $styles ) . '"' : '' ) );
	}
}

if ( ! function_exists( 'ideapark_header_metadata' ) ) {
	function ideapark_header_metadata() {

		$lang_postfix = ideapark_get_lang_postfix();

		$fonts = [
			ideapark_mod( 'theme_font_text' . $lang_postfix ),
			ideapark_mod( 'theme_font_header' . $lang_postfix )
		];

		$css       = ideapark_get_google_font_uri( $fonts );
		$css_icons = IDEAPARK_URI . '/assets/font/theme-icons.css?ver=' . ideapark_mtime( IDEAPARK_DIR . '/assets/font/theme-icons.css' );

		?>
		<link rel="stylesheet" href="<?php echo esc_url( $css ); ?>">
		<link rel="stylesheet" href="<?php echo esc_url( $css_icons ); ?>">
		<?php
	}
}

if ( ! function_exists( 'ideapark_init_filesystem' ) ) {
	function ideapark_init_filesystem() {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/file.php';
		}
		if ( is_admin() ) {
			$url   = admin_url();
			$creds = false;
			if ( function_exists( 'request_filesystem_credentials' ) ) {
				$creds = @request_filesystem_credentials( $url, '', false, false, [] );
				if ( false === $creds ) {
					return false;
				}
			}
			if ( ! WP_Filesystem( $creds ) ) {
				if ( function_exists( 'request_filesystem_credentials' ) ) {
					@request_filesystem_credentials( $url, '', true, false );
				}

				return false;
			}

			return true;
		} else {
			WP_Filesystem();
		}

		return true;
	}
}

if ( ! function_exists( 'ideapark_fpc' ) ) {
	function ideapark_fpc( $file, $data, $flag = 0 ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->put_contents( $file, ( FILE_APPEND == $flag && $wp_filesystem->exists( $file ) ? $wp_filesystem->get_contents( $file ) : '' ) . $data, false );
			}
		}

		return false;
	}
}

if ( ! function_exists( 'ideapark_fgc' ) ) {
	function ideapark_fgc( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->get_contents( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_is_file' ) ) {
	function ideapark_is_file( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->is_file( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_is_dir' ) ) {
	function ideapark_is_dir( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->is_dir( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_mkdir' ) ) {
	function ideapark_mkdir( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return wp_mkdir_p( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_mtime' ) ) {
	function ideapark_mtime( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->mtime( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_array_merge' ) ) {
	function ideapark_array_merge( $a1, $a2 ) {
		for ( $i = 1; $i < func_num_args(); $i ++ ) {
			$arg = func_get_arg( $i );
			if ( is_array( $arg ) && count( $arg ) > 0 ) {
				foreach ( $arg as $k => $v ) {
					$a1[ $k ] = $v;
				}
			}
		}

		return $a1;
	}
}

if ( ! function_exists( 'ideapark_ajax_custom_css' ) ) {
	function ideapark_ajax_custom_css() {
		echo ideapark_customize_css( true );
		die();
	}
}

if ( ! function_exists( 'ideapark_correct_tgmpa_mofile' ) ) {
	function ideapark_correct_tgmpa_mofile( $mofile, $domain ) {
		if ( 'tgmpa' !== $domain ) {
			return $mofile;
		}

		return preg_replace( '`/([a-z]{2}_[A-Z]{2}.mo)$`', '/tgmpa-$1', $mofile );
	}
}

if ( ! function_exists( 'ideapark_get_template_part' ) ) {
	function ideapark_get_template_part( $template, $args = null ) {
		set_query_var( 'ideapark_var', $args );
		get_template_part( $template );
		set_query_var( 'ideapark_var', null );
	}
}

if ( ! function_exists( 'ideapark_af' ) ) {
	function ideapark_af( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		return add_filter( $tag, $function_to_add, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'ideapark_rf' ) ) {
	function ideapark_rf( $tag, $function_to_remove, $priority = 10 ) {
		$f = 'remove_filter';

		return call_user_func( $f, $tag, $function_to_remove, $priority );
	}
}

if ( ! function_exists( 'ideapark_aa' ) ) {
	function ideapark_aa( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		return add_action( $tag, $function_to_add, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'ideapark_ra' ) ) {
	function ideapark_ra( $tag, $function_to_remove, $priority = 10 ) {
		$f = 'remove_action';

		return call_user_func( $f, $tag, $function_to_remove, $priority );
	}
}

if ( ! function_exists( 'ideapark_shortcode' ) ) {
	function ideapark_shortcode( $code ) {
		$f = 'do' . '_shortcode';

		return call_user_func( $f, $code );
	}
}

if ( ! function_exists( 'ideapark_get_inline_svg' ) ) {
	function ideapark_get_inline_svg( $attachment_id, $class = '' ) {
		$svg = get_post_meta( $attachment_id, '_ideapark_inline_svg', true );
		if ( empty( $svg ) ) {
			$svg = ideapark_fgc( get_attached_file( $attachment_id ) );
			update_post_meta( $attachment_id, '_ideapark_inline_svg', $svg );
		}

		if ( ! empty( $svg ) ) {
			if ( $class ) {
				if ( preg_match( '~(<svg[^>]+class\s*=\s*[\'"][^\'"]*)([\'"][^>]*>)~i', $svg, $match ) ) {
					$svg = str_replace( $match[1], $match[1] . ' ' . esc_attr( $class ), $svg );
				} else {
					$svg = preg_replace( '~<svg~i', '<svg class="' . esc_attr( $class ) . '"', $svg );
				}
			}
		}

		return $svg;
	}
}

if ( ! function_exists( 'ideapark_class' ) ) {
	function ideapark_class( $cond, $class_yes, $class_no = '' ) {
		echo ideapark_wrap( $cond ? $class_yes : $class_no, ' ', ' ' );
	}
}

if ( ! function_exists( 'ideapark_html_attributes' ) ) {
	function ideapark_html_attributes( array $attributes ) {
		$rendered_attributes = [];

		foreach ( $attributes as $attribute_key => $attribute_values ) {
			if ( is_array( $attribute_values ) ) {
				$attribute_values = implode( ' ', $attribute_values );
			}

			$rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
		}

		return implode( ' ', $rendered_attributes );
	}
}

if ( ! function_exists( 'ideapark_add_allowed_tags' ) ) {
	function ideapark_add_allowed_tags( $tags, $context_type = '' ) {

		if ( $context_type == 'post' ) {
			$tags['svg'] = [ 'class' => true ];
			$tags['use'] = [ 'xlink:href' => true ];
		}

		return $tags;
	}
}

if ( ! function_exists( 'ideapark_manu_link_hash_fix' ) ) {
	function ideapark_menu_link_hash_fix( $attr ) {
		global $ideapark_is_front_page;
		if ( ! $ideapark_is_front_page && ! empty( $attr['href'] ) && strpos( $attr['href'], '#' ) === 0 && strlen( $attr['href'] ) > 1 ) {
			$attr['href'] = home_url( '/' ) . $attr['href'];
		}

		return $attr;
	}
}

if ( ! function_exists( 'ideapark_empty_gif' ) ) {
	function ideapark_empty_gif() {
		return 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	}
}

if ( ! function_exists( 'ideapark_check_front_page' ) ) {
	function ideapark_check_front_page() {
		global $ideapark_is_front_page;
		$ideapark_is_front_page = is_front_page();
	}
}

if ( ! function_exists( 'ideapark_custom_meta_boxes' ) ) {
	function ideapark_custom_meta_boxes( $meta_boxes ) {
		$fields = [
			[
				'type' => 'custom_html',
				'std'  => sprintf( __( 'If nothing is selected, the %s default settings %s will be used', 'luchiana' ), '<a href="' . admin_url( 'customize.php?autofocus[control]=header_text_color' ) . '">', '</a>' ),
			],
			[
				'name'    => __( 'Header height', 'luchiana' ),
				'id'      => 'header_height',
				'type'    => 'select',
				'options' => [
					''       => __( 'Default', 'luchiana' ),
					'low'    => __( 'Low', 'luchiana' ),
					'medium' => __( 'Medium', 'luchiana' ),
					'high'   => __( 'High', 'luchiana' ),
				],
			],
			[
				'name'              => __( 'Menu and buttons color', 'luchiana' ),
				'label_description' => __( 'Only for transparent header types', 'luchiana' ),
				'id'                => 'header_menu_color',
				'type'              => 'color'
			],
			[
				'name' => __( 'Text color', 'luchiana' ),
				'id'   => 'header_text_color',
				'type' => 'color'
			],
			[
				'name' => __( 'Background color', 'luchiana' ),
				'id'   => 'header_bg_color',
				'type' => 'color'
			],
			[
				'name'             => __( 'Background image', 'luchiana' ),
				'id'               => 'header_bg_image',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'force_delete'     => false,
				'max_status'       => false,
				'image_size'       => 'thumbnail',
			],
			[
				'name'    => __( 'Background type', 'luchiana' ),
				'id'      => 'header_bg_type',
				'type'    => 'select',
				'options' => [
					''       => __( 'Default', 'luchiana' ),
					'cover'  => __( 'Cover', 'luchiana' ),
					'repeat' => __( 'Repeat', 'luchiana' ),
				],
			],
			[
				'name'             => __( 'Custom logo', 'luchiana' ),
				'id'               => 'header_custom_logo',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'force_delete'     => false,
				'max_status'       => false,
				'image_size'       => 'full',
			],
		];

		$meta_boxes[] = [
			'title'      => __( 'Page Header', 'luchiana' ),
			'context'    => 'side',
			'post_types' => [ 'page', 'post' ],
			'priority'   => 'low',
			'fields'     => $fields
		];

		$meta_boxes[] = [
			'title'      => __( 'Header', 'luchiana' ),
			'taxonomies' => [ 'category', 'post_tag' ],
			'fields'     => $fields,
		];

		return $meta_boxes;
	}
}

if ( ! function_exists( 'ideapark_breadcrumb_list' ) ) {
	function ideapark_breadcrumb_list() {

		$breadcrumb_home_text             = __( 'Home', 'luchiana' );
		$breadcrumb_display_home          = true;
		$breadcrumb_display_home_on_front = false;

		$home_url = home_url( '/' );

		$array_list = [];

		$is_page_for_posts = ! ! ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) );

		if ( is_front_page() && is_home() ) {

			if ( $breadcrumb_display_home && $breadcrumb_display_home_on_front ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_front_page is_home',

				];
			}

		} elseif ( is_front_page() ) {

			if ( $breadcrumb_display_home && $breadcrumb_display_home_on_front ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_front_page',
				];
			}

		} elseif ( is_home() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_home',
				];
			}

			if ( $is_page_for_posts ) {
				$array_list[] = [
					'link'     => get_permalink( get_option( 'page_for_posts' ) ),
					'title'    => get_the_title( get_option( 'page_for_posts' ) ),
					'location' => 'is_category',
				];
			}


		} else if ( is_attachment() ) {

			$current_attachment_id   = get_query_var( 'attachment_id' );
			$current_attachment_link = get_attachment_link( $current_attachment_id );

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_home',
				];
			}

			$array_list[] = [
				'link'     => $current_attachment_link,
				'title'    => get_the_title(),
				'location' => 'is_attachment',
			];

		} else if ( ideapark_woocommerce_on() && is_woocommerce() && is_shop() ) {
			$shop_page_id = wc_get_page_id( 'shop' );

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_page',
				];
			}

			$array_list[] = [
				'link'     => get_permalink( $shop_page_id ),
				'title'    => get_the_title( $shop_page_id ),
				'location' => 'is_page',
			];

		} else if ( ideapark_woocommerce_on() && is_woocommerce() ) {
			woocommerce_breadcrumb();

		} else if ( is_page() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_page',
				];
			}


			global $post;
			$home = get_post( get_option( 'page_on_front' ) );

			for ( $i = count( $post->ancestors ) - 1; $i >= 0; $i -- ) {
				if ( ( $home->ID ) != ( $post->ancestors[ $i ] ) ) {

					$array_list[] = [
						'link'     => get_permalink( $post->ancestors[ $i ] ),
						'title'    => get_the_title( $post->ancestors[ $i ] ),
						'location' => 'is_page',
					];
				}
			}


			$array_list[] = [
				'link'     => get_permalink( $post->ID ),
				'title'    => get_the_title( $post->ID ),
				'location' => 'is_page',
			];

		} else if ( is_singular() ) {

			if ( is_preview() ) {
				$array_list[] = [
					'link'     => '#',
					'title'    => __( 'Post preview', 'luchiana' ),
					'location' => 'post',
				];

				return $array_list;
			}

			$permalink_structure = get_option( 'permalink_structure', true );
			$permalink_structure = str_replace( '%postname%', '', $permalink_structure );
			$permalink_structure = str_replace( '%post_id%', '', $permalink_structure );

			$permalink_items = array_filter( explode( '/', $permalink_structure ) );

			global $post;
			$author_id        = $post->post_author;
			$author_posts_url = get_author_posts_url( $author_id );
			$author_name      = get_the_author_meta( 'display_name', $author_id );

			$post_date_year  = get_the_time( 'Y' );
			$post_date_month = get_the_time( 'm' );
			$post_date_day   = get_the_time( 'd' );

			$get_month_link = get_month_link( $post_date_year, $post_date_month );
			$get_year_link  = get_year_link( $post_date_year );
			$get_day_link   = get_day_link( $post_date_year, $post_date_month, $post_date_day );


			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_singular',
				];
			}

			if ( ! empty( $permalink_structure ) && get_post_type() == 'post' ) {

				if ( $is_page_for_posts || ! $breadcrumb_display_home ) {
					$array_list[] = [
						'link'     => $is_page_for_posts ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url(),
						'title'    => $is_page_for_posts ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Posts', 'luchiana' ),
						'location' => 'is_category',
					];
				}

				if ( in_array( '%year%', $permalink_items ) ) {

					$array_list[] = [
						'link'     => $get_year_link,
						'title'    => $post_date_year,
						'location' => 'is_singular',
					];

				}

				if ( in_array( '%monthnum%', $permalink_items ) ) {
					$array_list[] = [
						'link'     => $get_month_link,
						'title'    => $post_date_month,
						'location' => 'is_singular',
					];
				}

				if ( in_array( '%day%', $permalink_items ) ) {
					$array_list[] = [
						'link'     => $get_day_link,
						'title'    => $post_date_day,
						'location' => 'is_singular',
					];

				}

				if ( in_array( '%author%', $permalink_items ) ) {
					$array_list[] = [
						'link'     => $author_posts_url,
						'title'    => $author_name,
						'location' => 'is_singular',
					];
				}

				if ( in_array( '%category%', $permalink_items ) ) {
					$category_string = get_query_var( 'category_name' );
					$category_arr    = [];
					$taxonomy        = 'category';
					if ( strpos( $category_string, '/' ) ) {

						$category_arr   = explode( '/', $category_string );
						$category_count = count( $category_arr );
						$last_cat       = $category_arr[ ( $category_count - 1 ) ];

						$term_data = get_term_by( 'slug', $last_cat, $taxonomy );

						$term_id   = $term_data->term_id;
						$term_name = $term_data->name;
						$term_link = get_term_link( $term_id, $taxonomy );


						$parents_id = get_ancestors( $term_id, $taxonomy );

						$parents_id = array_reverse( $parents_id );

						$i = 1;
						foreach ( $parents_id as $id ) {

							$parent_term_link = get_term_link( $id, $taxonomy );
							$paren_term_name  = get_term_by( 'id', $id, $taxonomy );

							$array_list[] = [
								'link'     => $parent_term_link,
								'title'    => $paren_term_name->name,
								'location' => 'is_singular',
							];


							$i ++;
						}

						$array_list[] = [
							'link'     => $term_link,
							'title'    => $term_name,
							'location' => 'is_singular',
						];
					} else {

						$term_data = get_term_by( 'slug', $category_string, $taxonomy );

						$term_id   = isset( $term_data->term_id ) ? $term_data->term_id : '';
						$term_name = isset( $term_data->name ) ? $term_data->name : '';

						if ( ! empty( $term_id ) ):
							$term_link = get_term_link( $term_id, $taxonomy );

							$array_list[] = [
								'link'     => $term_link,
								'title'    => $term_name,
								'location' => 'is_singular',
							];
						endif;
					}
				}

				$array_list[] = [
					'link'     => get_permalink( $post->ID ),
					'title'    => get_the_title( $post->ID ),
					'location' => 'is_singular',
				];

			} else {

				$post_type = get_post_type();

				$obj = get_post_type_object( $post_type );

				if ( $post_type == 'post' ) {
					if ( $is_page_for_posts || ! $breadcrumb_display_home ) {
						$array_list[] = [
							'link'     => $is_page_for_posts ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url(),
							'title'    => $is_page_for_posts ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Posts', 'luchiana' ),
							'location' => 'is_singular',
						];
					}
				} else {
					$array_list[] = [
						'link'     => get_post_type_archive_link( $post_type ),
						'title'    => $obj->labels->name,
						'location' => 'is_singular',
					];
				}

				$array_list[] = [
					'link'     => get_permalink( $post->ID ),
					'title'    => get_the_title( $post->ID ),
					'location' => 'is_singular',
				];
			}


		} else if ( is_tax() ) {

			$queried_object = get_queried_object();
			$term_name      = $queried_object->name;
			$term_id        = $queried_object->term_id;

			$taxonomy   = $queried_object->taxonomy;
			$term_link  = get_term_link( $term_id, $taxonomy );
			$parents_id = get_ancestors( $term_id, $taxonomy );

			$parents_id = array_reverse( $parents_id );

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_tax',
				];
			}

			foreach ( $parents_id as $id ) {

				$parent_term_link = get_term_link( $id, $taxonomy );
				$paren_term_name  = get_term_by( 'id', $id, $taxonomy );

				$array_list[] = [
					'link'     => $parent_term_link,
					'title'    => $paren_term_name->name,
					'location' => 'is_tax',
				];
			}

			$array_list[] = [
				'link'     => $term_link,
				'title'    => $term_name,
				'location' => 'is_tax',
			];


		} else if ( is_category() ) {

			$current_cat_id = get_query_var( 'cat' );
			$queried_object = get_queried_object();

			$taxonomy  = $queried_object->taxonomy;
			$term_id   = $queried_object->term_id;
			$term_name = $queried_object->name;
			$term_link = get_term_link( $term_id, $taxonomy );

			$parents_id = get_ancestors( $term_id, $taxonomy );
			$parents_id = array_reverse( $parents_id );

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_category',
				];
			}

			if ( $taxonomy == 'category' ) {
				$array_list[] = [
					'link'     => $is_page_for_posts ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url(),
					'title'    => $is_page_for_posts ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Blog', 'luchiana' ),
					'location' => 'is_category',
				];
			} else {
				$array_list[] = [
					'link'     => '#',
					'title'    => $taxonomy,
					'location' => 'is_category',
				];
			}

			foreach ( $parents_id as $id ) {

				$parent_term_link = get_term_link( $id, $taxonomy );
				$paren_term_name  = get_term_by( 'id', $id, $taxonomy );

				$array_list[] = [
					'link'     => $parent_term_link,
					'title'    => $paren_term_name->name,
					'location' => 'is_category',
				];
			}

			$array_list[] = [
				'link'     => $term_link,
				'title'    => $term_name,
				'location' => 'is_category',
			];


		} else if ( is_tag() ) {

			$current_tag_id   = get_query_var( 'tag_id' );
			$current_tag      = get_tag( $current_tag_id );
			$current_tag_name = $current_tag->name;

			$current_tag_link = get_tag_link( $current_tag_id );;

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_tag',
				];
			}

			if ( $is_page_for_posts ) {
				$array_list[] = [
					'link'     => get_permalink( get_option( 'page_for_posts' ) ),
					'title'    => get_the_title( get_option( 'page_for_posts' ) ),
					'location' => 'is_tag',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Tag', 'luchiana' ),
				'location' => 'is_tag',
			];


			$array_list[] = [
				'link'     => $current_tag_link,
				'title'    => $current_tag_name,
				'location' => 'is_tag',
			];
		} else if ( is_author() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_author',
				];
			}

			if ( $is_page_for_posts ) {
				$array_list[] = [
					'link'     => get_permalink( get_option( 'page_for_posts' ) ),
					'title'    => get_the_title( get_option( 'page_for_posts' ) ),
					'location' => 'is_author',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Author', 'luchiana' ),
				'location' => 'is_author',
			];

			$array_list[] = [
				'link'     => get_author_posts_url( get_the_author_meta( "ID" ) ),
				'title'    => get_the_author(),
				'location' => 'is_author',
			];


		} else if ( is_search() ) {

			$current_query = sanitize_text_field( get_query_var( 's' ) );


			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_search',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Search', 'luchiana' ),
				'location' => 'is_search',
			];


			$array_list[] = [
				'link'     => '#',
				'title'    => $current_query,
				'location' => 'is_search',
			];

		} else if ( is_year() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_year',
				];
			}

			if ( $is_page_for_posts ) {
				$array_list[] = [
					'link'     => get_permalink( get_option( 'page_for_posts' ) ),
					'title'    => get_the_title( get_option( 'page_for_posts' ) ),
					'location' => 'is_year',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Year', 'luchiana' ),
				'location' => 'is_year',
			];

			$array_list[] = [
				'link'     => '#',
				'title'    => get_the_date( 'Y' ),
				'location' => 'is_year',
			];

		} else if ( is_month() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_month',
				];
			}

			if ( $is_page_for_posts ) {
				$array_list[] = [
					'link'     => get_permalink( get_option( 'page_for_posts' ) ),
					'title'    => get_the_title( get_option( 'page_for_posts' ) ),
					'location' => 'is_month',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Month', 'luchiana' ),
				'location' => 'is_month',
			];


			$array_list[] = [
				'link'     => '#',
				'title'    => get_the_date( 'F' ),
				'location' => 'is_month',
			];

		} else if ( is_date() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_date',
				];
			}

			if ( $is_page_for_posts ) {
				$array_list[] = [
					'link'     => get_permalink( get_option( 'page_for_posts' ) ),
					'title'    => get_the_title( get_option( 'page_for_posts' ) ),
					'location' => 'is_date',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Date', 'luchiana' ),
				'location' => 'is_date',
			];

			$array_list[] = [
				'link'     => '#',
				'title'    => get_the_date(),
				'location' => 'is_date',
			];
		} elseif ( is_404() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_404',
				];
			}

			$array_list[] = [
				'link'     => '#',
				'title'    => __( 'Page not found', 'luchiana' ),
				'location' => 'is_404',
			];

		} elseif ( is_post_type_archive() ) {

			if ( $breadcrumb_display_home ) {
				$array_list[] = [
					'link'     => $home_url,
					'title'    => $breadcrumb_home_text,
					'location' => 'is_tax',
				];
			}

			$queried_object = get_queried_object();
			$array_list[]   = [
				'link'     => get_post_type_archive_link( $queried_object->query_var ),
				'title'    => $queried_object->label,
				'location' => 'is_tax',
			];
		}

		return $array_list;

	}
}

if ( ! function_exists( 'ideapark_shorten_string' ) ) {
	function ideapark_shorten_string( $string, $limit_by = 'word', $limit_count = 4, $ending = '...' ) {

		if ( empty( $limit_count ) ) {
			$limit_count = 4;
		}

		if ( $limit_by == 'character' ) {
			if ( strlen( $string ) > $limit_count ) {
				$stringCut = substr( $string, 0, $limit_count );
				$string    = substr( $stringCut, 0, strrpos( $stringCut, ' ' ) );

				return $string . $ending;
			} else {
				return $string;
			}
		} else {
			$array = explode( " ", $string );
			if ( count( $array ) <= $limit_count ) {
				$retval = $string;
			} else {
				array_splice( $array, $limit_count );
				$retval = implode( " ", $array ) . $ending;
			}

			return $retval;
		}
	}
}

if ( ! function_exists( 'ideapark_is_elementor' ) ) {
	function ideapark_is_elementor() {
		return class_exists( 'Elementor\Plugin' );
	}
}

if ( ! function_exists( 'ideapark_is_elementor_page' ) ) {
	function ideapark_is_elementor_page() {
		global $post;
		if ( class_exists( 'Elementor\Plugin' ) ) {
			return \Elementor\Plugin::$instance->db->is_built_with_elementor( $post->ID );
		}
	}
}

if ( ! function_exists( 'ideapark_set_cookie' ) ) {
	function ideapark_set_cookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $httponly );
		}
	}
}

if ( ! function_exists( 'IDEAPARK_font' ) ) {
	function IDEAPARK_font( $fonts ) {
		$zip = IDEAPARK_DIR . '/plugins/font/icons.zip';

		if ( ideapark_is_file( $zip ) ) {
			$fonts['icons'] = [
				'zip'     => $zip,
				'version' => ideapark_mtime( IDEAPARK_DIR . '/plugins/font/icons.zip' )
			];
		}

		return $fonts;
	}
}

if ( ! function_exists( 'ideapark_elementor_add_css_editor' ) ) {
	function ideapark_elementor_add_css_editor() {
		wp_enqueue_style( 'ideapark-font', IDEAPARK_URI . '/assets/font/theme-icons.css', [], ideapark_mtime( IDEAPARK_DIR . '/assets/font/theme-icons.css' ), 'all' );
	}
}

if ( ! function_exists( 'ideapark_comment_form_fields' ) ) {
	function ideapark_comment_form_fields( $fields ) {

		if ( isset( $fields['comment'] ) && preg_match( '~comment-form-rating~', $fields['comment'] ) ) {
			return $fields;
		}

		if ( isset( $fields['cookies'] ) ) {
			$cookies_consent = $fields['cookies'];
			unset( $fields['cookies'] );
			$fields['cookies'] = $cookies_consent;
		}

		foreach ( $fields as $index => $field ) {
			if ( ( $index != 'cookies' ) && ( $label = preg_match( '~<label for="[^"]+">([^<]+)<~ui', $field, $match ) ) ) {
				$is_required      = preg_match( '~class="required"~ui', $field );
				$fields[ $index ] = preg_replace( '~<label[\s\S]+</label>~uiU', '', $fields[ $index ] );
				$fields[ $index ] = preg_replace( '~ id="~ui', ' placeholder="' . esc_attr( wp_specialchars_decode( $match[1] ) ) . ( $is_required ? '*' : '' ) . '" id="', $fields[ $index ] );
			}
		}

		return $fields;
	}
}

if ( ! function_exists( 'ideapark_utf8_wordwrap' ) ) {
	function ideapark_utf8_wordwrap( $string, $width = 75, $break = "\n", $cut = false ) {
		if ( $cut ) {
			// Match anything 1 to $width chars long followed by whitespace or EOS,
			// otherwise match anything $width chars long
			$search  = '/(.{1,' . $width . '})(?:\s|$)|(.{' . $width . '})/uS';
			$replace = '$1$2' . $break;
		} else {
			// Anchor the beginning of the pattern with a lookahead
			// to avoid crazy backtracking when words are longer than $width
			$search  = '/(?=\s)(.{1,' . $width . '})(?:\s|$)/uS';
			$replace = '$1' . $break;
		}

		return preg_replace( $search, $replace, $string );
	}
}

if ( ! function_exists( 'ideapark_truncate' ) ) {
	function ideapark_truncate( $str, $limit, $ending = '&hellip;' ) {
		return strtok( ideapark_utf8_wordwrap( $str, $limit, "{$ending}\n", true ), "\n" );
	}
}

if ( ! function_exists( 'ideapark_strlen' ) ) {
	function ideapark_strlen( $string ) {
		static $mbstring_available;

		if ( $mbstring_available === null ) {
			$mbstring_available = extension_loaded( 'mbstring' );
		}

		if ( $mbstring_available ) {
			return mb_strlen( $string, 'UTF-8' );
		}

		return strlen( utf8_decode( $string ) );
	}
}

if ( ! function_exists( 'ideapark_pingback_header' ) ) {
	function ideapark_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}
}

if ( ! function_exists( 'ideapark_generator_tag' ) ) {
	function ideapark_generator_tag( $gen, $type ) {
		$theme_obj = wp_get_theme( 'luchiana' );
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="' . esc_attr( $theme_obj['Name'] . ' ' . IDEAPARK_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="' . esc_attr( $theme_obj['Name'] . ' ' . IDEAPARK_VERSION ) . '" />';
				break;
		}

		return $gen;
	}
}

if ( ! function_exists( 'ideapark_post_params' ) ) {
	function ideapark_post_params() {
		global $post;
		$ret               = [];
		$ret['is_product'] = ( ideapark_woocommerce_on() && get_post_type() == 'product' );
		$ret['is_page']    = ( get_post_type() == 'page' );
		$ret['format']     = get_post_format();
		if ( ! $ret['format'] ) {
			$ret['format'] = 'standard';
		}
		if ( ! in_array( $ret['format'], [ 'standard', 'video', 'gallery' ] ) ) {
			$ret['format'] = 'standard';
		}

		$ret['video_url']     = get_post_meta( $post->ID, 'post_video_url', true );
		$ret['image_gallery'] = get_post_meta( $post->ID, 'post_image_gallery' );
		$ret['has_thumb']     = has_post_thumbnail() || ( $ret['format'] == 'video' && $ret['video_url'] ) || ( $ret['format'] == 'gallery' && $ret['image_gallery'] );

		return $ret;
	}
}

if ( ! function_exists( 'ideapark_header_params' ) ) {
	function ideapark_header_params() {
		global $post;
		static $result;

		if ( $result === null ) {

			$result = [
				'header_height'       => ( is_single() || is_home() || is_archive() || ideapark_woocommerce_on() && is_shop() ) ? '' : 'low',
				'image_id'            => '',
				'bg_color'            => '',
				'header_text_color'   => '',
				'header_menu_color'   => '',
				'header_type'         => ideapark_mod( 'header_type' ),
				'header_type_mobile'  => ideapark_mod( 'header_type_mobile' ),
				'header_class'        => '',
				'header_is_custom_bg' => false,
				'header_bg_type'      => '',
				'header_bg_size'      => '',
			];

			$post_type = '';

			if ( ( ( is_admin() && function_exists( 'get_current_screen' ) && ( $screen = get_current_screen() ) && ! empty( $screen->id ) && in_array( $screen->id, [
							'page',
							'post',
						] ) ) || IDEAPARK_IS_AJAX ) && ! empty( $post->ID ) ) {
				$post_type = get_post_type( $post->ID );
			}

			if ( ideapark_woocommerce_on() && ( is_cart() || is_checkout() || is_account_page() || ideapark_is_wishlist_page() ) ) {
				$result['header_class'] = 'wc';
			} elseif ( ideapark_woocommerce_on() && is_product() ) {
				$result['header_class'] = 'product-page';
			} elseif ( ideapark_woocommerce_on() && ( is_product_taxonomy() || is_shop() ) ) {
				$result['header_class'] = 'product-list';
			} elseif ( is_page() || $post_type == 'page' ) {
				$result['header_class'] = 'page';
			} elseif ( is_single() || is_home() || is_archive() ) {
				$result['header_class'] = 'post';
			}

			if ( $height = ideapark_mod( 'header_height' ) ) {
				$result['header_height'] = $height;
			}

			if ( $height = ideapark_mod( 'header_height_' . $result['header_class'] ) ) {
				$result['header_height'] = $height;
			}

			if (
			( ( is_category() || is_tax() || is_tag() ) && ( $queried_object = get_queried_object() ) && ( $term_meta = get_term_meta( $queried_object->term_id ) ) )
			) {

				if ( ! empty( $term_meta['header_height'][0] ) ) {
					$result['header_height'] = $term_meta['header_height'][0];
				}

				if ( ! empty( $term_meta['header_menu_color'][0] ) ) {
					$result['header_menu_color'] = $term_meta['header_menu_color'][0];
				}

				if ( ! empty( $term_meta['header_bg_color'][0] ) ) {
					$result['bg_color'] = $term_meta['header_bg_color'][0];
				}

				if ( ! empty( $term_meta['header_text_color'][0] ) ) {
					$result['header_text_color'] = $term_meta['header_text_color'][0];
				}

				if ( ! empty( $term_meta['header_bg_image'][0] ) ) {
					$result['image_id']            = $term_meta['header_bg_image'][0];
					$result['header_is_custom_bg'] = true;
				}

				if ( ! empty( $term_meta['header_custom_logo'][0] ) ) {
					$result['custom_logo_id'] = $term_meta['header_custom_logo'][0];
				}

				if ( ! empty( $term_meta['header_bg_type'][0] ) ) {
					$result['header_bg_type'] = $term_meta['header_bg_type'][0];
				}
			}

			if (
				( is_singular() && ( $post_id = $post->ID ) ) ||
				( is_shop() && ( $post_id = wc_get_page_id( 'shop' ) ) ) ||
				( is_home() && get_option( 'page_for_posts' ) && 'page' == get_option( 'show_on_front' ) && ( $post_id = get_option( 'page_for_posts' ) ) )
			) {

				if ( $val = get_post_meta( $post_id, 'header_height', true ) ) {
					$result['header_height'] = $val;
				}

				if ( $val = get_post_meta( $post_id, 'header_menu_color', true ) ) {
					$result['header_menu_color'] = $val;
				}

				if ( $val = get_post_meta( $post_id, 'header_bg_color', true ) ) {
					$result['bg_color'] = $val;
				}

				if ( $val = get_post_meta( $post_id, 'header_text_color', true ) ) {
					$result['header_text_color'] = $val;
				}

				if ( $val = get_post_meta( $post_id, 'header_bg_image', true ) ) {
					$result['image_id']            = $val;
					$result['header_is_custom_bg'] = true;

				}

				if ( $val = get_post_meta( $post_id, 'header_custom_logo', true ) ) {
					$result['custom_logo_id'] = $val;
				}

				if ( $val = get_post_meta( $post_id, 'header_bg_type', true ) ) {
					$result['header_bg_type'] = $val;
				}
			}
		}

		if ( ideapark_mod( 'header_background_retina' ) && $result['image_id'] && ( $params = wp_get_attachment_image_src( $result['image_id'], 'full' ) ) ) {
			$result['header_bg_size'] = round( $params[1] / 2 ) . 'px ' . round( $params[2] / 2 ) . 'px';
		}

		return $result;
	}
}

if ( ! function_exists( 'ideapark_is_wishlist_page' ) ) {
	function ideapark_is_wishlist_page() {
		global $post;

		return ( ideapark_woocommerce_on() && is_page() && ideapark_mod( 'wishlist_page' ) && ideapark_mod( 'wishlist_page' ) == $post->ID );
	}
}

if ( ! function_exists( 'ideapark_image_meta' ) ) {
	function ideapark_image_meta( $image_id, $size = 'full' ) {
		$full = wp_get_attachment_image_src( $image_id, 'full' );
		if ( $image = $size == 'full' ? $full : wp_get_attachment_image_src( $image_id, $size ) ) {
			$srcset     = wp_get_attachment_image_srcset( $image_id, $size );
			$sizes      = wp_get_attachment_image_sizes( $image_id, $size );
			$alt        = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$attachment = get_post( $image_id );
			if ( ! $alt ) {
				if ( ! $attachment ) {
					$alt = '';
				} else {
					$alt = $attachment->post_excerpt;
					if ( ! $alt ) {
						$alt = $attachment->post_title;
					}
				}
			}
			$alt   = trim( strip_tags( $alt ) );
			$title = $attachment ? $attachment->post_title : '';

			return [
				'width'       => $image[1],
				'height'      => $image[2],
				'src'         => $image[0],
				'srcset'      => $srcset,
				'sizes'       => $sizes,
				'full'        => $full[0],
				'full_width'  => $full[1],
				'full_height' => $full[2],
				'alt'         => $alt,
				'title'       => $title,
			];
		}

		return false;
	}
}

if ( ! function_exists( 'ideapark_img' ) ) {
	function ideapark_img( $image_meta, $class = '', $lazy = null, $attr = [] ) {
		if ( $image_meta ) {
			if ( $class ) {
				$image_meta['class'] = $class;
			}
			if ( ideapark_mod( 'lazyload' ) && $lazy === null || $lazy === true ) {
				$image_meta['loading'] = 'lazy';
			}
			$attr_names = [
				'class',
				'width',
				'height',
				'src',
				'srcset',
				'sizes',
				'alt',
				'loading'
			];
			if ( is_array( $attr ) && $attr ) {
				foreach ( $attr as $key => $val ) {
					$image_meta[ $key ] = $val;
					$attr_names[]       = $key;
				}
			}
			$s = "<img";
			foreach (
				$attr_names as $attr_name
			) {
				if ( ! empty( $image_meta[ $attr_name ] ) || is_array( $attr ) && array_key_exists( $attr_name, $attr ) ) {
					if ( ( $attr_name == 'srcset' || $attr_name == 'sizes' ) && ( empty( $image_meta['srcset'] ) || empty( $image_meta['sizes'] ) ) ) {
						continue;
					}
					$s .= ' ' . $attr_name . '="' . esc_attr( $image_meta[ $attr_name ] ) . '"';
				}
			}
			$s .= "/>";

			return $s;
		}
	}
}

if ( ! function_exists( 'ideapark_post_status' ) ) {
	function ideapark_post_status( $post_id ) {
		global $wpdb;
		$query = "SELECT post_status FROM {$wpdb->posts} WHERE ID=%d";

		return $wpdb->get_var( $wpdb->prepare( $query, $post_id ) );
	}
}

if ( ! function_exists( 'ideapark_advert_bar_render' ) ) {
	function ideapark_advert_bar_render() {
		global $ideapark_advert_bar;
		$header_type = ideapark_mod( 'header_type' );
		if ( ! is_admin() && ( $header_type == 'header-type-4' || $header_type == 'header-type-5' ) ) {
			ob_start();
			get_template_part( 'templates/header-advert-bar' );
			$ideapark_advert_bar = trim( ob_get_clean() );
		}
	}
}

if ( ! function_exists( 'ideapark_is_rtl' ) ) {
	function ideapark_is_rtl() {
		return apply_filters( 'ideapark_is_rtl', is_rtl() );
	}
}

if ( ! function_exists( 'ideapark_is_elementor_preview' ) ) {
	function ideapark_is_elementor_preview() {
		return ideapark_is_elementor() && isset( $_GET['action'] ) && $_GET['action'] == 'elementor' && ! empty( $_GET['post'] ) && is_admin();
	}
}

/*------------------------------------*\
	Actions + Filters
\*------------------------------------*/

if ( IDEAPARK_IS_AJAX_SEARCH ) {
	add_action( 'wp_ajax_ideapark_ajax_search', 'ideapark_ajax_search' );
	add_action( 'wp_ajax_nopriv_ideapark_ajax_search', 'ideapark_ajax_search' );
} elseif ( IDEAPARK_IS_AJAX_CSS ) {
	add_action( 'wp_ajax_ideapark_ajax_custom_css', 'ideapark_ajax_custom_css' );
} elseif ( ! IDEAPARK_IS_AJAX_HEARTBEAT ) {

	add_action( 'wp', 'ideapark_check_front_page' );
	add_action( 'wp_head', 'ideapark_header_metadata', 1 );
	add_action( 'wp_head', 'ideapark_pingback_header' );
	add_action( 'widgets_init', 'ideapark_widgets_init' );
	add_action( 'admin_enqueue_scripts', 'ideapark_admin_scripts' );
	add_action( 'wp_enqueue_scripts', 'ideapark_scripts', 99 );
	add_action( 'wp_enqueue_scripts', 'ideapark_scripts_load', 999 );
	add_action( 'current_screen', 'ideapark_editor_style' );
	add_action( 'wp_head', 'ideapark_advert_bar_render', 1 );
	add_action( 'elementor/editor/after_enqueue_styles', 'ideapark_elementor_add_css_editor' );
	add_action( 'ideapark_delete_transient', function () {
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_%' OR option_name LIKE '\_site\_transient\_%'" );
	} );

	add_filter( 'big_image_size_threshold', function () {
		return 3360;
	} );
	add_filter( 'body_class', 'ideapark_body_class' );
	add_filter( 'theme_mod_background_image', 'ideapark_disable_background_image', 10, 1 );
	add_filter( 'get_search_form', 'ideapark_search_form', 10 );
	add_filter( 'excerpt_more', 'ideapark_excerpt_more' );
	add_filter( 'excerpt_length', 'ideapark_custom_excerpt_length', 100 );
	add_filter( 'nav_menu_link_attributes', 'ideapark_menu_link_hash_fix' );
	add_filter( 'wp_kses_allowed_html', 'ideapark_add_allowed_tags', 100, 2 );
	add_filter( 'rwmb_meta_boxes', 'ideapark_custom_meta_boxes', 100 );
	add_filter( 'ideapark_fonts_theme_font', 'IDEAPARK_font' );
	add_filter( 'comment_form_fields', 'ideapark_comment_form_fields', 999 );
	add_filter( 'get_the_generator_html', 'ideapark_generator_tag', 10, 2 );
	add_filter( 'get_the_generator_xhtml', 'ideapark_generator_tag', 10, 2 );

}

add_action( 'after_setup_theme', 'ideapark_init_filesystem', 0 );
add_action( 'after_setup_theme', 'ideapark_check_version', 1 );
add_action( 'after_setup_theme', 'ideapark_setup' );