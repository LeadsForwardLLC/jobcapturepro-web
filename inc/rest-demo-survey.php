<?php
/**
 * REST API: Demo Survey form submission → GoHighLevel webhook (Demo only)
 *
 * Separate from Early Access. Sends application/x-www-form-urlencoded to the
 * Demo Survey webhook. Maps contact fields + demo-specific fields. Applies
 * demo tags (demo-completed, demo-interest); does not apply early-access tag.
 *
 * @package JCP_Core
 */

/**
 * GHL webhook URL for Demo Survey submissions only.
 */
define( 'JCP_GHL_DEMO_SURVEY_WEBHOOK_URL', 'https://services.leadconnectorhq.com/hooks/kMIwmFm9I7LJPEYo35qi/webhook-trigger/zYfSsYRsSdSdHlD5vqUv' );

/**
 * Register REST route for Demo Survey.
 */
function jcp_core_register_demo_survey_rest_routes(): void {
    register_rest_route( 'jcp/v1', '/demo-survey-submit', [
        'methods'             => 'POST',
        'permission_callback' => '__return_true',
        'callback'            => 'jcp_core_demo_survey_submit_handler',
        'args'                => [
            'first_name'     => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'last_name'      => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'email'          => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_email',
                'validate_callback' => function ( $value ) {
                    return is_email( $value );
                },
            ],
            'phone'          => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'business_name'  => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'business_type'  => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'service_area'   => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ],
            'demo_goals'     => [
                'required'          => false,
                'type'              => 'array',
                'items'             => [ 'type' => 'string' ],
            ],
        ],
    ] );
}

add_action( 'rest_api_init', 'jcp_core_register_demo_survey_rest_routes' );

/**
 * Build application/x-www-form-urlencoded body for Demo Survey GHL webhook.
 * Shared contact fields + demo-specific fields. Tags: demo-completed, demo-interest.
 *
 * @param array $params Sanitized request params.
 * @return string
 */
function jcp_core_build_demo_survey_ghl_body( array $params ): string {
    $first_name    = isset( $params['first_name'] ) ? trim( (string) $params['first_name'] ) : '';
    $last_name     = isset( $params['last_name'] ) ? trim( (string) $params['last_name'] ) : '';
    $email         = isset( $params['email'] ) ? trim( (string) $params['email'] ) : '';
    $phone         = isset( $params['phone'] ) ? trim( (string) $params['phone'] ) : '';
    $business_name = isset( $params['business_name'] ) ? trim( (string) $params['business_name'] ) : '';
    $business_type = isset( $params['business_type'] ) ? trim( (string) $params['business_type'] ) : '';
    $service_area  = isset( $params['service_area'] ) ? trim( (string) $params['service_area'] ) : '';
    $demo_goals    = isset( $params['demo_goals'] ) && is_array( $params['demo_goals'] )
        ? array_filter( array_map( 'trim', $params['demo_goals'] ) )
        : [];

    $scalar = [
        'First Name'     => $first_name,
        'Last Name'      => $last_name,
        'Email'          => $email,
        'Phone'          => $phone,
        'Company'        => $business_name,
        'Business Type'  => $business_type,
        'Service Area'   => $service_area,
        'Use Case'       => implode( ', ', $demo_goals ),
    ];
    $body = http_build_query( $scalar, '', '&', PHP_QUERY_RFC3986 );

    $tags = [ 'demo-completed', 'demo-interest' ];
    foreach ( $tags as $tag ) {
        $body .= '&Tags%5B%5D=' . rawurlencode( $tag );
    }

    return $body;
}

/**
 * Handle Demo Survey form POST: build GHL payload and forward to Demo Survey webhook.
 *
 * @param \WP_REST_Request $request Request.
 * @return \WP_REST_Response
 */
function jcp_core_demo_survey_submit_handler( \WP_REST_Request $request ): \WP_REST_Response {
    $first_name = $request->get_param( 'first_name' );
    $email      = $request->get_param( 'email' );

    if ( empty( trim( (string) $first_name ) ) || empty( trim( (string) $email ) ) ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'First name and email are required.', 'jcp-core' ) ],
            400
        );
    }

    $params = [
        'first_name'    => $first_name,
        'last_name'     => $request->get_param( 'last_name' ),
        'email'         => $email,
        'phone'         => $request->get_param( 'phone' ),
        'business_name' => $request->get_param( 'business_name' ),
        'business_type' => $request->get_param( 'business_type' ),
        'service_area'  => $request->get_param( 'service_area' ),
        'demo_goals'    => $request->get_param( 'demo_goals' ),
    ];

    $body_string = jcp_core_build_demo_survey_ghl_body( $params );

    $response = wp_remote_post(
        JCP_GHL_DEMO_SURVEY_WEBHOOK_URL,
        [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $body_string,
        ]
    );

    $code = wp_remote_retrieve_response_code( $response );
    $res_body = wp_remote_retrieve_body( $response );
    $ok = $code >= 200 && $code < 300;

    if ( $ok ) {
        return new \WP_REST_Response( [ 'success' => true ], 200 );
    }

    $msg = __( 'Something went wrong. Please try again.', 'jcp-core' );
    if ( $res_body !== '' ) {
        $decoded = json_decode( $res_body, true );
        if ( is_array( $decoded ) && isset( $decoded['message'] ) && is_string( $decoded['message'] ) ) {
            $msg = $decoded['message'];
        }
    }

    return new \WP_REST_Response( [ 'success' => false, 'message' => $msg ], 400 );
}
