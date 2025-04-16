<?php

namespace WeLabs\Demo;

class Book {
	public function __construct() {
		add_filter( 'template_include', array( $this, 'wl_load_custom_template' ) );
	}

	public function wl_load_custom_template( $template ) {
		if ( is_page( 'books-list' ) ) {
			$plugin_template = DEMO_TEMPLATE_DIR . '/page-books-list.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}
		return $template;
	}
}
