<?php
/**
 * Frontend Controller for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Frontend {

    /**
     * Init frontend hooks
     */
    public static function init() {
        add_action( "wp_enqueue_scripts", array( __CLASS__, "enqueue_scripts" ) );
    }

    /**
     * Enqueue frontend scripts when needed
     */
    public static function enqueue_scripts() {
        // Enqueued on demand via AZSUX_Assets::enqueue_frontend() in Renderer.
    }
}