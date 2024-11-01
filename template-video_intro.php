<?php global $ts_videointro ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<?php
	$video_code = new Themestrike_VideoIntroLITE_GetVideoCode();
	$video_code->get_video_code();
	?></head>

<body <?php body_class('ts-videointro-body'); ?>>
<div class="clearfix"></div>


	<input id="ts-videointro-width" type="hidden" value="<?php echo str_replace('px', '', $ts_videointro['video_size']['width']) ?>">
	<input id="ts-videointro-height" type="hidden" value="<?php echo str_replace('px', '', $ts_videointro['video_size']['height']) ?>">
	<input id="ts-videointro-videofit" type="hidden" value="fill">

	<input id="ts-videointro-frameborderwidth" type="hidden" value="40">

	<?php if ( trim($ts_videointro['logo_img']['url']) ) : ?>
		<div class="ts-videointro-logo-wrapper">
			<a href="<?php echo $ts_videointro['homepage_url'] ?>"><img src="<?php echo $ts_videointro['logo_img']['url'] ?>" alt="<?php echo get_bloginfo('name'); ?>"></a>
		</div><!-- /.ts-videointro-logo-wrapper -->
	<?php endif ?>

	<div class="ts-videointro-viewport">
		<div class="ts-videointro-skipintro-wrapper">
			<a href="<?php echo $ts_videointro['homepage_url'] ?>" class="ts-videointro-skipintro-link"><span class="ts-videointro-skipintro-text"><?php echo $ts_videointro['skipintro_text'] ?></span></a>
		</div><!-- /.ts-videointro-skipintro-wrapper -->

		<div class="ts-videointro-wrapper">
			<div class="ts-videointro-border"></div>
			<div id="ts-videointro-player-main" class="ts-videointro-player"></div>
		</div><!-- /.ts-videointro-wrapper -->

		<div class="ts-videointro-loader"></div>
	</div><!-- /.ts-videointro-viewport -->

	<div class="ts-videointro-bottomtext-left ts-videointro-bottomtext">
		<?php echo do_shortcode($ts_videointro['bottom_text_left']) ?>
	</div><!-- /.ts-videointro-bottomtext-left -->
	<div class="ts-videointro-bottomtext-right ts-videointro-bottomtext">
		<?php echo do_shortcode($ts_videointro['bottom_text_right']) ?>
	</div><!-- /.ts-videointro-bottomtext-right -->

	<?php wp_footer(); ?>
</body>
</html>