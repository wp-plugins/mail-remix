<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

/**
 * Class admin
 *
 * @package mail_remix
 */
class admin {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();
	}

	/**
	 * Registers apges within WordPress
	 */
	public function add_pages() {
		$icon = file_get_contents(plugin()->dir . '/client-s/branding/icon.svg');
		$page = add_menu_page(__('Mail Remix', __NAMESPACE__), __('Mail Remix', __NAMESPACE__), 'manage_options', 'mail-remix', array($this, 'main'), 'data:image/svg+xml;base64,' . base64_encode($icon));
		add_submenu_page('mail-remix', __('Mail Remix | Basic Config', __NAMESPACE__), __('Basic Config', __NAMESPACE__), 'manage_options', 'mail-remix', array($this, 'main'));

		$smtp_page      = add_submenu_page('mail-remix', __('Mail Remix | Transport', __NAMESPACE__), __('Transport', __NAMESPACE__), 'manage_options', 'remix-transport', array($this, 'transport'));
		$templates_page = add_submenu_page('mail-remix', __('Mail Remix | Templating', __NAMESPACE__), __('Templating', __NAMESPACE__), 'manage_options', 'remix-templates', array($this, 'templates'));

		// Scripts
		add_action('load-' . $page, array($this, 'init_scripts'));
		add_action('load-' . $smtp_page, array($this, 'init_scripts'));
		add_action('load-' . $templates_page, array($this, 'init_scripts'));

		$opts = plugin()->opts();

		if($opts['logging']) {
			$logging_page = add_submenu_page('mail-remix', __('Mail Remix | Logging', __NAMESPACE__), __('Logging', __NAMESPACE__), 'manage_options', 'remix-logging', array($this, 'logging'));
			add_action('load-' . $logging_page, array($this, 'init_scripts'));
		}
	}

	/**
	 * Sets up the enqueueing of scripts into WordPress
	 */
	public function init_scripts() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	/**
	 * Enqueues client-side scripts into WordPress
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(__NAMESPACE__ . '_colorpicker', plugins_url('', plugin()->file) . '/client-s/colpick/colpick.min.js', array('jquery'));
		wp_enqueue_style(__NAMESPACE__ . '_colorpicker', plugins_url('', plugin()->file) . '/client-s/colpick/colpick.min.css');

		wp_enqueue_script(__NAMESPACE__ . '_admin_js', plugins_url('', plugin()->file) . '/client-s/admin.min.js', array('jquery'));
		wp_enqueue_style(__NAMESPACE__ . '_admin_css', plugins_url('', plugin()->file) . '/client-s/admin.min.css');
	}

	// Page Basic Options
	public function main() {
		admin\main::maybe_save_opts();
		admin\main::do_print();
	}

	// Page Transport
	public function transport() {
		admin\transport::maybe_save_opts();
		admin\transport::do_print();
	}

	// Page Templating
	public function templates() {
		admin\templates::maybe_save_opts();
		admin\templates::do_print();
	}

	// Page Logging
	public function logging() {
		admin\logging::maybe_save_opts();
		admin\logging::do_print();
	}
}