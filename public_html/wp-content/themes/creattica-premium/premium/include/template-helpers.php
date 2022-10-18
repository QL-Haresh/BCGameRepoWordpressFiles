<?php
/**
 * Miscellaneous template functions.
 * These functions are for use throughout the theme's various template files.
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Remove the filter added for lite version ( to hide title area on static frontpage not
 * using Widgetized Template ). Instead let premium meta option decide this.
 * @todo: Redundant
 */
remove_filter( 'hoot_loop_meta_display_title', 'hoot_hide_loop_meta_static_frontpage' );

// see @ref1242
// /**
//  * Apply Sidebar Layout for Frontpage
//  *
//  * @since 1.0
//  * @param bool $sidebar
//  * @return string
//  */
// function hoot_premium_frontpage_sidebar( $sidebar ) {
// 	return hoot_get_meta_option( 'frontpage_sidebar', 0, 'none' );
// }
// add_filter( 'hoot_frontpage_sidebar', 'hoot_premium_frontpage_sidebar', 5 );

/**
 * Apply Sidebar Layout for Archives and Blog page
 * Add it at earliest priority as this is core option
 *
 * @deprecated since 1.9.0
 *
 * @since 1.0
 * @param bool $sidebar
 * @return string
 */
function hoot_premium_main_layout_extension( $sidebar ) {
	if ( is_archive() || is_home() )
		$sidebar = hoot_get_mod( 'sidebar_archives' );
	elseif ( is_front_page() && !is_home() ) // static page set as frontpage
		$sidebar = apply_filters( 'hoot_frontpage_sidebar', 'none' );
	return $sidebar;
}
// add_filter( 'hoot_main_layout', 'hoot_premium_main_layout_extension', 5 );

/**
 * Override sidebar layout for individual page/post
 *
 * @since 1.0
 * @param bool $sidebar
 * @return string
 */
function hoot_premium_main_layout_single_page( $sidebar ) {
	if ( is_singular() && !is_front_page() ) { // Page got 'sidebar_type' set before assigning it as front page : This option is not visible on Edit Page after it is set as frontpage
		$type = hoot_get_meta_option( 'sidebar_type' );
		if ( 'custom' === $type )
			$sidebar = hoot_get_meta_option( 'sidebar' );
	}
	return $sidebar;
}
add_filter( 'hoot_main_layout', 'hoot_premium_main_layout_single_page', 5 );

/**
 * Use premium sliders to display slider
 *
 * @since 1.0
 * @param string $output
 * @param string $slider_op
 * @return string
 */
function hoot_premium_frontpage_slider( $output, $slider_op ) {
	$sliderID = hoot_get_mod( $slider_op );
	$output = do_shortcode( '[hoot_slider id="' . $sliderID . '"]' );
	if ( empty( $output ) )
		$output = ' '; // Do not return empty (needed for installations migrated from lite to premium as they still contain lite sliders theme mods)
	return $output;
}
add_filter( 'hoot_frontpage_slider', 'hoot_premium_frontpage_slider', 5, 2 );

/**
 * Define archive type selected in options
 *
 * @since 1.0
 * @param string $archive_type
 * @param string $context
 * @return string
 */
function hoot_premium_default_archive_type( $archive_type, $context = '' ) {
	$archive_type = hoot_get_mod( 'archive_type' );
	return $archive_type;
}
add_filter( 'hoot_default_archive_type', 'hoot_premium_default_archive_type', 5, 2 );

/**
 * Locate archive type template location
 *
 * @since 1.0
 * @param string $template
 * @param string $archive_type
 * @param string $context
 * @return string
 */
function hoot_premium_default_archive_location( $template, $archive_type, $context = '' ) {

	if ( $archive_type == 'big' ) {
		return $template;
	} else {
		$base_premium = str_replace( HYBRID_PARENT, '', PREMIUM_DIR );
		return $base_premium . $template;
	}

}
add_filter( 'hoot_default_archive_location', 'hoot_premium_default_archive_location', 5, 3 );


/**
 * Set location for premium sliders
 *
 * @since 1.0
 * @param string $type
 * @return void
 */
function hoot_premium_slider_location( $type ) {

	if ( $type == 'carousel' ) {
		global $hoot_theme;
		$base_premium = str_replace( HYBRID_PARENT, '', PREMIUM_DIR );
		$hoot_theme->sliderSettings['template'] = $base_premium . "template-parts/slider-{$type}";
	}

}
add_action( 'hoot_slider_loaded', 'hoot_premium_slider_location', 5 );

