<?php
/**
 * Holds configuration constants.
 *
 * @package WP Content Directory
 */

$plugin_version = '0.1';

if ( file_exists( __DIR__ . '/.commit' ) ) {
	$plugin_version .= '-' . file_get_contents( __DIR__ . '/.commit' ); // @codingStandardsIgnoreLine
}

// Plugin Constants.
wpcd_define( 'WPCD_BUILDER_PLUGIN', __DIR__ . '/wp-content-directory.php' );
wpcd_define( 'WPCD_PLUGIN_VERSION', $plugin_version );
wpcd_define( 'WPCD_PLUGIN_DIR', __DIR__ );
wpcd_define( 'WPCD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Metabox prefix.
wpcd_define( 'WPCD_METABOX_PREFIX', 'wpcd_' );

// Post Types.
wpcd_define( 'EP_PERSON_POST_TYPE', 'ep-person' );

// Taxonomies.
wpcd_define( 'EP_PERSON_TYPE_TAXONOMY', 'ep-person-type' );
