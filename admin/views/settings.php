<?php
/**
 * Settings Admin View
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}
?>
<div class="wrap azsux-admin-wrap">
    <h1><?php esc_html_e( 'Cấu Hình Az Slider UX Pro', 'az-slider-ux-pro' ); ?></h1>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'azsux_settings_group' );
        do_settings_sections( 'azsux_settings_group' );
        $azsux_clean_on_uninstall = get_option( 'azsux_clean_on_uninstall', '0' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Dọn Dẹp Dữ Liệu Khi Xóa Plugin', 'az-slider-ux-pro' ); ?></th>
                <td>
                    <label for="azsux_clean_on_uninstall">
                        <input type="checkbox" name="azsux_clean_on_uninstall" id="azsux_clean_on_uninstall" value="1" <?php checked( $azsux_clean_on_uninstall, '1' ); ?>>
                        <?php esc_html_e( 'Xóa toàn bộ slider và cài đặt khi gỡ bỏ plugin (Uninstall)', 'az-slider-ux-pro' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Lưu ý: Nếu bật tùy chọn này, khi bạn Uninstall plugin, tất cả dữ liệu slider sẽ bị xóa hoàn toàn khỏi cơ sở dữ liệu.', 'az-slider-ux-pro' ); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button( __( 'Lưu Cài Đặt', 'az-slider-ux-pro' ) ); ?>
    </form>

    <!-- Demo Content Importer -->
    <div class="azsux-demo-box" style="margin-top: 30px; padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
        <h2 style="margin-top: 0; color: #166534;"><?php esc_html_e( 'Tạo 10 Bài Viết Demo Từ VnExpress (Khoa Học & Công Nghệ)', 'az-slider-ux-pro' ); ?></h2>
        <p style="color: #15803d; font-size: 14px;"><?php esc_html_e( 'Nhấp vào nút bên dưới để tự động lấy 10 bài viết mới nhất từ VnExpress Chuyên mục Khoa Học & Công Nghệ, kèm ảnh đại diện featured image, chuyên mục và nội dung chi tiết để test Blog Showcase Slider ngay lập tức.', 'az-slider-ux-pro' ); ?></p>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'azsux_import_demo_nonce' ); ?>
            <input type="hidden" name="action" value="azsux_import_demo_vnexpress">
            <button type="submit" class="button button-primary button-large">
                <span class="dashicons dashicons-download" style="vertical-align: middle; margin-top: -3px;"></span> <?php esc_html_e( 'Tạo 10 Bài Viết Demo VnExpress Ngay', 'az-slider-ux-pro' ); ?>
            </button>
        </form>
    </div>

    <!-- JSON Importer -->
    <div class="azsux-import-box" style="margin-top: 30px; padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 8px;">
        <h2><?php esc_html_e( 'Nhập Cấu Hình Slider Từ File (Import JSON)', 'az-slider-ux-pro' ); ?></h2>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
            <?php wp_nonce_field( 'azsux_import_action', 'azsux_import_nonce' ); ?>
            <input type="hidden" name="action" value="azsux_import_slider">
            <p>
                <input type="file" name="import_file" accept=".json" required>
            </p>
            <p>
                <button type="submit" class="button button-secondary"><?php esc_html_e( 'Nhập Slider JSON', 'az-slider-ux-pro' ); ?></button>
            </p>
        </form>
    </div>
</div>