=== Az Slider UX Pro ===
Contributors: dongle
Donate link: https://pluginaz.com
Tags: slider, accordion, showcase, flatsome, builder
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create modern interactive accordion and blog showcase sliders supporting Flatsome UX Builder, shortcodes, Gutenberg, and 3D fan effects.

== Description ==

Az Slider UX Pro is a modern, responsive slider and showcase solution built for WordPress and Flatsome UX Builder.

### Key Features:
* **Dual Layout Engines**:
  * **Accordion Showcase**: Split-content layout with interactive vertical & horizontal accordion cards.
  * **Blog Showcase**: Dynamic post query with interactive 3D center fan cards and smooth transitions.
* **Full Flatsome UX Builder Integration**: Real-time live iframe preview, drag-and-drop elements, and instant visual editing.
* **Gutenberg Block & Shortcode Support**: Works seamlessly with any theme via shortcode `[az_slider_ux id="123"]` or Gutenberg block.
* **Performance & SEO Optimized**: Lightweight vanilla JavaScript with zero jQuery dependency on frontend, semantic HTML5, and full accessibility support.
* **Highly Customizable**: Colors, gradients, background overlays, badges, typography, buttons, and mobile breakpoints.

== Installation ==

1. Upload the `az-slider-ux-pro` folder to the `/wp-content/plugins/` directory, or install the ZIP file via **Plugins > Add New > Upload Plugin**.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Go to **Az Slider UX > Thêm Slider Mới** to create your first slider.
4. Insert into any page using the Flatsome UX Builder element **Az Slider UX Pro**, Gutenberg Block, or shortcode `[az_slider_ux id="YOUR_SLIDER_ID"]`.

== Frequently Asked Questions ==

= Does Az Slider UX Pro work with themes other than Flatsome? =
Yes! While it features native integration with Flatsome UX Builder, it functions on any WordPress theme using the shortcode `[az_slider_ux id="123"]` or the Gutenberg block.

= Can I display dynamic blog posts? =
Yes! In the slider settings, switch the layout to **Blog Showcase** to automatically query recent posts by category, tag, or custom selection.

= Does it require jQuery on the frontend? =
No. The frontend engine runs on modern, lightweight Vanilla JavaScript for maximum speed and performance.

== Screenshots ==

1. Slider Accordion Showcase layout with interactive hover/click items.
2. Blog Showcase layout with 3D fan effect.
3. Flatsome UX Builder visual live preview integration.
4. Admin Slider Editor with live preview and preset templates.

== Changelog ==

= 1.0.2 =
* Fixed Flatsome UX Builder live iframe preview, asset loading, and real-time element re-initialization.
* Fixed method signature in AZSUX_Blog_Query (build_query_args, query_posts).
* Resolved WordPress Plugin Check & PHPCS compliance warnings and non-enqueued stylesheets.
* Standardized line endings and uninstall cleanup routine.

= 1.0.1 =
* Added Blog Showcase module with dynamic WP_Query, 3D center fan card layouts, and responsive controls.
* Fixed WordPress media upload compatibility and UTF-8 BOM encoding.
* Enhanced i18n translators comments, placeholder ordering, and security escaping.

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 1.0.2 =
Version 1.0.2 includes full Flatsome UX Builder live preview compatibility, bug fixes, and WordPress.org standards compliance.