<?php
/**
 * Template Routing & Fallbacks
 * Handle SPA-style routing and fallback templates.
 *
 * DIAGNOSIS (directory / profile rendering):
 * - /directory: Served by 404 fallback (path 'directory') → page-directory.php.
 *   Template uses get_header(), <div id="jcp-app" data-jcp-page="directory">, get_footer().
 *   JS (jcp-render.js) fetches assets/directory/index.html and injects into #jcp-app.
 *   Data: JCP_DIRECTORY_DATA from inc/enqueue.php (get_posts jcp_company + demo seed).
 * - /company and /company?id=xxx: 404 fallback (path 'company') → single-jcp_company.php.
 *   Same shell; JS loads assets/directory/profile.html. Data: JCP_PROFILE_DATA when real
 *   jcp_company post; demo IDs use ?id= (no WP post). jcp_company CPT used by theme;
 *   registration not in theme/jobcapturepro-plugin (may be ACF/mu-plugins).
 * - Controlling files: inc/template-routes.php (routing), page-directory.php,
 *   single-jcp_company.php, inc/enqueue.php, assets/js/core/jcp-render.js.
 * - No plugin template override; theme owns directory/profile rendering.
 *
 * Directory and company now use rewrite rules + template_include so they are served
 * as 200 with correct title (Rank Math / Yoast compatible) without going through 404.
 *
 * @package JCP_Core
 */

/**
 * Register query var and rewrite rules for directory and company (WordPress-native routing).
 *
 * @return void
 */
function jcp_core_register_directory_routes(): void {
    add_rewrite_rule( '^directory/?$', 'index.php?jcp_route=directory', 'top' );
    add_rewrite_rule( '^directory/([^/]+)/?$', 'index.php?jcp_route=company&jcp_company_slug=$matches[1]', 'top' );
    add_rewrite_rule( '^company/?$', 'index.php?jcp_route=company', 'top' );
}

/**
 * Register jcp_route and jcp_company_slug query vars.
 *
 * @param array $vars Existing query vars.
 * @return array
 */
function jcp_core_register_route_query_var( array $vars ): array {
    $vars[] = 'jcp_route';
    $vars[] = 'jcp_company_slug';
    return $vars;
}

/**
 * Serve directory/company with 200 and correct title (no 404).
 * Sets document title and Rank Math title/canonical so Directory never shows Blog title.
 *
 * @return void
 */
function jcp_core_directory_route_template_redirect(): void {
    $route = get_query_var( 'jcp_route', '' );
    if ( $route !== 'directory' && $route !== 'company' ) {
        return;
    }

    global $wp_query;
    $wp_query->is_404 = false;
    status_header( 200 );

    $titles = [
        'directory' => __( 'Directory', 'jcp-core' ),
        'company'   => __( 'Company Profile', 'jcp-core' ),
    ];
    if ( isset( $titles[ $route ] ) ) {
        $page_title = $titles[ $route ];
        add_filter(
            'document_title_parts',
            function ( $parts ) use ( $page_title ) {
                $parts['title'] = $page_title;
                return $parts;
            },
            999,
            1
        );
        // Rank Math outputs its own title/canonical from the main query (often blog). Override for directory/company.
        add_filter(
            'rank_math/frontend/title',
            function ( $title ) use ( $page_title ) {
                return $page_title . ' - ' . get_bloginfo( 'name' );
            },
            10,
            1
        );
        add_filter(
            'rank_math/frontend/canonical',
            function ( $canonical ) use ( $route ) {
                if ( $route === 'directory' ) {
                    return home_url( '/directory/' );
                }
                if ( $route === 'company' ) {
                    $slug = get_query_var( 'jcp_company_slug', '' );
                    if ( $slug !== '' ) {
                        return home_url( '/directory/' . $slug . '/' );
                    }
                    if ( isset( $_GET['id'] ) && is_string( $_GET['id'] ) ) {
                        $id = sanitize_text_field( wp_unslash( $_GET['id'] ) );
                        if ( $id !== '' ) {
                            return home_url( '/directory/' . $id . '/' );
                        }
                    }
                    return home_url( '/company/' );
                }
                return $canonical;
            },
            10,
            1
        );
    }
}

