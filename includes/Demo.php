<?php

namespace WeLabs\Demo;

use WeLabs\Demo\Corn;
use WeLabs\Demo\Taxonomy;
use WeLabs\Demo\SearchEnhancement;
use WeLabs\Demo\AdminForm;
use WeLabs\Demo\CustomTable;
use WeLabs\Demo\MyCustomListTable;
use WeLabs\Demo\NewMenu;
use WeLabs\Demo\VendorDashboardCustomizer;

/**
 * Demo class
 *
 * @class Demo The class that holds the entire Demo plugin
 */
final class Demo {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '0.0.1';

	/**
	 * Instance of self
	 *
	 * @var Demo
	 */
	private static $instance = null;

	/**
	 * Holds various class instances
	 *
	 * @since 2.6.10
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * Plugin dependencies
	 *
	 * @since 2.6.10
	 *
	 * @var array
	 */
	private const DEMO_DEPENEDENCIES = array(
		'plugins'   => array(
			// 'woocommerce/woocommerce.php',
			// 'dokan-lite/dokan.php',
			// 'dokan-pro/dokan-pro.php'
		),
		'classes'   => array(
			// 'Woocommerce',
			// 'WeDevs_Dokan',
			// 'Dokan_Pro'
		),
		'functions' => array(
			// 'dokan_admin_menu_position'
		),
	);

