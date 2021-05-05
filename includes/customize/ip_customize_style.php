<?php

function ideapark_theme_colors() {
	return [
		'text_color'       => $text_color = ideapark_mod_hex_color_norm( 'text_color', '#000000' ),
		'text_color_light' => ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.62 ),
		'background_color' => ideapark_mod_hex_color_norm( 'background_color', '#f3f3f3' ),
		'accent_color'     => ideapark_mod_hex_color_norm( 'accent_color', '#E4C1B1' ),
	];
}

function ideapark_customize_css( $is_return_value = false ) {

	$custom_css = '';

	/**
	 * @var $text_color            string
	 * @var $text_color_light      string
	 * @var $background_color      string
	 * @var $accent_color          string
	 */
	extract( ideapark_theme_colors() );

	$page_header_bg_image = [
		'default'      => ideapark_mod( 'header_image__url' ),
		'page'         => ideapark_mod( 'header_bgimage_page__url' ),
		'post'         => ideapark_mod( 'header_bgimage_post__url' ),
		'wc'           => ideapark_mod( 'header_bgimage_wc__url' ),
		'product-page' => ideapark_mod( 'header_bgimage_product_page__url' ),
		'product-list' => ideapark_mod( 'header_bgimage_product_list__url' ),
	];

	$page_header_color = [
		'default'      => ideapark_mod( 'header_text_color' ),
		'page'         => ideapark_mod( 'header_color_page' ),
		'post'         => ideapark_mod( 'header_color_post' ),
		'wc'           => ideapark_mod( 'header_color_wc' ),
		'product-page' => ideapark_mod( 'header_color_product_page' ),
		'product-list' => ideapark_mod( 'header_color_product_list' ),
	];

	$page_header_bg_color = [
		'default'      => ideapark_mod( 'header_background_color' ),
		'page'         => ideapark_mod( 'header_bgcolor_page' ),
		'post'         => ideapark_mod( 'header_bgcolor_post' ),
		'wc'           => ideapark_mod( 'header_bgcolor_wc' ),
		'product-page' => ideapark_mod( 'header_bgcolor_product_page' ),
		'product-list' => ideapark_mod( 'header_bgcolor_product_list' ),
	];

	$page_header_bg_type = [
		'default'      => ideapark_mod( 'header_background_type' ),
		'page'         => ideapark_mod( 'header_bgtype_page' ),
		'post'         => ideapark_mod( 'header_bgtype_post' ),
		'wc'           => ideapark_mod( 'header_bgtype_wc' ),
		'product-page' => ideapark_mod( 'header_bgtype_product_page' ),
		'product-list' => ideapark_mod( 'header_bgtype_product_list' ),
	];

	$is_retina = ideapark_mod( 'header_background_retina' );

	$page_header_bg_retina_size = [
		'default'      => $is_retina && ideapark_mod( 'header_image__width' ) && ideapark_mod( 'header_image__height' ) ? ( round( ideapark_mod( 'header_image__width' ) / 2 ) . 'px ' . round( ideapark_mod( 'header_image__height' ) / 2 ) . 'px' ) : '',
		'page'         => $is_retina && ideapark_mod( 'header_bgimage_page__width' ) && ideapark_mod( 'header_bgimage_page__height' ) ? ( round( ideapark_mod( 'header_bgimage_page__width' ) / 2 ) . 'px ' . round( ideapark_mod( 'header_bgimage_page__height' ) / 2 ) . 'px' ) : '',
		'post'         => $is_retina && ideapark_mod( 'header_bgimage_post__width' ) && ideapark_mod( 'header_bgimage_post__height' ) ? ( round( ideapark_mod( 'header_bgimage_post__width' ) / 2 ) . 'px ' . round( ideapark_mod( 'header_bgimage_post__height' ) / 2 ) . 'px' ) : '',
		'wc'           => $is_retina && ideapark_mod( 'header_bgimage_wc__width' ) && ideapark_mod( 'header_bgimage_wc__height' ) ? ( round( ideapark_mod( 'header_bgimage_wc__width' ) / 2 ) . 'px ' . round( ideapark_mod( 'header_bgimage_wc__height' ) / 2 ) . 'px' ) : '',
		'product-page' => $is_retina && ideapark_mod( 'header_bgimage_product_page__width' ) && ideapark_mod( 'header_bgimage_product_page__height' ) ? ( round( ideapark_mod( 'header_bgimage_product_page__width' ) / 2 ) . 'px ' . round( ideapark_mod( 'header_bgimage_product_page__height' ) / 2 ) . 'px' ) : '',
		'product-list' => $is_retina && ideapark_mod( 'header_bgimage_product_list__width' ) && ideapark_mod( 'header_bgimage_product_list__height' ) ? ( round( ideapark_mod( 'header_bgimage_product_list__width' ) / 2 ) . 'px ' . round( ideapark_mod( 'header_bgimage_product_list__height' ) / 2 ) . 'px' ) : '',
	];

	$header_bg = '';

	foreach ( $page_header_bg_image as $key => $val_bg ) {
		$val_text_color = $page_header_color[ $key ];
		$val_color      = $page_header_bg_color[ $key ];
		$val_type       = $page_header_bg_type[ $key ];
		$val_size       = $page_header_bg_retina_size[ $key ];
		$is_val_size    = ( $val_bg && ( $val_type == 'repeat' || $val_type == '' && $page_header_bg_type['default'] == 'repeat' ) && $val_size );
		if ( $val_color || $val_bg || $val_type == 'cover' || $is_val_size ) {
			$header_bg .= '
.c-page-header--' . $key . ' {
	' . ( $val_text_color ? 'color: ' . esc_attr( $val_text_color ) . ';' : '' ) . '
	' . ( $val_color ? 'background-color: ' . esc_attr( $val_color ) . ';' : '' ) . '
	' . ( $val_bg ? 'background-image: url("' . esc_url( $val_bg ) . '");' : '' ) . '
	' . ( $val_type == 'cover' ? 'background-repeat: no-repeat; background-size: cover; background-position: center;' : '' ) . '
	' . ( $is_val_size ? ( 'background-repeat: repeat; background-position: center; background-size: ' . $val_size . ';' ) : '' ) . '
}';
		}
	}

	$lang_postfix = ideapark_get_lang_postfix();

	$custom_css .= '
	<style> 
		:root {
			--text-color: ' . esc_attr( $text_color ) . ';
			--text-color-light: ' . ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.62 ) . ';
			--text-color-extra-light: ' . ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.13 ) . ';
			--text-color-tr: ' . ideapark_hex_to_rgba( $text_color, 0.15 ) . ';
			--background-color: ' . esc_attr( $background_color ) . ';
			--background-color-light: ' . ideapark_hex_to_rgb_overlay( '#FFFFFF', $background_color, 0.5 ) . ';
			--background-color-dark: ' . ideapark_hex_to_rgb_overlay( $background_color, '#000000', 0.03 ) . ';
			--accent-color: ' . esc_attr( $accent_color ) . ';
			--accent-color-dark: ' . ideapark_hex_to_rgb_overlay( $accent_color, '#000000', 0.1 ) . ';
			--font-text: "' . esc_attr( ideapark_mod( 'theme_font_text' . $lang_postfix ) ) . '", sans-serif;
			--font-header: "' . esc_attr( ideapark_mod( 'theme_font_header' . $lang_postfix ) ) . '", sans-serif;
			--logo-size: ' . esc_attr( round( ideapark_mod( 'logo_size' ) ) ) . 'px;
			--logo-size-mobile: ' . esc_attr( round( ideapark_mod( 'logo_size_mobile' ) ) ) . 'px;
			--shadow-color-desktop: ' . ideapark_hex_to_rgba( ideapark_mod_hex_color_norm( 'shadow_color_desktop', '#FFFFFF' ), 0.95 ) . ';
			--shadow-color-mobile: ' . ideapark_hex_to_rgba( ideapark_mod_hex_color_norm( 'shadow_color_mobile', $text_color ), 0.95 ) . ';
			--badge-bgcolor-outofstock: ' . ideapark_mod_hex_color_norm( 'outofstock_badge_color', $text_color ) . ';
			--custom-transform-transition: visibility 0.5s cubic-bezier(0.86, 0, 0.07, 1), opacity 0.5s cubic-bezier(0.86, 0, 0.07, 1), transform 0.5s cubic-bezier(0.86, 0, 0.07, 1), box-shadow 0.5s cubic-bezier(0.86, 0, 0.07, 1);
			--opacity-transition: opacity 0.3s linear, visibility 0.3s linear;
			--opacity-transform-transition: opacity 0.3s linear, visibility 0.3s linear, transform 0.3s ease-out, box-shadow 0.3s ease-out;
			--hover-transition: opacity 0.3s linear, visibility 0.3s linear, color 0.15s linear, border-color 0.15s linear, background-color 0.15s linear, box-shadow 0.15s linear;
			--star-rating-image: url("data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="14" height="10" fill="' . ideapark_mod_hex_color_norm( 'star_rating_color', $text_color ) . '" xmlns="http://www.w3.org/2000/svg"><path d="M8.956 9.782c.05.153-.132.28-.27.186L5.5 7.798l-3.19 2.168c-.137.093-.32-.033-.269-.187l1.178-3.563L.07 3.99c-.135-.095-.065-.3.103-.302l3.916-.032L5.335.114c.053-.152.28-.152.333 0L6.91 3.658l3.916.035c.168.001.238.206.103.302L7.78 6.217l1.175 3.565z"/></svg>' ) . '");
			--icon-user: "\f139" /* ip-user */;
			--icon-close-small: "\f10d" /* ip-close-small */;
			--icon-check: "\f10a" /* ip-check */;
			--icon-select: "\f111" /* ip-down_arrow */;
			--icon-select-bold: "\f144" /* ip-y-down */;
			--icon-romb: "\f131" /* ip-romb */;
			--icon-calendar: "\f105" /* ip-calendar */;
			--icon-li: "\f110" /* ip-dot */;
			--icon-quote: "\f12d" /* ip-quote */;
			--icon-submenu: "\f125" /* ip-menu-right */;
			--icon-depth: "\f146" /* ip-z-depth */;
		}
		
		.c-badge__list {
			--badge-bgcolor-featured: ' . ideapark_mod_hex_color_norm( 'featured_badge_color', $text_color ) . ';
			--badge-bgcolor-new: ' . ideapark_mod_hex_color_norm( 'new_badge_color', $text_color ) . ';
			--badge-bgcolor-sale: ' . ideapark_mod_hex_color_norm( 'sale_badge_color', $text_color ) . ';
		}
		
		.c-to-top-button {
			--to-top-button-color: ' . ideapark_mod_hex_color_norm( 'to_top_button_color' ) . ';
		}
		
		.c-top-menu {
			--top-menu-submenu-color: ' . ideapark_mod_hex_color_norm( 'top_menu_submenu_color', $text_color ) . ';
			--top-menu-submenu-bg-color: ' . ideapark_mod_hex_color_norm( 'top_menu_submenu_bg_color', '#FFFFFF' ) . ';
			--top_menu_submenu_accent_color: ' . ideapark_mod_hex_color_norm( 'top_menu_submenu_accent_color', $accent_color ) . ';
		}
		
		.c-product-grid__item {
			--font-size: ' . esc_attr( ideapark_mod( 'product_font_size' ) ) . 'px;
			--font-size-mobile: ' . esc_attr( round( ideapark_mod( 'product_font_size' ) * 18 / 20 ) ) . 'px;
			--font-size-compact: ' . esc_attr( ideapark_mod( 'product_font_size_compact' ) ) . 'px;
			--font-letter-spacing: ' . esc_attr( ideapark_mod( 'product_font_letterspacing' ) ) . 'em;
		}
		
		.c-header__outer {
			--top-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ) ) . ';
			--top-color-light: ' . ideapark_hex_to_rgb_overlay( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ), ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ), 0.62 ) . ';
			--top-accent-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'header_top_accent_color', '#E4C1B1' ) ) . ';
			--top-background-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ) ) . ';
			--top-color-hr: ' . ideapark_hex_to_rgb_overlay( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ), ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ), ideapark_hex_lighting( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ) ) > 200 ? 0.1 : 0.15 ) . '; 
			--top-color-input: ' . ideapark_hex_to_rgb_overlay( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ), ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ), 0.4 ) . ';
			
			--header-color-light: ' . ideapark_mod_hex_color_norm( 'header_color_light', $background_color ) . ';
			--header-color-dark: ' . ideapark_mod_hex_color_norm( 'header_color_dark', $text_color ) . ';
			
			--header-color-menu: ' . ideapark_mod_hex_color_norm( 'header_color_menu', $text_color ) . ';
			--header-color-bg-menu: ' . ideapark_mod_hex_color_norm( 'header_bg_color_menu', '#FFFFFF' ) . ';
			
			--header-color-mobile: ' . ideapark_mod_hex_color_norm( 'mobile_header_color', $text_color ) . ';
			--header-color-mobile-tr: ' . ideapark_mod_hex_color_norm( 'mobile_header_color_tr', '#FFFFFF' ) . ';
			--header-color-mobile-tr-neg: ' . ( ideapark_hex_lighting( ideapark_mod_hex_color_norm( 'mobile_header_color_tr', '#FFFFFF' ) ) > 128 ? $text_color : '#FFFFFF' ) . ';
			--header-color-bg-mobile: ' . ideapark_mod_hex_color_norm( 'mobile_header_background_color', '#FFFFFF' ) . ';
			
			--sticky-menu-color: ' . ideapark_mod_hex_color_norm( 'sticky_menu_color', in_array( ideapark_mod( 'header_type' ), [ 'header-type-4', 'header-type-5' ] ) ? ideapark_mod_hex_color_norm( 'header_color_menu', $text_color ) : $text_color ) . ';
			--sticky-menu-bg-color: ' . ideapark_mod_hex_color_norm( 'sticky_menu_bg_color', in_array( ideapark_mod( 'header_type' ), [ 'header-type-4', 'header-type-5' ] ) ? ideapark_mod_hex_color_norm( 'header_bg_color_menu', '#FFFFFF' ) : '#FFFFFF' ) . ';
		}
		
		.c-product__slider-item {
			--image-background-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'product_image_background_color' ) ) . ';
		}
		
		.woocommerce-store-notice {
			--store-notice-color: ' . ideapark_mod_hex_color_norm( 'store_notice_color' ) . ';
			--store-notice-background-color: ' . ideapark_mod_hex_color_norm( 'store_notice_background_color' ) . ';
		}
		
		' . $header_bg . '
			
	</style>';

	$custom_css = preg_replace( '~[\r\n]~', '', preg_replace( '~[\t\s]+~', ' ', str_replace( [
		'<style>',
		'</style>'
	], [ '', '' ], $custom_css ) ) );

	if ( $custom_css ) {
		if ( $is_return_value ) {
			return $custom_css;
		} else {
			wp_add_inline_style( 'ideapark-core', $custom_css );
		}
	}
}

