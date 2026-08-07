<?php

namespace GME\Elementor;

use GME\Elementor\Controls\MotionControls;

defined('ABSPATH') || exit;

final class Loader
{

    public function __construct()
    {
        $this->register_motion_controls();
        new \GME\Frontend\AnimationRenderer();
    }

    private function register_motion_controls()
    {
        add_action(
            'elementor/element/after_section_end',
            array($this, 'maybe_inject_motion_section'),
            10,
            3
        );
    }

    public function maybe_inject_motion_section($element, $section_id, $args)
    {
        if ('_section_style' !== $section_id) {
            return;
        }

        (new MotionControls())->register($element);
    }
}
