<?php
/**
 * REST API: Early Access form submission → GoHighLevel webhook
 *
 * Sends application/x-www-form-urlencoded. Exact keys: First Name, Email, Phone,
 * Company, Trade, Referral Source[] (array), Message.
 *
 * @package JCP_Core
 */

/**
 * GHL webhook URL for Early Access form submissions only.
 * Do not use for Demo Survey; see inc/rest-demo-survey.php for Demo webhook.
 */
define( 'JCP_GHL_WEBHOOK_URL_DEFAULT', 'https://services.leadconnectorhq.com/hooks/kMIwmFm9I7LJPEYo35qi/webhook-trigger/d476d7e2-286d-4201-811d-4fedfea5fdf5' );

/**
 * Register REST routes for Early Access.
 */
function jcp_core_register_early_access_rest_routes(): void {
    register_rest_route( 'jcp/v1', '/early-access-submit', [
        'methods'             => 'POST',
        'permission_callback' => '__return_true',
        'callback'            => 'jcp_core_early_access_submit_handler',
        'args'                => [
            'first_name'      => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'company'         => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'email'           => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_email',
                'validate_callback'  => function ( $value ) {
                    return is_email( $value );
                },
            ],
            'phone'           => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'message'         => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_textarea_field',
            ],
            'referral_source' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
        ],
    ] );

    register_rest_route( 'jcp/v1', '/early-access-test-ghl', [
        'methods'             => 'GET',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        },
        'callback'            => 'jcp_core_early_access_test_ghl',
    ] );
}

add_action( 'rest_api_init', 'jcp_core_register_early_access_rest_routes' );

/**
 * Get GHL webhook URL (hardcoded default for Early Access only).
 *
 * @return string
 */
function jcp_core_ghl_webhook_url(): string {
    return JCP_GHL_WEBHOOK_URL_DEFAULT;
}

/**
 * Build application/x-www-form-urlencoded body for GHL.
 * Keys: First Name, Email, Phone, Company, Trade, Referral Source[] (array), Message.
 *
 * @param string $first_name First Name.
 * @param string $email Email.
 * @param string $phone Phone.
 * @param string $company Company.
 * @param string $trade Trade (e.g. General Contractor).
 * @param array  $referral_source Referral Source (at least one value).
 * @param string $message Message.
 * @return string
 */
function jcp_core_build_ghl_body( string $first_name, string $email, string $phone, string $company, string $trade, array $referral_source, string $message ): string {
    $scalar = [
        'First Name' => $first_name,
        'Email'      => $email,
        'Phone'      => $phone,
        'Company'    => $company,
        'Trade'      => $trade,
        'Message'    => $message,
    ];
    $body = http_build_query( $scalar, '', '&', PHP_QUERY_RFC3986 );
    foreach ( $referral_source as $v ) {
        $v = trim( (string) $v );
        if ( $v !== '' ) {
            $body .= '&Referral+Source%5B%5D=' . rawurlencode( $v );
        }
    }
    return $body;
}

/**
 * Handle Early Access form POST: build GHL payload and forward.
 *
 * @param \WP_REST_Request $request Request.
 * @return \WP_REST_Response
 */
function jcp_core_early_access_submit_handler( \WP_REST_Request $request ): \WP_REST_Response {
    $first_name      = $request->get_param( 'first_name' );
    $company         = $request->get_param( 'company' );
    $email           = $request->get_param( 'email' );
    $phone           = $request->get_param( 'phone' );
    $message         = $request->get_param( 'message' );
    $referral_source = $request->get_param( 'referral_source' );

    $require_company = true;
    $require_phone   = true;

    if ( empty( $first_name ) || empty( $email ) || empty( $message ) || empty( $referral_source ) ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'Required fields must be filled.', 'jcp-core' ) ],
            400
        );
    }
    if ( $require_company && ( $company === null || trim( (string) $company ) === '' ) ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'Company is required.', 'jcp-core' ) ],
            400
        );
    }
    if ( $require_phone && ( $phone === null || trim( (string) $phone ) === '' ) ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'Phone is required.', 'jcp-core' ) ],
            400
        );
    }

    $first_name = trim( (string) $first_name );
    $company    = trim( (string) $company );
    $email      = trim( (string) $email );
    $phone      = trim( (string) $phone );
    $message    = trim( (string) $message );
    $referral_source = [ trim( (string) $referral_source ) ];

    $trade = 'General Contractor';

    $body_string = jcp_core_build_ghl_body( $first_name, $email, $phone, $company, $trade, $referral_source, $message );
    $url         = jcp_core_ghl_webhook_url();

    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( 'JCP GHL payload: ' . $body_string );
        error_log( 'JCP GHL URL: ' . $url );
    }

    $response = wp_remote_post(
        $url,
        [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $body_string,
        ]
    );

    $code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );

    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( 'JCP GHL response code: ' . (string) $code );
        error_log( 'JCP GHL response body: ' . (string) $body );
    }

    $ok = $code >= 200 && $code < 300;

    if ( $ok ) {
        return new \WP_REST_Response( [ 'success' => true ], 200 );
    }

    $msg = __( 'Something went wrong. Please try again.', 'jcp-core' );
    if ( $body !== '' ) {
        $decoded = json_decode( $body, true );
        if ( is_array( $decoded ) && isset( $decoded['message'] ) && is_string( $decoded['message'] ) ) {
            $msg = $decoded['message'];
        }
    }

    return new \WP_REST_Response( [ 'success' => false, 'message' => $msg ], 400 );
}

/**
 * Test GHL webhook (WP Admin only). Sends a test payload and returns response.
 *
 * @param \WP_REST_Request $request Request.
 * @return \WP_REST_Response
 */
function jcp_core_early_access_test_ghl( \WP_REST_Request $request ): \WP_REST_Response {
    $trade = 'General Contractor';

    $body_string = jcp_core_build_ghl_body(
        'Test First',
        'test@example.com',
        '555-000-0000',
        'Test Company',
        $trade,
        [ 'Google Search' ],
        'Test message from WP admin GHL test.'
    );
    $url = jcp_core_ghl_webhook_url();

    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( 'JCP GHL TEST payload: ' . $body_string );
        error_log( 'JCP GHL TEST URL: ' . $url );
    }

    $response = wp_remote_post(
        $url,
        [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $body_string,
        ]
    );

    $code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );

    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( 'JCP GHL TEST response code: ' . (string) $code );
        error_log( 'JCP GHL TEST response body: ' . (string) $body );
    }

    return new \WP_REST_Response( [
        'payload_sent'   => $body_string,
        'response_code' => $code,
        'response_body' => $body,
        'logged'        => true,
    ], 200 );
}
