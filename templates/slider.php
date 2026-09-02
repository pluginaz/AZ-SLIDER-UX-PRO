<?php
/**
 * Master Slider Shell Template
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/** @var array $azsux_settings */
/** @var string $azsux_instance_id */
/** @var string $azsux_data_html */
/** @var string $azsux_style_attr */
/** @var int $azsux_active_idx */

$azsux_content_side    = $azsux_settings['content_side'] ?? 'left';
$azsux_container_width = $azsux_settings['container_width'] ?? 'container';
$azsux_items           = $azsux_settings['items'] ?? array();
?>

<div class="azsux-slider-wrap azsux-content-<?php echo esc_attr( $azsux_content_side ); ?>"
     id="<?php echo esc_attr( $azsux_instance_id ); ?>"
     style="<?php echo esc_attr( $azsux_style_attr ); ?>"
     <?php echo $azsux_data_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

    <!-- Main Outer Container -->
    <div class="azsux-container azsux-width-<?php echo esc_attr( $azsux_container_width ); ?>">
        <!-- Overlay Layer for Background Images -->
        <?php if ( ! empty( $azsux_settings['bg_overlay'] ) ) : ?>
            <div class="azsux-bg-overlay-layer" style="background: <?php echo esc_attr( $azsux_settings['bg_overlay'] ); ?>;"></div>
        <?php endif; ?>

        <!-- Split Content Layout Grid -->
        <div class="azsux-split-layout">
            <?php if ( 'left' === $azsux_content_side ) : ?>
                <!-- Content Box Left -->
                <?php include AZSUX_PATH . 'templates/content.php'; ?>

                <!-- Accordion Items Right -->
                <div class="azsux-accordion-wrapper" role="tablist" aria-label="<?php esc_attr_e( 'Accordion Showcase', 'az-slider-ux-pro' ); ?>">
                    <?php foreach ( $azsux_items as $azsux_idx => $azsux_item ) : ?>
                        <?php
                        $azsux_is_frontend = true;
                        include AZSUX_PATH . 'templates/item.php';
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <!-- Accordion Items Left -->
                <div class="azsux-accordion-wrapper" role="tablist" aria-label="<?php esc_attr_e( 'Accordion Showcase', 'az-slider-ux-pro' ); ?>">
                    <?php foreach ( $azsux_items as $azsux_idx => $azsux_item ) : ?>
                        <?php
                        $azsux_is_frontend = true;
                        include AZSUX_PATH . 'templates/item.php';
                        ?>
                    <?php endforeach; ?>
                </div>

                <!-- Content Box Right -->
                <?php include AZSUX_PATH . 'templates/content.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>