/**
 * Load directory or company template when jcp_route is set.
 *
 * @param string $template Current template path.
 * @return string
 */
function jcp_core_directory_route_template_include( string $template ): string {
    $route = get_query_var( 'jcp_route', '' );
    if ( $route === 'directory' ) {
        $path = get_stylesheet_directory() . '/page-directory.php';
        if ( file_exists( $path ) ) {
            return $path;
        }
    }
    if ( $route === 'company' ) {
        $path = get_stylesheet_directory() . '/single-jcp_company.php';
        if ( file_exists( $path ) ) {
            return $path;
        }
    }
    return $template;
}

add_action( 'init', 'jcp_core_register_directory_routes' );
add_filter( 'query_vars', 'jcp_core_register_route_query_var' );
add_action( 'template_redirect', 'jcp_core_directory_route_template_redirect' );
add_filter( 'template_include', 'jcp_core_directory_route_template_include', 5 );

/**
 * Force prototype templates by route path.
 *
 * This protects live environments where the WP page/template assignment for
 * /prototype or /wp-plugin-prototype may be missing or incorrect.
 *
 * @param string $template Current template path.
 * @return string
 */
function jcp_core_force_prototype_templates( string $template ): string {
    $path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
    $segment = strpos( $path, '/' ) !== false ? strtok( $path, '/' ) : $path;

    if ( $segment === 'prototype' ) {
        $forced = get_stylesheet_directory() . '/page-prototype.php';
        if ( file_exists( $forced ) ) {
            return $forced;
        }
    }

    if ( $segment === 'wp-plugin-prototype' ) {
        $forced = get_stylesheet_directory() . '/page-wp-plugin-prototype.php';
        if ( file_exists( $forced ) ) {
            return $forced;
        }
    }

    return $template;
}
add_filter( 'template_include', 'jcp_core_force_prototype_templates', 4 );

/**
 * Flush rewrite rules on theme switch so directory/company rules take effect.
 *
 * @return void
 */
function jcp_core_flush_rewrite_rules_on_switch(): void {
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'jcp_core_flush_rewrite_rules_on_switch' );

/**
 * Fallback template routing for non-WordPress pages
 * Allows /demo, /pricing, etc. to render even if pages don't exist in WordPress.
 * Directory and company are handled by rewrite + template_include above (not 404).
 *
 * @return void
 */
function jcp_core_fallback_template_routes(): void {
    if ( ! is_404() ) {
        return;
    }

    $path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
    $path_segment = strpos( $path, '/' ) !== false ? strtok( $path, '/' ) : $path;

    $template_map = [
        'demo'                 => 'page-demo.php',
        'pricing'              => 'page-pricing.php',
        'early-access'         => 'page-early-access.php',
        'early-access-success' => 'page-early-access-success.php',
        'contact'              => 'page-contact.php',
        'contact-success'     => 'page-contact-success.php',
        'estimate'             => 'page-estimate.php',
        'ui-library'           => 'page-ui-library.php',
    ];
    if ( $path_segment === 'directory' || $path_segment === 'company' ) {
        return;
    }

    if ( ! isset( $template_map[ $path_segment ] ) ) {
        return;
    }

    $template_path = trailingslashit( get_stylesheet_directory() ) . $template_map[ $path_segment ];
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
        'estimate'             => __( 'Estimate', 'jcp-core' ),
        'ui-library'           => __( 'UI Library', 'jcp-core' ),
    ];
    if ( isset( $route_titles[ $path_segment ] ) ) {
        add_filter(
            'document_title_parts',
            function ( $parts ) use ( $route_titles, $path_segment ) {
                $parts['title'] = $route_titles[ $path_segment ];
                return $parts;
            },
            999,
            1
        );
    }

    include $template_path;
    exit;
}

add_action( 'template_redirect', 'jcp_core_fallback_template_routes' );
