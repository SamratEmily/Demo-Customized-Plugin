<?php

namespace WeLabs\Demo;

class ActionHook {
	/**
	 * The constructor.
	 */
	public function __construct() {
		// add_action( 'wp_loaded', [ $this, 'testing_hook' ], 10 );
		// add_action( 'after_testing_hook', [ $this, 'extend_implementation' ], 100 );
		// add_action( 'after_testing_hook', [ $this, 'first_function' ], 5 );
		// add_action( 'after_testing_hook', [ $this, 'second_function' ], 15 );
		// // add_action( 'woocommerce_process_product_meta', [ $this, 'save_new_custom_product_data' ], 10 );

		// add_filter('make_the_sum_even', [$this, 'making_even'], 10, 2);
	}

	public function testing_hook() {
		$a = 2;
		$b = 3;
		echo "Yes, Hook is started: $a+$b = " . ( $a + $b ) . '<br>';

		if ( ( $a + $b ) % 2 == 0 ) {
			echo 'even';
		} else {
			echo 'odd <br>';
			do_action( 'after_testing_hook' );

			$a = apply_filters( 'make_the_sum_even', $a, $b );
		}
		echo 'this is Sum of a, and b = ' . $a . '<br>';
	}

	public function extend_implementation() {
		echo 'Extended Implementation<br>';
	}

	public function first_function() {
		echo 'First function is called<br>';
	}

	public function second_function() {
		echo 'Second function is called<br>';
	}

	public function making_even( $first, $second ) {
		return ( $first + $second + 1 );
	}
}
