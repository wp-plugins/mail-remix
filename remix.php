<?php
/**
 * Plugin Name: Mail Remix
 * Author: Code By Jinx
 * Author URI: http://byjinx.com/
 * Description: Take your WordPress emails to the next level with HTML Templates. Replacement Codes, Shortcodes, Markdown, Inline PHP, and more!
 * Version: 150517
 */

/**
 * Copyright (C) 2015  Code by Jinx
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!defined('WPINC'))
	exit('Do NOT access this file directly: ' . basename(__FILE__));

$GLOBALS['wp_php_rv'] = '5.3'; // Required PHP version
if(require(dirname(__FILE__) . '/includes/wp-php-rv/check.php'))
	require dirname(__FILE__) . '/includes/remix.inc.php';
else wp_php_rv_notice();