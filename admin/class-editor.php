<?php
/**
 * Editor AJAX Actions
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Editor {

    /**
     * Init editor hooks
     */
    public static function init() {
        add_action( "wp_ajax_azsux_duplicate_slider", array( __CLASS__, "duplicate_slider" ) );
        add_action( "wp_ajax_azsux_apply_template", array( __CLASS__, "apply_template" ) );
    }

    /**
     * AJAX: Duplicate slider
     */
    public static function duplicate_slider() {
        check_ajax_referer( "azsux_admin_nonce", "nonce" );

        if ( ! current_user_can( "edit_posts" ) ) {
            wp_send_json_error( array( "message" => __( "Không có quyền thực hiện.", "az-slider-ux-pro" ) ) );
        }

        $slider_id = isset( $_POST["slider_id"] ) ? absint( wp_unslash( $_POST["slider_id"] ) ) : 0;
        if ( ! $slider_id || "az_slider" !== get_post_type( $slider_id ) ) {
            wp_send_json_error( array( "message" => __( "Slider không tồn tại.", "az-slider-ux-pro" ) ) );
        }

        $orig = get_post( $slider_id );
        $settings = AZSUX_Helpers::get_slider_settings( $slider_id );

        $new_id = wp_insert_post( array(
            "post_title"  => $orig->post_title . " (Bản Sao)",
            "post_type"   => "az_slider",
            "post_status" => "publish",
        ) );

        if ( is_wp_error( $new_id ) || ! $new_id ) {
            wp_send_json_error( array( "message" => __( "Không thể tạo bản sao.", "az-slider-ux-pro" ) ) );
        }

        update_post_meta( $new_id, "_azsux_slider_data", $settings );

        wp_send_json_success( array(
            "message"  => __( "Nhân bản slider thành công!", "az-slider-ux-pro" ),
            "redirect" => admin_url( "post.php?post=" . $new_id . "&action=edit" ),
        ) );
    }

    /**
     * AJAX: Apply preset template
     */
    public static function apply_template() {
        check_ajax_referer( "azsux_admin_nonce", "nonce" );

        if ( ! current_user_can( "edit_posts" ) ) {
            wp_send_json_error( array( "message" => __( "Không có quyền.", "az-slider-ux-pro" ) ) );
        }

        $template_key = isset( $_POST["template"] ) ? sanitize_text_field( wp_unslash( $_POST["template"] ) ) : "";
        $preset = AZSUX_Templates::get_preset( $template_key );

        if ( ! $preset ) {
            wp_send_json_error( array( "message" => __( "Mẫu preset không tồn tại.", "az-slider-ux-pro" ) ) );
        }

        $new_id = wp_insert_post( array(
            "post_title"  => $preset["title"],
            "post_type"   => "az_slider",
            "post_status" => "publish",
        ) );

        if ( is_wp_error( $new_id ) || ! $new_id ) {
            wp_send_json_error( array( "message" => __( "Lỗi khi tạo slider.", "az-slider-ux-pro" ) ) );
        }

        update_post_meta( $new_id, "_azsux_slider_data", $preset["settings"] );

        wp_send_json_success( array(
            "message"  => __( "Đã áp dụng mẫu thành công!", "az-slider-ux-pro" ),
            "redirect" => admin_url( "post.php?post=" . $new_id . "&action=edit" ),
        ) );
    }
}