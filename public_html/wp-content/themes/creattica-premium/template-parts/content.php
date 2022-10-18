<?php
/**
 * Template to display 'single' content (post / custom post type / attachment)
 *     - on archive pages (multi post list)
 *     - on single post page
 *
 * This is the default template for 'singular' hierarchy. To customize it, you can duplicate
 * it in the same folder and rename it as 'content-{$post-type}', and the new template will
 * be used for that particular {$post-type}
 * Example : Create 'content-page.php' for content displayed on pages.
 *           Create 'content-attachment.php' for displaying content on attachment pages.
 *           And so on for any other custom post type.
 */


/**
 * If viewing a single post/cpt/attachment
 */
if ( is_singular( get_post_type() ) || ( function_exists( 'is_bbpress' ) && is_bbpress() ) ) :
?>

	<article <?php hybridextend_attr( 'post' ); ?>>

		<div <?php hybridextend_attr( 'entry-content' ); ?>>

			<div class="entry-the-content">
				<?php global $post;
				if ( is_attachment() ) {
					echo wp_get_attachment_image( $post->ID, 'full' );
					the_excerpt();
					the_content();
				}
				else
					the_content(); ?>
			</div>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<div class="screen-reader-text" itemprop="datePublished" itemtype="https://schema.org/Date"><?php echo get_the_date('Y-m-d'); ?></div>

		<?php
		$hide_meta_info = apply_filters( 'hoot_hide_meta_info', false, 'bottom' );
		if ( !$hide_meta_info && 'bottom' == hoot_get_mod( 'post_meta_location' ) && !is_attachment() ):
			$metarray = hoot_get_mod('post_meta');
			if ( hoot_meta_info_display( $metarray, 'post', true ) ) :
			?>
			<footer class="entry-footer">
				<?php hoot_meta_info_blocks( $metarray, 'post' ); ?>
			</footer><!-- .entry-footer -->
			<?php
			endif;
		endif;
		?>

	</article><!-- .entry -->

<?php
/**
 * If not viewing a single post i.e. viewing the post in a list index (archive etc.)
 */
else :

	$archive_type = apply_filters( 'hoot_default_archive_type', 'big', 'content' );
	$archive_template = apply_filters( 'hoot_default_archive_location', 'template-parts/content-archive', $archive_type, 'content' );

	// Loads the template-parts/content-archive-{type}.php template.
	get_template_part( $archive_template, $archive_type );

endif;
?>