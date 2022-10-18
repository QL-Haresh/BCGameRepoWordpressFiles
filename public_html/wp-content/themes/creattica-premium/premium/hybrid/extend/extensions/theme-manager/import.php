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
class HybridExtend_Theme_Manager_Import extends HybridExtend_Theme_Manager {

	/**
	 * Initialize everything
	 * 
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Perform action and populate messages and admin page content */
		add_action( 'hybridextend_theme_mgr_page', array( $this, 'do_import' ), 5 );
		add_action( 'hybridextend_theme_mgr_page', array( $this, 'print_import' ) );

	}

	/**
	 * Do Import and add messages
	 *
	 * @since 2.0.0
	 */
	function do_import() {

		if ( isset( $_POST['hybridextend-import-mod'] ) && !empty( $_POST['hybridextend-import-mod'] ) ) :
			$mods = unserialize( gzuncompress( base64_decode( $_POST['hybridextend-import-mod'] ) ) );
			$hybridextend_customize = HybridExtend_Customize::get_instance();
			$settings = $hybridextend_customize->get_options('settings');
			if ( !empty( $mods ) && !empty( $settings ) && is_array( $mods ) && is_array( $settings ) ){
				remove_theme_mods();
				$done = 0;
				foreach ( $mods as $key => $value ) {
					if ( isset( $settings[ $key ] ) ) {
						set_theme_mod( $key, $value );
						$done++;
					}
				}
				?><div id="hybridextend-mgr-message" class="notice notice-success"><p><?php printf( __( '%s Customizer Settings imported successfully.', 'hybrid-core' ), $done ); ?></p></div><?php
			} else {
				?><div id="hybridextend-mgr-message" class="notice notice-warning"><p><?php _e( 'Something went wrong. Customizer settings were not imported. Please try again later.', 'hybrid-core' ); ?></p></div><?php
			}
		endif;

	}

	/**
	 * Print Theme Manager Page Content
	 *
	 * @since 2.0.0
	 */
	function print_import() {
		?>
		<div id="hybridextend-theme-mgr-import" class="hybridextend-theme-mgr">
			<h2><?php _e( 'Import Customizer Settings', 'hybrid-core' ); ?></h2>
			<p><?php _e( 'Paste your exported code here from another theme/installation, and click the Import button.', 'hybrid-core' ); ?></p>
			<p class="warning"><?php _e( '<strong>WARNING:</strong> All your current customizer settings will be overwritten.<br /><em>(It is recommended to backup your database before making any changes to your site; or at the very least copy and save the customizer settings for current active theme below to a text file on your computer.)</em>', 'hybrid-core' ); ?></p>
			<form action="themes.php?page=hybridextendthememanager" method="post">
				<textarea id="hybridextend-import-mod" name="hybridextend-import-mod" rows="6"></textarea>
				<br />
				<input class="button button-primary hybridextend-import-mod-button" type="submit" value="<?php _e( 'Import Settings', 'hybrid-core' ); ?>" />
			</form>
		</div> <!-- .hybridextend-theme-mgr -->
		<?php
	}

}