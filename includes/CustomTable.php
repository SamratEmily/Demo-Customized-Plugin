<?php

namespace WeLabs\Demo;

class CustomTable {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	public static function activate() {
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

	public function add_admin_menu() {
		add_menu_page(
			'Custom Form',
			'Custom Form',
			'manage_options',
			'custom-form',
			array( $this, 'render_form_page' )
		);
	}

	public function render_form_page() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_form';

		// Handle form submission
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['cfp_submit'] ) ) {
			$name  = sanitize_text_field( $_POST['cfp_name'] );
			$email = sanitize_email( $_POST['cfp_email'] );

			if ( ! empty( $name ) && ! empty( $email ) ) {
				$wpdb->insert(
					$table_name,
					array(
						'name'  => $name,
						'email' => $email,
					)
				);
				echo '<div class="updated"><p>Data saved successfully!</p></div>';
			} else {
				echo '<div class="error"><p>Please fill in all fields.</p></div>';
			}
		}

		$entries = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at" );

		?>
		<div class="wrap">
			<?php if ( ! empty( $entries ) ) : ?>
				<h2>Lists</h2>
				<table class="widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Submitted At</th>
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
				<p>No entries found.</p>
			<?php endif; ?>
		</div>

		<h2>Enter New Entry</h2>
			<form method="post">
				<table class="form-table">
					<tr>
						<th><label for="cfp_name">Name</label></th>
						<td><input type="text" name="cfp_name" id="cfp_name" class="regular-text" required></td>
					</tr>
					<tr>
						<th><label for="cfp_email">Email</label></th>
						<td><input type="email" name="cfp_email" id="cfp_email" class="regular-text" required></td>
					</tr>
				</table>
				<p><input type="submit" name="cfp_submit" class="button button-primary" value="Submit"></p>
			</form>
		<?php
	}
}
