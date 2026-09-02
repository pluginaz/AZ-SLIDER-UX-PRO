<?php
/**
 * Single Source Renderer Engine for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Renderer {

    /**
     * Render slider HTML
     *
     * @param int   $slider_id
     * @param array $overrides
     * @return string
     */
    public static function render( $slider_id, $overrides = array() ) {
        $slider_id = absint( $slider_id );

        // If ID is missing or 0, fallback to default demo slider in UX Builder
        if ( ! $slider_id ) {
            $default_id = AZSUX_Helpers::ensure_default_slider_exists();
            if ( $default_id ) {
                $slider_id = $default_id;
            } else {
                if ( ( function_exists( "ux_builder_is_active" ) && ux_builder_is_active() ) || is_admin() ) {
                    return self::render_placeholder();
                }
                return '<!-- Az Slider UX Pro: Invalid Slider ID -->';
            }
        }

        $settings = AZSUX_Helpers::get_slider_settings( $slider_id );
        if ( ! empty( $overrides ) && is_array( $overrides ) ) {
            $settings = wp_parse_args( $overrides, $settings );
            $settings = AZSUX_Sanitizer::sanitize_slider_settings( $settings );
        }

        // Branching according to layout type
        if ( "blog-showcase" === ( $settings["layout"] ?? "accordion-showcase" ) ) {
            return self::render_blog_showcase( $slider_id, $settings );
        }

        return self::render_accordion_showcase( $slider_id, $settings );
    }

    /**
     * Render Accordion Showcase Layout
     *
     * @param int   $slider_id
     * @param array $settings
     * @return string
     */
    protected static function render_accordion_showcase( $slider_id, $settings ) {
        if ( empty( $settings["items"] ) ) {
            if ( ( function_exists( "ux_builder_is_active" ) && ux_builder_is_active() ) || is_admin() ) {
                return self::render_placeholder();
            }
            return '<!-- Az Slider UX Pro: No Items Configured -->';
        }

        // Trigger asset enqueuing.
        AZSUX_Assets::enqueue_frontend();

        $instance_id = AZSUX_Helpers::generate_instance_id( $slider_id );
        $active_idx  = AZSUX_Sanitizer::clamp_int( $settings["active_item_index"] ?? 0, 0, count( $settings["items"] ) - 1, 0 );

        // Apply automatic contrast presets if theme_mode is light/dark
        $theme_mode  = $settings["theme_mode"] ?? "dark";
        $title_color = $settings["title_color"];
        $desc_color  = $settings["desc_color"];
        $badge_bg    = $settings["badge_bg"];
        $badge_color = $settings["badge_color"];

        if ( "light" === $theme_mode ) {
            if ( "#ffffff" === strtolower( $title_color ) || empty( $title_color ) ) {
                $title_color = "#0f172a";
            }
            if ( "#cbd5e1" === strtolower( $desc_color ) || empty( $desc_color ) ) {
                $desc_color = "#475569";
            }
        } elseif ( "dark" === $theme_mode ) {
            if ( "#0f172a" === strtolower( $title_color ) || empty( $title_color ) ) {
                $title_color = "#ffffff";
            }
            if ( "#475569" === strtolower( $desc_color ) || empty( $desc_color ) ) {
                $desc_color = "#cbd5e1";
            }
        }

        // Build container inline styles / dynamic data attributes.
        $data_attrs = array(
            'data-instance'      => esc_attr( $instance_id ),
            'data-layout'        => 'accordion-showcase',
            'data-interaction'   => esc_attr( $settings["interaction"] ),
            'data-autoplay'      => $settings["autoplay"] ? 'true' : 'false',
            'data-delay'         => esc_attr( $settings["autoplay_delay"] ),
            'data-pause-hover'   => $settings["pause_on_hover"] ? 'true' : 'false',
            'data-active-index'  => esc_attr( $active_idx ),
            'data-items-count'   => esc_attr( count( $settings["items"] ) ),
        );

        $data_html = '';
        foreach ( $data_attrs as $k => $v ) {
            $data_html .= ' ' . $k . '="' . $v . '"';
        }

        $slider_max_width_val = ( 'full' === ( $settings['slider_width'] ?? 'full' ) ) ? '100%' : esc_attr( $settings['slider_max_width'] ?? '100%' );
        $max_width_val        = ( 'full' === ( $settings['container_width'] ?? 'container' ) ) ? '100%' : esc_attr( $settings['max_width'] ?? '1320px' );

        // CSS Variables for instance container.
        $styles = array(
            '--azsux-height'           => esc_attr( $settings["height"] ),
            '--azsux-mobile-height'    => esc_attr( $settings["mobile_height"] ),
            '--azsux-gap'              => esc_attr( $settings["gap"] ),
            '--azsux-radius'           => esc_attr( $settings["border_radius"] ),
            '--azsux-slider-max-width' => $slider_max_width_val,
            '--azsux-max-width'        => $max_width_val,
            '--azsux-title-color'      => esc_attr( $title_color ),
            '--azsux-desc-color'       => esc_attr( $desc_color ),
            '--azsux-badge-bg'         => esc_attr( $badge_bg ),
            '--azsux-badge-color'      => esc_attr( $badge_color ),
        );

        // Background styling according to bg_type.
        $bg_css = '';
        if ( 'light' === $theme_mode && 'color' === $settings['bg_type'] && '#0f172a' === strtolower( $settings['bg_color'] ) ) {
            $bg_css = 'background-color: #ffffff;';
        } else {
            switch ( $settings["bg_type"] ) {
                case 'color':
                    $bg_css = 'background-color: ' . esc_attr( $settings["bg_color"] ) . ';';
                    break;
                case 'gradient2':
                    $bg_css = 'background: linear-gradient(' . esc_attr( $settings["bg_gradient_dir"] ) . ', ' . esc_attr( $settings["bg_gradient_color1"] ) . ', ' . esc_attr( $settings["bg_gradient_color2"] ) . ');';
                    break;
                case 'gradient3':
                    $bg_css = 'background: linear-gradient(' . esc_attr( $settings["bg_gradient_dir"] ) . ', ' . esc_attr( $settings["bg_gradient_color1"] ) . ', ' . esc_attr( $settings["bg_gradient_color2"] ) . ', ' . esc_attr( $settings["bg_gradient_color3"] ) . ');';
                    break;
                case 'image':
                    if ( ! empty( $settings["bg_image"] ) ) {
                        $bg_css = 'background-image: url("' . esc_url( $settings["bg_image"] ) . '"); background-size: cover; background-position: center;';
                    } else {
                        $bg_css = 'background-color: ' . esc_attr( $settings["bg_color"] ) . ';';
                    }
                    break;
            }
        }

        $style_attr = '';
        foreach ( $styles as $var => $val ) {
            $style_attr .= $var . ': ' . $val . '; ';
        }
        $style_attr .= $bg_css;

        $azsux_settings    = $settings;
        $azsux_instance_id = $instance_id;
        $azsux_data_html   = $data_html;
        $azsux_style_attr  = $style_attr;
        $azsux_items       = $settings['items'] ?? array();
        $azsux_active_idx  = $active_idx;

        ob_start();
        include AZSUX_PATH . "templates/slider.php";
        return ob_get_clean();
    }

    /**
     * Render Blog Showcase Layout
     *
     * @param int   $slider_id
     * @param array $settings
     * @return string
     */
    protected static function render_blog_showcase( $slider_id, $settings ) {
        $azsux_posts = AZSUX_Blog_Query::get_posts( $settings );
        $azsux_blog  = isset( $settings['blog'] ) && is_array( $settings['blog'] ) ? $settings['blog'] : AZSUX_Helpers::get_default_blog_settings();

        if ( empty( $azsux_posts ) ) {
            if ( ( function_exists( "ux_builder_is_active" ) && ux_builder_is_active() ) || is_admin() ) {
                return self::render_placeholder( __( "Không tìm thấy bài viết nào phù hợp với cấu hình Query Blog.", "az-slider-ux-pro" ) );
            }
            return '<!-- Az Slider UX Pro: No Blog Posts Found -->';
        }

        AZSUX_Assets::enqueue_frontend();

        $instance_id = AZSUX_Helpers::generate_instance_id( $slider_id );
        $total_cards = count( $azsux_posts );
        $active_idx  = AZSUX_Sanitizer::clamp_int( $settings['active_item_index'] ?? 0, 0, max( 0, $total_cards - 1 ), 0 );

        $data_attrs = array(
            'data-instance'        => esc_attr( $instance_id ),
            'data-layout'          => 'blog-showcase',
            'data-fan-preset'      => esc_attr( $azsux_blog['fan_preset'] ?? 'editorial-fan' ),
            'data-visible-desktop' => esc_attr( $azsux_blog['visible_desktop'] ?? 7 ),
            'data-visible-tablet'  => esc_attr( $azsux_blog['visible_tablet'] ?? 5 ),
            'data-visible-mobile'  => esc_attr( $azsux_blog['visible_mobile'] ?? 3 ),
            'data-loop'            => ! empty( $azsux_blog['loop'] ) ? 'true' : 'false',
            'data-side-click'      => esc_attr( $azsux_blog['side_click'] ?? 'activate' ),
            'data-active-click'    => esc_attr( $azsux_blog['active_click'] ?? 'open_post' ),
            'data-swipe'           => ! empty( $azsux_blog['swipe'] ) ? 'true' : 'false',
            'data-keyboard'        => ! empty( $azsux_blog['keyboard'] ) ? 'true' : 'false',
            'data-autoplay'        => ! empty( $azsux_blog['autoplay'] ) ? 'true' : 'false',
            'data-delay'           => esc_attr( $azsux_blog['autoplay_delay'] ?? 5000 ),
            'data-pause-hover'     => ! empty( $azsux_blog['pause_on_hover'] ) ? 'true' : 'false',
            'data-active-index'    => esc_attr( $active_idx ),
            'data-items-count'     => esc_attr( $total_cards ),
        );

        $data_html = '';
        foreach ( $data_attrs as $k => $v ) {
            $data_html .= ' ' . $k . '="' . $v . '"';
        }

        $slider_max_width_val = ( 'full' === ( $settings['slider_width'] ?? 'full' ) ) ? '100%' : esc_attr( $settings['slider_max_width'] ?? '100%' );
        $max_width_val        = ( 'full' === ( $settings['container_width'] ?? 'container' ) ) ? '100%' : esc_attr( $settings['max_width'] ?? '1320px' );

        $styles = array(
            '--azsux-height'                => esc_attr( $settings["height"] ),
            '--azsux-mobile-height'         => esc_attr( $settings["mobile_height"] ),
            '--azsux-gap'                   => esc_attr( $settings["gap"] ),
            '--azsux-radius'                => esc_attr( $settings["border_radius"] ),
            '--azsux-slider-max-width'      => $slider_max_width_val,
            '--azsux-max-width'             => $max_width_val,
            '--azsux-card-width'            => esc_attr( $azsux_blog['card_width'] ?? '365px' ),
            '--azsux-card-height'           => esc_attr( $azsux_blog['card_height'] ?? '390px' ),
            '--azsux-active-scale'          => esc_attr( $azsux_blog['active_scale'] ?? '1.05' ),
            '--azsux-active-bg'             => esc_attr( $azsux_blog['active_bg'] ?? '#935b39' ),
            '--azsux-active-text-color'     => esc_attr( $azsux_blog['active_text_color'] ?? '#ffffff' ),
            '--azsux-active-border-color'   => esc_attr( $azsux_blog['active_border_color'] ?? 'hsl(22.67deg 100% 79.38% / 29%)' ),
            '--azsux-active-shadow'         => esc_attr( $azsux_blog['active_shadow'] ?? '0 10px 30px hsl(18.68deg 100% 73.06% / 15%)' ),
            '--azsux-inactive-bg'           => esc_attr( $azsux_blog['inactive_bg'] ?? '#ffffff' ),
            '--azsux-inactive-text-color'   => esc_attr( $azsux_blog['inactive_text_color'] ?? '#0f172a' ),
            '--azsux-inactive-border-color' => esc_attr( $azsux_blog['inactive_border_color'] ?? 'rgba(255, 255, 255, 0.15)' ),
            '--azsux-arrow-color'           => esc_attr( $azsux_blog['arrow_color'] ?? '#ffffff' ),
            '--azsux-arrow-bg'              => esc_attr( $azsux_blog['arrow_bg'] ?? 'rgba(15, 23, 42, 0.7)' ),
        );

        $bg_css = '';
        switch ( $settings["bg_type"] ) {
            case 'color':
                $bg_css = 'background-color: ' . esc_attr( $settings["bg_color"] ) . ';';
                break;
            case 'gradient2':
                $bg_css = 'background: linear-gradient(' . esc_attr( $settings["bg_gradient_dir"] ) . ', ' . esc_attr( $settings["bg_gradient_color1"] ) . ', ' . esc_attr( $settings["bg_gradient_color2"] ) . ');';
                break;
            case 'gradient3':
                $bg_css = 'background: linear-gradient(' . esc_attr( $settings["bg_gradient_dir"] ) . ', ' . esc_attr( $settings["bg_gradient_color1"] ) . ', ' . esc_attr( $settings["bg_gradient_color2"] ) . ', ' . esc_attr( $settings["bg_gradient_color3"] ) . ');';
                break;
            case 'image':
                if ( ! empty( $settings["bg_image"] ) ) {
                    $bg_css = 'background-image: url("' . esc_url( $settings["bg_image"] ) . '"); background-size: cover; background-position: center;';
                } else {
                    $bg_css = 'background-color: ' . esc_attr( $settings["bg_color"] ) . ';';
                }
                break;
        }

        $style_attr = '';
        foreach ( $styles as $var => $val ) {
            $style_attr .= $var . ': ' . $val . '; ';
        }
        $style_attr .= $bg_css;

        $azsux_settings    = $settings;
        $azsux_instance_id = $instance_id;
        $azsux_data_html   = $data_html;
        $azsux_style_attr  = $style_attr;
        $azsux_active_idx  = $active_idx;

        ob_start();
        include AZSUX_PATH . "templates/blog-showcase.php";
        return ob_get_clean();
    }

    /**
     * Render UX Builder Placeholder when no slider is selected
     *
     * @param string $message
     * @return string
     */
    public static function render_placeholder( $message = "" ) {
        if ( empty( $message ) ) {
            $message = __( "Vui lòng chọn <strong>Slider</strong> từ danh sách dropdown ở bảng điều khiển bên trái.", "az-slider-ux-pro" );
        }

        return '<div class="azsux-builder-placeholder" style="padding: 40px 20px; background: #0f172a; color: #ffffff; border-radius: 16px; text-align: center; border: 2px dashed #854f2e; margin: 15px 0;">' .
               '<h3 style="margin: 0 0 10px 0; color: #ffffff; font-size: 20px; font-weight: 700;">Az Slider UX Pro</h3>' .
               '<p style="margin: 0; color: #cbd5e1; font-size: 14px;">' . $message . '</p>' .
               '</div>';
    }
}