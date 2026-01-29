<?php
/**
 * Design System Page Setup
 * 
 * Creates and maintains the internal design system documentation page.
 * This page is NOT publicly linked and NOT indexed by search engines.
 *
 * @package JCP_Core
 */

/**
 * Auto-create design system page on theme activation or if missing
 *
 * @return void
 */
function jcp_core_setup_design_system_page(): void {
    // Check if design system page already exists
    $design_system_page = get_page_by_path( 'design-system' );
    
    if ( $design_system_page ) {
        return; // Already exists
    }

    // Create the design system page
    $page_data = [
        'post_title'    => 'Design System',
        'post_name'     => 'design-system',
        'post_content'  => '', // Content is rendered by template
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_parent'   => 0,
    ];

    $page_id = wp_insert_post( $page_data );

    if ( ! is_wp_error( $page_id ) ) {
        // Set the page template to page-design-system.php
        update_post_meta( $page_id, '_wp_page_template', 'page-design-system.php' );
    }
}

// Run on theme activation
add_action( 'after_switch_theme', 'jcp_core_setup_design_system_page' );

// Also check on admin init (in case it got deleted)
add_action( 'admin_init', function() {
    if ( is_admin() && current_user_can( 'manage_pages' ) ) {
        jcp_core_setup_design_system_page();
    }
} );
