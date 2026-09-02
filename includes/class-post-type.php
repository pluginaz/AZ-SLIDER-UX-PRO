<?php
/**
 * Post Type Registration
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Post_Type {

    /**
     * Post type slug
     */
    const POST_TYPE = "az_slider";

    /**
     * Init hooks
     */
    public static function init() {
        add_action( "init", array( __CLASS__, "register_post_type" ) );
        add_filter( "manage_az_slider_posts_columns", array( __CLASS__, "set_custom_columns" ) );
        add_action( "manage_az_slider_posts_custom_column", array( __CLASS__, "render_custom_columns" ), 10, 2 );
    }

    /**
     * Register az_slider CPT
     */
    public static function register_post_type() {
        $labels = array(
            "name"               => _x( "Az Slider UX", "Post Type General Name", "az-slider-ux-pro" ),
            "singular_name"      => _x( "Az Slider", "Post Type Singular Name", "az-slider-ux-pro" ),
            "menu_name"          => __( "Az Slider UX", "az-slider-ux-pro" ),
            "parent_item_colon"  => __( "Parent Slider:", "az-slider-ux-pro" ),
            "all_items"          => __( "Tất cả Sliders", "az-slider-ux-pro" ),
            "view_item"          => __( "Xem Slider", "az-slider-ux-pro" ),
            "add_new_item"       => __( "Thêm Slider Mới", "az-slider-ux-pro" ),
            "add_new"            => __( "Thêm Mới", "az-slider-ux-pro" ),
            "edit_item"          => __( "Chỉnh Sửa Slider", "az-slider-ux-pro" ),
            "update_item"        => __( "Cập Nhật Slider", "az-slider-ux-pro" ),
            "search_items"       => __( "Tìm Kiếm Slider", "az-slider-ux-pro" ),
            "not_found"          => __( "Không tìm thấy slider nào", "az-slider-ux-pro" ),
            "not_found_in_trash" => __( "Không có slider trong thùng rác", "az-slider-ux-pro" ),
        );

        $args = array(
            "label"               => __( "Az Slider UX", "az-slider-ux-pro" ),
            "description"         => __( "Accordion Showcase Sliders", "az-slider-ux-pro" ),
            "labels"              => $labels,
            "supports"            => array( "title" ),
            "hierarchical"        => false,
            "public"              => true,
            "show_ui"             => true,
            "show_in_menu"        => true,
            "show_in_nav_menus"   => false,
            "show_in_admin_bar"   => true,
            "menu_position"       => 20,
            "menu_icon"           => "dashicons-slides",
            "can_export"          => true,
            "has_archive"         => false,
            "exclude_from_search" => true,
            "publicly_queryable"  => true,
            "capability_type"     => "post",
            "show_in_rest"        => true,
        );

        register_post_type( self::POST_TYPE, $args );
    }

    /**
     * Admin Columns Definition
     *
     * @param array $columns
     * @return array
     */
    public static function set_custom_columns( $columns ) {
        $new_columns = array(
            "cb"          => $columns["cb"],
            "title"       => __( "Tên Slider", "az-slider-ux-pro" ),
            "shortcode"   => __( "Shortcode", "az-slider-ux-pro" ),
            "items_count" => __( "Số Item", "az-slider-ux-pro" ),
            "date"        => $columns["date"],
        );
        return $new_columns;
    }

    /**
     * Admin Columns Content Render
     *
     * @param string $column
     * @param int    $post_id
     */
    public static function render_custom_columns( $column, $post_id ) {
        switch ( $column ) {
            case "shortcode":
                echo '<code>[az_slider_ux id="' . esc_attr( $post_id ) . '"]</code>';
                break;
            case "items_count":
                $settings = AZSUX_Helpers::get_slider_settings( $post_id );
                $count    = count( $settings["items"] ?? array() );
                echo esc_html( $count . " items" );
                break;
        }
    }
}