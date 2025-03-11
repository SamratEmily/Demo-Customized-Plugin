<?php

namespace WeLabs\Demo;

class CustomField {
	/**
	 * The constructor.
	 */
	public function __construct() {
		// add_action( 'init', array( $this, 'print_roles' ) );
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_new_custom_product_data' ), 10 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_new_custom_product_data' ), 10 );

		// Add a shortcode
		// add_shortcode( 'custom_fields_form', [ $this, 'display_custom_fields_form' ] );
	}

	public function print_roles() {
		$roles = wp_roles()->roles;

		foreach ( $roles as $role_name => $role_info ) {
			echo '<strong>Role: ' . ucfirst( $role_name ) . '</strong><br>';
			echo 'Capabilities: <br>';
			foreach ( $role_info['capabilities'] as $capability => $value ) {
				echo '- ' . $capability . '<br>';
			}
			echo '<br>';
		}
	}

	/**
	 * Add custom fields to the WooCommerce product edit page.
	 */
	public function add_new_custom_product_data() {
		global $post;

		echo '<div class="options_group">';

		// Get the stored value for the custom fields
		$custom_field_one_value = get_post_meta( $post->ID, '_custom_field_one', true );
		$custom_field_two_value = get_post_meta( $post->ID, '_custom_field_two', true );

		// Add Regular Price Field
		woocommerce_wp_text_input(
			array(
				'id'          => '_custom_field_one',
				'label'       => __( 'Field One', 'demo' ),
				'placeholder' => 'Enter custom Field One',
				'desc_tip'    => 'true',
				'description' => __( 'Set a custom Field One for this product.', 'demo' ),
				'type'        => 'text',
				'data_type'   => 'text',
				'value'       => $custom_field_one_value, // Show saved value in the input field
			)
		);

		// Add Discount Price Field
		woocommerce_wp_text_input(
			array(
				'id'          => '_custom_field_two',
				'label'       => __( 'Field Two', 'demo' ),
				'placeholder' => 'Enter custom Field Two',
				'desc_tip'    => 'true',
				'description' => __( 'Set a custom Field Two for this product.', 'demo' ),
				'type'        => 'text',
				'data_type'   => 'text',
				'value'       => $custom_field_two_value, // Show saved value in the input field
			)
		);

		echo '</div>';
	}

	/**
	 * Save the custom fields data when the product is saved.
	 */
	public function save_new_custom_product_data( $post_id ) {
		// Save Regular Price Field
		$custom_regular_price = isset( $_POST['_custom_field_one'] ) ? sanitize_text_field( $_POST['_custom_field_one'] ) : '';
		update_post_meta( $post_id, '_custom_field_one', $custom_regular_price );

		// Save Discount Price Field
		$custom_discount_price = isset( $_POST['_custom_field_two'] ) ? sanitize_text_field( $_POST['_custom_field_two'] ) : '';
		update_post_meta( $post_id, '_custom_field_two', $custom_discount_price );
	}

	/**
	 * Display custom fields form via shortcode.
	 */
	public function display_custom_fields_form() {
		// Check if the form is submitted and save data
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['custom_fields_form_nonce'] ) && wp_verify_nonce( $_POST['custom_fields_form_nonce'], 'save_custom_fields_form' ) ) {
			$this->save_frontend_custom_fields();
		}

		// Get current post ID
		$post_id = get_the_ID();

		// Get existing values
		$barcode     = get_post_meta( $post_id, '_custom_field_barcode', true );
		$expiry_date = get_post_meta( $post_id, '_custom_field_expiry_date', true );

		// Display the form
		ob_start();
		?>
		<form method="post">
			<?php wp_nonce_field( 'save_custom_fields_form', 'custom_fields_form_nonce' ); ?>
			<p>
				<label for="custom_field_barcode"><?php _e( 'Barcode', 'demo' ); ?></label>
				<input type="text" id="custom_field_barcode" name="custom_field_barcode" value="<?php echo esc_attr( $barcode ); ?>" placeholder="Enter Barcode">
			</p>
			<p>
				<label for="custom_field_expiry_date"><?php _e( 'Expiry Date', 'demo' ); ?></label>
				<input type="date" id="custom_field_expiry_date" name="custom_field_expiry_date" value="<?php echo esc_attr( $expiry_date ); ?>" placeholder="Enter Expiry Date">
			</p>
			<p>
				<input type="submit" value="<?php _e( 'Save', 'demo' ); ?>">
			</p>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Save custom fields from the frontend form.
	 */
	private function save_frontend_custom_fields() {
		// Check if we have a post ID and the user has permissions
		$post_id = get_the_ID();
		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save Barcode Field
		$barcode = isset( $_POST['custom_field_barcode'] ) ? sanitize_text_field( $_POST['custom_field_barcode'] ) : '';
		update_post_meta( $post_id, '_custom_field_barcode', $barcode );

		// Save Expiry Date Field
		$expiry_date = isset( $_POST['custom_field_expiry_date'] ) ? sanitize_text_field( $_POST['custom_field_expiry_date'] ) : '';
		update_post_meta( $post_id, '_custom_field_expiry_date', $expiry_date );
	}
}
