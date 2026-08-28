<?php
namespace GME\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use GME\Core\Assets;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FlipToggle extends Widget_Base {

	public function get_name() {
		return 'gme-flip-toggle';
	}

	public function get_title() {
		return __( 'GSAP Flip Toggle', 'gsap-motion-elementor' );
	}

	public function get_icon() {
		return 'eicon-toggle';
	}

	public function get_categories() {
		return array( 'gsap-motion-elementor' );
	}

	public function get_keywords() {
		return array( 'toggle', 'accordion', 'flip', 'expand', 'gsap' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'header_text',
			array(
				'label'   => __( 'Header Text', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click to expand', 'gsap-motion-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( '<p>This content smoothly expands and collapses using GSAP Flip.</p>', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'start_open',
			array(
				'label'        => __( 'Start Expanded', 'gsap-motion-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'gsap-motion-elementor' ),
				'label_off'    => __( 'No', 'gsap-motion-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
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
				'default' => 0.4,
				'min'     => 0.1,
				'max'     => 2,
				'step'    => 0.1,
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => __( 'Easing', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power2.inOut',
				'options' => array(
					'none'         => __( 'None (Linear)', 'gsap-motion-elementor' ),
					'power1.inOut' => __( 'Power1 InOut', 'gsap-motion-elementor' ),
					'power2.inOut' => __( 'Power2 InOut', 'gsap-motion-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_header',
			array(
				'label' => __( 'Header', 'gsap-motion-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_bg_color',
			array(
				'label'     => __( 'Background Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f3f4f6',
				'selectors' => array(
					'{{WRAPPER}} .gme-flip-toggle-header' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'header_text_color',
			array(
				'label'     => __( 'Text Color', 'gsap-motion-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gme-flip-toggle-header' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Assets::mark_animation_used();

		$start_open = ! empty( $settings['start_open'] ) ? 'yes' === $settings['start_open'] : false;

		$config = array(
			'duration' => $settings['duration'] ?? 0.4,
			'easing'   => $settings['easing'] ?? 'power2.inOut',
		);
		?>
		<div class="gme-flip-toggle<?php echo $start_open ? ' is-open' : ''; ?>" data-gme-flip="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
			<button type="button" class="gme-flip-toggle-header">
				<?php echo esc_html( $settings['header_text'] ?? '' ); ?>
				<span class="gme-flip-toggle-icon" aria-hidden="true">+</span>
			</button>
			<div class="gme-flip-toggle-content-wrap">
				<div class="gme-flip-toggle-content">
					<?php echo wp_kses_post( $settings['content'] ?? '' ); ?>
				</div>
			</div>
		</div>
		<?php
	}
}