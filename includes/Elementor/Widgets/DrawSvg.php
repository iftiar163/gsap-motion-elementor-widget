<?php
namespace GME\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use GME\Core\Assets;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class DrawSvg extends Widget_Base {

	public function get_name() {
		return 'gme-draw-svg';
	}

	public function get_title() {
		return __( 'GSAP Draw SVG', 'gsap-motion-elementor' );
	}

	public function get_icon() {
		return 'eicon-svg';
	}

	public function get_categories() {
		return array( 'gsap-motion-elementor' );
	}

	public function get_keywords() {
		return array( 'svg', 'draw', 'line', 'icon', 'gsap' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'SVG', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'svg_code',
			array(
				'label'       => __( 'SVG Code', 'gsap-motion-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '<svg viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg"><path d="M10 50 Q 100 10 190 50" fill="none" stroke="#000000" stroke-width="4"/></svg>',
				'description' => __( 'Paste raw SVG markup. DrawSVG only animates shapes with a stroke — shapes with only a fill will not visibly draw.', 'gsap-motion-elementor' ),
			)
		);

		$this->add_control(
			'stroke_color',
			array(
				'label'   => __( 'Override Stroke Color', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$this->add_control(
			'stroke_width',
			array(
				'label'   => __( 'Override Stroke Width (px)', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 20,
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
			'duration',
			array(
				'label'   => __( 'Duration (seconds)', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 0.2,
				'max'     => 10,
				'step'    => 0.1,
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => __( 'Easing', 'gsap-motion-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power1.inOut',
				'options' => array(
					'none'         => __( 'None (Linear)', 'gsap-motion-elementor' ),
					'power1.inOut' => __( 'Power1 InOut', 'gsap-motion-elementor' ),
					'power2.inOut' => __( 'Power2 InOut', 'gsap-motion-elementor' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Allowed SVG tags/attributes for wp_kses(). This is intentionally
	 * limited to common shape/structure elements — enough for most
	 * icons and line art, without permitting scripts or event handlers.
	 */
	private function get_allowed_svg_tags() {
		$common_attrs = array(
			'id'    => true,
			'class' => true,
			'style' => true,
			'fill'  => true,
			'stroke' => true,
			'stroke-width' => true,
			'stroke-linecap' => true,
			'stroke-linejoin' => true,
			'transform' => true,
		);

		return array(
			'svg'    => array_merge( $common_attrs, array(
				'viewbox' => true,
				'xmlns'   => true,
				'width'   => true,
				'height'  => true,
			) ),
			'path'   => array_merge( $common_attrs, array( 'd' => true ) ),
			'circle' => array_merge( $common_attrs, array( 'cx' => true, 'cy' => true, 'r' => true ) ),
			'ellipse' => array_merge( $common_attrs, array( 'cx' => true, 'cy' => true, 'rx' => true, 'ry' => true ) ),
			'line'   => array_merge( $common_attrs, array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ) ),
			'polyline' => array_merge( $common_attrs, array( 'points' => true ) ),
			'polygon'  => array_merge( $common_attrs, array( 'points' => true ) ),
			'rect'   => array_merge( $common_attrs, array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ) ),
			'g'      => $common_attrs,
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Assets::mark_animation_used();

		$svg = wp_kses( $settings['svg_code'] ?? '', $this->get_allowed_svg_tags() );

		if ( empty( $svg ) ) {
			echo '<p>' . esc_html__( 'Please enter valid SVG code.', 'gsap-motion-elementor' ) . '</p>';
			return;
		}

		$config = array(
			'trigger'  => $settings['trigger'] ?? 'on_scroll',
			'duration' => $settings['duration'] ?? 2,
			'easing'   => $settings['easing'] ?? 'power1.inOut',
		);

		$style = '';
		if ( ! empty( $settings['stroke_color']['value'] ) ) {
			$style .= '--gme-stroke-color:' . esc_attr( $settings['stroke_color'] ) . ';';
		}
		if ( ! empty( $settings['stroke_width'] ) ) {
			$style .= '--gme-stroke-width:' . esc_attr( $settings['stroke_width'] ) . 'px;';
		}
		?>
		<div
			class="gme-draw-svg"
			data-gme-draw="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
			<?php echo $style ? 'style="' . esc_attr( $style ) . '"' : ''; ?>
		>
			<?php echo $svg; // Already sanitized via wp_kses() above. ?>
		</div>
		<?php
	}
}