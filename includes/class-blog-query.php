<?php
/**
 * Blog Query Builder for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Blog_Query {

    /**
     * Fetch posts for Blog Showcase Slider
     *
     * @param array $settings Slider settings array
     * @return array Array of WP_Post objects
     */
    public static function get_posts( $settings = array() ) {
        $blog_settings = isset( $settings['blog'] ) && is_array( $settings['blog'] ) ? $settings['blog'] : array();
        $source        = $blog_settings['source'] ?? 'dynamic';

        if ( 'manual' === $source ) {
            return self::get_manual_posts( $blog_settings );
        }

        return self::get_dynamic_posts( $blog_settings );
    }

    /**
     * Get manual posts by ID list
     *
     * @param array $blog_settings
     * @return array
     */
    protected static function get_manual_posts( $blog_settings ) {
        $manual_ids = isset( $blog_settings['manual_posts'] ) && is_array( $blog_settings['manual_posts'] ) ? array_map( 'absint', $blog_settings['manual_posts'] ) : array();
        $manual_ids = array_filter( array_unique( $manual_ids ) );

        if ( empty( $manual_ids ) ) {
            return array();
        }

        $query_args = array(
            'post_type'           => 'any',
            'post_status'         => 'publish',
            'post__in'            => $manual_ids,
            'orderby'             => 'post__in',
            'posts_per_page'      => count( $manual_ids ),
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        );

        $query = new WP_Query( $query_args );
        return $query->posts;
    }

    /**
     * Get dynamic posts via WP_Query
     *
     * @param array $blog_settings
     * @return array
     */
    protected static function get_dynamic_posts( $blog_settings ) {
        $post_type      = sanitize_key( $blog_settings['post_type'] ?? 'post' );
        if ( ! post_type_exists( $post_type ) ) {
            $post_type = 'post';
        }

        $posts_per_page = AZSUX_Sanitizer::clamp_int( $blog_settings['posts_per_page'] ?? 7, 1, 30, 7 );
        $orderby        = AZSUX_Sanitizer::sanitize_enum( $blog_settings['orderby'] ?? 'date', array( 'date', 'title', 'modified', 'rand', 'comment_count' ), 'date' );
        $order          = AZSUX_Sanitizer::sanitize_enum( $blog_settings['order'] ?? 'DESC', array( 'DESC', 'ASC' ), 'DESC' );

        $query_args = array(
            'post_type'           => $post_type,
            'post_status'         => 'publish',
            'posts_per_page'      => $posts_per_page,
            'orderby'             => $orderby,
            'order'               => $order,
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        );

        // Exclude current singular post if on single page
        if ( ! empty( $blog_settings['exclude_current'] ) && is_singular() ) {
            $current_id = get_the_ID();
            if ( $current_id ) {
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
                $query_args['post__not_in'] = array( $current_id );
            }
        }

        // Tax Query for categories / tags
        $tax_query = array();

        $cats = isset( $blog_settings['categories'] ) && is_array( $blog_settings['categories'] ) ? array_map( 'absint', $blog_settings['categories'] ) : array();
        $cats = array_filter( $cats );
        if ( ! empty( $cats ) ) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $cats,
                'operator' => 'IN',
            );
        }

        $ex_cats = isset( $blog_settings['exclude_categories'] ) && is_array( $blog_settings['exclude_categories'] ) ? array_map( 'absint', $blog_settings['exclude_categories'] ) : array();
        $ex_cats = array_filter( $ex_cats );
        if ( ! empty( $ex_cats ) ) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $ex_cats,
                'operator' => 'NOT IN',
            );
        }

        $tags = isset( $blog_settings['tags'] ) && is_array( $blog_settings['tags'] ) ? array_map( 'absint', $blog_settings['tags'] ) : array();
        $tags = array_filter( $tags );
        if ( ! empty( $tags ) ) {
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
                'operator' => 'IN',
            );
        }

        if ( count( $tax_query ) > 1 ) {
            $tax_query['relation'] = 'AND';
        }

        if ( ! empty( $tax_query ) ) {
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
            $query_args['tax_query'] = $tax_query;
        }

        $query_args = apply_filters( 'azsux_blog_query_args', $query_args, $blog_settings );
        $query = new WP_Query( $query_args );

        return $query->posts;
    }
}