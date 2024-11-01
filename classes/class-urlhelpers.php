<?php
if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

class Themestrike_VideoIntroLITE_URLHelpers extends Themestrike_VideoIntroLITE {

	/**
	 * Constructor
	 */
	function __construct() {

	}

	
	public static function current_page_url() {
		$page_url = 'http';
		if( isset($_SERVER["HTTPS"]) ) {
			if ($_SERVER["HTTPS"] == "on") {$page_url .= "s";}
		}
		$page_url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$page_url .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$page_url .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		}
		return self::format_url_for_comparison($page_url);
	}


	public static function format_url_for_comparison($url) {
		$url             = untrailingslashit($url);
		$url_without_get = explode('?', $url);
		$url_without_get = array_shift($url_without_get);
		$url_clean       = untrailingslashit(preg_replace('#^https?://#', '', $url_without_get));

		return $url_clean;
	}

	
	public static function get_previouspage_url() {
		global $ts_videointro_referer;

		if(!isset($ts_videointro_referer))
			$ts_videointro_referer = null;

		return self::format_url_for_comparison($ts_videointro_referer);

	}

	
	public static function get_homepage_url() {
		global $ts_videointro;
		
		return self::format_url_for_comparison($ts_videointro['homepage_url']);
	}

	
	public static function get_videopage_url() {
		global $ts_videointro;
		
		return self::format_url_for_comparison($ts_videointro['videopage_url']);
	}

	
	public static function is_homepage_url() {
		
		return self::is_same_url(self::get_homepage_url(), self::current_page_url());
	}

	
	public static function is_videopage_url() {
		
		return self::is_same_url(self::get_videopage_url(), self::current_page_url());
	}


	public static function is_same_url($url1, $url2) {
		$is_same_url = $url1 == $url2;

		return  $is_same_url;
	}


}