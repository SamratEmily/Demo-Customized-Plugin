<?php

namespace WeLabs\Demo;

/**
 * Admin  side Form
 */
class AdminForm {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_form' ) );
		add_action( 'admin_menu', array( $this, 'register_submenu_page' ) );
		add_action( 'admin_init', array( $this, 'wporg_settings_init' ) );
		add_action( 'admin_init', array( $this, 'wporg_settings_init_under_general' ) );
	}

	/**
	 * Pill settings under general
	 *
	 * @return void
	 */
	public function wporg_settings_init_under_general() {
		register_setting( 'general', 'wporg_options_pill' );

		add_settings_field(
			'wporg_options_pill',
			__( 'Choose a Pill', 'wporg' ),
			array( $this, 'wporg_field_pill_cb_general' ),
			'general'
		);
	}

	/**
	 * Template for pill settings under general
	 *
	 * @return void
	 */
	public function wporg_field_pill_cb_general() {
		$options = get_option( 'wporg_options' );
		require_once DEMO_TEMPLATE_DIR . '/custom-settings-general.php';
	}


	/**
	 * Add the top level menu page.
	 */
	public function add_admin_form() {
		$capability = 'manage_options';
		$slug       = 'vendor_credit_notes';

		add_menu_page(
			__( 'Vendor Credit Notes', 'demo2' ),
			__( 'Vendor Credit Notes', 'demo2' ),
			$capability,
			$slug,
			array( $this, 'render_admin_page' ),
			'dashicons-media-spreadsheet',
			5
		);
	}

	/**
	 * Add the settings submenu page.
	 */
	public function register_submenu_page() {
		add_submenu_page(
			'options-general.php',
			__( 'Custom Settings', 'demo2' ),
			__( 'Custom Settings', 'demo2' ),
			'manage_options',
			'wporg-settings',
			array( $this, 'wporg_options_page_html' )
		);
	}

	/**
	 * Submenu page HTML.
	 */
	public function wporg_options_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// settings_errors( 'wporg_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'wporg' );
				do_settings_sections( 'wporg' );
				submit_button( __( 'Save Settings', 'wporg' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings, sections, and fields.
	 */
	public function wporg_settings_init() {
		register_setting( 'wporg', 'wporg_options' );

		add_settings_section(
			'wporg_section_developers',
			__( '', 'wporg' ),
			array( $this, 'wporg_section_developers_callback' ),
			'wporg'
		);

		add_settings_field(
			'wporg_field_pill',
			__( 'Pill', 'wporg' ),
			array( $this, 'wporg_field_pill_cb' ),
			'wporg',
			'wporg_section_developers',
			array(
				'label_for'         => 'wporg_field_pill',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom',
			)
		);
	}

	/**
	 * Section description callback.
	 */
	public function wporg_section_developers_callback( $args ) { }

	/**
	 * Field select callback.
	 */
	public function wporg_field_pill_cb( $args ) {
		$options = get_option( 'wporg_options' );
		require_once DEMO_TEMPLATE_DIR . '/custom-settings.php';
	}

	/**
	 * Vendor Credit Notes page HTML.
	 */
	public function render_admin_page() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Vendor Credit Notes', 'demo2' ) . '</h1>';
		echo '<p>' . esc_html__( 'This is the admin page content.', 'demo2' ) . '</p>';
		echo '</div>';
	}
}
