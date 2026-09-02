# Az Slider UX Pro (v1.0.3)

Plugin WordPress tạo slider accordion và blog showcase tương tác hiện đại, hỗ trợ toàn diện Flatsome UX Builder, shortcode và Gutenberg.

## Tính Năng Nổi Bật:
- **Dual Layout Engines**:
  - **Accordion Showcase**: Bố cục chia đôi (Content Box + Thẻ Accordion tương tác mượt mà theo hover/click).
  - **Blog Showcase**: Hiển thị bài viết động chuẩn SEO với hiệu ứng quạt 3D đa chiều (3D Center Fan Cards).
- **Tích hợp Flatsome UX Builder**: Xem trước trực tiếp theo thời gian thực (Live Preview) trong iframe, kéo thả và tùy biến nhanh chóng.
- **Tương thích toàn diện**: Hỗ trợ mọi theme WordPress qua shortcode `[az_slider_ux id="..."]` và khối Gutenberg Block.
- **Tuân thủ chuẩn WordPress**: Đạt 100% tiêu chuẩn WordPress Plugin Check & PHPCS.

## Cài Đặt:
1. Tải thư mục `az-slider-ux-pro` lên thư mục `/wp-content/plugins/`.
2. Kích hoạt plugin trong menu "Plugins" của WordPress Admin.
3. Tạo slider mới trong menu "Az Slider UX" và chèn vào trang qua shortcode hoặc element UX Builder.

## Nhật Ký Thay Đổi (Changelog):

### = 1.0.2 =
- Tối ưu hóa nạp Asset và tương thích live preview trong Flatsome UX Builder.
- Bổ sung MutationObserver tự động khởi tạo slider theo thời gian thực khi chỉnh sửa.
- Sửa lỗi method truy vấn Blog Showcase (`build_query_args` & `query_posts`).
- Chuẩn hóa toàn bộ line endings LF và quy chuẩn bảo mật WordPress.

### = 1.0.1 =
- Bổ sung module Blog Showcase với WP_Query động và hiệu ứng quạt 3D.
- Tối ưu hóa bộ lọc danh mục và responsive controls.

### = 1.0.0 =
- Phiên bản phát hành đầu tiên.