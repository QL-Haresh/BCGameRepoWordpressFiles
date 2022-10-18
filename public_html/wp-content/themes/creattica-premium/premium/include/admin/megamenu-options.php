<?php
/**
 * Build Megamenu Options
 *
 * @package    Hoot
 * @subpackage framework
 */

/**
 * Defines an array of megamenu options that will be used to generate the megamenu.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * Child themes can modify the megamenu options array using the 'hybridextend_megamenu_options' filter hook.
 *
 * @since 1.0
 * @param object $hybridextend_megamenu
 * @return void
 */
function hoot_megamenu_options( $hybridextend_megamenu ) {

	$options = array();

	/* Add supported options */
	// Keys must be small caps with no spaces (used as css ids, and as meta key stored in database)
	if ( hybridextend_theme_supports( 'hybridextend-megamenu', 'menuitem_icon' ) ) {
		$options[ 'hoot_icon' ] = array(
			'name' => __('Menu Icon', 'creattica-premium'),
			'type' => 'icon',
			//'top_level' => true,
			);
	}

	// Add megamenu options to main class options object
	$hybridextend_megamenu->add_options( $options );

}

/* Hook into action to add options */
add_action( 'hybridextend_megamenu_loaded', 'hoot_megamenu_options', 5 );

/**
 * Display code for megamenu icon
 *
 * @since 1.6
 * @param string   $title The menu item's title.
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return string
 */
function hoot_megamenu_displayicon( $title, $item, $args, $depth ) {
	$hoot_megamenu_item = ( isset( $item->ID ) ) ? get_post_meta( $item->ID, '_menu-item-hybridextend_megamenu', true ) : array();
	if ( isset( $hoot_megamenu_item[ 'hoot_icon' ] ) && !empty( $hoot_megamenu_item[ 'hoot_icon' ] ) )
		$title = '<i class="hybridextend-megamenu-icon ' . hybridextend_sanitize_fa( $hoot_megamenu_item[ 'hoot_icon' ] ) . '"></i> <span>' . $title . '</span>';
	return $title;
}
add_filter( 'nav_menu_item_title', 'hoot_megamenu_displayicon', 3, 4 ); // Hook into filter early on to display menuicon within the <span class="menu-title"> added by core theme at priority 5