<?php
/**
 * Admin Class
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Admin {

    /**
     * Init hooks
     */
    public static function init() {
        add_action( "admin_menu", array( __CLASS__, "add_admin_menu" ) );
        add_action( "admin_notices", array( __CLASS__, "render_admin_notices" ) );
        add_action( "admin_post_azsux_import_demo_vnexpress", array( __CLASS__, "import_demo_vnexpress" ) );
    }

    /**
     * Add admin submenu pages
     */
    public static function add_admin_menu() {
        add_submenu_page(
            "edit.php?post_type=az_slider",
            __( "Cấu Hình Az Slider UX", "az-slider-ux-pro" ),
            __( "Cấu Hình", "az-slider-ux-pro" ),
            "manage_options",
            "azsux-settings",
            array( "AZSUX_Settings", "render_page" )
        );

        add_submenu_page(
            "edit.php?post_type=az_slider",
            __( "Thư Viện Mẫu Preset", "az-slider-ux-pro" ),
            __( "Mẫu Slider", "az-slider-ux-pro" ),
            "edit_posts",
            "azsux-templates",
            array( "AZSUX_Templates", "render_page" )
        );
    }

    /**
     * Import 10 demo posts from VnExpress Khoa học công nghệ
     */
    public static function import_demo_vnexpress() {
        if ( ! current_user_can( "edit_posts" ) ) {
            wp_die( esc_html__( "Bạn không có quyền thực hiện.", "az-slider-ux-pro" ) );
        }

        check_admin_referer( "azsux_import_demo_nonce" );

        require_once ABSPATH . "wp-admin/includes/file.php";
        require_once ABSPATH . "wp-admin/includes/media.php";
        require_once ABSPATH . "wp-admin/includes/image.php";

        $json_file = AZSUX_PATH . "includes/demo-posts.json";
        if ( ! file_exists( $json_file ) ) {
            wp_safe_redirect( admin_url( "edit.php?post_type=az_slider&page=azsux-settings&azsux_notice=demo_missing" ) );
            exit;
        }

        $articles = json_decode( file_get_contents( $json_file ), true );
        if ( empty( $articles ) || ! is_array( $articles ) ) {
            wp_safe_redirect( admin_url( "edit.php?post_type=az_slider&page=azsux-settings&azsux_notice=demo_invalid" ) );
            exit;
        }

        // Get or create category
        $cat_name = __( "Khoa Học & Công Nghệ", "az-slider-ux-pro" );
        $cat_id   = 0;
        $term     = get_term_by( "name", $cat_name, "category" );
        if ( $term ) {
            $cat_id = $term->term_id;
        } else {
            $inserted = wp_insert_term( $cat_name, "category" );
            if ( ! is_wp_error( $inserted ) ) {
                $cat_id = $inserted["term_id"];
            }
        }

        $created_count = 0;

        foreach ( $articles as $art ) {
            $title   = sanitize_text_field( $art["title"] ?? "" );
            $excerpt = sanitize_textarea_field( $art["excerpt"] ?? "" );
            $content = wp_kses_post( $art["content"] ?? "" );
            $img_url = esc_url_raw( $art["imageUrl"] ?? "" );

            if ( empty( $title ) || empty( $content ) ) {
                continue;
            }

            // Skip if post title already exists
            $query = new WP_Query( array(
                "title"                  => $title,
                "post_type"              => "post",
                "post_status"            => "any",
                "posts_per_page"         => 1,
                "no_found_rows"          => true,
                "ignore_sticky_posts"    => true,
                "update_post_term_cache" => false,
                "update_post_meta_cache" => false,
            ) );

            if ( ! empty( $query->posts ) ) {
                continue;
            }

            $post_id = wp_insert_post( array(
                "post_title"    => $title,
                "post_content"  => $content,
                "post_excerpt"  => $excerpt,
                "post_status"   => "publish",
                "post_type"     => "post",
                "post_category" => $cat_id ? array( $cat_id ) : array(),
            ) );

            if ( is_wp_error( $post_id ) || ! $post_id ) {
                continue;
            }

            $created_count++;

            // Sideload Featured Image
            if ( ! empty( $img_url ) ) {
                $tmp = download_url( $img_url );
                if ( ! is_wp_error( $tmp ) ) {
                    $url_path   = wp_parse_url( $img_url, PHP_URL_PATH );
                    $file_name  = sanitize_file_name( basename( (string) $url_path ) );
                    $file_array = array(
                        "name"     => ! empty( $file_name ) ? $file_name : "vnexpress-" . $post_id . ".jpg",
                        "tmp_name" => $tmp,
                    );

                    $att_id = media_handle_sideload( $file_array, $post_id, $title );
                    if ( ! is_wp_error( $att_id ) ) {
                        set_post_thumbnail( $post_id, $att_id );
                    }

                    wp_delete_file( $tmp );
                }
            }
        }

        wp_safe_redirect( admin_url( "edit.php?azsux_notice=demo_created&count=" . $created_count ) );
        exit;
    }

    /**
     * Render admin notices
     */
    public static function render_admin_notices() {
        if ( empty( $_GET["azsux_notice"] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

        $notice = sanitize_key( wp_unslash( $_GET["azsux_notice"] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        switch ( $notice ) {
            case "imported":
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( "Nhập slider từ file thành công!", "az-slider-ux-pro" ) . '</p></div>';
                break;
            case "demo_created":
                $count = isset( $_GET["count"] ) ? absint( wp_unslash( $_GET["count"] ) ) : 10; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                /* translators: %d: number of posts created */
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( sprintf( __( "Đã tự động tạo %d bài viết demo Khoa Học & Công Nghệ chuẩn từ VnExpress!", "az-slider-ux-pro" ), $count ) ) . '</p></div>';
                break;
            case "import_empty":
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( "Vui lòng chọn file JSON để nhập.", "az-slider-ux-pro" ) . '</p></div>';
                break;
            case "import_invalid":
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( "File JSON không đúng cấu trúc Az Slider UX Pro.", "az-slider-ux-pro" ) . '</p></div>';
                break;
            case "import_failed":
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( "Không thể tạo slider mới từ file đã chọn.", "az-slider-ux-pro" ) . '</p></div>';
                break;
        }
    }
}