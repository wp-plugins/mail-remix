<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

/**
 * Class plugin
 *
 * @package mail_remix
 */
class plugin {

	/**
	 * @vars string
	 */
	public $file, $dir, $tmlt_dir, $log_dir;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->file     = dirname(dirname(__FILE__)) . '/remix.php';
		$this->dir      = dirname(dirname(__FILE__));
		$this->tmlt_dir = $this->dir . '/templates';
		$this->url      = plugins_url('', $this->file);
		$this->log_dir  = WP_CONTENT_DIR . '/mail-logs';

		spl_autoload_register(array($this, 'autoload'));

		add_action('plugins_loaded', array($this, 'build'));
	}

	/**
	 * Build plugin basics
	 */
	public function build() {
		load_plugin_textdomain('mail-remix');

		$opts = $this->opts();

		$this->init();
		if(is_admin()) $this->init_admin();
		if($opts['logging']) new logger;
	}

	/**
	 * Initialize template and SMTP functionality
	 */
	public function init() {
		$templater = new templater();
		new smtp;

		$opts = $this->opts();

		if($opts['enabled'])
			$templater->add_actions();
	}

	/**
	 * Initialize WordPress Dashboard pages
	 */
	public function init_admin() {
		$admin = new admin;
		add_action('admin_menu', array($admin, 'add_pages'));
	}

	protected function autoload($class) {
		if(strpos($class, __NAMESPACE__) !== 0) return; // We only need to autoload classes in our namespace

		$d   = DIRECTORY_SEPARATOR;
		$dir = dirname($this->file) . $d . 'classes' . $d;

		$class_file = $dir . str_replace(array(__NAMESPACE__ . '\\', '\\', '_'), array('', $d, '-'), $class) . '.php';
		is_file($class_file) AND require_once($class_file);
	}

	/**
	 * Plugin options
	 *
	 * @return array
	 */
	public function opts() {
		$defaults = array(
			'enabled'          => FALSE,

			'templating'       => TRUE,
			'template'         => 'clean.html',

			'parse_shortcodes' => FALSE,
			'parse_markdown'   => FALSE,
			'exec_php'         => FALSE,

			'smtp'             => FALSE,
			'smtp_port'        => 25,

			'smtp_auth'        => FALSE,
			'smtp_con_mode'    => 'plaintext',
			'smtp_host'        => '',
			'smtp_user'        => '',
			'smtp_pass'        => '',
			'smtp_from'        => '',
			'smtp_return_path' => '',

			'logging'          => FALSE
		);

		$defaults = apply_filters(__NAMESPACE__ . '_options_defaults', $defaults);

		$opts = get_site_option(__NAMESPACE__ . '_options', FALSE);

		if(!$opts) return apply_filters(__NAMESPACE__ . '_options', $defaults);
		return apply_filters(__NAMESPACE__ . '_options', array_merge($defaults, (array)maybe_unserialize($opts)));
	}

	/**
	 * Returns an array of options set for the site's templates
	 *
	 * @return mixed|void
	 */
	public function template_opts() {
		$defaults = array(
			'foreground-color' => '#FFFFFF',
			'background-color' => '#FFFFFF',
			'banner-color'     => '#FFFFFF',
			'border-color'     => '#E8E8E8',
			'footer-text'      => ''
		);

		$defaults = apply_filters(__NAMESPACE__ . '_template_colors_defaults', $defaults);
		$saved    = get_site_option(__NAMESPACE__ . '_template_colors', FALSE);

		if(!$saved) return apply_filters(__NAMESPACE__ . '_template_colors', $defaults);
		return apply_filters(__NAMESPACE__ . '_template_colors', array_merge($defaults, (array)maybe_unserialize($saved)));
	}
}

/** @var plugin Class Instance */
$GLOBALS[__NAMESPACE__] = new plugin;

/** @return plugin Class Instance */
function plugin() {
	return $GLOBALS[__NAMESPACE__];
}