<?php

namespace mail_remix\admin;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

/**
 * Class templates
 *
 * @package mail_remix\damin
 */
class templates {

	/**
	 * Page saving mechanisms
	 */
	public static function maybe_save_opts() {
		if(!isset($_POST[__NAMESPACE__ . '_admin_templates_nonce']) || !wp_verify_nonce($_POST[__NAMESPACE__ . '_admin_templates_nonce'], __NAMESPACE__ . '_save_templates'))
			return;

		$_p   = \mail_remix\utils::clean_request_vars($_POST);
		$opts = \mail_remix\plugin()->template_opts();

		$text_forms = array('foreground-color', 'background-color', 'banner-color', 'border-color');

		foreach($text_forms as $name) {
			if(isset($_p[$name]) && $_p[$name])
				$opts[$name] = sanitize_text_field($_p[$name]);
			else $opts[$name] = '';
		}

		$footer_text         = $_p['footer-text'];
		$opts['footer-text'] = trim($footer_text);

		update_site_option('mail_remix_template_colors', $opts);
	}

	/**
	 * Page HTML
	 */
	public static function do_print() {
		$opts = \mail_remix\plugin()->template_opts();

		?>
		<div class="wrap">
		<h2>Mail Remix | Templating</h2>

		<form method="post" action="">
			<div class="remix-templates-editor">
				<h3>Config</h3>
				<table class="form-table">
					<tbody>

					<tr>
						<th scope="row">
							<label for="remix-templates-dropdown">Base Template</label>
						</th>
						<td>
							<select id="remix-templates-dropdown">
								<?php
								foreach(\mail_remix\utils::get_templates() as $name => $file)
									echo '<option value="' . $file . '">' . $name . '</option>';
								?>
							</select>
							<p class="description">Customize your selected theme below.</p>
						</td>
					</tr>

					</tbody>
				</table>

				<h3>Colors</h3>
				<div class="remix-editor-colors">
					<table class="form-table">
						<tbody>

						<tr>
							<th scope="row">
								<label for="primary-color">Foreground Color</label>
							</th>
							<td>
								<input type="text" class="colorpicker" value="<?php echo $opts['foreground-color']; ?>" name="foreground-color" id="foreground-color" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="primary-color">Background Color</label>
							</th>
							<td>
								<input type="text" class="colorpicker" value="<?php echo $opts['background-color']; ?>" name="background-color" id="background-color" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="primary-color">Banner Color</label>
							</th>
							<td>
								<input type="text" class="colorpicker" value="<?php echo $opts['banner-color']; ?>" name="banner-color" id="banner-color" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="primary-color">Border Color</label>
							</th>
							<td>
								<input type="text" class="colorpicker" value="<?php echo $opts['border-color']; ?>" name="border-color" id="border-color" />
							</td>
						</tr>

						</tbody>
					</table>
				</div>

				<h3>Other Options</h3>
				<div class="remix-editor-colors">
					<table class="form-table">
						<tbody>

						<tr>
							<th scope="row">
								<label for="footer-text">Footer Text</label>
							</th>
							<td>
								<textarea name="footer-text" id="footer-text"><?php echo $opts['footer-text']; ?></textarea>
							</td>
						</tr>

						</tbody>
					</table>
				</div>

				<div class="remix-templates-preview"></div>
			</div>

			<hr />

			<?php wp_nonce_field(__NAMESPACE__ . '_save_templates', __NAMESPACE__ . '_admin_templates_nonce'); ?>

			<!--
			<button class="button button-default" type="button">Preview</button>
			-->
			<button class="button button-primary" type="submit">Save All Changes</button>
		</form>
	<?php
	}
}