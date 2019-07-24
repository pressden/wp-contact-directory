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
		add_filter( 'document_title_parts', [ $this, 'archive_template_title' ], 999, 1 );
	}

	/**
	 * Override the archive template.
	 *
	 * @param string $template The current archive template.
	 * @return string          The updated archive template.
	 */
	public function archive_template( $template ) {
		global $wp_query;

		$is_archive = ! empty( $wp_query->query['pagename'] ) && ( 'contact-directory' === $wp_query->query['pagename'] );
		$is_single  = ! empty( $wp_query->query['post_type'] ) && ( WPCD_CONTACT_POST_TYPE === $wp_query->query['post_type'] );

		// Bail early if not a post type archive.
		if ( ! $is_archive && ! $is_single ) {
			return $template;
		}

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

	/**
	 * Filters the archive title.
	 *
	 * @param string $title The current title.
	 * @return string       The modified title.
	 */
	public function archive_template_title( $title_parts ) {
		global $wp_query;

		$is_archive = ! empty( $wp_query->query['pagename'] ) && ( 'contact-directory' === $wp_query->query['pagename'] );
		$is_single  = ! empty( $wp_query->query['post_type'] ) && ( WPCD_CONTACT_POST_TYPE === $wp_query->query['post_type'] );

		// Bail early if no a post type archive.
		if ( ! $is_archive && ! $is_single ) {
			return $title_parts;
		}

		$title_parts['title'] = sprintf( esc_html__( 'Contact Archive | %1$s', 'wpcd' ), get_bloginfo( 'blogname' ) ); // @codingStandardsIgnoreLine

		return $title_parts;
	}
}
