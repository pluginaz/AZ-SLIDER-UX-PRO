<?php
/**
 * Uninstall Az Slider UX Pro
 *
 * Fired when the plugin is uninstalled via WordPress Admin.
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Option to clean data on uninstall if enabled in plugin settings.
$azsux_clean_data = get_option( 'azsux_clean_on_uninstall', '0' );

if ( '1' === (string) $azsux_clean_data ) {
	$azsux_sliders = get_posts(
		array(
			'post_type'        => 'az_slider',
			'post_status'      => 'any',
			'numberposts'      => 500,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);

	if ( ! empty( $azsux_sliders ) ) {
		foreach ( $azsux_sliders as $azsux_slider_id ) {
			wp_delete_post( $azsux_slider_id, true );
		}
	}

	delete_option( 'azsux_settings' );
	delete_option( 'azsux_clean_on_uninstall' );
}