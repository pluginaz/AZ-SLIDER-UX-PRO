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
        add_action( "admin_enqueue_scripts", array( __CLASS__, "enqueue_admin_assets" ) );
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
        if ( self::$frontend_enqueued ) {
            return;
        }

        self::register_frontend_assets();
        wp_enqueue_style( "dashicons" );
        wp_enqueue_style( "azsux-frontend-style" );
        wp_enqueue_style( "azsux-blog-showcase-style" );
        wp_enqueue_script( "azsux-frontend-script" );
        wp_enqueue_script( "azsux-blog-showcase-script" );
        self::$frontend_enqueued = true;
    }

    /**
     * Enqueue Admin Assets on az_slider screen
     *
     * @param string $hook
     */
    public static function enqueue_admin_assets( $hook ) {
        global $post_type;

        if ( "az_slider" !== $post_type && false === strpos( $hook, "azsux" ) ) {
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