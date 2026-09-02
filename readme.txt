=== Az Slider UX Pro ===
Contributors: dongle
Tags: slider, accordion, showcase, flatsome, builder
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create modern interactive accordion and blog showcase sliders supporting Flatsome UX Builder, shortcodes, Gutenberg, and 3D fan effects.

== Description ==

Az Slider UX Pro is a modern slider and accordion showcase solution for WordPress:
- Dual Layout Engines: Accordion Showcase (Split Content + Accordion Cards) and Blog Showcase (Dynamic WP_Query + 3D Center Fan Cards).
- Hover & Click interaction modes with smooth transitions.
- Native integration with Flatsome UX Builder and Gutenberg Block Editor.
- Works on any WordPress theme via shortcode `[az_slider_ux id="123"]`.
- Customizable colors, gradients, background images, overlays, badges, titles, descriptions, and action buttons.
- Responsive breakpoints for Desktop, Tablet, and Mobile devices.
- Built for Performance, SEO, and Accessibility (keyboard navigation, ARIA attributes, reduced motion).

== Installation ==

1. Upload the `az-slider-ux-pro` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress Admin.
3. Create a new slider under the "Az Slider UX" menu and embed it via shortcode or Flatsome UX Builder element.

== Changelog ==

= 1.0.2 =
* Fixed Flatsome UX Builder live iframe preview, asset loading, and real-time element re-initialization.
* Fixed method signature in AZSUX_Blog_Query (build_query_args, query_posts).
* Resolved WordPress Plugin Check & PHPCS compliance warnings and non-enqueued stylesheets.

= 1.0.1 =
* Added Blog Showcase module with dynamic WP_Query, 3D center fan card layouts, and responsive controls.
* Fixed WordPress media upload compatibility and UTF-8 BOM encoding.
* Enhanced i18n translators comments, placeholder ordering, and security escaping.

= 1.0.0 =
* Initial Release.