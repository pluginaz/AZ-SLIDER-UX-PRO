<?php
/**
 * Main Core Plugin Class
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Plugin {

    /**
     * Singleton instance
     *
     * @var AZSUX_Plugin
     */
    private static $instance = null;

    /**
     * Loader instance
     *
     * @var AZSUX_Loader
     */
    protected $loader;

    /**
     * Get instance
     *
     * @return AZSUX_Plugin
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->loader = new AZSUX_Loader();
        $this->load_dependencies();
        $this->define_hooks();
    }

    /**
     * Initialize dependencies
     */
    private function load_dependencies() {
        AZSUX_Helpers::init_webp_support();
        AZSUX_Post_Type::init();
        AZSUX_Meta::init();
        AZSUX_Shortcode::init();
        AZSUX_Assets::init();
        AZSUX_Import_Export::init();
        AZSUX_REST::init();

        AZSUX_Admin::init();
        AZSUX_Editor::init();
        AZSUX_Settings::init();

        AZSUX_Frontend::init();
        AZSUX_Flatsome::init();
        AZSUX_Gutenberg_Block::init();
    }

    /**
     * Define core hooks
     */
    private function define_hooks() {
        $this->loader->add_action( "plugins_loaded", $this, "load_textdomain" );
    }

    /**
     * Load textdomain
     */
    public function load_textdomain() {
        // phpcs:ignore PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound
        load_plugin_textdomain(
            "az-slider-ux-pro",
            false,
            dirname( AZSUX_BASENAME ) . "/languages"
        );
    }

    /**
     * Run loader
     */
    public function run() {
        $this->loader->run();
    }
}