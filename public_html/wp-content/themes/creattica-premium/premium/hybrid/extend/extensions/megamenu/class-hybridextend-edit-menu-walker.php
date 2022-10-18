<?php
/**
 * Walker Class for Edit Nav Menu (sync WP.5.2.4)
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Create HTML list of nav menu input items in Edit Menu screen in backend
 * Copied and Modified from Walker_Nav_Menu_Edit class in core url('/wp-admin/includes/class-walker-nav-menu-edit.php')
 *
 * @since 1.1.0
 *
 * @see Walker_Nav_Menu
 */
class HybridExtend_Edit_Menu_Walker extends Walker_Nav_Menu_Edit {

	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @since WP.3.0.0
	 *
	 * @global int $_wp_nav_menu_max_depth
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int    $id     Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$item_output = '';

		// Get the item content from the parent class
		parent::start_el( $item_output, $item, $depth, $args, $id );

		// Start an output buffer
		ob_start();

		/**
		 * Fires just before the move field of a nav menu item in the menu editor.
		 *
		 * @since unknown
		 *
		 * @param int    $item_id The item ID.
		 * @param object $item    The nav menu item.
		 * @param int    $depth   The current walker depth.
		 * @param array  $args    An array of arguments for walking the tree.
		 */
		do_action( 'wp_nav_menu_item_custom_fields', $item->ID, $item, $depth, $args );

		// Get the contents of the output buffer
		$custom_fields = ob_get_clean();

		// Append the contents of the output buffer to the nav menu item and
		// append that to the walker output
		$output .= preg_replace( '/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/',
			$custom_fields,
			$item_output
		);

	}

} // HybridExtend_Edit_Menu_Walker