<?php
namespace GME\Elementor\Controls;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the "GSAP Motion" controls section injected into every
 * Elementor element's Advanced tab.
 */
final class MotionControls {

	const PREFIX = 'gme_';

	/**
	 * Register the "GSAP Motion" section and its controls onto the
	 * given element (widget, section, column, or container).
	 *
	 * @param Controls_Stack $element The element currently being built.
	 */
	public function register( Controls_Stack $element ) {

		$element->start_controls_section(
			self::PREFIX . 'motion_section',
			array(
				'label' => __( 'GSAP Motion', 'gsap-motion-elementor' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_enabled',
			array(
				'label'        => __( 'Enable GSAP Animation', 'gsap-motion-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'gsap-motion-elementor' ),
				'label_off'    => __( 'No', 'gsap-motion-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_type',
			array(
				'label'     => __( 'Animation Type', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'      => __( 'Fade In', 'gsap-motion-elementor' ),
					'slide_up'  => __( 'Slide Up', 'gsap-motion-elementor' ),
					'slide_down' => __( 'Slide Down', 'gsap-motion-elementor' ),
					'slide_left' => __( 'Slide Left', 'gsap-motion-elementor' ),
					'slide_right' => __( 'Slide Right', 'gsap-motion-elementor' ),
					'scale_in'  => __( 'Scale In', 'gsap-motion-elementor' ),
					'rotate_in' => __( 'Rotate In', 'gsap-motion-elementor' ),
				),
				'condition' => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_trigger',
			array(
				'label'     => __( 'Trigger', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'on_scroll',
				'options'   => array(
					'on_load'   => __( 'On Page Load', 'gsap-motion-elementor' ),
					'on_scroll' => __( 'On Scroll Into View', 'gsap-motion-elementor' ),
				),
				'condition' => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_duration',
			array(
				'label'     => __( 'Duration (seconds)', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 0.1,
				'max'       => 5,
				'step'      => 0.1,
				'condition' => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_delay',
			array(
				'label'     => __( 'Delay (seconds)', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'max'       => 5,
				'step'      => 0.1,
				'condition' => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_easing',
			array(
				'label'     => __( 'Easing', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'power2.out',
				'options'   => array(
					'none'        => __( 'None (Linear)', 'gsap-motion-elementor' ),
					'power1.out'  => __( 'Power1 Out', 'gsap-motion-elementor' ),
					'power2.out'  => __( 'Power2 Out', 'gsap-motion-elementor' ),
					'power3.out'  => __( 'Power3 Out', 'gsap-motion-elementor' ),
					'back.out'    => __( 'Back Out', 'gsap-motion-elementor' ),
					'elastic.out' => __( 'Elastic Out', 'gsap-motion-elementor' ),
					'bounce.out'  => __( 'Bounce Out', 'gsap-motion-elementor' ),
				),
				'condition' => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_repeat',
			array(
				'label'       => __( 'Repeat', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => -1,
				'description' => __( 'Number of times to repeat after the first play. Use -1 to repeat infinitely. Leave 0 to play once.', 'gsap-motion-elementor' ),
				'condition'   => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'animation_yoyo',
			array(
				'label'        => __( 'Yoyo (Bounce Back)', 'gsap-motion-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'gsap-motion-elementor' ),
				'label_off'    => __( 'No', 'gsap-motion-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Only has a visible effect when Repeat is set above 0 or -1.', 'gsap-motion-elementor' ),
				'condition'    => array(
					self::PREFIX . 'animation_enabled' => 'yes',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'scroll_behavior',
			array(
				'label'     => __( 'Scroll Behavior', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'play_once',
				'options'   => array(
					'play_once' => __( 'Play Once', 'gsap-motion-elementor' ),
					'scrub'     => __( 'Scrub With Scroll', 'gsap-motion-elementor' ),
				),
				'condition' => array(
					self::PREFIX . 'animation_enabled' => 'yes',
					self::PREFIX . 'animation_trigger' => 'on_scroll',
				),
				'render_type'  => 'template',
			)
		);

		$element->add_control(
			self::PREFIX . 'scrub_amount',
			array(
				'label'       => __( 'Scrub Smoothness', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'min'         => 0,
				'max'         => 3,
				'step'        => 0.1,
				'description' => __( '0 = tightly tied to scroll position. Higher values add a smoothing delay.', 'gsap-motion-elementor' ),
				'condition'   => array(
					self::PREFIX . 'animation_enabled' => 'yes',
					self::PREFIX . 'animation_trigger' => 'on_scroll',
					self::PREFIX . 'scroll_behavior'   => 'scrub',
				),
				'render_type'  => 'template',
			)
		);

		// Stagger only makes sense on elements that actually contain
		// children — Section, Column, and Container types.
		if ( in_array( $element->get_name(), array( 'common-base', 'container' ), true ) ) {

			$element->add_control(
				self::PREFIX . 'stagger_children',
				array(
					'label'        => __( 'Stagger Children', 'gsap-motion-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'gsap-motion-elementor' ),
					'label_off'    => __( 'No', 'gsap-motion-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Animate each direct child one after another instead of animating this element as a whole.', 'gsap-motion-elementor' ),
					'condition'    => array(
						self::PREFIX . 'animation_enabled' => 'yes',
					),
					'render_type'  => 'template',
				)
			);

			$element->add_control(
				self::PREFIX . 'stagger_amount',
				array(
					'label'     => __( 'Stagger Delay (seconds)', 'gsap-motion-elementor' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 0.15,
					'min'       => 0,
					'max'       => 1,
					'step'      => 0.05,
					'condition' => array(
						self::PREFIX . 'animation_enabled' => 'yes',
						self::PREFIX . 'stagger_children'  => 'yes',
					),
					'render_type'  => 'template',
				)
			);
		}

		$element->end_controls_section();
	}
}