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
		$this->database_version = get_option( $key, '0.0.0' );
		add_action( 'admin_init', [$this, 'run'] );
	}

	public function register_update( $version, VersionUpdate $class )
	{
		if(!isset( $this->updates[$version] )) {
			$this->updates[$version] = [];
		}

		$this->updates[$version][] = $class;
	}

	public function run()
	{
		foreach( $this->updates as $version => $classes ) {
			if(!version_compare( $this->database_version, $version, '<') ) {
				continue;
			}
			foreach($classes as $class) {
				if(!in_array( VersionUpdate::class, class_uses( $class ) )) {
					continue;
				}
				$class->run();
			}
		}

		update_option( $this->key, $this->current_version );
	}

	abstract protected function get_current_version( $root );

}