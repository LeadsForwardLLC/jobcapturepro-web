<?php
/**
 * JCP page block registry.
 *
 * @package JCP_Core
 */

/**
 * All registered block types.
 *
 * @return array<string, array<string, mixed>>
 */
function jcp_block_registry(): array {
	return [
		'breadcrumb' => [
			'type'         => 'breadcrumb',
			'label'        => __( 'Breadcrumb', 'jcp-core' ),
			'description'  => __( 'Industries hub breadcrumb', 'jcp-core' ),
			'category'     => 'nav',
			'legacy_key'   => null,
			'doc_sections' => [],
			'page_kinds'   => [ 'industry' ],
		],
		'hero' => [
			'type'         => 'hero',
			'label'        => __( 'Hero', 'jcp-core' ),
			'description'  => __( 'H1, subheadline, CTAs, trust line', 'jcp-core' ),
			'category'     => 'header',
			'legacy_key'   => 'hero',
			'doc_sections' => [ 'HERO' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'what_it_is' => [
			'type'         => 'what_it_is',
			'label'        => __( 'What it is', 'jcp-core' ),
			'description'  => __( 'Intro with checklist columns', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'what_it_is',
			'doc_sections' => [ 'WHAT IT IS' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'core_mechanic' => [
			'type'         => 'core_mechanic',
			'label'        => __( 'Core mechanic', 'jcp-core' ),
			'description'  => __( 'Stat strip (1 photo / 4 channels)', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'core_mechanic',
			'doc_sections' => [ 'CORE MECHANIC' ],
			'page_kinds'   => [ 'industry', 'marketing' ],
		],
		'how_it_works' => [
			'type'         => 'how_it_works',
			'label'        => __( 'How it works', 'jcp-core' ),
			'description'  => __( 'Numbered timeline steps', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'how_it_works',
			'doc_sections' => [ 'HOW IT WORKS' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'check_ins' => [
			'type'         => 'check_ins',
			'label'        => __( 'Check-ins / features', 'jcp-core' ),
			'description'  => __( 'Feature cards grid', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'check_ins',
			'doc_sections' => [ 'CHECK-INS' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'problem' => [
			'type'         => 'problem',
			'label'        => __( 'Problem', 'jcp-core' ),
			'description'  => __( 'Pain point cards', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'problem',
			'doc_sections' => [ 'PROBLEM' ],
			'page_kinds'   => [ 'industry', 'marketing' ],
		],
		'benefits' => [
			'type'         => 'benefits',
			'label'        => __( 'Benefits', 'jcp-core' ),
			'description'  => __( 'Benefit cards', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'benefits',
			'doc_sections' => [ 'BENEFITS' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'differentiation' => [
			'type'         => 'differentiation',
			'label'        => __( 'Differentiation', 'jcp-core' ),
			'description'  => __( 'Body copy and bullets', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'differentiation',
			'doc_sections' => [ 'DIFFERENTIATION' ],
			'page_kinds'   => [ 'industry', 'marketing' ],
		],
		'who_its_for' => [
			'type'         => 'who_its_for',
			'label'        => __( 'Who it\'s for', 'jcp-core' ),
			'description'  => __( 'Audience cards', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'who_its_for',
			'doc_sections' => [ "WHO IT'S FOR", 'WHO ITS FOR' ],
			'page_kinds'   => [ 'industry', 'marketing' ],
		],
		'faq' => [
			'type'         => 'faq',
			'label'        => __( 'FAQ', 'jcp-core' ),
			'description'  => __( 'Questions and answers', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'faq',
			'doc_sections' => [ 'FAQ' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'final_cta' => [
			'type'         => 'final_cta',
			'label'        => __( 'Final CTA', 'jcp-core' ),
			'description'  => __( 'Bottom conversion band', 'jcp-core' ),
			'category'     => 'cta',
			'legacy_key'   => 'final_cta',
			'doc_sections' => [ 'FINAL CTA' ],
			'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
		],
		'cta_band' => [
			'type'         => 'cta_band',
			'label'        => __( 'CTA band', 'jcp-core' ),
			'description'  => __( 'Mid-page CTA strip', 'jcp-core' ),
			'category'     => 'cta',
			'legacy_key'   => 'cta_band_1',
			'doc_sections' => [],
			'page_kinds'   => [ 'referral', 'marketing' ],
		],
		'commission' => [
			'type'         => 'commission',
			'label'        => __( 'Commission table', 'jcp-core' ),
			'description'  => __( 'Referral commission tiers', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'commission',
			'doc_sections' => [],
			'page_kinds'   => [ 'referral' ],
		],
		'partners' => [
			'type'         => 'partners',
			'label'        => __( 'Partners', 'jcp-core' ),
			'description'  => __( 'Partner types grid', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'partners',
			'doc_sections' => [],
			'page_kinds'   => [ 'referral' ],
		],
		'share' => [
			'type'         => 'share',
			'label'        => __( 'Share', 'jcp-core' ),
			'description'  => __( 'Share / link copy section', 'jcp-core' ),
			'category'     => 'content',
			'legacy_key'   => 'share',
			'doc_sections' => [],
			'page_kinds'   => [ 'referral' ],
		],
	];
}

/**
 * @param string $type Block type.
 * @return array<string, mixed>|null
 */
function jcp_block_get( string $type ): ?array {
	$registry = jcp_block_registry();
	return $registry[ $type ] ?? null;
}

/**
 * Block types allowed for a page kind.
 *
 * @param string $page_kind industry|marketing|referral.
 * @return array<int, array<string, mixed>>
 */
function jcp_block_types_for_kind( string $page_kind ): array {
	$out = [];
	foreach ( jcp_block_registry() as $block ) {
		$kinds = $block['page_kinds'] ?? [];
		if ( in_array( $page_kind, $kinds, true ) ) {
			$out[] = $block;
		}
	}
	return $out;
}

/**
 * Map doc section header to block type.
 *
 * @param string $section Section header.
 */
function jcp_block_type_from_doc_section( string $section ): ?string {
	$upper = strtoupper( str_replace( '’', "'", trim( $section ) ) );
	foreach ( jcp_block_registry() as $block ) {
		$sections = $block['doc_sections'] ?? [];
		foreach ( $sections as $doc ) {
			if ( strtoupper( $doc ) === $upper ) {
				return (string) $block['type'];
			}
		}
	}
	return null;
}
