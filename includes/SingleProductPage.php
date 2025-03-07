<?php

namespace WeLabs\Demo;

class SingleProductPage {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'dokan_product_edit_after_pricing', array( $this, 'add_company_name' ), 12, 2 );
		add_action( 'dokan_process_product_meta', array( $this, 'save_company_name' ), 10 );
	}

	/**
	 * Display the company name input field in the product edit form.
	 *
	 * @param int $post_id The product ID.
	 */
	public function add_company_name( $product, $post_id ) {
		if ( is_a( $product, 'WC_Product' ) ) {
			$company_name = $product->get_meta( '_company_name', true );
		} else {
			$company_name = get_post_meta( $post_id, '_company_name', true );
		}

		require_once DEMO_TEMPLATE_DIR . '/AddCompanyName.php';
	}

	/**
	 * Save the company name when the product is updated.
	 *
	 * @param int $product_id The product ID.
	 */
	public function save_company_name( $product_id ) {
		if ( isset( $_POST['company_name'] ) ) {
			$company_name = sanitize_text_field( $_POST['company_name'] );

			update_post_meta( $product_id, '_company_name', $company_name );
		}
	}
}
