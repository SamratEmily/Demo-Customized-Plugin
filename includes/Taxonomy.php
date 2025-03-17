<?php

namespace WeLabs\Demo;

class Taxonomy {
	public function __construct() {
		add_action( 'init', array( $this, 'wl_register_book_post_type' ) );

		add_action( 'init', array( $this, 'register_taxonomy_for_book_genre' ) );
		add_action( 'init', array( $this, 'register_taxonomy_for_book_Author' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_book_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_book_meta_fields' ) );
	}

	/**
	 * Add custom meta fields for books (ISBN, Publication Year).
	 */
	public function add_book_meta_boxes() {
		add_meta_box(
			'book_meta_box',
			__( 'Book Details', 'demo' ),
			array( $this, 'render_book_meta_box' ),
			'book',
			'side',
			'default'
		);
	}

	public function render_book_meta_box( $post ) {
		// Retrieve existing values
		$isbn             = get_post_meta( $post->ID, '_isbn', true );
		$publication_year = get_post_meta( $post->ID, '_publication_year', true );

		wp_nonce_field( 'save_book_meta', 'book_meta_nonce' );

		echo '<label for="isbn">' . __( 'ISBN:', 'demo' ) . '</label>';
		echo '<input type="text" id="isbn" name="isbn" value="' . esc_attr( $isbn ) . '" style="width:100%;" />';

		echo '<br><br>';

		echo '<label for="publication_year">' . __( 'Publication Year:', 'demo' ) . '</label>';
		echo '<input type="number" id="publication_year" name="publication_year" value="' . esc_attr( $publication_year ) . '" style="width:100%;" />';
	}

	public function save_book_meta_fields( $post_id ) {
		if ( ! isset( $_POST['book_meta_nonce'] ) || ! wp_verify_nonce( $_POST['book_meta_nonce'], 'save_book_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['isbn'] ) ) {
			update_post_meta( $post_id, '_isbn', sanitize_text_field( $_POST['isbn'] ) );
		}

		if ( isset( $_POST['publication_year'] ) ) {
			update_post_meta( $post_id, '_publication_year', sanitize_text_field( $_POST['publication_year'] ) );
		}
	}

	public function wl_register_book_post_type() {
		$labels = array(
			'name'          => __( 'Books', 'demo' ),
			'singular_name' => __( 'Book', 'demo' ),
			'menu_name'     => __( 'Books', 'demo' ),
			'add_new'       => __( 'Add New Book', 'demo' ),
			'add_new_item'  => __( 'Add New Book', 'demo' ),
			'edit_item'     => __( 'Edit Book', 'demo' ),
			'new_item'      => __( 'New Book', 'demo' ),
			'view_item'     => __( 'View Book', 'demo' ),
			'all_items'     => __( 'All Books', 'demo' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-book',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'taxonomies'   => array( 'book_genre', 'book_author' ),
			'show_in_rest' => true,
		);

		register_post_type( 'book', $args );
	}

	public function register_taxonomy_for_book_genre() {
		$labels = array(
			'name'              => _x( 'Genres', 'taxonomy general name', 'demo' ),
			'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'demo' ),
			'search_items'      => __( 'Search Genres', 'demo' ),
			'all_items'         => __( 'All Genres', 'demo' ),
			'parent_item'       => __( 'Parent Genre', 'demo' ),
			'parent_item_colon' => __( 'Parent Genre:', 'demo' ),
			'edit_item'         => __( 'Edit genre', 'demo' ),
			'update_item'       => __( 'Update genre', 'demo' ),
			'add_new_item'      => __( 'Add New genre', 'demo' ),
			'new_item_name'     => __( 'New genre Name', 'demo' ),
			'menu_name'         => __( 'Genres', 'demo' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_in_rest'      => true,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'show_in_menu'      => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		);

		register_taxonomy( 'genre', array( 'book' ), $args );
	}

	public function register_taxonomy_for_book_Author() {
		$labels = array(
			'name'              => _x( 'Authors', 'taxonomy general name', 'demo' ),
			'singular_name'     => _x( 'Author', 'taxonomy singular name', 'demo' ),
			'search_items'      => __( 'Search Author', 'demo' ),
			'all_items'         => __( 'All Authors', 'demo' ),
			'parent_item'       => __( 'Parent Author', 'demo' ),
			'parent_item_colon' => __( 'Parent Author:', 'demo' ),
			'edit_item'         => __( 'Edit Author', 'demo' ),
			'update_item'       => __( 'Update Author', 'demo' ),
			'add_new_item'      => __( 'Add New Author', 'demo' ),
			'new_item_name'     => __( 'New Author Name', 'demo' ),
			'menu_name'         => __( 'Author', 'demo' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_in_rest'      => true,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'show_in_menu'      => true,
			'rewrite'           => array( 'slug' => 'author' ),
		);

		register_taxonomy( 'author', array( 'book' ), $args );
	}
}