/**
 * Hide or display meta information for current page/post
 *
 * @since 1.0
 * @param string $hide
 * @param string $context
 * @return string
 */
function hoot_premium_hide_meta_info( $hide, $context ) {
	$override = hoot_get_meta_option( 'meta_hide_info' );
	return ( $override ) ? $override : $hide;
}
add_filter( 'hoot_hide_meta_info', 'hoot_premium_hide_meta_info', 5, 2 );

/**
 * Hide or display Page Loop Area for current page/post
 *
 * @since 1.0
 * @param string $value
 * @return string
 */
function hoot_premium_loop_meta_display_title( $value ) {
	if ( is_page() || is_singular( 'post' ) )
		$id = get_queried_object_id();
	elseif ( is_home() && !is_front_page() )
		$id = get_option( 'page_for_posts' );
	// elseif ( current_theme_supports( 'woocommerce' ) && is_shop() )
	// 	$id = get_option( 'woocommerce_shop_page_id' );
	else
		return $value;
	return hoot_get_meta_option( 'display_loop_meta', $id );
}
add_filter( 'hoot_loop_meta_display_title', 'hoot_premium_loop_meta_display_title', 5 );

/**
 * Hide or display Page Loop Area for Woocommerce Products (archives) Page (shop/taxonomies)
 *
 * @since 1.0
 * @param string $value
 * @return string
 */
function hoot_premium_wooloop_meta_display_title( $value ) {
	if ( current_theme_supports( 'woocommerce' ) && is_shop() ) {
		$id = get_option( 'woocommerce_shop_page_id' );
		return hoot_get_meta_option( 'display_loop_meta', $id );
	}
	return $value;
}
add_filter( 'hoot_wooloop_meta_display_title', 'hoot_premium_wooloop_meta_display_title', 5 );

/**
 * Page Loop Area
 *
 * @since 1.0
 * @param string $value
 * @param string $location
 * @param string $context
 * @return string
 */
function hoot_premium_loop_meta_pre_title_content( $value, $location, $context ) {
	if ( is_page() || is_singular( 'post' ) )
		$id = get_queried_object_id();
	elseif ( is_home() && !is_front_page() )
		$id = get_option( 'page_for_posts' );
	elseif ( current_theme_supports( 'woocommerce' ) && is_shop() )
		$id = get_option( 'woocommerce_shop_page_id' );
	else
		return $value;
	return hoot_get_meta_option( 'pre_title_content', $id );
}
add_filter( 'hoot_loop_meta_pre_title_content', 'hoot_premium_loop_meta_pre_title_content', 5, 3 );

/**
 * Page Loop Area
 *
 * @since 1.0
 * @param string $value
 * @param string $location
 * @param string $context
 * @return string
 */
function hoot_premium_loop_meta_pre_title_content_stretch( $value, $location, $context ) {
	if ( is_page() || is_singular( 'post' ) )
		$id = get_queried_object_id();
	elseif ( is_home() && !is_front_page() )
		$id = get_option( 'page_for_posts' );
	elseif ( current_theme_supports( 'woocommerce' ) && is_shop() )
		$id = get_option( 'woocommerce_shop_page_id' );
	else
		return $value;
	return hoot_get_meta_option( 'pre_title_content_stretch', $id );
}
add_filter( 'hoot_loop_meta_pre_title_content_stretch', 'hoot_premium_loop_meta_pre_title_content_stretch', 5, 3 );

/**
 * Page Loop Area
 *
 * @since 1.0
 * @param string $value
 * @param string $location
 * @param string $context
 * @return string
 */
function hoot_premium_loop_meta_pre_title_content_post( $value, $location, $context ) {
	if ( is_page() || is_singular( 'post' ) )
		$id = get_queried_object_id();
	elseif ( is_home() && !is_front_page() )
		$id = get_option( 'page_for_posts' );
	elseif ( current_theme_supports( 'woocommerce' ) && is_shop() )
		$id = get_option( 'woocommerce_shop_page_id' );
	else
		return $value;
	return hoot_get_meta_option( 'pre_title_content_post', $id );
}
add_filter( 'hoot_loop_meta_pre_title_content_post', 'hoot_premium_loop_meta_pre_title_content_post', 5, 3 );

/**
 * Allow users to determine WooCommerce Page Layouts
 *
 * @since 1.0
 * @param bool
 * @return bool
 */
