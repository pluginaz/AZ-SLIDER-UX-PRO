<?php
/**
 * Settings Page Manager
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Settings {

    /**
     * Init settings hooks
     */
    public static function init() {
        add_action( "admin_init", array( __CLASS__, "register_settings" ) );
    }

    /**
     * Register options
     */
    public static function register_settings() {
        register_setting( "azsux_settings_group", "azsux_clean_on_uninstall", array(
            "type"              => "string",
            "sanitize_callback" => "sanitize_text_field",
            "default"           => "0",
        ) );
    }

    /**
     * Render page view
     */
    public static function render_page() {
        include AZSUX_PATH . "admin/views/settings.php";
    }
}