<?php

namespace WeLabs\Demo;

use WeLabs\Demo\CornEmail;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EmailManager {
    public function __construct() {
        add_filter( 'woocommerce_email_classes', [ $this, 'register_custom_email' ] );
    }

    /**
     * Register our custom email.
     */
    public function register_custom_email( $emails ) {
        require_once __DIR__ . '/CornEmail.php';
        $emails['WeLabs_Demo_CornEmail'] = new CornEmail();
        return $emails;
    }
}

