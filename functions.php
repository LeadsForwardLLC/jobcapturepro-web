<?php
/**
 * JobCapturePro Theme Bootstrap
 * Loads all modular theme functionality from /inc/ directory
 *
 * @package JCP_Core
 */

// Load helper functions (asset paths, URLs, ACF helpers)
require_once get_template_directory() . '/inc/helpers.php';

// Load company data functions
require_once get_template_directory() . '/inc/company-data.php';

// Load asset enqueuing logic
require_once get_template_directory() . '/inc/enqueue.php';

// Load template routing
require_once get_template_directory() . '/inc/template-routes.php';

// Load ACF configuration (if ACF is available)
require_once get_template_directory() . '/inc/acf-config.php';

// REST: Early Access form → GHL webhook
require_once get_template_directory() . '/inc/rest-early-access.php';

// Load design system page setup
require_once get_template_directory() . '/inc/design-system-setup.php';

/**
 * Theme setup: text domain and SEO-safe document title.
 */
function jcp_core_theme_setup(): void {
	load_theme_textdomain( 'jcp-core', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'jcp_core_theme_setup' );

/**
 * Remove tailwind.min.css if it's being enqueued (prevents 404 errors)
 */
function jcp_core_remove_tailwind() {
    wp_dequeue_style( 'tailwind' );
    wp_deregister_style( 'tailwind' );
    wp_dequeue_style( 'tailwindcss' );
    wp_deregister_style( 'tailwindcss' );
    wp_dequeue_style( 'tailwind.min.css' );
    wp_deregister_style( 'tailwind.min.css' );
}
add_action( 'wp_enqueue_scripts', 'jcp_core_remove_tailwind', 999 );
