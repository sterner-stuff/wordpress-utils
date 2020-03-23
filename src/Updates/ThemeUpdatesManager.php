<?php

namespace SternerStuff\WordPressUtils\Updates;

use SternerStuff\WordPressUtils\Updates\Manager;

class ThemeUpdatesManager
{

	use Manager;
	
	protected function get_current_version( $theme_directory_name )
	{
		$theme = wp_get_theme( $theme_directory_name );
		if(!$theme->exists()) {
			throw new Exception("No such theme", 1);
		}
		if(!$theme->version) {
			throw new Exception("Theme version not found.", 1);
		}
		return $theme->version;
	}
}