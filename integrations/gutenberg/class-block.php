<?php
/**
 * Gutenberg Block Integration
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Gutenberg_Block {

    /**
     * Init hooks
     */
    public static function init() {
        add_action( "init", array( __CLASS__, "register_block" ) );
    }

    /**
     * Register dynamic block
     */
    public static function register_block() {
        if ( ! function_exists( "register_block_type" ) ) {
            return;
        }

        register_block_type_from_metadata( AZSUX_PATH . "integrations/gutenberg", array(
            "render_callback" => array( __CLASS__, "render_block" ),
        ) );
    }

    /**
     * Server side render callback
     *
     * @param array $attributes
     * @return string
     */
    public static function render_block( $attributes ) {
        $slider_id = isset( $attributes["sliderId"] ) ? absint( $attributes["sliderId"] ) : 0;
        if ( ! $slider_id ) {
            return '<!-- Az Slider UX Pro Gutenberg: No Slider Selected -->';
        }

        return AZSUX_Renderer::render( $slider_id );
    }
}