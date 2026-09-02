<?php
/**
 * Master Blog Showcase Shell Template
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/** @var array $settings */
/** @var array $azsux_blog */
/** @var array $azsux_posts */
/** @var string $instance_id */
/** @var string $data_html */
/** @var string $style_attr */

$azsux_container_width = $settings['container_width'] ?? 'container';
$azsux_theme_mode      = $settings['theme_mode'] ?? 'dark';
$azsux_total_cards     = count( $azsux_posts );
$azsux_active_idx      = AZSUX_Sanitizer::clamp_int( $settings['active_item_index'] ?? 0, 0, max( 0, $azsux_total_cards - 1 ), 0 );

$azsux_show_arrows = ! empty( $azsux_blog['show_arrows'] );
$azsux_arrow_pos   = $azsux_blog['arrow_position'] ?? 'sides';
$azsux_is_top      = ( 0 === strpos( $azsux_arrow_pos, 'top-' ) );
$azsux_is_bottom   = ( 0 === strpos( $azsux_arrow_pos, 'bottom-' ) );
$azsux_is_sides    = ( 'sides' === $azsux_arrow_pos || ( ! $azsux_is_top && ! $azsux_is_bottom ) );
?>

<div class="azsux-slider-wrap azsux-layout-blog-showcase azsux-theme-<?php echo esc_attr( $azsux_theme_mode ); ?>"
     id="<?php echo esc_attr( $instance_id ); ?>"
     style="<?php echo esc_attr( $style_attr ); ?>"
     <?php echo $data_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

    <!-- Main Outer Container -->
    <div class="azsux-container azsux-width-<?php echo esc_attr( $azsux_container_width ); ?>">
        <!-- Overlay Layer -->
        <?php if ( ! empty( $settings['bg_overlay'] ) ) : ?>
            <div class="azsux-bg-overlay-layer" style="background: <?php echo esc_attr( $settings['bg_overlay'] ); ?>;"></div>
        <?php endif; ?>

        <!-- Stage Wrapper -->
        <div class="azsux-blog-stage-wrapper azsux-nav-pos-<?php echo esc_attr( $azsux_arrow_pos ); ?>">

            <!-- TOP NAVIGATION ARROWS -->
            <?php if ( $azsux_show_arrows && $azsux_total_cards > 1 && $azsux_is_top ) : ?>
                <div class="azsux-blog-nav-bar azsux-nav-bar-<?php echo esc_attr( $azsux_arrow_pos ); ?>">
                    <button type="button" class="azsux-blog-nav-btn azsux-blog-nav-prev" aria-label="<?php esc_attr_e( 'Bài trước', 'az-slider-ux-pro' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button type="button" class="azsux-blog-nav-btn azsux-blog-nav-next" aria-label="<?php esc_attr_e( 'Bài tiếp theo', 'az-slider-ux-pro' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            <?php endif; ?>

            <!-- SIDE PREV ARROW -->
            <?php if ( $azsux_show_arrows && $azsux_total_cards > 1 && $azsux_is_sides ) : ?>
                <button type="button" class="azsux-blog-nav-btn azsux-blog-nav-prev" aria-label="<?php esc_attr_e( 'Bài trước', 'az-slider-ux-pro' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
            <?php endif; ?>

            <!-- Stage Cards Track -->
            <div class="azsux-blog-stage" role="tablist" aria-label="<?php esc_attr_e( 'Blog Showcase Slider', 'az-slider-ux-pro' ); ?>">
                <?php foreach ( $azsux_posts as $azsux_idx => $azsux_post ) : ?>
                    <?php include AZSUX_PATH . 'templates/blog-card.php'; ?>
                <?php endforeach; ?>
            </div>

            <!-- SIDE NEXT ARROW -->
            <?php if ( $azsux_show_arrows && $azsux_total_cards > 1 && $azsux_is_sides ) : ?>
                <button type="button" class="azsux-blog-nav-btn azsux-blog-nav-next" aria-label="<?php esc_attr_e( 'Bài tiếp theo', 'az-slider-ux-pro' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            <?php endif; ?>

            <!-- BOTTOM NAVIGATION ARROWS -->
            <?php if ( $azsux_show_arrows && $azsux_total_cards > 1 && $azsux_is_bottom ) : ?>
                <div class="azsux-blog-nav-bar azsux-nav-bar-<?php echo esc_attr( $azsux_arrow_pos ); ?>">
                    <button type="button" class="azsux-blog-nav-btn azsux-blog-nav-prev" aria-label="<?php esc_attr_e( 'Bài trước', 'az-slider-ux-pro' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button type="button" class="azsux-blog-nav-btn azsux-blog-nav-next" aria-label="<?php esc_attr_e( 'Bài tiếp theo', 'az-slider-ux-pro' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>