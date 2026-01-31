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
        'contact'              => 'page-contact.php',
        'contact-success'     => 'page-contact-success.php',
        'directory'            => 'page-directory.php',
        'estimate'             => 'page-estimate.php',
        'company'              => 'single-jcp_company.php',
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

    $route_titles = [
        'demo'                 => __( 'Demo', 'jcp-core' ),
        'pricing'              => __( 'Pricing', 'jcp-core' ),
        'early-access'         => __( 'Early Access', 'jcp-core' ),
        'early-access-success' => __( 'You\'re on the list', 'jcp-core' ),
        'contact'              => __( 'Contact', 'jcp-core' ),
        'contact-success'      => __( 'Message sent', 'jcp-core' ),
        'directory'            => __( 'Directory', 'jcp-core' ),
        'estimate'             => __( 'Estimate', 'jcp-core' ),
        'company'              => __( 'Company Profile', 'jcp-core' ),
        'ui-library'           => __( 'UI Library', 'jcp-core' ),
    ];
    if ( isset( $route_titles[ $path ] ) ) {
        add_filter(
            'document_title_parts',
            function ( $parts ) use ( $route_titles, $path ) {
                $parts['title'] = $route_titles[ $path ];
                return $parts;
            },
            10,
            1
        );
    }

    include $template_path;
    exit;
}

add_action( 'template_redirect', 'jcp_core_fallback_template_routes' );
