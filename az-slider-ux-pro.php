<?php
/**
 * Plugin Name:       Az Slider UX Pro
 * Plugin URI:        https://pluginaz.com/san-pham/az-slider-ux-pro
 * Description:       Tạo slider accordion tương tác hiện đại, hỗ trợ UX Builder, shortcode, gradient, nội dung động và responsive.
 * Version:           1.0.3
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Lê Anh Đông
 * Author URI:        https://pluginaz.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       az-slider-ux-pro
 * Domain Path:       /languages
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

// Define Constants.
define( "AZSUX_VERSION", "1.0.3" );
define( "AZSUX_FILE", __FILE__ );
define( "AZSUX_PATH", plugin_dir_path( __FILE__ ) );
define( "AZSUX_URL", plugin_dir_url( __FILE__ ) );
define( "AZSUX_BASENAME", plugin_basename( __FILE__ ) );

/**
 * Autoload / Include required core files.
 */
require_once AZSUX_PATH . "includes/class-helpers.php";
require_once AZSUX_PATH . "includes/class-sanitizer.php";
require_once AZSUX_PATH . "includes/class-post-data.php";
require_once AZSUX_PATH . "includes/class-blog-query.php";
require_once AZSUX_PATH . "includes/class-loader.php";
require_once AZSUX_PATH . "includes/class-post-type.php";
require_once AZSUX_PATH . "includes/class-meta.php";
require_once AZSUX_PATH . "includes/class-renderer.php";
require_once AZSUX_PATH . "includes/class-shortcode.php";
require_once AZSUX_PATH . "includes/class-assets.php";
require_once AZSUX_PATH . "includes/class-import-export.php";
require_once AZSUX_PATH . "includes/class-rest.php";
require_once AZSUX_PATH . "includes/class-plugin.php";

require_once AZSUX_PATH . "admin/class-admin.php";
require_once AZSUX_PATH . "admin/class-editor.php";
require_once AZSUX_PATH . "admin/class-settings.php";
require_once AZSUX_PATH . "admin/class-templates.php";

require_once AZSUX_PATH . "public/class-frontend.php";

require_once AZSUX_PATH . "integrations/flatsome/class-flatsome.php";
require_once AZSUX_PATH . "integrations/gutenberg/class-block.php";

/**
 * Run Activation Hook.
 */
function azsux_activate_plugin() {
    AZSUX_Post_Type::register_post_type();
    AZSUX_Helpers::ensure_default_slider_exists();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, "azsux_activate_plugin" );

/**
 * Run Deactivation Hook.
 */
function azsux_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, "azsux_deactivate_plugin" );

/**
 * Initialize Plugin Core.
 */
function azsux_init() {
    AZSUX_Plugin::get_instance()->run();
}
add_action( "plugins_loaded", "azsux_init" );