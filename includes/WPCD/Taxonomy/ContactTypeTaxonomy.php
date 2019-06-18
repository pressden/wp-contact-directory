<?php // @codingStandardsIgnoreLine
/**
 * The contact type taxonomy is used to categorize contacts.
 *
 * Usage:
 *
 * ```php
 *
 * $taxonomy = new ContactTypeTaxonomy();
 * $taxonomy->register();
 *
 * ```
 *
 * @package ElasticPress Rules Builder
 */

namespace WPCD\Taxonomy;

/**
 * A class for the Content Type Taxonomy.
 */
class ContactTypeTaxonomy extends AbstractTaxonomy {
	/**
	 * Returns the name of the taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @return string The name of the taxonomy.
	 */
	public function get_name() {
		return WPCD_CONTACT_TYPE_TAXONOMY;
	}

	/**
	 * Returns the singular name for the taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @return string The singular name for the taxonomy.
	 */
	public function get_singular_label() {
		return esc_html__( 'Contact Type', 'wpcd' );
	}

	/**
	 * Returns the plural name for taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @return string The plural name for the taxonomy.
	 */
	public function get_plural_label() {
		return esc_html__( 'Contact Types', 'wpcd' );
	}

	/**
	 * Options for the taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @return array Options for the taxonomy.
	 */
	public function get_options() {
		return [
			'labels'            => $this->get_labels(),
			'hierarchical'      => true,
			'public'            => false,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_ui'           => true,
			'capability_type'   => 'post',
		];
	}
}
