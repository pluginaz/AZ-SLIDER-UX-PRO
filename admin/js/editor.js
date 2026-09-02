(function ($) {
    "use strict";

    $(document).ready(function () {
        // Init WP Color Picker palette on color inputs
        function initColorPickers() {
            if ($.fn.wpColorPicker) {
                $(".azsux-color-picker").each(function () {
                    if (!$(this).hasClass("wp-color-picker")) {
                        $(this).wpColorPicker();
                    }
                });
            }
        }
        initColorPickers();

        // Helper to set color picker value dynamically
        function setColorValue(selector, color) {
            var input = $(selector);
            input.val(color);
            if (input.data("wpWpColorPicker")) {
                input.wpColorPicker("color", color);
            }
        }

        // Layout Mode Switcher (Accordion vs Blog Showcase)
        function updateLayoutTabs() {
            var layout = $("#azsux-layout").val();
            if (layout === "blog-showcase") {
                $(".azsux-tab-acc-only").hide();
                $(".azsux-tab-blog-only").show();
                if ($("#azsux-tab-items").hasClass("azsux-tab-active")) {
                    $(".azsux-nav-tabs .nav-tab").first().trigger("click");
                }
            } else {
                $(".azsux-tab-acc-only").show();
                $(".azsux-tab-blog-only").hide();
                if ($("#azsux-tab-blog-query").hasClass("azsux-tab-active") || $("#azsux-tab-blog-card").hasClass("azsux-tab-active")) {
                    $(".azsux-nav-tabs .nav-tab").first().trigger("click");
                }
            }
        }
        $("#azsux-layout").on("change", updateLayoutTabs);
        updateLayoutTabs();

        // Theme Mode Contrast Switcher
        $("#azsux-theme-mode").on("change", function () {
            var mode = $(this).val();
            if (mode === "light") {
                setColorValue("#azsux-bg-color", "#ffffff");
                setColorValue("#azsux-title-color", "#0f172a");
                setColorValue("#azsux-desc-color", "#475569");
                setColorValue("#azsux-badge-bg", "#2563eb");
                setColorValue("#azsux-badge-color", "#ffffff");
            } else if (mode === "dark") {
                setColorValue("#azsux-bg-color", "#0f172a");
                setColorValue("#azsux-title-color", "#ffffff");
                setColorValue("#azsux-desc-color", "#cbd5e1");
                setColorValue("#azsux-badge-bg", "#854f2e");
                setColorValue("#azsux-badge-color", "#ffffff");
            }
        });

        // Tab Navigation
        $(".azsux-nav-tabs .nav-tab").on("click", function (e) {
            e.preventDefault();
            var target = $(this).attr("href");
            $(".azsux-nav-tabs .nav-tab").removeClass("nav-tab-active");
            $(this).addClass("nav-tab-active");
            $(".azsux-tab-content").removeClass("azsux-tab-active");
            $(target).addClass("azsux-tab-active");
            initColorPickers();
        });

        // Background Type Conditional Fields
        function updateBgRows() {
            var type = $("#azsux-bg-type").val();
            $(".azsux-bg-row").hide();
            if (type === "color") {
                $(".azsux-bg-color-row").show();
            } else if (type === "gradient2") {
                $(".azsux-bg-grad-row").show();
            } else if (type === "gradient3") {
                $(".azsux-bg-grad-row, .azsux-bg-grad3-row").show();
            } else if (type === "image") {
                $(".azsux-bg-img-row, .azsux-bg-color-row").show();
            }
        }
        $("#azsux-bg-type").on("change", updateBgRows);
        updateBgRows();

        // Slider Outer Width Conditional Fields
        function updateSliderWidthRows() {
            var widthType = $("#azsux-slider-width").val();
            if (widthType === "custom") {
                $(".azsux-slider-maxwidth-row").show();
            } else {
                $(".azsux-slider-maxwidth-row").hide();
            }
        }
        $("#azsux-slider-width").on("change", updateSliderWidthRows);
        updateSliderWidthRows();

        // Sortable Items Repeater
        if ($.fn.sortable) {
            $("#azsux-items-repeater").sortable({
                handle: ".azsux-drag-handle",
                placeholder: "azsux-item-card-placeholder",
                axis: "y",
                opacity: 0.8,
                update: function () {
                    updateItemIndices();
                }
            });
        }

        // Toggle Item Body Collapse
        $(document).on("click", ".azsux-toggle-item-btn", function (e) {
            e.preventDefault();
            var card = $(this).closest(".azsux-item-card");
            card.find(".azsux-item-body").slideToggle(200);
            $(this).find(".dashicons").toggleClass("dashicons-arrow-down-alt2 dashicons-arrow-up-alt2");
        });

        // Remove Item Card
        $(document).on("click", ".azsux-remove-item-btn", function (e) {
            e.preventDefault();
            if (confirm(AZSliderAdminVars.i18n.confirm_delete || "Bạn có chắc muốn xóa?")) {
                $(this).closest(".azsux-item-card").remove();
                updateItemIndices();
            }
        });

        // Update item count & indices
        function updateItemIndices() {
            var count = 0;
            $("#azsux-items-repeater .azsux-item-card").each(function (index) {
                count++;
                $(this).attr("data-index", index);
                $(this).find(".azsux-item-idx-label").text("Item #" + (index + 1));

                // Reindex input names
                $(this).find("input, select, textarea").each(function () {
                    var name = $(this).attr("name");
                    if (name) {
                        var newName = name.replace(/azsux\[items\]\[\d+\]/, "azsux[items][" + index + "]");
                        $(this).attr("name", newName);
                    }
                });
            });
            $(".azsux-items-count").text(count);
        }

        // Add New Item
        $("#azsux-add-item-btn").on("click", function (e) {
            e.preventDefault();
            var newIndex = $("#azsux-items-repeater .azsux-item-card").length;
            var template = getNewItemTemplate(newIndex);
            $("#azsux-items-repeater").append(template);
            updateItemIndices();
            initColorPickers();
        });

        // Add Button inside Item
        $(document).on("click", ".azsux-add-btn-btn", function (e) {
            e.preventDefault();
            var itemCard = $(this).closest(".azsux-item-card");
            var itemIdx = itemCard.attr("data-index") || 0;
            var buttonsContainer = itemCard.find(".azsux-buttons-list");
            var btnIdx = buttonsContainer.find(".azsux-button-row").length;
            var btnHtml = getButtonRowTemplate(itemIdx, btnIdx);
            buttonsContainer.append(btnHtml);
        });

        // Remove Button Row
        $(document).on("click", ".azsux-remove-btn-row", function (e) {
            e.preventDefault();
            $(this).closest(".azsux-button-row").remove();
        });

        // WP Media Uploader Frame Handler
        $(document).on("click", ".azsux-upload-img-btn", function (e) {
            e.preventDefault();
            var button = $(this);
            var parent = button.closest(".azsux-media-uploader");
            var urlInput = parent.find('input[name*="[image]"], input[name*="[bg_image]"]');
            var idInput = parent.find('input[name*="[image_id]"], input[name*="[bg_image_id]"]');
            var preview = parent.find(".azsux-img-preview");
            var removeBtn = parent.find(".azsux-remove-img-btn");

            var frame = wp.media({
                title: AZSliderAdminVars.i18n.select_image || "Chọn Hình Ảnh",
                button: { text: AZSliderAdminVars.i18n.use_image || "Sử dụng ảnh này" },
                multiple: false
            });

            frame.on("select", function () {
                var attachment = frame.state().get("selection").first().toJSON();
                urlInput.val(attachment.url);
                idInput.val(attachment.id);
                preview.html('<img src="' + attachment.url + '" alt="Preview">');
                removeBtn.show();
            });

            frame.open();
        });

        // Remove Image Handler
        $(document).on("click", ".azsux-remove-img-btn", function (e) {
            e.preventDefault();
            var parent = $(this).closest(".azsux-media-uploader");
            parent.find('input[name*="[image]"], input[name*="[bg_image]"]').val("");
            parent.find('input[name*="[image_id]"], input[name*="[bg_image_id]"]').val("0");
            parent.find(".azsux-img-preview").empty();
            $(this).hide();
        });

        // AJAX Duplicate Slider
        $(".azsux-duplicate-btn").on("click", function (e) {
            e.preventDefault();
            var sliderId = $(this).data("slider-id");
            var btn = $(this);
            btn.prop("disabled", true).text("Đang nhân bản...");

            $.post(AZSliderAdminVars.ajax_url, {
                action: "azsux_duplicate_slider",
                nonce: AZSliderAdminVars.nonce,
                slider_id: sliderId
            }, function (res) {
                if (res.success && res.data.redirect) {
                    window.location.href = res.data.redirect;
                } else {
                    alert(res.data.message || "Lỗi khi nhân bản.");
                    btn.prop("disabled", false).text("Nhân Bản Slider");
                }
            });
        });

        // AJAX Apply Template Preset
        $(".azsux-apply-template-btn").on("click", function (e) {
            e.preventDefault();
            var templateKey = $(this).data("template");
            var btn = $(this);
            btn.prop("disabled", true).text("Đang áp dụng...");

            $.post(AZSliderAdminVars.ajax_url, {
                action: "azsux_apply_template",
                nonce: AZSliderAdminVars.nonce,
                template: templateKey
            }, function (res) {
                if (res.success && res.data.redirect) {
                    window.location.href = res.data.redirect;
                } else {
                    alert(res.data.message || "Lỗi áp dụng mẫu.");
                    btn.prop("disabled", false).text("Tạo Slider Từ Mẫu Này");
                }
            });
        });

        // Copy Shortcode
        $(".azsux-copy-btn").on("click", function (e) {
            e.preventDefault();
            var text = $(this).data("copy");
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(text).select();
            document.execCommand("copy");
            temp.remove();
            $(this).text("Đã Sao Chép!");
            var btn = $(this);
            setTimeout(function () {
                btn.text("Sao Chép");
            }, 2000);
        });

        // Template helper string generators
        function getButtonRowTemplate(itemIdx, btnIdx) {
            return '<div class="azsux-button-row">' +
                '<input type="text" name="azsux[items][' + itemIdx + '][buttons][' + btnIdx + '][text]" placeholder="Chữ hiển thị" value="Khám Phá">' +
                '<input type="text" name="azsux[items][' + itemIdx + '][buttons][' + btnIdx + '][url]" placeholder="Đường dẫn (URL)" value="#">' +
                '<select name="azsux[items][' + itemIdx + '][buttons][' + btnIdx + '][style]">' +
                '<option value="primary">Primary</option><option value="secondary">Secondary</option><option value="outline">Outline</option><option value="link">Link</option>' +
                '</select>' +
                '<input type="text" name="azsux[items][' + itemIdx + '][buttons][' + btnIdx + '][badge]" placeholder="Badge nút" value="">' +
                '<select name="azsux[items][' + itemIdx + '][buttons][' + btnIdx + '][target]"><option value="_self">Chuyển trang</option><option value="_blank">Mở tab mới</option></select>' +
                '<button type="button" class="button azsux-remove-btn-row"><span class="dashicons dashicons-trash"></span></button>' +
                '</div>';
        }

        function getNewItemTemplate(idx) {
            return '<div class="azsux-item-card" data-index="' + idx + '">' +
                '<div class="azsux-item-header">' +
                '<div class="azsux-item-title-bar"><span class="dashicons dashicons-menu azsux-drag-handle"></span><span class="azsux-item-idx-label">Item #' + (idx + 1) + '</span></div>' +
                '<div class="azsux-item-actions">' +
                '<button type="button" class="button button-small azsux-toggle-item-btn"><span class="dashicons dashicons-arrow-up-alt2"></span></button>' +
                '<button type="button" class="button button-small button-link-delete azsux-remove-item-btn"><span class="dashicons dashicons-trash"></span></button>' +
                '</div></div>' +
                '<div class="azsux-item-body">' +
                '<div class="azsux-field-group"><label>Badge Item</label><input type="text" name="azsux[items][' + idx + '][badge]" value="Mới ' + (idx + 1) + '"></div>' +
                '<div class="azsux-field-group"><label>Nhãn Dọc Khi Collapsed</label><input type="text" name="azsux[items][' + idx + '][item_label]" value="Showcase ' + (idx + 1) + '"></div>' +
                '<div class="azsux-field-group azsux-full-width"><label>Tiêu Đề Item</label><input type="text" name="azsux[items][' + idx + '][title]" value="Tiêu đề Showcase mới"></div>' +
                '<div class="azsux-field-group azsux-full-width"><label>Mô Tả Item</label><textarea name="azsux[items][' + idx + '][description]" rows="3">Mô tả nội dung chi tiết cho item này.</textarea></div>' +
                '<div class="azsux-field-group azsux-full-width"><label>Hình Ảnh Item</label><div class="azsux-media-uploader"><input type="hidden" name="azsux[items][' + idx + '][image]" value=""><input type="hidden" name="azsux[items][' + idx + '][image_id]" value="0"><div class="azsux-img-preview"></div><button type="button" class="button azsux-upload-img-btn">Chọn Ảnh</button><button type="button" class="button azsux-remove-img-btn" style="display:none;">Xóa Ảnh</button></div></div>' +
                '<div class="azsux-field-group"><label>Màu Nền Thẻ (Custom BG)</label><input type="text" name="azsux[items][' + idx + '][item_bg_color]" value="" class="azsux-color-picker" placeholder="#1e293b"></div>' +
                '<div class="azsux-field-group"><label>Màu Gradient Thẻ 1</label><input type="text" name="azsux[items][' + idx + '][item_bg_gradient1]" value="" class="azsux-color-picker" placeholder="#1e293b"></div>' +
                '<div class="azsux-field-group"><label>Màu Gradient Thẻ 2</label><input type="text" name="azsux[items][' + idx + '][item_bg_gradient2]" value="" class="azsux-color-picker" placeholder="#334155"></div>' +
                '<div class="azsux-field-group azsux-full-width"><label>Hệ Thống Nút Bấm</label><div class="azsux-buttons-subrepeater"><div class="azsux-buttons-list">' + getButtonRowTemplate(idx, 0) + '</div><button type="button" class="button azsux-add-btn-btn"><span class="dashicons dashicons-plus"></span> Thêm Nút Bấm</button></div></div>' +
                '</div></div>';
        }
    });
})(jQuery);