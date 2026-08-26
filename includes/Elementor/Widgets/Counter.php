<?php

namespace GME\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use GME\Core\Assets;

defined( 'ABSPATH' ) || exit;

final class Counter extends Widget_Base {

    public function get_name() {
        return 'gme-counter';
    }

    public function get_title() {
        return __( 'GSAP Counter', 'gsap-motion-elementor' );
    }

    public function get_icon() {
        return 'eicon-counter';
    }

    public function get_categories() {
        return [ 'gsap-motion-elementor' ];
    }

    public function get_keywords() {
        return [ 'counter', 'gsap', 'motion', 'animation' ];
    }

    protected function register_controls() {
        
        $this->start_controls_section(
            'section_counter_content',
            [
                'label' => __( 'Counter', 'gsap-motion-elementor' ),
            ]
        );

        $this->add_control(
            'starting_number',
            [
                'label'   => __( 'Starting Number', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
            ]
        );

        $this->add_control(
            'ending_number',
            [
                'label'   => __( 'Ending Number', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 100,
            ]
        );

        $this->add_control(
			'duration',
			array(
				'label'   => __( 'Duration (seconds)', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 0.5,
				'max'     => 10,
				'step'    => 0.5,
			)
		);

        $this->add_control(
			'thousands_separator',
			array(
				'label'        => __( 'Thousands Separator', 'gsap-motion-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'gsap-motion-elementor' ),
				'label_off'    => __( 'No', 'gsap-motion-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

        $this->add_control(
			'prefix',
			array(
				'label'       => __( 'Prefix', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'e.g. $', 'gsap-motion-elementor' ),
			)
		);

        $this->add_control(
			'suffix',
			array(
				'label'       => __( 'Suffix', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'e.g. +', 'gsap-motion-elementor' ),
			)
		);

        $this->add_control(
			'title_text',
			array(
				'label'       => __( 'Title', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Happy Clients', 'gsap-motion-elementor' ),
				'label_block' => true,
			)
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style_number',
			array(
				'label' => __( 'Number', 'gsap-motion-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

        $this->add_control(
			'number_color',
			array(
				'label'     => __( 'Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gme-counter-number-wrap' => 'color: {{VALUE}};',
				),
			)
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .gme-counter-number-wrap',
			)
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'gsap-motion-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

        $this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gme-counter-title' => 'color: {{VALUE}};',
				),
			)
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .gme-counter-title',
			)
		);

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        Assets::mark_animation_used();

        $start     = isset( $settings['starting_number'] ) ? $settings['starting_number'] : 0;
		$end       = isset( $settings['ending_number'] ) ? $settings['ending_number'] : 100;
		$duration  = isset( $settings['duration'] ) ? $settings['duration'] : 2;
		$separator = ! empty( $settings['thousands_separator'] ) ? $settings['thousands_separator'] : '';

        ?>
            <div class="gme-counter">
                <div class="gme-counter-number-wrap">
                    <?php if ( ! empty( $settings['prefix'] ) ) : ?>
                        <span class="gme-counter-prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
                    <?php endif; ?>
                    <span
                        class="gme-counter-number"
                        data-start="<?php echo esc_attr( $start ); ?>"
                        data-end="<?php echo esc_attr( $end ); ?>"
                        data-duration="<?php echo esc_attr( $duration ); ?>"
                        data-separator="<?php echo esc_attr( $separator ); ?>"
                    ><?php echo esc_html( $start ); ?></span>
                    <?php if ( ! empty( $settings['suffix'] ) ) : ?>
                        <span class="gme-counter-suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( ! empty( $settings['title_text'] ) ) : ?>
                    <div class="gme-counter-title"><?php echo esc_html( $settings['title_text'] ); ?></div>
                <?php endif; ?>
		</div>
        <?php
            
    }
}