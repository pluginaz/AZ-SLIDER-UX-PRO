<?php
/**
 * Content Box Template
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/** @var array $azsux_settings */
/** @var array $azsux_active_item */
/** @var int $azsux_active_idx */

$azsux_active_item = $azsux_settings['items'][ $azsux_active_idx ] ?? $azsux_settings['items'][0];
?>
<div class="azsux-content-box" aria-live="polite">
    <div class="azsux-content-inner">
        <!-- Badge -->
        <div class="azsux-badge-wrap">
            <span class="azsux-badge"><?php echo esc_html( $azsux_active_item['badge'] ?? '' ); ?></span>
        </div>

        <!-- Title -->
        <h2 class="azsux-title"><?php echo esc_html( $azsux_active_item['title'] ?? '' ); ?></h2>

        <!-- Description -->
        <p class="azsux-description"><?php echo esc_html( $azsux_active_item['description'] ?? '' ); ?></p>

        <!-- Buttons -->
        <div class="azsux-actions">
            <?php
            $azsux_buttons = $azsux_active_item['buttons'] ?? array();
            include AZSUX_PATH . 'templates/buttons.php';
            ?>
        </div>
    </div>
</div>