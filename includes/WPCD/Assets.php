<?php // @codingStandardsIgnoreLine
/**
 * The Assets object is the central place to manage shared Javascript & CSS
 * files. The assets are registered here. Other parts of the plugin can
 * use them by enqueuing or declaring as dependencies.
 *
 * @package WPCD
 */

namespace WPCD;

/**
 * Registers scripts and styles for use throughout the plugin.
 */
class Assets implements \WPCD\RegistrationInterface {
	/**
	 * Determines whether or not to register these hooks.
	 *
	 * @return bool True if class hooks should be registered, false otherwise.
	 */
	public function can_register() {
		return true;
	}

	/**
	 * Registers the scripts & styles.
	 */
	public function register() {
		$this->register_scripts();
		$this->register_styles();
	}

	/**
	 * Registers the javascript assets
	 */
	public function register_scripts() {
		$this->script(
			'wpcd-admin',
			'dist/js/admin.js',
			[]
		);

		$this->script(
			'wpcd-editor',
			'dist/js/editor.js',
			[]
		);

		$this->script(
			'wpcd-frontend',
			'dist/js/frontend.js',
			[]
		);

	}

	/**
	 * Registers the CSS assets.
	 *
	 * @return void
	 */
	public function register_styles() {
		$this->style( 'wpcd-frontend', 'dist/css/frontend.min.css' );
	}

	/**
	 * Registers a script with defaults to use plugin revision to bust
	 * cache automatically.
	 *
	 * @param string $name        The script name.
	 * @param string $path        The relative path of the script.
	 * @param array  $deps        Optional dependency names.
	 * @param bool   $footer      Whether to output the script in the footer.
	 * @return void
	 */
	public function script( $name, $path, array $deps = [], bool $footer = true ) {
		if ( ! $this->is_script_debug() ) {
			$path = preg_replace( '#\.js$#', '.min.js', $path );
		}

		wp_register_script(
			$name,
			$this->asset_url( $path ),
			$deps,
			get_plugin_version(),
			$footer
		);
	}

	/**
	 * Registers a style with defaults to use plugin revision to bust
	 * cache automatically.
	 *
	 * @param string $name  The style name.
	 * @param string $path  The relative path of the style.
	 * @param array  $deps  Optional dependency names.
	 * @param bool   $media Default 'all'.
	 */
	public function style( $name, $path, $deps = [], $media = 'all' ) {
		if ( ! $this->is_script_debug() ) {
			$path = preg_replace( '#\.css$#', '.min.css', $path );
		}

		wp_register_style(
			$name,
			$this->asset_url( $path ),
			$deps,
			get_plugin_version(),
			$media
		);
	}

	/**
	 * Returns the base path for the assets.
	 *
	 * @param string $path The path to the asset.
	 * @return string      The fully qualified URL to the asset.
	 */
	protected function asset_url( $path ) {
		return trailingslashit( WPCD_PLUGIN_URL ) . $path;
	}

	/**
	 * Determines if script debugging is turned off.
	 *
	 * @return bool True if script debugging is on, false otherwise.
	 */
	protected function is_script_debug() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}

	/**
	 * Returns the suffix based on script debug.
	 *
	 * @return string Suffix for scripts and styles.
	 */
	protected function suffix() {
		return $this->is_script_debug()
			? '.min'
			: '';
	}

}
