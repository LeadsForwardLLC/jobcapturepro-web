<?php
/**
 * Company Data Functions
 * Parse, normalize, and retrieve company CPT information
 *
 * @package JCP_Core
 */

/**
 * Normalize address data (handles multiple formats)
 *
 * @param mixed $raw_address Raw address data (string, array, or JSON)
 * @return array Normalized address array
 */
function jcp_core_normalize_address( $raw_address ): array {
    if ( empty( $raw_address ) ) {
        return [];
    }

    if ( is_array( $raw_address ) ) {
        return $raw_address;
    }

    if ( is_string( $raw_address ) ) {
        $decoded = json_decode( $raw_address, true );
        if ( is_array( $decoded ) ) {
            return $decoded;
        }
    }

    return [];
}

/**
 * Get company service label from industries
 *
 * @param int $post_id Company post ID
 * @return string Service label (first industry or default)
 */
function jcp_core_company_service_label( int $post_id ): string {
    $industries_string = get_post_meta( $post_id, '_jcp_selected_industries_string', true );
    if ( ! empty( $industries_string ) ) {
        $parts = array_map( 'trim', explode( ',', $industries_string ) );
        return $parts[0] ?? 'Service';
    }

    $industries = get_post_meta( $post_id, '_jcp_selected_industries', true );
    if ( is_array( $industries ) && ! empty( $industries ) ) {
        return $industries[0];
    }

    return 'Service';
}

/**
 * Get company city and state formatted
 *
 * @param int $post_id Company post ID
 * @return string Formatted "City, State" string
 */
function jcp_core_company_city_state( int $post_id ): string {
    $formatted = get_post_meta( $post_id, '_jcp_address_formatted', true );
    if ( ! empty( $formatted ) ) {
        return $formatted;
    }

    $raw_address = get_post_meta( $post_id, '_jcp_address', true );
    $address = jcp_core_normalize_address( $raw_address );

    if ( ! empty( $address ) ) {
        $parts = [];
        if ( ! empty( $address['city'] ) ) {
            $parts[] = $address['city'];
        }
        if ( ! empty( $address['state'] ) ) {
            $parts[] = $address['state'];
        }
        return ! empty( $parts ) ? implode( ', ', $parts ) : '';
    }

    return '';
}

/**
 * Parse check-ins data (handles multiple formats)
 *
 * @param int $post_id Company post ID
 * @return array Array of check-ins
 */
function jcp_core_parse_checkins( int $post_id ): array {
    $raw = get_post_meta( $post_id, '_jcp_checkins', true );
    if ( empty( $raw ) ) {
        $raw = get_post_meta( $post_id, '_jcp_recent_checkins', true );
    }

    if ( empty( $raw ) ) {
        $raw = get_post_meta( $post_id, '_jcp_checkins_json', true );
    }

    if ( empty( $raw ) ) {
        return [];
    }

    if ( is_string( $raw ) ) {
        $decoded = json_decode( $raw, true );
        if ( is_array( $decoded ) ) {
            $raw = $decoded;
        }
    }

    if ( ! is_array( $raw ) ) {
        return [];
    }

    $checkins = [];
    foreach ( $raw as $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }
        $checkins[] = [
            'title'       => $item['title'] ?? $item['name'] ?? 'Job Check-In',
            'description' => $item['description'] ?? '',
            'time'        => $item['time'] ?? $item['date'] ?? '',
            'location'    => $item['location'] ?? '',
            'image'       => $item['image'] ?? $item['photo'] ?? '',
        ];
    }

    return $checkins;
}

/**
 * Get complete company data object
 *
 * @param WP_Post $post Company post object
 * @return array Company data array
 */
function jcp_core_company_data( WP_Post $post ): array {
    $post_id = $post->ID;
    $raw_address = get_post_meta( $post_id, '_jcp_address', true );
    $address = jcp_core_normalize_address( $raw_address );

    $lat = $address['lat'] ?? $address['latitude'] ?? null;
    $lng = $address['lng'] ?? $address['longitude'] ?? null;

    return [
        'id'               => get_post_meta( $post_id, '_jcp_company_id', true ) ?: (string) $post_id,
        'wpId'             => $post_id,
        'name'             => get_the_title( $post_id ) ?: 'Company',
        'service'          => jcp_core_company_service_label( $post_id ),
        'city'             => jcp_core_company_city_state( $post_id ) ?: 'Service Area',
        'badge'            => get_post_meta( $post_id, '_jcp_verified_status', true ) ?: 'listed',
        'rating'           => get_post_meta( $post_id, '_jcp_rating', true ) ?: '',
        'reviews'          => (int) ( get_post_meta( $post_id, '_jcp_review_count', true ) ?: 0 ),
        'jobs'             => (int) ( get_post_meta( $post_id, '_jcp_job_count', true ) ?: 0 ),
        'activity'         => get_post_meta( $post_id, '_jcp_activity_label', true ) ?: 'Active',
        'lastJobDaysAgo'   => (int) ( get_post_meta( $post_id, '_jcp_last_job_days', true ) ?: 0 ),
        'logo'             => get_post_meta( $post_id, '_jcp_logo_url', true ) ?: '',
        'phone'            => get_post_meta( $post_id, '_jcp_phone', true ) ?: '',
        'website'          => get_post_meta( $post_id, '_jcp_website_url', true ) ?: '',
        'description'      => wp_strip_all_tags( $post->post_content ?? '' ),
        'serviceArea'      => $address['serviceArea'] ?? '',
        'address'          => $address,
        'lat'              => is_numeric( $lat ) ? (float) $lat : null,
        'lng'              => is_numeric( $lng ) ? (float) $lng : null,
        'permalink'        => get_permalink( $post_id ),
        'checkins'         => jcp_core_parse_checkins( $post_id ),
    ];
}
