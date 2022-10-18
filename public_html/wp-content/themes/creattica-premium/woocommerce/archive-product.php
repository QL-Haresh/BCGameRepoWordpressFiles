<?php
/**
 * The Template for displaying product archives, including the main
 * shop page which is a post type archive.
 * @version 3.4.0
 */
?>

<?php get_header( 'shop' ); ?>

<?php
// Dispay Loop Meta at top
hoot_display_loop_title_content( 'pre', 'archive-product.php' );
if ( hoot_page_header_attop() ) {
	get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
	hoot_display_loop_title_content( 'post', 'archive-product.php' );
}

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'archive-product.php' );
?>

<div class="hgrid main-content-grid">

	<?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', 'archive-product.php' );
	?>

	<main <?php hybridextend_attr( 'content' ); ?>>

		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_start', 'archive-product.php' );

		/**
		 * woocommerce_before_main_content hook
		 *
		 * removed @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );

		if ( ( function_exists( 'woocommerce_product_loop' ) && woocommerce_product_loop() ) || have_posts() ) :

			// Dispay Loop Meta in content wrap
			if ( ! hoot_page_header_attop() ) {
				hoot_display_loop_title_content( 'post', 'archive-product.php' );
				get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
			}
			?>

			<div id="content-wrap">

				<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if ( !function_exists( 'wc_get_loop_prop' ) || wc_get_loop_prop( 'total' ) ) :
					while ( have_posts() ) : the_post();
						do_action( 'woocommerce_shop_loop' );
						wc_get_template_part( 'content', 'product' );
					endwhile;
				endif;

				woocommerce_product_loop_end();

				// Template modification Hook
				do_action( 'hoot_template_before_loop_nav', 'archive-product.php' );

				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>

			</div><!-- #content-wrap -->

		<?php
		else:

			wc_get_template( 'loop/no-products-found.php' );

		endif;

		/**
		 * woocommerce_after_main_content hook
		 *
		 * removed @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );

		// Template modification Hook
		do_action( 'hoot_template_main_end', 'archive-product.php' );
		?>

	</main><!-- #content -->

	<?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', 'archive-product.php' );

	/**
	 * woocommerce_sidebar hook
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	do_action( 'woocommerce_sidebar' );
	?>

</div><!-- .hgrid -->

<?php get_footer( 'shop' ); ?>