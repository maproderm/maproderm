<?php if ( ideapark_woocommerce_on() && ideapark_mod( 'cart_enabled' ) ) { ?>
	<div class="c-header__cart js-cart">
		<a class="c-header__button-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
			<i class="ip-cart c-header__cart-icon"><!-- --></i><?php echo ideapark_cart_info(); ?>
		</a>
		<?php if ( empty( $ideapark_var['device'] ) || $ideapark_var['device'] != 'mobile' ) { ?>
			<div class="widget_shopping_cart_content"></div>
		<?php } ?>
	</div>
<?php } ?>