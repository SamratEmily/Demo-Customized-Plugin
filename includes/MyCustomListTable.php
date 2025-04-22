<?php

namespace WeLabs\Demo;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

use WP_List_Table;

class MyCustomListTable extends WP_List_Table {

	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Item', 'demo2' ),
				'plural'   => __( 'Items', 'demo2' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Define the columns displayed in the table.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'name'  => esc_html__( 'Name', 'demo2' ),
			'email' => esc_html__( 'Email', 'demo2' ),
		);
	}

	/**
	 * Render the Name column.
	 *
	 * @param object $item
	 * @return string
	 */
	public function column_name( $item ) {
		return esc_html( $item->name );
	}

	/**
	 * Render the Email column.
	 *
	 * @param object $item
	 * @return string
	 */
	public function column_email( $item ) {
		return esc_html( $item->email );
	}

	/**
	 * Prepare table data, columns, and pagination.
	 */
	public function prepare_items() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_form';

		$data = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY created_at DESC" );

		// Define column headers
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = array(); // Add sortable columns if needed

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->items = $data;
	}
}
