<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

/**
 * Class smtp
 *
 * @package mail_remix
 */
class smtp {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();

		$opts = $this->plugin->opts();

		if($opts['smtp']) $this->force_smtp();
	}

	/**
	 * Forces emails to be sent via SMTP on the `phpmailer_init` hook
	 */
	private function force_smtp() {
		add_action('phpmailer_init', function ($mailer) {
			$opts = $this->plugin->opts();

			$mailer->IsSMTP();

			$mailer->Host = $opts['smtp_host'];
			$mailer->Port = $opts['smtp_port'];

			if(!empty($opts['smtp_from'])) {
				$email = htmlspecialchars_decode($opts['smtp_from']);

				if(strpos($email, '<') === FALSE)
					$mailer->setFrom($email);

				else {
					$email_parts   = explode('<', $email, 2);
					$email_name    = str_replace(array('\'', '"'), '', $email_parts[0]);
					$email_address = preg_replace('/>$/', '', $email_parts[1]);

					$mailer->setFrom($email_address, $email_name);
				}
			}

			if(!empty($opts['smtp_return_path'])) {
				$email = htmlspecialchars_decode($opts['smtp_return_path']);

				if(strpos($email, '<') === FALSE)
					$mailer->ReturnPath = $email;

				else {
					$email_parts   = explode('<', $email, 2);
					$email_name    = str_replace(array('\'', '"'), '', $email_parts[0]);
					$email_address = preg_replace('/>$/', '', $email_parts[1]);

					$mailer->ReturnPath = $email_address;
				}
			}

			if($opts['smtp_auth']) {
				$mailer->SMTPAuth = TRUE;

				$mailer->Username = $opts['smtp_user'];
				$mailer->Password = $opts['smtp_pass'];
			}

			if($opts['smtp_con_mode'] !== 'plaintext' && !empty($opts['smtp_con_mode']))
				$mailer->SMTPSecure = $opts['smtp_con_mode'];
		}, 1, 1);
	}
}