<?php

if ( ! function_exists( 'ideapark_setup_woocommerce' ) ) {
	function ideapark_setup_woocommerce() {

		if ( ( ideapark_is_requset( 'frontend' ) || ideapark_is_elementor_preview() ) && ideapark_woocommerce_on() ) {

			if ( ideapark_is_elementor_preview() ) {
				WC()->frontend_includes();
			}

			/* All WC pages */
			ideapark_ra( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			ideapark_rf( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
			ideapark_ra( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
			ideapark_ra( 'woocommerce_before_lost_password_form', 'woocommerce_output_all_notices', 10 );
			ideapark_ra( 'woocommerce_before_reset_password_form', 'woocommerce_output_all_notices', 10 );
			ideapark_ra( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );
			if ( ! ideapark_mod( 'product_preview_rating' ) ) {
				ideapark_ra( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			}

			add_action( 'woocommerce_after_page_header', 'woocommerce_output_all_notices', 10 );

			/* Products loop */

			ideapark_ra( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			ideapark_ra( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			ideapark_ra( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			ideapark_ra( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
			ideapark_ra( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			ideapark_ra( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
			ideapark_ra( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			ideapark_ra( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

			add_action( 'woocommerce_before_shop_loop', 'ideapark_woocommerce_search_form', 30 );

			add_action( 'woocommerce_before_shop_loop_item_title', function () { ?><div class="c-product-grid__badges c-badge__list"><?php }, 1 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'ideapark_woocommerce_show_product_loop_badges', 2 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 3 );
			add_action( 'woocommerce_before_shop_loop_item_title', function () { ?></div><!-- .c-product-grid__badges --><?php }, 4 );
			add_action( 'woocommerce_before_shop_loop_item_title', function () { ?><div class="c-product-grid__thumb-wrap"><?php }, 6 );
			if ( ideapark_mod( 'outofstock_badge_text' ) ) {
				add_action( 'woocommerce_before_shop_loop_item_title', 'ideapark_stock_badge', 7 );
			}
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'ideapark_loop_product_thumbnail', 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'ideapark_template_product_buttons', 20 );
			add_action( 'woocommerce_before_shop_loop_item_title', function () { ?></div><!-- .c-product-grid__thumb-wrap --><?php }, 50 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 55 );
			add_action( 'woocommerce_before_shop_loop_item_title', function () { ?><div class="c-product-grid__details"><div class="c-product-grid__title-wrap"><?php }, 100 );
			add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
			add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
			add_action( 'woocommerce_after_shop_loop_item_title', 'ideapark_template_short_description', 4 );
		add_action( 'woocommerce_after_shop_loop_item_title', function () { ?></div>
			<!-- .c-product-grid__title-wrap -->
			<div class="c-product-grid__price-wrap"><?php }, 50 );
			if ( ideapark_mod( 'product_brand_attribute' ) && taxonomy_exists( ideapark_mod( 'product_brand_attribute' ) ) ) {
				if ( ideapark_mod( 'show_product_grid_brand' ) ) {
					add_action( 'woocommerce_after_shop_loop_item_title', 'ideapark_template_brand', 52 );
				}
				if ( ideapark_mod( 'show_product_page_brand' ) ) {
					add_action( 'woocommerce_product_meta_end', 'ideapark_template_brand_meta' );
				}
			}
			add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 55 );
			add_action( 'woocommerce_after_shop_loop_item_title', function () { ?></div><!-- .c-product-grid__price-wrap --></div><!-- .c-product-grid__details --><?php }, 60 );

			add_action( 'woocommerce_archive_description', function () { ?><div class="<?php if (ideapark_mod( 'category_description_position' ) == 'below') { ?>l-section l-section--container <?php } ?>entry-content c-product-grid__cat-desc c-product-grid__cat-desc--<?php echo esc_attr( ideapark_mod( 'category_description_position' ) ); ?>"><?php }, 9 );
			add_action( 'woocommerce_archive_description', function () { ?></div><?php }, 11 );


			/* Product page */
			add_filter( 'woocommerce_post_class', function ( $classes ) {
				if ( is_product() && ! ideapark_mod( '_is_product_loop' ) && ! ideapark_mod( '_is_product_set' ) ) {
					ideapark_mod_set_temp( '_is_product_set', true );
					$ip_classes = [ 'c-product', 'c-product--' . ideapark_mod( 'product_page_layout' ) ];
					switch ( ideapark_mod( 'product_page_layout' ) ) {
						case 'layout-1':
						case 'layout-2':
							$ip_classes[] = 'l-section';
							$ip_classes[] = 'l-section--container';
							break;

						case 'layout-3':
						case 'layout-4':
							$ip_classes[] = 'l-section';
							$ip_classes[] = 'l-section--container-wide';
							break;
					}

					return array_merge( $ip_classes, $classes );
				} else {
					return $classes;
				}
			}, 100 );
			if ( ideapark_mod( 'hide_variable_price_range' ) ) {
				add_filter( 'woocommerce_get_price_html', function ( $price, $product ) {
					if ( $product->is_type( 'variable' ) && is_product() ) {
						return '';
					}

					return $price;
				}, 10, 2 );

				add_filter( 'woocommerce_show_variation_price', '__return_true' );
			}
			add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__gallery"><?php }, 5 );
			add_action( 'woocommerce_before_single_product_summary', function () { ?></div><!-- .c-product__gallery --><?php }, 50 );
			add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-badge__list c-product__badges"><?php }, 8 );
			add_action( 'woocommerce_before_single_product_summary', 'ideapark_woocommerce_show_product_loop_badges', 9 );
			add_action( 'woocommerce_before_single_product_summary', function () { ?></div><!-- .c-product__badges --><?php }, 12 );
			ideapark_ra( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			ideapark_ra( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 19 );

			if ( ! IDEAPARK_IS_AJAX_QUICKVIEW ) {
				switch ( ideapark_mod( 'product_page_layout' ) ) {
					case 'layout-1':
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__wrap c-product__wrap--layout-1"><?php }, 1 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__wrap --><?php }, 15 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-1"><div class="js-sticky-sidebar-nearby"><?php }, 2 );
						add_action( 'woocommerce_before_single_product_summary', 'woocommerce_output_product_data_tabs', 50 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?></div><!-- .c-product__col-1 --></div><!-- .js-sticky-sidebar-nearby --><?php }, 99 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-2"><div class="js-sticky-sidebar"><?php }, 100 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__col-2 --></div><!-- .js-sticky-sidebar --><?php }, 10 );

						break;
					case 'layout-2':
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__wrap c-product__wrap--layout-2"><?php }, 1 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__wrap --><?php }, 15 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-1"><div class="js-sticky-sidebar-nearby"><?php }, 2 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?></div><!-- .c-product__col-1 --></div><!-- .js-sticky-sidebar-nearby --><?php }, 99 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-2"><div class="js-sticky-sidebar"><?php }, 100 );
						add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 55 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__col-2 --></div><!-- .js-sticky-sidebar --><?php }, 10 );

						break;
					case 'layout-3':
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="js-sticky-sidebar-nearby"><?php }, 1 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .js-sticky-sidebar-nearby --><?php }, 16 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__wrap c-product__wrap--layout-3"><?php }, 2 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__wrap --><?php }, 15 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-2"><?php }, 3 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?></div><!-- .c-product__col-2 --><?php }, 99 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-1"><div class="js-sticky-sidebar"><?php }, 100 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__col-1 --></div><!-- .js-sticky-sidebar --><?php }, 10 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?><div class="c-product__col-3"><?php }, 11 );
						add_action( 'woocommerce_after_single_product_summary', 'ideapark_tabs_list', 12 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__col-3 --><?php }, 14 );
						add_action( 'woocommerce_after_single_product_summary', 'ideapark_tab_reviews', 15 );
						ideapark_ra( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
						add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_meta', 13 );
						break;
					case 'layout-4':
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__wrap c-product__wrap--layout-3"><?php }, 2 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__wrap --><?php }, 15 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-1"><?php }, 3 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?></div><!-- .c-product__col-1 --><?php }, 99 );
						add_action( 'woocommerce_before_single_product_summary', function () { ?><div class="c-product__col-2"><?php }, 100 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__col-2 --><?php }, 10 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?><div class="c-product__col-3"><?php }, 11 );
						add_action( 'woocommerce_after_single_product_summary', 'ideapark_tabs_list', 12 );
						add_action( 'woocommerce_after_single_product_summary', function () { ?></div><!-- .c-product__col-3 --><?php }, 14 );
						add_action( 'woocommerce_after_single_product_summary', 'ideapark_tab_reviews', 15 );
						ideapark_ra( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
						add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_meta', 13 );
						break;
				}
			} else {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_product_link_open', 4 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_product_link_close', 6 );
			}

			ideapark_ra( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 8 );
			add_action( 'woocommerce_single_product_summary', function () { ?><div class="c-product__atc-wrap"><?php }, 29 );
			add_action( 'woocommerce_single_product_summary', function () { ?></div><!-- .c-product__atc-wra --><?php }, 31 );
			add_action( 'woocommerce_single_product_summary', 'ideapark_product_wishlist', 35 );

			add_action( 'woocommerce_share', 'ideapark_product_share' );

			/* Cart page */
			ideapark_ra( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
			add_action( 'woocommerce_before_cart_totals', 'woocommerce_checkout_coupon_form', 10 );

			/* Checkout page */
			ideapark_ra( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10 );
			ideapark_ra( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
			ideapark_ra( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
			add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_coupon_form', 10 );

			/* All Account pages */
			ideapark_ra( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );


			/** Snippets **/

			if ( ideapark_mod( 'store_notice_button_text' ) ) {
				add_filter( "woocommerce_demo_store", function ( $notice ) {
					return preg_replace( "~(dismiss-link\">)([^>]+)(<)~", "\\1" . esc_html( ideapark_mod( 'store_notice_button_text' ) ) . "\\3", $notice );
				} );
			}

		}
	}
}

