<?php
/**
 * Asset Enqueuing
 * Conditional CSS/JS loading per page
 *
 * @package JCP_Core
 */

/**
 * Enqueue CSS and JS based on current page
 *
 * @return void
 */
function jcp_core_enqueue_assets(): void {
    $pages = jcp_core_get_page_detection();
    $render_handle = 'jcp-core-render';
    $render_deps = [];

    $is_marketing = $pages['is_home'] || $pages['is_pricing'] || $pages['is_early_access'] || $pages['is_contact'];

    // Always load navigation JS
    jcp_core_enqueue_script( 'jcp-core-nav', 'js/core/jcp-nav.js' );

    // UI Library page (internal documentation - shows all components)
    if ( $pages['is_ui_library'] ) {
        jcp_core_enqueue_style( 'jcp-core-base', 'css/base.css' );
        jcp_core_enqueue_style( 'jcp-core-layout', 'css/layout.css', [ 'jcp-core-base' ] );
        jcp_core_enqueue_style( 'jcp-core-buttons', 'css/buttons.css', [ 'jcp-core-layout' ] );
        jcp_core_enqueue_style( 'jcp-core-components', 'css/components.css', [ 'jcp-core-buttons' ] );
        jcp_core_enqueue_style( 'jcp-core-utilities', 'css/utilities.css', [ 'jcp-core-components' ] );
        jcp_core_enqueue_style( 'jcp-core-sections', 'css/sections.css', [ 'jcp-core-components' ] );
        jcp_core_enqueue_style( 'jcp-core-home', 'css/pages/home.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_style( 'jcp-core-blog', 'css/pages/blog.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_style( 'jcp-core-pricing', 'css/pages/pricing.css', [ 'jcp-core-sections' ] );
        return;
    }

    // Load base CSS with all design system variables on all pages
    jcp_core_enqueue_style( 'jcp-core-base', 'css/base.css' );

    // Marketing pages: full design system
    if ( $is_marketing ) {
        jcp_core_enqueue_style( 'jcp-core-layout', 'css/layout.css', [ 'jcp-core-base' ] );
        jcp_core_enqueue_style( 'jcp-core-buttons', 'css/buttons.css', [ 'jcp-core-layout' ] );
        jcp_core_enqueue_style( 'jcp-core-components', 'css/components.css', [ 'jcp-core-buttons' ] );
        jcp_core_enqueue_style( 'jcp-core-utilities', 'css/utilities.css', [ 'jcp-core-components' ] );
        // Shared section styles (FAQ, Final CTA, etc.) for all marketing pages
        jcp_core_enqueue_style( 'jcp-core-sections', 'css/sections.css', [ 'jcp-core-components' ] );
    } else {
        // Other pages: include layout so .jcp-container works (page.php, blog, single)
        jcp_core_enqueue_style( 'jcp-core-layout', 'css/layout.css', [ 'jcp-core-base' ] );
        jcp_core_enqueue_style( 'jcp-core-buttons', 'css/buttons.css', [ 'jcp-core-layout' ] );
        jcp_core_enqueue_style( 'jcp-core-components', 'css/components.css', [ 'jcp-core-buttons' ] );
        jcp_core_enqueue_style( 'jcp-core-utilities', 'css/utilities.css', [ 'jcp-core-components' ] );
    }

    // Page-specific assets
    if ( $pages['is_home'] ) {
        // Homepage uses sections.css (already loaded for marketing pages) + page-specific overrides
        jcp_core_enqueue_style( 'jcp-core-home', 'css/pages/home.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_script( 'jcp-core-home', 'js/pages/home.js' );
        $render_deps[] = 'jcp-core-home';
        $home_ctas = [
            'primary_text'   => 'View the live demo',
            'primary_url'    => '/demo',
            'secondary_text' => 'Learn how it works',
            'secondary_url'  => '#how-it-works',
        ];
        wp_localize_script( 'jcp-core-home', 'JCP_HOME_HERO_CTAS', $home_ctas );
    }

    if ( $pages['is_pricing'] ) {
        // FAQ styles now come from css/sections.css (enqueued above for all marketing pages)
        jcp_core_enqueue_style( 'jcp-core-pricing', 'css/pages/pricing.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_script( 'jcp-shared-faq', 'js/features/faq.js' );
        jcp_core_enqueue_script( 'jcp-core-pricing', 'js/pages/pricing.js', [ 'jcp-shared-faq' ] );
        $render_deps[] = 'jcp-core-pricing';
    }

    if ( $pages['is_early_access'] ) {
        // Final CTA styles now come from css/sections.css (enqueued above for all marketing pages)
        jcp_core_enqueue_style( 'jcp-core-early-access', 'css/pages/early-access.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_script( 'jcp-core-early-access', 'js/pages/early-access.js' );
        $render_deps[] = 'jcp-core-early-access';
        $ea_config = function_exists( 'jcp_core_get_early_access_form_config' ) ? jcp_core_get_early_access_form_config() : [];
        $ea_config['rest_url'] = rest_url( 'jcp/v1/early-access-submit' );
        wp_localize_script( 'jcp-core-early-access', 'JCP_EARLY_ACCESS_FORM', $ea_config );
    }

    if ( $pages['is_early_access_success'] ) {
        jcp_core_enqueue_style( 'jcp-core-sections', 'css/sections.css', [ 'jcp-core-components' ] );
        jcp_core_enqueue_style( 'jcp-core-early-access', 'css/pages/early-access.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_script( 'jcp-core-early-access-success', 'js/pages/early-access-success.js' );
        wp_localize_script( 'jcp-core-early-access-success', 'JCP_DEMO_CONVERSION', [
            'rest_url' => rest_url( 'jcp/v1/demo-event' ),
        ] );
    }

    if ( $pages['is_contact_success'] ) {
        jcp_core_enqueue_style( 'jcp-core-sections', 'css/sections.css', [ 'jcp-core-components' ] );
        jcp_core_enqueue_style( 'jcp-core-contact', 'css/pages/contact.css', [ 'jcp-core-sections' ] );
    }

    if ( $pages['is_contact'] ) {
        jcp_core_enqueue_style( 'jcp-core-contact', 'css/pages/contact.css', [ 'jcp-core-sections' ] );
        jcp_core_enqueue_script( 'jcp-core-contact', 'js/pages/contact.js' );
        $render_deps[] = 'jcp-core-contact';
        wp_localize_script( 'jcp-core-contact', 'JCP_CONTACT_FORM', [
            'rest_url'         => rest_url( 'jcp/v1/contact-submit' ),
            'success_redirect' => home_url( '/contact-success/' ),
        ] );
    }

    if ( $pages['is_blog'] || $pages['is_single'] || $pages['is_page'] ) {
        // Blog and standard page: ensure sections loaded for blog.css dependency
        if ( ! $is_marketing ) {
            jcp_core_enqueue_style( 'jcp-core-sections', 'css/sections.css', [ 'jcp-core-components' ] );
        }
        jcp_core_enqueue_style( 'jcp-core-blog', 'css/pages/blog.css', [ 'jcp-core-sections' ] );
    }

    // Always load render dispatcher
    jcp_core_enqueue_script( $render_handle, 'js/core/jcp-render.js', $render_deps );

    // Global JS config
    $globals = "window.JCP_ENV = 'live';\n";
    $globals .= "window.JCP_CONFIG = { env: 'live', baseUrl: '" . esc_url_raw( site_url() ) . "' };\n";
    $globals .= "window.JCP_ASSET_BASE = '" . esc_url_raw( get_stylesheet_directory_uri() . '/assets' ) . "';";
    wp_add_inline_script( $render_handle, $globals, 'before' );

    // Demo page
    if ( $pages['is_demo'] ) {
        $demo_mode = isset( $_GET['mode'] ) && $_GET['mode'] === 'run'; // phpcs:ignore
        jcp_core_enqueue_style( 'jcp-core-demo', 'css/pages/demo.css' );
        if ( $demo_mode ) {
            jcp_core_enqueue_style( 'jcp-core-leaflet', 'demo/leaflet/leaflet.css', [ 'jcp-core-demo' ] );
            jcp_core_enqueue_script( 'jcp-core-leaflet', 'demo/leaflet/leaflet.js', [ $render_handle ] );
            jcp_core_enqueue_script( 'jcp-core-demo', 'js/features/demo/jcp-demo.js', [ 'jcp-core-leaflet' ] );
            wp_localize_script( 'jcp-core-demo', 'JCP_DEMO_EVENT', [
                'rest_url' => rest_url( 'jcp/v1/demo-event' ),
            ] );
        } else {
            jcp_core_enqueue_style( 'jcp-core-survey', 'css/pages/survey.css', [ 'jcp-core-demo' ] );
            jcp_core_enqueue_script( 'jcp-core-survey', 'js/pages/survey.js', [ $render_handle ] );
            wp_localize_script( 'jcp-core-survey', 'JCP_DEMO_SURVEY', [
                'rest_url'       => rest_url( 'jcp/v1/demo-survey-submit' ),
                'rest_viewed_url' => rest_url( 'jcp/v1/demo-viewed-submit' ),
                'rest_event_url' => rest_url( 'jcp/v1/demo-event' ),
            ] );
        }
        return;
    }

    // Directory page
    if ( $pages['is_directory'] ) {
        jcp_core_enqueue_style( 'jcp-core-demo', 'css/pages/demo.css' );
        // directory-trust.css merged into directory-consolidated.css
        jcp_core_enqueue_style( 'jcp-core-directory', 'css/pages/directory-consolidated.css', [ 'jcp-core-utilities' ] );
        jcp_core_enqueue_script( 'jcp-core-directory', 'js/features/directory/directory.js', [ $render_handle ] );

        // Fetch all companies
        $companies = get_posts(
            [
                'post_type'      => 'jcp_company',
                'post_status'    => 'publish',
                'numberposts'    => -1,
            ]
        );

        $listings = [];
        foreach ( $companies as $company ) {
            $listings[] = jcp_core_company_data( $company );
        }

        // Add demo companies if we have fewer than 10 listings
        if ( count( $listings ) < 10 ) {
            $demo_companies = [
                [
                    'id'             => 'demo-1',
                    'wpId'           => 0,
                    'name'           => 'Summit Roofing',
                    'service'        => 'Roofing',
                    'city'           => 'Houston, TX',
                    'badge'          => 'verified',
                    'rating'         => '4.9',
                    'reviews'        => 126,
                    'jobs'           => 42,
                    'activity'       => 'Very Active',
                    'lastJobDaysAgo' => 2,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-1',
                ],
                [
                    'id'             => 'demo-2',
                    'wpId'           => 0,
                    'name'           => 'Elite Plumbing Services',
                    'service'        => 'Plumbing',
                    'city'           => 'Dallas, TX',
                    'badge'          => 'trusted',
                    'rating'         => '4.8',
                    'reviews'        => 89,
                    'jobs'           => 67,
                    'activity'       => 'Very Active',
                    'lastJobDaysAgo' => 1,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-2',
                ],
                [
                    'id'             => 'demo-3',
                    'wpId'           => 0,
                    'name'           => 'Premier HVAC Solutions',
                    'service'        => 'HVAC',
                    'city'           => 'Austin, TX',
                    'badge'          => 'verified',
                    'rating'         => '4.7',
                    'reviews'        => 54,
                    'jobs'           => 38,
                    'activity'       => 'Active',
                    'lastJobDaysAgo' => 3,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-3',
                ],
                [
                    'id'             => 'demo-4',
                    'wpId'           => 0,
                    'name'           => 'Apex Electrical Contractors',
                    'service'        => 'Electrical',
                    'city'           => 'San Antonio, TX',
                    'badge'          => 'verified',
                    'rating'         => '4.9',
                    'reviews'        => 112,
                    'jobs'           => 51,
                    'activity'       => 'Very Active',
                    'lastJobDaysAgo' => 0,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-4',
                ],
                [
                    'id'             => 'demo-5',
                    'wpId'           => 0,
                    'name'           => 'Coastal General Contractors',
                    'service'        => 'General Contractor',
                    'city'           => 'Houston, TX',
                    'badge'          => 'trusted',
                    'rating'         => '4.8',
                    'reviews'        => 203,
                    'jobs'           => 89,
                    'activity'       => 'Very Active',
                    'lastJobDaysAgo' => 1,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-5',
                ],
                [
                    'id'             => 'demo-6',
                    'wpId'           => 0,
                    'name'           => 'Precision Roofing & Repair',
                    'service'        => 'Roofing',
                    'city'           => 'Dallas, TX',
                    'badge'          => 'verified',
                    'rating'         => '4.6',
                    'reviews'        => 45,
                    'jobs'           => 28,
                    'activity'       => 'Active',
                    'lastJobDaysAgo' => 4,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-6',
                ],
                [
                    'id'             => 'demo-7',
                    'wpId'           => 0,
                    'name'           => 'Reliable Plumbing Experts',
                    'service'        => 'Plumbing',
                    'city'           => 'Austin, TX',
                    'badge'          => 'unlisted',
                    'rating'         => '4.5',
                    'reviews'        => 23,
                    'jobs'           => 15,
                    'activity'       => 'Active',
                    'lastJobDaysAgo' => 5,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-7',
                ],
                [
                    'id'             => 'demo-8',
                    'wpId'           => 0,
                    'name'           => 'Master Electricians Inc',
                    'service'        => 'Electrical',
                    'city'           => 'Houston, TX',
                    'badge'          => 'verified',
                    'rating'         => '4.7',
                    'reviews'        => 78,
                    'jobs'           => 34,
                    'activity'       => 'Active',
                    'lastJobDaysAgo' => 2,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-8',
                ],
                [
                    'id'             => 'demo-9',
                    'wpId'           => 0,
                    'name'           => 'LeadsForward',
                    'service'        => 'General Contractor',
                    'city'           => 'Sarasota',
                    'badge'          => 'verified',
                    'rating'         => '5.0',
                    'reviews'        => 'New',
                    'jobs'           => 1,
                    'activity'       => 'Active recently',
                    'lastJobDaysAgo' => 0,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-9',
                ],
                [
                    'id'             => 'demo-10',
                    'wpId'           => 0,
                    'name'           => 'Standard Builders LLC',
                    'service'        => 'General Contractor',
                    'city'           => 'Dallas, TX',
                    'badge'          => 'unlisted',
                    'rating'         => '4.4',
                    'reviews'        => 18,
                    'jobs'           => 12,
                    'activity'       => 'Active',
                    'lastJobDaysAgo' => 6,
                    'logo'           => '',
                    'permalink'      => '/company/?id=demo-10',
                ],
            ];

            // Add demo companies to listings
            $listings = array_merge( $listings, $demo_companies );
        }

        $directory_data = wp_json_encode( [ 'listings' => $listings ] );
        wp_add_inline_script( 'jcp-core-directory', "window.JCP_DIRECTORY_DATA = {$directory_data};", 'before' );
        return;
    }

    // Company (single company profile)
    if ( $pages['is_company'] ) {
        jcp_core_enqueue_style( 'jcp-core-demo', 'css/pages/demo.css' );
        // directory-trust.css merged into directory-consolidated.css
        jcp_core_enqueue_style( 'jcp-core-directory', 'css/pages/directory-consolidated.css', [ 'jcp-core-utilities' ] );
        jcp_core_enqueue_style( 'jcp-core-profile', 'css/pages/profile-consolidated.css', [ 'jcp-core-directory' ] );
        jcp_core_enqueue_script( 'jcp-core-profile', 'js/features/directory/profile.js', [ $render_handle ] );
        jcp_core_enqueue_script( 'jcp-core-directory-integration', 'js/features/directory/directory-integration.js', [ 'jcp-core-profile' ] );

        $post = get_post();
        if ( $post && $post->post_type === 'jcp_company' ) {
            $profile_data = wp_json_encode( jcp_core_company_data( $post ) );
            wp_add_inline_script( 'jcp-core-profile', "window.JCP_PROFILE_DATA = {$profile_data};", 'before' );
        }
        return;
    }

    // Estimate page
    if ( $pages['is_estimate'] ) {
        jcp_core_enqueue_style( 'jcp-core-demo', 'css/pages/demo.css' );
        jcp_core_enqueue_style( 'jcp-core-estimate', 'css/pages/estimate.css' );
        jcp_core_enqueue_script( 'jcp-core-analytics', 'js/features/estimate/analytics.js', [ $render_handle ] );
        jcp_core_enqueue_script( 'jcp-core-requests', 'js/features/estimate/requests.js', [ $render_handle ] );
        jcp_core_enqueue_script( 'jcp-core-estimate', 'js/features/estimate/estimate-builder.js', [ 'jcp-core-analytics', 'jcp-core-requests' ] );
        return;
    }
}

add_action( 'wp_enqueue_scripts', 'jcp_core_enqueue_assets' );

/**
 * Add defer to theme scripts to reduce parse-blocking and improve LCP/TBT.
 * Scripts still run in order after DOM ready; no behavior change.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified tag.
 */
function jcp_core_defer_theme_scripts( $tag, $handle ): string {
    if ( strpos( $handle, 'jcp-core-' ) === 0 || strpos( $handle, 'jcp-shared-' ) === 0 ) {
        if ( strpos( $tag, ' defer' ) === false && strpos( $tag, ' async' ) === false ) {
            return str_replace( ' src', ' defer src', $tag );
        }
    }
    return $tag;
}

add_filter( 'script_loader_tag', 'jcp_core_defer_theme_scripts', 10, 2 );
