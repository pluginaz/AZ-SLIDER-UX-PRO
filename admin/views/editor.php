<?php
/**
 * Editor Metabox View
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/** @var array $settings */
$azsux_items          = ! empty( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : AZSUX_Helpers::get_default_items();
$azsux_blog           = isset( $settings['blog'] ) && is_array( $settings['blog'] ) ? $settings['blog'] : AZSUX_Helpers::get_default_blog_settings();
$azsux_current_layout = $settings['layout'] ?? 'accordion-showcase';
$azsux_categories     = get_categories( array( 'hide_empty' => false ) );
?>

<div class="azsux-admin-editor-wrap">
    <!-- Header Controls -->
    <div class="azsux-editor-topbar">
        <div class="azsux-topbar-title">
            <span class="dashicons dashicons-slides"></span>
            <strong>Az Slider UX Pro Editor</strong>
        </div>
        <div class="azsux-topbar-actions">
            <button type="button" class="button button-secondary azsux-duplicate-btn" data-slider-id="<?php echo esc_attr( $post->ID ); ?>">
                <span class="dashicons dashicons-admin-page"></span> <?php esc_html_e( 'Nhân Bản Slider', 'az-slider-ux-pro' ); ?>
            </button>
            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=azsux_export_slider&id=' . $post->ID ), 'azsux_export_' . $post->ID ) ); ?>" class="button button-secondary">
                <span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Xuất Cấu Hình (JSON)', 'az-slider-ux-pro' ); ?>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <nav class="nav-tab-wrapper azsux-nav-tabs">
        <a href="#azsux-tab-general" class="nav-tab nav-tab-active"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Cấu Hình Chung', 'az-slider-ux-pro' ); ?></a>
        <a href="#azsux-tab-style" class="nav-tab"><span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Màu Sắc & Phông Nền', 'az-slider-ux-pro' ); ?></a>
        <a href="#azsux-tab-items" class="nav-tab azsux-tab-acc-only" <?php echo ( 'blog-showcase' === $azsux_current_layout ) ? 'style="' . esc_attr( 'display:none;' ) . '"' : ''; ?>><span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Accordion Items', 'az-slider-ux-pro' ); ?> (<span class="azsux-items-count"><?php echo esc_html( count( $azsux_items ) ); ?></span>)</a>
        <a href="#azsux-tab-blog-query" class="nav-tab azsux-tab-blog-only" <?php echo ( 'accordion-showcase' === $azsux_current_layout ) ? 'style="' . esc_attr( 'display:none;' ) . '"' : ''; ?>><span class="dashicons dashicons-database"></span> <?php esc_html_e( 'Blog Query & Source', 'az-slider-ux-pro' ); ?></a>
        <a href="#azsux-tab-blog-card" class="nav-tab azsux-tab-blog-only" <?php echo ( 'accordion-showcase' === $azsux_current_layout ) ? 'style="' . esc_attr( 'display:none;' ) . '"' : ''; ?>><span class="dashicons dashicons-admin-post"></span> <?php esc_html_e( 'Thẻ Blog & Hiệu Ứng 3D', 'az-slider-ux-pro' ); ?></a>
        <a href="#azsux-tab-shortcode" class="nav-tab"><span class="dashicons dashicons-shortcode"></span> <?php esc_html_e( 'Mã Nhúng & Shortcode', 'az-slider-ux-pro' ); ?></a>
    </nav>

    <!-- Tab 1: General Settings -->
    <div id="azsux-tab-general" class="azsux-tab-content azsux-tab-active">
        <div class="azsux-form-grid">
            <div class="azsux-field-group azsux-full-width">
                <label for="azsux-layout"><?php esc_html_e( 'Chọn Chế Độ Slider (Layout Mode)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[layout]" id="azsux-layout">
                    <option value="accordion-showcase" <?php selected( $azsux_current_layout, 'accordion-showcase' ); ?>><?php esc_html_e( '1. Accordion Showcase (Split Content + Thẻ Thu Gọn Tương Tắc)', 'az-slider-ux-pro' ); ?></option>
                    <option value="blog-showcase" <?php selected( $azsux_current_layout, 'blog-showcase' ); ?>><?php esc_html_e( '2. Blog Showcase (Dynamic WP Query Bài Viết + Hiệu Ứng Quạt 3D Center)', 'az-slider-ux-pro' ); ?></option>
                </select>
                <p class="description"><?php esc_html_e( 'Accordion Showcase phù hợp giới thiệu dịch vụ/sản phẩm thủ công. Blog Showcase tự động nạp danh sách bài viết tin tức chuẩn SEO.', 'az-slider-ux-pro' ); ?></p>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-theme-mode"><?php esc_html_e( 'Chế Độ Nền & Tương Phản Màu Chữ', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[theme_mode]" id="azsux-theme-mode">
                    <option value="dark" <?php selected( $settings['theme_mode'] ?? 'dark', 'dark' ); ?>><?php esc_html_e( 'Giao Diện Nền Đen Tối (Dark Theme — Chữ Trắng Tương Phản)', 'az-slider-ux-pro' ); ?></option>
                    <option value="light" <?php selected( $settings['theme_mode'] ?? 'dark', 'light' ); ?>><?php esc_html_e( 'Giao Diện Nền Trắng Sáng (Light Theme — Chữ Tối Tương Phản)', 'az-slider-ux-pro' ); ?></option>
                    <option value="custom" <?php selected( $settings['theme_mode'] ?? 'dark', 'custom' ); ?>><?php esc_html_e( 'Tùy Chỉnh Màu Sắc (Custom Color Palette)', 'az-slider-ux-pro' ); ?></option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-content-side"><?php esc_html_e( 'Vị Trí Khối Nội Dung (Accordion)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[content_side]" id="azsux-content-side">
                    <option value="left" <?php selected( $settings['content_side'], 'left' ); ?>><?php esc_html_e( 'Nội dung Bên Trái — Accordion Bên Phải', 'az-slider-ux-pro' ); ?></option>
                    <option value="right" <?php selected( $settings['content_side'], 'right' ); ?>><?php esc_html_e( 'Accordion Bên Trái — Nội dung Bên Phải', 'az-slider-ux-pro' ); ?></option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-slider-width"><?php esc_html_e( 'Chiều Dài Khung Slider Outer', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[slider_width]" id="azsux-slider-width">
                    <option value="full" <?php selected( $settings['slider_width'] ?? 'full', 'full' ); ?>><?php esc_html_e( 'Khung Rộng Full 100% (Full Width)', 'az-slider-ux-pro' ); ?></option>
                    <option value="custom" <?php selected( $settings['slider_width'] ?? 'full', 'custom' ); ?>><?php esc_html_e( 'Giới Hạn Chiều Rộng (Set Max-Width)', 'az-slider-ux-pro' ); ?></option>
                </select>
            </div>

            <div class="azsux-field-group azsux-slider-maxwidth-row">
                <label for="azsux-slider-max-width"><?php esc_html_e( 'Max-Width Khung Slider Outer', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[slider_max_width]" id="azsux-slider-max-width" value="<?php echo esc_attr( $settings['slider_max_width'] ?? '100%' ); ?>" placeholder="1400px hoặc 100%">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-container-width"><?php esc_html_e( 'Chiều Rộng Khung Nội Dung Bên Trong (Container)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[container_width]" id="azsux-container-width">
                    <option value="container" <?php selected( $settings['container_width'] ?? 'container', 'container' ); ?>><?php esc_html_e( 'Khung Chuẩn (Container Max-Width)', 'az-slider-ux-pro' ); ?></option>
                    <option value="full" <?php selected( $settings['container_width'] ?? 'container', 'full' ); ?>><?php esc_html_e( 'Khung Dài 100% (Full Width)', 'az-slider-ux-pro' ); ?></option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-max-width"><?php esc_html_e( 'Max-Width Khung Nội Dung', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[max_width]" id="azsux-max-width" value="<?php echo esc_attr( $settings['max_width'] ?? '1320px' ); ?>" placeholder="1320px">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-height"><?php esc_html_e( 'Chiều Cao Slider (Desktop)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[height]" id="azsux-height" value="<?php echo esc_attr( $settings['height'] ); ?>" placeholder="550px">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-mobile-height"><?php esc_html_e( 'Chiều Cao Slider (Mobile)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[mobile_height]" id="azsux-mobile-height" value="<?php echo esc_attr( $settings['mobile_height'] ); ?>" placeholder="auto">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-gap"><?php esc_html_e( 'Khoảng Cách Giữa Thẻ (Gap)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[gap]" id="azsux-gap" value="<?php echo esc_attr( $settings['gap'] ); ?>" placeholder="16px">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-radius"><?php esc_html_e( 'Bo Góc Khung (Border Radius)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[border_radius]" id="azsux-radius" value="<?php echo esc_attr( $settings['border_radius'] ); ?>" placeholder="16px">
            </div>
        </div>
    </div>

    <!-- Tab 2: Style & Background -->
    <div id="azsux-tab-style" class="azsux-tab-content">
        <div class="azsux-form-grid">
            <div class="azsux-field-group">
                <label for="azsux-bg-type"><?php esc_html_e( 'Loại Phông Nền (Background Type)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[bg_type]" id="azsux-bg-type">
                    <option value="color" <?php selected( $settings['bg_type'], 'color' ); ?>><?php esc_html_e( 'Màu Đơn (Solid Color)', 'az-slider-ux-pro' ); ?></option>
                    <option value="gradient2" <?php selected( $settings['bg_type'], 'gradient2' ); ?>><?php esc_html_e( 'Gradient 2 Màu', 'az-slider-ux-pro' ); ?></option>
                    <option value="gradient3" <?php selected( $settings['bg_type'], 'gradient3' ); ?>><?php esc_html_e( 'Gradient 3 Màu Pro', 'az-slider-ux-pro' ); ?></option>
                    <option value="image" <?php selected( $settings['bg_type'], 'image' ); ?>><?php esc_html_e( 'Hình Ảnh Background', 'az-slider-ux-pro' ); ?></option>
                </select>
            </div>

            <div class="azsux-field-group azsux-bg-row azsux-bg-color-row">
                <label for="azsux-bg-color"><?php esc_html_e( 'Màu Phông Nền Khung (Background Color)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[bg_color]" id="azsux-bg-color" value="<?php echo esc_attr( $settings['bg_color'] ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group azsux-bg-row azsux-bg-grad-row">
                <label for="azsux-bg-grad-dir"><?php esc_html_e( 'Hướng Gradient', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[bg_gradient_dir]" id="azsux-bg-grad-dir">
                    <option value="135deg" <?php selected( $settings['bg_gradient_dir'], '135deg' ); ?>>135deg (Chéo góc)</option>
                    <option value="90deg" <?php selected( $settings['bg_gradient_dir'], '90deg' ); ?>>90deg (Ngang từ trái sang phải)</option>
                    <option value="180deg" <?php selected( $settings['bg_gradient_dir'], '180deg' ); ?>>180deg (Dọc từ trên xuống)</option>
                    <option value="45deg" <?php selected( $settings['bg_gradient_dir'], '45deg' ); ?>>45deg (Chéo lên)</option>
                </select>
            </div>

            <div class="azsux-field-group azsux-bg-row azsux-bg-grad-row">
                <label for="azsux-bg-grad-1"><?php esc_html_e( 'Màu Gradient 1', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[bg_gradient_color1]" id="azsux-bg-grad-1" value="<?php echo esc_attr( $settings['bg_gradient_color1'] ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group azsux-bg-row azsux-bg-grad-row">
                <label for="azsux-bg-grad-2"><?php esc_html_e( 'Màu Gradient 2', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[bg_gradient_color2]" id="azsux-bg-grad-2" value="<?php echo esc_attr( $settings['bg_gradient_color2'] ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group azsux-bg-row azsux-bg-grad3-row">
                <label for="azsux-bg-grad-3"><?php esc_html_e( 'Màu Gradient 3', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[bg_gradient_color3]" id="azsux-bg-grad-3" value="<?php echo esc_attr( $settings['bg_gradient_color3'] ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group azsux-bg-row azsux-bg-img-row">
                <label><?php esc_html_e( 'Hình Ảnh Background', 'az-slider-ux-pro' ); ?></label>
                <div class="azsux-media-uploader">
                    <input type="hidden" name="azsux[bg_image]" id="azsux-bg-image-url" value="<?php echo esc_attr( $settings['bg_image'] ); ?>">
                    <input type="hidden" name="azsux[bg_image_id]" id="azsux-bg-image-id" value="<?php echo esc_attr( $settings['bg_image_id'] ); ?>">
                    <div class="azsux-img-preview">
                        <?php if ( ! empty( $settings['bg_image'] ) ) : ?>
                            <img src="<?php echo esc_url( $settings['bg_image'] ); ?>" alt="Background">
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button azsux-upload-img-btn"><?php esc_html_e( 'Chọn Ảnh Background', 'az-slider-ux-pro' ); ?></button>
                    <button type="button" class="button azsux-remove-img-btn" <?php echo empty( $settings['bg_image'] ) ? 'style="' . esc_attr( 'display:none;' ) . '"' : ''; ?>><?php esc_html_e( 'Xóa Ảnh', 'az-slider-ux-pro' ); ?></button>
                </div>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-bg-overlay"><?php esc_html_e( 'Lớp Phủ Overlay (CSS/RGBA)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[bg_overlay]" id="azsux-bg-overlay" value="<?php echo esc_attr( $settings['bg_overlay'] ); ?>" placeholder="rgba(0,0,0,0.4)">
            </div>
        </div>
    </div>

    <!-- Tab 3: Accordion Items Repeater -->
    <div id="azsux-tab-items" class="azsux-tab-content">
        <p class="description"><?php esc_html_e( 'Kéo thả để sắp xếp thứ tự item. Mỗi item là một thẻ accordion chuyển đổi hình ảnh, badge, tiêu đề, mô tả và hệ thống nút bấm.', 'az-slider-ux-pro' ); ?></p>
        
        <div id="azsux-items-repeater" class="azsux-repeater-container">
            <?php foreach ( $azsux_items as $azsux_idx => $azsux_item ) : ?>
                <?php include AZSUX_PATH . 'templates/item.php'; ?>
            <?php endforeach; ?>
        </div>

        <div class="azsux-repeater-footer">
            <button type="button" class="button button-primary button-large id-add-item-btn" id="azsux-add-item-btn">
                <span class="dashicons dashicons-plus-alt"></span> <?php esc_html_e( 'Thêm Item Mới', 'az-slider-ux-pro' ); ?>
            </button>
        </div>
    </div>

    <!-- Tab: BLOG QUERY & SOURCE -->
    <div id="azsux-tab-blog-query" class="azsux-tab-content">
        <div class="azsux-form-grid">
            <div class="azsux-field-group">
                <label for="azsux-blog-source"><?php esc_html_e( 'Nguồn Bài Viết (Source Type)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][source]" id="azsux-blog-source">
                    <option value="dynamic" <?php selected( $azsux_blog['source'] ?? 'dynamic', 'dynamic' ); ?>><?php esc_html_e( 'Bài Viết Động (WP_Query)', 'az-slider-ux-pro' ); ?></option>
                    <option value="manual" <?php selected( $azsux_blog['source'] ?? 'dynamic', 'manual' ); ?>><?php esc_html_e( 'Chọn Bài Viết Thủ Công', 'az-slider-ux-pro' ); ?></option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-post-type"><?php esc_html_e( 'Loại Bài Viết (Post Type)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][post_type]" id="azsux-blog-post-type">
                    <option value="post" <?php selected( $azsux_blog['post_type'] ?? 'post', 'post' ); ?>>Posts (Bài viết)</option>
                    <option value="page" <?php selected( $azsux_blog['post_type'] ?? 'post', 'page' ); ?>>Pages (Trang)</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-posts-count"><?php esc_html_e( 'Tổng Số Bài Query Nạp Về', 'az-slider-ux-pro' ); ?></label>
                <input type="number" name="azsux[blog][posts_per_page]" id="azsux-blog-posts-count" value="<?php echo esc_attr( $azsux_blog['posts_per_page'] ?? 7 ); ?>" min="1" max="30" step="1">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-orderby"><?php esc_html_e( 'Sắp Xếp Theo (Order By)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][orderby]" id="azsux-blog-orderby">
                    <option value="date" <?php selected( $azsux_blog['orderby'] ?? 'date', 'date' ); ?>>Mới Nhất (Date)</option>
                    <option value="title" <?php selected( $azsux_blog['orderby'] ?? 'date', 'title' ); ?>>Tiêu Đề (Title A-Z)</option>
                    <option value="modified" <?php selected( $azsux_blog['orderby'] ?? 'date', 'modified' ); ?>>Mới Cập Nhật (Modified)</option>
                    <option value="rand" <?php selected( $azsux_blog['orderby'] ?? 'date', 'rand' ); ?>>Ngẫu Nhiên (Random)</option>
                    <option value="comment_count" <?php selected( $azsux_blog['orderby'] ?? 'date', 'comment_count' ); ?>>Nhiều Bình Luận Nhất</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-order"><?php esc_html_e( 'Thứ Tự Sắp Xếp (Order)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][order]" id="azsux-blog-order">
                    <option value="DESC" <?php selected( $azsux_blog['order'] ?? 'DESC', 'DESC' ); ?>>Giảm Dần (DESC)</option>
                    <option value="ASC" <?php selected( $azsux_blog['order'] ?? 'DESC', 'ASC' ); ?>>Tăng Dần (ASC)</option>
                </select>
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label><?php esc_html_e( 'Lọc Theo Chuyên Mục (Categories)', 'az-slider-ux-pro' ); ?></label>
                <div style="max-height: 150px; overflow-y: auto; border: 1px solid #cbd5e1; padding: 10px; border-radius: 6px; background: #fff;">
                    <?php
                    $azsux_selected_cats = $azsux_blog['categories'] ?? array();
                    foreach ( $azsux_categories as $azsux_cat ) :
                        ?>
                        <label style="display: block; font-weight: normal; margin-bottom: 4px;">
                            <input type="checkbox" name="azsux[blog][categories][]" value="<?php echo esc_attr( $azsux_cat->term_id ); ?>" <?php checked( in_array( $azsux_cat->term_id, $azsux_selected_cats, true ) ); ?>>
                            <?php echo esc_html( $azsux_cat->name ); ?> (<?php echo esc_html( $azsux_cat->count ); ?>)
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label for="azsux-blog-exclude-current">
                    <input type="checkbox" name="azsux[blog][exclude_current]" id="azsux-blog-exclude-current" value="1" <?php checked( ! empty( $azsux_blog['exclude_current'] ) ); ?>>
                    <strong><?php esc_html_e( 'Tự động loại trừ bài viết hiện tại khi xem trang bài viết đơn (Single Post)', 'az-slider-ux-pro' ); ?></strong>
                </label>
            </div>
        </div>
    </div>

    <!-- Tab: BLOG CARD & EFFECT -->
    <div id="azsux-tab-blog-card" class="azsux-tab-content">
        <div class="azsux-form-grid">
            <div class="azsux-field-group">
                <label for="azsux-blog-preset"><?php esc_html_e( 'Kiểu Hiệu Ứng Quạt 3D (Fan Preset)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][fan_preset]" id="azsux-blog-preset">
                    <option value="editorial-fan" <?php selected( $azsux_blog['fan_preset'] ?? 'editorial-fan', 'editorial-fan' ); ?>>Editorial Fan 3D (Mặc định chuẩn concept)</option>
                    <option value="soft-fan" <?php selected( $azsux_blog['fan_preset'] ?? 'editorial-fan', 'soft-fan' ); ?>>Soft Fan (Nghiêng nhẹ nhàng)</option>
                    <option value="strong-fan" <?php selected( $azsux_blog['fan_preset'] ?? 'editorial-fan', 'strong-fan' ); ?>>Strong Fan 3D (Nghiêng góc rộng 3D)</option>
                    <option value="flat-center" <?php selected( $azsux_blog['fan_preset'] ?? 'editorial-fan', 'flat-center' ); ?>>Flat Center (Phẳng không nghiêng)</option>
                    <option value="stacked" <?php selected( $azsux_blog['fan_preset'] ?? 'editorial-fan', 'stacked' ); ?>>Stacked (Xếp chồng đè)</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-arrow-position"><?php esc_html_e( 'Vị Trí Mũi Tên Điều Hướng [←] [→]', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][arrow_position]" id="azsux-blog-arrow-position">
                    <option value="sides" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'sides' ); ?>>Trái - Phải (Mặc định nằm 2 mép bên Stage)</option>
                    <option value="bottom-center" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'bottom-center' ); ?>>Bên Dưới — Căn Giữa (Bottom Center)</option>
                    <option value="bottom-left" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'bottom-left' ); ?>>Bên Dưới — Căn Trái (Bottom Left)</option>
                    <option value="bottom-right" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'bottom-right' ); ?>>Bên Dưới — Căn Phải (Bottom Right)</option>
                    <option value="top-center" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'top-center' ); ?>>Bên Trên — Căn Giữa (Top Center)</option>
                    <option value="top-left" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'top-left' ); ?>>Bên Trên — Căn Trái (Top Left)</option>
                    <option value="top-right" <?php selected( $azsux_blog['arrow_position'] ?? 'sides', 'top-right' ); ?>>Bên Trên — Căn Phải (Top Right)</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-visible-desktop"><?php esc_html_e( 'Số Thẻ Hiển Thị Trên Máy Tính (Desktop/PC)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][visible_desktop]" id="azsux-blog-visible-desktop">
                    <option value="3" <?php selected( $azsux_blog['visible_desktop'] ?? 7, 3 ); ?>>3 bài (1 trung tâm + 2 bài hai bên)</option>
                    <option value="5" <?php selected( $azsux_blog['visible_desktop'] ?? 7, 5 ); ?>>5 bài (1 trung tâm + 4 bài hai bên)</option>
                    <option value="7" <?php selected( $azsux_blog['visible_desktop'] ?? 7, 7 ); ?>>7 bài (1 trung tâm + 6 bài hai bên)</option>
                    <option value="9" <?php selected( $azsux_blog['visible_desktop'] ?? 7, 9 ); ?>>9 bài (1 trung tâm + 8 bài hai bên)</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-visible-tablet"><?php esc_html_e( 'Số Thẻ Hiển Thị Trên Máy Tính Bảng (Tablet)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][visible_tablet]" id="azsux-blog-visible-tablet">
                    <option value="3" <?php selected( $azsux_blog['visible_tablet'] ?? 5, 3 ); ?>>3 bài (1 trung tâm + 2 bài hai bên)</option>
                    <option value="5" <?php selected( $azsux_blog['visible_tablet'] ?? 5, 5 ); ?>>5 bài (1 trung tâm + 4 bài hai bên)</option>
                    <option value="7" <?php selected( $azsux_blog['visible_tablet'] ?? 5, 7 ); ?>>7 bài (1 trung tâm + 6 bài hai bên)</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-visible-mobile"><?php esc_html_e( 'Số Thẻ Hiển Thị Trên Điện Thoại (Mobile)', 'az-slider-ux-pro' ); ?></label>
                <select name="azsux[blog][visible_mobile]" id="azsux-blog-visible-mobile">
                    <option value="1" <?php selected( $azsux_blog['visible_mobile'] ?? 3, 1 ); ?>>1 bài (Chỉ hiện 1 bài trung tâm)</option>
                    <option value="3" <?php selected( $azsux_blog['visible_mobile'] ?? 3, 3 ); ?>>3 bài (1 bài trung tâm + 2 bài hai bên)</option>
                    <option value="5" <?php selected( $azsux_blog['visible_mobile'] ?? 3, 5 ); ?>>5 bài (1 bài trung tâm + 4 bài hai bên)</option>
                </select>
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-card-width"><?php esc_html_e( 'Chiều Rộng Thẻ Blog', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][card_width]" id="azsux-blog-card-width" value="<?php echo esc_attr( $azsux_blog['card_width'] ?? '365px' ); ?>" placeholder="365px">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-card-height"><?php esc_html_e( 'Chiều Cao Thẻ Blog', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][card_height]" id="azsux-blog-card-height" value="<?php echo esc_attr( $azsux_blog['card_height'] ?? '365px' ); ?>" placeholder="365px">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-active-scale"><?php esc_html_e( 'Độ Tăng Kích Thước Thẻ Active (Scale)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][active_scale]" id="azsux-blog-active-scale" value="<?php echo esc_attr( $azsux_blog['active_scale'] ?? '1.05' ); ?>" placeholder="1.05">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-active-bg"><?php esc_html_e( 'Màu Nền Thẻ Active', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][active_bg]" id="azsux-blog-active-bg" value="<?php echo esc_attr( $azsux_blog['active_bg'] ?? '#ffffff' ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-active-text"><?php esc_html_e( 'Màu Chữ Thẻ Active', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][active_text_color]" id="azsux-blog-active-text" value="<?php echo esc_attr( $azsux_blog['active_text_color'] ?? '#0f172a' ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-inactive-bg"><?php esc_html_e( 'Màu Nền Thẻ Phụ (Inactive)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][inactive_bg]" id="azsux-blog-inactive-bg" value="<?php echo esc_attr( $azsux_blog['inactive_bg'] ?? 'rgba(255, 255, 255, 0.08)' ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group">
                <label for="azsux-blog-inactive-text"><?php esc_html_e( 'Màu Chữ Thẻ Phụ (Inactive)', 'az-slider-ux-pro' ); ?></label>
                <input type="text" name="azsux[blog][inactive_text_color]" id="azsux-blog-inactive-text" value="<?php echo esc_attr( $azsux_blog['inactive_text_color'] ?? '#ffffff' ); ?>" class="azsux-color-picker">
            </div>

            <div class="azsux-field-group azsux-full-width">
                <label><?php esc_html_e( 'Các Thành Phần Hiển Thị Trên Thẻ', 'az-slider-ux-pro' ); ?></label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 6px;">
                    <label><input type="checkbox" name="azsux[blog][show_category]" value="1" <?php checked( ! empty( $azsux_blog['show_category'] ) ); ?>> Badge Chuyên Mục</label>
                    <label><input type="checkbox" name="azsux[blog][show_image]" value="1" <?php checked( ! empty( $azsux_blog['show_image'] ) ); ?>> Hình Ảnh Đại Diện</label>
                    <label><input type="checkbox" name="azsux[blog][show_title]" value="1" <?php checked( ! empty( $azsux_blog['show_title'] ) ); ?>> Tiêu Đề Bài Viết</label>
                    <label><input type="checkbox" name="azsux[blog][show_excerpt]" value="1" <?php checked( ! empty( $azsux_blog['show_excerpt'] ) ); ?>> Đoán Trích Excerpt</label>
                    <label><input type="checkbox" name="azsux[blog][show_date]" value="1" <?php checked( ! empty( $azsux_blog['show_date'] ) ); ?>> Ngày Đăng Bài</label>
                    <label><input type="checkbox" name="azsux[blog][show_views]" value="1" <?php checked( ! empty( $azsux_blog['show_views'] ) ); ?>> Lượt Xem (Views)</label>
                    <label><input type="checkbox" name="azsux[blog][show_reading_time]" value="1" <?php checked( ! empty( $azsux_blog['show_reading_time'] ) ); ?>> Thời Gian Đọc</label>
                    <label><input type="checkbox" name="azsux[blog][show_arrows]" value="1" <?php checked( ! empty( $azsux_blog['show_arrows'] ) ); ?>> Mũi Tên Điều Hướng [←] [→]</label>
                    <label><input type="checkbox" name="azsux[blog][loop]" value="1" <?php checked( ! empty( $azsux_blog['loop'] ) ); ?>> Vòng Lặp Vô Hạn (Loop)</label>
                    <label><input type="checkbox" name="azsux[blog][swipe]" value="1" <?php checked( ! empty( $azsux_blog['swipe'] ) ); ?>> Vuốt Touch Swipe Mobile</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 4: Shortcode & Embed -->
    <div id="azsux-tab-shortcode" class="azsux-tab-content">
        <div class="azsux-shortcode-box">
            <h3><?php esc_html_e( '1. Dán Shortcode vào Trang hoặc Bài Viết', 'az-slider-ux-pro' ); ?></h3>
            <p><?php esc_html_e( 'Sao chép đoạn mã ngắn bên dưới và dán vào bất kỳ trình soạn thảo nào (Gutenberg, Elementor, WPBakery, Classic Editor):', 'az-slider-ux-pro' ); ?></p>
            <div class="azsux-copy-code">
                <code>[az_slider_ux id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                <button type="button" class="button azsux-copy-btn" data-copy='[az_slider_ux id="<?php echo esc_attr( $post->ID ); ?>"]'><?php esc_html_e( 'Sao Chép', 'az-slider-ux-pro' ); ?></button>
            </div>

            <h3><?php esc_html_e( '2. Dùng Trong Code Template PHP Theme', 'az-slider-ux-pro' ); ?></h3>
            <div class="azsux-copy-code">
                <code>&lt;?php echo do_shortcode('[az_slider_ux id="<?php echo esc_attr( $post->ID ); ?>"]'); ?&gt;</code>
                <button type="button" class="button azsux-copy-btn" data-copy="&lt;?php echo do_shortcode('[az_slider_ux id=&quot;<?php echo esc_attr( $post->ID ); ?>&quot;]'); ?&gt;"><?php esc_html_e( 'Sao Chép', 'az-slider-ux-pro' ); ?></button>
            </div>

            <h3><?php esc_html_e( '3. Tích Hợp Flatsome UX Builder', 'az-slider-ux-pro' ); ?></h3>
            <p><?php esc_html_e( 'Khi thiết kế trang với Flatsome UX Builder, bạn chỉ cần tìm element "Az Slider UX Pro" trong danh sách UX Elements và chọn slider này.', 'az-slider-ux-pro' ); ?></p>
        </div>
    </div>
</div>