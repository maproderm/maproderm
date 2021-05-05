<?php if ( trim( ideapark_mod( 'header_other' ) ) ) { ?>
	<li class="c-header__top-row-item c-header__top-row-item--other">
		<?php echo do_shortcode( esc_html( trim( ideapark_mod( 'header_other' ) ) ) ); ?>
	</li>
<?php } ?>