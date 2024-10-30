<?php
/*
Plugin Name: WP CookieScan by code.je
Plugin URI: https://code.je
Description: Add CookieScan to Wordpress
Version: 1.0.1
Author: Codentia

Copyright (C) 2021  Codentia Ltd (https://code.je)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * ----------------------------------------------------------------------------------------
 * ADMIN MENU ITEM
 * ----------------------------------------------------------------------------------------
 */
if(!function_exists('cookiescan_menu_item'))
{
	function cookiescan_menu_item()
	{
		add_submenu_page(
			'options-general.php',
			'CookieScan',
			'CookieScan',
			'manage_options',
			'cookiescan',
			'cookiescan_settings_section'
		); 
	}

	add_action('admin_menu', 'cookiescan_menu_item');
}

/**
 * ----------------------------------------------------------------------------------------
 * SETTINGS LINK
 * ----------------------------------------------------------------------------------------
 */
if(!function_exists('cookiescan_settings_link'))
{
	function cookiescan_settings_link($links)
	{
		$links[] = '<a href="' . admin_url('options-general.php?page=cookiescan') . '">' . __('Settings') . '</a>';
		
		return $links;
	}

	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cookiescan_settings_link');
}

/**
 * ----------------------------------------------------------------------------------------
 * SETTINGS FORM
 * ----------------------------------------------------------------------------------------
 */
if(!function_exists('cookiescan_settings_section'))
{
	function cookiescan_settings_section()
	{
		?>
			<div class='wrap'>
				<h1>CookieScan Settings</h1>

				<img src="<?php echo(plugin_dir_url(__FILE__) . '/assets/img/cookiescan.png'); ?>" role='img' alt='CookieScan logo' width='213' height='59' />

				<form method='post' action='options.php'>
					<?php
					settings_fields('cookiescan_config_section');

					do_settings_sections('cookiescan');

					submit_button();
					?>
				</form>
			</div>
		<?php
	}
}

/**
 * ----------------------------------------------------------------------------------------
 * SETTINGS
 * ----------------------------------------------------------------------------------------
 */
if(!function_exists('cookiescan_settings'))
{
	function cookiescan_settings()
	{
		add_settings_section('cookiescan_config_section', '', null, 'cookiescan');

		add_settings_field(
			'cookiescan-id', 
			'Enter CookieScan Domain ID', 
			'cookiescan_id', 
			'cookiescan', 
			'cookiescan_config_section',
			array(
				'label_for' => 'cookiescan_id'
			)
		); 
	 
		register_setting('cookiescan_config_section', 'cookiescan-id');
	}

	add_action('admin_init', 'cookiescan_settings');
}

/**
 * ----------------------------------------------------------------------------------------
 * INPUT FIELD
 * ----------------------------------------------------------------------------------------
 */
if(!function_exists('cookiescan_id'))
{
	function cookiescan_id()
	{
		$domain_id = get_option('cookiescan-id');
		
		echo("<input type='text' name='cookiescan-id' id='cookiescan_id' value='$domain_id' />");
	}
}

/**
 * ----------------------------------------------------------------------------------------
 * ENQUEUE SCRIPTS
 * ----------------------------------------------------------------------------------------
 */
if(!function_exists('enqueue_cookiescan_scripts'))
{
	function enqueue_cookiescan_scripts()
	{
		$domain_id = get_option('cookiescan-id');

		if($domain_id)
		{
			wp_enqueue_script('get-variables', 'https://www.cookiescan.com/domain/getVariables?domainId=' . $domain_id, array(), null, false);
			wp_enqueue_script('cookiescan', 'https://www.cookiescan.com/plugins/cookiescanplugin.js?domainId=' . $domain_id, array(), null, false);
		}
	}

	add_action('wp_enqueue_scripts', 'enqueue_cookiescan_scripts');
}
