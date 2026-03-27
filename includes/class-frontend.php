<?php

namespace EGTMSP;

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Frontend class - Handles Google Tag Manager script output on the frontend.
 */
class EGTMSP_Frontend {

	/**
	 * Initialize frontend hooks.
	 */
	public function init() {
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
		$gtm_id = get_option( EGTMSP_OPTION_GTM_ID );
		if ( empty( $gtm_id ) ) {
			return;
		}

		$gtm_id = esc_js( $gtm_id );

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
			. "})(window,document,'script','dataLayer','{$gtm_id}');";

		wp_add_inline_script( $handle, $inline_script, 'before' );
	}

	/**
	 * Print the Google Tag Manager noscript fallback in the <body> section.
	 */
	public function egtmsp_print_gtm_tag_to_body() {
		$gtm_id = get_option( EGTMSP_OPTION_GTM_ID );
		if ( empty( $gtm_id ) ) {
			return;
		}

		$gtm_id = esc_attr( $gtm_id );
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}
}
