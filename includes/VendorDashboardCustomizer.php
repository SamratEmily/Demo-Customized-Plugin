<?php

namespace WeLabs\Demo;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * class VendorDashboardCustomizer
 */
class VendorDashboardCustomizer {
    public function __construct() {
        // add_filter('dokan_get_template_path', array($this, 'custom_template_path'), 10, 3);

        add_action( 'dokan_dashboard_content_before', array( $this, 'dashboard_top_bar' ) );

    }

    public function dashboard_top_bar() {
		require  DEMO_TEMPLATE_DIR . '/top-bar.php';
	}


    /**
     * Use custom template for Dokan sidebar and topbar
     */
    public function custom_template_path($template_path, $template, $args) {
        // Check for sidebar or topbar template
        if (
            strpos($template, 'global/sidebar.php') !== false ||
            strpos($template, 'global/top-bar.php') !== false
        ) {
            return "Sunshine";
        }
        return $template_path;
    }
} 