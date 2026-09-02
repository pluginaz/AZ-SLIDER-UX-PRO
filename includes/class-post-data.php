<?php
/**
 * Post Data Adapter for Blog Showcase
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Post_Data {

    /**
     * WP_Post Object
     *
     * @var WP_Post
     */
    protected $post;

    /**
     * Constructor
     *
     * @param int|WP_Post $post
     */
    public function __construct( $post ) {
        if ( is_numeric( $post ) ) {
            $this->post = get_post( absint( $post ) );
        } elseif ( $post instanceof WP_Post ) {
            $this->post = $post;
        } else {
            $this->post = null;
        }
    }

    /**
     * Check if valid post
     *
     * @return bool
     */
    public function is_valid() {
        return ( $this->post instanceof WP_Post && "publish" === $this->post->post_status );
    }

    /**
     * Get post ID
     *
     * @return int
     */
    public function get_id() {
        return $this->post ? $this->post->ID : 0;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function get_title() {
        if ( ! $this->post ) {
            return "";
        }
        return get_the_title( $this->post->ID );
    }

    /**
     * Get Permalink
     *
     * @return string
     */
    public function get_permalink() {
        if ( ! $this->post ) {
            return "#";
        }
        return get_permalink( $this->post->ID );
    }

    /**
     * Get Excerpt
     *
     * @param int $length
     * @return string
     */
    public function get_excerpt( $length = 18 ) {
        if ( ! $this->post ) {
            return "";
        }

        if ( ! empty( $this->post->post_excerpt ) ) {
            return wp_strip_all_tags( $this->post->post_excerpt );
        }

        $content = wp_strip_all_tags( strip_shortcodes( $this->post->post_content ) );
        return wp_trim_words( $content, $length, "..." );
    }

    /**
     * Get Thumbnail URL
     *
     * @param string $size
     * @return string
     */
    public function get_thumbnail_url( $size = "medium_large" ) {
        if ( ! $this->post ) {
            return "";
        }

        $attachment_id = get_post_thumbnail_id( $this->post->ID );
        if ( $attachment_id ) {
            $src = wp_get_attachment_image_src( $attachment_id, $size );
            if ( $src && ! empty( $src[0] ) ) {
                return $src[0];
            }
        }

        return "";
    }

    /**
     * Get Category / Primary Term Name
     *
     * @param string $taxonomy
     * @return array Array with 'name' and 'link'
     */
    public function get_primary_term( $taxonomy = "category" ) {
        if ( ! $this->post ) {
            return array( "name" => "", "link" => "#" );
        }

        $terms = get_the_terms( $this->post->ID, $taxonomy );
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return array( "name" => "", "link" => "#" );
        }

        $term = $terms[0];
        $link = get_term_link( $term );

        return array(
            "name" => $term->name,
            "link" => is_wp_error( $link ) ? "#" : $link,
        );
    }

    /**
     * Get Date Formatted
     *
     * @param string $format
     * @return string
     */
    public static function get_date_formatted( $post_id, $format = "" ) {
        if ( empty( $format ) ) {
            $format = get_option( "date_format", "Y-m-d" );
        }
        return get_the_date( $format, $post_id );
    }

    /**
     * Get View Count (with plugin adapters / meta key fallback)
     *
     * @param string $meta_key
     * @return int
     */
    public function get_view_count( $meta_key = "post_views_count" ) {
        if ( ! $this->post ) {
            return 0;
        }

        if ( empty( $meta_key ) ) {
            $meta_key = "post_views_count";
        }

        $views = get_post_meta( $this->post->ID, $meta_key, true );
        if ( "" === $views ) {
            $views = get_post_meta( $this->post->ID, "views", true );
        }

        return absint( $views );
    }

    /**
     * Get Reading Time
     *
     * @param int $wpm Words per minute
     * @return string
     */
    public function get_reading_time( $wpm = 200 ) {
        if ( ! $this->post ) {
            return "1 min read";
        }

        $words = str_word_count( wp_strip_all_tags( $this->post->post_content ) );
        $minutes = max( 1, ceil( $words / max( 1, $wpm ) ) );

        return $minutes . " " . __( "phút đọc", "az-slider-ux-pro" );
    }
}