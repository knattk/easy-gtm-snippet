<?php

namespace EGTMSP;

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Frontend class - Handles Google Tag Manager script output on the frontend.
 */
class EGTMSP_Frontend {

	/**
	 * Cached GTM container ID for current request.
	 *
	 * @var string
	 */
	private $gtm_id = '';

	/**
	 * Initialize frontend hooks.
	 */
	public function init() {
		$this->gtm_id = $this->egtmsp_get_valid_gtm_id();

		if ( '' === $this->gtm_id ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'egtmsp_enqueue_gtm_script' ], 1 );

		if ( function_exists( 'wp_body_open' ) ) {
			add_action( 'wp_body_open', [ $this, 'egtmsp_print_gtm_tag_to_body' ], 1 );
		} else {
			add_action( 'wp_footer', [ $this, 'egtmsp_print_gtm_tag_to_body' ], 1 );
		}
	}

	/**
	 * Enqueue the Google Tag Manager script using WordPress script APIs.
	 */
	public function egtmsp_enqueue_gtm_script() {
		if ( '' === $this->gtm_id ) {
			return;
		}

		$handle = 'egtmsp-loader';
		wp_register_script( $handle, false, [], EGTMSP_VERSION, false );
		wp_enqueue_script( $handle );

		$inline_script = "(function(w,d,s,l,i){\n"
			. "w[l]=w[l]||[];\n"
			. "w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});\n"
			. "var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';\n"
			. "j.async=true;\n"
			. "j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;\n"
			. "f.parentNode.insertBefore(j,f);\n"
			. "})(window,document,'script','dataLayer','" . esc_js( $this->gtm_id ) . "');";

		wp_add_inline_script( $handle, $inline_script, 'before' );
	}

	/**
	 * Print the Google Tag Manager noscript fallback in the <body> section.
	 */
	public function egtmsp_print_gtm_tag_to_body() {
		if ( '' === $this->gtm_id ) {
			return;
		}
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $this->gtm_id ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	/**
	 * Read and validate GTM ID from options.
	 *
	 * @return string
	 */
	private function egtmsp_get_valid_gtm_id() {
		$gtm_id = trim( (string) get_option( EGTMSP_OPTION_GTM_ID, '' ) );

		if ( '' === $gtm_id ) {
			return '';
		}

		return preg_match( '/^GTM-[A-Z0-9]+$/', $gtm_id ) ? $gtm_id : '';
	}
}
