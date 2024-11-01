<?php
if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

class Themestrike_VideoIntroLITE_GetVideoCode extends Themestrike_VideoIntroLITE {

	/**
	 * Constructor
	 */
	function __construct() {
	}


	function get_video_code() {
		$this->get_youtube_code();
	}

	function get_youtube_code() {
		global $ts_videointro;

		?>
		<script src="http://www.youtube.com/player_api"></script>
	    <script>
	        /**
	         * Create YouTube Player
	         */
	        var player;
	        function onYouTubePlayerAPIReady() {
	            player = new YT.Player('ts-videointro-player-main', {
	              height: '390',
	              width: '640',
	              videoId: '<?php echo $ts_videointro['youtube_id'] ?>',
	              playerVars: {
	                          controls: 0,
	                          showinfo: 0 ,
	                          modestbranding: 1,
	                          wmode: "opaque",
	                          rel: 0
                  },
	              events: {
	                'onReady': onPlayerReady,
	                'onStateChange': onPlayerStateChange
	              }
	            });
	        }

	        // autoplay video
	        function onPlayerReady(event) {
	        	event.target.setVolume(100);
	        	if(!jQuery.browser.mobile)
		            event.target.playVideo();
	        }

	        // when video ends
	        function onPlayerStateChange(event) {        
	            if(event.data === 0) {          
                	window.open("<?php echo $ts_videointro['homepage_url'] ?>", "_self");
	            }
	        }


	        jQuery(document).ready(function($){
				$('.ts-videointro-viewport').addClass('ts-videointro-player-embedded ts-videointro-player-youtube');
	        });
	    </script>
		<?php
	}


}