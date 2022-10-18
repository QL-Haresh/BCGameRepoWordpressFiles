<?php
/**
 * Auto Theme Updater
 * This file is loaded at 'init' hook
 * This file is loaded only for is_admin()
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Theme Updater class. This wraps everything up nicely.
 *
 * @since 2.0.0
 */
final class HybridExtend_Theme_Updater {

	protected $args = array();

	public function __construct( $args = array() ) {

		global $hybridextend_theme;

		$this->args = wp_parse_args( $args, array(
			'remote_api_url' => 'https://wphoot.com',         // Gets from HybridExtend_Theme_Manager_Autoupgrade
			'request_data' => array(),
			'theme_slug' => str_replace( '_', '-', HYBRIDEXTEND_THEMESLUG ), // get_template() = directory name
			'premium_slug' => strtolower( preg_replace( '/[^a-zA-Z0-9]+/', '-', trim( HYBRIDEXTEND_THEME_NAME ) ) ), // instead of directory name
			'item_name' => HYBRIDEXTEND_TEMPLATE,             // Gets from HybridExtend_Theme_Manager_Autoupgrade
			'license' => '',                                  // Gets from HybridExtend_Theme_Manager_Autoupgrade
			'version' => HYBRIDEXTEND_THEME_VERSION,          // Gets from HybridExtend_Theme_Manager_Autoupgrade
			'author' => $hybridextend_theme->get( 'Author' ), // Gets from HybridExtend_Theme_Manager_Autoupgrade
			'beta' => false,
		) );
		$this->args['response_key'] = str_replace( '_', '-', HYBRIDEXTEND_THEMESLUG ) . '-update-response';

		add_filter( 'site_transient_update_themes', array( &$this, 'theme_update_transient' ) );
		add_filter( 'delete_site_transient_update_themes', array( &$this, 'delete_theme_update_transient' ) );
		add_action( 'load-update-core.php', array( &$this, 'delete_theme_update_transient' ) );
		add_action( 'load-themes.php', array( &$this, 'delete_theme_update_transient' ) );
		add_action( 'load-themes.php', array( &$this, 'load_themes_screen' ) );

	}

	function load_themes_screen() {
		add_thickbox();
		add_action( 'admin_notices', array( &$this, 'update_nag' ) );
	}

	function update_nag() {
		$nag = '';

		$api_response = get_transient( $this->args['response_key'] );
		if ( false !== $api_response ) {
			// var_dump($this->args['version'], $api_response->new_version); // @TS
			if ( version_compare( $this->args['version'], $api_response->new_version, '<' ) ) {
				$nag .= sprintf(
					__('New version <strong>%2$s</strong> available for <strong>%1$s</strong>', 'hybrid-core' ),
					HYBRIDEXTEND_THEME_NAME, // HYBRIDEXTEND_TEMPLATE,
					$api_response->new_version
				);
			}
		}

		if ( !empty( $nag ) )
			echo '<div id="update-nag">' . $nag . '</div>';

		return;
	}

	function theme_update_transient( $value ) {
		$update_data = $this->check_for_update();
		if ( $update_data ) {
			$value->response[ $this->args['premium_slug'] ] = $update_data;
		}
		return $value;
	}

	function delete_theme_update_transient() {
		delete_transient( $this->args['response_key'] );
	}

	function check_for_update() {

		$update_data = get_transient( $this->args['response_key'] );

		if ( false === $update_data ) {
			$failed = false;

			$api_params = array(
				'edd_action' 	=> 'get_version',
				'license' 		=> $this->args['license'],
				'name' 			=> $this->args['item_name'],
				'slug' 			=> $this->args['theme_slug'],
				'version'		=> $this->args['version'],
				'author'		=> $this->args['author'],
				'beta'			=> $this->args['beta']
			);

			$response = wp_remote_post( $this->args['remote_api_url'], array( 'timeout' => 15, 'body' => $api_params ) );

			// Make sure the response was successful
			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
				$failed = true;
			}

			$update_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! is_object( $update_data ) ) {
				$failed = true;
			}

			// If the response failed, try again in 30 minutes
			if ( $failed ) {
				$data = new stdClass;
				$data->new_version = $this->args['version'];
				set_transient( $this->args['response_key'], $data, strtotime( '+30 minutes', current_time( 'timestamp' ) ) );
				return false;
			}

			// If the status is 'ok', return the update arguments
			if ( ! $failed ) {
				$update_data->sections = maybe_unserialize( $update_data->sections );
				set_transient( $this->args['response_key'], $update_data, strtotime( '+12 hours', current_time( 'timestamp' ) ) );
			}
		}

		if ( version_compare( $this->args['version'], $update_data->new_version, '>=' ) ) {
			return false;
		}

		return (array) $update_data;
	}

}