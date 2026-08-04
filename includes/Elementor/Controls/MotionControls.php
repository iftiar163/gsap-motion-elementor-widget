<?php

namespace GME\Elementor\Controls;

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

final class MotionControls
{

    const PREFIX = 'gme_';

    public function register(Controls_Stack $element)
    {

        $element->start_controls_section(
            self::PREFIX . 'motion_section',
            array(
                'label' => __('GSAP Motion', 'gsap-motion-elementor'),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            )
        );

        $element->add_control(
            self::PREFIX . 'animation_enabled',
            array(
                'label'        => __('Enable GSAP Animation', 'gsap-motion-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'gsap-motion-elementor'),
                'label_off'    => __('No', 'gsap-motion-elementor'),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $element->end_controls_section();
    }
}
