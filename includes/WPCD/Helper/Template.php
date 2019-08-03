<?php // @codingStandardsIgnoreLine
/**
 * Helper functions for rendering template files.
 *
 * @package WPCD
 */

namespace WPCD;

/**
 * Helper function for including a template.
 *
 * @param string $template  The template name to include.
 * @param array  $params    An array of parameters to include with the template.
 * @return string           Markup for the template.
 */
function include_template( $template, array $params = [] ) {
	// Attempt to locate the file in the theme.
	if ( '' !== locate_template( $template ) ) {
		$template = get_stylesheet_directory() . '/' . $template;
	} else {
		$template = WPCD_PLUGIN_DIR . '/templates/' . $template;
	}

	// Bail early if the template does not exist.
	if ( ! file_exists( $template ) ) {
		return '';
	}

	ob_start();

	// Extract params array to make keys available as direct variables.
	extract( $params ); // @codingStandardsIgnoreLine
	include $template;
}
