<?php
/**
 * This is the most generic template file in a WordPress theme
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the blog posts index page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

// Loads the header.php template.
get_header();
?>

<?php
// Dispay Loop Meta at top
hoot_display_loop_title_content( 'pre', 'index.php' );
if ( hoot_page_header_attop() ) {
	get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
	hoot_display_loop_title_content( 'post', 'index.php' );
}

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'index.php' );
?>

<div class="hgrid main-content-grid">

	<?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', 'index.php' );
	?>

	<main <?php hybridextend_attr( 'content' ); ?>>

		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_start', 'index.php' );

		// Checks if any posts were found.
		if ( have_posts() ) :

			// Dispay Loop Meta in content wrap
			if ( ! hoot_page_header_attop() ) {
				hoot_display_loop_title_content( 'post', 'index.php' );
				get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
			}
			?>

			<div id="content-wrap">

				<?php
				// Template modification Hook
				do_action( 'hoot_loop_start', 'index.php' );

				// Begins the loop through found posts, and load the post data.
				while ( have_posts() ) : the_post();

					// Loads the template-parts/content-{$post_type}.php template.
					hybridextend_get_content_template();

				// End found posts loop.
				endwhile;

				// Template modification Hook
				do_action( 'hoot_loop_end', 'index.php' );
				?>

			</div><!-- #content-wrap -->

			<?php
			// Template modification Hook
			do_action( 'hoot_template_before_loop_nav', 'index.php' );

			// Loads the template-parts/loop-nav.php template.
			get_template_part( 'template-parts/loop-nav' );

		// If no posts were found.
		else :

			// Loads the template-parts/error.php template.
			get_template_part( 'template-parts/error' );

		// End check for posts.
		endif;

		// Template modification Hook
		do_action( 'hoot_template_main_end', 'index.php' );
		?>

	</main><!-- #content -->

	<?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', 'index.php' );
	?>

	<?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

</div><!-- .hgrid -->

<?php get_footer(); // Loads the footer.php template. ?>