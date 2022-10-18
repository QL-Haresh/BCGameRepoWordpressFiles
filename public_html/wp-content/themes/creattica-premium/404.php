<?php 
// Loads the header.php template.
get_header();
?>

<?php
// Template modification Hook
do_action( 'hoot_template_before_content_grid', '404.php' );
?>

<div class="hgrid main-content-grid">

	<?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', '404.php' );
	?>

	<main <?php hybridextend_attr( 'content' ); ?>>

		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_start', '404.php' );
		?>

		<div id="content-wrap">

			<?php
			// Loads the template-parts/error.php template.
			get_template_part( 'template-parts/error' );
			?>

		</div><!-- #content-wrap -->

		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_end', '404.php' );
		?>

	</main><!-- #content -->

	<?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', '404.php' );
	?>

	<?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

</div><!-- .hgrid -->

<?php get_footer(); // Loads the footer.php template. ?>