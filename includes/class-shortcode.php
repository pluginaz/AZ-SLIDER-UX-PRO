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
        $raw_atts = is_array( $atts ) ? $atts : array();
        $parsed_atts = shortcode_atts(
            array(
                "id"              => 0,
                "container_width" => "",
                "slider_width"    => "",
            ),
            $raw_atts,
            "az_slider_ux"
        );

        $slider_id = absint( $parsed_atts["id"] );

        // Filter out empty override values so defaults from slider post meta aren't wiped
        $overrides = array_filter( $parsed_atts, function( $v ) {
            return "" !== $v && null !== $v;
        } );

        return AZSUX_Renderer::render( $slider_id, $overrides );
    }
}