function hoot_premium_woo_pages_force_nosidebar( $value ) {
	return false;
}
add_filter( 'hoot_woo_pages_force_nosidebar', 'hoot_premium_woo_pages_force_nosidebar', 5 );

/**
 * Display custom 404 page content
 *
 * @since 1.6
 * @return void
 */
function hoot_premium_custom404() {
	if ( is_404() && 'custom' == hoot_get_mod('404_page') ) {
		$page_404 = intval( hoot_get_mod('404_custom_page') );
		if ( !empty( $page_404 ) ) {
			remove_action( 'hoot_404_content', 'hoot_display_404_content', 5 );
			add_action( 'hoot_404_content', 'hoot_premium_display_custom404_content', 5 );
			add_filter( 'hoot_display_404_content_title', 'hoot_display_custom404_content_title' );
		}
	}
}
add_action( 'wp', 'hoot_premium_custom404' );
function hoot_premium_display_custom404_content() {
	$page_404 = intval( hoot_get_mod('404_custom_page') );
	$post = get_post( $page_404 );
	echo apply_filters( 'the_content', $post->post_content );
}
function hoot_display_custom404_content_title(){
	return false;
}

/**
 * Loop pagination function. This can be used for custom loops to create pagination
 * Uses WordPress's core 'paginate_links' function
 *
 * @since 1.0
 * @access public
 * @param array $args Arguments to customize how the page links are output.
 * @return string $page_links
 */
function hoot_loop_pagination( $args = array(), $custom_query = array(), $base_url = '' ) {
	global $wp_rewrite, $wp_query;

	/* Use query */
	$query = ( ! empty( $custom_query ) ) ? $custom_query : $wp_query;

	/* Set base url */
	$base_url = ( !empty( $base_url ) ) ? esc_url( $base_url ) : get_pagenum_link();

	/* If there's not more than one page, return nothing. */
	if ( 1 >= $query->max_num_pages )
		return;

	/* Get the current page. */
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );

	/* Get the max number of pages. */
	$max_num_pages = intval( $query->max_num_pages );

	/* Get the pagination base. */
	$pagination_base = $wp_rewrite->pagination_base;

	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base'         => add_query_arg( 'paged', '%#%', $base_url ),
		'format'       => '',
		'total'        => $max_num_pages,
		'current'      => $current,
		'prev_next'    => true,
		'prev_text'  => __( '&laquo; Previous', 'hybrid-core' ), // This is the WordPress default.
		'next_text'  => __( 'Next &raquo;', 'hybrid-core' ), // This is the WordPress default.
		'show_all'     => false,
		'end_size'     => 1,
		'mid_size'     => 1,
		'add_fragment' => '',
		'type'         => 'plain',

		// Begin hoot_loop_pagination() arguments.
		'before'       => '<nav class="pagination loop-pagination">',
		'after'        => '</nav>',
		'echo'         => true,
	);

	/* Add the $base argument to the array if the user is using permalinks. */
	if ( $wp_rewrite->using_permalinks() && !is_search() )
		$defaults['base'] = user_trailingslashit( trailingslashit( $base_url ) . "{$pagination_base}/%#%" );

	/* Allow developers to overwrite the arguments with a filter. */
	$args = apply_filters( 'hoot_loop_pagination_args', $args );

	/* Merge the arguments input with the defaults. */
	$args = wp_parse_args( $args, $defaults );

	/* Don't allow the user to set this to an array. */
	if ( 'array' == $args['type'] )
		$args['type'] = 'plain';

	/* Get the paginated links. */
	$page_links = paginate_links( $args );

	/* Remove 'page/1' from the entire output since it's not needed. */
	$page_links = preg_replace( 
		array( 
			"#(href=['\"].*?){$pagination_base}/1(['\"])#",  // 'page/1'
			"#(href=['\"].*?){$pagination_base}/1/(['\"])#", // 'page/1/'
			"#(href=['\"].*?)\?paged=1(['\"])#",             // '?paged=1'
			"#(href=['\"].*?)&\#038;paged=1(['\"])#"         // '&#038;paged=1'
		), 
		'$1$2', 
		$page_links 
	);

	/* Wrap the paginated links with the $before and $after elements. */
	$page_links = $args['before'] . $page_links . $args['after'];

	/* Allow devs to completely overwrite the output. */
	$page_links = wp_kses_post( apply_filters( 'hoot_loop_pagination', $page_links ) );

	/* Return the paginated links for use in themes. */
	if ( $args['echo'] )
		echo $page_links;
	else
		return $page_links;
}