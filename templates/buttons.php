<?php
/**
 * Buttons Template
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/** @var array $azsux_buttons */
if ( empty( $azsux_buttons ) || ! is_array( $azsux_buttons ) ) {
    return;
}
?>
<div class="azsux-buttons-wrapper">
    <?php foreach ( $azsux_buttons as $azsux_btn ) :
        $azsux_btn_text   = sanitize_text_field( $azsux_btn['text'] ?? '' );
        if ( '' === $azsux_btn_text ) {
            continue;
        }
        $azsux_btn_url    = esc_url( $azsux_btn['url'] ?? '#' );
        $azsux_btn_target = ( '_blank' === ( $azsux_btn['target'] ?? '' ) ) ? '_blank' : '_self';
        $azsux_btn_style  = sanitize_html_class( $azsux_btn['style'] ?? 'primary' );
        $azsux_btn_badge  = sanitize_text_field( $azsux_btn['badge'] ?? '' );
        
        $azsux_custom_style = '';
        if ( ! empty( $azsux_btn['bg_color'] ) ) {
            $azsux_custom_style .= 'background-color: ' . esc_attr( $azsux_btn['bg_color'] ) . '; border-color: ' . esc_attr( $azsux_btn['bg_color'] ) . '; ';
        }
        if ( ! empty( $azsux_btn['text_color'] ) ) {
            $azsux_custom_style .= 'color: ' . esc_attr( $azsux_btn['text_color'] ) . '; ';
        }
        ?>
        <a href="<?php echo esc_url( $azsux_btn_url ); ?>" target="<?php echo esc_attr( $azsux_btn_target ); ?>" class="azsux-btn azsux-btn-<?php echo esc_attr( $azsux_btn_style ); ?>" <?php echo ( '_blank' === $azsux_btn_target ) ? 'rel="noopener noreferrer"' : ''; ?> <?php echo ! empty( $azsux_custom_style ) ? 'style="' . esc_attr( $azsux_custom_style ) . '"' : ''; ?>>
            <span class="azsux-btn-text"><?php echo esc_html( $azsux_btn_text ); ?></span>
            <?php if ( ! empty( $azsux_btn_badge ) ) : ?>
                <span class="azsux-btn-badge"><?php echo esc_html( $azsux_btn_badge ); ?></span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>