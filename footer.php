<?php $page_id = apply_filters( 'wpml_object_id', ideapark_mod( 'footer_page' ), 'any' ); ?>
</div><!-- /.l-inner -->
<footer
	class="l-section c-footer<?php ideapark_class( ! $page_id && ideapark_mod( 'footer_copyright' ), 'c-footer--simple' ); ?>">
	<?php if ( $page_id && 'publish' == ideapark_post_status( $page_id ) ) {
		if ( ideapark_is_elementor() && ( $elementor_instance = Elementor\Plugin::instance() ) && $elementor_instance->db->is_built_with_elementor( $page_id ) ) {
			$page_content = $elementor_instance->frontend->get_builder_content_for_display( $page_id );
		} else {
			setup_postdata( $page_id );
			$page_content = get_the_content();
			$page_content = str_replace( ']]>', ']]&gt;', $page_content );
			$page_content = ideapark_wrap( $page_content, '<div class="entry-content">', '</div>' );
			wp_reset_postdata();
		}
		echo ideapark_wrap( $page_content, '<div class="l-section">', '</div>' );
	} else { ?>
		<div class="l-section__container">
			<?php if ( ideapark_mod( 'footer_copyright' ) ) { ?>
				<?php get_template_part( 'templates/footer-copyright' ); ?>
			<?php } ?>
		</div>
	<?php } ?>
</footer>
</div><!-- /.l-wrap -->
<?php get_template_part( 'templates/pswp' ); ?>
<?php if ( ideapark_mod( 'to_top_button' ) ) { ?>
	<button class="c-to-top-button js-to-top-button c-to-top-button--without-menu" type="button">
		<i class="ip-right c-to-top-button__svg"></i>
	</button>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>
