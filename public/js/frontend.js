/**
 * Az Slider UX Pro Frontend Vanilla JavaScript Engine
 */

(function () {
    "use strict";

    function initAZSlider(container) {
        if (!container) {
            return;
        }

        // Avoid re-binding if already initialized on same DOM items
        if (container.dataset.azsuxInitialized === "true") {
            return;
        }
        container.dataset.azsuxInitialized = "true";

        var interaction = container.getAttribute("data-interaction") || "hover-click";
        var autoplay = container.getAttribute("data-autoplay") === "true";
        var delay = parseInt(container.getAttribute("data-delay") || "4000", 10);
        var pauseOnHover = container.getAttribute("data-pause-hover") === "true";
        var items = container.querySelectorAll(".azsux-accordion-item");
        var contentBox = container.querySelector(".azsux-content-box");

        if (!items || items.length === 0) {
            return;
        }

        var activeIndex = parseInt(container.getAttribute("data-active-index") || "0", 10);
        if (isNaN(activeIndex) || activeIndex < 0 || activeIndex >= items.length) {
            activeIndex = 0;
        }
        var timer = null;
        var isPaused = false;

        function setActiveItem(index) {
            if (index < 0 || index >= items.length) {
                return;
            }
            activeIndex = index;

            items.forEach(function (item, idx) {
                var isActive = idx === activeIndex;
                item.classList.toggle("azsux-active", isActive);
                item.setAttribute("aria-selected", isActive ? "true" : "false");
                item.setAttribute("tabindex", isActive ? "0" : "-1");
            });

            // Update Content Box from JSON payload of active item
            var activeCard = items[activeIndex];
            if (activeCard && contentBox) {
                var rawPayload = activeCard.getAttribute("data-payload");
                if (rawPayload) {
                    try {
                        var payload = JSON.parse(rawPayload);
                        updateContentBox(payload);
                    } catch (e) {
                        console.error("AZ Slider UX: Invalid payload JSON", e);
                    }
                }
            }
        }

        function updateContentBox(data) {
            if (!contentBox) return;

            var badgeWrapEl = contentBox.querySelector(".azsux-badge-wrap");
            var badgeEl = contentBox.querySelector(".azsux-badge");
            var titleEl = contentBox.querySelector(".azsux-title");
            var descEl = contentBox.querySelector(".azsux-description");
            var actionsEl = contentBox.querySelector(".azsux-actions");

            if (badgeEl) {
                badgeEl.textContent = data.badge || "";
                if (badgeWrapEl) {
                    badgeWrapEl.style.display = data.badge ? "block" : "none";
                }
            }

            if (titleEl) {
                titleEl.textContent = data.title || "";
            }

            if (descEl) {
                descEl.textContent = data.description || "";
            }

            if (actionsEl && Array.isArray(data.buttons)) {
                actionsEl.innerHTML = buildButtonsHTML(data.buttons);
            }
        }

        function buildButtonsHTML(buttons) {
            if (!buttons || buttons.length === 0) {
                return "";
            }

            var html = '<div class="azsux-buttons-wrapper">';
            buttons.forEach(function (btn) {
                if (!btn.text) return;
                var style = btn.style || "primary";
                var target = btn.target === "_blank" ? '_blank" rel="noopener noreferrer' : "_self";
                var badgeHtml = btn.badge ? '<span class="azsux-btn-badge">' + escapeHTML(btn.badge) + "</span>" : "";

                var customStyle = "";
                if (btn.bg_color) customStyle += "background-color: " + escapeHTML(btn.bg_color) + "; border-color: " + escapeHTML(btn.bg_color) + "; ";
                if (btn.text_color) customStyle += "color: " + escapeHTML(btn.text_color) + "; ";

                html += '<a href="' + escapeHTML(btn.url || "#") + '" target="' + target + '" class="azsux-btn azsux-btn-' + escapeHTML(style) + '" style="' + customStyle + '">';
                html += '<span class="azsux-btn-text">' + escapeHTML(btn.text) + "</span>";
                html += badgeHtml;
                html += "</a>";
            });
            html += "</div>";
            return html;
        }

        function escapeHTML(str) {
            if (typeof str !== "string") return "";
            return str.replace(/[&<>"']/g, function (m) {
                return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;" }[m];
            });
        }

        // Event Listeners for Accordion Items
        items.forEach(function (item, idx) {
            // Click Handler
            if (interaction === "click" || interaction === "hover-click") {
                item.addEventListener("click", function (e) {
                    // Do not block button clicks inside active card
                    if (e.target.closest("a, button, input, select")) {
                        return;
                    }
                    setActiveItem(idx);
                });
            }

            // Hover Handler
            if (interaction === "hover" || interaction === "hover-click") {
                item.addEventListener("mouseenter", function () {
                    setActiveItem(idx);
                });
            }

            // Keyboard Accessibility Handler
            item.addEventListener("keydown", function (e) {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    setActiveItem(idx);
                } else if (e.key === "ArrowRight" || e.key === "ArrowDown") {
                    e.preventDefault();
                    var next = (idx + 1) % items.length;
                    setActiveItem(next);
                    items[next].focus();
                } else if (e.key === "ArrowLeft" || e.key === "ArrowUp") {
                    e.preventDefault();
                    var prev = (idx - 1 + items.length) % items.length;
                    setActiveItem(prev);
                    items[prev].focus();
                } else if (e.key === "Home") {
                    e.preventDefault();
                    setActiveItem(0);
                    items[0].focus();
                } else if (e.key === "End") {
                    e.preventDefault();
                    setActiveItem(items.length - 1);
                    items[items.length - 1].focus();
                }
            });
        });

        // Autoplay Loop Logic
        function startAutoplay() {
            if (!autoplay || timer) return;
            timer = setInterval(function () {
                if (!isPaused) {
                    var nextIndex = (activeIndex + 1) % items.length;
                    setActiveItem(nextIndex);
                }
            }, delay);
        }

        function stopAutoplay() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        if (autoplay) {
            if (pauseOnHover) {
                container.addEventListener("mouseenter", function () { isPaused = true; });
                container.addEventListener("mouseleave", function () { isPaused = false; });
                container.addEventListener("focusin", function () { isPaused = true; });
                container.addEventListener("focusout", function () { isPaused = false; });
            }
            startAutoplay();
        }

        // Initial Active Item Setup
        setActiveItem(activeIndex);
    }

    // Global API object for dynamically inserted sliders (e.g., UX Builder / AJAX)
    window.AZSliderUX = window.AZSliderUX || {};
    window.AZSliderUX.init = initAZSlider;
    window.AZSliderUX.initAll = function () {
        var sliders = document.querySelectorAll(".azsux-slider-wrap:not(.azsux-layout-blog-showcase)");
        sliders.forEach(function (slider) {
            initAZSlider(slider);
        });
    };

    // Auto-init all sliders on DOM ready / load
    if (document.readyState === "complete" || document.readyState === "interactive") {
        window.AZSliderUX.initAll();
    } else {
        document.addEventListener("DOMContentLoaded", function () {
            window.AZSliderUX.initAll();
        });
    }

    window.addEventListener("load", function () {
        window.AZSliderUX.initAll();
    });

    // Support jQuery / Flatsome UX Builder events
    if (typeof jQuery !== "undefined") {
        jQuery(document).on("flatsome-slider-init uxb_init ux_builder_init ajaxComplete", function () {
            window.AZSliderUX.initAll();
        });
    }

    // DOM Mutation Observer for live builder environments (Flatsome UX Builder iframe)
    if (typeof MutationObserver !== "undefined") {
        var observer = new MutationObserver(function (mutations) {
            var shouldInit = false;
            for (var i = 0; i < mutations.length; i++) {
                var m = mutations[i];
                if (m.addedNodes && m.addedNodes.length > 0) {
                    for (var j = 0; j < m.addedNodes.length; j++) {
                        var node = m.addedNodes[j];
                        if (node.nodeType === 1) {
                            if (node.classList && node.classList.contains("azsux-slider-wrap") && !node.classList.contains("azsux-layout-blog-showcase")) {
                                shouldInit = true;
                                break;
                            }
                            if (node.querySelector && node.querySelector(".azsux-slider-wrap:not(.azsux-layout-blog-showcase)")) {
                                shouldInit = true;
                                break;
                            }
                        }
                    }
                }
                if (shouldInit) break;
            }
            if (shouldInit) {
                window.AZSliderUX.initAll();
            }
        });

        if (document.body) {
            observer.observe(document.body, { childList: true, subtree: true });
        } else {
            document.addEventListener("DOMContentLoaded", function () {
                if (document.body) {
                    observer.observe(document.body, { childList: true, subtree: true });
                }
            });
        }
    }
})();