if ( ! function_exists( 'ideapark_tabs_list' ) ) {
	function ideapark_tabs_list() {
		$product_tabs = apply_filters( 'woocommerce_product_tabs', [] );
		if ( ! empty( $product_tabs ) ) {
			foreach ( $product_tabs as $key => $product_tab ) {
				if ( $key != 'reviews' ) { ?>
					<div
						class="c-product__tabs-header"><?php echo wp_kses( apply_filters( 'woocommerce_product_' . $key . '_tab_title', preg_replace( '~\((\d+)\)~', '<sup class="c-product__rev-counter">\\1</sup>', $product_tab['title'] ), $key ), [ 'sup' => [ 'class' => true ] ] ); ?></div>
					<div
						class="c-product__tabs-panel woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel <?php if ( $key == 'description' ) { ?>entry-content<?php } ?> wc-tab visible"
						id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
						aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
					</div>
				<?php }
			}
			do_action( 'woocommerce_product_after_tabs' );
		}
	}
}

if ( ! function_exists( 'ideapark_tab_reviews' ) ) {
	function ideapark_tab_reviews() {
		$product_tabs = apply_filters( 'woocommerce_product_tabs', [] );
		if ( ! empty( $product_tabs ) ) {
			foreach ( $product_tabs as $key => $product_tab ) {
				if ( $key == 'reviews' ) { ?>
					<div class="c-product__col-2-center">
						<div
							class="c-product__tabs-panel woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel <?php if ( $key == 'description' ) { ?>entry-content<?php } ?> wc-tab visible"
							id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
							aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
							<?php
							if ( isset( $product_tab['callback'] ) ) {
								call_user_func( $product_tab['callback'], $key, $product_tab );
							}
							?>
						</div>
					</div>
				<?php }
			}
			do_action( 'woocommerce_product_after_tabs' );
		}
	}
}

