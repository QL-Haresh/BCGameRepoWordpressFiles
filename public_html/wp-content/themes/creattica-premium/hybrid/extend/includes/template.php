<?php
/**
 * Functions for loading template parts. These functions are helper functions or more flexible functions 
 * than what core WordPress currently offers with template part loading.
 *
 * These functions are closely related to the Template functions in Hybrid Framework. Currently Hybrid
 * does not allow a way to filter the location folder. Till that happens, we use the functions below.
 * @credit https://github.com/justintadlock/hybrid-core/blob/master/inc/template.php 
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Loads a post content template based off the post type and/or the post format. This functionality is 
 * not feasible with the WordPress get_template_part() function, so we have to rely on some custom logic 
 * and locate_template().
 *
 * Note that using this function assumes that you're creating a content template to handle attachments. 
 * This filter must be removed since we're bypassing the WP template hierarchy and focusing on templates 
 * specific to the content.
 *
 * @since 1.0.0
 * @access public
 * @return string
 */

if ( !function_exists( 'hybridextend_get_content_template' ) ) :
function hybridextend_get_content_template() {

	/* Set up an empty array and get the post type. */
	$templates = array();
	$post_type = get_post_type();

	/* Assume the developer is creating an attachment template. */
	if ( 'attachment' === $post_type ) {
		remove_filter( 'the_content', 'prepend_attachment' );

		$mime_type = get_post_mime_type();

		list( $type, $subtype ) = false !== strpos( $mime_type, '/' ) ? explode( '/', $mime_type ) : array( $mime_type, '' );

		$templates[] = "content-attachment-{$type}.php";
		$templates[] = "template-parts/content-attachment-{$type}.php";
		$templates[] = "content-attachment.php";
		$templates[] = "template-parts/content-attachment.php";
	}

	/* If the post type supports 'post-formats', get the template based on the format. */
	if ( post_type_supports( $post_type, 'post-formats' ) ) {

		/* Get the post format. */
		$post_format = get_post_format() ? get_post_format() : 'standard';

		/* Template based off post type and post format. */
		$templates[] = "content-{$post_type}-{$post_format}.php";
		$templates[] = "template-parts/content-{$post_type}-{$post_format}.php";

		/* Template based off the post format. */
		$templates[] = "content-{$post_format}.php";
		$templates[] = "template-parts/content-{$post_format}.php";
	}

	/* Template based off the post type. */
	$templates[] = "content-{$post_type}.php";
	$templates[] = "template-parts/content-{$post_type}.php";

	/* Fallback 'content.php' template. */
	$templates[] = 'content.php';
	$templates[] = 'template-parts/content.php';

	/* Allow devs to filter the content template hierarchy. */
	$templates = apply_filters( 'hybridextend_content_template_hierarchy', $templates, $post_type );

	/* Locate the found content template. Use include instead of require/require_once used in locate_template() */
	include( locate_template( $templates, false, false ) );
}
endif;

/**
 * A function for loading a menu template. This works similar to the WordPress `get_*()` template functions. 
 * It's purpose is for loading a menu template part. This function looks for menu templates within the 
 * `menu` sub-folder or the root theme folder.
 *
 * @since 1.0.0
 * @access public
 * @param string $name
 * @return void
 */
if ( !function_exists( 'hybridextend_get_menu' ) ) :
function hybridextend_get_menu( $name = '' ) {

	$templates = array();

	if ( '' !== $name ) {
		$templates[] = "menu-{$name}.php";
		$templates[] = "template-parts/menu-{$name}.php";
	}

	$templates[] = 'menu.php';
	$templates[] = 'template-parts/menu.php';

	$templates = apply_filters( 'hybridextend_menu_template_hierarchy', $templates, $name );

	locate_template( $templates, true );
}
endif;

