<?php

namespace GME\Elementor;

use GME\Elementor\Widgets\Counter;
use GME\Core\Plugin;

defined( 'ABSPATH' ) || exit;

final class WidgetsManager {

    const WIDGETS = array(
		Counter::class,
	);

    public function __construct() {
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
    }

    /**
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 */
	public function register_widgets( $widgets_manager ) {
		foreach ( self::WIDGETS as $widget_class ) {
			if ( ! $this->is_widget_enabled( $widget_class ) ) {
				continue;
			}
			$widgets_manager->register( new $widget_class() );
		}
	}

    /**
	 * Checks saved settings for a list of explicitly disabled widgets.
	 * Any widget not in that list is enabled by default.
	 */
	private function is_widget_enabled( $widget_class ) {
		$disabled = Plugin::instance()->get_setting( 'disabled_widgets', array() );
		return ! in_array( $widget_class, $disabled, true );
	}
}