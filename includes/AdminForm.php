<?php

namespace WeLabs\Demo;

class AdminForm {
    public function __construct() {
        add_action( 'admin_menu', [$this, 'add_admin_form'] );
    }

    public function add_admin_form() {
        $capability = 'manage_options';

        $slug = 'vendor_credit_notes';

        add_menu_page(
            __( 'Vendor Credit Notes', 'demo2' ),
            __( 'Vendor Credit Notes', 'demo2' ),
            $capability,            
            $slug,                      
            [$this, 'render_admin_page'],        
            'dashicons-media-spreadsheet',       
            5                                    
        );
    }

    public function render_admin_page() {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Vendor Credit Notes', 'demo2' ) . '</h1>';
        echo '<p>' . esc_html__( 'This is the admin page content.', 'demo2' ) . '</p>';
        echo '</div>';
    }
}
