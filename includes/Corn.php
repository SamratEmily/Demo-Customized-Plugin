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
	 * The task that runs on schedule.
	 */
	public function execute_scheduled_task() {
		error_log( 'Cron job executed at: ' . current_time( 'mysql' ) );
	}
}
