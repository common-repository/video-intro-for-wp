<?php

if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 * */

class Themestrike_VideoIntroLITE_OptionsPanel extends Themestrike_VideoIntroLITE {

	public $args = array();
	public $sections = array();
	public $theme;
	public $ReduxFramework;
	public $disabled_msg;

	public function __construct() {
		// This is needed. Bah WordPress bugs.  ;)
		if ( defined('TEMPLATEPATH') && strpos(__FILE__,TEMPLATEPATH) !== false) {
			$this->initSettings();
		} else {
			add_action('plugins_loaded', array($this, 'initSettings'), 10);    
		}

		$this->disabled_msg = '<span class="ts-videointrolite-disabled-msg">You can edit this option in the full version of <a href="http://codecanyon.net/item/video-intro-for-wordpress/6900712?ref=meydjer" target="_blank">Video Intro for WordPress</a>.</span>';
	}

	public function initSettings() {

		if ( !class_exists("ReduxFramework" ) ) {
			return;
		}       
		
		// Just for demo purposes. Not needed per say.
		$this->theme = wp_get_theme();

		// Set the default arguments
		$this->setArguments();

		// Set a few help tabs so you can see how it's done
		$this->setHelpTabs();

		// Create the sections and fields
		$this->setSections();

		if (!isset($this->args['opt_name'])) { // No errors please
			return;
		}

		// If Redux is running as a plugin, this will remove the demo notice and links
		add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
		// Function to test the compiler hook and demo CSS output.
		// add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2); 
		// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
		// Change the arguments after they've been declared, but before the panel is created
		//add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
		// Change the default value of a field after it's been set, but before it's been useds
		//add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
		// Dynamically add a section. Can be also used to modify sections/fields
		// add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

		$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);

		$this->ReduxFramework->set('video_source', 'youtube');
		$this->ReduxFramework->set('m4v', '');
		$this->ReduxFramework->set('ogv', '');
		$this->ReduxFramework->set('video_fit', 'fill');
		$this->ReduxFramework->set('volume', '100');
		$this->ReduxFramework->set('action_after_end', 'redirect');

		$this->ReduxFramework->set('when_redirect_to_video', 'always');

		$this->ReduxFramework->set('logo_margin', array(
				'margin-top'     => '0px', 
				'margin-right'   => '0px', 
				'margin-bottom'  => '0px', 
				'margin-left'    => '0px',
				'units'          => 'px', 
			));
		$this->ReduxFramework->set('logo_align', 'center');

		$this->ReduxFramework->set('frame_border_width', '40');
		$this->ReduxFramework->set('frame_border_radius', '2');
		$this->ReduxFramework->set('frame_border_bg', array(
				'background-color' => '#FFFFFF',
				'background-image' => '',
				'background-repeat' => '',
				'background-attachment' => '',
				'background-position' => ''
			));

		$this->ReduxFramework->set('skipintro_is_enabled', 1);
		$this->ReduxFramework->set('skipintro_bg_color', array(
				'regular' => '#121517',
				'hover' => '#fff'
			));
		$this->ReduxFramework->set('skipintro_font_color', array(
				'regular' => '#fff',
				'hover' => '#121517'
			));
		$this->ReduxFramework->set('skipintro_border_radius', '4');

		$this->ReduxFramework->set('bottom_text_typography', array(
		        'color' => "#121517",
		        'font-style' => '400',
		        'font-family' => 'Arial, Helvetica, sans-serif',
		        'google' => true,
		        'font-size' => '12px'
		    ));


		$this->ReduxFramework->set('css_enqueue', '');
		$this->ReduxFramework->set('custom_css', "body {\n\n}");

