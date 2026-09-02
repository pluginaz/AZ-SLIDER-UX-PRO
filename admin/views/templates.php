<?php
/**
 * Templates Preset View
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

$azsux_presets = AZSUX_Templates::get_presets();
?>
<div class="wrap azsux-admin-wrap">
    <h1><?php esc_html_e( 'Thư Viện Mẫu Slider Accordion Pro', 'az-slider-ux-pro' ); ?></h1>
    <p><?php esc_html_e( 'Chọn một mẫu thiết kế sẵn để khởi tạo slider ấn tượng chỉ với 1 click.', 'az-slider-ux-pro' ); ?></p>

    <div class="azsux-presets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
        <?php foreach ( $azsux_presets as $azsux_key => $azsux_preset ) : ?>
            <div class="azsux-preset-card" style="background: #fff; border: 1px solid #c3c4c7; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div>
                    <h3 style="margin-top: 0; font-size: 18px; color: #1d2327;"><?php echo esc_html( $azsux_preset['title'] ); ?></h3>
                    <p style="color: #646970; font-size: 14px; line-height: 1.5;"><?php echo esc_html( $azsux_preset['desc'] ); ?></p>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" class="button button-primary azsux-apply-template-btn" data-template="<?php echo esc_attr( $azsux_key ); ?>">
                        <span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Tạo Slider Từ Mẫu Này', 'az-slider-ux-pro' ); ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>