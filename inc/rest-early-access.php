<?php
/**
 * REST API: Early Access form submission → GoHighLevel webhook
 *
 * Accepts POST from frontend, forwards to GHL with exact payload keys.
 * Content-Type to GHL: application/x-www-form-urlencoded.
 *
 * @package JCP_Core
 */

/**
 * GHL webhook URL (exact from prompt).
 */
define( 'JCP_GHL_WEBHOOK_URL', 'https://services.leadconnectorhq.com/hooks/kMIwmFm9I7LJPEYo35qi/webhook-trigger/d476d7e2-286d-4201-811d-4fedfea5fdf5' );

/**
 * Register REST route for Early Access form submit.
 */
function jcp_core_register_early_access_rest_route(): void {
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
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'email'           => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_email',
                'validate_callback' => function ( $value ) {
                    return is_email( $value );
                },
            ],
            'phone'           => [
                'required'          => true,
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
}

add_action( 'rest_api_init', 'jcp_core_register_early_access_rest_route' );

/**
 * Handle Early Access form POST: build GHL payload and forward.
 *
 * Payload keys (exact, case-sensitive): First Name, Company, Email, Phone, Trade, Referral Source, Message.
 * Referral Source must be an array (single item); Trade = "General Contractor".
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

    if ( empty( $first_name ) || empty( $company ) || empty( $email ) || empty( $phone ) || empty( $message ) || empty( $referral_source ) ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'All required fields must be filled.', 'jcp-core' ) ],
            400
        );
    }

    // Build GHL payload with exact keys (case-sensitive). Referral Source as array for http_build_query.
    $ghl_body = [
        'First Name'     => $first_name,
        'Company'        => $company,
        'Email'          => $email,
        'Phone'          => $phone,
        'Trade'          => 'General Contractor',
        'Referral Source' => [ $referral_source ],
        'Message'        => $message,
    ];

    $body_string = http_build_query( $ghl_body, '', '&', PHP_QUERY_RFC3986 );

    $response = wp_remote_post(
        JCP_GHL_WEBHOOK_URL,
        [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $body_string,
        ]
    );

    $code = wp_remote_retrieve_response_code( $response );
    $ok   = $code >= 200 && $code < 300;

    if ( $ok ) {
        return new \WP_REST_Response( [ 'success' => true ], 200 );
    }

    $body = wp_remote_retrieve_body( $response );
    $msg  = __( 'Something went wrong. Please try again.', 'jcp-core' );
    if ( $body ) {
        $decoded = json_decode( $body, true );
        if ( isset( $decoded['message'] ) && is_string( $decoded['message'] ) ) {
            $msg = $decoded['message'];
        }
    }

    return new \WP_REST_Response( [ 'success' => false, 'message' => $msg ], 400 );
}
