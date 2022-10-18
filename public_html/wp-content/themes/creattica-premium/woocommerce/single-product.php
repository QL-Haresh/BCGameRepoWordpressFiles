<?php
/**
 * The Template for displaying all single products.
 * @version 1.6.4
 */
?>

<?php get_header( 'shop' ); ?>

<?php
// Dispay Loop Meta at top
hoot_display_loop_title_content( 'pre', 'single-product.php' );
if ( hoot_page_header_attop() ) {
	get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
	hoot_display_loop_title_content( 'post', 'single-product.php' );
}

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'single-product.php' );
?>

<div class="hgrid main-content-grid">

	<?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', 'single-product.php' );
	?>

	<main <?php hybridextend_attr( 'content' ); ?>>

		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_start', 'single-product.php' );

		/**
		 * woocommerce_before_main_content hook
		 *
		 * removed @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
		?>

		<?php if ( have_posts() ) : ?>

			<?php
			// Dispay Loop Meta in content wrap
			if ( ! hoot_page_header_attop() ) {
				hoot_display_loop_title_content( 'post', 'single-product.php' );
				get_template_part( 'template-parts/loop-meta', 'shop' ); // Loads the template-parts/loop-meta-shop.php template to display Title Area with Meta Info (of the loop)
			}
			?>

			<div id="content-wrap">

				<?php
				// Template modification Hook
				do_action( 'hoot_loop_start', 'single-product.php' );
				?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; ?>

				<?php
				// Template modification Hook
				do_action( 'hoot_loop_end', 'single-product.php' );
				?>

			</div><!-- #content-wrap -->

			<?php
			// Template modification Hook
			do_action( 'hoot_template_after_content_wrap', 'single-product.php' );
			?>

		<?php else : ?>

			<?php
			// Loads the template-parts/error.php template.
			get_template_part( 'template-parts/error' );
			?>

		<?php endif; ?>

		<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * removed @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );

		// Template modification Hook
		do_action( 'hoot_template_main_end', 'single-product.php' );
		?>

	</main><!-- #content -->

	<?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', 'single-product.php' );
	?>

	<?php
	/**
	 * woocommerce_sidebar hook
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	do_action( 'woocommerce_sidebar' );
	?>

</div><!-- .hgrid -->

<?php get_footer( 'shop' ); ?>