	/**
	 * Constructor for the Demo class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( DEMO_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( DEMO_FILE, array( $this, 'deactivate' ) );
		register_activation_hook( DEMO_FILE, [ 'WeLabs\Demo\CustomTable', 'activate' ] );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		add_action( 'woocommerce_flush_rewrite_rules', array( $this, 'flush_rewrite_rules' ) );
	}

	/**
	 * Initializes the Demo() class
	 *
	 * Checks for an existing Demo instance
	 * and if it doesn't find one then create a new one.
	 *
	 * @return Demo
	 */
	public static function init() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Magic getter to bypass referencing objects
	 *
	 * @since 2.6.10
	 *
	 * @param string $prop
	 *
	 * @return Class Instance
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}
	}

	/**
	 * Placeholder for activation function
	 *
	 * Nothing is being called here yet.
	 */
	public function activate() {
		// Check demo dependency plugins
		if ( ! $this->check_dependencies() ) {
			wp_die( $this->get_dependency_message() );
		}

		// Rewrite rules during demo activation
		if ( $this->has_woocommerce() ) {
			$this->flush_rewrite_rules();
		}

	}

	/**
	 * Flush rewrite rules after demo is activated or woocommerce is activated
	 *
	 * @since 3.2.8
	 */
	public function flush_rewrite_rules() {
		// fix rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Placeholder for deactivation function
	 *
	 * Nothing being called here yet.
	 */
	public function deactivate() {     }

	/**
	 * Define all constants
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->define( 'DEMO_PLUGIN_VERSION', $this->version );
		$this->define( 'DEMO_DIR', dirname( DEMO_FILE ) );
		$this->define( 'DEMO_INC_DIR', DEMO_DIR . '/includes' );
		$this->define( 'DEMO_TEMPLATE_DIR', DEMO_DIR . '/templates' );
		$this->define( 'DEMO_PLUGIN_ASSET', plugins_url( 'assets', DEMO_FILE ) );

		// give a way to turn off loading styles and scripts from parent theme
		$this->define( 'DEMO_LOAD_STYLE', true );
		$this->define( 'DEMO_LOAD_SCRIPTS', true );
	}

	/**
	 * Define constant if not already defined
	 *
	 * @param string      $name
	 * @param string|bool $value
	 *
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the plugin after WP User Frontend is loaded
	 *
	 * @return void
	 */
	public function init_plugin() {
		// Check demo dependency plugins
		if ( ! $this->check_dependencies() ) {
			add_action( 'admin_notices', array( $this, 'admin_error_notice_for_dependency_missing' ) );
			return;
		}

		$this->includes();
		$this->init_hooks();

		do_action( 'demo_loaded' );
	}

	/**
	 * Initialize the actions
	 *
	 * @return void
	 */
	public function init_hooks() {
		// initialize the classes
		add_action( 'init', array( $this, 'init_classes' ), 4 );
		add_action( 'plugins_loaded', array( $this, 'after_plugins_loaded' ) );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
		// include_once STUB_PLUGIN_DIR . '/functions.php';
	}

	/**
	 * Init all the classes
	 *
	 * @return void
	 */
	public function init_classes() {
		$this->container['scripts']                 = new Assets();
		// $this->container['custom_field']            = new CustomField();
		// $this->container['task']                    = new Task();
		// $this->container['company_name']            = new SingleProductPage();
		// $this->container['vendor_setting_new_data'] = new VendorSetting();
		// $this->container['action_hook']             = new ActionHook();
		// $this->container['corn']                    = new Corn();
		// $this->container['email_manager']           = new EmailManager();
		// $this->container['taxonomy']           = new Taxonomy();
		// $this->container['search_enhancement']           = new SearchEnhancement();
		// $this->container['books']           = new Book();
		// $this->container['admin_form'] = new AdminForm();
		// $this->container['custom_table'] = new CustomTable();
		// $this->container['another_menu'] = new AnotherMenu();
		// $this->container['new_menu'] = new NewMenu();
		// $this->container['my_custom_list_table'] = new MyCustomListTable();
		$this->container['vendor_dashboard_customizer'] = new VendorDashboardCustomizer();
	}

	/**
	 * Executed after all plugins are loaded
	 *
	 * At this point demo Pro is loaded
	 *
	 * @since 2.8.7
	 *
	 * @return void
	 */
	public function after_plugins_loaded() {
		// Initiate background processes and other tasks
	}

	/**
	 * Check whether woocommerce is installed and active
	 *
	 * @since 2.9.16
	 *
	 * @return bool
	 */
	public function has_woocommerce() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Check whether woocommerce is installed
	 *
	 * @since 3.2.8
	 *
	 * @return bool
	 */
	public function is_woocommerce_installed() {
		return in_array( 'woocommerce/woocommerce.php', array_keys( get_plugins() ), true );
	}

	/**
	 * Check plugin dependencies
	 *
	 * @return boolean
	 */
	public function check_dependencies() {
		if ( array_key_exists( 'plugins', self::DEMO_DEPENEDENCIES ) && ! empty( self::DEMO_DEPENEDENCIES['plugins'] ) ) {
			for ( $plugin_counter = 0; $plugin_counter < count( self::DEMO_DEPENEDENCIES['plugins'] ); $plugin_counter++ ) {
				if ( ! is_plugin_active( self::DEMO_DEPENEDENCIES['plugins'][ $plugin_counter ] ) ) {
					return false;
				}
			}
		} elseif ( array_key_exists( 'classes', self::DEMO_DEPENEDENCIES ) && ! empty( self::DEMO_DEPENEDENCIES['classes'] ) ) {
			for ( $class_counter = 0; $class_counter < count( self::DEMO_DEPENEDENCIES['classes'] ); $class_counter++ ) {
				if ( ! class_exists( self::DEMO_DEPENEDENCIES['classes'][ $class_counter ] ) ) {
					return false;
				}
			}
		} elseif ( array_key_exists( 'functions', self::DEMO_DEPENEDENCIES ) && ! empty( self::DEMO_DEPENEDENCIES['functions'] ) ) {
			for ( $func_counter = 0; $func_counter < count( self::DEMO_DEPENEDENCIES['functions'] ); $func_counter++ ) {
				if ( ! function_exists( self::DEMO_DEPENEDENCIES['functions'][ $func_counter ] ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Dependency error message
	 *
	 * @return void
	 */
	protected function get_dependency_message() {
		return __( 'Demo plugin is enabled but not effective. It requires dependency plugins to work.', 'demo' );
	}

	/**
	 * Admin error notice for missing dependency plugins
	 *
	 * @return void
	 */
	public function admin_error_notice_for_dependency_missing() {
		$class = 'notice notice-error';
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $this->get_dependency_message() ) );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', DEMO_FILE ) );
	}

	/**
	 * Get the template file path to require or include.
	 *
	 * @param string $name
	 * @return string
	 */
	public function get_template( $name ) {
		$template = untrailingslashit( DEMO_TEMPLATE_DIR ) . '/' . untrailingslashit( $name );

		return apply_filters( 'demo_template', $template, $name );
	}
}
