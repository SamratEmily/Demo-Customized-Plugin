<?php

namespace WeLabs\Demo;

class VendorSetting {
    /**
     * The constructor.
     */
    public function __construct() {
        add_action( 'dokan_settings_after_store_name', [ $this, 'add_company_name' ], 12, 2 );
        add_action( 'dokan_store_profile_saved', [ $this, 'save_company_name' ], 10, 2 );
    }

    /**
     * Display the company name input field in the vendor settings form.
     *
     * @param int $user_id The vendor user ID.
     */
    public function add_company_name( $user_id ) {
        // Retrieve the company name from user meta
        $company_name = get_user_meta( $user_id, '_company_name', true );
        require_once DEMO_TEMPLATE_DIR . '/vendor.php';
    }

    /**
     * Save the company name when the vendor settings are saved.
     *
     * @param int $user_id The vendor user ID.
     * @param array $dokan_settings The array of vendor settings being saved.
     */
    public function save_company_name( $user_id, $dokan_settings ) {
        // Check if the company name is set in the POST request
        if ( isset( $_POST['company_name'] ) ) {
            $company_name = sanitize_text_field( $_POST['company_name'] );

            // Save the company name in the user_meta table
            update_user_meta( $user_id, '_company_name', $company_name );
        }
    }
}
