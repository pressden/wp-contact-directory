<?php // @codingStandardsIgnoreLine
/**
 * Base class for meta boxes.
 *
 * @package WPCD
 */

namespace WPCD\Admin\MetaBox;

/**
 * Abstract class for meta boxes.
 */
abstract class AbstractMetaBox implements \WPCD\RegistrationInterface {
	/**
	 * The prefix for metaboxes.
	 *
	 * @var string
	 */
	const META_BOX_PREFIX = 'wpcd_';

	/**
	 * Registers the metabox hooks.
	 *
	 * @return void
	 */
	abstract public function register();

	/**
	 * Returns the metabox name.
	 *
	 * @return string The name for the meta box.
	 */
	abstract public function get_meta_box_name();

	/**
	 * Initializes the meta box.
	 *
	 * @return void
	 */
	abstract public function get_meta_box();

	/**
	 * Determines if this metabox can be registered.
	 *
	 * @return bool True if metabox should be registered, false otherwise.
	 */
	public function can_register() {
		return true;
	}

	/**
	 * Returns the taxonomies this metabox should be registered to.
	 *
	 * @return array The taxonomies to register the metabox to.
	 */
	protected function get_taxonomies() {
		return [];
	}

	/**
	 * Returns the post types this metabox should be registered to.
	 *
	 * @return array The post types to register the metabox to.
	 */
	protected function get_post_types() {
		return [];
	}

	/**
	 * Returns the current post ID of the screen.
	 *
	 * @return int The current post ID.
	 */
	protected function get_current_post_id() {
		// Try getting the ID from GET data.
		$post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		// Fallback to post data.
		if ( empty( $post_id ) ) {
			$post_id = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );
		}

		return $post_id;
	}

	/**
	 * Returns the current post type of the screen.
	 *
	 * @return string The current post type.
	 */
	protected function get_current_post_type() {
		global $pagenow;

		// Bail early if page isn't an edit post page.
		if ( ! in_array( $pagenow, [ 'post.php', 'post-new.php' ], true ) ) {
			return null;
		}

		// Get the post ID.
		$post_id = $this->get_current_post_id();

		// If there's a post ID, return the post type.
		if ( ! empty( $post_id ) ) {
			return get_post_type( $post_id );
		}

		// Get the post type from query string.
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_ENCODED );

		return $post_type ?? WPCD_POST_POST_TYPE;

	}
}
