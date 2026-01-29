<?php
/**
 * Global Header Template
 * Renders the opening HTML, head, and body tags
 *
 * @package JCP_Core
 */
$pages = jcp_core_get_page_detection();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'jcp-global-nav-active' ); ?>>
  <?php get_template_part( 'templates/partials/nav' ); ?>
  <div class="jcp-shell">
