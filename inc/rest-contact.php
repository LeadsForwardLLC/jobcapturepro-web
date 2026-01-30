<?php
/**
 * REST API: Contact form submission → GoHighLevel webhook
 *
 * Sends application/x-www-form-urlencoded to the Contact webhook.
 * Same pattern as Early Access and Demo Survey: frontend POSTs JSON to this endpoint,
 * handler builds GHL payload and forwards to the webhook.
 *
 * @package JCP_Core
 */

/**
 * GHL webhook URL for Contact form submissions.
 */
define( 'JCP_GHL_CONTACT_WEBHOOK_URL', 'https://services.leadconnectorhq.com/hooks/kMIwmFm9I7LJPEYo35qi/webhook-trigger/TlPUbnJk6zVDXXXkT1vw' );

/**
 * Register REST route for Contact form.
 */
function jcp_core_register_contact_rest_routes(): void {
    register_rest_route( 'jcp/v1', '/contact-submit', [
        'methods'             => 'POST',
        'permission_callback' => '__return_true',
        'callback'            => 'jcp_core_contact_submit_handler',
        'args'                => [
            'first_name' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'last_name'  => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'email'      => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_email',
                'validate_callback' => function ( $value ) {
                    return is_email( $value );
                },
            ],
            'phone'      => [
                'required'          => false,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'topic'      => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'message'    => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            ],
            'attachment_filename' => [
                'required'          => false,
                'type'             => 'string',
                'sanitize_callback' => 'sanitize_file_name',
            ],
            'attachment_data' => [
                'required' => false,
                'type'    => 'string',
            ],
        ],
    ] );
}

add_action( 'rest_api_init', 'jcp_core_register_contact_rest_routes' );

/**
 * Raise PHP body limits for contact form submissions (attachments sent as base64 in JSON).
 * Runs as early as possible when the request is to contact-submit so the larger body is accepted.
 * If you still see "request entity too large", raise in server config: nginx client_max_body_size 28M;
 * or Apache LimitRequestBody 29360128; or PHP post_max_size / upload_max_filesize 28M in php.ini / .user.ini.
 */
function jcp_core_contact_raise_limits_early(): void {
    $uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
    if ( strpos( $uri, 'jcp/v1/contact-submit' ) === false ) {
        return;
    }
    $limit = '28M'; // ~20MB file as base64 (~27MB) + form fields
    if ( function_exists( 'ini_set' ) ) {
        @ini_set( 'post_max_size', $limit );
        @ini_set( 'upload_max_filesize', $limit );
    }
}
add_action( 'init', 'jcp_core_contact_raise_limits_early', 0 );

function jcp_core_contact_raise_limits( $result, $server, $request ): mixed {
    if ( $request && $request->get_route() === '/jcp/v1/contact-submit' ) {
        $limit = '28M';
        if ( function_exists( 'ini_set' ) ) {
            @ini_set( 'post_max_size', $limit );
            @ini_set( 'upload_max_filesize', $limit );
        }
    }
    return $result;
}
add_filter( 'rest_pre_dispatch', 'jcp_core_contact_raise_limits', 1, 3 );

/**
 * Build application/x-www-form-urlencoded body for Contact GHL webhook.
 *
 * @param string $first_name First name.
 * @param string $last_name  Last name.
 * @param string $email      Email.
 * @param string $phone      Phone (optional).
 * @param string $topic      Topic (e.g. getting-started, technical-issue).
 * @param string $message    Message body.
 * @return string
 */
function jcp_core_build_contact_ghl_body( string $first_name, string $last_name, string $email, string $phone, string $topic, string $message ): string {
    $scalar = [
        JCP_GHL_KEY_FIRST_NAME => $first_name,
        JCP_GHL_KEY_LAST_NAME  => $last_name,
        JCP_GHL_KEY_EMAIL      => $email,
        JCP_GHL_KEY_PHONE      => $phone,
        JCP_GHL_KEY_TOPIC      => $topic,
        JCP_GHL_KEY_MESSAGE    => $message,
    ];
    return http_build_query( $scalar, '', '&', PHP_QUERY_RFC3986 );
}

/**
 * Map contact form topic value to human-readable label for GHL.
 *
 * @param string $topic Topic value (e.g. getting-started).
 * @return string
 */
function jcp_core_contact_topic_label( string $topic ): string {
    $labels = [
        'getting-started'   => 'Getting Started',
        'technical-issue'   => 'Technical Issue',
        'feature-request'  => 'Feature Request',
        'billing'          => 'Billing',
        'general-question' => 'General Question',
    ];
    return isset( $labels[ $topic ] ) ? $labels[ $topic ] : $topic;
}

/** Max attachment size (20MB) for contact form. Server post_max_size / client_max_body_size must allow ~1.35x this (base64 overhead). */
define( 'JCP_CONTACT_ATTACHMENT_MAX_BYTES', 20 * 1024 * 1024 );

