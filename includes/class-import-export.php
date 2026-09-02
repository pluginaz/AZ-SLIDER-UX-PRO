<?php
/**
 * Import / Export Handler
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Import_Export {

    /**
     * Init hooks
     */
    public static function init() {
        add_action( "admin_post_azsux_export_slider", array( __CLASS__, "export_slider" ) );
        add_action( "admin_post_azsux_import_slider", array( __CLASS__, "import_slider" ) );
    }

    /**
     * Export slider configuration to JSON
     */
    public static function export_slider() {
        if ( ! current_user_can( "edit_posts" ) ) {
            wp_die( esc_html__( "Bạn không có quyền thực hiện hành động này.", "az-slider-ux-pro" ) );
        }

        $slider_id = isset( $_GET["id"] ) ? absint( wp_unslash( $_GET["id"] ) ) : 0;
        if ( ! $slider_id || "az_slider" !== get_post_type( $slider_id ) ) {
            wp_die( esc_html__( "Slider không hợp lệ.", "az-slider-ux-pro" ) );
        }

        check_admin_referer( "azsux_export_" . $slider_id );

        $post     = get_post( $slider_id );
        $settings = AZSUX_Helpers::get_slider_settings( $slider_id );

        $export_data = array(
            "generator" => "Az Slider UX Pro",
            "version"   => AZSUX_VERSION,
            "title"     => $post->post_title,
            "settings"  => $settings,
        );

        $filename = "az-slider-" . sanitize_title( $post->post_title ) . "-" . gmdate( "Y-m-d" ) . ".json";

        header( "Content-Type: application/json; charset=utf-8" );
        header( "Content-Disposition: attachment; filename=" . $filename );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );

        echo wp_json_encode( $export_data, JSON_PRETTY_PRINT );
        exit;
    }

    /**
     * Import slider from uploaded JSON file
     */
    public static function import_slider() {
        if ( ! current_user_can( "edit_posts" ) ) {
            wp_die( esc_html__( "Bạn không có quyền thực hiện hành động này.", "az-slider-ux-pro" ) );
        }

        check_admin_referer( "azsux_import_action", "azsux_import_nonce" );

        if ( empty( $_FILES["import_file"]["tmp_name"] ) ) {
            wp_safe_redirect( admin_url( "edit.php?post_type=az_slider&azsux_notice=import_empty" ) );
            exit;
        }

        $tmp_file = sanitize_text_field( wp_unslash( $_FILES["import_file"]["tmp_name"] ) );
        if ( empty( $tmp_file ) || ! file_exists( $tmp_file ) ) {
            wp_safe_redirect( admin_url( "edit.php?post_type=az_slider&azsux_notice=import_invalid" ) );
            exit;
        }

        $raw_json = file_get_contents( $tmp_file );
        $data     = json_decode( $raw_json, true );

        if ( ! is_array( $data ) || empty( $data["settings"] ) ) {
            wp_safe_redirect( admin_url( "edit.php?post_type=az_slider&azsux_notice=import_invalid" ) );
            exit;
        }

        $title = ! empty( $data["title"] ) ? sanitize_text_field( $data["title"] ) . " (Imported)" : __( "Slider Imported", "az-slider-ux-pro" );
        $clean_settings = AZSUX_Sanitizer::sanitize_slider_settings( $data["settings"] );

        $new_id = wp_insert_post( array(
            "post_title"  => $title,
            "post_type"   => "az_slider",
            "post_status" => "publish",
        ) );

        if ( is_wp_error( $new_id ) || ! $new_id ) {
            wp_safe_redirect( admin_url( "edit.php?post_type=az_slider&azsux_notice=import_failed" ) );
            exit;
        }

        update_post_meta( $new_id, "_azsux_slider_data", $clean_settings );

        wp_safe_redirect( admin_url( "post.php?post=" . $new_id . "&action=edit&azsux_notice=imported" ) );
        exit;
    }
}