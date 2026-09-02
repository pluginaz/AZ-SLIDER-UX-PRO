<?php
/**
 * Item Template (Used in Admin Editor & Frontend Accordion Card)
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

// Check if rendering frontend accordion card or admin metabox card.
$azsux_is_frontend = isset( $azsux_is_frontend ) ? $azsux_is_frontend : false;

if ( $azsux_is_frontend ) :
    /** @var array $azsux_item */
    /** @var int $azsux_idx */
    /** @var int $azsux_active_idx */
    $azsux_is_active = ( $azsux_idx === $azsux_active_idx );
    $azsux_item_id   = ! empty( $azsux_item['id'] ) ? $azsux_item['id'] : 'item-' . ( $azsux_idx + 1 );
    
    // Custom item background styling if provided
    $azsux_item_bg_style = '';
    if ( ! empty( $azsux_item['item_bg_color'] ) ) {
        $azsux_item_bg_style .= 'background-color: ' . esc_attr( $azsux_item['item_bg_color'] ) . '; ';
    }
    if ( ! empty( $azsux_item['item_bg_gradient1'] ) && ! empty( $azsux_item['item_bg_gradient2'] ) ) {
        $azsux_item_bg_style .= 'background: linear-gradient(135deg, ' . esc_attr( $azsux_item['item_bg_gradient1'] ) . ', ' . esc_attr( $azsux_item['item_bg_gradient2'] ) . '); ';
    }

    // JSON payload for fast frontend JavaScript active state switching
    $azsux_data_payload = wp_json_encode( array(
        'badge'       => $azsux_item['badge'] ?? '',
        'title'       => $azsux_item['title'] ?? '',
        'description' => $azsux_item['description'] ?? '',
        'buttons'     => $azsux_item['buttons'] ?? array(),
    ) );
    ?>
    <div class="azsux-accordion-item <?php echo esc_attr( $azsux_is_active ? 'azsux-active' : '' ); ?>"
         data-index="<?php echo esc_attr( $azsux_idx ); ?>"
         data-item-id="<?php echo esc_attr( $azsux_item_id ); ?>"
         data-payload="<?php echo esc_attr( $azsux_data_payload ); ?>"
         role="tab"
         id="azsux-tab-<?php echo esc_attr( $azsux_item_id ); ?>"
         aria-selected="<?php echo esc_attr( $azsux_is_active ? 'true' : 'false' ); ?>"
         aria-controls="azsux-panel-<?php echo esc_attr( $azsux_item_id ); ?>"
         tabindex="0"
         style="<?php echo esc_attr( $azsux_item_bg_style ); ?>">

        <!-- Background Image Container -->
        <?php if ( ! empty( $azsux_item['image'] ) ) : ?>
            <div class="azsux-item-image" style="background-image: url('<?php echo esc_url( $azsux_item['image'] ); ?>');"></div>
        <?php endif; ?>

        <!-- Gradient / Dark Overlay -->
        <div class="azsux-item-overlay"></div>

        <!-- Vertical Collapsed Label / Number Badge -->
        <div class="azsux-item-collapsed-label">
            <?php if ( ! empty( $azsux_item['item_badge'] ) ) : ?>
                <span class="azsux-item-num"><?php echo esc_html( $azsux_item['item_badge'] ); ?></span>
            <?php endif; ?>
            <span class="azsux-item-text"><?php echo esc_html( ! empty( $azsux_item['item_label'] ) ? $azsux_item['item_label'] : $azsux_item['title'] ); ?></span>
        </div>

        <!-- In-card content preview (visible on active card / mobile) -->
        <div class="azsux-item-active-card-content">
            <span class="azsux-card-badge"><?php echo esc_html( $azsux_item['badge'] ?? '' ); ?></span>
            <h3 class="azsux-card-title"><?php echo esc_html( $azsux_item['title'] ?? '' ); ?></h3>
        </div>
    </div>
