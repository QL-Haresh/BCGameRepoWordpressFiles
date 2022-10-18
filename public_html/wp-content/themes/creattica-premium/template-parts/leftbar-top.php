<?php
// Template modification Hook
do_action( 'hoot_template_before_leftbar_top' );

if ( is_active_sidebar( 'hoot-leftbar-top' ) ) :
	?>
	<div <?php hybridextend_attr( 'leftbar-top', '', 'leftbar-section hgrid-stretch inline-nav' ); ?>>
		<div class="hgrid-span-12">
			<?php dynamic_sidebar( 'hoot-leftbar-top' ); ?>
		</div>
	</div>
	<?php
endif;

// Template modification Hook
do_action( 'hoot_template_after_leftbar_top' );