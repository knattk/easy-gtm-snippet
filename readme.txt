=== Easy GTM Snippet ===
Contributors: knattk, KDEV
Tags: google tag manager, gtm, analytics, tracking
Requires at least: 4.0
Tested up to: 6.9
Stable tag: 1.1.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple plugin to add Google Tag Manager to your WordPress site.

== Description ==

Easy GTM is a simple plugin to add Google Tag Manager to your WordPress site. It allows you to easily insert the GTM container code into the head and body tags of your website.

Originally created by manishah.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/easy-gtm-snippet` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->Google Tag Manager screen to configure the plugin.

== External services ==

This plugin connects to Google Tag Manager to load the container JavaScript used for tag management.

It sends data to the following service:

* Service name: Google Tag Manager
* Service URL: https://www.googletagmanager.com
* What the service is used for: Loading your configured GTM container script and fallback iframe on site pages
* What data is sent and when: When a visitor loads a page where this plugin outputs GTM code, the visitor's browser requests resources from googletagmanager.com. This request may include data such as IP address, browser and device details (user agent), referrer URL, and the GTM container ID you configured.

Google privacy policy: https://policies.google.com/privacy
Google terms of service: https://policies.google.com/terms

== Screenshots ==
1. Plugin Dashboard

== Frequently Asked Questions ==

= How do I use this? =

Go to Settings > Easy GTM and enter your Google Tag Manager ID (GTM-XXXX).


== Acknowledgements ==

* Credits: Inspired by Easy WP Google Tag Manager by manishah.


== Changelog ==

= 1.0.0 =
* Initial release.