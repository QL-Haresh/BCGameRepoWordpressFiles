<?php
// Template modification Hook
do_action( 'hoot_template_before_leftbar_bottom' );

if ( is_active_sidebar( 'hoot-leftbar-bottom' ) ) :
	$lbbclass = ( hoot_get_mod( 'leftbar_bottom_mobile_display' ) ) ?
				'mobile-display mobile-' . ( hoot_get_mod( 'leftbar_bottom_mobile_location' ) ) :
				'';
	?>
	<div <?php hybridextend_attr( 'leftbar-bottom', '', 'leftbar-section hgrid-stretch inline-nav ' . $lbbclass ); ?>>
		<div class="hgrid-span-12">
			<?php dynamic_sidebar( 'hoot-leftbar-bottom' ); ?>
		</div>
	</div>
	<?php
endif;

// Template modification Hook
do_action( 'hoot_template_after_leftbar_bottom' );