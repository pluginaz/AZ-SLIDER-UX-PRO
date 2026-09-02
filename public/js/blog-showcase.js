/**
 * Az Slider UX Pro — Blog Showcase Vanilla JS Controller
 */

(function () {
    "use strict";

    function initAZBlogShowcase(container) {
        if (!container || container.dataset.azsuxBlogInitialized === "true") {
            return;
        }
        container.dataset.azsuxBlogInitialized = "true";

        var cards = container.querySelectorAll(".stagger-card, .azsux-blog-card");
        if (!cards || cards.length === 0) {
            return;
        }

        var total = cards.length;
        var activeIndex = parseInt(container.getAttribute("data-active-index") || "0", 10);
        if (activeIndex < 0 || activeIndex >= total) {
            activeIndex = 0;
        }

        var visDesktop = parseInt(container.getAttribute("data-visible-desktop") || "7", 10);
        var visTablet = parseInt(container.getAttribute("data-visible-tablet") || "5", 10);
        var visMobile = parseInt(container.getAttribute("data-visible-mobile") || "3", 10);

        var isLoop = container.getAttribute("data-loop") !== "false";
        var sideClickMode = container.getAttribute("data-side-click") || "activate";
        var activeClickMode = container.getAttribute("data-active-click") || "open_post";
        var enableSwipe = container.getAttribute("data-swipe") !== "false";
        var enableKeyboard = container.getAttribute("data-keyboard") !== "false";
        var isAutoplay = container.getAttribute("data-autoplay") === "true";
        var delay = parseInt(container.getAttribute("data-delay") || "5000", 10);
        var pauseOnHover = container.getAttribute("data-pause-hover") !== "false";

        var prevBtn = container.querySelector(".azsux-blog-nav-prev");
        var nextBtn = container.querySelector(".azsux-blog-nav-next");
        var stage = container.querySelector(".azsux-blog-stage");

        var timer = null;
        var isPaused = false;

        function getVisibleCount() {
            var w = window.innerWidth;
            if (w <= 768) {
                return visMobile;
            } else if (w <= 1024) {
                return visTablet;
            }
            return visDesktop;
        }

        function updatePositions() {
            var half = Math.floor(total / 2);
            var visibleCount = getVisibleCount();
            var maxVisibleHalf = Math.floor(visibleCount / 2);

            cards.forEach(function (card, i) {
                var diff = i - activeIndex;

                if (isLoop && total > 2) {
                    while (diff > half) {
                        diff -= total;
                    }
                    while (diff < -half) {
                        diff += total;
                    }
                }

                var posStr = diff.toString();
                if (Math.abs(diff) > maxVisibleHalf) {
                    posStr = diff > 0 ? "4" : "-4";
                }

                card.setAttribute("data-position", posStr);

                var isActive = (diff === 0);
                card.classList.toggle("is-center", isActive);
                card.classList.toggle("azsux-active", isActive);
                card.setAttribute("aria-selected", isActive ? "true" : "false");
                card.setAttribute("tabindex", isActive ? "0" : "-1");
            });
        }

        function setActiveIndex(newIndex) {
            if (isLoop) {
                activeIndex = (newIndex + total) % total;
            } else {
                activeIndex = Math.max(0, Math.min(total - 1, newIndex));
            }
            updatePositions();
        }

        function next() {
            setActiveIndex(activeIndex + 1);
        }

        function prev() {
            setActiveIndex(activeIndex - 1);
        }

        // Card Click Handler
        cards.forEach(function (card, i) {
            card.addEventListener("click", function (e) {
                var pos = parseInt(card.getAttribute("data-position") || "0", 10);

                if (pos === 0) {
                    // Active Card Clicked
                    if (activeClickMode === "open_post") {
                        var permalink = card.getAttribute("data-permalink");
                        if (permalink && permalink !== "#") {
                            window.location.href = permalink;
                        }
                    }
                } else {
                    // Side Card Clicked
                    if (sideClickMode === "activate") {
                        e.preventDefault();
                        setActiveIndex(i);
                    }
                }
            });

            // Keyboard navigation
            if (enableKeyboard) {
                card.addEventListener("keydown", function (e) {
                    if (e.key === "Enter" || e.key === " ") {
                        var pos = parseInt(card.getAttribute("data-position") || "0", 10);
                        if (pos !== 0) {
                            e.preventDefault();
                            setActiveIndex(i);
                        }
                    } else if (e.key === "ArrowRight") {
                        e.preventDefault();
                        next();
                    } else if (e.key === "ArrowLeft") {
                        e.preventDefault();
                        prev();
                    }
                });
            }
        });

        // Navigation Buttons
        if (prevBtn) {
            prevBtn.addEventListener("click", function (e) {
                e.preventDefault();
                prev();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", function (e) {
                e.preventDefault();
                next();
            });
        }

        // Touch Swipe & Mouse Drag Support
        if (enableSwipe && stage) {
            var startX = 0;
            var startY = 0;
            var isDragging = false;

            stage.addEventListener("touchstart", function (e) {
                if (e.touches && e.touches.length > 0) {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    isDragging = true;
                }
            }, { passive: true });

            stage.addEventListener("touchend", function (e) {
                if (!isDragging) return;
                isDragging = false;

                if (e.changedTouches && e.changedTouches.length > 0) {
                    var deltaX = e.changedTouches[0].clientX - startX;
                    var deltaY = e.changedTouches[0].clientY - startY;

                    // Ensure horizontal swipe is dominant
                    if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                        if (deltaX < 0) {
                            next();
                        } else {
                            prev();
                        }
                    }
                }
            }, { passive: true });
        }

        // Autoplay Loop
        function startAutoplay() {
            if (!isAutoplay || timer) return;
            timer = setInterval(function () {
                if (!isPaused) {
                    next();
                }
            }, delay);
        }

        function stopAutoplay() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        if (isAutoplay) {
            if (pauseOnHover) {
                container.addEventListener("mouseenter", function () { isPaused = true; });
                container.addEventListener("mouseleave", function () { isPaused = false; });
                container.addEventListener("focusin", function () { isPaused = true; });
                container.addEventListener("focusout", function () { isPaused = false; });
            }
            startAutoplay();
        }

        // Window resize listener to recalculate visible counts
        window.addEventListener("resize", function () {
            updatePositions();
        });

        // Initial Position Calculation
        updatePositions();
    }

    // Auto-init all blog showcase instances on DOM ready
    document.addEventListener("DOMContentLoaded", function () {
        var sliders = document.querySelectorAll(".azsux-layout-blog-showcase");
        sliders.forEach(function (slider) {
            initAZBlogShowcase(slider);
        });
    });

    // Global API hook
    window.AZSliderUX = window.AZSliderUX || {};
    window.AZSliderUX.initBlog = initAZBlogShowcase;
})();