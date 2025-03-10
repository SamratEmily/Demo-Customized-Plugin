<?php

namespace WeLabs\Demo;

class Corn {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', 'example_add_cron_interval' );
	}

	public function example_add_cron_interval( $schedules ) {
		$schedules['five_seconds'] = array(
			'interval' => 5,
			'display'  => esc_html__( 'Every Five Seconds' ),
		);
		return $schedules;
	}
}
