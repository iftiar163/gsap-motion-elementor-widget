<?php

namespace GME\Core;

defined( "ABSPATH" ) || exit;

final class Deactivator {

    public static function deactivate() {
        self::maybe_flush_rewrite_rules();
    }

    private static function maybe_flush_rewrite_rules() {
        flush_rewrite_rules();
    }
}