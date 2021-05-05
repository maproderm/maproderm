<?php
if ( ( $page_id = apply_filters( 'wpml_object_id', ideapark_mod( 'header_advert_bar_page' ), 'any' ) ) && 'publish' == ideapark_post_status( $page_id ) ) {
	if ( ideapark_is_elementor() && ( $elementor_instance = Elementor\Plugin::instance() ) && $elementor_instance->db->is_built_with_elementor( $page_id ) ) {
		$page_content = $elementor_instance->frontend->get_builder_content_for_display( $page_id );
	} else {
		setup_postdata( $page_id );
		$page_content = get_the_content();
		$page_content = str_replace( ']]>', ']]&gt;', $page_content );
		$page_content = ideapark_wrap( $page_content, '<div class="entry-content">', '</div>' );
		wp_reset_postdata();
	}
	echo ideapark_wrap( $page_content );
}