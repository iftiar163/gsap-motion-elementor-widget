<?php

namespace GME\Core;

defined( "ABSPATH" ) || exit;

final class Activator {

    public static function activate() {
        self::set_default_settings();
        self::maybe_flush_rewrite_rules();

        if( false === get_option( 'gme_installed_version' ) ) {
            update_option( 'gme_installed_version', GME_VERSION );
        }

        update_option( 'gme_version', GME_VERSION );
    }

    private static function set_default_settings() {
        if( false === get_option( 'gme_settings', false ) ) {
            $default = [
                'load_gsap_globally' => false,
				'enabled_widgets'    => [],
            ];
            add_option( 'gme_settings', $default );
        }
    }

    private static function maybe_flush_rewrite_rules() {
        flush_rewrite_rules();
    }
}