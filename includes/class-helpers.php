<?php
/**
 * Helper utilities for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Helpers {

    /**
     * Init WebP & Media upload hooks
     */
    public static function init_webp_support() {
        add_filter( "upload_mimes", array( __CLASS__, "enable_webp_mime_types" ) );
        add_filter( "wp_check_filetype_and_ext", array( __CLASS__, "fix_webp_filetype" ), 10, 4 );
        add_filter( "file_is_displayable_image", array( __CLASS__, "make_webp_displayable" ), 10, 2 );
    }

    /**
     * Add WebP mime types support
     */
    public static function enable_webp_mime_types( $mimes ) {
        $mimes["webp"] = "image/webp";
        return $mimes;
    }

    /**
     * Fix WebP filetype & extension check
     */
    public static function fix_webp_filetype( $data, $file, $filename, $mimes ) {
        $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
        if ( "webp" === $ext ) {
            $data["ext"]             = "webp";
            $data["type"]            = "image/webp";
            $data["proper_filename"] = false;
        }
        return $data;
    }

    /**
     * Ensure WebP files are displayable as valid images
     */
    public static function make_webp_displayable( $result, $path ) {
        if ( ! $result && false !== strpos( strtolower( (string) $path ), ".webp" ) ) {
            return true;
        }
        return $result;
    }

    /**
     * Ensure at least one default slider post exists
     *
     * @return int
     */
    public static function ensure_default_slider_exists() {
        $existing = get_posts( array(
            "post_type"   => "az_slider",
            "post_status" => "any",
            "numberposts" => 1,
            "fields"      => "ids",
        ) );

        if ( ! empty( $existing ) ) {
            return $existing[0];
        }

        $default_id = wp_insert_post( array(
            "post_title"  => __( "Az Slider UX Demo Mẫu", "az-slider-ux-pro" ),
            "post_type"   => "az_slider",
            "post_status" => "publish",
        ) );

        if ( $default_id && ! is_wp_error( $default_id ) ) {
            update_post_meta( $default_id, "_azsux_slider_data", self::get_default_settings() );
            return $default_id;
        }

        return 0;
    }

    /**
     * Get default settings for a slider
     *
     * @return array
     */
    public static function get_default_settings() {
        return array(
            "layout"              => "accordion-showcase",
            "theme_mode"          => "dark",
            "content_side"        => "left",
            "slider_width"        => "full",
            "slider_max_width"    => "100%",
            "container_width"     => "container",
            "max_width"           => "1320px",
            "interaction"         => "hover-click",
            "autoplay"            => false,
            "autoplay_delay"      => 4000,
            "pause_on_hover"      => true,
            "height"              => "550px",
            "mobile_height"       => "auto",
            "gap"                 => "16px",
            "border_radius"       => "0",
            "bg_type"             => "color",
            "bg_color"            => "#241d15",
            "bg_gradient_dir"     => "135deg",
            "bg_gradient_color1"  => "#241d15",
            "bg_gradient_color2"  => "#1e1b4b",
            "bg_gradient_color3"  => "#311b92",
            "bg_image"            => "",
            "bg_image_id"         => 0,
            "bg_overlay"          => "rgba(0, 0, 0, 0.4)",
            "title_color"         => "#ffffff",
            "desc_color"          => "#cbd5e1",
            "badge_bg"            => "#854f2e",
            "badge_color"         => "#ffffff",
            "active_item_index"   => 0,
            "items"               => self::get_default_items(),
            "blog"                => self::get_default_blog_settings(),
        );
    }

    /**
     * Get default blog settings
     *
     * @return array
     */
    public static function get_default_blog_settings() {
        return array(
            "source"                => "dynamic",
            "post_type"             => "post",
            "manual_posts"          => array(),
            "posts_per_page"        => 7,
            "visible_desktop"       => 7,
            "visible_tablet"        => 5,
            "visible_mobile"        => 3,
            "orderby"               => "date",
            "order"                 => "DESC",
            "categories"            => array(),
            "exclude_categories"    => array(),
            "tags"                  => array(),
            "exclude_current"       => true,
            "show_category"         => true,
            "show_image"            => true,
            "show_title"            => true,
            "show_excerpt"          => true,
            "excerpt_length"        => 18,
            "show_date"             => true,
            "date_format"           => "",
            "show_views"            => false,
            "show_author"           => false,
            "show_comments"         => false,
            "show_reading_time"     => false,
            "link_behavior"         => "card",
            "card_width"            => "365px",
            "card_height"           => "390px",
            "fan_preset"            => "editorial-fan",
            "active_scale"          => "1.05",
            "active_bg"             => "#935b39",
            "active_text_color"     => "#ffffff",
            "active_border_color"   => "hsl(22.67deg 100% 79.38% / 29%)",
            "active_shadow"         => "0 10px 30px hsl(18.68deg 100% 73.06% / 15%)",
            "inactive_bg"           => "#ffffff",
            "inactive_text_color"   => "#0f172a",
            "inactive_border_color" => "rgba(255, 255, 255, 0.15)",
            "show_arrows"           => true,
            "arrow_position"        => "sides",
            "arrow_style"           => "round",
            "arrow_color"           => "#ffffff",
            "arrow_bg"              => "rgba(15, 23, 42, 0.7)",
            "loop"                  => true,
            "side_click"            => "activate",
            "active_click"          => "open_post",
            "swipe"                 => true,
            "keyboard"              => true,
            "autoplay"              => false,
            "autoplay_delay"        => 5000,
            "pause_on_hover"        => true,
        );
    }

    /**
     * Get default items list
     *
     * @return array
     */
    public static function get_default_items() {
        return array(
            array(
                "id"                 => "item-1",
                "badge"              => "Nổi Bật 01",
                "title"              => "Trải Nghiệm UX Slider Accordion Tối Ưu",
                "description"        => "Giải pháp hiển thị sản phẩm và dịch vụ tương tác cao, thiết kế chuẩn responsive và Flatsome UX Builder.",
                "image"              => "",
                "image_id"           => 0,
                "item_label"         => "Showcase 01",
                "item_badge"         => "01",
                "item_bg_color"      => "#1e293b",
                "item_bg_gradient1" => "#1e293b",
                "item_bg_gradient2" => "#334155",
                "buttons"            => array(
                    array(
                        "text"       => "Khám Phá Ngay",
                        "url"        => "#",
                        "target"     => "_self",
                        "style"      => "primary",
                        "badge"      => "Hot",
                        "bg_color"   => "#854f2e",
                        "text_color" => "#ffffff",
                    ),
                    array(
                        "text"       => "Xem Chi Tiết",
                        "url"        => "#",
                        "target"     => "_self",
                        "style"      => "outline",
                        "badge"      => "",
                        "bg_color"   => "",
                        "text_color" => "#ffffff",
                    ),
                ),
            ),
            array(
                "id"                 => "item-2",
                "badge"              => "Nổi Bật 02",
                "title"              => "Tương Tắc Mượt Mặc Định Chuẩn SEO",
                "description"        => "Hiệu ứng chuyển đổi nội dung, màu sắc và hình ảnh mượt mà khi hover hoặc click trên mọi thiết bị.",
                "image"              => "",
                "image_id"           => 0,
                "item_label"         => "Showcase 02",
                "item_badge"         => "02",
                "item_bg_color"      => "#0f766e",
                "item_bg_gradient1" => "#0f766e",
                "item_bg_gradient2" => "#115e59",
                "buttons"            => array(
                    array(
                        "text"       => "Tìm Hiểu Thêm",
                        "url"        => "#",
                        "target"     => "_self",
                        "style"      => "primary",
                        "badge"      => "New",
                        "bg_color"   => "#0d9488",
                        "text_color" => "#ffffff",
                    ),
                ),
            ),
            array(
                "id"                 => "item-3",
                "badge"              => "Nổi Bật 03",
                "title"              => "Tối Ưu Hiệu Năng & Tương Thích Theme",
                "description"        => "Hoạt động hoàn hảo với Flatsome UX Builder, Gutenberg và shortcode chuẩn WordPress.",
                "image"              => "",
                "image_id"           => 0,
                "item_label"         => "Showcase 03",
                "item_badge"         => "03",
                "item_bg_color"      => "#4c1d95",
                "item_bg_gradient1" => "#4c1d95",
                "item_bg_gradient2" => "#581c87",
                "buttons"            => array(
                    array(
                        "text"       => "Dùng Thử Ngay",
                        "url"        => "#",
                        "target"     => "_self",
                        "style"      => "primary",
                        "badge"      => "Pro",
                        "bg_color"   => "#7c3aed",
                        "text_color" => "#ffffff",
                    ),
                ),
            ),
        );
    }

    /**
     * Get single slider settings merged with defaults
     *
     * @param int $slider_id
     * @return array
     */
    public static function get_slider_settings( $slider_id ) {
        $slider_id = absint( $slider_id );
        if ( ! $slider_id || "az_slider" !== get_post_type( $slider_id ) ) {
            return self::get_default_settings();
        }

        $meta = get_post_meta( $slider_id, "_azsux_slider_data", true );
        if ( ! is_array( $meta ) ) {
            $meta = array();
        }

        $defaults = self::get_default_settings();

        // Merge blog settings explicitly
        if ( isset( $meta['blog'] ) && is_array( $meta['blog'] ) ) {
            $meta['blog'] = wp_parse_args( $meta['blog'], $defaults['blog'] );
        }

        return AZSUX_Sanitizer::sanitize_slider_settings( wp_parse_args( $meta, $defaults ) );
    }

    /**
     * Generate unique instance ID for frontend output
     *
     * @param int $slider_id
     * @return string
     */
    public static function generate_instance_id( $slider_id ) {
        static $counter = 0;
        $counter++;
        return "azsux-instance-" . absint( $slider_id ) . "-" . $counter . "-" . wp_rand( 100, 999 );
    }

    /**
     * Check if currently running in Flatsome UX Builder (editor or iframe preview)
     *
     * @return bool
     */
    public static function is_ux_builder() {
        if ( function_exists( 'ux_builder_is_active' ) && ux_builder_is_active() ) {
            return true;
        }
        // phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
        if ( isset( $_GET['app'] ) && 'uxbuilder' === sanitize_key( wp_unslash( $_GET['app'] ) ) ) {
            return true;
        }
        if ( isset( $_GET['uxb_iframe'] ) ) {
            return true;
        }
        if ( defined( 'UX_BUILDER_AJAX_REQUEST' ) && UX_BUILDER_AJAX_REQUEST ) {
            return true;
        }
        if ( defined( 'UX_BUILDER_DOING_AJAX' ) && UX_BUILDER_DOING_AJAX ) {
            return true;
        }
        if ( isset( $_POST['action'] ) && 'ux_builder_do_shortcode' === sanitize_key( wp_unslash( $_POST['action'] ) ) ) {
            return true;
        }
        if ( is_admin() && isset( $_GET['action'] ) && 'edit' === sanitize_key( wp_unslash( $_GET['action'] ) ) && isset( $_GET['app'] ) ) {
            return true;
        }
        // phpcs:enable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
        return false;
    }
}
