<?php // @codingStandardsIgnoreLine
/**
 * Custom archive template support for contacts.
 *
 * @package WPCD
 */

namespace WPCD\Template;

/**
 * Class for archive support.
 */
class ArchiveTemplateSupport implements \WPCD\RegistrationInterface {
	/**
	 * Determines if the object should be registered.
	 *
	 * @since 0.1.0
	 *
	 * @return bool True if the object should be registered, false otherwise.
	 */
	public function can_register() {
		return ! is_admin();
	}

	/**
	 * Registration method for the object.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'template_include', [ $this, 'archive_template' ], 999, 1 );
	}

	/**
	 * Override the archive template.
	 *
	 * @param string $template The current archive template.
	 * @return string          The updated archive template.
	 */
	public function archive_template( $template ) {
		// Bail early if no a post type archive.
		// if ( ! is_post_type_archive( WPCD_CONTACT_POST_TYPE ) ) {
		//   return $template;
		// }

		$override_template = 'archive-wpcd-contact.php';

		// Attempt to locate the file in the theme.
		if ( '' !== locate_template( $override_template ) ) {
			$override_template = get_stylesheet_directory() . '/' . $override_template;
		} else {
			$override_template = WPCD_PLUGIN_DIR . '/templates/' . $override_template;
		}

		// Bail early if the template does not exist.
		return file_exists( $override_template ) ? $override_template : $template;
	}
}
