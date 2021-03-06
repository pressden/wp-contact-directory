<?php // @codingStandardsIgnoreLine
/**
 * Base plugin class for the WP Contact Directory.
 *
 * @package WP Contact Directory
 */

namespace WPCD;

/**
 * This Plugin's main class.
 * All subclasses/modules are managed from within this class.
 * This class is used as a singleton and should not be instantiated directly.
 *
 * Usage:
 *
 * ```php
 *
 * $plugin = \WPCD\Plugin::get_instance();
 *
 * ```
 */
class Plugin {
	/**
	 * Holds a single instance of this class.
	 *
	 * @since 0.1.0
	 *
	 * @var \WPCD\Plugin
	 */
	private static $singleton_instance = null;

	/**
	 * Holds admin support objects.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $admin_support = [];

	/**
	 * Holds admin menu support objects.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $admin_menu_support = [];

	/**
	 * Holds support objects.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $support = [];

	/**
	 * Returns a single instance of this class.
	 *
	 * @since 0.1.0
	 *
	 * @return \WPCD\Plugin A singleton instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$singleton_instance ) {
			self::$singleton_instance = new self();
		}

		return self::$singleton_instance;
	}

	/**
	 * Starts the plugin by subscribing to the WordPress lifecycle hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enable() {
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_init', [ $this, 'init_admin' ], 20 );
		add_action( 'admin_menu', [ $this, 'init_admin_menu' ], 20 );
		add_action( 'admin_enqueue_scripts', [ $this, 'init_admin_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'init_editor_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'init_frontend_assets' ] );
	}

	/**
	 * Runs on init WP lifecycle hook.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init() {
		// Initialize admin support classes.
		if ( is_admin() ) {
			$this->init_admin_support();
		}

		// Supporting classes for the plugin that should be registered on the init hook.
		$this->support = [
			// Misc.
			'archive_support'       => new Template\ArchiveTemplateSupport(),

			// Taxonomies.
			'contact_type_taxonomy' => new Taxonomy\ContactGroupTaxonomy(),

			// Post Types.
			'contact_post_type'     => new PostType\ContactPostType(),

			// Assets.
			'assets'     => new Assets(),
		];

		// Register objects.
		$this->register_objects( $this->support );
	}

	/**
	 * Enqueue scripts for the admin pages.
	 *
	 * @return void
	 */
	public function init_admin_scripts() {
		wp_enqueue_script( 'wpcd-admin' );

		$l10n = [];

		wp_localize_script( 'wpcd-admin', 'WPCDAdmin', $l10n );
	}

	/**
	 * Enqueue scripts & styles for the Gutenberg editor. This fires on the block
	 * enqueue hence safe to assume that Gutenberg is active.
	 *
	 * @return void
	 */
	public function init_editor_assets() {
		wp_enqueue_script( 'wpcd-editor' );
	}

	/**
	 * Enqueue scripts & styles for the frontend.
	 *
	 * @return void
	 */
	public function init_frontend_assets() {
		wp_enqueue_script( 'wpcd-frontend' );
		wp_enqueue_style( 'wpcd-frontend' );
	}

	/**
	 * Initializes admin support.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	protected function init_admin_support() {
		$this->admin_support = [
			// Meta Boxes.
			'contact_details_meta_box' => new Admin\MetaBox\ContactDetailsMetaBox(),
		];

		// Register objects.
		$this->register_objects( $this->admin_support );
	}

	/**
	 * Runs on the admin_init WP lifecycle hook.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init_admin() {
		$this->admin_support = [];

		// Register objects.
		$this->register_objects( $this->admin_support );
	}

	/**
	 * Runs on the init_admin_menu WP lifecycle hook.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init_admin_menu() {
		$this->admin_menu_support = [];
		// Register objects.
		$this->register_objects( $this->admin_menu_support );
	}

	/**
	 * Registers an array of objects.
	 *
	 * @since 0.1.0
	 *
	 * @param array $objects The array of objects to register.
	 * @return void
	 */
	protected function register_objects( array $objects ) {
		array_map( [ $this, 'register_object' ], $objects );
	}

	/**
	 * Registers a single object.
	 *
	 * @since 0.1.0
	 *
	 * @param object $object The object to register.
	 * @return void
	 */
	protected function register_object( $object ) {
		// Bail early if there are no registration methods.
		if ( ! ( $object instanceof \WPCD\RegistrationInterface ) ) {
			return;
		}

		// Bail early if the object cannot be registered.
		if ( ! $object->can_register() ) {
			return;
		}

		// Register the object.
		$object->register();
	}
}
