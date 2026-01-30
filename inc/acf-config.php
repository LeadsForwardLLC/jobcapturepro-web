<?php
/**
 * ACF Configuration
 * Homepage field groups and options page setup
 *
 * @package JCP_Core
 */

// Only run if ACF is active
if ( ! function_exists( 'acf_add_options_page' ) ) {
    return;
}

/**
 * Create Homepage Options Page in ACF
 */
function jcp_core_register_homepage_options_page() {
    acf_add_options_page(
        [
            'page_title' => 'Homepage Settings',
            'menu_title' => 'Homepage Settings',
            'menu_slug'  => 'jcp-homepage',
            'capability' => 'manage_options',
            'position'   => 20,
            'icon_url'   => 'dashicons-layout',
        ]
    );
}

add_action( 'acf/init', 'jcp_core_register_homepage_options_page' );

/**
 * Register ACF field groups for Homepage
 * Using programmatic approach (no JSON sync needed)
 */
function jcp_core_register_acf_field_groups() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    // Hero Section Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_hero',
            'title'    => 'Hero Section',
            'fields'   => [
                [
                    'key'        => 'hero_headline',
                    'label'      => 'Headline (H1)',
                    'name'       => 'hero_headline',
                    'type'       => 'text',
                    'required'   => 1,
                    'default'    => 'Turn every completed job into more calls and more customers',
                    'maxlength'  => 120,
                ],
                [
                    'key'        => 'hero_subheadline',
                    'label'      => 'Subheadline',
                    'name'       => 'hero_subheadline',
                    'type'       => 'textarea',
                    'default'    => 'Your team already takes job photos. JobCapturePro automatically turns those jobs into website updates, Google visibility, social posts, directory listings, and review requests so your work keeps bringing in new business.',
                    'rows'       => 4,
                ],
                [
                    'key'        => 'hero_image_url',
                    'label'      => 'Hero Image URL',
                    'name'       => 'hero_image_url',
                    'type'       => 'url',
                    'default'    => 'http://jobcapturepro.com/wp-content/uploads/2025/12/jcp-user-photo.jpg',
                ],
                [
                    'key'        => 'hero_image_alt',
                    'label'      => 'Hero Image Alt Text',
                    'name'       => 'hero_image_alt',
                    'type'       => 'text',
                    'default'    => 'Contractor capturing a job photo with JobCapturePro',
                ],
                [
                    'key'        => 'hero_badge_text',
                    'label'      => 'Badge Text (top-left overlay)',
                    'name'       => 'hero_badge_text',
                    'type'       => 'text',
                    'default'    => 'Real job proof',
                ],
                [
                    'key'        => 'hero_content_title',
                    'label'      => 'Bottom Content Title (e.g., "Verified instantly")',
                    'name'       => 'hero_content_title',
                    'type'       => 'text',
                    'default'    => 'Verified job proof',
                ],
                [
                    'key'        => 'hero_content_subtitle',
                    'label'      => 'Bottom Content Subtitle',
                    'name'       => 'hero_content_subtitle',
                    'type'       => 'text',
                    'default'    => 'AI check-ins appear in minutes',
                ],
                [
                    'key'        => 'hero_cta_primary_text',
                    'label'      => 'Primary CTA Button Text',
                    'name'       => 'hero_cta_primary_text',
                    'type'       => 'text',
                    'default'    => 'Watch the Live Demo',
                ],
                [
                    'key'        => 'hero_cta_primary_url',
                    'label'      => 'Primary CTA Button URL',
                    'name'       => 'hero_cta_primary_url',
                    'type'       => 'url',
                    'default'    => '/demo',
                ],
                [
                    'key'        => 'hero_cta_secondary_text',
                    'label'      => 'Secondary CTA Button Text',
                    'name'       => 'hero_cta_secondary_text',
                    'type'       => 'text',
                    'default'    => 'Learn how it works',
                ],
                [
                    'key'        => 'hero_cta_secondary_url',
                    'label'      => 'Secondary CTA Button URL',
                    'name'       => 'hero_cta_secondary_url',
                    'type'       => 'url',
                    'default'    => '#how-it-works',
                ],
                [
                    'key'        => 'hero_stats',
                    'label'      => 'Stats Row (bottom overlay)',
                    'name'       => 'hero_stats',
                    'type'       => 'repeater',
                    'min'        => 3,
                    'max'        => 5,
                    'layout'     => 'block',
                    'button_label' => 'Add Stat',
                    'sub_fields' => [
                        [
                            'key'      => 'stat_icon',
                            'label'    => 'Icon Name (Lucide)',
                            'name'     => 'stat_icon',
                            'type'     => 'text',
                            'required' => 1,
                            'default'  => 'map-pin',
                            'instructions' => 'e.g., map-pin, globe, star. See /assets/shared/assets/icons/lucide/',
                        ],
                        [
                            'key'      => 'stat_label',
                            'label'    => 'Label Text',
                            'name'     => 'stat_label',
                            'type'     => 'text',
                            'required' => 1,
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 10,
        ]
    );

    // How It Works Section Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_how_it_works',
            'title'    => 'How It Works Section',
            'fields'   => [
                [
                    'key'      => 'enable_how_it_works',
                    'label'    => 'Enable this section',
                    'name'     => 'enable_how_it_works',
                    'type'     => 'true_false',
                    'default'  => 1,
                ],
                [
                    'key'        => 'how_it_works_title',
                    'label'      => 'Section Title',
                    'name'       => 'how_it_works_title',
                    'type'       => 'text',
                    'default'    => 'How JobCapturePro works',
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_how_it_works',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
                [
                    'key'        => 'how_it_works_subtitle',
                    'label'      => 'Subtitle',
                    'name'       => 'how_it_works_subtitle',
                    'type'       => 'textarea',
                    'default'    => 'Every completed job becomes verified proof across every channel that matters. Here\'s the simple flow your crew already knows.',
                    'rows'       => 3,
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_how_it_works',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 20,
        ]
    );

    // FAQ Section Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_faq',
            'title'    => 'FAQ Section',
            'fields'   => [
                [
                    'key'      => 'enable_faq',
                    'label'    => 'Enable this section',
                    'name'     => 'enable_faq',
                    'type'     => 'true_false',
                    'default'  => 1,
                ],
                [
                    'key'        => 'faq_title',
                    'label'      => 'Section Title',
                    'name'       => 'faq_title',
                    'type'       => 'text',
                    'default'    => 'FAQ',
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_faq',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
                [
                    'key'      => 'faq_items',
                    'label'    => 'FAQ Items',
                    'name'     => 'faq_items',
                    'type'     => 'repeater',
                    'layout'   => 'block',
                    'button_label' => 'Add FAQ',
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_faq',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                    'sub_fields' => [
                        [
                            'key'      => 'faq_question',
                            'label'    => 'Question',
                            'name'     => 'faq_question',
                            'type'     => 'text',
                            'required' => 1,
                        ],
                        [
                            'key'      => 'faq_answer',
                            'label'    => 'Answer',
                            'name'     => 'faq_answer',
                            'type'     => 'textarea',
                            'required' => 1,
                            'rows'     => 3,
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 30,
        ]
    );

    // Pricing Section Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_pricing',
            'title'    => 'Pricing Section',
            'fields'   => [
                [
                    'key'      => 'enable_pricing_section',
                    'label'    => 'Enable this section',
                    'name'     => 'enable_pricing_section',
                    'type'     => 'true_false',
                    'default'  => 1,
                ],
                [
                    'key'        => 'pricing_section_title',
                    'label'      => 'Section Title',
                    'name'       => 'pricing_section_title',
                    'type'       => 'text',
                    'default'    => 'Pricing that grows with your business',
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_pricing_section',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
                [
                    'key'        => 'pricing_section_subtitle',
                    'label'      => 'Subtitle',
                    'name'       => 'pricing_section_subtitle',
                    'type'       => 'textarea',
                    'default'    => 'No setup fees. No long contracts. Scale up or down anytime.',
                    'rows'       => 2,
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_pricing_section',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 25,
        ]
    );

    // Features Section Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_features',
            'title'    => 'Features Section',
            'fields'   => [
                [
                    'key'      => 'enable_features',
                    'label'    => 'Enable this section',
                    'name'     => 'enable_features',
                    'type'     => 'true_false',
                    'default'  => 1,
                ],
                [
                    'key'        => 'features_title',
                    'label'      => 'Section Title',
                    'name'       => 'features_title',
                    'type'       => 'text',
                    'default'    => 'What you get with JobCapturePro',
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_features',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
                [
                    'key'        => 'features_subtitle',
                    'label'      => 'Subtitle',
                    'name'       => 'features_subtitle',
                    'type'       => 'textarea',
                    'default'    => 'Everything your business needs to turn job photos into business results.',
                    'rows'       => 2,
                    'conditional_logic' => [
                        [
                            [
                                'field'    => 'enable_features',
                                'operator' => '==',
                                'value'    => '1',
                            ],
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 22,
        ]
    );

    // Footer Settings Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_footer',
            'title'    => 'Footer Settings',
            'fields'   => [
                [
                    'key'        => 'footer_tagline',
                    'label'      => 'Footer Tagline',
                    'name'       => 'footer_tagline',
                    'type'       => 'text',
                    'default'    => 'Turn real job photos into proof, visibility, reviews, and more jobs.',
                    'maxlength'  => 120,
                ],
                [
                    'key'      => 'footer_links',
                    'label'    => 'Footer Links',
                    'name'     => 'footer_links',
                    'type'     => 'repeater',
                    'layout'   => 'block',
                    'button_label' => 'Add Link',
                    'sub_fields' => [
                        [
                            'key'      => 'footer_link_text',
                            'label'    => 'Link Text',
                            'name'     => 'footer_link_text',
                            'type'     => 'text',
                            'required' => 1,
                        ],
                        [
                            'key'      => 'footer_link_url',
                            'label'    => 'Link URL',
                            'name'     => 'footer_link_url',
                            'type'     => 'url',
                            'required' => 1,
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 40,
        ]
    );

    // Section Visibility Field Group (Master Control)
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_visibility',
            'title'    => 'Section Visibility & Order',
            'fields'   => [
                [
                    'key'        => 'section_order_info',
                    'label'      => 'Section Display Order',
                    'name'       => 'section_order_info',
                    'type'       => 'message',
                    'message'    => 'Sections display in this order: Hero → How It Works → Features → Pricing → FAQ. Each section can be enabled/disabled individually above.',
                ],
                [
                    'key'      => 'show_social_proof',
                    'label'    => 'Show Social Proof Section',
                    'name'     => 'show_social_proof',
                    'type'     => 'true_false',
                    'default'  => 1,
                    'instructions' => 'Show customer testimonials and trust indicators',
                ],
                [
                    'key'      => 'show_final_cta',
                    'label'    => 'Show Final CTA Section',
                    'name'     => 'show_final_cta',
                    'type'     => 'true_false',
                    'default'  => 1,
                    'instructions' => 'Show the final call-to-action section at bottom',
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 5,
        ]
    );

    // General Settings Field Group
    acf_add_local_field_group(
        [
            'key'      => 'jcp_homepage_general',
            'title'    => 'General Settings',
            'fields'   => [
                [
                    'key'        => 'site_logo_url',
                    'label'      => 'Site Logo URL',
                    'name'       => 'site_logo_url',
                    'type'       => 'url',
                    'default'    => 'https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png',
                    'instructions' => 'Logo used in navigation and footer',
                ],
                [
                    'key'        => 'site_primary_color',
                    'label'      => 'Primary Brand Color',
                    'name'       => 'site_primary_color',
                    'type'       => 'color_picker',
                    'default'    => '#ff503e',
                    'instructions' => 'Used for buttons, links, and highlights',
                ],
                [
                    'key'        => 'site_description',
                    'label'      => 'Site Meta Description',
                    'name'       => 'site_description',
                    'type'       => 'textarea',
                    'default'    => 'JobCapturePro turns job photos into website updates, Google visibility, social posts, and review requests.',
                    'rows'       => 2,
                    'instructions' => 'Used for SEO meta tags',
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'jcp-homepage',
                    ],
                ],
            ],
            'menu_order' => 1,
        ]
    );

}

add_action( 'acf/init', 'jcp_core_register_acf_field_groups' );

/**
 * Get ACF Homepage Options with Safe Defaults
 *
 * @param string $field_name Field name to retrieve
 * @param mixed  $default Default value if field not set
 * @return mixed Field value or default
 */
function jcp_core_get_homepage_option( $field_name, $default = null ) {
    $value = get_field( $field_name, 'option' );
    
    if ( empty( $value ) && $default !== null ) {
        return $default;
    }
    
    return $value;
}

/**
 * Default "Why are you interested?" options for Early Access form (checkboxes).
 * Matches "What should this demo prove?" options on the Demo Survey.
 *
 * @return array List of [ 'label' => string, 'value' => string ]
 */
function jcp_core_early_access_default_why_interested_options(): array {
    return [
        [ 'label' => 'More inbound calls', 'value' => 'More inbound calls' ],
        [ 'label' => 'Better Google visibility', 'value' => 'Better Google visibility' ],
        [ 'label' => 'More customer reviews', 'value' => 'More customer reviews' ],
        [ 'label' => 'Stronger website trust', 'value' => 'Stronger website trust' ],
        [ 'label' => 'Less marketing busywork', 'value' => 'Less marketing busywork' ],
        [ 'label' => 'Showcase my work', 'value' => 'Showcase my work' ],
    ];
}

/**
 * Default "How did you hear about us?" options for Early Access form (exact labels for GHL).
 *
 * @return array List of [ 'label' => string, 'value' => string ]
 */
function jcp_core_early_access_default_referral_options(): array {
    $labels = [
        'Google Search',
        'Google Maps',
        'Facebook / Instagram',
        'Referral (another contractor)',
        'YouTube / Video',
        'Podcast',
        'Industry Event',
        'Other',
    ];
    $out = [];
    foreach ( $labels as $label ) {
        $out[] = [ 'label' => $label, 'value' => $label ];
    }
    return $out;
}

/**
 * Default "Business type" options for Early Access form (same as Demo Survey Step 1).
 * Grouped for optgroup rendering.
 *
 * @return array List of [ 'label' => string (optgroup), 'options' => [ [ 'value' => string, 'label' => string ], ... ] ]
 */
function jcp_core_early_access_default_business_type_options(): array {
    return [
        [
            'label'   => 'Building & mechanical',
            'options' => [
                [ 'value' => 'plumbing', 'label' => 'Plumbing' ],
                [ 'value' => 'hvac', 'label' => 'HVAC' ],
                [ 'value' => 'electrical', 'label' => 'Electrical' ],
                [ 'value' => 'roofing', 'label' => 'Roofing' ],
            ],
        ],
        [
            'label'   => 'General contracting & remodeling',
            'options' => [
                [ 'value' => 'general-contractor', 'label' => 'General Contractor' ],
                [ 'value' => 'handyman', 'label' => 'Handyman' ],
                [ 'value' => 'remodeling', 'label' => 'Remodeling / Renovation' ],
            ],
        ],
        [
            'label'   => 'Outdoor & property',
            'options' => [
                [ 'value' => 'landscaping', 'label' => 'Landscaping' ],
                [ 'value' => 'lawn-care', 'label' => 'Lawn care' ],
                [ 'value' => 'tree-service', 'label' => 'Tree service' ],
                [ 'value' => 'pest-control', 'label' => 'Pest control' ],
                [ 'value' => 'fencing', 'label' => 'Fencing' ],
            ],
        ],
        [
            'label'   => 'Cleaning & restoration',
            'options' => [
                [ 'value' => 'carpet-cleaning', 'label' => 'Carpet cleaning' ],
                [ 'value' => 'house-cleaning', 'label' => 'House cleaning' ],
                [ 'value' => 'pressure-washing', 'label' => 'Pressure washing' ],
                [ 'value' => 'painting', 'label' => 'Painting (interior / exterior)' ],
            ],
        ],
        [
            'label'   => 'Other trades',
            'options' => [
                [ 'value' => 'flooring', 'label' => 'Flooring' ],
                [ 'value' => 'windows-doors', 'label' => 'Windows & doors' ],
                [ 'value' => 'insulation', 'label' => 'Insulation' ],
                [ 'value' => 'garage-doors', 'label' => 'Garage doors' ],
                [ 'value' => 'pool-service', 'label' => 'Pool service' ],
                [ 'value' => 'moving-junk', 'label' => 'Moving / Junk removal' ],
            ],
        ],
        [
            'label'   => 'Other',
            'options' => [
                [ 'value' => 'other', 'label' => 'Other home service' ],
            ],
        ],
    ];
}

/**
 * Resolve business type value to display label for GHL Trade field.
 *
 * @param string $value Business type value (e.g. plumbing).
 * @return string Label (e.g. Plumbing) or value if not found.
 */
function jcp_core_early_access_business_type_label( string $value ): string {
    $value = trim( $value );
    if ( $value === '' ) {
        return '';
    }
    foreach ( jcp_core_early_access_default_business_type_options() as $group ) {
        foreach ( $group['options'] as $opt ) {
            if ( isset( $opt['value'] ) && (string) $opt['value'] === $value && isset( $opt['label'] ) ) {
                return (string) $opt['label'];
            }
        }
    }
    return $value;
}

/**
 * Get Early Access form config for frontend (hardcoded defaults; no backend options).
 *
 * @return array why_interested_options, referral_options, business_type_options, require_phone, require_company, success_redirect, success_message (+ rest_url set by enqueue)
 */
function jcp_core_get_early_access_form_config(): array {
    return [
        'why_interested_options'  => jcp_core_early_access_default_why_interested_options(),
        'referral_options'        => jcp_core_early_access_default_referral_options(),
        'business_type_options'   => jcp_core_early_access_default_business_type_options(),
        'require_phone'           => true,
        'require_company'         => true,
        'success_redirect'        => home_url( '/early-access-success/' ),
        'success_message'        => "Thanks for signing up. We'll be in touch soon with early-bird pricing and next steps.",
    ];
}