/**
 * Handle Contact form POST: build GHL payload and forward to Contact webhook.
 * Sends multipart/form-data when an attachment is present so GHL receives the file.
 *
 * @param \WP_REST_Request $request Request.
 * @return \WP_REST_Response
 */
function jcp_core_contact_submit_handler( \WP_REST_Request $request ): \WP_REST_Response {
    $first_name = trim( (string) $request->get_param( 'first_name' ) );
    $last_name  = trim( (string) $request->get_param( 'last_name' ) );
    $email      = trim( (string) $request->get_param( 'email' ) );
    $phone      = trim( (string) $request->get_param( 'phone' ) );
    $topic      = trim( (string) $request->get_param( 'topic' ) );
    $message    = trim( (string) $request->get_param( 'message' ) );
    $attachment_filename = $request->get_param( 'attachment_filename' );
    $attachment_data     = $request->get_param( 'attachment_data' );

    if ( $first_name === '' || $last_name === '' || $email === '' || $topic === '' || $message === '' ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'First name, last name, email, topic, and message are required.', 'jcp-core' ) ],
            400
        );
    }

    if ( ! is_email( $email ) ) {
        return new \WP_REST_Response(
            [ 'success' => false, 'message' => __( 'Please enter a valid email address.', 'jcp-core' ) ],
            400
        );
    }

    $topic_label = jcp_core_contact_topic_label( $topic );

    $has_attachment = ! empty( $attachment_data ) && ! empty( $attachment_filename );
    $attachment_decoded = null;
    $attachment_omitted = false;
    if ( $has_attachment ) {
        $attachment_filename = sanitize_file_name( (string) $attachment_filename );
        if ( $attachment_filename === '' ) {
            $attachment_omitted = true;
            $has_attachment = false;
        } else {
            // Strip whitespace/newlines so base64 with line breaks (e.g. 76-char wrap) still decodes.
            $attachment_data_clean = is_string( $attachment_data ) ? preg_replace( '/\s+/', '', $attachment_data ) : '';
            $attachment_decoded = $attachment_data_clean !== '' ? base64_decode( $attachment_data_clean, true ) : false;
            if ( $attachment_decoded === false || strlen( $attachment_decoded ) > JCP_CONTACT_ATTACHMENT_MAX_BYTES ) {
                $attachment_omitted = true;
                $has_attachment = false;
                $attachment_decoded = null;
            }
        }
    }

    if ( $has_attachment && $attachment_decoded !== null ) {
        $body = [
            JCP_GHL_KEY_FIRST_NAME => $first_name,
            JCP_GHL_KEY_LAST_NAME  => $last_name,
            JCP_GHL_KEY_EMAIL      => $email,
            JCP_GHL_KEY_PHONE      => $phone,
            JCP_GHL_KEY_TOPIC      => $topic_label,
            JCP_GHL_KEY_MESSAGE    => $message,
            JCP_GHL_KEY_ATTACHMENT => [
                'content'  => $attachment_decoded,
                'filename' => $attachment_filename,
            ],
        ];
        $request_args = [
            'timeout' => 20,
            'body'    => $body,
        ];
    } else {
        $body_string = jcp_core_build_contact_ghl_body( $first_name, $last_name, $email, $phone, $topic_label, $message );
        $request_args = [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $body_string,
        ];
    }

    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( 'JCP Contact GHL URL: ' . JCP_GHL_CONTACT_WEBHOOK_URL );
        if ( ! $has_attachment ) {
            error_log( 'JCP Contact GHL payload: ' . ( isset( $body_string ) ? $body_string : '' ) );
        } else {
            error_log( 'JCP Contact GHL payload: multipart with attachment ' . $attachment_filename );
        }
    }

    $response = wp_remote_post( JCP_GHL_CONTACT_WEBHOOK_URL, $request_args );

    $code     = wp_remote_retrieve_response_code( $response );
    $res_body = wp_remote_retrieve_body( $response );

    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        error_log( 'JCP Contact GHL response code: ' . (string) $code );
        error_log( 'JCP Contact GHL response body: ' . (string) $res_body );
    }

    $ok = $code >= 200 && $code < 300;

    if ( $ok ) {
        $payload = [ 'success' => true ];
        if ( ! empty( $attachment_omitted ) ) {
            $payload['attachment_omitted'] = true;
        }
        return new \WP_REST_Response( $payload, 200 );
    }

    // If we sent with attachment and GHL rejected it, retry without attachment so the message still goes through.
    if ( $has_attachment && $attachment_decoded !== null ) {
        $body_string = jcp_core_build_contact_ghl_body( $first_name, $last_name, $email, $phone, $topic_label, $message );
        $retry_args  = [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body'    => $body_string,
        ];
        $retry_response = wp_remote_post( JCP_GHL_CONTACT_WEBHOOK_URL, $retry_args );
        $retry_code     = wp_remote_retrieve_response_code( $retry_response );
        if ( $retry_code >= 200 && $retry_code < 300 ) {
            return new \WP_REST_Response( [ 'success' => true, 'attachment_omitted' => true ], 200 );
        }
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
