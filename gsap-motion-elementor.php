<?php
/**
 * Plugin Name:       GSAP Motion for Elementor
 * Plugin URI:        https://example.com/gsap-motion-elementor
 * Description:       Professional, all-in-one GSAP animation toolkit for Elementor. Animate any widget, section, or container with ScrollTrigger, SplitText, Flip, MotionPath, DrawSVG, and more.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Iftiar Hossain
 * Author URI:        https://iftiarhossain.com
 * License:            GPL v2 or later
 * License URI:        https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:        gsap-motion-elementor
 * Domain Path:        /languages
 * Elementor tested up to: 4.0
 * Elementor Pro tested up to: 4.0
 *
 * @package GME
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core plugin Constants
define( 'GME_VERSION', '1.0.0' );
define( 'GME_PLUGIN_FILE', __FILE__ );
define( 'GME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GME_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GME_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'GME_MIN_PHP_VERSION', '7.4' );
define( 'GME_MIN_WP_VERSION', '6.0' );
define( 'GME_MIN_ELEMENTOR_VERSION', '3.15.0' );

// Composer autoloader
if( file_exists( GME_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require_once GME_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    wp_die( esc_html__( 'Please run "composer install" in the plugin directory.', 'gsap-motion-elementor' ) );
}

function gme_boot_plugin() {

	// Guard: PHP version too old.
	if ( version_compare( PHP_VERSION, GME_MIN_PHP_VERSION, '<' ) ) {
		add_action( 'admin_notices', 'gme_admin_notice_php_version' );
		return;
	}

	// Guard: Elementor not installed or not active.
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'gme_admin_notice_missing_elementor' );
		return;
	}

	// Guard: Elementor version too old.
	if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, GME_MIN_ELEMENTOR_VERSION, '<' ) ) {
		add_action( 'admin_notices', 'gme_admin_notice_elementor_version' );
		return;
	}

	// All checks passed — hand off to the real plugin engine.
	\GME\Core\Plugin::instance();
}
add_action( 'plugins_loaded', 'gme_boot_plugin' );

/**
 * Admin notice: PHP version too low.
 */

function gme_admin_notice_php_version() {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: 1: Required PHP version, 2: Current PHP version */
				__( 'GSAP Motion for Elementor requires PHP version %1$s or higher. You are running version %2$s. Please contact your host to upgrade.', 'gsap-motion-elementor' ),
				GME_MIN_PHP_VERSION,
				PHP_VERSION
			)
		)
	);
}

/**
 * Admin notice: Elementor missing.
 */
function gme_admin_notice_missing_elementor() {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html__( 'GSAP Motion for Elementor requires Elementor to be installed and activated.', 'gsap-motion-elementor' )
	);
}

/**
 * Admin notice: Elementor version too old.
 */
function gme_admin_notice_elementor_version() {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %s: Required Elementor version */
				__( 'GSAP Motion for Elementor requires Elementor version %s or higher. Please update Elementor.', 'gsap-motion-elementor' ),
				GME_MIN_ELEMENTOR_VERSION
			)
		)
	);
}

/**
 * Activation hook.
 */

register_activation_hook( GME_PLUGIN_FILE, function() {
	if ( file_exists( GME_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
		require_once GME_PLUGIN_DIR . 'vendor/autoload.php';
		\GME\Core\Activator::activate();
	}
} );

/**
 * Deactivation hook.
 */

register_deactivation_hook( GME_PLUGIN_FILE, function() {
	if ( file_exists( GME_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
		require_once GME_PLUGIN_DIR . 'vendor/autoload.php';
		\GME\Core\Deactivator::deactivate();
	}
} );