<?php else :
    /** Admin Editor Metabox Item Card */
    $azsux_item_id = ! empty( $azsux_item['id'] ) ? $azsux_item['id'] : 'item-' . ( $azsux_idx + 1 );
    ?>
    <div class="azsux-item-card" data-index="<?php echo esc_attr( $azsux_idx ); ?>">
        <div class="azsux-item-header">
            <div class="azsux-item-title-bar">
                <span class="dashicons dashicons-menu azsux-drag-handle"></span>
                <span class="azsux-item-idx-label"><?php
                    /* translators: 1: item index number, 2: item title */
                    echo esc_html( sprintf( __( 'Item #%1$d: %2$s', 'az-slider-ux-pro' ), $azsux_idx + 1, $azsux_item['title'] ?? '' ) );
                ?></span>
            </div>
            <div class="azsux-item-actions">
                <button type="button" class="button button-small azsux-toggle-item-btn"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
                <button type="button" class="button button-small button-link-delete azsux-remove-item-btn"><span class="dashicons dashicons-trash"></span></button>
            </div>
        </div>

        <div class="azsux-item-body">
            <input type="hidden" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][id]" value="<?php echo esc_attr( $azsux_item_id ); ?>">
            
            <div class="azsux-field-group">
                <label><?php esc_html_e( 'Badge Khối', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][badge]" value="<?php echo esc_attr( $azsux_item['badge'] ?? '' ); ?>" placeholder="Hot / New / Nổi Bật">
            </div>

            <div class="azsux-field-group">
                <label><?php esc_html_e( 'Nhãn Dọc Khi Thu Gọn (Collapsed Label)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][item_label]" value="<?php echo esc_attr( $azsux_item['item_label'] ?? '' ); ?>" placeholder="Tên thu gọn">
            </div>

            <div class="azsux-field-group">
                <label><?php esc_html_e( 'Số / Badge Nhỏ Trên Thẻ', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][item_badge]" value="<?php echo esc_attr( $azsux_item['item_badge'] ?? '' ); ?>" placeholder="01 / PRO">
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label><?php esc_html_e( 'Tiêu Đề Item', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][title]" value="<?php echo esc_attr( $azsux_item['title'] ?? '' ); ?>" placeholder="Nhập tiêu đề chính">
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label><?php esc_html_e( 'Mô Tả Item', 'az-slider-ux-pro' ); ?></label>
                <textarea name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][description]" rows="3" placeholder="Nhập mô tả chi tiết..."><?php echo esc_textarea( $azsux_item['description'] ?? '' ); ?></textarea>
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label><?php esc_html_e( 'Hình Ảnh Item', 'az-slider-ux-pro' ); ?></label>
                <div class="azsux-media-uploader">
                    <input type="hidden" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][image]" value="<?php echo esc_attr( $azsux_item['image'] ?? '' ); ?>">
                    <input type="hidden" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][image_id]" value="<?php echo esc_attr( $azsux_item['image_id'] ?? 0 ); ?>">
                    <div class="azsux-img-preview">
                        <?php if ( ! empty( $azsux_item['image'] ) ) : ?>
                            <img src="<?php echo esc_url( $azsux_item['image'] ); ?>" alt="Preview">
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button azsux-upload-img-btn"><?php esc_html_e( 'Chọn Ảnh Item', 'az-slider-ux-pro' ); ?></button>
                    <button type="button" class="button azsux-remove-img-btn" <?php echo empty( $azsux_item['image'] ) ? 'style="' . esc_attr( 'display:none;' ) . '"' : ''; ?>><?php esc_html_e( 'Xóa Ảnh', 'az-slider-ux-pro' ); ?></button>
                </div>
            </div>

            <!-- Custom background override per item -->
            <div class="azsux-field-group">
                <label><?php esc_html_e( 'Màu Nền Thẻ (Custom BG)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][item_bg_color]" value="<?php echo esc_attr( $azsux_item['item_bg_color'] ?? '' ); ?>" placeholder="#1e293b">
            </div>

            <div class="azsux-field-group">
                <label><?php esc_html_e( 'Màu Gradient Thẻ 1', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][item_bg_gradient1]" value="<?php echo esc_attr( $azsux_item['item_bg_gradient1'] ?? '' ); ?>" placeholder="#1e293b">
            </div>

            <div class="azsux-field-group">
                <label><?php esc_html_e( 'Màu Gradient Thẻ 2', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][item_bg_gradient2]" value="<?php echo esc_attr( $azsux_item['item_bg_gradient2'] ?? '' ); ?>" placeholder="#334155">
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label><?php esc_html_e( 'Hệ Thống Nút Bấm Khi Active', 'az-slider-ux-pro' ); ?></label>
                <div class="azsux-buttons-subrepeater">
                    <div class="azsux-buttons-list">
                        <?php
                        $azsux_buttons = ! empty( $azsux_item['buttons'] ) && is_array( $azsux_item['buttons'] ) ? $azsux_item['buttons'] : array();
                        foreach ( $azsux_buttons as $azsux_b_idx => $azsux_btn ) :
                            ?>
                            <div class="azsux-button-row">
                                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][buttons][<?php echo esc_attr( $azsux_b_idx ); ?>][text]" value="<?php echo esc_attr( $azsux_btn['text'] ?? '' ); ?>" placeholder="Chữ hiển thị">
                                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][buttons][<?php echo esc_attr( $azsux_b_idx ); ?>][url]" value="<?php echo esc_attr( $azsux_btn['url'] ?? '#' ); ?>" placeholder="Đường dẫn">
                                <select name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][buttons][<?php echo esc_attr( $azsux_b_idx ); ?>][style]">
                                    <option value="primary" <?php selected( $azsux_btn['style'] ?? '', 'primary' ); ?>>Primary</option>
                                    <option value="secondary" <?php selected( $azsux_btn['style'] ?? '', 'secondary' ); ?>>Secondary</option>
                                    <option value="outline" <?php selected( $azsux_btn['style'] ?? '', 'outline' ); ?>>Outline</option>
                                    <option value="link" <?php selected( $azsux_btn['style'] ?? '', 'link' ); ?>>Link</option>
                                </select>
                                <input type="text" name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][buttons][<?php echo esc_attr( $azsux_b_idx ); ?>][badge]" value="<?php echo esc_attr( $azsux_btn['badge'] ?? '' ); ?>" placeholder="Badge nút">
                                <select name="azsux[items][<?php echo esc_attr( $azsux_idx ); ?>][buttons][<?php echo esc_attr( $azsux_b_idx ); ?>][target]">
                                    <option value="_self" <?php selected( $azsux_btn['target'] ?? '', '_self' ); ?>>Chuyển trang</option>
                                    <option value="_blank" <?php selected( $azsux_btn['target'] ?? '', '_blank' ); ?>>Mở tab mới</option>
                                </select>
                                <button type="button" class="button azsux-remove-btn-row"><span class="dashicons dashicons-trash"></span></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button azsux-add-btn-btn"><span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Thêm Nút Bấm', 'az-slider-ux-pro' ); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>