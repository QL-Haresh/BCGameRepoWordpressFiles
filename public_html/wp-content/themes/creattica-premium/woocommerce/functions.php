<?php
/**
 * Woocommerce functions
 * This file is loaded at 'after_setup_theme' action @priority 10 ONLY IF woocommerce plugin is active
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Woocommerce Template Setup
 *
 * @since 1.4
 * @access public
 * @return void
 */
function hoot_woo_setup() {
	// Remove default woocommerce opening divs for the content
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );

	// Remove woocommerce breadcrumbs
	// if ( !is_product() )
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// Remove default woocommerce closing divs for the content
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	/* Add theme support for WC Product Gallery slider and zoom */
	// Since this file is loaded using 'after_setup_theme' hook at priority 10, theme support can be added directly.
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'wp', 'hoot_woo_setup' );

/**
 * Registers woocommerce sidebars.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_woo_register_sidebars() {
	hybrid_register_sidebar(
		array(
			'id'          => 'hoot-woocommerce-sidebar',
			'name'        => _x( 'Woocommerce Sidebar', 'sidebar', 'creattica-premium' ),
			'description' => __( 'The primary sidebar for woocommerce pages.', 'creattica-premium' )
		)
	);
}
add_action( 'widgets_init', 'hoot_woo_register_sidebars' );

/**
 * Add woocommerce sidebar class.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function hoot_theme_woo_attr_sidebar( $attr, $context ) {
	if ( !empty( $context ) && ( $context == 'primary' || $context == 'secondary' ) ) {
		if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
			$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
			$attr['class'] .= " woocommerce-sidebar woocommerce-sidebar-{$context}";
		}
	}
	return $attr;
}
add_filter( 'hybrid_attr_sidebar', 'hoot_theme_woo_attr_sidebar', 11, 2 );

/**
 * Apply sidebar layout for woocommerce pages
 *
 * @since 1.0
 * @access public
 * @param string $sidebar
 * @return array
 */
function hoot_woo_main_layout( $sidebar ) {

	// Check for pages which use WooCommerce templates (cart and checkout are standard 'Pages' with shortcodes and thus are not included)
	if ( is_woocommerce() ){
		if ( is_product() ) { // single product page. Wrapper for is_singular
			$sidebar = hoot_get_mod( 'sidebar_wooproduct' );
		} else { // shop, category, tag archives etc
			$sidebar = hoot_get_mod( 'sidebar_wooshop' );
		}
	}

	// Let developers edit default layout for Cart and Checkout which are standard 'Pages' with shortcodes
	$forcenosidebar = apply_filters( 'hoot_woo_pages_force_nosidebar', true );
	if ( $forcenosidebar && ( is_cart() || is_checkout() || is_account_page() ) ) {
		$sidebar = 'none';
	}

	return $sidebar;
}
add_filter( 'hoot_main_layout', 'hoot_woo_main_layout' );


/**
 * Do not show meta info for Products or WooPages (Account, Cart, Checkout)
 *
 * @since 1.0
 * @access public
 * @param array $display
 * @param string $context
 */
if ( !function_exists('hoot_woo_meta_info_blocks_display') ) {
function hoot_woo_meta_info_blocks_display( $display, $context ) {
	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() )
		$display = array();
	return $display;
}
}
add_filter( 'hoot_meta_info_blocks_display', 'hoot_woo_meta_info_blocks_display', 10, 2 );


/**
 * Change product # displayed on shop page
 *
 * @since 1.0
 * @access public
 * @param int $value
 * @return int
 */
if ( !function_exists('hoot_woo_loop_per_page') ) {
function hoot_woo_loop_per_page( $value ) {
	return intval( hoot_get_mod( 'wooshop_products' ) );
}
}
add_filter( 'loop_shop_per_page', 'hoot_woo_loop_per_page', 20 );

/**
 * Override theme default specification for product # per row
 *
 * @since 1.0
 * @access public
 * @param int $value
 * @return int
 */
if ( !function_exists('hoot_woo_loop_columns') ) {
function hoot_woo_loop_columns( $value ) {
	return intval( hoot_get_mod( 'wooshop_product_columns' ) );
}
}
add_filter( 'loop_shop_columns', 'hoot_woo_loop_columns', 999 );

/**
 * Add inline style if product # per row is different
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists('hoot_woo_custom_loop_columns_css') ) {
function hoot_woo_custom_loop_columns_css() {
	$columns = hoot_get_mod( 'wooshop_product_columns' );

	if ( $columns == 4 )
		return;

	switch ( $columns ) {
		case '2':
			$css = '.woocommerce.archive ul.products li.product, .woocommerce-page.archive ul.products li.product { width: 48.1%; }'; // only on shop page
			break;
		case '3':
			$css = '.woocommerce.archive ul.products li.product, .woocommerce-page.archive ul.products li.product { width: 30.8%; }'; // only on shop page
			break;
		case '5':
			$css = '.woocommerce.archive ul.products li.product, .woocommerce-page.archive ul.products li.product { width: 16.96%; }'; // only on shop page
			break;
	}

	if ( !empty( $css ) ) {
		$handle = ( is_child_theme() ) ? 'hybridextend-template-style' : 'hybridextend-style';
		wp_add_inline_style( $handle, $css );
	}
}
}
add_action( 'wp_enqueue_scripts', 'hoot_woo_custom_loop_columns_css', 99 );

/**
 * Bug fix for Woocommerce in some installations (on posts/products singular)
 * WC_Query hooks into pre_get_posts (@priority 10) and checks for `isset( $q->queried_object->ID )`
 *   in woocommerce\includes\class=wc-query.php at line#215. This gives suppressed error
 *   "Notice: Undefined property: WP_Query::$queried_object in \wp-includes\query.php on line 3960"
 * Note that class WP_Query (\includes\query.php) does unset($this->queried_object); in WP_Query::init()
 * 
 * The proper way to chck queried object is `get_queried_object()` and not $q->queried_object
 * So we set $q->queried_object by running get_queried_object() at pre_get_posts @priority 9
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_woo_set_queried_object( $q ){
	if ( $q->is_main_query() )
		$r = get_queried_object();
}
add_action( 'pre_get_posts', 'hoot_woo_set_queried_object', 9 );

/**
 * Hide title area on single product page
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'hoot_hide_loop_meta_woo_product' ) ) :
function hoot_hide_loop_meta_woo_product() {
	return ''; // return 'hide' to hide the title on single product pages
}
endif;