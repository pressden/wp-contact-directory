<?php // @codingStandardsIgnoreLine
/**
 * An interface for registration objects to implement.
 *
 * @package WP Contact Directory
 */

namespace WPCD;

/**
 * Interface for registration objects to implement.
 */
interface RegistrationInterface {
	/**
	 * Determines if the object should be registered.
	 *
	 * @since 0.1.0
	 *
	 * @return bool True if the object should be registered, false otherwise.
	 */
	public function can_register();

	/**
	 * Registration method for the object.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register();
}
