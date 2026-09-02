<?php
/**
 * Flatsome Integration Engine
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Flatsome {

    /**
     * Init hooks
     */
    public static function init() {
        add_action( "ux_builder_setup", array( __CLASS__, "register_ux_builder_element" ) );
        add_action( "init", array( __CLASS__, "register_ux_builder_element" ), 20 );
    }

    /**
     * Get array of sliders for UX Builder select dropdown
     *
     * @return array
     */
    public static function get_slider_options() {
        AZSUX_Helpers::ensure_default_slider_exists();

        $sliders = get_posts( array(
            "post_type"        => "az_slider",
            "post_status"      => array( "publish", "draft", "private", "any" ),
            "numberposts"      => -1,
            "orderby"          => "title",
            "order"            => "ASC",
            "suppress_filters" => false,
        ) );

        $options = array(
            "0" => __( "-- Chọn Az Slider --", "az-slider-ux-pro" ),
        );

        if ( ! empty( $sliders ) ) {
            foreach ( $sliders as $s ) {
                $title = ! empty( $s->post_title ) ? $s->post_title : __( "(Slider Không Tiêu Đề)", "az-slider-ux-pro" );
                $options[ (string) $s->ID ] = $title . " [ID: " . $s->ID . "]";
            }
        }

        return $options;
    }

    /**
     * Register UX Builder Shortcode
     */
    public static function register_ux_builder_element() {
        static $registered = false;
        if ( $registered ) {
            return;
        }

        if ( ! function_exists( "add_ux_builder_shortcode" ) ) {
            return;
        }

        $registered = true;

        add_ux_builder_shortcode( "az_slider_ux", array(
            "name"      => __( "Az Slider UX Pro", "az-slider-ux-pro" ),
            "category"  => __( "Content", "az-slider-ux-pro" ),
            "thumbnail" => AZSUX_URL . "assets/images/ux-thumbnail.svg",
            "options"   => array(
                "id" => array(
                    "type"       => "select",
                    "heading"    => __( "Chọn Az Slider", "az-slider-ux-pro" ),
                    "default"    => "0",
                    "full_width" => true,
                    "options"    => self::get_slider_options(),
                ),
                "container_width" => array(
                    "type"       => "select",
                    "heading"    => __( "Chiều Rộng Khung Nội Dung", "az-slider-ux-pro" ),
                    "default"    => "container",
                    "options"    => array(
                        "container" => __( "Khung Chuẩn (Container Max-Width)", "az-slider-ux-pro" ),
                        "full"      => __( "Khung Dài 100% (Full Width)", "az-slider-ux-pro" ),
                    ),
                ),
            ),
        ) );
    }
}