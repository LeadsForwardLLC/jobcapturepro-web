<?php
/**
 * Template Name: App Prototype
 *
 * Minimal page: no header, no footer. Only the phone simulator centered.
 * Full interactive app (no "Start Demo" or guided tour). Internal use only.
 *
 * @package JCP_Core
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<?php wp_head(); ?>
	<style>
		body.jcp-prototype-page {
			margin: 0;
			padding: 0;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: #ffffff;
		}
		body.jcp-prototype-page .demo-container {
			width: 100%;
			max-width: 100%;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			padding: 0;
		}
		body.jcp-prototype-page .phone-wrapper {
			margin: 0;
		}
		body.jcp-prototype-page .right-panel,
		body.jcp-prototype-page .tour-dock,
		body.jcp-prototype-page #tour-float,
		body.jcp-prototype-page #tour-bubble,
		body.jcp-prototype-page .mobile-stepper,
		body.jcp-prototype-page #post-demo-panel,
		body.jcp-prototype-page #post-demo-bubble,
		body.jcp-prototype-page #directory-hint,
		body.jcp-prototype-page .demo-mode-indicator {
			display: none !important;
		}
	</style>
</head>
<body <?php body_class( 'jcp-prototype-page' ); ?>>
<div id="jcp-app" data-jcp-page="prototype" data-demo-mode="false"></div>
<?php wp_footer(); ?>
</body>
</html>
