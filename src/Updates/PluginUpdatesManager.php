<?php

namespace SternerStuff\WordPressUtils\Updates;

use SternerStuff\WordPressUtils\Updates\Manager;

class PluginUpdatesManager
{

	use Manager;
	
	protected function get_current_version( $plugin_file )
	{
		if( ! function_exists('get_plugin_data') ){
	        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	    }

		$plugin_data = get_plugin_data( $plugin_file );
		if(empty( $plugin_data['Version'] )) {
			throw new \Exception("Plugin version not found.", 1);
		}
		return $plugin_data['Version'];
	}
}