<?php
/**
 * REST API Manager
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_REST {

    /**
     * REST Namespace
     */
    const NAMESPACE = "az-slider-ux-pro/v1";

    /**
     * Init hooks
     */
    public static function init() {
        add_action( "rest_api_init", array( __CLASS__, "register_routes" ) );
    }

    /**
     * Register REST API routes
     */
    public static function register_routes() {
        register_rest_route( self::NAMESPACE, "/sliders", array(
            "methods"             => WP_REST_Server::READABLE,
            "callback"            => array( __CLASS__, "get_sliders" ),
            "permission_callback" => array( __CLASS__, "check_permission" ),
        ) );

        register_rest_route( self::NAMESPACE, "/render/(?P<id>\d+)", array(
            "methods"             => WP_REST_Server::READABLE,
            "callback"            => array( __CLASS__, "render_slider" ),
            "permission_callback" => "__return_true",
        ) );
    }

    /**
     * Check edit permission
     */
    public static function check_permission() {
        return current_user_can( "edit_posts" );
    }

    /**
     * Get list of sliders
     */
    public static function get_sliders() {
        $posts = get_posts( array(
            "post_type"      => "az_slider",
            "post_status"    => "publish",
            "numberposts"    => -1,
            "orderby"        => "title",
            "order"          => "ASC",
        ) );

        $list = array();
        foreach ( $posts as $p ) {
            $list[] = array(
                "id"    => $p->ID,
                "title" => $p->post_title,
            );
        }

        return rest_ensure_response( $list );
    }

    /**
     * Render slider HTML endpoint
     *
     * @param WP_REST_Request $request
     */
    public static function render_slider( $request ) {
        $slider_id = absint( $request->get_param( "id" ) );
        if ( ! $slider_id ) {
            return new WP_Error( "invalid_id", __( "ID slider không hợp lệ", "az-slider-ux-pro" ), array( "status" => 400 ) );
        }

        $html = AZSUX_Renderer::render( $slider_id );
        return rest_ensure_response( array(
            "id"   => $slider_id,
            "html" => $html,
        ) );
    }
}