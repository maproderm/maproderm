<?php if ( is_front_page() ) { ?>
<div
	class="c-header__logo<?php if ( ideapark_mod( 'logo_mobile' ) && ( ideapark_mod( 'logo_sticky' ) || ideapark_mod( 'logo_mobile_sticky' ) ) ) { ?> c-header__logo--sticky<?php } ?>">
	<?php } else { ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
	   class="c-header__logo<?php if ( ideapark_mod( 'logo_mobile' ) && ( ideapark_mod( 'logo_sticky' ) || ideapark_mod( 'logo_mobile_sticky' ) ) ) { ?> c-header__logo--sticky<?php } ?>">
		<?php } ?>

		<?php if ( ideapark_mod( 'logo__width' ) && ideapark_mod( 'logo__height' ) ) {
			$dimension = ' width="' . ideapark_mod( 'logo__width' ) . '" height="' . ideapark_mod( 'logo__height' ) . '" ';
		} else {
			$dimension = '';
		}

		$logo_url = ideapark_mod( 'logo' );

		/**
		 * @var string $custom_logo_id
		 */
		extract( ideapark_header_params() );

		if ( ! empty( $custom_logo_id ) ) {
			$params    = wp_get_attachment_image_src( $custom_logo_id, 'full' );
			$logo_url  = $params[0];
			$dimension = ' width="' . $params[1] . '" height="' . $params[2] . '" ';
		}
		?>


		<?php if ( ideapark_mod( 'logo_mobile' ) ) { ?>
			<img <?php echo ideapark_wrap( $dimension ); ?>
				src="<?php echo esc_url( ideapark_mod( 'logo_mobile' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--mobile"/>
		<?php } elseif ( $logo_url ) { ?>
			<img <?php echo ideapark_wrap( $dimension ); ?>
				src="<?php echo esc_url( $logo_url ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--all"/>
		<?php } else { ?>
			<span
				class="c-header__logo-empty"><?php echo esc_html( trim( ideapark_truncate( get_bloginfo( 'name', 'display' ), 10, '' ), " -/.,\r\n\t" ) ); ?></span>
		<?php } ?>

		<?php if ( ideapark_mod( 'logo_mobile_sticky' ) ) { ?>
			<img <?php echo ideapark_wrap( $dimension ); ?>
				src="<?php echo esc_url( ideapark_mod( 'logo_mobile_sticky' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--sticky"/>
		<?php } elseif ( ideapark_mod( 'logo_sticky' ) ) { ?>
			<img <?php echo ideapark_wrap( $dimension ); ?>
				src="<?php echo esc_url( ideapark_mod( 'logo_sticky' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--sticky"/>
		<?php } ?>

<?php if ( is_front_page() ) { ?>
</div>
<?php } else { ?>
	</a>
<?php } ?>
