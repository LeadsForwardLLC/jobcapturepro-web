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
 * Build a single-line address string from an address array (supports multiple key names).
 *
 * @param array $address Address array (e.g. from _jcp_address or API)
 * @return string Formatted address or empty string
 */
function jcp_core_format_address_from_array( array $address ): string {
    if ( empty( $address ) ) {
        return '';
    }
    $street = $address['street'] ?? $address['street1'] ?? $address['address1'] ?? $address['streetAddress'] ?? $address['line1'] ?? '';
    $city   = $address['city'] ?? $address['locality'] ?? $address['cityName'] ?? '';
    $state  = $address['state'] ?? $address['region'] ?? $address['administrativeArea'] ?? $address['stateCode'] ?? '';
    $zip    = $address['zip'] ?? $address['postalCode'] ?? $address['postal_code'] ?? $address['zipCode'] ?? '';
    $country = $address['country'] ?? $address['countryCode'] ?? '';
    $parts = array_filter( [ $street, $city, $state, $zip, $country ] );
    return implode( ', ', $parts );
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
 * Abbreviate US state to 2-letter code for display (City, ST).
 * If already 2 chars or unknown, returns as-is.
 *
 * @param string $state State name or code
 * @return string 2-letter state code or original
 */
function jcp_core_abbreviate_state( string $state ): string {
    $state = trim( $state );
    if ( strlen( $state ) === 2 ) {
        return strtoupper( $state );
    }
    $map = [
        'alabama' => 'AL', 'alaska' => 'AK', 'arizona' => 'AZ', 'arkansas' => 'AR',
        'california' => 'CA', 'colorado' => 'CO', 'connecticut' => 'CT',
        'delaware' => 'DE', 'florida' => 'FL', 'georgia' => 'GA', 'hawaii' => 'HI',
        'idaho' => 'ID', 'illinois' => 'IL', 'indiana' => 'IN', 'iowa' => 'IA',
        'kansas' => 'KS', 'kentucky' => 'KY', 'louisiana' => 'LA', 'maine' => 'ME',
        'maryland' => 'MD', 'massachusetts' => 'MA', 'michigan' => 'MI', 'minnesota' => 'MN',
        'mississippi' => 'MS', 'missouri' => 'MO', 'montana' => 'MT', 'nebraska' => 'NE',
        'nevada' => 'NV', 'new hampshire' => 'NH', 'new jersey' => 'NJ', 'new mexico' => 'NM',
        'new york' => 'NY', 'north carolina' => 'NC', 'north dakota' => 'ND', 'ohio' => 'OH',
        'oklahoma' => 'OK', 'oregon' => 'OR', 'pennsylvania' => 'PA', 'rhode island' => 'RI',
        'south carolina' => 'SC', 'south dakota' => 'SD', 'tennessee' => 'TN', 'texas' => 'TX',
        'utah' => 'UT', 'vermont' => 'VT', 'virginia' => 'VA', 'washington' => 'WA',
        'west virginia' => 'WV', 'wisconsin' => 'WI', 'wyoming' => 'WY', 'district of columbia' => 'DC',
    ];
    $key = strtolower( $state );
    return isset( $map[ $key ] ) ? $map[ $key ] : $state;
}

/**
 * Get company city and state formatted for directory (City, ST).
 * Uses abbreviated state when built from address array or when normalizing stored formatted string.
 *
 * @param int $post_id Company post ID
 * @return string Formatted "City, State" string (state abbreviated)
 */
function jcp_core_company_city_state( int $post_id ): string {
    $formatted = get_post_meta( $post_id, '_jcp_address_formatted', true );
    if ( ! empty( $formatted ) ) {
        $formatted = trim( $formatted );
        if ( strpos( $formatted, ',' ) !== false ) {
            $parts = array_map( 'trim', explode( ',', $formatted ) );
            $last = end( $parts );
            if ( $last !== '' && strlen( $last ) > 2 ) {
                $abbr = jcp_core_abbreviate_state( $last );
                if ( $abbr !== $last ) {
                    $parts[ count( $parts ) - 1 ] = $abbr;
                    $formatted = implode( ', ', $parts );
                }
            }
        }
        return $formatted;
    }

    $raw_address = get_post_meta( $post_id, '_jcp_address', true );
    $address = jcp_core_normalize_address( $raw_address );

    if ( ! empty( $address ) ) {
        $parts = [];
        if ( ! empty( $address['city'] ) ) {
            $parts[] = trim( $address['city'] );
        }
        $state = $address['state'] ?? $address['region'] ?? $address['stateCode'] ?? $address['administrativeArea'] ?? '';
        if ( $state !== '' ) {
            $parts[] = jcp_core_abbreviate_state( (string) $state );
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

    // Full address: prefer stored formatted string, else build from array, else use raw string
    $address_formatted = get_post_meta( $post_id, '_jcp_address_formatted', true );
    $address_formatted = is_string( $address_formatted ) ? trim( $address_formatted ) : '';
    if ( $address_formatted === '' && ! empty( $address ) ) {
        $address_formatted = jcp_core_format_address_from_array( $address );
    }
    if ( $address_formatted === '' && is_string( $raw_address ) && trim( $raw_address ) !== '' ) {
        $decoded = json_decode( $raw_address, true );
        if ( ! is_array( $decoded ) ) {
            $address_formatted = trim( $raw_address );
        }
    }

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
        'addressFormatted' => $address_formatted ?: '',
        'description'      => wp_strip_all_tags( $post->post_content ?? '' ),
        'serviceArea'      => $address['serviceArea'] ?? '',
        'address'          => $address,
        'lat'              => is_numeric( $lat ) ? (float) $lat : null,
        'lng'              => is_numeric( $lng ) ? (float) $lng : null,
        'permalink'        => home_url( '/directory/' . get_post_field( 'post_name', $post_id ) ),
        'checkins'         => jcp_core_parse_checkins( $post_id ),
    ];
}

/**
 * Return demo company listings with pretty permalinks (/directory/slug).
 * Used for directory listing and profile resolution.
 *
 * @return array List of demo company data arrays
 */
function jcp_core_get_demo_companies(): array {
    $base = [
        [ 'id' => 'demo-1', 'wpId' => 0, 'name' => 'Summit Roofing', 'service' => 'Roofing', 'city' => 'Houston, TX', 'badge' => 'verified', 'rating' => '4.9', 'reviews' => 126, 'jobs' => 42, 'activity' => 'Very Active', 'lastJobDaysAgo' => 2, 'logo' => '', 'addressFormatted' => '123 Main St, Houston, TX 77001', 'phone' => '(555) 123-4567', 'website' => 'https://example.com' ],
        [ 'id' => 'demo-2', 'wpId' => 0, 'name' => 'Elite Plumbing Services', 'service' => 'Plumbing', 'city' => 'Dallas, TX', 'badge' => 'trusted', 'rating' => '4.8', 'reviews' => 89, 'jobs' => 67, 'activity' => 'Very Active', 'lastJobDaysAgo' => 1, 'logo' => '' ],
        [ 'id' => 'demo-3', 'wpId' => 0, 'name' => 'Premier HVAC Solutions', 'service' => 'HVAC', 'city' => 'Austin, TX', 'badge' => 'verified', 'rating' => '4.7', 'reviews' => 54, 'jobs' => 38, 'activity' => 'Active', 'lastJobDaysAgo' => 3, 'logo' => '' ],
        [ 'id' => 'demo-4', 'wpId' => 0, 'name' => 'Apex Electrical Contractors', 'service' => 'Electrical', 'city' => 'San Antonio, TX', 'badge' => 'verified', 'rating' => '4.9', 'reviews' => 112, 'jobs' => 51, 'activity' => 'Very Active', 'lastJobDaysAgo' => 0, 'logo' => '' ],
        [ 'id' => 'demo-5', 'wpId' => 0, 'name' => 'Coastal General Contractors', 'service' => 'General Contractor', 'city' => 'Houston, TX', 'badge' => 'trusted', 'rating' => '4.8', 'reviews' => 203, 'jobs' => 89, 'activity' => 'Very Active', 'lastJobDaysAgo' => 1, 'logo' => '' ],
        [ 'id' => 'demo-6', 'wpId' => 0, 'name' => 'Precision Roofing & Repair', 'service' => 'Roofing', 'city' => 'Dallas, TX', 'badge' => 'verified', 'rating' => '4.6', 'reviews' => 45, 'jobs' => 28, 'activity' => 'Active', 'lastJobDaysAgo' => 4, 'logo' => '' ],
        [ 'id' => 'demo-7', 'wpId' => 0, 'name' => 'Reliable Plumbing Experts', 'service' => 'Plumbing', 'city' => 'Austin, TX', 'badge' => 'unlisted', 'rating' => '4.5', 'reviews' => 23, 'jobs' => 15, 'activity' => 'Active', 'lastJobDaysAgo' => 5, 'logo' => '' ],
        [ 'id' => 'demo-8', 'wpId' => 0, 'name' => 'Master Electricians Inc', 'service' => 'Electrical', 'city' => 'Houston, TX', 'badge' => 'verified', 'rating' => '4.7', 'reviews' => 78, 'jobs' => 34, 'activity' => 'Active', 'lastJobDaysAgo' => 2, 'logo' => '' ],
        [ 'id' => 'demo-9', 'wpId' => 0, 'name' => 'LeadsForward', 'service' => 'General Contractor', 'city' => 'Sarasota', 'badge' => 'verified', 'rating' => '5.0', 'reviews' => 'New', 'jobs' => 1, 'activity' => 'Active recently', 'lastJobDaysAgo' => 0, 'logo' => '' ],
        [ 'id' => 'demo-10', 'wpId' => 0, 'name' => 'Standard Builders LLC', 'service' => 'General Contractor', 'city' => 'Dallas, TX', 'badge' => 'unlisted', 'rating' => '4.4', 'reviews' => 18, 'jobs' => 12, 'activity' => 'Active', 'lastJobDaysAgo' => 6, 'logo' => '' ],
    ];
    $out = [];
    foreach ( $base as $row ) {
        $row['permalink'] = home_url( '/directory/' . $row['id'] );
        $out[] = $row;
    }
    return $out;
}

/**
 * Resolve company profile data by slug (for /directory/slug and /company?id=slug).
 * Tries WP jcp_company post by post_name, then demo companies by id.
 *
 * @param string $slug Company slug or id (e.g. summit-roofing or demo-1)
 * @return array|null Company data array or null
 */
function jcp_core_resolve_company_for_profile( string $slug ): ?array {
    $slug = trim( $slug );
    if ( $slug === '' ) {
        return null;
    }
    $posts = get_posts(
        [
            'post_type'      => 'jcp_company',
            'post_status'    => 'publish',
            'name'           => $slug,
            'posts_per_page' => 1,
        ]
    );
    if ( ! empty( $posts ) ) {
        $data = jcp_core_company_data( $posts[0] );
        $data['permalink'] = home_url( '/directory/' . get_post_field( 'post_name', $posts[0]->ID ) );
        return $data;
    }
    foreach ( jcp_core_get_demo_companies() as $row ) {
        if ( isset( $row['id'] ) && (string) $row['id'] === $slug ) {
            return array_merge( $row, [ 'permalink' => home_url( '/directory/' . $row['id'] ) ] );
        }
    }
    if ( $slug === 'contractor-demo' ) {
        return [
            'id'             => 'contractor-demo',
            'wpId'           => 0,
            'name'           => 'Demo Listing',
            'service'        => 'Service',
            'city'           => 'Service Area',
            'badge'          => 'verified',
            'rating'          => '5.0',
            'reviews'        => 'New',
            'jobs'            => 1,
            'activity'       => 'Active recently',
            'lastJobDaysAgo' => 0,
            'logo'           => '',
            'permalink'      => home_url( '/directory/contractor-demo' ),
        ];
    }
    return null;
}
