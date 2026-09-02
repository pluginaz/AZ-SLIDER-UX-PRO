<?php
/**
 * Templates / Presets Manager
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Templates {

    /**
     * Get all preset templates
     *
     * @return array
     */
    public static function get_presets() {
        return array(
            "dark_showcase" => array(
                "title"       => __( "Dark Luxury Showcase", "az-slider-ux-pro" ),
                "desc"        => __( "Thiết kế tối sang trọng với hiệu ứng Gradient tím đậm và tương tác Accordion đỉnh cao.", "az-slider-ux-pro" ),
                "settings"    => array(
                    "layout"             => "accordion-showcase",
                    "content_side"       => "left",
                    "interaction"        => "hover-click",
                    "autoplay"           => false,
                    "autoplay_delay"     => 4000,
                    "pause_on_hover"     => true,
                    "height"             => "550px",
                    "mobile_height"      => "auto",
                    "gap"                => "16px",
                    "border_radius"      => "16px",
                    "bg_type"            => "gradient3",
                    "bg_gradient_dir"    => "135deg",
                    "bg_gradient_color1" => "#0f172a",
                    "bg_gradient_color2" => "#1e1b4b",
                    "bg_gradient_color3" => "#311b92",
                    "title_color"        => "#ffffff",
                    "desc_color"         => "#cbd5e1",
                    "badge_bg"           => "#854f2e",
                    "badge_color"        => "#ffffff",
                    "active_item_index"  => 0,
                    "items"              => AZSUX_Helpers::get_default_items(),
                ),
            ),
            "modern_teal" => array(
                "title"       => __( "Modern Emerald Teal", "az-slider-ux-pro" ),
                "desc"        => __( "Phong cách hiện đại kết hợp sắc Xanh Ngọc Lục Bảo tươi mát và bố cục Split Content.", "az-slider-ux-pro" ),
                "settings"    => array(
                    "layout"             => "accordion-showcase",
                    "content_side"       => "right",
                    "interaction"        => "hover-click",
                    "autoplay"           => true,
                    "autoplay_delay"     => 5000,
                    "pause_on_hover"     => true,
                    "height"             => "580px",
                    "mobile_height"      => "auto",
                    "gap"                => "20px",
                    "border_radius"      => "24px",
                    "bg_type"            => "gradient2",
                    "bg_gradient_dir"    => "145deg",
                    "bg_gradient_color1" => "#064e3b",
                    "bg_gradient_color2" => "#0f766e",
                    "title_color"        => "#ffffff",
                    "desc_color"         => "#a7f3d0",
                    "badge_bg"           => "#10b981",
                    "badge_color"        => "#ffffff",
                    "active_item_index"  => 0,
                    "items"              => AZSUX_Helpers::get_default_items(),
                ),
            ),
            "sunset_amber" => array(
                "title"       => __( "Sunset Warm Amber", "az-slider-ux-pro" ),
                "desc"        => __( "Tông màu cam hoàng hôn ấm áp, ấn tượng và kích thích hành động người dùng.", "az-slider-ux-pro" ),
                "settings"    => array(
                    "layout"             => "accordion-showcase",
                    "content_side"       => "left",
                    "interaction"        => "click",
                    "autoplay"           => false,
                    "autoplay_delay"     => 4000,
                    "pause_on_hover"     => true,
                    "height"             => "520px",
                    "mobile_height"      => "auto",
                    "gap"                => "14px",
                    "border_radius"      => "20px",
                    "bg_type"            => "gradient2",
                    "bg_gradient_dir"    => "120deg",
                    "bg_gradient_color1" => "#7c2d12",
                    "bg_gradient_color2" => "#9a3412",
                    "title_color"        => "#ffffff",
                    "desc_color"         => "#ffedd5",
                    "badge_bg"           => "#f97316",
                    "badge_color"        => "#ffffff",
                    "active_item_index"  => 0,
                    "items"              => AZSUX_Helpers::get_default_items(),
                ),
            ),
        );
    }

    /**
     * Get single preset
     *
     * @param string $key
     * @return array|false
     */
    public static function get_preset( $key ) {
        $presets = self::get_presets();
        return isset( $presets[ $key ] ) ? $presets[ $key ] : false;
    }

    /**
     * Render page view
     */
    public static function render_page() {
        include AZSUX_PATH . "admin/views/templates.php";
    }
}