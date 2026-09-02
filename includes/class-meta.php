<?php
/**
 * Post Meta Manager
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Meta {

    /**
     * Init meta hooks
     */
    public static function init() {
        add_action( "add_meta_boxes", array( __CLASS__, "add_meta_boxes" ) );
        add_action( "save_post", array( __CLASS__, "save_meta_boxes" ), 10, 2 );
    }

    /**
     * Register meta box for az_slider
     */
    public static function add_meta_boxes() {
        add_meta_box(
            "azsux_slider_editor",
            __( "Cấu Hình Az Slider UX Pro", "az-slider-ux-pro" ),
            array( __CLASS__, "render_meta_box" ),
            "az_slider",
            "normal",
            "high"
        );
    }

    /**
     * Render main editor metabox
     *
     * @param WP_Post $post
     */
    public static function render_meta_box( $post ) {
        wp_nonce_field( "azsux_save_slider_data", "azsux_slider_meta_nonce" );
        $settings = AZSUX_Helpers::get_slider_settings( $post->ID );
        include AZSUX_PATH . "admin/views/editor.php";
    }

    /**
     * Save metabox data securely
     *
     * @param int     $post_id
     * @param WP_Post $post
     */
    public static function save_meta_boxes( $post_id, $post ) {
        // Nonce check.
        if ( ! isset( $_POST["azsux_slider_meta_nonce"] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST["azsux_slider_meta_nonce"] ) ), "azsux_save_slider_data" ) ) {
            return;
        }

        // Autosave check.
        if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
            return;
        }

        // Capability check.
        if ( ! current_user_can( "edit_post", $post_id ) ) {
            return;
        }

        // Check post type.
        if ( "az_slider" !== $post->post_type ) {
            return;
        }

        // Process posted data.
        if ( isset( $_POST["azsux"] ) && is_array( $_POST["azsux"] ) ) {
            $raw_data  = wp_unslash( $_POST["azsux"] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $sanitized = AZSUX_Sanitizer::sanitize_slider_settings( $raw_data );
            update_post_meta( $post_id, "_azsux_slider_data", $sanitized );
        }
    }
}