/**
 * This is a replacement function for the WordPress `get_header()` function. The reason for this function 
 * over the core function is because the core function does not provide the functionality needed to properly 
 * implement what's needed, particularly the ability to add header templates to a sub-directory. 
 * Technically, there's a workaround for that using the `get_header` hook, but it requires keeping a 
 * an empty `header.php` template in the theme's root, which will get loaded every time a header template 
 * gets loaded. That's kind of nasty hack, which leaves us with this function. This is the **only** 
 * clean solution currently possible.
 *
 * This function maintains compatibility with the core `get_header()` function. It does so in two ways: 
 * 1) The `get_header` hook is properly fired and 2) The core naming convention of header templates 
 * (`header-$name.php` and `header.php`) is preserved and given a higher priority than custom templates.
 *
 * @link http://core.trac.wordpress.org/ticket/15086
 * @link http://core.trac.wordpress.org/ticket/18676
 *
 * @since 1.0.0
 * @access public
 * @param string $name
 * @return void
 */
if ( !function_exists( 'hybridextend_get_header' ) ) :
function hybridextend_get_header( $name = null ) {

	do_action( 'get_header', $name ); // Core WordPress hook

	$templates = array();

	if ( '' !== $name ) {
		$templates[] = "header-{$name}.php";
		$templates[] = "template-parts/header-{$name}.php";
	}

	$templates[] = 'header.php';
	$templates[] = 'template-parts/header.php';

	$templates = apply_filters( 'hybridextend_header_template_hierarchy', $templates, $name );

	locate_template( $templates, true );
}
endif;

/**
 * Add 'template-parts' location to hybrid framework's comment callback function
 *
 * @since 1.0.0
 * @access public
 * @param array
 * @param string
 * @return array
 */
if ( !function_exists( 'hybridextend_comment_template_hierarchy' ) ) :
function hybridextend_comment_template_hierarchy( $templates, $comment_type ) {

	// Create an array of template files to look for.
	$templates = array(
		"comment-{$comment_type}.php",
		"comment/{$comment_type}.php",
		"template-parts/comment-{$comment_type}.php",
	);

	// If the comment type is a 'pingback' or 'trackback', allow the use of 'comment-ping.php'.
	if ( 'pingback' == $comment_type || 'trackback' == $comment_type ) {
		$templates[] = 'comment-ping.php';
		$templates[] = 'comment/ping.php';
		$templates[] = 'template-parts/comment-ping.php';
	}

	// Add the fallback 'comment.php' template.
	$templates[] = 'comment.php';
	$templates[] = 'comment/comment.php';
	$templates[] = 'template-parts/comment.php';

	return $templates;

}
endif;
add_filter( 'hybrid_comment_template_hierarchy', 'hybridextend_comment_template_hierarchy', 10, 2 );

/**
 * This is a replacement function for the WordPress `get_footer()` function. The reason for this function 
 * over the core function is because the core function does not provide the functionality needed to properly 
 * implement what's needed, particularly the ability to add footer templates to a sub-directory. 
 * Technically, there's a workaround for that using the `get_footer` hook, but it requires keeping a 
 * an empty `footer.php` template in the theme's root, which will get loaded every time a footer template 
 * gets loaded. That's kind of nasty hack, which leaves us with this function. This is the **only** 
 * clean solution currently possible.
 *
 * This function maintains compatibility with the core `get_footer()` function. It does so in two ways: 
 * 1) The `get_footer` hook is properly fired and 2) The core naming convention of footer templates 
 * (`footer-$name.php` and `footer.php`) is preserved and given a higher priority than custom templates.
 *
 * @link http://core.trac.wordpress.org/ticket/15086
 * @link http://core.trac.wordpress.org/ticket/18676
 *
 * @since 1.0.0
 * @access public
 * @param string $name
 * @return void
 */
if ( !function_exists( 'hybridextend_get_footer' ) ) :
function hybridextend_get_footer( $name = null ) {

	do_action( 'get_footer', $name ); // Core WordPress hook

	$templates = array();

	if ( '' !== $name ) {
		$templates[] = "footer-{$name}.php";
		$templates[] = "template-parts/footer-{$name}.php";
	}

	$templates[] = 'footer.php';
	$templates[] = 'template-parts/footer.php';

	$templates = apply_filters( 'hybridextend_footer_template_hierarchy', $templates, $name );

	locate_template( $templates, true );
}
endif;

