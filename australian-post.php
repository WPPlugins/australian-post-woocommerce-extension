<?php
/* @wordpress-plugin
 * Plugin Name:       Australia Post WooCommerce Extension
 * Plugin URI:        https://wpruby.com/plugin/australia-post-woocommerce-extension-pro/
 * Description:       WooCommerce Australia Post Shipping Method.
 * Version:           1.5.6
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       australian-post
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/wsenjer/woocommerce-australian-post-extension
 */

define('AUSPOST_LITE_URL', plugin_dir_url(__FILE__));

/**
 *
 * @since 1.5.6
 *
 */
class WPRuby_Australia_Post_Lite
{
    public function __construct()
    {
        //info: if the Pro version active, deactivate it first.
        if ($this->is_plugin_active('woocommerce-australia-post-extension-pro/class-australian-post.php')) {
            add_action('admin_init', array($this, 'deactivate_pro_version'));
        }
        //info: Only add the shipping method actions only if WooCommerce is activated.
        if ($this->is_plugin_active('woocommerce/woocommerce.php')) {
            add_filter('woocommerce_shipping_methods', array($this, 'add_australia_post_method'));
            add_action('woocommerce_shipping_init', array($this, 'init_australian_post'));
        }

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'));
    }

    // deactivate the pro version
    public function deactivate_pro_version()
    {
        deactivate_plugins('woocommerce-australia-post-extension-pro/class-australian-post.php');
    }

    public function add_australia_post_method($methods)
    {
        if (version_compare(WC()->version, '2.6.0', 'lt')) {
            $methods['auspost'] = 'WC_Australian_Post_Shipping_Method_Legacy';
        } else {
            $methods['auspost'] = 'WC_Australian_Post_Shipping_Method';
        }
        return $methods;
    }

    public function init_australian_post()
    {
        require 'class-australian-post.php';
        if (version_compare(WC()->version, '2.6.0', 'lt')) {
            require 'class-australian-post-legacy.php';
        }
    }
    private function is_plugin_active($slug)
    {
        $active_plugins = (array) get_option('active_plugins', array());
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array($slug, $active_plugins) || array_key_exists($slug, $active_plugins);
    }

    public function plugin_action_links($links)
    {
        $links[] = '<a href="https://wpruby.com/plugin/australia-post-woocommerce-extension-pro/" target="_blank">Get the Pro version</a>';
        $links[] = '<a href="https://wpruby.com/submit-ticket/" target="_blank">Support</a>';
        return $links;
    }
}
new WPRuby_Australia_Post_Lite();
