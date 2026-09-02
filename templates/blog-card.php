<?php
/**
 * Blog Card Template for Az Slider UX Pro
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/** @var WP_Post $azsux_post */
/** @var array $azsux_blog */
/** @var int $azsux_idx */
/** @var int $azsux_total_cards */
/** @var int $azsux_active_idx */

$azsux_post_data = new AZSUX_Post_Data( $azsux_post );
if ( ! $azsux_post_data->is_valid() ) {
    return;
}

$azsux_post_id   = $azsux_post_data->get_id();
$azsux_permalink = $azsux_post_data->get_permalink();
$azsux_title     = $azsux_post_data->get_title();
$azsux_excerpt   = $azsux_post_data->get_excerpt( $azsux_blog['excerpt_length'] ?? 18 );
$azsux_thumb_url = $azsux_post_data->get_thumbnail_url( 'medium_large' );
$azsux_term_info = $azsux_post_data->get_primary_term();
$azsux_is_active = ( $azsux_idx === $azsux_active_idx );

$azsux_card_link_type = $azsux_blog['link_behavior'] ?? 'card';
?>
<div class="stagger-card azsux-blog-card <?php echo esc_attr( $azsux_is_active ? 'is-center azsux-active' : '' ); ?>"
     id="stagger-card-<?php echo esc_attr( $azsux_idx ); ?>"
     data-index="<?php echo esc_attr( $azsux_idx ); ?>"
     data-post-id="<?php echo esc_attr( $azsux_post_id ); ?>"
     data-permalink="<?php echo esc_url( $azsux_permalink ); ?>"
     role="tab"
     aria-selected="<?php echo esc_attr( $azsux_is_active ? 'true' : 'false' ); ?>"
     tabindex="0">

    <span class="stagger-card-corner-line"></span>

    <!-- Category Tag -->
    <?php if ( ! empty( $azsux_blog['show_category'] ) && ! empty( $azsux_term_info['name'] ) ) : ?>
        <span class="stagger-card-tag azsux-blog-tag">
            <a href="<?php echo esc_url( $azsux_term_info['link'] ); ?>" onclick="event.stopPropagation();" style="color: inherit; text-decoration: none;">
                <?php echo esc_html( $azsux_term_info['name'] ); ?>
            </a>
        </span>
    <?php endif; ?>

    <!-- Image -->
    <?php if ( ! empty( $azsux_blog['show_image'] ) && ! empty( $azsux_thumb_url ) ) : ?>
        <img src="<?php echo esc_url( $azsux_thumb_url ); ?>" alt="<?php echo esc_attr( $azsux_title ); ?>" class="stagger-card-img azsux-blog-card-img" />
    <?php else : ?>
        <div class="stagger-card-img azsux-blog-card-img azsux-no-image"></div>
    <?php endif; ?>

    <!-- Title -->
    <?php if ( ! empty( $azsux_blog['show_title'] ) ) : ?>
        <h3 class="stagger-card-title azsux-blog-card-title">
            <a href="<?php echo esc_url( $azsux_permalink ); ?>" target="_self" style="color: inherit; text-decoration: none;">
                <?php echo esc_html( $azsux_title ); ?>
            </a>
        </h3>
    <?php endif; ?>

    <!-- Excerpt -->
    <?php if ( ! empty( $azsux_blog['show_excerpt'] ) && ! empty( $azsux_excerpt ) ) : ?>
        <p class="stagger-card-excerpt azsux-blog-card-excerpt">
            <?php echo esc_html( $azsux_excerpt ); ?>
        </p>
    <?php endif; ?>

    <!-- Meta Footer -->
    <p class="stagger-card-meta azsux-blog-card-meta" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <?php if ( ! empty( $azsux_blog['show_date'] ) ) : ?>
            <span><?php echo esc_html( AZSUX_Post_Data::get_date_formatted( $azsux_post_id, $azsux_blog['date_format'] ?? '' ) ); ?></span>
        <?php endif; ?>

        <?php if ( ! empty( $azsux_blog['show_views'] ) ) : ?>
            <span><span class="dashicons dashicons-visibility" style="margin-right: 4px; font-size: 14px; vertical-align: middle;"></span><?php echo esc_html( $azsux_post_data->get_view_count() ); ?></span>
        <?php endif; ?>
    </p>
</div>