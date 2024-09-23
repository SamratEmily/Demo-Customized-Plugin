<?php

namespace WeLabs\Demo;

class SingleProductPage {
    /**
     * The constructor.
     */
    public function __construct() {
        // Hook to display the company name field in the product edit form
        add_action( 'dokan_product_edit_after_pricing', [ $this, 'add_company_name' ], 12, 2 );

        // Hook to save the company name when the product is updated
        add_action( 'dokan_process_product_meta', [ $this, 'save_company_name' ], 10 );
    }

    /**
     * Display the company name input field in the product edit form.
     *
     * @param int $post_id The product ID.
     */
    public function add_company_name( $product, $post_id ) {
        // Make sure $post_id is not null, fallback to global post if necessary
        // Ensure we are working with a WC_Product object
		if ( is_a( $product, 'WC_Product' ) ) {
            // Retrieve the company name from WooCommerce's get_meta method
            $company_name = $product->get_meta( '_company_name', true );
        } else {
            // Fallback to get_post_meta if $product isn't a WC_Product object
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
        // Check if the company name is set in the POST request
        if ( isset( $_POST['company_name'] ) ) {
            $company_name = sanitize_text_field( $_POST['company_name'] );

            // Save the company name in the post_meta table
            update_post_meta( $product_id, '_company_name', $company_name );
        }
    }
}
