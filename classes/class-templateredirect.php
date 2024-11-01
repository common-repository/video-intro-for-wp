<?php
if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

class Themestrike_VideoIntroLITE_TemplateRedirect extends Themestrike_VideoIntroLITE {

	/**
	 * Constructor
	 */
	function __construct() {

		add_action( 'init', array( &$this, 'register_session' ), 0 );

		add_action( 'init', array( &$this, 'set_referer' ), 0 );

		add_action( 'init', array( &$this, 'redirect_to_video_page' ) );

		add_action( 'template_redirect', array( &$this, 'load_video_template' ), 0 );

	}


	function register_session(){
	    if( !session_id() ) session_start();
	}


	function set_referer() {
		global $ts_videointro_referer;

		if(isset($_SESSION['referer'])){
			$ts_videointro_referer = $_SESSION['referer'];

		} elseif(isset($_SERVER['HTTP_REFERER'])){
			$ts_videointro_referer = $_SERVER['HTTP_REFERER'];

		} else {
		}
		$_SESSION['referer'] = Themestrike_VideoIntroLITE_URLHelpers::current_page_url();
	}


	function already_visited(){
		if(!isset($_SESSION['ts_videointro_visited_site'])) {
		    $_SESSION['ts_videointro_visited_site'] = 'yes';
		    return false;
		} else {
			return true;
		}
	}

	/**
	 * Detect search engine bots
	 * http://stackoverflow.com/questions/677419/how-to-detect-search-engine-bots-with-php
	 */
	public function is_crawler() {
		$interesting_crawlers = array( '008','ABACHOBot','Accoona-AI-Agent','AddSugarSpiderBot','AnyApexBot','Arachmo','B-l-i-t-z-B-O-T','Baiduspider','BecomeBot','BeslistBot','BillyBobBot','Bimbot','Bingbot','BlitzBOT','boitho.com-dc','boitho.com-robot','btbot','CatchBot','Cerberian Drtrs','Charlotte','ConveraCrawler','cosmos','Covario IDS','DataparkSearch','DiamondBot','Discobot','Dotbot','EmeraldShield.com WebBot','envolk[ITS]spider','EsperanzaBot','Exabot','FAST Enterprise Crawler','FAST-WebCrawler','FDSE robot','FindLinks','FurlBot','FyberSpider','g2crawler','Gaisbot','GalaxyBot','genieBot','Gigabot','Girafabot','Googlebot','Googlebot-Image','GurujiBot','HappyFunBot','hl_ftien_spider','Holmes','htdig','iaskspider','ia_archiver','iCCrawler','ichiro','igdeSpyder','IRLbot','IssueCrawler','Jaxified Bot','Jyxobot','KoepaBot','L.webis','LapozzBot','Larbin','LDSpider','LexxeBot','Linguee Bot','LinkWalker','lmspider','lwp-trivial','mabontland','magpie-crawler','Mediapartners-Google','MJ12bot','Mnogosearch','mogimogi','MojeekBot','Moreoverbot','Morning Paper','msnbot','MSRBot','MVAClient','mxbot','NetResearchServer','NetSeer Crawler','NewsGator','NG-Search','nicebot','noxtrumbot','Nusearch Spider','NutchCVS','Nymesis','obot','oegp','omgilibot','OmniExplorer_Bot','OOZBOT','Orbiter','PageBitesHyperBot','Peew','polybot','Pompos','PostPost','Psbot','PycURL','Qseero','Radian6','RAMPyBot','RufusBot','SandCrawler','SBIder','ScoutJet','Scrubby','SearchSight','Seekbot','semanticdiscovery','Sensis Web Crawler','SEOChat::Bot','SeznamBot','Shim-Crawler','ShopWiki','Shoula robot','silk','Sitebot','Snappy','sogou spider','Sosospider','Speedy Spider','Sqworm','StackRambler','suggybot','SurveyBot','SynooBot','Teoma','TerrawizBot','TheSuBot','Thumbnail.CZ robot','TinEye','truwoGPS','TurnitinBot','TweetedTimes Bot','TwengaBot','updated','Urlfilebot','Vagabondo','VoilaBot','Vortex','voyager','VYU2','webcollage','Websquash.com','wf84','WoFindeIch Robot','WomlpeFactory','Xaldon_WebSpider','yacy','Yahoo! Slurp','Yahoo! Slurp China','YahooSeeker','YahooSeeker-Testing','YandexBot','YandexImages','Yasaklibot','Yeti','YodaoBot','yoogliFetchAgent','YoudaoBot','Zao','Zealbot','zspider','ZyBorg' );
		$interesting_crawlers = array_map('strtolower', $interesting_crawlers);
		$pattern = implode('|', $interesting_crawlers);
		
		if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/'.$pattern.'/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		  return true;
		} else {
		  return false;
		}
	}

	public function previous_page_was_videopage_or_homepage() {
		return
			Themestrike_VideoIntroLITE_URLHelpers::get_previouspage_url() == Themestrike_VideoIntroLITE_URLHelpers::get_homepage_url()
			||
			Themestrike_VideoIntroLITE_URLHelpers::get_previouspage_url() == Themestrike_VideoIntroLITE_URLHelpers::get_videopage_url();
	}


	public function redirect_to_video_page() {
		global $ts_videointro;

		$do_redirect = false;

		if($this->is_crawler()) {
			$do_redirect = false;
		} else if ($ts_videointro['when_redirect_to_video'] == 'always') {
			$do_redirect = true;
		} else if ($ts_videointro['when_redirect_to_video'] == 'first_visit' && !$this->already_visited()) {
			$do_redirect = true;
		}

		if(Themestrike_VideoIntroLITE_URLHelpers::is_homepage_url() && !$this->previous_page_was_videopage_or_homepage() && $do_redirect) {
			wp_redirect( $ts_videointro['videopage_url'] ); exit;
		}

	}


	/**
	 * Load Video template
	 * @url http://stackoverflow.com/a/4975004/1355201
	 */
	public function load_video_template() {
		global $ts_videointro;

		if ( Themestrike_VideoIntroLITE_URLHelpers::is_videopage_url() ) {
			include(parent::$plugin_dir . 'template-video_intro.php');
			die();
		}
	}

}