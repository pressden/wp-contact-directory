<?php // @codingStandardsIgnoreLine
/**
 * Creates the Contact Details metabox.
 *
 * @package WPCD
 */

namespace WPCD\Admin\MetaBox;

/**
 * A class for the Contact Details meta box.
 */
class ContactDetailsMetaBox extends AbstractMetaBox {
	/**
	 * Determines if this metabox can be registered.
	 *
	 * @return bool True if metabox should be registered, false otherwise.
	 */
	public function can_register() {
		return class_exists( '\Fieldmanager_Group' );
	}

	/**
	 * Register hooks for the metabox.
	 *
	 * @return void
	 */
	public function register() {
		// Register the meta box.
		foreach ( $this->get_post_types() as $post_type ) {
			add_action( "fm_post_{$post_type}", [ $this, 'get_meta_box' ] );
		}
	}

	/**
	 * Returns the taxonomies this metabox should be registered to.
	 *
	 * @return array The post types to register the metabox to.
	 */
	protected function get_post_types() {
		return [
			WPCD_CONTACT_POST_TYPE,
		];
	}

	/**
	 * Returns the metabox name.
	 *
	 * @return string The metabox name.
	 */
	public function get_meta_box_name() {
		return self::META_BOX_PREFIX . 'contact_details';
	}

	/**
	 * Initializes the meta box.
	 *
	 * @return void
	 */
	public function get_meta_box() {
		// Create fields.
		$fields = [
			'description'  => new \Fieldmanager_Textfield(
				[
					'label'            => esc_html__( 'Job Title / Description', 'wpcd' ),
					'input_type'       => 'text',
					'validation_rules' => [
						'required' => false,
					],
				]
			),
			'location' => new \Fieldmanager_Textfield(
				[
					'label'            => esc_html__( 'Location', 'wpcd' ),
					'input_type'       => 'text',
					'validation_rules' => [
						'required' => false,
					],
				]
			),
			'email'    => new \Fieldmanager_Textfield(
				[
					'label'            => esc_html__( 'Email', 'wpcd' ),
					'input_type'       => 'text',
					'validation_rules' => [
						'required' => false,
					],
				]
			),
			'twitter'  => new \Fieldmanager_Textfield(
				[
					'label'            => esc_html__( 'Twitter', 'wpcd' ),
					'input_type'       => 'url',
					'description'      => esc_html__( 'Full URL.', 'wpcd' ),
					'validation_rules' => [
						'required' => false,
					],
				]
			),
			'linkedin' => new \Fieldmanager_Textfield(
				[
					'label'            => esc_html__( 'LinkedIn', 'wpcd' ),
					'input_type'       => 'url',
					'description'      => esc_html__( 'Full URL.', 'wpcd' ),
					'validation_rules' => [
						'required' => false,
					],
				]
			),
		];

		$fm = new \Fieldmanager_Group(
			[
				'name'           => $this->get_meta_box_name(),
				'children'       => $fields,
				'serialize_data' => false,
			]
		);

		$fm->add_meta_box( esc_html__( 'Contact Details', 'wpcd' ), $this->get_post_types() );
	}
}
