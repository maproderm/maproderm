<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * @var $product WC_Product
 **/
global $post;
global $product;

if ( ( $brand_taxonomy = ideapark_mod( 'product_brand_attribute' ) ) && ( $attributes = $product->get_attributes() ) && ideapark_check_brand_taxonomy( $brand_taxonomy, $attributes ) ) {
	if ( $terms = $attributes[ $brand_taxonomy ]->get_terms() ) {
		$brands = [];
		foreach ( $terms as $term ) {
			$brands[] = '<a class="c-product-grid__brand" href="' . esc_url( get_term_link( $term->term_id, $brand_taxonomy ) ) . '">' . esc_html( $term->name ) . '</a>';
		}
		$brands = array_filter( $brands );
		echo ideapark_wrap( implode( ' • ', $brands ), '<div class="c-product-grid__brands">', '</div>' );
	}
}