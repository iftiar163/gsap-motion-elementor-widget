<?php

namespace GME\Frontend;

use Elementor\Element_Base;
use GME\Core\Assets;

defined("ABSPATH") || exit;

final class AnimationRenderer {

    public function __construct() {
        add_action('elementor/frontend/before_render', [$this, 'maybe_render_animation_attributes']);
    }

    /**
	 * @param Element_Base $element The element about to render.
	 */

    public function maybe_render_animation_attributes( Element_Base $element ) {

        $settings = $element->get_settings_for_display();

        if( empty( $settings['gme_animation_enabled'] ) || 'yes' !== $settings['gme_animation_enabled'] ) {
            return;
        }

        // if( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
        //     Assets::mark_animation_used();
        // }

        Assets::mark_animation_used();

        $animation_settings = [
           'type'            => $settings['gme_animation_type'] ?? 'fade',
			'trigger'         => $settings['gme_animation_trigger'] ?? 'on_scroll',
			'duration'        => $settings['gme_animation_duration'] ?? 1,
			'delay'           => $settings['gme_animation_delay'] ?? 0,
			'easing'          => $settings['gme_animation_easing'] ?? 'power2.out',
			'repeat'          => $settings['gme_animation_repeat'] ?? 0,
			'yoyo'            => $settings['gme_animation_yoyo'] ?? '',
			'scroll_behavior' => $settings['gme_scroll_behavior'] ?? 'play_once',
			'scrub_amount'    => $settings['gme_scrub_amount'] ?? 1,
			'stagger_children' => $settings['gme_stagger_children'] ?? '',
			'stagger_amount'  => $settings['gme_stagger_amount'] ?? 0.15,
        ];

        $element->add_render_attribute(
            '_wrapper',
            [
                'class'              => 'gme-animate',
				'data-gme-animation' => wp_json_encode( $animation_settings ),
            ]
        );
    }
}