		$this->ReduxFramework->set('js_enqueue', '');
		$this->ReduxFramework->set('custom_js', "jQuery(document).ready(function($){\n\n});");
		$this->ReduxFramework->set('tracking_code', '');

	}

	/**

	  This is a test function that will let you see when the compiler hook occurs.
	  It only runs if a field   set with compiler=>true is changed.

	 * */
	function compiler_action($options, $css) {
		// echo "<h1>The compiler hook has run!";
		// print_r($options); //Option values
		// print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

		
		  // Demo of how to use the dynamic CSS and write your own static CSS file
		  // $filename = dirname(__FILE__) . '/style' . '.css';
		  // global $wp_filesystem;
		  // if( empty( $wp_filesystem ) ) {
		  // require_once( ABSPATH .'/wp-admin/includes/file.php' );
		  // WP_Filesystem();
		  // }

		  // if( $wp_filesystem ) {
		  // $wp_filesystem->put_contents(
		  // $filename,
		  // $css,
		  // FS_CHMOD_FILE // predefined mode settings for WP files
		  // );
		  // }
		 
	}

	/**

	  Custom function for filtering the sections array. Good for child themes to override or add to the sections.
	  Simply include this function in the child themes functions.php file.

	  NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
	  so you must use get_template_directory_uri() if you want to use any of the built in icons

	 * */
	function dynamic_section($sections) {
		//$sections = array();
		$sections[] = array(
			'title' => __('Section via hook', 'themestrike-videointro'),
			'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'themestrike-videointro'),
			'icon' => 'el-icon-paper-clip',
			// Leave this as a blank section, no options just some intro text set above.
			'fields' => array()
		);

		return $sections;
	}

	/**

	  Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

	 * */
	function change_arguments($args) {
		//$args['dev_mode'] = true;

		return $args;
	}

	/**

	  Filter hook for filtering the default value of any given field. Very useful in development mode.

	 * */
	function change_defaults($defaults) {
		$defaults['str_replace'] = "Testing filter hook!";

		return $defaults;
	}

	// Remove the demo link and the notice of integrated demo from the redux-framework plugin
	function remove_demo() {

	    // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
	    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
	        remove_filter( 'plugin_row_meta', array(
	            ReduxFrameworkPlugin::instance(),
	            'plugin_metalinks'
	        ), null, 2 );

	        // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
	        remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
	    }
	}

	public function setSections() {

		/**
		  Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
		 * */
		// Background Patterns Reader
		$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
		$sample_patterns_url = ReduxFramework::$_url . '../sample/patterns/';
		$sample_patterns = array();

		if (is_dir($sample_patterns_path)) :

			if ($sample_patterns_dir = opendir($sample_patterns_path)) :
				$sample_patterns = array();

				while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

					if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
						$name = explode(".", $sample_patterns_file);
						$name = str_replace('.' . end($name), '', $sample_patterns_file);
						$sample_patterns[] = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
					}
				}
			endif;
		endif;

		ob_start();

		$ct = wp_get_theme();
		$this->theme = $ct;
		$item_name = $this->theme->get('Name');
		$tags = $this->theme->Tags;
		$screenshot = $this->theme->get_screenshot();
		$class = $screenshot ? 'has-screenshot' : '';

		$customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'themestrike-videointro'), $this->theme->display('Name'));
		?>
		<div id="current-theme" class="<?php echo esc_attr($class); ?>">
		<?php if ($screenshot) : ?>
			<?php if (current_user_can('edit_theme_options')) : ?>
					<a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
						<img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
					</a>
			<?php endif; ?>
				<img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
		<?php endif; ?>

			<h4>
		<?php echo $this->theme->display('Name'); ?>
			</h4>

			<div>
				<ul class="theme-info">
					<li><?php printf(__('By %s', 'themestrike-videointro'), $this->theme->display('Author')); ?></li>
					<li><?php printf(__('Version %s', 'themestrike-videointro'), $this->theme->display('Version')); ?></li>
					<li><?php echo '<strong>' . __('Tags', 'themestrike-videointro') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
				</ul>
				<p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
			<?php
			if ($this->theme->parent()) {
				printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'themestrike-videointro'), $this->theme->parent()->display('Name'));
			}
			?>

			</div>

		</div>

		<?php
		$item_info = ob_get_contents();

		ob_end_clean();

		$sampleHTML = '';
		if (file_exists(dirname(__FILE__) . '/info-html.html')) {
			/** @global WP_Filesystem_Direct $wp_filesystem  */
			global $wp_filesystem;
			if (empty($wp_filesystem)) {
				require_once(ABSPATH . '/wp-admin/includes/file.php');
				WP_Filesystem();
			}
			$sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
		}


		// ACTUAL DECLARATION OF SECTIONS
		
		$this->sections[] = array(
			'title' => __('FULL Version', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-shopping-cart',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(
			
				array(
					'id'     => 'full_version',
					'type'   => 'info',
					'notice' => true,
					'style'  => 'info',
					'title'  => __('Want more features and awesome support?', 'themestrike-videointro'),
					'desc'   => '

						<br>

						<a href="http://codecanyon.net/item/video-intro-for-wordpress/6900712?ref=meydjer" target="_blank">Buy Video Intro for WordPress on CodeCanyon</a>.

						<br>
						<br>

						This is just the LITE version of Video Intro for WordPress. If you like this plugin and want to support it, if you need more features and advanced options or if you need professional support, buy the <strong>FULL VERSION</strong>:

						<br>
						<br>

						Explore this Options Panel to find everything you can do with <a href="http://codecanyon.net/item/video-intro-for-wordpress/6900712?ref=meydjer" target="_blank">Video Intro for WordPress FULL</a>.

						<br>
						<br>

						<a href="http://codecanyon.net/item/video-intro-for-wordpress/6900712?ref=meydjer" target="_blank">Buy Video Intro for WordPress on CodeCanyon</a>.

						<br>
						<br>

						<a href="http://plugins.themestrike.com/videointro2/" target="_blank"><img src="http://plugins.themestrike.com/videointro2/ts-assets/default-config.png" /></a>

						<a href="http://plugins.themestrike.com/videointro2/videopage/?themestrike_demo=2" target="_blank"><img src="http://plugins.themestrike.com/videointro2/ts-assets/custom-colors.png" /></a>

						<a href="http://plugins.themestrike.com/videointro2/videopage/?themestrike_demo=3" target="_blank"><img src="http://plugins.themestrike.com/videointro2/ts-assets/transparent-frame.png" /></a>

						<a href="http://plugins.themestrike.com/videointro2/videopage/?themestrike_demo=4" target="_blank"><img src="http://plugins.themestrike.com/videointro2/ts-assets/frame-with-image-bg.png" /></a>

						<br>
						<br>
						<br>
						<br>

						<a href="http://codecanyon.net/item/video-intro-for-wordpress/6900712?ref=meydjer" target="_blank">Buy Video Intro for WordPress on CodeCanyon</a>.

						<br>
						<br>
						<br>
						<br>


						'
				),				
			),
		);

		$this->sections[] = array(
			'title' => __('Video', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-video',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(
			
				array(
					'id' => 'video_source',
					'type' => 'select',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Video Source', 'themestrike-videointro').'</div>',
					'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('YouTube or Self-Hosted video?', 'themestrike-videointro').'</span>',
					'options' => array(
						'self'    => __( 'Self-Hosted', 'themestrike-videointro' ),
						'youtube' => __( 'YouTube', 'themestrike-videointro' )
						),
					'default' => 'self',
					'desc'    => $this->disabled_msg
				),

				array(
					'id' => 'youtube_id',
					'type' => 'text',
					'title' => __('YouTube Video ID', 'themestrike-videointro'),
					'default' => 'rFvP5IDjl1Y'
				),
				
				array(
					'id' => 'm4v',
					'type' => 'media',
					'url' => true,
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('M4V Video File', 'themestrike-videointro').'</div>',
					'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('<strong>Tip:</strong> You can just change your ".mp4" video extension to ".m4v". <br><br><strong>Warning:</strong> You will need the ".ogv" version too.', 'themestrike-videointro').'</span>',
					// 'compiler' => 'true',
					'readonly' => false,
					'preview' => false,
					'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
					// 'desc' => __('Basic media uploader with disabled URL input field.', 'themestrike-videointro'),
					// 'subtitle' => __('Upload any media using the WordPress native uploader', 'themestrike-videointro'),
					'desc'    => $this->disabled_msg
				),
				
				array(
					'id' => 'ogv',
					'type' => 'media',
					'url' => true,
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('OGV Video File', 'themestrike-videointro').'</div>',
					'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('<strong>Tip</strong>: If don\'t have the ".ogv" version, you can convert your video to ".ogv" at <a href="http://video.online-convert.com/convert-to-ogg" target="_blank">Online-Convert.com</a>. <br><br><strong>Warning:</strong> You will need the ".m4v" version too.', 'themestrike-videointro').'</span>',
					// 'compiler' => 'true',
					'readonly' => false,
					'preview' => false,
					'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
					// 'desc' => __('Basic media uploader with disabled URL input field.', 'themestrike-videointro'),
					// 'subtitle' => __('Upload any media using the WordPress native uploader', 'themestrike-videointro'),
					'desc'    => $this->disabled_msg
				),

				array(
					'id' => 'video_size',
					'type' => 'dimensions',
					//'units' => 'em', // You can specify a unit value. Possible: px, em, %
					//'units_extended' => 'true', // Allow users to select any type of unit
					'title' => __('Video Dimensions', 'themestrike-videointro'),
					'default' => array('width' => 1280, 'height' => 720,)
				),

				array(
				    'id' => 'video_fit',
				    'type' => 'button_set',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('Video Fit', 'themestrike-videointro').'</div>',
				    'options' => array('fill' => __( 'Fill Screen', 'themestrike-videointro' ), 'fit' => __( 'Fit to Screen', 'themestrike-videointro' )), //Must provide key => value pairs for radio options
				    'default' => 'fill',
				    'desc'    => $this->disabled_msg
				),

				array(
					'id' => 'volume',
					'type' => 'spinner',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Volume', 'themestrike-videointro').'</div>',
					"default" => "80",
					"min" => "0",
					"step" => "5",
					"max" => "100",
					'desc'    => $this->disabled_msg
				),

				array(
				    'id' => 'action_after_end',
				    'type' => 'button_set',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('Action after end', 'themestrike-videointro').'</div>',
				    'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('What happens after video end?', 'themestrike-videointro').'</span>',
				    'options' => array(
					    'redirect' => __( 'Redirect', 'themestrike-videointro' ),
					    'loop' => __( 'Loop', 'themestrike-videointro' )
				    ), //Must provide key => value pairs for radio options
				    'default' => 'redirect',
				    'desc'    => $this->disabled_msg
				),
				
			),
		);

		$this->sections[] = array(
			'title' => __('Redirect', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-share',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(

				array(
					'id'      => 'when_redirect_to_video',
					'type'    => 'button_set',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Redirect to Video Page...', 'themestrike-videointro').'</div>',
					'options' => array(
						'always'      => __( 'Always', 'themestrike-videointro' ),
						'first_visit' => __( 'First Visit Only', 'themestrike-videointro' ),
						'never'       => __( 'Never', 'themestrike-videointro' ),
				    	), //Must provide key => value pairs for radio options
				    'default' => 'first_visit',
				    'desc'    => $this->disabled_msg
				),

				array(
					'id'       => 'homepage_url',
					'type'     => 'text',
					'title'    => __('Homepage URL', 'themestrike-videointro'),
					'desc'     => __('The URL of your homepage, or any other page you want to check if it\'s a new user to be redirected to your video page.', 'themestrike-videointro'),
					'validate' => 'url',
					'default'  => get_home_url()
				),

				array(
					'id'       => 'videopage_url',
					'type'     => 'text',
					'title'    => __('Video Page URL', 'themestrike-videointro'),
					'desc'     => __('The URL of your video page (you need to create one, no content is needed).', 'themestrike-videointro'),
					'validate' => 'url',
					'default'  => get_home_url() . '/videopage'
				),

				// array(
				// 	'id' => 'url_to_rewrite',
				// 	'type' => 'text',
				// 	'title' => __('URL to Rewrite', 'themestrike-videointro'),
				// 	'desc' => __('The URL of the page you want to substitute with your video intro.', 'themestrike-videointro'),
				// 	'validate' => 'url',
				// 	'default' => get_home_url()
				// ),

				// array(
				// 	'id' => 'url_to_redirect',
				// 	'type' => 'text',
				// 	'title' => __('Redirect to', 'themestrike-videointro'),
				// 	'desc' => __('The URL of the page you want to redirect after the end of your video or when the visitor click at the Skip Intro button.', 'themestrike-videointro'),
				// 	'validate' => 'url',
				// 	'default' => get_home_url() . '/home'
				// ),

				array(
					'id'    => 'redirect_warning',
					'type'  => 'info',
					'title' => __('Auto redirect', 'themestrike-videointro'),
					'style' => 'warning',
					'desc'  => __('If you want to use this feature, video loop must be disabled. Go to "Video Intro &rarr; Video &rarr; Action after and" to configure it.', 'themestrike-videointro')
				),
				
			),
		);

		$this->sections[] = array(
			'title' => __('Logo', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-star',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(
			
				array(
					'id' => 'logo_img',
					'type' => 'media',
					'url' => true,
					'title' => __('Logo Image', 'themestrike-videointro'),
					'readonly' => false,
					'preview' => true,
					'default' => array('url' => parent::$plugin_url . 'assets/img/your-logo-1.png')
				),

				array(
					'id'             => 'logo_margin',
					'type'           => 'spacing',
					'mode'           => 'margin',
					'units'          => array('px', '%'),
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Logo Margin', 'themestrike-videointro').'</div>',
					'default'            => array(
					    'margin-top'     => '0px', 
					    'margin-right'   => '0px', 
					    'margin-bottom'  => '0px', 
					    'margin-left'    => '0px',
					    'units'          => 'px', 
					),
					'desc'    => $this->disabled_msg
				),

				array(
					'id' => 'logo_align',
					'type' => 'image_select',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Logo Alignment', 'themestrike-videointro').'</div>',
					'options' => array(
						'left' => array('title' => __( 'Left', 'themestrike-videointro' ), 'img' => 'images/align-left.png'),
						'center' => array('title' => __( 'Center', 'themestrike-videointro' ), 'img' => 'images/align-center.png'),
						'right' => array('title' => __( 'Right', 'themestrike-videointro' ), 'img' => 'images/align-right.png')
					), //Must provide key => value(array:title|img) pairs for radio options
					'default' => 'center',
					'presets' => null,
					'desc'    => $this->disabled_msg
				),
				
			),
		);

		$this->sections[] = array(
			'title' => __('Frame', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-check-empty',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(
			
				array(
					'id' => 'frame_border_width',
					'type' => 'spinner',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Frame Border Width', 'themestrike-videointro').'</div>',
					'desc' => __("In pixels. Type \"0\" (zero) if you don't want any frame border.", 'themestrike-videointro') . '<br>' . $this->disabled_msg,
					"default" => "40",
					"min" => "0",
					"step" => "1",
					"max" => "200",
				),

				array(
				    'id' => 'frame_border_radius',
				    'type' => 'spinner',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('Frame Border Radius', 'themestrike-videointro').'</div>',
					'desc' => __("In pixels. Type \"0\" (zero) if you don't want any border radius.", 'themestrike-videointro') . '<br>' . $this->disabled_msg,
				    "default" => "2",
				    "min" => "0",
				    "step" => "1",
				    "max" => "200",

				),
				
				array(
					'id' => 'frame_border_bg',
					'type' => 'background',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Frame Border Background', 'themestrike-videointro').'</div>',
					// 'compiler' => true,
					'default' => array(
						'background-color' => '#FFFFFF',
						'background-image' => '',
						'background-repeat' => '',
						'background-attachment' => '',
						'background-position' => ''
						),
					// 'validate' => 'color',
					'desc'    => $this->disabled_msg
				),

			),
		);

	   $this->sections[] = array(
			'title' => __('Skip Intro Button', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-step-forward',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(

				array(
					'id' => 'skipintro_is_enabled',
					'type' => 'switch',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Skip Intro On/Off', 'themestrike-videointro').'</div>',
					"default" => 1,
					'desc'    => $this->disabled_msg
				),

				array(
				    'id' => 'skipintro_text',
				    'type' => 'text',
				    'title' => __('Skip Intro Text', 'themestrike-videointro'),
				    'default' => __( 'SKIP INTRO TEXT', 'themestrike-videointro' )
				),

				array(
					'id' => 'skipintro_bg_color',
					'type' => 'link_color',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Skip Intro Background Color', 'themestrike-videointro').'</div>',
					'active' => false, // Disable Active Color
					'default' => array(
						'regular' => '#121517',
						'hover' => '#fff'
					),
					'desc'    => $this->disabled_msg
				),
				
				array(
					'id' => 'skipintro_font_color',
					'type' => 'link_color',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Skip Intro Font Color', 'themestrike-videointro').'</div>',
					'active' => false, // Disable Active Color
					'default' => array(
						'regular' => '#fff',
						'hover' => '#121517'
					),
					'desc'    => $this->disabled_msg
				),

				array(
					'id' => 'skipintro_border_radius',
					'type' => 'spinner',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('Skip Intro Border Radius', 'themestrike-videointro').'</div>',
					'desc' => __("In pixels. Type \"0\" (zero) if you don't want border radius.", 'themestrike-videointro') . '<br>' . $this->disabled_msg,
					"default" => "4",
					"min" => "0",
					"step" => "1",
					"max" => "100",
				),

				array(
					'id'    => 'skipintro_info',
					'type'  => 'info',
					'title' => __('Skip Intro URL', 'themestrike-videointro'),
					'style' => 'warning',
					'desc'  => __('The same as "Video Page URL" URL. Go to "Video Intro &rarr; Redirect &rarr; Video Page URL" if you want to configure it.', 'themestrike-videointro')
				),

			),
		);


	   $this->sections[] = array(
			'title' => __('Bottom Text', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-text-width',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(

				array(
				    'id' => 'bottom_text_typography',
				    'type' => 'typography',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('Bottom Text Typography', 'themestrike-videointro').'</div>',
				    //'compiler'=>true, // Use if you want to hook in your own CSS compiler
				    'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				    // 'font-backup' => true, // Select a backup non-google font in addition to a google font
				    //'font-style'=>false, // Includes font-style and weight. Can use font-style or font-weight to declare
				    //'subsets'=>false, // Only appears if google is true and subsets not set to false
				    //'font-size'=>false,
				    'line-height'=>false,
				    //'word-spacing'=>true, // Defaults to false
				    //'letter-spacing'=>true, // Defaults to false
				    //'color'=>false,
				    //'preview'=>false, // Disable the previewer
				    'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				    // 'compiler' => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
				    'units' => 'px', // Defaults to px
				    // 'subtitle' => __('Typography option with each property can be called individually.', 'themestrike-videointro'),
				    'default' => array(
				        'color' => "#121517",
				        'font-style' => '400',
				        'font-family' => 'Arial, Helvetica, sans-serif',
				        'google' => true,
				        'font-size' => '12px'),
				    'desc'    => $this->disabled_msg
				),

				array(
					'id' => 'bottom_text_left',
					'type' => 'editor',
					'title' => __('Bottom Text Left', 'themestrike-videointro'),
					'default' => __( '&copy; Copyright 2014 &ndash; John Doe', 'themestrike-videointro' )
				),

				array(
					'id' => 'bottom_text_right',
					'type' => 'editor',
					'title' => __('Bottom Text Right', 'themestrike-videointro'),
					'default' => __( '1-800-555-9274 &nbsp; <a href="your@email.com">your@email.com</a> &nbsp; <a href="http://twitter.com" target="_blank">Twitter</a> &nbsp; <a href="http://facebook.com" target="_blank">Facebook</a>', 'themestrike-videointro' )
				),

			),
		);


	   $this->sections[] = array(
			'title' => __('CSS', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-css',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(

				array(
				    'id' => 'css_enqueue',
				    'type' => 'textarea',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('CSS Enqueue', 'themestrike-videointro').'</div>',
				    'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('By default, Video Intro for WordPress remove all CSS from queue (only in video intro page). If you want to re-enqueue some stylesheets, write the name of the stylesheets <strong>handles</strong> (one per line).', 'themestrike-videointro').'</span>',
				    'desc' => __('E.g.: <br><br>my-theme-style <br>another-handler <br>one-more-example', 'themestrike-videointro') . '<br>' . $this->disabled_msg
				),

				array(
					'id' => 'custom_css',
					'type' => 'ace_editor',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('CSS Code', 'themestrike-videointro').'</div>',
					'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('Paste your own CSS code here.', 'themestrike-videointro').'</span>',
					'mode' => 'css',
					'theme' => 'monokai',
					'default' => "body {\n\n}",
					'desc'    => $this->disabled_msg
				),

			),
		);

	   $this->sections[] = array(
			'title' => __('JavaScript', 'themestrike-videointro'),
			// 'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'themestrike-videointro'),
			'icon' => 'el-icon-puzzle',
			// 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
			'fields' => array(

				array(
				    'id' => 'js_enqueue',
				    'type' => 'textarea',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('JavaScript Enqueue', 'themestrike-videointro').'</div>',
				    'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('By default, Video Intro for WordPress remove all JS from queue (only in video intro page). If you want to re-enqueue some scripts, write the name of the scripts <strong>handles</strong> (one per line).', 'themestrike-videointro').'</span>',
				    'desc' => __('E.g.: <br><br>my-theme-script <br>another-handler <br>one-more-example', 'themestrike-videointro') . '<br>' . $this->disabled_msg
				),

				array(
					'id' => 'custom_js',
					'type' => 'ace_editor',
					'title' => '<div class="ts-videointrolite-disabled-title">'.__('JS Code', 'themestrike-videointro').'</div>',
					'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('Paste your own JS code here.', 'themestrike-videointro').'</span>',
					'mode' => 'javascript',
					'theme' => 'monokai',
					'default' => "jQuery(document).ready(function($){\n\n});",
					'desc'    => $this->disabled_msg
				),

				array(
				    'id' => 'tracking_code',
				    'type' => 'textarea',
				    'title' => '<div class="ts-videointrolite-disabled-title">'.__('Tracking Code', 'themestrike-videointro').'</div>',
				    'subtitle' =>'<span class="ts-videointrolite-disabled-subtitle">'.__('Paste your Google Analytics (or other) tracking code here <strong style="color:red">WITH &lt;script> tags</strong>. This will be added into the footer template.', 'themestrike-videointro').'</span>',
				    'desc'    => $this->disabled_msg
				),

			),
		);



		$theme_info = '<div class="redux-framework-section-desc">';
		$theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'themestrike-videointro') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
		$theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'themestrike-videointro') . $this->theme->get('Author') . '</p>';
		$theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'themestrike-videointro') . $this->theme->get('Version') . '</p>';
		$theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
		$tabs = $this->theme->get('Tags');
		if (!empty($tabs)) {
			$theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'themestrike-videointro') . implode(', ', $tabs) . '</p>';
		}
		$theme_info .= '</div>';

		if (file_exists(dirname(__FILE__) . '/../README.md')) {
			$this->sections['theme_docs'] = array(
				'icon' => 'el-icon-list-alt',
				'title' => __('Documentation', 'themestrike-videointro'),
				'fields' => array(
					array(
						'id' => '17',
						'type' => 'raw',
						'markdown' => true,
						'content' => file_get_contents(dirname(__FILE__) . '/../README.md')
					),
				),
			);
		}//if
				$this->sections[] = array(
			'type' => 'divide',
		);

		if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
			$tabs['docs'] = array(
				'icon' => 'el-icon-book',
				'title' => __('Documentation', 'themestrike-videointro'),
				'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
			);
		}
	}

	public function setHelpTabs() {

		// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
		$this->args['help_tabs'][] = array(
			'id' => 'redux-opts-1',
			'title' => __('Theme Information 1', 'themestrike-videointro'),
			'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'themestrike-videointro')
		);

		$this->args['help_tabs'][] = array(
			'id' => 'redux-opts-2',
			'title' => __('Theme Information 2', 'themestrike-videointro'),
			'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'themestrike-videointro')
		);

		// Set the help sidebar
		$this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'themestrike-videointro');
	}

	/**

	  All the possible arguments for Redux.
	  For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

	 * */
	public function setArguments() {

		$this->args = array(
			// TYPICAL -> Change these values as you need/desire
			'display_name' => parent::$plugin_name, // Name that appears at the top of your panel
			'display_version' => 'v. ' . parent::$plugin_v, // Version that appears at the top of your panel
			'opt_name' => 'ts_videointro', // This is where your data is stored in the database and also becomes your global variable name.
			'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
			'allow_sub_menu' => true, // Show the sections below the admin menu item or not
			'menu_title' => __('Video Intro', 'themestrike-videointro'),
			'page' => __('Video Intro', 'themestrike-videointro'),
			'google_api_key' => 'AIzaSyDokGWTGskEh0tkCICgYQfZg7bXeKJ8nQg', // Must be defined to add google fonts to the typography module
			'admin_bar' => false, // Show the panel pages on the admin bar
			'global_variable' => 'ts_videointro', // Set a different name for your global variable other than the opt_name
			'dev_mode' => false, // Show the time the page took to load, etc
			'customizer' => true, // Enable basic customizer support
			// OPTIONAL -> Give you extra features
			'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
			'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
			'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
			'menu_icon' => 'dashicons-video-alt3', // Specify a custom URL to an icon
			'last_tab' => '', // Force your panel to always open to a specific tab (by id)
			'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
			'page_slug' => '_videointro', // Page slug used to denote the panel
			'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
			'default_show' => false, // If true, shows the default value next to each field that is not the default value.
			'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
			// CAREFUL -> These options are for advanced use only
			'transient_time' => 60 * MINUTE_IN_SECONDS,
			'output' => false, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
			'output_tag' => false, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
			//'domain'              => 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
			//'footer_credit'       => '', // Disable the footer credit of Redux. Please leave if you can help it.
			// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
			'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
			'show_import_export' => true, // REMOVE
			'system_info' => false, // REMOVE
			'help_tabs' => array(),
			'help_sidebar' => '', // __( '', $this->args['domain'] );            
		);


		// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.     
		// $this->args['share_icons'][] = array(
		// 	'url' => 'https://github.com/ReduxFramework/ReduxFramework',
		// 	'title' => 'Visit us on GitHub',
		// 	'icon' => 'el-icon-github'
		// 		// 'img' => '', // You can use icon OR img. IMG needs to be a full URL.
		// );
		// $this->args['share_icons'][] = array(
		// 	'url' => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
		// 	'title' => 'Like us on Facebook',
		// 	'icon' => 'el-icon-facebook'
		// );
		// $this->args['share_icons'][] = array(
		// 	'url' => 'http://twitter.com/reduxframework',
		// 	'title' => 'Follow us on Twitter',
		// 	'icon' => 'el-icon-twitter'
		// );
		// $this->args['share_icons'][] = array(
		// 	'url' => 'http://www.linkedin.com/company/redux-framework',
		// 	'title' => 'Find us on LinkedIn',
		// 	'icon' => 'el-icon-linkedin'
		// );



		// Panel Intro text -> before the form
		if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
			if (!empty($this->args['global_variable'])) {
				$v = $this->args['global_variable'];
			} else {
				$v = str_replace("-", "_", $this->args['opt_name']);
			}
			// $this->args['intro_text'] = sprintf(__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'themestrike-videointro'), $v);
		} else {
			// $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'themestrike-videointro');
		}

		// Add content after the form.
		// $this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'themestrike-videointro');
	}

	public function validate_callback_function( $field, $value, $existing_value ) {
	    $error = true;
	    $value = 'just testing';

	    /*
	  do your validation

	  if(something) {
	    $value = $value;
	  } elseif(something else) {
	    $error = true;
	    $value = $existing_value;
	    
	  }
	 */

	    $return['value'] = $value;
	    $field['msg']    = 'your custom error message';
	    if ( $error == true ) {
	        $return['error'] = $field;
	    }

	    return $return;
	}

	public function class_field_callback( $field, $value ) {
	    print_r( $field );
	    echo '<br/>CLASS CALLBACK';
	    print_r( $value );
	}


}


/**
 * Custom function for the callback referenced above
 */
if ( ! function_exists( 'redux_my_custom_field' ) ):
    function redux_my_custom_field( $field, $value ) {
        print_r( $field );
        echo '<br/>';
        print_r( $value );
    }
endif;

/**
 * Custom function for the callback validation referenced above
 * */
if ( ! function_exists( 'redux_validate_callback_function' ) ):
    function redux_validate_callback_function( $field, $value, $existing_value ) {
        $error = true;
        $value = 'just testing';

        /*
      do your validation

      if(something) {
        $value = $value;
      } elseif(something else) {
        $error = true;
        $value = $existing_value;
        
      }
     */

        $return['value'] = $value;
        $field['msg']    = 'your custom error message';
        if ( $error == true ) {
            $return['error'] = $field;
        }

        return $return;
    }
endif;