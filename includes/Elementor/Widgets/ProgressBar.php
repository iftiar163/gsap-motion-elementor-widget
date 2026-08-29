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

final class ProgressBar extends Widget_Base {

	public function get_name() {
		return 'gme-progress-bar';
	}

	public function get_title() {
		return __( 'GSAP Progress Bar', 'gsap-motion-elementor' );
	}

	public function get_icon() {
		return 'eicon-skill-bar';
	}

	public function get_categories() {
		return array( 'gsap-motion-elementor' );
	}

	public function get_keywords() {
		return array( 'progress', 'bar', 'skill', 'percentage', 'gsap' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Progress', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Web Design', 'gsap-motion-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'percentage',
			array(
				'label'   => __( 'Percentage', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 75,
				),
			)
		);

		$this->add_control(
			'show_percentage_label',
			array(
				'label'        => __( 'Show Percentage Number', 'gsap-motion-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'gsap-motion-elementor' ),
				'label_off'    => __( 'No', 'gsap-motion-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'duration',
			array(
				'label'   => __( 'Duration (seconds)', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1.5,
				'min'     => 0.3,
				'max'     => 5,
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
				),
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
					'{{WRAPPER}} .gme-progress-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .gme-progress-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_bar',
			array(
				'label' => __( 'Bar', 'gsap-motion-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'track_color',
			array(
				'label'     => __( 'Track Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e5e7eb',
				'selectors' => array(
					'{{WRAPPER}} .gme-progress-track' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fill_color',
			array(
				'label'     => __( 'Fill Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .gme-progress-fill' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_height',
			array(
				'label'     => __( 'Bar Height (px)', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 4,
						'max' => 60,
					),
				),
				'default'   => array(
					'size' => 12,
				),
				'selectors' => array(
					'{{WRAPPER}} .gme-progress-track' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_radius',
			array(
				'label'      => __( 'Bar Radius', 'gsap-motion-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 20,
					'right'  => 20,
					'bottom' => 20,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .gme-progress-track, {{WRAPPER}} .gme-progress-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Assets::mark_animation_used();

		$percentage = isset( $settings['percentage']['size'] ) ? $settings['percentage']['size'] : 75;
		$duration   = $settings['duration'] ?? 1.5;
		$easing     = $settings['easing'] ?? 'power2.out';
		$show_label = ! empty( $settings['show_percentage_label'] );

		$config = array(
			'percentage' => $percentage,
			'duration'   => $duration,
			'easing'     => $easing,
		);
		?>
		<div class="gme-progress-bar" data-gme-progress="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
			<?php if ( ! empty( $settings['title'] ) || $show_label ) : ?>
				<div class="gme-progress-header">
					<?php if ( ! empty( $settings['title'] ) ) : ?>
						<span class="gme-progress-title"><?php echo esc_html( $settings['title'] ); ?></span>
					<?php endif; ?>
					<?php if ( $show_label ) : ?>
						<span class="gme-progress-percentage">0%</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="gme-progress-track">
				<div class="gme-progress-fill" style="width: 0%;"></div>
			</div>
		</div>
		<?php
	}
}