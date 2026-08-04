<?php

namespace GME\Core;

if (! defined('ABSPATH')) {
    exit;
}

final class Plugin
{
    private static $instance = null;

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $settings = [];

    private function __construct()
    {

        $this->load_settings();
        $this->load_textdomain();
        $this->init_hooks();
    }

    /**
     * Prevent cloning of the instance (Singleton safety).
     */

    private function __clone()
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Prevent unserializing of the instance (Singleton safety).
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    /**
     * Load plugin translations from the /languages folder.
     */
    private function load_textdomain()
    {
        load_plugin_textdomain(
            'gsap-motion-elementor',
            false,
            dirname(GME_PLUGIN_BASENAME) . '/languages'
        );
    }

    private function load_settings()
    {
        $defaults = [
            'load_gsap_globally' => false,
            'enabled_widgets'    => [],
        ];

        $saved = get_option('gme_settings', []);

        $this->settings = wp_parse_args($saved, $defaults);
    }

    /**
     * Get a single setting value.
     *
     * @param string $key     Setting key.
     * @param mixed  $default Fallback value if not set.
     * @return mixed
     */

    public function get_setting($key, $default = null)
    {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }

    public function get_settings()
    {
        return $this->settings;
    }

    private function init_hooks()
    {

        if (is_admin()) {
            $this->init_admin();
        }

        add_action('elementor/init', [$this, 'init_elementor']);

        $this->init_assets();
    }

    /**
     * Boot the wp-admin UI (Dashboard, Widgets, Settings pages).
     * Placeholder — built in a future step.
     */

    private function init_admin()
    {
        new \GME\Admin\AdminMenu();
    }

    /**
     * Boot Elementor-specific integration (widgets, controls, frontend scripts).
     * Placeholder — built in a future step.
     */

    public function init_elementor()
    {
        new \GME\Elementor\Loader();
    }

    /**
     * Boot asset management (smart conditional CSS/JS loading).
     * Placeholder — built in a future step.
     */
    private function init_assets()
    {
        new \GME\Core\Assets();
    }
}
