<?php

// Apply this to only woocommerce pages
if ( !is_woocommerce() )
	return;

/**
 * Template modification Hooks
 */
$display_loop_meta = apply_filters( 'hoot_woo_loop_meta', true );
do_action ( 'hoot_woo_loop_meta', 'start' );

if ( !$display_loop_meta )
	return;

/**
 * If viewing a multi product page 
 */
if ( !is_product() && !is_singular() ) :

	$display_title = apply_filters( 'hoot_wooloop_meta_display_title', true, 'plural' );
	if ( $display_title !== 'hide' ) :
	?>

		<div <?php hybridextend_attr( 'loop-meta-wrap', 'woocommerce' ); ?>>
			<div class="hgrid">

				<div <?php hybridextend_attr( 'loop-meta', '', 'hgrid-span-12' ); ?>>

					<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
						<h1 <?php hybridextend_attr( 'loop-title' ); ?>><?php woocommerce_page_title(); ?></h1>
					<?php endif; ?>
					<div <?php hybridextend_attr( 'loop-description' ); ?>>
						<?php do_action( 'woocommerce_archive_description' ); ?>
					</div><!-- .loop-description -->

				</div><!-- .loop-meta -->

			</div>
		</div>

	<?php
	endif;

/**
 * If viewing a single product
 */
elseif ( is_product() ) :

	add_filter( 'hoot_loop_meta_display_title', 'hoot_hide_loop_meta_woo_product' );
	get_template_part( 'template-parts/loop-meta' );

endif;

/**
 * Template modification Hooks
 */
do_action ( 'hoot_woo_loop_meta', 'end' );