/**
 * This is a replacement function for the WordPress `get_sidebar()` function. The reason for this function 
 * over the core function is because the core function does not provide the functionality needed to properly 
 * implement what's needed, particularly the ability to add sidebar templates to a sub-directory. 
 * Technically, there's a workaround for that using the `get_sidebar` hook, but it requires keeping a 
 * an empty `sidebar.php` template in the theme's root, which will get loaded every time a sidebar template 
 * gets loaded. That's kind of nasty hack, which leaves us with this function. This is the **only** 
 * clean solution currently possible.
 *
 * This function maintains compatibility with the core `get_sidebar()` function. It does so in two ways: 
 * 1) The `get_sidebar` hook is properly fired and 2) The core naming convention of sidebar templates 
 * (`sidebar-$name.php` and `sidebar.php`) is preserved and given a higher priority than custom templates.
 *
 * @link http://core.trac.wordpress.org/ticket/15086
 * @link http://core.trac.wordpress.org/ticket/18676
 *
 * @since 1.0.0
 * @access public
 * @param string $name
 * @param string $context
 * @return void
 */
if ( !function_exists( 'hybridextend_get_sidebar' ) ) :
function hybridextend_get_sidebar( $name = null, $context = '' ) {

	$get_template = apply_filters( 'hybridextend_get_sidebar_template', true, $context );

	if ( $get_template ) :

		do_action( 'get_sidebar', $name ); // Core WordPress hook

		$templates = array();

		if ( '' !== $name ) {
			$templates[] = "sidebar-{$name}.php";
			$templates[] = "template-parts/sidebar-{$name}.php";
		}

		$templates[] = 'sidebar.php';
		$templates[] = 'template-parts/sidebar.php';

		$templates = apply_filters( 'hybridextend_sidebar_template_hierarchy', $templates, $name );

		// Set require_once to false for frontpage content to display sidebar when "Hoot > Blog Posts" widget used in an area before it
		locate_template( $templates, true, false );

	endif;

}
endif;

/**
 * A function for loading a custom widget template. This works similar to the WordPress `get_*()` template functions. 
 * It's purpose is for loading a widget display template. This function looks for widget templates within the 
 * `widget` sub-folder or the root theme folder.
 *
 * @since 1.0.0
 * @access public
 * @param string $name
 * @return void
 */
function hybridextend_get_widget( $name = '' ) {
	include( hybridextend_locate_widget( $name ) );
}

/**
 * A function for locating a custom widget template. This works similar to the WordPress `get_*()` template functions. 
 * It's purpose is for loading a widget display template. This function looks for widget templates within the 
 * `widget` sub-folder or the root theme folder.
 *
 * @since 1.0.0
 * @access public
 * @param string $name
 * @return void
 */
if ( !function_exists( 'hybridextend_locate_widget' ) ) :
function hybridextend_locate_widget( $name = '' ) {
	$templates = array();

	if ( '' !== $name ) {
		$templates[] = "widget-{$name}.php";
		$templates[] = "widget/{$name}.php";
		$templates[] = "template-parts/widget-{$name}.php";
	}

	$templates[] = 'widget.php';
	$templates[] = 'widget/widget.php';
	$templates[] = 'template-parts/widget.php';

	$templates = apply_filters( 'hybridextend_widget_template_hierarchy', $templates, $name );

	return locate_template( $templates, false );
}
endif;

/**
 * This function is an extension of 'get_template_part' in a way that it allows $load option to
 * be passed to 'locate_template' (something that WordPress doesnt support currently)
 *
 * @link https://core.trac.wordpress.org/browser/tags/4.9/src/wp-includes/general-template.php#L134
 *
 * @since 2.2.2
 * @access public
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param bool $load If true the template file will be loaded if it is found.
 * @return void
 */
