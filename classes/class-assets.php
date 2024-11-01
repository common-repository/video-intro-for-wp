<?php
if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

class Themestrike_VideoIntroLITE_Assets extends Themestrike_VideoIntroLITE {

	/**
	 * Constructor
	 */
	function __construct() {
		// Load JavaScript and stylesheets
		add_action( 'init', array( &$this, 'register_scripts_and_styles' ) );
		add_action( 'wp_print_scripts', array(&$this, 'reset_scripts_queue'), 100);
		add_action( 'wp_print_styles', array(&$this, 'reset_styles_queue'), 100);
	}

	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	function register_scripts_and_styles() {
		if ( is_admin() && isset($_GET['page']) && $_GET['page'] == '_videointro') {
			/**
			 * CSS
			 */
			//Video Intro for WordPress LITE Admin CSS
			wp_register_style(
				parent::$plugin_slug . '_admin_css',
				parent::$plugin_url . 'assets/css/themestrike_videointro_admin.css',
				array(),
				parent::$plugin_v
				);
			wp_enqueue_style( parent::$plugin_slug . '_admin_css' );

		} else if ( ! is_admin() && Themestrike_VideoIntroLITE_URLHelpers::is_videopage_url() ) {

			$min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

			/**
			 * JS
			 */
			//jPlayer
			wp_register_script(
				'jplayer',
				parent::$plugin_url . 'assets/js/jquery.jplayer.min.js',
				array('jquery'),
				'2.7.0'
				);
			wp_enqueue_script( 'jplayer' );

			//Detect Mobile Browser
			wp_register_script(
				'detectmobilebrowser',
				parent::$plugin_url . 'assets/js/jquery.detectmobilebrowser.js',
				array('jquery')
				);
			wp_enqueue_script( 'detectmobilebrowser' );

			//Detect Mobile Browser
			wp_register_script(
				'jquery-mobile-events-touch',
				parent::$plugin_url . 'assets/js/jquery.mobile.events-touch.min.js',
				array('jquery'),
				'1.4.5'
				);
			wp_enqueue_script( 'jquery-mobile-events-touch' );

			//Video Intro for WordPress Front-end JS
			wp_register_script(
				parent::$plugin_slug . '_js',
				parent::$plugin_url . 'assets/js/themestrike_videointro'.$min.'.js',
				array('jquery'),
				parent::$plugin_v
				);
			wp_enqueue_script( parent::$plugin_slug . '_js' );

			/**
			 * CSS
			 */
			//Video Intro for WordPress Front-end CSS
			wp_register_style(
				parent::$plugin_slug . '_css',
				parent::$plugin_url . 'assets/css/themestrike_videointro'.$min.'.css',
				array(),
				parent::$plugin_v
				);
			wp_enqueue_style( parent::$plugin_slug . '_css' );

		} // end if/else
	} // end register_scripts_and_styles

	/**
	 * Remove all registered scripts and styles
	 * and re-enqueue selected scripts and styles
	 */
	function reset_scripts_queue() {
		if ( ! is_admin() && Themestrike_VideoIntroLITE_URLHelpers::is_videopage_url() ) {
			global $wp_scripts, $ts_videointro;

			if(isset($ts_videointro['js_enqueue']) && !empty($ts_videointro['js_enqueue'])) {
				$wp_scripts->queue = explode("\n", $ts_videointro['js_enqueue']);
			} else {
				$wp_scripts->queue = array();
			}

			$wp_scripts->queue[] = 'jplayer';
			$wp_scripts->queue[] = 'detectmobilebrowser';
			$wp_scripts->queue[] = 'jquery-mobile-events-touch';
			$wp_scripts->queue[] = parent::$plugin_slug . '_js';
			$wp_scripts->queue[] = 'admin-bar';

		}
	}
	function reset_styles_queue() {
		if ( ! is_admin() && Themestrike_VideoIntroLITE_URLHelpers::is_videopage_url() ) {
			global $wp_styles, $ts_videointro;

			if(isset($ts_videointro['css_enqueue']) && !empty($ts_videointro['css_enqueue'])) {
				$wp_styles->queue = explode("\n", $ts_videointro['css_enqueue']);
			} else {
				$wp_styles->queue = array();
			}

			$wp_styles->queue[] = parent::$plugin_slug . '_css';
			$wp_styles->queue[] = 'admin-bar';

		}
	}

}