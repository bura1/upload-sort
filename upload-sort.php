<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.helix.hr/
 * @since             1.0.0
 * @package           Upload_Sort
 *
 * @wordpress-plugin
 * Plugin Name:       Upload and Sort
 * Plugin URI:        http://www.helix.hr/
 * Description:       File manager plugin.
 * Version:           1.0.0
 * Author:            Helix
 * Author URI:        http://www.helix.hr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       upload-sort
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-upload-sort-activator.php
 */
function activate_upload_sort() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-upload-sort-activator.php';
	Upload_Sort_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-upload-sort-deactivator.php
 */
function deactivate_upload_sort() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-upload-sort-deactivator.php';
	Upload_Sort_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_upload_sort' );
register_deactivation_hook( __FILE__, 'deactivate_upload_sort' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-upload-sort.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_upload_sort() {

	$plugin = new Upload_Sort();
	$plugin->run();

}
run_upload_sort();
