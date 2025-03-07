<?php
namespace WeLabs\Demo;

class Task {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'dokan_order_detail_after_order_general_details', array( $this, 'add_new_task' ), 10, 2 );
		add_action( 'admin_post_save_custom_task', array( $this, 'handle_form_submission' ) ); // Form submission for logged-in users (admin)
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'display_order_data_in_admin' ) );
	}

	/**
	 * Display the new task input field in the order details page.
	 */
	public function add_new_task() {
		$order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;
		?>
		<div class="dokan-panel dokan-panel-default">
			<div class="dokan-panel-heading">
				<strong><?php _e( 'Custom Task Data', 'text-domain' ); ?></strong>
			</div>
			<form class="dokan-form-inline" id="add-order-note" role="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<div class="clearfix">
				<div class="dokan-panel-body general-details">
					<p>
						<textarea id="new_task" name="new_task" class="form-control" cols="30" rows="5"></textarea>
					</p>
					<input type="hidden" name="action" value="save_custom_task">
					<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ); ?>">
					<?php wp_nonce_field( 'save_new_task_action', 'save_new_task_nonce' ); ?>
					<input type="submit" name="add_custom_task" class="add_task btn btn-sm btn-theme dokan-btn-theme" value="Add Custom Data">
				</div> 
				</div>     
			</form>
		</div>
		<?php
	}

	/**
	 * Handle form submission and save the custom task input field to order meta when the form is submitted.
	 */
	public function handle_form_submission() {
		if ( isset( $_POST['new_task'], $_POST['save_new_task_nonce'], $_POST['order_id'] ) && wp_verify_nonce( $_POST['save_new_task_nonce'], 'save_new_task_action' ) ) {
			$order_id = intval( $_POST['order_id'] );
			$new_task = sanitize_text_field( $_POST['new_task'] );

			if ( $order_id && $new_task ) {
				update_post_meta( $order_id, '_new_task', $new_task );

				wp_redirect( add_query_arg( 'task_saved', '1', wp_get_referer() ) );
				exit;
			}
		}
	}


	/**
	 * Display custom order data in the WooCommerce admin order edit page.
	 *
	 * @param WC_Order $order
	 */
	public function display_order_data_in_admin( $order ) {
		$new_task = get_post_meta( $order->get_id(), '_new_task', true );

		if ( ! empty( $new_task ) ) {
			echo '<p><strong>' . __( 'Custom Task Data:', 'text-domain' ) . '</strong> ' . esc_html( $new_task ) . '</p>';
		}

		if ( isset( $_GET['task_saved'] ) && $_GET['task_saved'] === '1' ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Custom task data saved successfully!', 'text-domain' ) . '</p></div>';
		}
	}
}
