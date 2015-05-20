<?php
namespace mail_remix\admin;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

/**
 * Class logging
 *
 * @package mail_remix\admin
 */
class logging {

	/**
	 * Page saving mechanisms
	 */
	public static function maybe_save_opts() {
		if(!isset($_POST[__NAMESPACE__ . '_admin_logging_nonce']) || !wp_verify_nonce($_POST[__NAMESPACE__ . '_admin_logging_nonce'], __NAMESPACE__ . '_save_logging'))
			return;

		$_p = \mail_remix\utils::clean_request_vars($_POST);

		$opts = \mail_remix\plugin()->opts();

		if(isset($_p['logging']) && $_p['logging'])
			$opts['logging'] = TRUE;
		else $opts['logging'] = FALSE;

		update_site_option(__NAMESPACE__ . '_options', $opts);
	}

	/**
	 * Page HTML
	 */
	public static function do_print() {
		$log_files = scandir(\mail_remix\plugin()->log_dir);

		$log_files = array_filter($log_files, function ($o) {
			$files_no = array('.', '..', '.htaccess');

			if(in_array($o, $files_no, TRUE) || strpos($o, '.log') === FALSE) return FALSE;
			return TRUE;
		});

		arsort($log_files);

		if(isset($_REQUEST['log-file']) && !empty($_REQUEST['log-file']))
			$log_file = sanitize_text_field($_REQUEST['log-file']);
		else $log_file = '';

		if($log_file && is_file(\mail_remix\plugin()->log_dir . '/' . $log_file) && is_readable(\mail_remix\plugin()->log_dir . '/' . $log_file))
			$log_file_content = fopen(\mail_remix\plugin()->log_dir . '/' . $log_file, 'r');
		else $log_file_content = FALSE;

		?>
		<div class="wrap">
			<h2>Mail Remix | Logging</h2>
			<div class="updated">
				<p>
					Mail Remix is currently logging ALL WordPress emails sent through <a href="http://codex.wordpress.org/Function_Reference/wp_mail" target="_blank"><code>wp_mail()</code></a>. Logs for these emails are available below.
				</p>
				<p>
					These emails are logged via the <a href="http://codex.wordpress.org/Plugin_API/Action_Reference/phpmailer_init"><code>phpmailer_init</code></a> action, alongside the base functionality for the plugin.
					Because the <code>wp_mail()</code> function is pluggable, emails sent by some plugins (those with their own PHPMailer implementations) may not be logged.
				</p>
				<p>
					Any emails that are not being logged in these entries are incapable of being templated by Mail Remix.
				</p>
			</div>

			<h3>Logs</h3>

			<div class="mail-remix-logs">
				<form method="post" action="">
					<label for="remix-log-file-dropdown" class="special-label">Select Log File</label> <br />
					<select id="remix-log-file-dropdown" name="log-file" style="width: 75%;">
						<option>--</option>
						<?php foreach($log_files as $file)
							echo '<option value="' . $file . '" '
							     . ($log_file === $file ? 'selected="selected"' : '')
							     . '>' . $file . '</option>'; ?>
					</select>
					<button type="submit" class="button button-secondary">View Log</button>
					<p class="description">
						These log files are located in the <code><?php echo \mail_remix\plugin()->log_dir; ?></code> directory.
					</p>
				</form>

				<span class="special-label">Details</span>
				<textarea onchange="return false;" onkeypress="return false;" style="width: 100%; max-width: 100%; min-width: 500px; min-height: 350px;"><?php
					if($log_file_content !== FALSE)
						while(!feof($log_file_content))
							echo fgets($log_file_content);
					?></textarea>
			</div>
		</div>
	<?php
	}
}