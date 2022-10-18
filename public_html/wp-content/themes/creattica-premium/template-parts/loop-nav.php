<?php
/**
 * If viewing a single post page.
 */
if ( is_singular( 'post' ) ) :
?>

	<div class="loop-nav">
		<?php previous_post_link( '<div class="prev">' . __( 'Previous Post: %link', 'creattica-premium' ) . '</div>', '%title' ); ?>
		<?php next_post_link(     '<div class="next">' . __( 'Next Post: %link',     'creattica-premium' ) . '</div>', '%title' ); ?>
	</div><!-- .loop-nav -->

<?php
/**
 * If viewing the blog, an archive, or search results.
 */
elseif ( is_home() || is_archive() || is_search() ) :

	?><div class="clearfix"></div><?php

	if ( function_exists('wp_pagenavi' ) ) {
		// Load WP-PageNavi plugin if installed and active
		wp_pagenavi();
	} else {
		$paginate_args = apply_filters( 'hoot_posts_pagination_args', array() );
		the_posts_pagination( $paginate_args );
	}

endif;