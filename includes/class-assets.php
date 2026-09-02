<?php
/**
 * Assets Manager for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Assets {

    /**
     * Track if frontend assets were enqueued
     *
     * @var bool
     */
    private static $frontend_enqueued = false;

    /**
     * Init asset hooks
     */
    public static function init() {
        add_action( "wp_enqueue_scripts", array( __CLASS__, "register_frontend_assets" ) );
        add_action( "wp_enqueue_scripts", array( __CLASS__, "check_and_enqueue_for_builder" ), 20 );
        add_action( "admin_enqueue_scripts", array( __CLASS__, "enqueue_admin_assets" ) );
        add_action( "ux_builder_enqueue_scripts", array( __CLASS__, "enqueue_ux_builder_assets" ), 20 );
    }

    /**
     * Register frontend assets for conditional loading
     */
    public static function register_frontend_assets() {
        wp_register_style(
            "azsux-frontend-style",
            AZSUX_URL . "public/css/frontend.css",
            array(),
            AZSUX_VERSION
        );

        wp_register_style(
            "azsux-blog-showcase-style",
            AZSUX_URL . "public/css/blog-showcase.css",
            array( "azsux-frontend-style" ),
            AZSUX_VERSION
        );

        wp_register_script(
            "azsux-frontend-script",
            AZSUX_URL . "public/js/frontend.js",
            array(),
            AZSUX_VERSION,
            true
        );

        wp_register_script(
            "azsux-blog-showcase-script",
            AZSUX_URL . "public/js/blog-showcase.js",
            array( "azsux-frontend-script" ),
            AZSUX_VERSION,
            true
        );

        wp_localize_script(
            "azsux-frontend-script",
            "AZSliderUXVars",
            array(
                "ajax_url" => admin_url( "admin-ajax.php" ),
                "nonce"    => wp_create_nonce( "azsux_frontend_nonce" ),
            )
        );
    }

    /**
     * Force enqueue frontend assets when renderer runs
     */
    public static function enqueue_frontend() {
        self::register_frontend_assets();
        wp_enqueue_style( "dashicons" );
        wp_enqueue_style( "azsux-frontend-style" );
        wp_enqueue_style( "azsux-blog-showcase-style" );
        wp_enqueue_script( "azsux-frontend-script" );
        wp_enqueue_script( "azsux-blog-showcase-script" );
        self::$frontend_enqueued = true;
    }

    /**
     * Check if in UX Builder during normal wp_enqueue_scripts and enqueue
     */
    public static function check_and_enqueue_for_builder() {
        if ( AZSUX_Helpers::is_ux_builder() ) {
            self::enqueue_frontend();
        }
    }

    /**
     * Enqueue assets specifically inside UX Builder
     */
    public static function enqueue_ux_builder_assets( $context = "" ) {
        self::enqueue_frontend();
    }

    /**
     * Enqueue Admin Assets on az_slider screen
     *
     * @param string $hook
     */
    public static function enqueue_admin_assets( $hook ) {
        global $post_type;

        // Also enqueue frontend assets if on UX Builder screen in admin
        if ( AZSUX_Helpers::is_ux_builder() ) {
            self::enqueue_frontend();
        }

        if ( "az_slider" !== $post_type && false === strpos( (string) $hook, "azsux" ) ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( "wp-color-picker" );

        wp_enqueue_style(
            "azsux-admin-style",
            AZSUX_URL . "admin/css/admin.css",
            array( "wp-color-picker" ),
            AZSUX_VERSION
        );

        wp_enqueue_script(
            "azsux-admin-editor",
            AZSUX_URL . "admin/js/editor.js",
            array( "jquery", "jquery-ui-sortable", "wp-color-picker" ),
            AZSUX_VERSION,
            true
        );

        wp_localize_script(
            "azsux-admin-editor",
            "AZSliderAdminVars",
            array(
                "ajax_url"     => admin_url( "admin-ajax.php" ),
                "nonce"        => wp_create_nonce( "azsux_admin_nonce" ),
                "default_item" => AZSUX_Helpers::get_default_items()[0],
                "i18n"         => array(
                    "confirm_delete" => __( "Bạn có chắc chắn muốn xóa item này?", "az-slider-ux-pro" ),
                    "select_image"   => __( "Chọn Hình Ảnh", "az-slider-ux-pro" ),
                    "use_image"      => __( "Sử dụng ảnh này", "az-slider-ux-pro" ),
                    "add_button"     => __( "Thêm Nút", "az-slider-ux-pro" ),
                ),
            )
        );
    }
}
