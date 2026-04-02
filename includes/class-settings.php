<?php

namespace EGTMSP;

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Settings class - Handles plugin settings page and WordPress settings registration.
 */
class EGTMSP_Settings {

	/**
	 * Initialize admin hooks.
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'egtmsp_create_plugin_settings_page' ] );
		add_action( 'admin_init', [ $this, 'egtmsp_register_plugin_settings' ] );
	}

	/**
	 * Register a settings page for the plugin options.
	 */
	public function egtmsp_create_plugin_settings_page() {
		add_options_page(
			__( 'Google Tag Manager', 'easy-gtm-snippet' ),
			__( 'Google Tag Manager', 'easy-gtm-snippet' ),
			'manage_options',
			EGTMSP_SETTINGS_PAGE,
			[ $this, 'egtmsp_render_plugin_settings_page' ]
		);
	}

	/**
	 * Register settings, sections, and fields for the settings page.
	 */
	public function egtmsp_register_plugin_settings() {
		register_setting( EGTMSP_SETTINGS_GROUP, EGTMSP_OPTION_GTM_ID, [ $this, 'egtmsp_sanitize_gtm_id' ] );

		add_settings_section(
			EGTMSP_SETTINGS_SECTION,
			__( 'Easy GTM Snippet', 'easy-gtm-snippet' ),
			[ $this, 'egtmsp_gtag_settings_section_callback' ],
			EGTMSP_SETTINGS_PAGE
		);

		add_settings_field(
			EGTMSP_OPTION_GTM_ID,
			__( 'Google Tag Manager ID', 'easy-gtm-snippet' ),
			[ $this, 'egtmsp_gtag_settings_callback' ],
			EGTMSP_SETTINGS_PAGE,
			EGTMSP_SETTINGS_SECTION,
			array(
				'label_for' => EGTMSP_OPTION_GTM_ID,
			)
		);
	}

	/**
	 * Sanitize the GTM ID.
	 *
	 * @param string $input The input GTM ID.
	 * @return string Sanitized GTM ID.
	 */
	public function egtmsp_sanitize_gtm_id( $input ) {
		$sanitized = strtoupper( trim( sanitize_text_field( (string) $input ) ) );

		if ( '' === $sanitized ) {
			return '';
		}

		if ( preg_match( '/^GTM-[A-Z0-9]+$/', $sanitized ) ) {
			return $sanitized;
		}

		add_settings_error(
			EGTMSP_OPTION_GTM_ID,
			'egtmsp_invalid_gtm_id',
			esc_html__( 'Invalid Google Tag Manager ID. Use format GTM-XXXXXXX.', 'easy-gtm-snippet' ),
			'error'
		);

		return (string) get_option( EGTMSP_OPTION_GTM_ID, '' );
	}

	/**
	 * Render the settings page content.
	 */
	public function egtmsp_render_plugin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( EGTMSP_SETTINGS_GROUP );
				do_settings_sections( EGTMSP_SETTINGS_PAGE );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Callback function for the settings section description.
	 */
	public function egtmsp_gtag_settings_section_callback() {
		echo '<p>' . esc_html__( 'Enter your Google Tag Container ID GTM-XXXXXXX', 'easy-gtm-snippet' ) . '</p>';
	}

	/**
	 * Callback function to render the settings input field.
	 *
	 * @param array $args Arguments passed from add_settings_field.
	 */
	public function egtmsp_gtag_settings_callback( $args ) {
		$option = get_option( EGTMSP_OPTION_GTM_ID );
		?>
		<input type="text" 
		       id="<?php echo esc_attr( $args['label_for'] ); ?>" 
		       name="<?php echo esc_attr( EGTMSP_OPTION_GTM_ID ); ?>" 
		       value="<?php echo esc_attr( $option ); ?>" 
		       placeholder="GTM-XXXXXXX"
		       class="regular-text" />
		<?php
	}
}