function ideapark_uniord( $u ) {
	$k  = mb_convert_encoding( $u, 'UCS-2LE', 'UTF-8' );
	$k1 = ord( substr( $k, 0, 1 ) );
	$k2 = ord( substr( $k, 1, 1 ) );

	return $k2 * 256 + $k1;
}

function ideapark_b64enc( $input ) {

	$keyStr = "ABCDEFGHIJKLMNOP" .
	          "QRSTUVWXYZabcdef" .
	          "ghijklmnopqrstuv" .
	          "wxyz0123456789+/" .
	          "=";

	$output = "";
	$i      = 0;

	do {
		$chr1 = ord( substr( $input, $i ++, 1 ) );
		$chr2 = $i < strlen( $input ) ? ord( substr( $input, $i ++, 1 ) ) : null;
		$chr3 = $i < strlen( $input ) ? ord( substr( $input, $i ++, 1 ) ) : null;

		$enc1 = $chr1 >> 2;
		$enc2 = ( ( $chr1 & 3 ) << 4 ) | ( $chr2 >> 4 );
		$enc3 = ( ( $chr2 & 15 ) << 2 ) | ( $chr3 >> 6 );
		$enc4 = $chr3 & 63;

		if ( $chr2 === null ) {
			$enc3 = $enc4 = 64;
		} else if ( $chr3 === null ) {
			$enc4 = 64;
		}

		$output = $output .
		          substr( $keyStr, $enc1, 1 ) .
		          substr( $keyStr, $enc2, 1 ) .
		          substr( $keyStr, $enc3, 1 ) .
		          substr( $keyStr, $enc4, 1 );
		$chr1   = $chr2 = $chr3 = "";
		$enc1   = $enc2 = $enc3 = $enc4 = "";
	} while ( $i < strlen( $input ) );

	return $output;
}