if ( !function_exists( 'hybridextend_get_template_part' ) ) :
function hybridextend_get_template_part( $slug, $name = null, $load = true ) {

	do_action( "get_template_part_{$slug}", $slug, $name );

	$templates = array();
	$name = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
		$templates[] = "template-parts/{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";
	$templates[] = "template-parts/{$slug}.php";

	$templates = apply_filters( 'hybridextend_get_template_hierarchy', $slug, $name );

	/* Load / Return the template */
	if ( $load )
		locate_template( $templates, true, false );
	else
		return locate_template( $templates );
}
endif;

/* == template-general == */

/**
 * Displays a link to theme on WordPress.org.
 *
 * @since 1.1.4
 * @access public
 * @param string $link
 * @param string $anchor
 * @return void
 */
function hybridextend_wp_theme_link( $link = '', $anchor = '' ) {
	echo hybridextend_get_wp_theme_link( $link, $anchor );
}

/**
 * Returns a link to theme on WordPress.org.
 *
 * @since 1.1.4
 * @access public
 * @param string $link
 * @param string $anchor
 * @return string
 */
function hybridextend_get_wp_theme_link( $link = '', $anchor = '' ) {

	/* Get Theme */
	global $hybridextend_theme;
	$name = $hybridextend_theme->display( 'Name', false, true );
	$slug = preg_replace( "/[\s-]+/", " ", strtolower( hybridextend_trim( $name ) ) );
	$slug = str_replace( " ", "-", $slug );

	$link   = ( empty( $link ) ) ? 'https://wordpress.org/themes/' . $slug : $link;
	$anchor = ( empty( $anchor ) ) ? $name : $anchor;

	$title = sprintf( __( '%s WordPress Theme', 'hybrid-core' ), $name );

	return sprintf( '<a class="wp-theme-link" href="%s" title="%s">%s</a>', esc_url( $link ), esc_attr( $title ), esc_html( $anchor ) );
}

/**
 * Returns a link to the theme URI.
 *
 * @since 1.0.0
 * @access public
 * @param string $link
 * @param string $anchor
 * @return string
 */
function hybridextend_get_theme_link( $link = '', $anchor = '' ) {

	/* Get Theme */
	global $hybridextend_theme;
	$name = ( is_child_theme() ) ? $hybridextend_theme->parent()->get( 'Name' ) : $hybridextend_theme->get( 'Name' );

	$link   = ( empty( $link ) ) ? ( ( is_child_theme() ) ? $hybridextend_theme->parent()->get( 'ThemeURI' ) : $hybridextend_theme->get( 'ThemeURI' ) ) : $link;
	$anchor = ( empty( $anchor ) ) ? $name : $anchor;

	$title = sprintf( __( '%s WordPress Theme', 'hybrid-core' ), $name );

	return sprintf( '<a class="theme-link" href="%s" title="%s">%s</a>', esc_url( $link ), esc_attr( $title ), esc_html( $anchor ) );
}

/**
 * Filter the WordPress archive title after Hybrid has worked its magic
 *
 * @deprecated since 2.9.0 -> modified hybrid core function
 *
 * @since 2.1.5
 * @access public
 * @param string  $title
 * @return string
 */
function hybridextend_loop_title( $title ) {
	$title_suffix = '';

	/* If the current page is a paged page. */
	if ( ( ( $page = get_query_var( 'paged' ) ) || ( $page = get_query_var( 'page' ) ) ) && $page > 1 ) {
		$paged = number_format_i18n( absint( $page ) );
		$title_suffix = ' <span class="loop-title-suffix loop-title-paged">' . sprintf( __( '(Page %s)', 'hybrid-core' ), $paged ) . '</span>';
		$title_suffix = apply_filters( 'hybridextend_title_suffix', $title_suffix, $paged );
	}

	return $title . $title_suffix;
}
// add_filter( 'hybrid_archive_title', 'hybridextend_loop_title', 1, 3 );