<?php
/**
 * Theme Manager Extension
 * This file is loaded at 'init' hook
 * This file is loaded only for is_admin()
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Theme Manager class. This wraps everything up nicely.
 *
 * @since 2.0.0
 */
class HybridExtend_Theme_Manager_Export extends HybridExtend_Theme_Manager {

	/**
	 * Initialize everything
	 * 
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Perform action and populate messages and admin page content */
		add_action( 'hybridextend_theme_mgr_page', array( $this, 'print_export' ) );

	}

	/**
	 * Print Theme Manager Page Content
	 *
	 * @since 2.0.0
	 */
	function print_export() {
		?>
		<div id="hybridextend-theme-mgr-export" class="hybridextend-theme-mgr">
			<h2><?php _e( 'Export Customizer Settings', 'hybrid-core' ); ?></h2>
			<p><?php _e( 'Copy this code to export Customizer Settings for a particular theme<br /><em><strong>(only wphoot themes are displayed here)</strong></em>', 'hybrid-core' ); ?></p>
			<?php
			$themes = $this->themes();
			$current = get_option( 'stylesheet' );
			foreach ( $themes as $theme_slug => $theme ) {
				$onlyhoot = apply_filters( 'hybridextend_exporter_only_hoot_themes', true );
				if ( !$onlyhoot ||
					 ( isset( $theme['author'] ) && stripos( $theme['author'], 'wphoot' ) !== false )
					 ) {
					$theme_name = ( !empty( $theme['name'] ) ) ? $theme['name'] : '';
					$mods = $this->theme_mods( $theme_slug, $theme_name );
					$iscurrent = ( $current == $theme_slug ) ? ' <span>' . __( 'Active Theme', 'hybrid-core' ) . '</span>' : '';
					echo '<h4>' . $theme_name . $iscurrent . '</h4>';
					if ( !empty( $mods ) )
						echo '<textarea id="' . esc_attr( "hybridextend-$theme_slug" ) . '" class="hybridextend-theme-mgr-export-mod" name="hybridextend-theme-mgr-export[' . $theme_slug . ']" rows="3"  readonly="readonly" onclick="this.select()">' . esc_textarea( base64_encode( gzcompress( serialize ( $mods ) ) ) ) . '</textarea>';
					else
						echo '<p>' . __( 'No customizer settings available yet for this theme.', 'hybrid-core' ) . '</p>';
				}
			}
			?>
		</div> <!-- .hybridextend-theme-mgr -->
		<?php
	}

	/**
	 * Get Themes
	 *
	 * @since 2.0.0
	 */
	function themes() {

		global $wp_themes;
		$return = array();

		if ( !empty( $wp_themes ) )
			$themes = $wp_themes;
		else
			$themes = wp_get_themes();

		if ( is_array( $themes ) && !empty( $themes ) ) {
			foreach ( $themes as $theme ) {
				$slug = $theme->get_stylesheet();
				$return[ $slug ][ 'name' ] = $theme->get('Name');
				$return[ $slug ][ 'author' ] = $theme->get('Author');
			}
		}
		return $return;

	}

	/**
	 * Get Theme Mods
	 *
	 * @since 2.0.0
	 */
	function theme_mods( $theme_slug, $theme_name = '' ) {
		$mods = get_option( "theme_mods_$theme_slug" ); // Theme Slug
		if ( false === $mods ) {
			$mods = get_option( "mods_$theme_name" ); // Theme Name // Deprecated location.
		}
		return $mods;
	}

}