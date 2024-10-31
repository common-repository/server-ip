<?php 
/*
Plugin Name: Server IP
Plugin URI: http://adresseip.vpndock.com/plugin-adresse-ip-serveur-wordpress/
Description: Display Server IP address (and hostname if exists) on the WordPress dashboard admin panel, or show Server IP address on page, post or sidebar widget with a shortcode.
Author: VPN Dock
Version: 1.0.003
Author URI: http://vpndock.com/
License: GPL2
*/

 
if(is_admin())
	{	
		function server_ip_dashboard_widget_function()
		{
			// server ip
			$server_ip = $_SERVER['SERVER_ADDR'];
			if(!$server_ip)
				$server_ip = 'unknown';
			
			// server hostname
			$server_hostname = @gethostbyaddr($_SERVER['SERVER_ADDR']);
			if(!$server_hostname OR $server_hostname == $server_ip)
				$server_hostname = 'unknown';
			
			// display
			echo '<div style="display:table; width: 100%;">';
			
			echo '<div style="display:table-cell;"><big><strong>'.$server_ip.'</strong></big></div>';
			
			if($server_hostname != 'unknown')
				echo '<div style="display:table-cell; text-align: right;"><small>('.__('hostname', 'server-ip').' : '.$server_hostname.')</small></div>';
			
			echo "</div>\n";
		}
		
		
		// add FAQ link on plugin page
		function server_ip_faq_link($links)
		{ 
			$faq_link = '<a href="http://wordpress.org/plugins/server-ip/faq/" target="_blank">FAQ</a>'; 
			//array_unshift($links, $faq_link);
			array_push($links, $faq_link); 
			return $links; 
		}
		
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'server_ip_faq_link' );
		
		
		// function dashboard widget
		function server_ip_add_dashboard_widgets()
		{
			wp_add_dashboard_widget('server_ip_dashboard_widget', __('Server IP Address', 'server-ip'), 'server_ip_dashboard_widget_function');
		}
		
		// function load translation file
		function server_ip_load_translation_file()
		{
			$plugin_path = basename(dirname(__FILE__)).'/lang/';
			load_plugin_textdomain( 'server-ip', false, $plugin_path );
		}
		
		
		add_action('wp_dashboard_setup', 'server_ip_add_dashboard_widgets' );
		add_action('init', 'server_ip_load_translation_file');
	}
 

// shortcode [server_ip]
function display_server_ip()
{
        $server_ip = $_SERVER['SERVER_ADDR'];
        return $server_ip;
}

add_shortcode('server_ip', 'display_server_ip');

// use shortcodes on Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