if ( ! function_exists( 'ideapark_loop_product_thumbnail' ) ) {
	function ideapark_loop_product_thumbnail() {
		global $product;
		if ( $product ) {

			$image_size = 'woocommerce_thumbnail';

			if ( ideapark_mod( '_product_layout' ) == '3-per-row' ) {
				$image_size = 'medium';
			}

			if ( ideapark_mod( 'grid_image_fit' ) == 'contain' ) {
				if ( ideapark_mod( '_product_layout' ) == 'compact' ) {
					$image_size = 'ideapark-product-thumbnail-compact';
				} else {
					$image_size = 'medium';
				}
			}

			$image_size = apply_filters( 'single_product_archive_thumbnail_size', $image_size );
			echo ideapark_wrap( $product->get_image( $image_size, [ 'class' => 'c-product-grid__thumb c-product-grid__thumb--' . ideapark_mod( 'grid_image_fit' ) ] ) );
		}
	}
}

if ( ! function_exists( 'ideapark_template_product_buttons' ) ) {
	function ideapark_template_product_buttons() {
		wc_get_template( 'global/product-buttons.php' );
	}
}

if ( ! function_exists( 'ideapark_template_short_description' ) ) {
	function ideapark_template_short_description() {
		wc_get_template( 'loop/short-description.php' );
	}
}

if ( ! function_exists( 'ideapark_template_brand' ) ) {
	function ideapark_template_brand() {
		wc_get_template( 'loop/brand.php' );
	}
}

if ( ! function_exists( 'ideapark_template_brand_meta' ) ) {
	function ideapark_template_brand_meta() {
		wc_get_template( 'loop/brand_meta.php' );
	}
}

if ( ! function_exists( 'ideapark_cart_info' ) ) {
	function ideapark_cart_info() {
		global $woocommerce;

		if ( isset( $woocommerce->cart ) ) {
			$cart_total = $woocommerce->cart->get_cart_total();
			$cart_count = $woocommerce->cart->get_cart_contents_count();

			return '<span class="js-cart-info">'
			       . ( ! $woocommerce->cart->is_empty() ? ideapark_wrap( esc_html( $cart_count ), '<span class="c-header__cart-count js-cart-count">', '</span>' ) : '' )
			       . ( ! $woocommerce->cart->is_empty() ? ideapark_wrap( $cart_total, '<span class="c-header__cart-sum">', '</span>' ) : '' ) .
			       '</span>';
		}
	}
}

