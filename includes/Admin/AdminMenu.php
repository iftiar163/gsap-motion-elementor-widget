<?php

namespace GME\Admin;

defined("ABSPATH") || exit;

final class AdminMenu
{

	const MENU_SLUG = 'gme-settings';

	public function __construct()
	{
		add_action('admin_menu', [$this, 'register_pages']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function register_pages()
	{
		add_menu_page(
			__('GSAP Motion', 'gsap-motion-elementor'),
			__('GSAP Motion', 'gsap-motion-elementor'),
			'manage_options',
			self::MENU_SLUG,
			array($this, 'render_dashboard_page'),
			'dashicons-controls-play',
			58
		);

		// Submenu: Dashboard (re-adds itself as first submenu with a clean label).
		add_submenu_page(
			self::MENU_SLUG,
			__('Dashboard', 'gsap-motion-elementor'),
			__('Dashboard', 'gsap-motion-elementor'),
			'manage_options',
			self::MENU_SLUG,
			array($this, 'render_dashboard_page')
		);

		// Submenu: Widgets.
		add_submenu_page(
			self::MENU_SLUG,
			__('Widgets', 'gsap-motion-elementor'),
			__('Widgets', 'gsap-motion-elementor'),
			'manage_options',
			'gme-widgets',
			array($this, 'render_widgets_page')
		);

		// Submenu: Settings.
		add_submenu_page(
			self::MENU_SLUG,
			__('Settings', 'gsap-motion-elementor'),
			__('Settings', 'gsap-motion-elementor'),
			'manage_options',
			'gme-settings',
			array($this, 'render_settings_page')
		);
	}

	/**
	 * Determine if the current admin screen belongs to our plugin.
	 * Used to avoid loading our CSS/JS on unrelated wp-admin pages.
	 *
	 * @param string $hook Current admin page hook suffix.
	 * @return bool
	 */

	public function is_plugin_screen($hook)
	{
		return isset($_GET['page']) && strpos(sanitize_text_field(wp_unslash($_GET['page'])), 'gme-') === 0
			|| (isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === self::MENU_SLUG);
	}

	/**
	 * Enqueue admin CSS/JS — ONLY on our own plugin pages.
	 *
	 * @param string $hook Current admin page hook suffix.
	 */
	public function enqueue_assets($hook)
	{
		if (! $this->is_plugin_screen($hook)) {
			return;
		}

		wp_enqueue_style(
			'gme-admin',
			GME_PLUGIN_URL . 'assets/build/admin.css',
			[],
			GME_VERSION
		);

		wp_enqueue_script(
			'gme-admin',
			GME_PLUGIN_URL . 'assets/build/admin.js',
			[],
			GME_VERSION,
			true
		);
	}

	/**
	 * Render the Dashboard page by including its view template.
	 */
	public function render_dashboard_page()
	{
		$this->render_view('dashboard');
	}

	/**
	 * Render the Widgets page by including its view template.
	 */
	public function render_widgets_page()
	{
		$this->render_view('widgets');
	}

	/**
	 * Render the Settings page by including its view template.
	 */
	public function render_settings_page()
	{
		$this->render_view('settings');
	}

	/**
	 * Include a view template file, keeping PHP logic and HTML separate.
	 *
	 * @param string $view Name of the view file (without .php extension).
	 */

	private function render_view($view)
	{
		$file = GME_PLUGIN_DIR . 'includes/Admin/Views/' . $view . '.php';

		if (file_exists($file)) {
			require $file;
		}
	}
}
