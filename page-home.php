<?php
/**
 * Template Name: Home
 *
 * Homepage template. Renders the homepage via JavaScript (data-jcp-page="home").
 * Assign this template to the static front page in Settings > Reading.
 * No hero block or WP title/content customization—app renders full page.
 *
 * @package JCP_Core
 */

get_header();
?>
<div id="jcp-app" data-jcp-page="home"></div>
<?php
get_footer();
