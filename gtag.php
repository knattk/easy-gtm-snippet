<?php

namespace EGTMSP;

/**
 * Plugin Name:       Easy GTM Snippet
 * Description:       A simple plugin to add Google Tag Manager to your WordPress site.
 * Version:           1.1.1
 * Requires at least: 4.0
 * Requires PHP:      7.0
 * Author:            KDEV
 * Author URI:        https://github.com/knattk
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-gtm-snippet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
if ( ! defined( 'EGTMSP_PLUGIN_FILE' ) ) {
	define( 'EGTMSP_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'EGTMSP_VERSION' ) ) {
	define( 'EGTMSP_VERSION', '1.1.1' );
}

if ( ! defined( 'EGTMSP_PLUGIN_DIR' ) ) {
	define( 'EGTMSP_PLUGIN_DIR', plugin_dir_path( EGTMSP_PLUGIN_FILE ) );
}

if ( ! defined( 'EGTMSP_OPTION_GTM_ID' ) ) {
	define( 'EGTMSP_OPTION_GTM_ID', 'egtmsp_gtm_container_id' );
}

if ( ! defined( 'EGTMSP_SETTINGS_GROUP' ) ) {
	define( 'EGTMSP_SETTINGS_GROUP', 'egtmsp_settings_group' );
}

if ( ! defined( 'EGTMSP_SETTINGS_PAGE' ) ) {
	define( 'EGTMSP_SETTINGS_PAGE', 'egtmsp-settings' );
}

if ( ! defined( 'EGTMSP_SETTINGS_SECTION' ) ) {
	define( 'EGTMSP_SETTINGS_SECTION', 'egtmsp_gtm_settings_section' );
}

// Require plugin classes.
require_once EGTMSP_PLUGIN_DIR . 'includes/class-frontend.php';
require_once EGTMSP_PLUGIN_DIR . 'includes/class-settings.php';
require_once EGTMSP_PLUGIN_DIR . 'includes/class-plugin.php';

// Initialize the plugin.
new EGTMSP_Plugin();
