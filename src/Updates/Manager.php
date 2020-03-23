<?php 

namespace SternerStuff\WordPressUtils\Updates;

use SternerStuff\WordPressUtils\Updates\VersionUpdate;

trait Manager {

	private $updates = [];
	private $current_version;
	private $database_version;

	/**
	 * [__construct description]
	 * @param string $root        For plugins, path to root plugin file. For themes, name of the theme directory
	 * @param string $options_key Suffixed with '_version' to store version in database
	 */
	public function __construct( $root, $options_key )
	{
		$this->current_version = $this->get_current_version( $root );
		$this->key = $options_key . '_version';
		add_action( 'admin_init', [$this, 'run'] );
	}

	public function register_update( $version, $class )
	{
		if(!in_array( VersionUpdate::class, class_implements( $class ) )) {
			throw new \Exception("Update class does not implement VersionUpdate", 1);
			
		}
		if(!isset( $this->updates[$version] )) {
			$this->updates[$version] = [];
		}

		$this->updates[$version][] = $class;
	}

	public function run()
	{
		$this->database_version = get_option( $this->key, '0.0.0' );
		foreach( $this->updates as $version => $classes ) {
			if(!version_compare( $this->database_version, $version, '<') ) {
				continue;
			}
			foreach($classes as $class) {
				if(!in_array( VersionUpdate::class, class_implements( $class ) )) {
					continue;
				}
				$class::run();
			}
		}

		update_option( $this->key, $this->current_version );
	}

	abstract protected function get_current_version( $root );

}