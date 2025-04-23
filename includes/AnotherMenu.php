<?php

namespace WeLabs\Demo;

if (!defined('ABSPATH')) {
    exit;   
}

class AnotherMenu {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Another Menu', 'demo2'), // Page title
            __('Another Menu', 'demo2'), // Menu title
            'manage_options', // Capability required
            'another-menu', // Menu slug
            array($this, 'render_page'), // Callback function
            'dashicons-admin-generic', // Icon
            30 // Position
        );
    }

    public function render_page() {
        ?>
        <div class="">
            <h1><?php echo esc_html__('Another Menu Page', 'demo2'); ?></h1>
            <div class="card">
                <h2><?php echo esc_html__('Welcome to Another Menu', 'demo2'); ?></h2>
                <p><?php echo esc_html__('This is a new admin menu page created for demonstration purposes.', 'demo2'); ?></p>
            </div>
        </div>
        <?php
    }
} 