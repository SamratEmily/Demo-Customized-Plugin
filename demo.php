<?php
/**
 * Plugin Name: Demo
 * Plugin URI:  https://welabs.dev
 * Description: Custom plugin by weLabs
 * Version: 0.0.1
 * Author: WeLabs
 * Author URI: https://welabs.dev
 * Text Domain: demo
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPL2
 */
use WeLabs\Demo\Demo;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'DEMO_FILE' ) ) {
    define( 'DEMO_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Demo Plugin when all plugins loaded
 *
 * @return \WeLabs\Demo\Demo
 */
function welabs_demo() {
    return Demo::init();
}

// Lets Go....
welabs_demo();
