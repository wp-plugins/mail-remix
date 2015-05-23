<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

/**
 * Class utils
 *
 * @package mail_remix
 */
class utils {
	/**
	 * Cleans request vars from $_POST or $_GET
	 *
	 * @param array $req
	 *
	 * @return array
	 */
	public static function clean_request_vars($req = array()) {
		if(empty($req)) $req = $_REQUEST;
		return array_map('self::clean_string', $req);
	}

	private static function clean_string($str) {
		if(!$str) return $str;
		return esc_html(stripslashes($str));
	}

	/**
	 * Retrieves an array of templates available in the plugin
	 *
	 * @return mixed|void
	 */
	public static function get_templates() {
		return apply_filters(__NAMESPACE__ . '_templates', array('Clean' => plugin()->dir . '/templates/clean.html'));
	}
}