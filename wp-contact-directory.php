<?php
/**
 * Plugin Name: WordPress Contact Directory
 * Description: Directory for contacts.
 * Version:     0.1.0
 * Author:      Boulton
 * License:     GPLv2 or later
 * Text Domain: wpcd
 * Domain Path: /languages/
 *
 * @package WordPress Client Directory
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wrapper around PHP's define function. The defined constant is
 * ignored if it has already been defined. This allows the
 * config.local.php to override any constant in config.php.
 *
 * @since 0.1.0
 *
 * @param string $name  The constant name.
 * @param mixed  $value The constant value.
 * @return void
 */
function wpcd_define( $name, $value ) {
	// Bail early if already defined.
	if ( defined( $name ) ) {
		return;
	}

	// Define the constant.
	define( $name, $value );
}

if ( file_exists( __DIR__ . '/config.test.php' ) && defined( 'PHPUNIT_RUNNER' ) ) {
	require_once __DIR__ . '/config.test.php';
}

if ( file_exists( __DIR__ . '/config.local.php' ) ) {
	require_once __DIR__ . '/config.local.php';
}

require_once __DIR__ . '/config.php';

/**
 * Loads the WPCD PHP autoloader if possible.
 *
 * @since 0.1.0
 *
 * @return bool True or false if autoloading was successful.
 */
function wpcd_autoload() {
	// Bail early if it cannot be autoloaded.
	if ( ! wpcd_can_autoload() ) {
		return false;
	}

	require_once wpcd_autoloader();
	return true;
}

/**
 * In server mode we can autoload if autoloader file exists. For
 * test environments we prevent autoloading of the plugin to prevent
 * global pollution and for better performance.
 *
 * @since 0.1.0
 *
 * @return bool True if the plugin can be autoloaded, false otherwise.
 */
function wpcd_can_autoload() {
	// Bail early if the autoloader doesn't exist.
	if ( ! file_exists( wpcd_autoloader() ) ) {
		error_log( 'Fatal Error: Composer not setup in ' . WPCD_PLUGIN_DIR ); // @codingStandardsIgnoreLine
		return false;
	}

	return true;
}

/**
 * Default is Composer's autoloader.
 *
 * @since 0.1.0
 *
 * @return string The path to the composer autoloader.
 */
function wpcd_autoloader() {
	return WPCD_PLUGIN_DIR . '/vendor/autoload.php';
}

/**
 * Plugin code entry point. Singleton instance is used to maintain a common single
 * instance of the plugin throughout the current request's lifecycle.
 *
 * If autoloading failed an admin notice is shown and logged to
 * error_log.
 *
 * @since 0.1.0
 *
 * @return void
 */
function wpcd_autorun() {
	// Bail early if plugin cannot be autoloded.
	if ( ! wpcd_autoload() ) {
		add_action( 'admin_notices', 'wpcd_autoload_notice' );
		return;
	}

	// Bail early if plugin requirements are not met.
	if ( ! wpcd_requirements_met() ) {
		add_action( 'admin_notices', 'wpcd_requirements_notice' );
		return;
	}

	$plugin = \WPCD\Plugin::get_instance();
	$plugin->enable();
}

/**
 * Displays notice if the plugin cannot be autoloaded.
 *
 * @since 0.1.0
 *
 * @return void
 */
function wpcd_autoload_notice() {
	$class   = 'notice notice-error';
	$message = 'Error: Please run $ composer install in the WordPress Contact Directory plugin directory.';

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_html( $class ), wp_kses_post( $message ) );
	error_log( $message ); // @codingStandardsIgnoreLine
}

/**
 * Determines if requirements are met for this plugin.
 *
 * @since 0.1.0
 *
 * @return bool True if the plugin requirements are met, false otherwise.
 */
function wpcd_requirements_met() {
	return class_exists( '\Fieldmanager_Group' );
}

/**
 * Displays notice if the plugin cannot be autoloaded.
 *
 * @since 0.1.0
 *
 * @return void
 */
function wpcd_requirements_notice() {
	?>
	<p>
		<?php
		wp_kses_post(
			sprintf(
				__(
					'This plugin requires <a href="%1$s">Fieldmanager</a>.',
					'wpcd'
				),
				'https://github.com/alleyinteractive/wordpress-fieldmanager'
			)
		);
		?>
	</p>
	<?php
}

// Kick off the plugin.
wpcd_autorun();
