<?php

namespace GME\Core;

defined('ABSPATH') || exit;

final class Assets
{

    private static $has_animation = false;

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_assets']);
        add_action('wp_footer', [$this, 'maybe_enqueue_frontend_assets'], 5);
        add_action('elementor/editor/after_enqueue_scripts', array($this, 'enqueue_editor_assets'));
    }

    public static function mark_animation_used()
    {
        self::$has_animation = true;
    }

    public static function register_frontend_assets()
    {

        wp_register_style(
            'gme-frontend',
            GME_PLUGIN_URL . 'assets/build/frontend.css',
            [],
            GME_VERSION
        );

        wp_register_script(
            'gme-frontend',
            GME_PLUGIN_URL . 'assets/build/frontend.js',
            [],
            GME_VERSION,
            true
        );
    }

    public function maybe_enqueue_frontend_assets()
    {

        $force_global = Plugin::instance()->get_setting('load_gsap_globally', false);

        if (! $force_global && ! self::$has_animation) {
            return;
        }

        wp_enqueue_style('gme-frontend');
        wp_enqueue_script('gme-frontend');
    }

    public function enqueue_editor_assets()
    {
        // wp_enqueue_script/style calls will go here in a future step.
    }
}