if ( ! function_exists( 'ideapark_wishlist_info' ) ) {
	function ideapark_wishlist_info() {

		if ( ideapark_mod( 'wishlist_page' ) ) {
			$count = sizeof( ideapark_wishlist()->ids() );
		} else {
			$count = 0;
		}

		return '<span class="js-wishlist-info">'
		       . ( $count ? ideapark_wrap( $count, '<span class="c-header__cart-count">', '</span>' ) : '' ) .
		       '</span>';
	}
}

if ( ! function_exists( 'ideapark_header_add_to_cart_fragment' ) ) {
	function ideapark_header_add_to_cart_fragment( $fragments ) {
		$fragments['.js-cart-info']     = ideapark_cart_info();
		$fragments['.js-wishlist-info'] = ideapark_wishlist_info();
		ob_start();
		wc_print_notices();
		$fragments['ideapark_notice'] = ob_get_clean();

		return $fragments;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_show_product_loop_badges' ) ) {
	function ideapark_woocommerce_show_product_loop_badges() {
		/**
		 * @var $product WC_Product
		 **/
		global $product;

		if ( ideapark_mod( 'featured_badge_text' ) && $product->is_featured() ) {
			echo '<span class="c-badge c-badge--featured">' . esc_html( ideapark_mod( 'featured_badge_text' ) ) . '</span>';
		}

		$newness = (int) ideapark_mod( 'product_newness' );

		if ( ideapark_mod( 'new_badge_text' ) && $newness > 0 ) {
			$postdate      = get_the_time( 'Y-m-d' );
			$postdatestamp = strtotime( $postdate );
			if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {
				echo '<span class="c-badge c-badge--new">' . esc_html( ideapark_mod( 'new_badge_text' ) ) . '</span>';
			}
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_breadcrumbs' ) ) {
	function ideapark_woocommerce_breadcrumbs() {
		return [
			'delimiter'   => '',
			'wrap_before' => '<nav class="c-breadcrumbs"><ol class="c-breadcrumbs__list">',
			'wrap_after'  => '</ol></nav>',
			'before'      => '<li class="c-breadcrumbs__item">',
			'after'       => '</li>',
			'home'        => esc_html_x( 'Home', 'breadcrumb', 'woocommerce' ),
		];
	}
}

if ( ! function_exists( 'ideapark_woocommerce_account_menu_items' ) ) {
	function ideapark_woocommerce_account_menu_items( $items ) {
		unset( $items['customer-logout'] );

		return $items;
	}
}

if ( ! function_exists( 'ideapark_product_availability' ) ) {
	function ideapark_product_availability() {
		global $product;

		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			$availability = $product->get_availability();
			if ( $product->is_in_stock() ) {
				$availability_html = '<span class="c-stock c-stock--in-stock ' . esc_attr( $availability['class'] ) . '">' . ( $availability['availability'] ? esc_html( $availability['availability'] ) : esc_html__( 'In stock', 'luchiana' ) ) . '</span>';
			} else {
				$availability_html = '<span class="c-stock c-stock--out-of-stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</span>';
			}
		} else {
			$availability_html = '';
		}

		echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
	}
}

if ( ! function_exists( 'ideapark_cut_product_categories' ) ) {
	function ideapark_cut_product_categories( $links ) {
		if ( ideapark_woocommerce_on() && is_product() ) {
			$links = array_slice( $links, 0, 2 );
		}

		return $links;
	}
}

if ( ! function_exists( 'ideapark_remove_product_description_heading' ) ) {
	function ideapark_remove_product_description_heading() {
		return '';
	}
}

if ( ! function_exists( 'ideapark_woocommerce_search_form' ) ) {
	function ideapark_woocommerce_search_form() {
		if ( is_search() ) {
			echo '<div class="c-product-search-form">';
			get_search_form();
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_max_srcset_image_width_768' ) ) {
	function ideapark_woocommerce_max_srcset_image_width_768( $max_width, $size_array ) {
		return 768;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_max_srcset_image_width_360' ) ) {
	function ideapark_woocommerce_max_srcset_image_width_360( $max_width, $size_array ) {
		return 360;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_hide_uncategorized' ) ) {
	function ideapark_woocommerce_hide_uncategorized( $args ) {
		if ( ideapark_mod( 'hide_uncategorized' ) ) {
			$args['exclude'] = get_option( 'default_product_cat' );
			if ( ! empty( $args['include'] ) ) {
				$args['include'] = implode( ',', array_filter( explode( ',', $args['include'] ), function ( $var ) {
					return $var != get_option( 'default_product_cat' );
				} ) );
			}
		}

		return $args;
	}
}

if ( ! function_exists( 'ideapark_subcategory_archive_thumbnail_size' ) ) {
	function ideapark_subcategory_archive_thumbnail_size( $thumbnail_size ) {
		return 'woocommerce_gallery_thumbnail';
	}
}

if ( ! function_exists( 'ideapark_loop_add_to_cart_link' ) ) {
	function ideapark_loop_add_to_cart_link( $text, $product, $args ) {
		$text = preg_replace( '~(<a[^>]+>)~ui', '\\1<span class="c-product-grid__atc-text">', $text );
		$text = preg_replace( '~(</a>)~ui', '</span>' . '\\1', $text );
		if ( $product->get_type() == 'simple' ) {
			return preg_replace( '~(<a[^>]+>)~ui', '\\1<i class="ip-plus c-product-grid__atc-icon"></i>', $text );
		} else {
			return preg_replace( '~(</a>)~ui', '<i class="ip-button-more c-product-grid__atc-icon"></i>' . '\\1', $text );
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_gallery_image_size' ) ) {
	function ideapark_woocommerce_gallery_image_size( $size ) {
		return 'woocommerce_single';
	}
}

if ( ! function_exists( 'ideapark_get_filtered_term_product_counts' ) ) {
	function ideapark_get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type, $tax_query = null, $meta_query = null ) {
		global $wpdb;

		if ( $tax_query === null ) {
			$tax_query = WC_Query::get_main_tax_query();
		}

		if ( $meta_query === null ) {
			$meta_query = WC_Query::get_main_meta_query();
		}

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query.
		$query           = [];
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'"
		                  . $tax_query_sql['where'] . $meta_query_sql['where'] .
		                  'AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		if ( ! empty( WC_Query::$query_vars ) ) {
			$search = WC_Query::get_main_search_query_sql();
			if ( $search ) {
				$query['where'] .= ' AND ' . $search;
			}
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = [];
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			$results                      = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
			}
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}
}

if ( ! function_exists( 'ideapark_get_term_thumbnail' ) ) {
	$ideapark_get_term_thumbnail_cache = [];
	function ideapark_get_term_thumbnail( $term, $class = '' ) {
		/* @var $term WP_Term */
		global $ideapark_get_term_thumbnail_cache;
		if ( array_key_exists( $term->term_id, $ideapark_get_term_thumbnail_cache ) ) {
			return $ideapark_get_term_thumbnail_cache[ $term->term_id ];
		}
		$image = '';

		if ( $thumbnail_id = absint( get_term_meta( $term->term_id, 'ideapark_thumbnail_id', true ) ) ) {
			$image_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
			$image_alt = trim( strip_tags( $term->name ) );
			if ( empty( $image_alt ) ) {
				$image_alt = trim( strip_tags( get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) ) );
			}

			$image = '<span class="c-markers__wrap ' . ( $class ? esc_attr( $class ) : '' ) . ' js-marker"><span class="c-markers__title">' . esc_html( $image_alt ) . '</span><img class="c-markers__icon" src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr( $image_alt ) . '"></span>';
		}

		$ideapark_get_term_thumbnail_cache[ $term->term_id ] = $image;

		return $image;
	}
}

if ( ! function_exists( 'ideapark_single_variation' ) ) {
	function ideapark_single_variation() {
		echo '<div class="c-variation__single-info single_variation">';
		woocommerce_template_loop_price();
		echo '</div>';
		echo '<div class="c-variation__single-price">';
		woocommerce_template_loop_price();
		echo '</div>';
	}
}

if ( ! function_exists( 'ideapark_woocommerce_loop_add_to_cart_args' ) ) {
	function ideapark_woocommerce_loop_add_to_cart_args( $args ) {

		$args['class'] = 'h-cb c-product-grid__atc ' . $args['class'];

		return $args;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_available_variation' ) ) {
	function ideapark_woocommerce_available_variation( $params, $instance, $variation ) {

		$image = wp_get_attachment_image_src( $params['image_id'], 'woocommerce_single' );
		if ( ! empty( $image ) ) {
			$params['image']['gallery_thumbnail_src'] = $image[0];
		}

		return $params;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_pagination_args' ) ) {
	function ideapark_woocommerce_pagination_args( $args ) {
		$args['prev_text'] = ideapark_pagination_prev();
		$args['next_text'] = ideapark_pagination_next();
		$args['end_size']  = 1;
		$args['mid_size']  = 1;

		return $args;
	}
}

if ( ! function_exists( 'ideapark_ajax_product_images' ) ) {
	function ideapark_ajax_product_images() {
		ob_start();

		if ( isset( $_REQUEST['product_id'] ) && ( $product_id = absint( $_REQUEST['product_id'] ) ) ) {
			$variation_id   = isset( $_REQUEST['variation_id'] ) ? absint( $_REQUEST['variation_id'] ) : 0;
			$index          = isset( $_REQUEST['index'] ) ? absint( $_REQUEST['index'] ) : 0;
			$product_images = ideapark_product_images( $product_id, $variation_id );
			$images         = [];
			foreach ( $product_images as $_index => $image ) {
				if ( ! empty( $image['video_url'] ) ) {
					if ( $_index == $index ) {
						add_filter( 'oembed_result', function ( $html ) {
							return str_replace( "?feature=oembed", "?feature=oembed&autoplay=1", $html );
						} );
					}
					$images[] = [
						'html' => ideapark_wrap( wp_oembed_get( $image['video_url'] ), '<div class="pswp__video-wrap">', '</div>' )
					];
				} else {
					$images[] = [
						'src' => $image['full'][0],
						'w'   => $image['full'][1],
						'h'   => $image['full'][2],
					];
				}
			}

			ob_end_clean();
			wp_send_json( [ 'images' => $images ] );
		}
		ob_end_clean();
	}
}

if ( ! function_exists( 'ideapark_ajax_product' ) ) {
	function ideapark_ajax_product() {
		global $woocommerce, $product, $post;

		if (
			ideapark_woocommerce_on() &&
			ideapark_mod( 'shop_modal' ) &&
			! empty( $_POST['product_id'] ) &&
			( $product_id = (int) $_POST['product_id'] ) &&
			( $product = wc_get_product( $_POST['product_id'] ) ) &&
			( $post = get_post( $_POST['product_id'] ) )
		) {
			setup_postdata( $post );
			wc_get_template_part( 'content', 'quickview' );
			wp_reset_postdata();
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_before_widget_product_list' ) ) {
	function ideapark_woocommerce_before_widget_product_list( $content ) {
		return str_replace( 'product_list_widget', 'c-product-list-widget', $content );
	}
}

if ( ! function_exists( 'ideapark_wp_scrset_on' ) ) {
	function ideapark_wp_scrset_on( $name = '' ) {
		$f = 'add_filter';
		$n = 'wp_calculate_image_' . 'srcset';
		call_user_func( $f, $n, 'ideapark_woocommerce_srcset' . ( $name ? '_' : '' ) . $name, 10, 5 );
	}
}

if ( ! function_exists( 'ideapark_wp_scrset_off' ) ) {
	function ideapark_wp_scrset_off( $name = '' ) {
		$f = 'remove_filter';
		$n = 'wp_calculate_image_' . 'srcset';
		call_user_func( $f, $n, 'ideapark_woocommerce_srcset' . ( $name ? '_' : '' ) . $name, 10 );
	}
}

if ( ! function_exists( 'ideapark_wp_max_scrset_on' ) ) {
	function ideapark_wp_max_scrset_on( $name = '' ) {
		$f = 'add_filter';
		$n = 'max_srcset_image_' . 'width';
		call_user_func( $f, $n, 'ideapark_woocommerce_max_srcset_image_width' . ( $name ? '_' : '' ) . $name, 10, 2 );
	}
}

if ( ! function_exists( 'ideapark_wp_max_scrset_off' ) ) {
	function ideapark_wp_max_scrset_off( $name = '' ) {
		$f = 'remove_filter';
		$n = 'max_srcset_image_' . 'width';
		call_user_func( $f, $n, 'ideapark_woocommerce_max_srcset_image_width' . ( $name ? '_' : '' ) . $name, 10 );
	}
}

if ( ! function_exists( 'ideapark_woocommerce_srcset_grid' ) ) {
	function ideapark_woocommerce_srcset_grid( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		foreach ( $sources as $width => $data ) {
			if ( $width != $size_array[0] && $width != $size_array[0] * 2 ) {
				unset( $sources[ $width ] );
			}
		}

		return $sources;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_srcset_retina' ) ) {
	function ideapark_woocommerce_srcset_retina( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		foreach ( $sources as $width => $data ) {
			if ( $width != $size_array[0] && $width != $size_array[0] * 2 ) {
				unset( $sources[ $width ] );
			}
		}

		return $sources;
	}
}

if ( ! function_exists( 'ideapark_product_images' ) ) {
	function ideapark_product_images( $product_id = 0, $variation_id = 0 ) {
		global $product;

		if ( ! $product_id ) {
			$product_id = $product->get_id();
		} else {
			$product = wc_get_product( $product_id );
		}
		$image_size     = IDEAPARK_IS_AJAX_QUICKVIEW || ideapark_mod( 'product_page_layout' ) == 'layout-3' || ideapark_mod( 'product_page_layout' ) == 'layout-4' ? 'medium_large' : 'woocommerce_single';
		$images         = [];
		$attachment_ids = $product->get_gallery_image_ids();
		if ( ! is_array( $attachment_ids ) ) {
			$attachment_ids = [];
		}
		if ( get_post_meta( $product_id, '_thumbnail_id', true ) ) {
			if ( $variation_id && ( $attachment_id = get_post_thumbnail_id( $variation_id ) ) ) {
				array_unshift( $attachment_ids, $attachment_id );
			} else {
				array_unshift( $attachment_ids, get_post_thumbnail_id( $product_id ) );
			}
		}

		if ( $attachment_ids ) {

			add_filter( 'wp_lazy_loading_enabled', '__return_false', 100 );
			foreach ( $attachment_ids as $attachment_id ) {
				if ( ! wp_get_attachment_url( $attachment_id ) ) {
					continue;
				}

				$image = wp_get_attachment_image( $attachment_id, $image_size, false, [
					'alt'   => get_the_title( $attachment_id ),
					'class' => 'c-product__slider-img c-product__slider-img--' . ideapark_mod( 'product_image_fit' )
				] );

				$full = ideapark_mod( 'shop_product_modal' ) || ideapark_mod( 'quickview_product_zoom' ) ? wp_get_attachment_image_src( $attachment_id, 'full' ) : false;

				$thumb = wp_get_attachment_image( $attachment_id, 'woocommerce_gallery_thumbnail', false, [
					'alt'   => get_the_title( $product_id ),
					'class' => 'c-product__thumbs-img'
				] );

				$images[] = [
					'attachment_id' => $attachment_id,
					'image'         => $image,
					'full'          => $full,
					'thumb'         => $thumb
				];
			}
			ideapark_rf( 'wp_lazy_loading_enabled', '__return_false', 100 );
		}

		if ( $video_url = get_post_meta( $product_id, '_ip_product_video_url', true ) ) {

			$is_youtube_preview = false;
			if ( $video_thumb_id = get_post_meta( $product_id, '_ip_product_video_thumb', true ) ) {
				$thumb_url = ( $image = wp_get_attachment_image_src( $video_thumb_id, 'woocommerce_gallery_thumbnail' ) ) ? $image[0] : '';
				$image_url = ( $image = wp_get_attachment_image_src( $video_thumb_id, $image_size ) ) ? $image[0] : '';
			} else {
				$pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
				if ( preg_match( $pattern, $video_url, $match ) ) {
					$image_url          = 'https://img.youtube.com/vi/' . $match[1] . '/maxresdefault.jpg';
					$thumb_url          = 'https://img.youtube.com/vi/' . $match[1] . '/default.jpg';
					$is_youtube_preview = true;
				} else {
					$image_url = '';
					$thumb_url = '';
				}
			}
			$video = [
				'thumb_url'          => $thumb_url,
				'image_url'          => $image_url,
				'video_url'          => $video_url,
				'is_youtube_preview' => $is_youtube_preview,
			];

			if ( sizeof( $images ) >= 4 ) {
				array_splice( $images, 3, 0, [ $video ] );
			} else {
				$images[] = $video;
			}
		}

		return $images;
	}
}

if ( ! function_exists( 'ideapark_product_wishlist' ) ) {
	function ideapark_product_wishlist() {
		if ( ideapark_mod( 'wishlist_page' ) ) { ?>
			<div
				class="c-product__wishlist"><?php Ideapark_Wishlist()->ideapark__button( 'h-cb c-product__wishlist-button', 'c-product__wishlist-icon', 'c-product__wishlist-text', __( 'Add to Wishlist', 'luchiana' ), __( 'Remove from Wishlist', 'luchiana' ) ) ?></div>
		<?php }
	}
}

if ( ! function_exists( 'ideapark_product_share' ) ) {
	function ideapark_product_share() {
		if ( ideapark_mod( 'product_share' ) && shortcode_exists( 'ip-post-share' ) ) { ?>
			<div class="c-product__share">
				<div class="c-product__share-title"><?php esc_html_e( 'Share', 'luchiana' ); ?></div>
				<?php echo ideapark_shortcode( '[ip-post-share]' ); ?>
			</div>
		<?php }
	}
}

if ( ! function_exists( 'ideapark_add_to_cart_ajax_notice' ) ) {
	function ideapark_add_to_cart_ajax_notice( $product_id ) {
		wc_add_to_cart_message( $product_id );
	}
}

if ( ! function_exists( 'ideapark_woocommerce_demo_store' ) ) {
	function ideapark_woocommerce_demo_store( $notice ) {
		return str_replace( 'woocommerce-store-notice ', 'woocommerce-store-notice woocommerce-store-notice--' . ideapark_mod( 'store_notice' ) . ' ', $notice );
	}
}

if ( ! function_exists( 'ideapark_woocommerce_product_tabs' ) ) {
	function ideapark_woocommerce_product_tabs( $tabs ) {
		$theme_tabs = ideapark_parse_checklist( ideapark_mod( 'product_tabs' ) );
		$priority   = 10;
		foreach ( $theme_tabs as $theme_tab_index => $enabled ) {
			if ( array_key_exists( $theme_tab_index, $tabs ) ) {
				if ( $enabled ) {
					$tabs[ $theme_tab_index ]['priority'] = $priority;
				} else {
					unset( $tabs[ $theme_tab_index ] );
				}
			}
			$priority += 10;
		}

		return $tabs;
	}
}

if ( ! function_exists( 'ideapark_stock_badge' ) ) {
	function ideapark_stock_badge() {
		global $product;
		/**
		 * @var $product WC_Product
		 */

		$availability = $product->get_availability();
		if ( ! ( $product->is_in_stock() || $product->is_on_backorder() ) ) {
			$availability_html = '<div class="c-product-grid__stock-wrap"><span class="c-product-grid__stock c-product-grid__stock--out-of-stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( ideapark_mod( 'outofstock_badge_text' ) ) . '</span></div>';
			echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
		}
	}
}

if ( ! function_exists( 'ideapark_check_brand_taxonomy' ) ) {
	function ideapark_check_brand_taxonomy( $brand_taxonomy, $attributes ) {
		static $result = [];
		if ( array_key_exists( $brand_taxonomy, $result ) ) {
			return $result[ $brand_taxonomy ];
		}
		if ( array_key_exists( $brand_taxonomy, $attributes ) && $attributes[ $brand_taxonomy ]->is_taxonomy() && ! $attributes[ $brand_taxonomy ]->get_variation() ) {
			$result[ $brand_taxonomy ] = true;
		} else {
			$result[ $brand_taxonomy ] = false;
		}

		return $result[ $brand_taxonomy ];
	}
}

if ( IDEAPARK_IS_AJAX_IMAGES ) {
	add_action( 'wp_ajax_ideapark_product_images', 'ideapark_ajax_product_images' );
	add_action( 'wp_ajax_nopriv_ideapark_product_images', 'ideapark_ajax_product_images' );
} else {
	add_action( 'wp_loaded', 'ideapark_setup_woocommerce', 99 );

	add_action( 'wc_ajax_ideapark_ajax_product', 'ideapark_ajax_product' );
	add_action( 'wp_ajax_ideapark_ajax_product', 'ideapark_ajax_product' );
	add_action( 'wp_ajax_nopriv_ideapark_ajax_product', 'ideapark_ajax_product' );

	add_action( 'woocommerce_ajax_added_to_cart', 'ideapark_add_to_cart_ajax_notice' );

	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	add_filter( 'woocommerce_add_to_cart_fragments', 'ideapark_header_add_to_cart_fragment' );
	add_filter( 'woocommerce_breadcrumb_defaults', 'ideapark_woocommerce_breadcrumbs' );
	add_filter( 'woocommerce_account_menu_items', 'ideapark_woocommerce_account_menu_items' );
	add_filter( 'woocommerce_product_description_heading', 'ideapark_remove_product_description_heading' );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'ideapark_loop_add_to_cart_link', 99, 3 );
	add_filter( 'woocommerce_gallery_image_size', 'ideapark_woocommerce_gallery_image_size', 99, 1 );
	add_filter( 'woocommerce_loop_add_to_cart_args', 'ideapark_woocommerce_loop_add_to_cart_args', 99 );
	add_filter( 'woocommerce_available_variation', 'ideapark_woocommerce_available_variation', 100, 3 );
	add_filter( 'woocommerce_pagination_args', 'ideapark_woocommerce_pagination_args' );
	add_filter( 'subcategory_archive_thumbnail_size', 'ideapark_subcategory_archive_thumbnail_size', 99, 1 );
	add_filter( 'woocommerce_before_widget_product_list', 'ideapark_woocommerce_before_widget_product_list' );
	add_filter( 'woocommerce_demo_store', 'ideapark_woocommerce_demo_store' );
	add_filter( 'woocommerce_product_tabs', 'ideapark_woocommerce_product_tabs', 11 );
}

add_filter( 'woocommerce_product_subcategories_args', 'ideapark_woocommerce_hide_uncategorized' );
add_filter( 'woocommerce_product_categories_widget_args', 'ideapark_woocommerce_hide_uncategorized' );
add_filter( 'woocommerce_product_categories_widget_dropdown_args', 'ideapark_woocommerce_hide_uncategorized' );