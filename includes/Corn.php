<?php

namespace WeLabs\Demo;

class Corn {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', [ $this, 'example_add_cron_interval' ] );
		add_action( 'wp', [ $this, 'schedule_cron_event' ] );
		add_action( 'welabs_demo_cron_task', [ $this, 'execute_scheduled_task' ] );
		add_action( 'save_post_product', [ $this, 'on_product_update' ], 10, 2 );
	}

	/**
	 * Add a new cron interval (every 5 seconds).
	 */
	public function example_add_cron_interval( $schedules ) {
		$schedules['five_seconds'] = array(
			'interval' => 5,
			'display'  => esc_html__( 'Every Five Seconds', 'welabs-demo' ),
		);
		return $schedules;
	}

	/**
	 * Schedule the cron event if not already scheduled.
	 */
	public function schedule_cron_event() {
		if ( ! wp_next_scheduled( 'welabs_demo_cron_task' ) ) {
			wp_schedule_event( time(), 'five_seconds', 'welabs_demo_cron_task' );
		}
	}

	/**
	 * Cron job task.
	 */
	public function execute_scheduled_task() {
		error_log( 'Cron job executed at: ' . current_time( 'mysql' ) );
	}

	/**
	 * Handle product update: Send email and store update time.
	 */
	public function on_product_update( $post_ID, $post ) {
		if ( wp_is_post_autosave( $post_ID ) || wp_is_post_revision( $post_ID ) ) {
			return;
		}

		update_post_meta( $post_ID, '_last_updated_time', current_time( 'mysql' ) );

		$admin_email = get_option( 'admin_email' );
		$subject = 'Product Updated : ' . get_the_title( $post_ID );
		$message = 'The product "' . get_the_title( $post_ID ) . '" was updated at ' . current_time( 'mysql' ) . ".\n\n";
		$message .= 'View Product: ' . get_permalink( $post_ID );

		wp_mail( $admin_email, $subject, $message );
	}
}

