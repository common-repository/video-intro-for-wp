<?php
/*
Plugin Name: Video Intro for WordPress LITE
Plugin URI: http://themestrike.com
Description: Video Intro for WordPress LITE version
Version: 1.0.1
Author: Themestrike
Author Email: hi@themestrike.com
License: http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
*/

if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

class Themestrike_VideoIntroLITE {

	/**
	 * Vars
	 */
	public static $plugin_name;
	public static $plugin_slug;
	public static $plugin_dir;
	public static $plugin_url;
	public static $plugin_v;
	public static $plugin_file;
	
	/**
	 * Constructor
	 */
	function __construct() {
		/**
		 * Setting vars
		 */
		self::$plugin_name = 'Video Intro for WordPress LITE';
		self::$plugin_slug = 'themestrike_video_intro_lite';
		self::$plugin_dir  = plugin_dir_path( __FILE__ );
		self::$plugin_url  = plugin_dir_url( __FILE__ ) ;
		self::$plugin_file = __FILE__ ;
		self::$plugin_v    = '1.0.1' ;

		//register an activation hook for the plugin
		register_activation_hook( self::$plugin_file, array( &$this, 'install_video_intro' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_video_intro' ) );

		//Deactivate LITE version if FULL version is installed
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'themestrike_video_intro/themestrike_video_intro.php' ) )
			deactivate_plugins( plugin_basename( __FILE__ ) );
	}


	/**
	 * Runs when the plugin is activated
	 */  
	function install_video_intro() {

	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_video_intro() {
		// Setup localization
		load_plugin_textdomain( self::$plugin_slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend
		}
	}

  
} // end class

/** 
 * Autoload classes
 */
if  ( ! function_exists('themestrike_videointrolite_autoload_plugin_classes') ) {
	function themestrike_videointrolite_autoload_plugin_classes($class_name)
	{
		$class_name          = strtolower($class_name);
		$exploded_class_name = explode('_', $class_name);
		$mother_class        = array_shift($exploded_class_name);
		// if is not a Themestrike class, stop the autoload
		if('themestrike' != $mother_class) return null;
		// pop the last element, store it and format as a class file
		$file_name           = 'class-' . array_pop($exploded_class_name) . '.php';
		//remove the second part ot the class
		array_shift($exploded_class_name);
		// write path with the reamining elements
		$path                = implode('/', $exploded_class_name);
		// if there is a path, add a '/' to the end
		$path                = ($path) ? $path . '/' : $path;
		$file_with_path      = 'classes/'  . $path . $file_name;

		require_once $file_with_path;
	}
}

/**
 * Register autoload
 */
spl_autoload_register('themestrike_videointrolite_autoload_plugin_classes');

/**
 * TGM Plugin Activation
 */
require plugin_dir_path( __FILE__ ) . 'lib/tgm-plugin-activation/config.php';

/**
 * Load current file class
 */
new Themestrike_VideoIntroLITE();

/**
 * Load Assets
 */
new Themestrike_VideoIntroLITE_Assets();

/**
 * Load Options Panel
 */
new Themestrike_VideoIntroLITE_OptionsPanel();

/**
 * Load Template Redirection
 */
new Themestrike_VideoIntroLITE_TemplateRedirect();