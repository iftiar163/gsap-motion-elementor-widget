<?php

namespace GME\Elementor;

use GME\Elementor\Controls\MotionControls;

defined('ABSPATH') || exit;

final class Loader {

	public function __construct() {
		$this->register_motion_controls();
		$this->register_widget_category();
		new \GME\Frontend\AnimationRenderer();
		new WidgetsManager();
	}

	private function register_motion_controls() {

		add_action(
			'elementor/element/after_section_end',
			array( $this, 'maybe_inject_motion_section' ),
			10,
			3
		);

		add_action(
			'elementor/element/container/section_layout/after_section_end',
			array( $this, 'inject_motion_section' ),
			10,
			2
		);
	}

	private function register_widget_category() {
		add_action(
			'elementor/elements/categories_registered',
			function ( $elements_manager ) {
				$elements_manager->add_category(
					'gsap-motion-elementor',
					array(
						'title' => __( 'GSAP Motion', 'gsap-motion-elementor' ),
						'icon'  => 'eicon-animation',
					)
				);
			}
		);
	}


	public function maybe_inject_motion_section( $element, $section_id, $args ) {
		if ( '_section_style' !== $section_id ) {
			return;
		}
		$this->inject_motion_section( $element, $args );
	}

	public function inject_motion_section( $element, $args ) {
		( new MotionControls() )->register( $element );
	}
}