<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $product;
/**
 * @var $product WC_Product
 **/
if ( ideapark_mod( 'shop_modal' ) || ideapark_mod( 'wishlist_page' ) ) { ?>
	<div class="c-product-grid__thumb-button-list">
		<?php if ( ideapark_mod( 'shop_modal' ) ) { ?>
			<button class="h-cb c-product-grid__thumb-button js-grid-zoom" type="button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
				<i class="ip-eye c-product-grid__icon c-product-grid__icon--normal"></i><i class="ip-eye_hover c-product-grid__icon c-product-grid__icon--hover"></i>
			</button>
		<?php } ?>
		<?php if ( ideapark_mod( 'wishlist_page' ) ) { ?>
			<?php ideapark_wishlist()->ideapark__button( 'h-cb c-product-grid__thumb-button', 'c-product-grid__icon' ); ?>
		<?php } ?>
	</div>
<?php }
