<?php

namespace WeLabs\Demo;

class Corn {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', [ $this, 'add_cron_interval' ] );
		add_action( 'wp', [ $this, 'schedule_cron_event' ] );
		add_action( 'welabs_demo_cron_task', [ $this, 'send_scheduled_email' ] );
		add_action( 'save_post_product', [ $this, 'store_updated_product' ], 10, 2 );
	}

	/**
	 * Add a new cron interval (every 5 minutes).
	 */
	public function add_cron_interval( $schedules ) {
		$schedules['twenty_second'] = array(
			'interval' => 20,
			'display'  => esc_html__( 'Every Five Minutes', 'demo' ),
		);
		return $schedules;
	}

	/**
	 * Schedule the cron event if not already scheduled.
	 */
	public function schedule_cron_event() {
		if ( ! wp_next_scheduled( 'welabs_demo_cron_task' ) ) {
			wp_schedule_event( time(), 'twenty_second', 'welabs_demo_cron_task' );
		}
	}

	/**
	 * Store updated products for the next scheduled email.
	 */
	public function store_updated_product( $post_ID, $post ) {
		if ( wp_is_post_autosave( $post_ID ) || wp_is_post_revision( $post_ID ) ) {
			return;
		}

		update_post_meta( $post_ID, '_last_updated_time', current_time( 'mysql' ) );

		$updated_products = get_transient( 'welabs_updated_products' ) ?: [];
		$updated_products[$post_ID] = [
			'title' => get_the_title( $post_ID ),
			'url'   => get_permalink( $post_ID ),
			'time'  => current_time( 'mysql' ),
		];
		set_transient( 'welabs_updated_products', $updated_products, 3600 );
	}

	/**
	 * Send scheduled email to the admin with updated products.
	 */
	public function send_scheduled_email() {
		$admin_email = get_option( 'admin_email' );
		$updated_products = get_transient( 'welabs_updated_products' );

		if ( ! empty( $updated_products ) ) {
			$subject = 'Recently Updated Products';
			$message = "The following products were updated:\n\n";

			foreach ( $updated_products as $product ) {
				$message .= "- {$product['title']} (Updated at: {$product['time']})\n";
				$message .= "  View: {$product['url']}\n\n";
			}

			wp_mail( $admin_email, $subject, $message );

			delete_transient( 'welabs_updated_products' );
		}
	}
}

