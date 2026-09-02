<?php
/**
 * Shortcode Handler
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Shortcode {

    /**
     * Init shortcode hooks
     */
    public static function init() {
        add_shortcode( "az_slider_ux", array( __CLASS__, "render_shortcode" ) );
        add_shortcode( "az_slider_ux_pro", array( __CLASS__, "render_shortcode" ) );
    }

    /**
     * Shortcode callback
     *
     * @param array $atts
     * @return string
     */
    public static function render_shortcode( $atts = array() ) {
        $atts = shortcode_atts(
            array(
                "id" => 0,
            ),
            $atts,
            "az_slider_ux"
        );

        $slider_id = absint( $atts["id"] );

        if ( ! $slider_id ) {
            return '<!-- Az Slider UX Pro: ID parameter is required -->';
        }

        return AZSUX_Renderer::render( $slider_id );
    }
}