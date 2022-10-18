<?php
global $hoot_theme;

if ( empty( $hoot_theme->blogposts ) )
	return;
if ( empty( $hoot_theme->blogposts['args'] ) || !is_array( $hoot_theme->blogposts['args'] ) )
	$hoot_theme->blogposts['args'] = array();

/* Create Query */
$query_args = wp_parse_args( $hoot_theme->blogposts['args'] , array(
	'ignore_sticky_posts' => 1,
	) );
$query_args = apply_filters( 'hoot_blogposts_query_args', $query_args );
$hoot_blogposts_query = new WP_Query( $query_args );

/* Add Pagination */
if ( !function_exists( 'hoot_blogposts_pagination' ) ) :
	function hoot_blogposts_pagination( $hoot_blogposts_query, $query_args ) {
		$base_url = ( !empty( $query_args['cat'] ) ) ?
					esc_url( get_category_link( $query_args['cat'] ) ) :
					( ( get_option( 'page_for_posts' ) ) ?
						esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) :
						esc_url( home_url('/') )
						);
		hoot_loop_pagination( array(), $hoot_blogposts_query, $base_url );
	}
endif;
add_action( 'hoot_blogposts_content_end', 'hoot_blogposts_pagination', 10, 2 );
if ( isset( $hoot_theme->blogposts['pagination'] ) && $hoot_theme->blogposts['pagination'] === false )
	remove_action( 'hoot_blogposts_content_end', 'hoot_blogposts_pagination', 10, 2 );

/* Set current layout */
if ( !empty( $hoot_theme->blogposts['layout'] ) ) {
	$sidebar = $hoot_theme->blogposts['layout'];
} else {
	$sidebar = hoot_get_mod( 'sidebar_archives', false );
	$sidebar = ( !$sidebar ) ? hoot_get_mod( 'sidebar', false ) : $sidebar;
}
hoot_set_current_layout( $sidebar );

/* Display List */
if ( $hoot_blogposts_query->have_posts() ) : ?>
	<div class="hoot-blogposts">

		<?php
		/* Display Blogposts Title */
		if ( !empty( $hoot_theme->blogposts['title'] ) )
			echo '<h3 class="hoot-blogposts-title">' . wp_kses_post( $hoot_theme->blogposts['title'] ) . '</h3>';
		?>

		<div id="content-archive" class="content <?php echo hoot_main_layout_class( 'content' ); ?>">

			<?php do_action( 'hoot_blogposts_content_start', $hoot_blogposts_query, $query_args ); ?>

			<div id="content-wrap">
				<?php
				while ( $hoot_blogposts_query->have_posts() ):
					$hoot_blogposts_query->the_post();

					$archive_type = apply_filters( 'hoot_default_archive_type', 'big', 'blogposts' );
					$archive_template = apply_filters( 'hoot_default_archive_location', 'template-parts/content-archive', $archive_type, 'blogposts' );

					// Loads the template-parts/content-archive-{type}.php template.
					get_template_part( $archive_template, $archive_type );
				endwhile;
				?>
			</div>

			<?php do_action( 'hoot_blogposts_content_end', $hoot_blogposts_query, $query_args ); ?>

		</div>

		<?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

		<div class="clearfix"></div>

	</div>
<?php
else :
	get_template_part( 'template-parts/error' );
endif;

/* Resets */
wp_reset_postdata(); // Reset post data before hoot_set_main_layout otherwise get_page_template returns page.php instead of widgetized-template.php
hoot_set_main_layout();