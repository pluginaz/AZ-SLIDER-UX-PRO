<?php
/**
 * Sanitizer for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Sanitizer {

    /**
     * Sanitize whole slider configuration array
     *
     * @param array $input
     * @return array
     */
    public static function sanitize_slider_settings( $input ) {
        if ( ! is_array( $input ) ) {
            $input = array();
        }

        $defaults = AZSUX_Helpers::get_default_settings();
        $clean    = array();

        $clean["layout"]              = self::sanitize_enum( $input["layout"] ?? "", array( "accordion-showcase", "blog-showcase" ), "accordion-showcase" );
        $clean["theme_mode"]          = self::sanitize_enum( $input["theme_mode"] ?? "", array( "dark", "light", "custom" ), "dark" );
        $clean["content_side"]        = self::sanitize_enum( $input["content_side"] ?? "", array( "left", "right" ), "left" );
        $clean["slider_width"]        = self::sanitize_enum( $input["slider_width"] ?? "", array( "full", "custom" ), "full" );
        $clean["slider_max_width"]    = sanitize_text_field( $input["slider_max_width"] ?? "100%" );
        $clean["container_width"]     = self::sanitize_enum( $input["container_width"] ?? "", array( "container", "full" ), "container" );
        $clean["max_width"]           = sanitize_text_field( $input["max_width"] ?? "1320px" );
        $clean["interaction"]         = self::sanitize_enum( $input["interaction"] ?? "", array( "hover", "click", "hover-click" ), "hover-click" );
        $clean["autoplay"]            = ! empty( $input["autoplay"] );
        $clean["autoplay_delay"]      = self::clamp_int( $input["autoplay_delay"] ?? 4000, 1000, 30000, 4000 );
        $clean["pause_on_hover"]      = isset( $input["pause_on_hover"] ) ? ! empty( $input["pause_on_hover"] ) : true;
        $clean["height"]              = sanitize_text_field( $input["height"] ?? "550px" );
        $clean["mobile_height"]       = sanitize_text_field( $input["mobile_height"] ?? "auto" );
        $clean["gap"]                 = sanitize_text_field( $input["gap"] ?? "16px" );
        $clean["border_radius"]       = sanitize_text_field( $input["border_radius"] ?? "16px" );
        $clean["bg_type"]             = self::sanitize_enum( $input["bg_type"] ?? "", array( "color", "gradient2", "gradient3", "image" ), "color" );
        $clean["bg_color"]            = self::sanitize_color( $input["bg_color"] ?? "#0f172a", "#0f172a" );
        $clean["bg_gradient_dir"]     = sanitize_text_field( $input["bg_gradient_dir"] ?? "135deg" );
        $clean["bg_gradient_color1"]  = self::sanitize_color( $input["bg_gradient_color1"] ?? "#0f172a", "#0f172a" );
        $clean["bg_gradient_color2"]  = self::sanitize_color( $input["bg_gradient_color2"] ?? "#1e1b4b", "#1e1b4b" );
        $clean["bg_gradient_color3"]  = self::sanitize_color( $input["bg_gradient_color3"] ?? "#311b92", "#311b92" );
        $clean["bg_image"]            = esc_url_raw( $input["bg_image"] ?? "" );
        $clean["bg_image_id"]         = absint( $input["bg_image_id"] ?? 0 );
        $clean["bg_overlay"]          = sanitize_text_field( $input["bg_overlay"] ?? "rgba(0, 0, 0, 0.4)" );
        $clean["title_color"]         = self::sanitize_color( $input["title_color"] ?? "#ffffff", "#ffffff" );
        $clean["desc_color"]          = self::sanitize_color( $input["desc_color"] ?? "#cbd5e1", "#cbd5e1" );
        $clean["badge_bg"]            = self::sanitize_color( $input["badge_bg"] ?? "#854f2e", "#854f2e" );
        $clean["badge_color"]         = self::sanitize_color( $input["badge_color"] ?? "#ffffff", "#ffffff" );
        $clean["active_item_index"]   = absint( $input["active_item_index"] ?? 0 );

        $raw_items = isset( $input["items"] ) && is_array( $input["items"] ) ? $input["items"] : array();
        $clean["items"] = self::sanitize_items( $raw_items );

        if ( empty( $clean["items"] ) ) {
            $clean["items"] = $defaults["items"];
        }

        $raw_blog = isset( $input["blog"] ) && is_array( $input["blog"] ) ? $input["blog"] : array();
        $clean["blog"] = self::sanitize_blog_settings( $raw_blog );

        return $clean;
    }

    /**
     * Sanitize Blog Showcase settings block
     *
     * @param array $blog
     * @return array
     */
    public static function sanitize_blog_settings( $blog ) {
        if ( ! is_array( $blog ) ) {
            $blog = array();
        }

        $defaults = AZSUX_Helpers::get_default_blog_settings();
        $clean    = array();

        $clean["source"]                = self::sanitize_enum( $blog["source"] ?? "", array( "dynamic", "manual" ), "dynamic" );
        $clean["post_type"]             = sanitize_key( $blog["post_type"] ?? "post" );

        $manual_posts = isset( $blog["manual_posts"] ) && is_array( $blog["manual_posts"] ) ? array_map( "absint", $blog["manual_posts"] ) : array();
        $clean["manual_posts"]          = array_values( array_filter( array_unique( $manual_posts ) ) );

        $clean["posts_per_page"]        = self::clamp_int( $blog["posts_per_page"] ?? 7, 1, 30, 7 );
        $clean["visible_desktop"]       = self::clamp_int( $blog["visible_desktop"] ?? 7, 1, 15, 7 );
        $clean["visible_tablet"]        = self::clamp_int( $blog["visible_tablet"] ?? 5, 1, 9, 5 );
        $clean["visible_mobile"]        = self::clamp_int( $blog["visible_mobile"] ?? 3, 1, 5, 3 );
        $clean["orderby"]               = self::sanitize_enum( $blog["orderby"] ?? "", array( "date", "title", "modified", "rand", "comment_count" ), "date" );
        $clean["order"]                 = self::sanitize_enum( $blog["order"] ?? "", array( "DESC", "ASC" ), "DESC" );

        $cats = isset( $blog["categories"] ) && is_array( $blog["categories"] ) ? array_map( "absint", $blog["categories"] ) : array();
        $clean["categories"]            = array_values( array_filter( array_unique( $cats ) ) );

        $ex_cats = isset( $blog["exclude_categories"] ) && is_array( $blog["exclude_categories"] ) ? array_map( "absint", $blog["exclude_categories"] ) : array();
        $clean["exclude_categories"]    = array_values( array_filter( array_unique( $ex_cats ) ) );

        $tags = isset( $blog["tags"] ) && is_array( $blog["tags"] ) ? array_map( "absint", $blog["tags"] ) : array();
        $clean["tags"]                  = array_values( array_filter( array_unique( $tags ) ) );

        $clean["exclude_current"]       = ! empty( $blog["exclude_current"] );
        $clean["show_category"]         = ! empty( $blog["show_category"] );
        $clean["show_image"]            = ! empty( $blog["show_image"] );
        $clean["show_title"]            = ! empty( $blog["show_title"] );
        $clean["show_excerpt"]          = ! empty( $blog["show_excerpt"] );
        $clean["excerpt_length"]        = self::clamp_int( $blog["excerpt_length"] ?? 18, 5, 100, 18 );
        $clean["show_date"]             = ! empty( $blog["show_date"] );
        $clean["date_format"]           = sanitize_text_field( $blog["date_format"] ?? "" );
        $clean["show_views"]            = ! empty( $blog["show_views"] );
        $clean["show_author"]           = ! empty( $blog["show_author"] );
        $clean["show_comments"]         = ! empty( $blog["show_comments"] );
        $clean["show_reading_time"]     = ! empty( $blog["show_reading_time"] );
        $clean["link_behavior"]         = self::sanitize_enum( $blog["link_behavior"] ?? "", array( "card", "title", "button", "none" ), "card" );
        $clean["card_width"]            = sanitize_text_field( $blog["card_width"] ?? "365px" );
        $clean["card_height"]           = sanitize_text_field( $blog["card_height"] ?? "390px" );
        $clean["fan_preset"]            = self::sanitize_enum( $blog["fan_preset"] ?? "", array( "editorial-fan", "soft-fan", "strong-fan", "flat-center", "stacked" ), "editorial-fan" );
        $clean["active_scale"]          = sanitize_text_field( $blog["active_scale"] ?? "1.05" );
        $clean["active_bg"]             = self::sanitize_color( $blog["active_bg"] ?? "#935b39", "#935b39" );
        $clean["active_text_color"]     = self::sanitize_color( $blog["active_text_color"] ?? "#ffffff", "#ffffff" );
        $clean["active_border_color"]   = sanitize_text_field( $blog["active_border_color"] ?? "hsl(22.67deg 100% 79.38% / 29%)" );
        $clean["active_shadow"]         = sanitize_text_field( $blog["active_shadow"] ?? "0 10px 30px hsl(18.68deg 100% 73.06% / 15%)" );
        $clean["inactive_bg"]           = self::sanitize_color( $blog["inactive_bg"] ?? "#ffffff", "#ffffff" );
        $clean["inactive_text_color"]   = self::sanitize_color( $blog["inactive_text_color"] ?? "#0f172a", "#0f172a" );
        $clean["inactive_border_color"] = sanitize_text_field( $blog["inactive_border_color"] ?? "rgba(255, 255, 255, 0.15)" );
        $clean["show_arrows"]           = ! empty( $blog["show_arrows"] );
        $clean["arrow_position"]        = self::sanitize_enum( $blog["arrow_position"] ?? "", array( "sides", "top-left", "top-center", "top-right", "bottom-left", "bottom-center", "bottom-right" ), "sides" );
        $clean["arrow_style"]           = self::sanitize_enum( $blog["arrow_style"] ?? "", array( "round", "square", "outline" ), "round" );
        $clean["arrow_color"]           = self::sanitize_color( $blog["arrow_color"] ?? "#ffffff", "#ffffff" );
        $clean["arrow_bg"]              = sanitize_text_field( $blog["arrow_bg"] ?? "rgba(15, 23, 42, 0.7)" );
        $clean["loop"]                  = ! empty( $blog["loop"] );
        $clean["side_click"]            = self::sanitize_enum( $blog["side_click"] ?? "", array( "activate", "none" ), "activate" );
        $clean["active_click"]          = self::sanitize_enum( $blog["active_click"] ?? "", array( "open_post", "none" ), "open_post" );
        $clean["swipe"]                 = ! empty( $blog["swipe"] );
        $clean["keyboard"]              = ! empty( $blog["keyboard"] );
        $clean["autoplay"]              = ! empty( $blog["autoplay"] );
        $clean["autoplay_delay"]        = self::clamp_int( $blog["autoplay_delay"] ?? 5000, 1000, 30000, 5000 );
        $clean["pause_on_hover"]        = isset( $blog["pause_on_hover"] ) ? ! empty( $blog["pause_on_hover"] ) : true;

        return wp_parse_args( $clean, $defaults );
    }

    /**
     * Sanitize list of items
     *
     * @param array $raw_items
     * @return array
     */
    public static function sanitize_items( $raw_items ) {
        $clean_items = array();

        foreach ( $raw_items as $index => $item ) {
            if ( ! is_array( $item ) ) {
                continue;
            }

            $id = ! empty( $item["id"] ) ? sanitize_html_class( $item["id"] ) : "item-" . ( $index + 1 );
            $clean_item = array(
                "id"                 => $id,
                "badge"              => sanitize_text_field( $item["badge"] ?? "" ),
                "title"              => sanitize_text_field( $item["title"] ?? "" ),
                "description"        => sanitize_textarea_field( $item["description"] ?? "" ),
                "image"              => esc_url_raw( $item["image"] ?? "" ),
                "image_id"           => absint( $item["image_id"] ?? 0 ),
                "item_label"         => sanitize_text_field( $item["item_label"] ?? "" ),
                "item_badge"         => sanitize_text_field( $item["item_badge"] ?? "" ),
                "item_bg_color"      => self::sanitize_color( $item["item_bg_color"] ?? "", "" ),
                "item_bg_gradient1" => self::sanitize_color( $item["item_bg_gradient1"] ?? "", "" ),
                "item_bg_gradient2" => self::sanitize_color( $item["item_bg_gradient2"] ?? "", "" ),
                "buttons"            => self::sanitize_buttons( $item["buttons"] ?? array() ),
            );

            $clean_items[] = $clean_item;
        }

        return $clean_items;
    }

    /**
     * Sanitize buttons array
     *
     * @param array $raw_buttons
     * @return array
     */
    public static function sanitize_buttons( $raw_buttons ) {
        if ( ! is_array( $raw_buttons ) ) {
            return array();
        }

        $clean_buttons = array();

        foreach ( $raw_buttons as $btn ) {
            if ( ! is_array( $btn ) ) {
                continue;
            }

            $text = sanitize_text_field( $btn["text"] ?? "" );
            if ( "" === $text ) {
                continue;
            }

            $clean_buttons[] = array(
                "text"       => $text,
                "url"        => esc_url_raw( $btn["url"] ?? "#" ),
                "target"     => self::sanitize_enum( $btn["target"] ?? "_self", array( "_self", "_blank" ), "_self" ),
                "style"      => self::sanitize_enum( $btn["style"] ?? "primary", array( "primary", "secondary", "outline", "link" ), "primary" ),
                "badge"      => sanitize_text_field( $btn["badge"] ?? "" ),
                "bg_color"   => self::sanitize_color( $btn["bg_color"] ?? "", "" ),
                "text_color" => self::sanitize_color( $btn["text_color"] ?? "", "" ),
            );
        }

        return $clean_buttons;
    }

    /**
     * Enum value validation
     *
     * @param string $val
     * @param array  $allowed
     * @param string $default
     * @return string
     */
    public static function sanitize_enum( $val, $allowed, $default ) {
        $val = sanitize_text_field( (string) $val );
        return in_array( $val, $allowed, true ) ? $val : $default;
    }

    /**
     * Color validator (Hex, RGB, RGBA, or empty)
     *
     * @param string $color
     * @param string $default
     * @return string
     */
    public static function sanitize_color( $color, $default = "" ) {
        $color = trim( sanitize_text_field( (string) $color ) );
        if ( "" === $color ) {
            return $default;
        }

        if ( preg_match( "/^#([a-fA-F0-9]{3}){1,2}$/", $color ) ) {
            return $color;
        }

        if ( preg_match( "/^rgba?\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*(?:,\s*([0-9\.]+)\s*)?\)$/", $color ) ) {
            return $color;
        }

        return $default;
    }

    /**
     * Clamp integer within range
     *
     * @param mixed $val
     * @param int   $min
     * @param int   $max
     * @param int   $default
     * @return int
     */
    public static function clamp_int( $val, $min, $max, $default ) {
        $int_val = filter_var( $val, FILTER_VALIDATE_INT );
        if ( false === $int_val ) {
            return $default;
        }
        if ( $int_val < $min ) {
            return $min;
        }
        if ( $int_val > $max ) {
            return $max;
        }
        return $int_val;
    }
}