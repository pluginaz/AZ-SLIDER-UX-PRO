<?php
/**
 * Loader class for hooks and filters
 *
 * @package Az_Slider_UX_Pro
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

class AZSUX_Loader {

    /**
     * Array of actions
     *
     * @var array
     */
    protected $actions = array();

    /**
     * Array of filters
     *
     * @var array
     */
    protected $filters = array();

    /**
     * Add action hook
     */
    public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
    }

    /**
     * Add filter hook
     */
    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
    }

    /**
     * Utility add hook
     */
    private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
        $hooks[] = array(
            "hook"          => $hook,
            "component"     => $component,
            "callback"      => $callback,
            "priority"      => $priority,
            "accepted_args" => $accepted_args,
        );
        return $hooks;
    }

    /**
     * Run all registered actions and filters
     */
    public function run() {
        foreach ( $this->filters as $hook ) {
            add_filter( $hook["hook"], array( $hook["component"], $hook["callback"] ), $hook["priority"], $hook["accepted_args"] );
        }

        foreach ( $this->actions as $hook ) {
            add_action( $hook["hook"], array( $hook["component"], $hook["callback"] ), $hook["priority"], $hook["accepted_args"] );
        }
    }
}

