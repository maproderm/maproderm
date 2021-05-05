<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( in_array( $name = wc_get_loop_prop( 'name' ), [
		'related',
		'up-sells',
		'cross-sells'
	] ) || ! ( $layout = ideapark_mod( '_product_layout' ) ) ) {
	$layout = '4-per-row';
	ideapark_mod_set_temp( '_product_layout', $layout );
	ideapark_mod_set_temp( '_product_layout_class', 'c-product-grid__item--' . $layout . ' c-product-grid__item--normal' );
}

ideapark_mod_set_temp( '_is_product_loop', true );
?>
<div
	class="c-product-grid__wrap c-product-grid__wrap--<?php echo esc_attr( $layout ); ?> <?php ideapark_class( ideapark_mod( '_with_sidebar' ), 'c-product-grid__wrap--sidebar', '' ); ?>">
	<div
		class="c-product-grid__list c-product-grid__list--<?php echo esc_attr( $layout ); ?>">
