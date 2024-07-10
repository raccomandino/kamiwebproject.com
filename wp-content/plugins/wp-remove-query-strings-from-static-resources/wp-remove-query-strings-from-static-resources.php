<?php
/**
 * Plugin Name:	WP Remove Query Strings From Static Resources
 * Description:	It will remove query strings from static resources like CSS and JS files.
 * Version:		1.5
 * Author:		Rinku Yadav
 * Author URI:	http://rinkuyadav.com
 * License:		GPLv2 or later
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wprqsfsr
 * Domain Path: /languages
 *
 * @package     WP Remove Query Strings From Static Resources
 */

// Exit if directly accessed files.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Set path to constant.
if ( ! defined( 'WPRQSFSR_PATH' ) ) {
	define( 'WPRQSFSR_PATH', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
}

// Set url to constant.
if ( ! defined( 'WPRQSFSR_URL' ) ) {
	define( 'WPRQSFSR_URL', plugin_dir_url( __FILE__ ) );
}

// Set plugin base file.
if ( ! defined( 'WPRQSFSR_BASE_FILE' ) ) {
	define( 'WPRQSFSR_BASE_FILE', plugin_basename( __FILE__ ) );
}

// Add core class.
require WPRQSFSR_PATH . 'inc/classes/class-wprqsfsr-core.php';

// Call logic.
new Wprqsfsr_Core();
