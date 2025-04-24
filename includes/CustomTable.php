<?php

namespace WeLabs\Demo;

use WeLabs\Demo\MyCustomListTable;

/**
 * CustomTable Class
 */
class CustomTable {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_menu', array( $this, 'add_a_menu_for_wp_list' ) );
	}

	/**
	 * Add a menu
	 *
	 * @return void
	 */
	public function add_a_menu_for_wp_list() {
        add_menu_page(
            'WP List Table',
            'WP List Table',
            'manage_options',
            'my-custom-table',
            [ $this, 'render_my_custom_admin_table'],
            'dashicons-editor-table',
            30
        );
    }

	/**
	 * Render admin table
	 *
	 * @return void
	 */
    public function render_my_custom_admin_table() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'demo2'));
        }

        $table = new MyCustomListTable();
        $table->prepare_items();
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">WP List Table</h1>';
        echo '<form method="post">';
        // Add nonce field for security
        wp_nonce_field('wp_list_table_action', 'wp_list_table_nonce');
        $table->display();
        echo '</form>';
        echo '</div>';
    }
    
	/**
	 * Register the table when plugin activate
	 *
	 * @return void
	 */
	public static function activate() {
		// Check if user has permission to activate plugins
		if (!current_user_can('activate_plugins')) {
			return;
		}

		global $wpdb;
		$table_name      = $wpdb->prefix . 'custom_form';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Add a menu at the admin side
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page(
			'Custom Form',
			'Custom Form',
			'manage_options',
			'custom-form',
			array( $this, 'render_form_page' ),
			'dashicons-editor-table',
			40
		);
	}

	/**
	 * Rendering admin page
	 *
	 * @return void
	 */
	public function render_form_page() {
		// Check user capabilities
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'demo2'));
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_form';

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['cfp_submit'] ) ) {
			// Verify nonce
			if (!isset($_POST['custom_form_nonce']) || !wp_verify_nonce($_POST['custom_form_nonce'], 'custom_form_action')) {
				wp_die(__('Security check failed.', 'demo2'));
			}

			// Sanitize and validate inputs
			$name = isset($_POST['cfp_name']) ? sanitize_text_field($_POST['cfp_name']) : '';
			$email = isset($_POST['cfp_email']) ? sanitize_email($_POST['cfp_email']) : '';

			if (empty($name) || empty($email) || !is_email($email)) {
				echo '<div class="error"><p>' . esc_html__('Please provide valid name and email.', 'demo2') . '</p></div>';
			} else {
				$result = $wpdb->insert(
					$table_name,
					array(
						'name'  => $name,
						'email' => $email,
					),
					array('%s', '%s')
				);

				if ($result) {
					echo '<div class="updated"><p>' . esc_html__('Data saved successfully!', 'demo2') . '</p></div>';
				} else {
					echo '<div class="error"><p>' . esc_html__('Error saving data.', 'demo2') . '</p></div>';
				}
			}
		}

		$entries = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at" );

		?>
		<div class="wrap">
			<?php if ( ! empty( $entries ) ) : ?>
				<h2><?php echo esc_html__('Lists', 'demo2'); ?></h2>
				<table class="widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th><?php echo esc_html__('ID', 'demo2'); ?></th>
							<th><?php echo esc_html__('Name', 'demo2'); ?></th>
							<th><?php echo esc_html__('Email', 'demo2'); ?></th>
							<th><?php echo esc_html__('Submitted At', 'demo2'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$cnt = 1;
						foreach ( $entries as $entry ) :
							?>
							<tr>
								<td><?php echo esc_html( $cnt ); ?></td>
								<td><?php echo esc_html( $entry->name ); ?></td>
								<td><?php echo esc_html( $entry->email ); ?></td>
								<td><?php echo esc_html( $entry->created_at ); ?></td>
							</tr>
							<?php
							$cnt = $cnt + 1;
						endforeach;
						?>
					</tbody>
				</table>
			<?php else : ?>
				<p><?php echo esc_html__('No entries found.', 'demo2'); ?></p>
			<?php endif; ?>
		</div>

		<h2><?php echo esc_html__('Enter New Entry', 'demo2'); ?></h2>
		<form method="post">
			<?php wp_nonce_field('custom_form_action', 'custom_form_nonce'); ?>
			<table class="form-table">
				<tr>
					<th><label for="cfp_name"><?php echo esc_html__('Name', 'demo2'); ?></label></th>
					<td><input type="text" name="cfp_name" id="cfp_name" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="cfp_email"><?php echo esc_html__('Email', 'demo2'); ?></label></th>
					<td><input type="email" name="cfp_email" id="cfp_email" class="regular-text" required></td>
				</tr>
			</table>
			<p><input type="submit" name="cfp_submit" class="button button-primary" value="<?php echo esc_attr__('Submit', 'demo2'); ?>"></p>
		</form>
		<?php
	}
}
