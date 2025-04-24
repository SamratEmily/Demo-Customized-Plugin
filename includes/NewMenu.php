<?php

namespace WeLabs\Demo;

if (!defined('ABSPATH')) {
    exit;
}

class NewMenu {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * Add menu to admin panel
     *
     * @return void
     */
    public function add_admin_menu() {
        add_menu_page(
            __('New Menu', 'demo2'), // Page title
            __('New Menu', 'demo2'), // Menu title
            'manage_options', // Capability required
            'new-menu', // Menu slug
            array($this, 'render_page'), // Callback function
            'dashicons-admin-generic', // Icon
            31 // Position (after WP List Table)
        );
    }

    /**
     * Render the admin page
     *
     * @return void
     */
    public function render_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'demo2'));
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_menu_submit'])) {
            // Verify nonce
            if (!isset($_POST['new_menu_nonce']) || !wp_verify_nonce($_POST['new_menu_nonce'], 'new_menu_action')) {
                wp_die(__('Security check failed.', 'demo2'));
            }

            // Process form data here
            echo '<div class="updated"><p>' . esc_html__('Settings saved successfully!', 'demo2') . '</p></div>';
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('New Menu Page', 'demo2'); ?></h1>
            
            <div class="card">
                <h2><?php echo esc_html__('Welcome to New Menu', 'demo2'); ?></h2>
                <p><?php echo esc_html__('This is a new admin menu page with proper security measures.', 'demo2'); ?></p>
            </div>

            <form method="post">
                <?php wp_nonce_field('new_menu_action', 'new_menu_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="new_menu_field"><?php echo esc_html__('Sample Field', 'demo2'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="new_menu_field" id="new_menu_field" class="regular-text">
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="new_menu_submit" class="button button-primary" value="<?php echo esc_attr__('Save Changes', 'demo2'); ?>">
                </p>
            </form>
        </div>
        <?php
    }
} 