<?php
/**
 * Plugin Name:       Karlog-IT Simple SSL
 * Description:       A simple WordPress plugin that redirects your site to HTTPS!
 * Version:           1.1
 * Author:            Karlog-IT
 * Author URI:        https://karlog-IT.dk
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace HTTPS_Direct;
require 'helpers/Handler.php';

class HTTPS_Direct {
	private static $_instance = null;

	public static function Instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		spl_autoload_register( function ( $class ) {
			if ( stripos( $class, __NAMESPACE__ ) === 0 ) {
				/** @noinspection PhpIncludeInspection */
				@include strtolower(
					implode( DIRECTORY_SEPARATOR, [
						realpath( __DIR__ ),
						trim(
							strtr( $class, [
								__NAMESPACE__ => '',
								'\\'          => DIRECTORY_SEPARATOR,
							] ),
							DIRECTORY_SEPARATOR
						) . '.php',
					] )
				);
			}
		} );

		add_action( 'admin_menu', [ $this, 'setup_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'styles_scripts' ] );

		Helpers\Handler::Instance();
	}

	function styles_scripts() {
		wp_enqueue_style( 'https-direct-css', plugin_dir_url( __FILE__ ) . 'custom/style.css' );
		wp_enqueue_script( 'https-direct-js', plugin_dir_url( __FILE__ ) . 'custom/script.js', array( 'jquery' ), null );
		$array = array(
		        'admin_url' => get_bloginfo('wpurl'),
			'plugins_dir' => plugin_dir_url( __FILE__ ),
		);
		wp_localize_script( 'https-direct-js', 'WPURLS', $array );
	}

	function setup_menu() {
		add_menu_page( 'Simple SSL', 'Simple SSL', 'manage_options', 'Karlog-IT-Simple-SSL', [ $this, 'admin_page' ] );
	}

	function admin_page() {
		?>
        <div class="<?=strtr(get_class($this),['\\'=>'_'])?>">
            <h1>Simple SSL</h1>
            <h2>By <a target="_blank" href="https://karlog-it.dk">Karlog-IT</a></h2>
            <p>A simple WordPress plugin that redirects your site to HTTPS!</p>
            <p style="font-size: 0.9em">
                By pressing enable we configure the .htaccess file to redirect all requests to HTTPS and updates your<br>
                general settings to use HTTPS.
            </p>
            <p style="font-size: 0.9em">
                With this, it does require to have a valid SSL certificate that we do not contribute.
            </p>
            <div class="statuslist">
                <p style="float: left">.htaccess file</p>
                <p style="float: right"class="status <?=Helpers\Handler::getStatus()[0] == true ? 'enabled">Active</p>' : 'disabled">Inactive</p>'?>
                <div style="clear: both"></div>
                <p style="float: left">General settings</p>
                <p  style="float: right" class="status <?=Helpers\Handler::getStatus()[1] == true ? 'enabled">Active</p>' : 'disabled">Inactive</p>'?>
                <div style="clear: both"></div>
            </div>
            <p>
                <label class="status">
                    <input <?=Helpers\Handler::$status[0] ? 'checked' : ''?> type="checkbox" name="https">
                    <span>Enable</span>
                </label>
            </p>
        </div>
		<?php
	}
}

HTTPS_Direct::Instance();
