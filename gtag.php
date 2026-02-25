<?php

namespace EasyGTM;

/**
 * Plugin Name:       Easy GTM Snippet
 * Description:       A simple plugin to add Google Tag Manager to your WordPress site. (Originally created by manishah)
 * Version:           1.0.1
 * Requires at least: 4.0
 * Requires PHP:      5.6
 * Author:            KDEV
 * Author URI:        https://github.com/knattk
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-gtm-snippet
 */

if (!defined('ABSPATH')) {
    exit;
}

class EasyGTM
{
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        add_action('init', [$this, 'register_hooks']);
        add_action('admin_menu', [$this, 'create_plugin_settings_page']);
        add_action('admin_init', [$this, 'register_plugin_settings']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_settings_link']);
    }

    /**
     * Load the plugin text domain for translation.
     */
    public function load_textdomain()
    {
        load_plugin_textdomain('easy-gtm-snippet');
    }

    /**
     * Register hooks for frontend output.
     */
    public function register_hooks()
    {
        add_action('wp_head', [$this, 'print_gtm_tag_to_head'], 1);
        
        if (function_exists('wp_body_open')) {
            add_action('wp_body_open', [$this, 'print_gtm_tag_to_body'], 1);
        } else {
            add_action('wp_footer', [$this, 'print_gtm_tag_to_body'], 1);
        }
    }

    /**
     * Add settings link to plugin list table.
     *
     * @param array $links Existing plugin action links.
     * @return array Modified links.
     */
    public function add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=easy-gtm-snippet">' . __('Settings', 'easy-gtm-snippet') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Print the Google Tag Manager script in the <head> section.
     */
    public function print_gtm_tag_to_head()
    {
        $gtm_id = get_option('easy_gtm_gtag_id');
        if (empty($gtm_id)) {
            return;
        }

        $gtm_id = esc_js($gtm_id);
    ?>
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?php echo esc_js($gtm_id); ?>');
        </script>
        <!-- End Google Tag Manager -->
    <?php
    }

    /**
     * Print the Google Tag Manager noscript fallback in the <body> section.
     */
    public function print_gtm_tag_to_body()
    {
        $gtm_id = get_option('easy_gtm_gtag_id');
        if (empty($gtm_id)) {
            return;
        }

        $gtm_id = esc_attr($gtm_id);
    ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
<?php
    }

    /**
     * Register a settings page for the plugin options.
     */
    public function create_plugin_settings_page()
    {
        add_options_page(
            __('Google Tag Manager', 'easy-gtm-snippet'),
            __('Google Tag Manager', 'easy-gtm-snippet'),
            'manage_options',
            'easy-gtm-snippet',
            [$this, 'render_plugin_settings_page']
        );
    }

    /**
     * Register settings, sections, and fields for the settings page.
     */
    public function register_plugin_settings()
    {
        register_setting('easy-gtm-group', 'easy_gtm_gtag_id', [$this, 'sanitize_gtm_id']);

        add_settings_section(
            'easy-gtm_gtag_settings_section',
            __('Easy GTM Snippet', 'easy-gtm-snippet'),
            [$this, 'gtag_settings_section_callback'],
            'easy-gtm-snippet'
        );

        add_settings_field(
            'easy_gtm_gtag_id',
            __('Google Tag Manager ID', 'easy-gtm-snippet'),
            [$this, 'gtag_settings_callback'],
            'easy-gtm-snippet',
            'easy-gtm_gtag_settings_section',
            array(
                'label_for' => 'easy_gtm_gtag_id'
            )
        );
    }

    /**
     * Sanitize the GTM ID.
     *
     * @param string $input The input GTM ID.
     * @return string Sanitized GTM ID.
     */
    public function sanitize_gtm_id($input)
    {
        $sanitized = sanitize_text_field($input);
        return strtoupper(trim($sanitized));
    }

    /**
     * Render the settings page content.
     */
    public function render_plugin_settings_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('easy-gtm-group');
                do_settings_sections('easy-gtm-snippet');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Callback function for the settings section description.
     */
    public function gtag_settings_section_callback()
    {
        echo '<p>' . esc_html__('Enter your Google Tag Container ID GTM-XXXXXXX', 'easy-gtm-snippet') . '</p>';
    }

    /**
     * Callback function to render the settings input field.
     *
     * @param array $args Arguments passed from add_settings_field.
     */
    public function gtag_settings_callback($args)
    {
        $option = get_option('easy_gtm_gtag_id');
        ?>
        <input type="text" 
               id="<?php echo esc_attr($args['label_for']); ?>" 
               name="easy_gtm_gtag_id" 
               value="<?php echo esc_attr($option); ?>" 
               placeholder="GTM-XXXXXXX"
               class="regular-text" />
        <?php
    }
}

new EasyGTM();
