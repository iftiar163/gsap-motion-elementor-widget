<?php
namespace GME\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use GME\Core\Assets;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SplitText extends Widget_Base {

	public function get_name() {
		return 'gme-split-text';
	}

	public function get_title() {
		return __( 'GSAP Split Text', 'gsap-motion-elementor' );
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_categories() {
		return array( 'gsap-motion-elementor' );
	}

	public function get_keywords() {
		return array( 'text', 'split', 'reveal', 'gsap' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Text', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Animate this text with GSAP', 'gsap-motion-elementor' ),
				'placeholder' => __( 'Enter plain text (no HTML formatting)', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => __( 'HTML Tag', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'p'    => 'p',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_animation',
			array(
				'label' => __( 'Animation', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'split_by',
			array(
				'label'   => __( 'Split By', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'chars',
				'options' => array(
					'chars' => __( 'Characters', 'gsap-motion-elementor' ),
					'words' => __( 'Words', 'gsap-motion-elementor' ),
					'lines' => __( 'Lines', 'gsap-motion-elementor' ),
				),
			)
		);

		$this->add_control(
			'animation_type',
			array(
				'label'   => __( 'Animation Type', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide_up',
				'options' => array(
					'fade'      => __( 'Fade In', 'gsap-motion-elementor' ),
					'slide_up'  => __( 'Slide Up', 'gsap-motion-elementor' ),
					'scale_in'  => __( 'Scale In', 'gsap-motion-elementor' ),
					'rotate_in' => __( 'Rotate In', 'gsap-motion-elementor' ),
				),
			)
		);

		$this->add_control(
			'trigger',
			array(
				'label'   => __( 'Trigger', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'on_scroll',
				'options' => array(
					'on_load'   => __( 'On Page Load', 'gsap-motion-elementor' ),
					'on_scroll' => __( 'On Scroll Into View', 'gsap-motion-elementor' ),
				),
			)
		);

		$this->add_control(
			'stagger',
			array(
				'label'   => __( 'Stagger (seconds)', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.03,
				'min'     => 0,
				'max'     => 0.3,
				'step'    => 0.01,
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'   => __( 'Duration (seconds)', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min'     => 0.1,
				'max'     => 3,
				'step'    => 0.1,
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => __( 'Easing', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power2.out',
				'options' => array(
					'none'        => __( 'None (Linear)', 'gsap-motion-elementor' ),
					'power1.out'  => __( 'Power1 Out', 'gsap-motion-elementor' ),
					'power2.out'  => __( 'Power2 Out', 'gsap-motion-elementor' ),
					'back.out'    => __( 'Back Out', 'gsap-motion-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style', 'gsap-motion-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gme-split-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .gme-split-text',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Assets::mark_animation_used();

		$tag = ! empty( $settings['html_tag'] ) ? $settings['html_tag'] : 'h2';

		$config = array(
			'split_by'  => $settings['split_by'] ?? 'chars',
			'type'      => $settings['animation_type'] ?? 'slide_up',
			'trigger'   => $settings['trigger'] ?? 'on_scroll',
			'stagger'   => $settings['stagger'] ?? 0.03,
			'duration'  => $settings['duration'] ?? 0.6,
			'easing'    => $settings['easing'] ?? 'power2.out',
		);

		printf(
			'<%1$s class="gme-split-text" data-gme-split="%2$s">%3$s</%1$s>',
			esc_attr( $tag ),
			esc_attr( wp_json_encode( $config ) ),
			esc_html( $settings['text'] ?? '' )
		);
	}
}