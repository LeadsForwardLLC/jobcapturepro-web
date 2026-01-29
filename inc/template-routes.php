<?php
/**
 * Template Routing & Fallbacks
 * Handle SPA-style routing and fallback templates
 *
 * @package JCP_Core
 */

/**
 * Fallback template routing for non-WordPress pages
 * Allows /demo, /pricing, etc. to render even if pages don't exist in WordPress
 *
 * @return void
 */
function jcp_core_fallback_template_routes(): void {
    if ( ! is_404() ) {
        return;
    }

    $path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
    $template_map = [
        'demo'                 => 'page-demo.php',
        'pricing'              => 'page-pricing.php',
        'early-access'         => 'page-early-access.php',
        'early-access-success' => 'page-early-access-success.php',
        'directory'            => 'page-directory.php',
        'estimate'             => 'page-estimate.php',
        'company'              => 'single-jcp_company.php',
        'design-system'        => 'page-design-system.php',
        'ui-library'           => 'page-ui-library.php',
    ];

    if ( ! isset( $template_map[ $path ] ) ) {
        return;
    }

    $template_path = trailingslashit( get_stylesheet_directory() ) . $template_map[ $path ];
    if ( ! file_exists( $template_path ) ) {
        return;
    }

    global $wp_query;
    $wp_query->is_404 = false;
    status_header( 200 );
    include $template_path;
    exit;
}

add_action( 'template_redirect', 'jcp_core_fallback_template_routes' );
