<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://bowo.io
 * @since             1.0.0
 * @package           Ql_Plain_Contact
 *
 * @wordpress-plugin
 * Plugin Name:       Plain Contact
 * Plugin URI:        https://github.com/qriouslad/ql-plain-contact
 * Description:       This plugin creates a simple and secure contact form via the [plaincontact] shortcode that you add to the content area of your page/post.
 * Version:           1.4.0
 * Author:            Bowo
 * Author URI:        https://bowo.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ql-plain-contact
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
define( 'QL_PLAIN_CONTACT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ql-plain-contact-activator.php
 */
function activate_ql_plain_contact() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ql-plain-contact-activator.php';
	Ql_Plain_Contact_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ql-plain-contact-deactivator.php
 */
function deactivate_ql_plain_contact() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ql-plain-contact-deactivator.php';
	Ql_Plain_Contact_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ql_plain_contact' );
register_deactivation_hook( __FILE__, 'deactivate_ql_plain_contact' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ql-plain-contact.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ql_plain_contact() {

	$plugin = new Ql_Plain_Contact();
	$plugin->run();

}
run_ql_plain_contact();
