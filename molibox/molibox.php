<?php
/*
Plugin Name: MoliBox
Plugin URI: 
Description: Yet another WordPress lightbox plugin - but also optimized for mobile devices.
Version: 0.1
Author: Flo Edelmann
Author URI: http://www.duran2.de/
Text Domain: molibox
License: GPL2
*/

/*  Copyright 2012  Florian Edelmann  (email: florian-edelmann@online.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
register uninstall hook: WP2.7+
*/

load_plugin_textdomain('molibox', false, 'molibox/languages');

function molibox_install() {
  add_option('molibox_enable_always', 'on', '', 'yes');
  add_option('molibox_rel_types', 'molibox,lightbox', '', 'yes');
  add_option('molibox_overlay_color', '#000000', '', 'yes');
  add_option('molibox_overlay_opacity', '0.7', '', 'yes');
}

function molibox_remove() {
  // if uninstall not called from WordPress, exit
  if (!defined('WP_UNINSTALL_PLUGIN'))
  	exit();
  
  delete_option('molibox_overlay_color');
  delete_option('molibox_enable_always');
  delete_option('molibox_overlay_opacity');
}

// Add settings link on plugin page
function molibox_plugin_settings_link($links) {
  array_unshift($links, '<a href="options-media.php">' . __('Settings', 'molibox') . '</a>'); 
  return $links; 
}

function molibox_enqueue_scripts() {
  wp_enqueue_script('molibox', plugins_url('molibox/molibox.js'));
  wp_localize_script('molibox', 'molibox', array(
    'enableAlways' => get_option('molibox_enable_always'),
    'relTypes' => get_option('molibox_rel_types'),
    'overlayColor' => get_option('molibox_overlay_color'),
    'overlayOpacity' => get_option('molibox_overlay_opacity'),
    'nextText' => __('Next', 'molibox'),
    'prevText' => __('Previous', 'molibox')
  ));
  
  wp_enqueue_style('molibox', plugins_url('molibox/molibox.css'));
}

register_activation_hook(__FILE__, 'molibox_install');
register_uninstall_hook(__FILE__, 'molibox_remove');

if (is_admin()) {
  require_once "settings.php";
  add_action('admin_init', 'molibox_settings_api_init');
}
add_filter('plugin_action_links_molibox/molibox.php', 'molibox_plugin_settings_link');
add_action('wp_enqueue_scripts', 'molibox_enqueue_scripts');

?>
