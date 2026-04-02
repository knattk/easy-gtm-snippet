<?php

namespace EGTMSP;

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Main plugin class - Initializes plugin components and manages hooks.
 */
class EGTMSP_Plugin {

	/**
	 * Frontend handler.
	 *
	 * @var EGTMSP_Frontend
	 */
	private $frontend;

	/**
	 * Settings handler.
	 *
	 * @var EGTMSP_Settings|null
	 */
	private $settings;

	/**
	 * Constructor - Initialize plugin components.
	 */
	public function __construct() {
		$this->frontend = new EGTMSP_Frontend();
		$this->settings = is_admin() ? new EGTMSP_Settings() : null;

		add_action( 'plugins_loaded', [ $this, 'egtmsp_load_textdomain' ] );
		add_action( 'init', [ $this, 'egtmsp_register_hooks' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( EGTMSP_PLUGIN_FILE ), [ $this, 'egtmsp_add_settings_link' ] );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function egtmsp_load_textdomain() {
		load_plugin_textdomain( 'easy-gtm-snippet' );
	}

	/**
	 * Register hooks for frontend and admin components.
	 */
	public function egtmsp_register_hooks() {
		$this->frontend->init();

		if ( $this->settings instanceof EGTMSP_Settings ) {
			$this->settings->init();
		}
	}

	/**
	 * Add settings link to plugin list table.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified links.
	 */
	public function egtmsp_add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=' . esc_attr( EGTMSP_SETTINGS_PAGE ) . '">' . __( 'Settings', 'easy-gtm-snippet' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
}
